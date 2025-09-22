<?php

namespace App\Filament\Resources\PastoralReminderResource\Widgets;

use App\Models\PastoralReminder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class PastoralReminderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $dueToday = PastoralReminder::active()->dueToday()->count();
        $dueThisWeek = PastoralReminder::active()->dueThisWeek()->count();
        $totalActive = PastoralReminder::active()->count();
        $birthdaysThisMonth = PastoralReminder::active()
            ->byType('birthday')
            ->whereMonth('reminder_date', now()->month)
            ->count();

        return [
            Stat::make('Due Today', $dueToday)
                ->description('Reminders to send today')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color($dueToday > 0 ? 'warning' : 'success'),

            Stat::make('Due This Week', $dueThisWeek)
                ->description('Reminders for next 7 days')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($dueThisWeek > 5 ? 'warning' : 'primary'),

            Stat::make('Total Active', $totalActive)
                ->description('Active pastoral reminders')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Birthdays This Month', $birthdaysThisMonth)
                ->description('Birthday celebrations')
                ->descriptionIcon('heroicon-m-gift')
                ->color('info'),
        ];
    }
}
