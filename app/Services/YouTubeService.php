<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class YouTubeService
{
    protected string $apiKey;
    protected string $channelId;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
        $this->channelId = config('services.youtube.channel_id');
    }

    /**
     * Get currently live stream from the channel
     */
    public function getCurrentLiveStream(): ?array
    {
        try {
            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $this->channelId,
                'eventType' => 'live',
                'type' => 'video',
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data['items'])) {
                    $video = $data['items'][0];
                    $videoId = $video['id']['videoId'];

                    return [
                        'video_id' => $videoId,
                        'url' => "https://www.youtube.com/watch?v={$videoId}",
                        'title' => $video['snippet']['title'],
                        'description' => $video['snippet']['description'],
                        'thumbnail' => $video['snippet']['thumbnails']['high']['url'] ?? null,
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('YouTube API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get upcoming live streams
     */
    public function getUpcomingLiveStreams(): array
    {
        try {
            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $this->channelId,
                'eventType' => 'upcoming',
                'type' => 'video',
                'maxResults' => 10,
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $streams = [];

                foreach ($data['items'] ?? [] as $video) {
                    $videoId = $video['id']['videoId'];
                    $streams[] = [
                        'video_id' => $videoId,
                        'url' => "https://www.youtube.com/watch?v={$videoId}",
                        'title' => $video['snippet']['title'],
                        'description' => $video['snippet']['description'],
                        'thumbnail' => $video['snippet']['thumbnails']['high']['url'] ?? null,
                        'scheduled_start' => $video['snippet']['publishedAt'] ?? null,
                    ];
                }

                return $streams;
            }

            return [];
        } catch (\Exception $e) {
            Log::error('YouTube API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get live stream scheduled for a specific date/time
     */
    public function getLiveStreamForDateTime(\DateTime $targetDateTime): ?array
    {
        $upcomingStreams = $this->getUpcomingLiveStreams();

        foreach ($upcomingStreams as $stream) {
            if ($stream['scheduled_start']) {
                $scheduledTime = new \DateTime($stream['scheduled_start']);

                // Check if scheduled within 30 minutes of target time
                $timeDiff = abs($scheduledTime->getTimestamp() - $targetDateTime->getTimestamp());
                if ($timeDiff <= 1800) { // 30 minutes = 1800 seconds
                    return $stream;
                }
            }
        }

        return null;
    }

    /**
     * Cache the current live stream URL
     */
    public function cacheLiveStream(): ?string
    {
        $stream = $this->getCurrentLiveStream();

        if ($stream) {
            Cache::put('youtube_live_stream', $stream, now()->addHours(4));
            return $stream['url'];
        }

        return null;
    }

    /**
     * Get cached live stream
     */
    public function getCachedLiveStream(): ?array
    {
        return Cache::get('youtube_live_stream');
    }
}
