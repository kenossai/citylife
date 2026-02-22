<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Event;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\ContactSubmission;
use App\Models\VolunteerApplication;
use App\Models\TeachingSeries;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class OverviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return Cache::remember('widget.overview_stats', now()->addMinutes(10), function () {
            // Consolidate into fewer queries
            $memberStats = Member::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as recent_count
            ", [now()->subDays(30)->toDateTimeString()])->first();

            $enrollmentStats = CourseEnrollment::selectRaw("
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count
            ")->first();

            $eventStats = Event::where('is_published', true)->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN start_date >= NOW() THEN 1 ELSE 0 END) as upcoming
            ")->first();

            $totalMembers = (int) $memberStats->total;
            $activeMembersCount = (int) $memberStats->active_count;
            $recentMembersCount = (int) $memberStats->recent_count;
            $activeCourseEnrollments = (int) $enrollmentStats->active_count;
            $completedCourseEnrollments = (int) $enrollmentStats->completed_count;
            $upcomingEvents = (int) $eventStats->upcoming;
            $totalEvents = (int) $eventStats->total;
            $unreadMessages = ContactSubmission::where('status', 'new')->count();
            $pendingVolunteers = VolunteerApplication::where('status', 'pending')->count();

            return [
                Stat::make('Total Members', $totalMembers)
                    ->description($activeMembersCount . ' active members')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                    ])
                    ->url(route('filament.admin.resources.members.index')),

                Stat::make('New Members (30 days)', $recentMembersCount)
                    ->description('Recent registrations')
                    ->descriptionIcon('heroicon-m-user-plus')
                    ->color($recentMembersCount > 10 ? 'success' : 'warning')
                    ->url(route('filament.admin.resources.members.index')),

                Stat::make('Course Enrollments', $activeCourseEnrollments)
                    ->description($completedCourseEnrollments . ' completed')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->color('info')
                    ->url(route('filament.admin.resources.course-enrollments.index')),

                Stat::make('Upcoming Events', $upcomingEvents)
                    ->description($totalEvents . ' total events')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color($upcomingEvents > 0 ? 'primary' : 'gray')
                    ->url(route('filament.admin.resources.events.index')),

                Stat::make('Unread Messages', $unreadMessages)
                    ->description('Need attention')
                    ->descriptionIcon('heroicon-m-envelope')
                    ->color($unreadMessages > 0 ? 'danger' : 'success')
                    ->url(route('filament.admin.resources.mail-managers.index')),

                Stat::make('Pending Volunteers', $pendingVolunteers)
                    ->description('Applications to review')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color($pendingVolunteers > 0 ? 'warning' : 'success')
                    ->url(route('filament.admin.resources.volunteer-applications.index')),
            ];
        });
    }
}
