<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OverviewStatsWidget;
use App\Filament\Widgets\MemberAnalyticsWidget;
use App\Filament\Widgets\CourseStatsWidget;
use App\Filament\Widgets\CourseAnalyticsWidget;
use App\Filament\Widgets\EventAnalyticsWidget;
use App\Filament\Widgets\CommunicationStatsWidget;
use App\Filament\Widgets\ProgressTrackingWidget;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\GiftAidStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament-panels::pages.dashboard';

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 4,
            'lg' => 6,
            'xl' => 12,
            '2xl' => 12,
        ];
    }

    
    public function getWidgets(): array
    {
        return [
            OverviewStatsWidget::class,
            CourseStatsWidget::class,
            CommunicationStatsWidget::class,
            GiftAidStatsWidget::class,
            MemberAnalyticsWidget::class,
            CourseAnalyticsWidget::class,
            EventAnalyticsWidget::class,
            ProgressTrackingWidget::class,
            RecentActivityWidget::class,
        ];
    }

    public function getTitle(): string
    {
        return 'CityLife Admin Dashboard';
    }

    protected function getHeaderActions(): array
    {
        return [
            // You can add actions here if needed
        ];
    }
}
