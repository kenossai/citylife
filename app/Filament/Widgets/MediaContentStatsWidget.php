<?php

namespace App\Filament\Widgets;

use App\Models\TeachingSeries;
use App\Models\CityLifeTalkTime;
use App\Models\MediaContent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MediaContentStatsWidget extends BaseWidget
{
    protected static ?int $sort = 9;

    protected function getStats(): array
    {
        // Teaching Series metrics
        $totalSeries = TeachingSeries::count();
        $publishedSeries = TeachingSeries::where('is_published', true)->count();
        $featuredSeries = TeachingSeries::where('is_featured', true)->count();
        $totalSeriesViews = TeachingSeries::sum('views_count') ?: 0;
        
        // CityLife TalkTime metrics
        $totalEpisodes = CityLifeTalkTime::count();
        $publishedEpisodes = CityLifeTalkTime::where('is_published', true)->count();
        $featuredEpisodes = CityLifeTalkTime::where('is_featured', true)->count();
        
        // Media Content metrics
        $totalMediaContent = MediaContent::count();
        $publishedMediaContent = MediaContent::where('is_published', true)->count();
        $totalMediaViews = MediaContent::sum('views_count') ?: 0;
        $totalDownloads = MediaContent::sum('downloads_count') ?: 0;
        
        // Recent content (last 30 days)
        $recentSeries = TeachingSeries::where('created_at', '>=', now()->subDays(30))->count();
        $recentEpisodes = CityLifeTalkTime::where('created_at', '>=', now()->subDays(30))->count();

        return [
            Stat::make('Teaching Series', $totalSeries)
                ->description($publishedSeries . ' published')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color($publishedSeries > 0 ? 'success' : 'gray')
                ->url(route('filament.admin.resources.teaching-series.index')),
                
            Stat::make('Total Series Views', number_format($totalSeriesViews))
                ->description('Across all series')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
                
            Stat::make('TalkTime Episodes', $totalEpisodes)
                ->description($publishedEpisodes . ' published')
                ->descriptionIcon('heroicon-m-microphone')
                ->color($publishedEpisodes > 0 ? 'success' : 'gray')
                ->url(route('filament.admin.resources.city-life-talk-times.index')),
                
            Stat::make('Media Content', $totalMediaContent)
                ->description($publishedMediaContent . ' published')
                ->descriptionIcon('heroicon-m-play-circle')
                ->color($publishedMediaContent > 0 ? 'success' : 'gray')
                ->url(route('filament.admin.resources.media-contents.index')),
                
            Stat::make('Media Views', number_format($totalMediaViews))
                ->description(number_format($totalDownloads) . ' downloads')
                ->descriptionIcon('heroicon-m-chart-bar-square')
                ->color('primary'),
                
            Stat::make('Recent Content (30 days)', $recentSeries + $recentEpisodes)
                ->description($recentSeries . ' series, ' . $recentEpisodes . ' episodes')
                ->descriptionIcon('heroicon-m-clock')
                ->color(($recentSeries + $recentEpisodes) > 0 ? 'warning' : 'gray'),
        ];
    }
}
