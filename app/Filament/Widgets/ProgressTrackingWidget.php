<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Member;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ProgressTrackingWidget extends ChartWidget
{
    protected static ?string $heading = 'Learning Progress Overview';
    protected static ?int $sort = 8;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        return Cache::remember('widget.progress_tracking', now()->addMinutes(15), function () {
            // Get enrollment status distribution
            $statusCounts = CourseEnrollment::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Ensure all statuses are represented
            $statuses = ['active', 'completed', 'withdrawn', 'suspended'];
            $statusData = [];
            $statusLabels = [];

            foreach ($statuses as $status) {
                $count = $statusCounts[$status] ?? 0;
                $statusData[] = $count;
                $statusLabels[] = ucfirst($status);
            }

            // 1 query with conditional aggregation instead of 5 separate queries
            $progress = CourseEnrollment::where('status', 'active')
                ->selectRaw("
                    SUM(CASE WHEN progress_percentage BETWEEN 0 AND 25 THEN 1 ELSE 0 END) as `0_25`,
                    SUM(CASE WHEN progress_percentage BETWEEN 26 AND 50 THEN 1 ELSE 0 END) as `26_50`,
                    SUM(CASE WHEN progress_percentage BETWEEN 51 AND 75 THEN 1 ELSE 0 END) as `51_75`,
                    SUM(CASE WHEN progress_percentage BETWEEN 76 AND 99 THEN 1 ELSE 0 END) as `76_99`,
                    SUM(CASE WHEN progress_percentage = 100 THEN 1 ELSE 0 END) as `at_100`
                ")
                ->first();

            $progressRanges = [
                '0-25%'  => (int) ($progress->{'0_25'} ?? 0),
                '26-50%' => (int) ($progress->{'26_50'} ?? 0),
                '51-75%' => (int) ($progress->{'51_75'} ?? 0),
                '76-99%' => (int) ($progress->{'76_99'} ?? 0),
                '100%'   => (int) ($progress->at_100 ?? 0),
            ];

            return [
                'datasets' => [
                    [
                        'label' => 'Enrollment Status',
                        'data' => $statusData,
                        'backgroundColor' => [
                            '#3B82F6', // Active - Blue
                            '#10B981', // Completed - Green
                            '#EF4444', // Withdrawn - Red
                            '#F59E0B', // Suspended - Yellow
                        ],
                        'borderColor' => '#ffffff',
                        'borderWidth' => 2,
                    ],
                ],
                'labels' => $statusLabels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ": " + context.parsed + " (" + percentage + "%)";
                        }'
                    ]
                ]
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'aspectRatio' => 1.2,
        ];
    }
}
