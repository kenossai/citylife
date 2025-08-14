<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Filament\Widgets\ChartWidget;

class CourseAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Course Performance Analytics';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    
    protected function getData(): array
    {
        // Get top 10 courses by enrollment
        $courses = Course::withCount(['enrollments' => function ($query) {
            $query->where('status', 'active');
        }])
        ->having('enrollments_count', '>', 0)
        ->orderBy('enrollments_count', 'desc')
        ->limit(10)
        ->get();

        $labels = $courses->pluck('title')->map(function ($title) {
            return strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title;
        })->toArray();
        
        $enrollmentData = $courses->pluck('enrollments_count')->toArray();
        
        // Get completion rates for these courses
        $completionData = $courses->map(function ($course) {
            $totalEnrollments = $course->enrollments()->count();
            $completedEnrollments = $course->enrollments()->where('status', 'completed')->count();
            return $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;
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
    }

    protected function getType(): string
    {
        return 'doughnut';
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
                            return context.label + ": " + context.parsed + " students";
                        }'
                    ]
                ]
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
