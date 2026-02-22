<?php

namespace App\Filament\Widgets;

use App\Models\NewsletterSubscriber;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class NewsletterStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return Cache::remember('widget.newsletter_stats', now()->addMinutes(15), function () {
            // 2 queries instead of 4 (NewsletterSubscriber + Member)
            $subStats = NewsletterSubscriber::active()->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN gdpr_consent = 1 THEN 1 ELSE 0 END) as gdpr_count,
                SUM(CASE WHEN MONTH(subscribed_at) = ? AND YEAR(subscribed_at) = ? THEN 1 ELSE 0 END) as month_count
            ", [now()->month, now()->year])->first();

            $totalSubscribers = (int) ($subStats->total ?? 0);
            $gdprCompliantSubscribers = (int) ($subStats->gdpr_count ?? 0);
            $thisMonthSubscribers = (int) ($subStats->month_count ?? 0);
            $membersWithNewsletter = Member::where('newsletter_consent', true)->count();

            return [
                Stat::make('Active Newsletter Subscribers', $totalSubscribers)
                    ->description('Total active subscribers')
                    ->descriptionIcon('heroicon-m-envelope')
                    ->color('success'),

                Stat::make('GDPR Compliant', $gdprCompliantSubscribers)
                    ->description('Subscribers with GDPR consent')
                    ->descriptionIcon('heroicon-m-shield-check')
                    ->color('primary'),

                Stat::make('This Month', $thisMonthSubscribers)
                    ->description('New subscribers this month')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Member Subscribers', $membersWithNewsletter)
                    ->description('Church members subscribed')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('warning'),
            ];
        });
    }
}
