<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\YouTubeService;
use App\Models\TeachingSeries;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckSundayLiveStream extends Command
{
    protected $signature = 'stream:check-sunday';
    protected $description = 'Check for Sunday service live stream and update teaching series';

    public function handle(YouTubeService $youtubeService)
    {
        $now = Carbon::now();
        
        // Only run on Sundays between 10:45 AM and 12:00 PM
        if (!$now->isSunday()) {
            $this->info('Not Sunday - skipping live stream check');
            return 0;
        }

        $currentHour = $now->hour;
        $currentMinute = $now->minute;
        
        // Check if we're within the service time window (10:45 - 12:00)
        if ($currentHour < 10 || ($currentHour === 10 && $currentMinute < 45) || $currentHour >= 12) {
            $this->info('Outside service time window - skipping');
            return 0;
        }

        $this->info('Checking for live stream...');

        // First, check for currently live stream
        $liveStream = $youtubeService->getCurrentLiveStream();
        
        if (!$liveStream) {
            $this->info('No live stream found, checking upcoming streams...');
            
            // Check for upcoming stream scheduled around 11:15 AM
            $targetTime = Carbon::today()->setTime(11, 15);
            $liveStream = $youtubeService->getLiveStreamForDateTime($targetTime);
        }

        if ($liveStream) {
            $this->info("Found live stream: {$liveStream['title']}");
            $this->info("URL: {$liveStream['url']}");

            // Find or create today's teaching series
            $today = Carbon::today();
            $teachingSeries = TeachingSeries::whereDate('series_date', $today)->first();

            if ($teachingSeries) {
                // Update existing series with live stream URL
                $teachingSeries->update([
                    'video_url' => $liveStream['url'],
                    'youtube_live_url' => $liveStream['url'],
                ]);
                
                $this->info("Updated teaching series: {$teachingSeries->title}");
                Log::info("Auto-updated teaching series with live stream", [
                    'series_id' => $teachingSeries->id,
                    'video_url' => $liveStream['url'],
                ]);
            } else {
                // Create new teaching series for today's service
                $teachingSeries = TeachingSeries::create([
                    'title' => 'Sunday Service - ' . $today->format('F j, Y'),
                    'slug' => 'sunday-service-' . $today->format('Y-m-d'),
                    'video_url' => $liveStream['url'],
                    'youtube_live_url' => $liveStream['url'],
                    'series_date' => $today,
                    'category' => 'Sermons',
                    'is_published' => false, // Admin can publish later
                    'summary' => 'Live Sunday service',
                ]);
                
                $this->info("Created new teaching series: {$teachingSeries->title}");
                Log::info("Auto-created teaching series with live stream", [
                    'series_id' => $teachingSeries->id,
                    'video_url' => $liveStream['url'],
                ]);
            }

            // Cache the live stream for homepage/banner
            $youtubeService->cacheLiveStream();
            
            return 0;
        }

        $this->warn('No live stream found for today');
        return 1;
    }
}
