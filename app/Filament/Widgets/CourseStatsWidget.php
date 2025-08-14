<?php

namespace App\Filament\Widgets;

use App\Models\CourseEnrollment;
use App\Models\LessonAttendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $totalEnrollments = CourseEnrollment::count();
        $activeEnrollments = CourseEnrollment::where('status', 'active')->count();
        $completedEnrollments = CourseEnrollment::where('status', 'completed')->count();
        $certificatesIssued = CourseEnrollment::where('certificate_issued', true)->count();
        
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;
        $certificationRate = $completedEnrollments > 0 ? round(($certificatesIssued / $completedEnrollments) * 100, 1) : 0;
        
        // Calculate average attendance rate
        $totalAttendance = LessonAttendance::count();
        $attendedCount = LessonAttendance::where('attended', true)->count();
        $attendanceRate = $totalAttendance > 0 ? round(($attendedCount / $totalAttendance) * 100, 1) : 0;
        
        // Recent enrollments (last 7 days)
        $recentEnrollments = CourseEnrollment::where('enrollment_date', '>=', now()->subDays(7))->count();

        return [
            Stat::make('Total Enrollments', $totalEnrollments)
                ->description($activeEnrollments . ' currently active')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->url(route('filament.admin.resources.course-enrollments.index')),
                
            Stat::make('Completion Rate', $completionRate . '%')
                ->description($completedEnrollments . ' completed courses')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($completionRate >= 80 ? 'success' : ($completionRate >= 60 ? 'warning' : 'danger'))
                ->url(route('filament.admin.resources.course-enrollments.index')),
                
            Stat::make('Certificates Issued', $certificatesIssued)
                ->description($certificationRate . '% of completions')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->url(route('filament.admin.resources.course-enrollments.index')),
                
            Stat::make('Attendance Rate', $attendanceRate . '%')
                ->description($attendedCount . '/' . $totalAttendance . ' sessions')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($attendanceRate >= 85 ? 'success' : ($attendanceRate >= 70 ? 'warning' : 'danger'))
                ->url(route('filament.admin.resources.attendances.index')),
        ];
    }
}
