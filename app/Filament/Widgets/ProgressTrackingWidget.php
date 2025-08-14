<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Member;
use Filament\Widgets\ChartWidget;

class ProgressTrackingWidget extends ChartWidget
{
    protected static ?string $heading = 'Learning Progress Overview';
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = 'full';
    
    protected function getData(): array
    {
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
        
        // Get progress distribution for active enrollments
        $progressRanges = [
            '0-25%' => CourseEnrollment::where('status', 'active')->whereBetween('progress_percentage', [0, 25])->count(),
            '26-50%' => CourseEnrollment::where('status', 'active')->whereBetween('progress_percentage', [26, 50])->count(),
            '51-75%' => CourseEnrollment::where('status', 'active')->whereBetween('progress_percentage', [51, 75])->count(),
            '76-99%' => CourseEnrollment::where('status', 'active')->whereBetween('progress_percentage', [76, 99])->count(),
            '100%' => CourseEnrollment::where('status', 'active')->where('progress_percentage', 100)->count(),
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
        ];
    }
}
