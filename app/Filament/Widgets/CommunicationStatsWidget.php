<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use App\Models\VolunteerApplication;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommunicationStatsWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        // Contact submissions analysis
        $totalMessages = ContactSubmission::count();
        $newMessages = ContactSubmission::where('status', 'new')->count();
        $respondedMessages = ContactSubmission::where('status', 'responded')->count();
        $responseRate = $totalMessages > 0 ? round(($respondedMessages / $totalMessages) * 100, 1) : 0;
        
        // Volunteer applications analysis
        $totalVolunteers = VolunteerApplication::count();
        $pendingVolunteers = VolunteerApplication::where('status', 'pending')->count();
        $approvedVolunteers = VolunteerApplication::where('status', 'approved')->count();
        $approvalRate = $totalVolunteers > 0 ? round(($approvedVolunteers / $totalVolunteers) * 100, 1) : 0;
        
        // Recent activity (last 7 days)
        $recentMessages = ContactSubmission::where('created_at', '>=', now()->subDays(7))->count();
        $recentVolunteers = VolunteerApplication::where('created_at', '>=', now()->subDays(7))->count();
        
        // Member engagement metrics
        $activeMemberPercentage = Member::count() > 0 ? round((Member::where('is_active', true)->count() / Member::count()) * 100, 1) : 0;

        return [
            Stat::make('Total Messages', $totalMessages)
                ->description($newMessages . ' unread messages')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($newMessages > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.mail-managers.index')),
                
            Stat::make('Response Rate', $responseRate . '%')
                ->description($respondedMessages . ' messages responded')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($responseRate >= 80 ? 'success' : ($responseRate >= 60 ? 'warning' : 'danger'))
                ->url(route('filament.admin.resources.mail-managers.index')),
                
            Stat::make('Volunteer Applications', $totalVolunteers)
                ->description($pendingVolunteers . ' pending review')
                ->descriptionIcon('heroicon-m-user-group')
                ->color($pendingVolunteers > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.volunteer-applications.index')),
                
            Stat::make('Volunteer Approval Rate', $approvalRate . '%')
                ->description($approvedVolunteers . ' approved volunteers')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($approvalRate >= 70 ? 'success' : ($approvalRate >= 50 ? 'warning' : 'danger'))
                ->url(route('filament.admin.resources.volunteer-applications.index')),
                
            Stat::make('Recent Activity (7 days)', $recentMessages + $recentVolunteers)
                ->description($recentMessages . ' messages, ' . $recentVolunteers . ' volunteers')
                ->descriptionIcon('heroicon-m-bell')
                ->color(($recentMessages + $recentVolunteers) > 0 ? 'primary' : 'gray'),
                
            Stat::make('Active Members', $activeMemberPercentage . '%')
                ->description(Member::where('is_active', true)->count() . ' active members')
                ->descriptionIcon('heroicon-m-users')
                ->color($activeMemberPercentage >= 80 ? 'success' : ($activeMemberPercentage >= 60 ? 'warning' : 'danger'))
                ->url(route('filament.admin.resources.members.index')),
        ];
    }
}
