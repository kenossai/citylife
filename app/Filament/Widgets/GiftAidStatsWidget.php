<?php

namespace App\Filament\Widgets;

use App\Models\GiftAidDeclaration;
use App\Models\Giving;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Cache;

class GiftAidStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return Cache::remember('widget.gift_aid_stats', now()->addMinutes(15), function () {
            $totalDeclarations = GiftAidDeclaration::where('is_active', true)->count();

            // 1 query instead of 2 for Giving
            $givingStats = Giving::where('gift_aid_eligible', true)->selectRaw("
                SUM(amount) as total_amount,
                SUM(CASE WHEN MONTH(given_date) = ? AND YEAR(given_date) = ? THEN amount ELSE 0 END) as month_amount
            ", [now()->month, now()->year])->first();

            $totalGiftAidEligible = (float) ($givingStats->total_amount ?? 0);
            $potentialGiftAid = $totalGiftAidEligible * 0.25;
            $thisMonthEligible = (float) ($givingStats->month_amount ?? 0);

            return [
                Stat::make('Active Gift Aid Declarations', $totalDeclarations)
                    ->description('Current active declarations')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('success'),

                Stat::make('Gift Aid Eligible Amount', '£' . number_format($totalGiftAidEligible, 2))
                    ->description('Total eligible donations')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color('primary'),

                Stat::make('Potential Gift Aid', '£' . number_format($potentialGiftAid, 2))
                    ->description('25% reclaimable from HMRC')
                    ->descriptionIcon('heroicon-m-calculator')
                    ->color('warning'),

                Stat::make('This Month Eligible', '£' . number_format($thisMonthEligible, 2))
                    ->description('Gift Aid eligible this month')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),
            ];
        });
    }
}
