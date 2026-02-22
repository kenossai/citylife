<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class CourseAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Course Performance Analytics';
    protected static ?int $sort = 9;
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        return Cache::remember('widget.course_analytics', now()->addMinutes(15), function () {
            // Fix N+1: fetch total and completed counts in the same query
            $courses = Course::withCount([
                'enrollments as active_enrollments_count' => function ($query) {
                    $query->where('status', 'active');
                },
                'enrollments as total_enrollments_count',
                'enrollments as completed_enrollments_count' => function ($query) {
                    $query->where('status', 'completed');
                },
            ])
            ->having('active_enrollments_count', '>', 0)
            ->orderBy('active_enrollments_count', 'desc')
            ->limit(10)
            ->get();

            $labels = $courses->pluck('title')->map(function ($title) {
                return strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title;
            })->toArray();

            $enrollmentData = $courses->pluck('active_enrollments_count')->toArray();

            // No extra queries — use already-loaded counts
            $completionData = $courses->map(function ($course) {
                $total = (int) $course->total_enrollments_count;
                $completed = (int) $course->completed_enrollments_count;
                return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
            })->toArray();

            return [
                'datasets' => [
                    [
                        'label' => 'Active Enrollments',
                        'data' => $enrollmentData,
                        'backgroundColor' => [
                            '#EF4444', '#F97316', '#F59E0B', '#EAB308',
                            '#84CC16', '#22C55E', '#10B981', '#14B8A6',
                            '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1'
                        ],
                        'borderColor' => '#ffffff',
                        'borderWidth' => 2,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'aspectRatio' => 1.2,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.parsed + " students";
                        }'
                    ]
                ]
            ],
        ];
    }
}
