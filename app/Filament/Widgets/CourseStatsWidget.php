<?php

namespace App\Filament\Widgets;

use App\Models\CourseEnrollment;
use App\Models\LessonAttendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class CourseStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return Cache::remember('widget.course_stats', now()->addMinutes(10), function () {
            // 2 queries instead of 7
            $enrollStats = CourseEnrollment::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN certificate_issued = 1 THEN 1 ELSE 0 END) as certificates,
                SUM(CASE WHEN enrollment_date >= ? THEN 1 ELSE 0 END) as recent_count
            ", [now()->subDays(7)->toDateString()])->first();

            $attendStats = LessonAttendance::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN attended = 1 THEN 1 ELSE 0 END) as attended_count
            ")->first();

            $totalEnrollments = (int) $enrollStats->total;
            $activeEnrollments = (int) $enrollStats->active_count;
            $completedEnrollments = (int) $enrollStats->completed_count;
            $certificatesIssued = (int) $enrollStats->certificates;
            $recentEnrollments = (int) $enrollStats->recent_count;
            $totalAttendance = (int) $attendStats->total;
            $attendedCount = (int) $attendStats->attended_count;

            $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;
            $certificationRate = $completedEnrollments > 0 ? round(($certificatesIssued / $completedEnrollments) * 100, 1) : 0;
            $attendanceRate = $totalAttendance > 0 ? round(($attendedCount / $totalAttendance) * 100, 1) : 0;

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
        });
    }
}
