<?php

namespace App\Observers;

use App\Models\News;
use App\Services\SocialMediaService;
use Illuminate\Support\Facades\Log;

class NewsObserver
{
    protected SocialMediaService $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService)
    {
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * Handle the News "created" event.
     */
    public function created(News $news): void
    {
        // Auto-post when a new announcement is created and published
        if ($news->is_published && $this->shouldAutoPost()) {
            $this->autoPostAnnouncement($news, 'created');
        }
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news): void
    {
        // Auto-post when an announcement is published for the first time
        if ($news->wasChanged('is_published') && $news->is_published && $this->shouldAutoPost()) {
            $this->autoPostAnnouncement($news, 'published');
        }

        // Re-post if significant changes are made to an already published announcement
        elseif ($news->is_published && $this->hasSignificantChanges($news) && $this->shouldAutoPost()) {
            $this->autoPostAnnouncement($news, 'updated');
        }
    }

    /**
     * Auto-post an announcement to social media
     */
    protected function autoPostAnnouncement(News $news, string $action): void
    {
        try {
            // Only auto-post if auto-posting is enabled in config
            if (!config('services.social_media.auto_post_announcements', false)) {
                return;
            }

            $platforms = $this->getEnabledPlatforms();

            if (empty($platforms)) {
                Log::info("No social media platforms enabled for auto-posting announcement: {$news->title}");
                return;
            }

            Log::info("Auto-posting announcement to social media: {$news->title}", [
                'news_id' => $news->id,
                'action' => $action,
                'platforms' => $platforms
            ]);

            $results = $this->socialMediaService->postAnnouncement($news, $platforms);

            // Log results
            foreach ($results as $platform => $result) {
                if ($result['success']) {
                    Log::info("Successfully posted announcement to {$platform}", [
                        'news_id' => $news->id,
                        'platform_post_id' => $result['post_id'] ?? null
                    ]);
                } else {
                    Log::error("Failed to post announcement to {$platform}", [
                        'news_id' => $news->id,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error("Error auto-posting announcement to social media", [
                'news_id' => $news->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Check if we should auto-post (avoid during seeders/imports)
     */
    protected function shouldAutoPost(): bool
    {
        // Don't auto-post during console commands (seeders, imports, etc.)
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return false;
        }

        // Don't auto-post if specifically disabled
        if (config('services.social_media.auto_post_enabled', true) === false) {
            return false;
        }

        return true;
    }

    /**
     * Check if the announcement has significant changes that warrant re-posting
     */
    protected function hasSignificantChanges(News $news): bool
    {
        $significantFields = [
            'title',
            'excerpt',
            'content',
            'author'
        ];

        foreach ($significantFields as $field) {
            if ($news->wasChanged($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get enabled social media platforms
     */
    protected function getEnabledPlatforms(): array
    {
        $platforms = ['facebook', 'twitter', 'instagram', 'linkedin'];
        $enabled = [];

        foreach ($platforms as $platform) {
            if (config("services.{$platform}.enabled", false)) {
                $enabled[] = $platform;
            }
        }

        return $enabled;
    }
}
