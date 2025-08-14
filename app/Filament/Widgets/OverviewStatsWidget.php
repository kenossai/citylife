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

class OverviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Get counts for various metrics
        $totalMembers = Member::count();
        $activeMembersCount = Member::where('is_active', true)->count();
        $recentMembersCount = Member::where('created_at', '>=', now()->subDays(30))->count();
        
        $totalCourses = Course::count();
        $activeCourseEnrollments = CourseEnrollment::where('status', 'active')->count();
        $completedCourseEnrollments = CourseEnrollment::where('status', 'completed')->count();
        
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->where('is_published', true)
            ->count();
        
        $totalEvents = Event::where('is_published', true)->count();
        
        $unreadMessages = ContactSubmission::where('status', 'new')->count();
        $pendingVolunteers = VolunteerApplication::where('status', 'pending')->count();
        
        $publishedSeries = TeachingSeries::where('is_published', true)->count();

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
    }
}
