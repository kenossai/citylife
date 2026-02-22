<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use App\Models\VolunteerApplication;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class CommunicationStatsWidget extends BaseWidget
{
    protected static ?int $sort = 7;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return Cache::remember('widget.communication_stats', now()->addMinutes(10), function () {
            // 3 queries instead of 10
            $contactStats = ContactSubmission::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_count,
                SUM(CASE WHEN status = 'responded' THEN 1 ELSE 0 END) as responded_count,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as recent_count
            ", [now()->subDays(7)->toDateTimeString()])->first();

            $volunteerStats = VolunteerApplication::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as recent_count
            ", [now()->subDays(7)->toDateTimeString()])->first();

            $memberStats = Member::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count
            ")->first();

            $totalMessages = (int) $contactStats->total;
            $newMessages = (int) $contactStats->new_count;
            $respondedMessages = (int) $contactStats->responded_count;
            $recentMessages = (int) $contactStats->recent_count;
            $totalVolunteers = (int) $volunteerStats->total;
            $pendingVolunteers = (int) $volunteerStats->pending_count;
            $approvedVolunteers = (int) $volunteerStats->approved_count;
            $recentVolunteers = (int) $volunteerStats->recent_count;
            $totalMembers = (int) $memberStats->total;
            $activeMembers = (int) $memberStats->active_count;

            $responseRate = $totalMessages > 0 ? round(($respondedMessages / $totalMessages) * 100, 1) : 0;
            $approvalRate = $totalVolunteers > 0 ? round(($approvedVolunteers / $totalVolunteers) * 100, 1) : 0;
            $activeMemberPercentage = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0;

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
                    ->description($activeMembers . ' active members')
                    ->descriptionIcon('heroicon-m-users')
                    ->color($activeMemberPercentage >= 80 ? 'success' : ($activeMemberPercentage >= 60 ? 'warning' : 'danger'))
                    ->url(route('filament.admin.resources.members.index')),
            ];
        });
    }
}
