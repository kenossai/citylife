<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\CourseEnrollment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MemberAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Member Registration Trends';
    protected static ?int $sort = 2;
    
    protected function getData(): array
    {
        // Get member registrations for the last 12 months
        $memberData = collect();
        $enrollmentData = collect();
        
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $memberCount = Member::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $enrollmentCount = CourseEnrollment::whereBetween('enrollment_date', [$startOfMonth, $endOfMonth])->count();
            
            $memberData->push($memberCount);
            $enrollmentData->push($enrollmentCount);
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
