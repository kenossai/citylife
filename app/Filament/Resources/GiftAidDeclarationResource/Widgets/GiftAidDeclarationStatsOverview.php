<?php

namespace App\Filament\Resources\GiftAidDeclarationResource\Widgets;

use App\Models\GiftAidDeclaration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GiftAidDeclarationStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Declarations', GiftAidDeclaration::count())
                ->description('All time declarations')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Active Declarations', GiftAidDeclaration::where('is_active', true)->count())
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('This Month', GiftAidDeclaration::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count())
                ->description('New declarations this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('This Year', GiftAidDeclaration::whereYear('created_at', now()->year)->count())
                ->description('Total this year')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
