<?php

namespace App\Filament\Widgets;

use App\Models\GiftAidDeclaration;
use App\Models\Giving;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class GiftAidStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        // Get totals
        $totalDeclarations = GiftAidDeclaration::where('is_active', true)->count();
        $totalGiftAidEligible = Giving::where('gift_aid_eligible', true)->sum('amount');
        $potentialGiftAid = $totalGiftAidEligible * 0.25;
        $thisMonthEligible = Giving::where('gift_aid_eligible', true)
            ->whereMonth('given_date', now()->month)
            ->whereYear('given_date', now()->year)
            ->sum('amount');

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
    }
}
