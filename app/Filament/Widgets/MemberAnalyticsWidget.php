<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\CourseEnrollment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MemberAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Member Registration Trends';
    protected static ?int $sort = 2;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        return Cache::remember('widget.member_analytics', now()->addMinutes(15), function () {
            return $this->buildChartData();
        });
    }

    private function buildChartData(): array
    {
        $since = now()->subMonths(11)->startOfMonth();

        // 2 queries instead of 24
        $memberRows = Member::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', $since)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $enrollmentRows = CourseEnrollment::selectRaw("DATE_FORMAT(enrollment_date, '%Y-%m') as month, COUNT(*) as count")
            ->where('enrollment_date', '>=', $since)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $memberData = collect();
        $enrollmentData = collect();

        for ($i = 11; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $memberData->push($memberRows->get($key, 0));
            $enrollmentData->push($enrollmentRows->get($key, 0));
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Members',
                    'data' => $memberData->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Course Enrollments',
                    'data' => $enrollmentData->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => collect(range(11, 0))->map(function ($i) {
                return now()->subMonths($i)->format('M Y');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'aspectRatio' => 1.2,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
        ];
    }
}
