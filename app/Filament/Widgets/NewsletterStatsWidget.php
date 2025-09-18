<?php

namespace App\Filament\Widgets;

use App\Models\NewsletterSubscriber;
use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsletterStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $totalSubscribers = NewsletterSubscriber::active()->count();
        $gdprCompliantSubscribers = NewsletterSubscriber::active()->gdprCompliant()->count();
        $thisMonthSubscribers = NewsletterSubscriber::active()
            ->whereMonth('subscribed_at', now()->month)
            ->whereYear('subscribed_at', now()->year)
            ->count();
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
    }
}
