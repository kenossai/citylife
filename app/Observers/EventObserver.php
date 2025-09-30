<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\SocialMediaService;
use Illuminate\Support\Facades\Log;

class EventObserver
{
    protected SocialMediaService $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService)
    {
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        // Auto-post when a new event is created and published
        if ($event->is_published && $this->shouldAutoPost()) {
            $this->autoPostEvent($event, 'created');
        }
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Auto-post when an event is published for the first time
        if ($event->wasChanged('is_published') && $event->is_published && $this->shouldAutoPost()) {
            $this->autoPostEvent($event, 'published');
        }

        // Re-post if significant changes are made to an already published event
        elseif ($event->is_published && $this->hasSignificantChanges($event) && $this->shouldAutoPost()) {
            $this->autoPostEvent($event, 'updated');
        }
    }

    /**
     * Auto-post an event to social media
     */
    protected function autoPostEvent(Event $event, string $action): void
    {
        try {
            // Only auto-post if auto-posting is enabled in config
            if (!config('services.social_media.auto_post_events', false)) {
                return;
            }

            $platforms = $this->getEnabledPlatforms();

            if (empty($platforms)) {
                Log::info("No social media platforms enabled for auto-posting event: {$event->title}");
                return;
            }

            Log::info("Auto-posting event to social media: {$event->title}", [
                'event_id' => $event->id,
                'action' => $action,
                'platforms' => $platforms
            ]);

            $results = $this->socialMediaService->postEvent($event, $platforms);

            // Log results
            foreach ($results as $platform => $result) {
                if ($result['success']) {
                    Log::info("Successfully posted event to {$platform}", [
                        'event_id' => $event->id,
                        'platform_post_id' => $result['post_id'] ?? null
                    ]);
                } else {
                    Log::error("Failed to post event to {$platform}", [
                        'event_id' => $event->id,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error("Error auto-posting event to social media", [
                'event_id' => $event->id,
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
     * Check if the event has significant changes that warrant re-posting
     */
    protected function hasSignificantChanges(Event $event): bool
    {
        $significantFields = [
            'title',
            'description',
            'start_date',
            'end_date',
            'location',
            'event_anchor',
            'guest_speaker'
        ];

        foreach ($significantFields as $field) {
            if ($event->wasChanged($field)) {
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
