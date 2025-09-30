<?php

namespace App\Services;

use App\Models\Event;
use App\Models\News;
use App\Models\SocialMediaPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SocialMediaService
{
    protected $platforms = ['facebook', 'twitter', 'instagram', 'linkedin'];

    /**
     * Auto-post an event to configured social media platforms
     */
    public function postEvent(Event $event, array $platforms = null): array
    {
        $platforms = $platforms ?? $this->getEnabledPlatforms();
        $results = [];

        $content = $this->formatEventContent($event);

        foreach ($platforms as $platform) {
            try {
                $result = $this->postToPlatform($platform, $content, $event->featured_image);

                // Log the post in our database
                SocialMediaPost::create([
                    'content_type' => 'event',
                    'content_id' => $event->id,
                    'platform' => $platform,
                    'content' => $content['text'],
                    'status' => $result['success'] ? 'published' : 'failed',
                    'platform_post_id' => $result['post_id'] ?? null,
                    'response_data' => $result,
                    'scheduled_at' => now(),
                    'published_at' => $result['success'] ? now() : null,
                ]);

                $results[$platform] = $result;

            } catch (\Exception $e) {
                Log::error("Social media posting failed for {$platform}", [
                    'event_id' => $event->id,
                    'error' => $e->getMessage()
                ]);

                $results[$platform] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Auto-post an announcement/news to social media
     */
    public function postAnnouncement(News $news, array $platforms = null): array
    {
        $platforms = $platforms ?? $this->getEnabledPlatforms();
        $results = [];

        $content = $this->formatAnnouncementContent($news);

        foreach ($platforms as $platform) {
            try {
                $result = $this->postToPlatform($platform, $content, $news->featured_image);

                // Log the post in our database
                SocialMediaPost::create([
                    'content_type' => 'news',
                    'content_id' => $news->id,
                    'platform' => $platform,
                    'content' => $content['text'],
                    'status' => $result['success'] ? 'published' : 'failed',
                    'platform_post_id' => $result['post_id'] ?? null,
                    'response_data' => $result,
                    'scheduled_at' => now(),
                    'published_at' => $result['success'] ? now() : null,
                ]);

                $results[$platform] = $result;

            } catch (\Exception $e) {
                Log::error("Social media posting failed for {$platform}", [
                    'news_id' => $news->id,
                    'error' => $e->getMessage()
                ]);

                $results[$platform] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Schedule a post for later publishing
     */
    public function schedulePost(string $contentType, int $contentId, array $platforms, \DateTime $scheduledTime): array
    {
        $results = [];

        foreach ($platforms as $platform) {
            $post = SocialMediaPost::create([
                'content_type' => $contentType,
                'content_id' => $contentId,
                'platform' => $platform,
                'content' => '', // Will be generated when published
                'status' => 'scheduled',
                'scheduled_at' => $scheduledTime,
            ]);

            $results[$platform] = [
                'success' => true,
                'scheduled_post_id' => $post->id
            ];
        }

        return $results;
    }

    /**
     * Process scheduled posts that are ready to be published
     */
    public function processScheduledPosts(): array
    {
        $readyPosts = SocialMediaPost::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        $results = [];

        foreach ($readyPosts as $post) {
            try {
                if ($post->content_type === 'event') {
                    $event = Event::find($post->content_id);
                    if ($event) {
                        $content = $this->formatEventContent($event);
                        $result = $this->postToPlatform($post->platform, $content, $event->featured_image);
                    }
                } elseif ($post->content_type === 'news') {
                    $news = News::find($post->content_id);
                    if ($news) {
                        $content = $this->formatAnnouncementContent($news);
                        $result = $this->postToPlatform($post->platform, $content, $news->featured_image);
                    }
                }

                if (isset($result)) {
                    $post->update([
                        'content' => $content['text'],
                        'status' => $result['success'] ? 'published' : 'failed',
                        'platform_post_id' => $result['post_id'] ?? null,
                        'response_data' => $result,
                        'published_at' => $result['success'] ? now() : null,
                    ]);

                    $results[] = [
                        'post_id' => $post->id,
                        'platform' => $post->platform,
                        'result' => $result
                    ];
                }

            } catch (\Exception $e) {
                Log::error("Scheduled post processing failed", [
                    'post_id' => $post->id,
                    'error' => $e->getMessage()
                ]);

                $post->update([
                    'status' => 'failed',
                    'response_data' => ['error' => $e->getMessage()]
                ]);
            }
        }

        return $results;
    }

    /**
     * Format event content for social media
     */
    public function formatEventContent(Event $event): array
    {
        $text = "🎉 {$event->title}\n\n";
        $text .= "{$event->description}\n\n";
        $text .= "📅 Date: {$event->start_date->format('M j, Y')}\n";
        $text .= "🕐 Time: {$event->start_date->format('g:i A')}";

        if ($event->end_date) {
            $text .= " - {$event->end_date->format('g:i A')}";
        }

        $text .= "\n📍 Location: {$event->location}\n";

        if ($event->event_anchor) {
            $text .= "👤 Host: {$event->event_anchor}\n";
        }

        if ($event->guest_speaker) {
            $text .= "🎤 Speaker: {$event->guest_speaker}\n";
        }

        if ($event->requires_registration) {
            $text .= "\n📝 Registration required!\n";
        }

        $text .= "\n🔗 More info: " . route('events.show', $event->slug);
        $text .= "\n\n#CityLife #ChurchEvents #Sheffield #Community #Faith";

        return [
            'text' => $text,
            'hashtags' => ['CityLife', 'ChurchEvents', 'Sheffield', 'Community', 'Faith'],
            'url' => route('events.show', $event->slug)
        ];
    }

    /**
     * Format news/announcement content for social media
     */
    public function formatAnnouncementContent(News $news): array
    {
        $text = "📢 {$news->title}\n\n";
        $text .= "{$news->excerpt}\n\n";

        if ($news->author) {
            $text .= "✍️ By: {$news->author}\n";
        }

        $text .= "\n🔗 Read more: " . route('news.show', $news->slug);
        $text .= "\n\n#CityLife #ChurchNews #Announcement #Sheffield #Faith";

        return [
            'text' => $text,
            'hashtags' => ['CityLife', 'ChurchNews', 'Announcement', 'Sheffield', 'Faith'],
            'url' => route('news.show', $news->slug)
        ];
    }

    /**
     * Post content to a specific social media platform
     */
    protected function postToPlatform(string $platform, array $content, ?string $image = null): array
    {
        switch ($platform) {
            case 'facebook':
                return $this->postToFacebook($content, $image);
            case 'twitter':
                return $this->postToTwitter($content, $image);
            case 'instagram':
                return $this->postToInstagram($content, $image);
            case 'linkedin':
                return $this->postToLinkedIn($content, $image);
            default:
                throw new \InvalidArgumentException("Unsupported platform: {$platform}");
        }
    }

    /**
     * Post to Facebook using Facebook Graph API
     */
    protected function postToFacebook(array $content, ?string $image = null): array
    {
        $accessToken = config('services.facebook.access_token');
        $pageId = config('services.facebook.page_id');

        if (!$accessToken || !$pageId) {
            return ['success' => false, 'error' => 'Facebook credentials not configured'];
        }

        $url = "https://graph.facebook.com/v18.0/{$pageId}/feed";

        $data = [
            'message' => $content['text'],
            'access_token' => $accessToken,
        ];

        if ($image && $this->isValidImageUrl($image)) {
            $data['link'] = $content['url'];
            $data['picture'] = $image;
        }

        $response = Http::post($url, $data);

        if ($response->successful()) {
            return [
                'success' => true,
                'post_id' => $response->json('id'),
                'platform_response' => $response->json()
            ];
        }

        return [
            'success' => false,
            'error' => $response->json('error.message', 'Unknown error'),
            'platform_response' => $response->json()
        ];
    }

    /**
     * Post to Twitter using Twitter API v2
     */
    protected function postToTwitter(array $content, ?string $image = null): array
    {
        $bearerToken = config('services.twitter.bearer_token');

        if (!$bearerToken) {
            return ['success' => false, 'error' => 'Twitter credentials not configured'];
        }

        // Limit text to Twitter's character limit
        $text = Str::limit($content['text'], 270, '... ' . $content['url']);

        $url = 'https://api.twitter.com/2/tweets';

        $data = [
            'text' => $text
        ];

        $response = Http::withToken($bearerToken)
            ->post($url, $data);

        if ($response->successful()) {
            return [
                'success' => true,
                'post_id' => $response->json('data.id'),
                'platform_response' => $response->json()
            ];
        }

        return [
            'success' => false,
            'error' => $response->json('errors.0.detail', 'Unknown error'),
            'platform_response' => $response->json()
        ];
    }

    /**
     * Post to Instagram using Instagram Basic Display API
     */
    protected function postToInstagram(array $content, ?string $image = null): array
    {
        // Instagram requires images for posts
        if (!$image || !$this->isValidImageUrl($image)) {
            return ['success' => false, 'error' => 'Instagram posts require images'];
        }

        $accessToken = config('services.instagram.access_token');
        $userId = config('services.instagram.user_id');

        if (!$accessToken || !$userId) {
            return ['success' => false, 'error' => 'Instagram credentials not configured'];
        }

        // Step 1: Create media object
        $createUrl = "https://graph.instagram.com/v18.0/{$userId}/media";

        $createData = [
            'image_url' => $image,
            'caption' => $content['text'],
            'access_token' => $accessToken,
        ];

        $createResponse = Http::post($createUrl, $createData);

        if (!$createResponse->successful()) {
            return [
                'success' => false,
                'error' => $createResponse->json('error.message', 'Failed to create media'),
                'platform_response' => $createResponse->json()
            ];
        }

        $mediaId = $createResponse->json('id');

        // Step 2: Publish the media
        $publishUrl = "https://graph.instagram.com/v18.0/{$userId}/media_publish";

        $publishData = [
            'creation_id' => $mediaId,
            'access_token' => $accessToken,
        ];

        $publishResponse = Http::post($publishUrl, $publishData);

        if ($publishResponse->successful()) {
            return [
                'success' => true,
                'post_id' => $publishResponse->json('id'),
                'platform_response' => $publishResponse->json()
            ];
        }

        return [
            'success' => false,
            'error' => $publishResponse->json('error.message', 'Failed to publish media'),
            'platform_response' => $publishResponse->json()
        ];
    }

    /**
     * Post to LinkedIn using LinkedIn API
     */
    protected function postToLinkedIn(array $content, ?string $image = null): array
    {
        $accessToken = config('services.linkedin.access_token');
        $organizationId = config('services.linkedin.organization_id');

        if (!$accessToken || !$organizationId) {
            return ['success' => false, 'error' => 'LinkedIn credentials not configured'];
        }

        $url = 'https://api.linkedin.com/v2/ugcPosts';

        $data = [
            'author' => "urn:li:organization:{$organizationId}",
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $content['text']
                    ],
                    'shareMediaCategory' => 'NONE'
                ]
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
            ]
        ];

        if ($image && $this->isValidImageUrl($image)) {
            $data['specificContent']['com.linkedin.ugc.ShareContent']['shareMediaCategory'] = 'IMAGE';
            $data['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [
                [
                    'status' => 'READY',
                    'description' => [
                        'text' => $content['text']
                    ],
                    'media' => $image,
                    'title' => [
                        'text' => 'City Life Church'
                    ]
                ]
            ];
        }

        $response = Http::withToken($accessToken)
            ->withHeaders(['X-Restli-Protocol-Version' => '2.0.0'])
            ->post($url, $data);

        if ($response->successful()) {
            return [
                'success' => true,
                'post_id' => $response->header('x-restli-id'),
                'platform_response' => $response->json()
            ];
        }

        return [
            'success' => false,
            'error' => $response->json('message', 'Unknown error'),
            'platform_response' => $response->json()
        ];
    }

    /**
     * Get enabled social media platforms from config
     */
    protected function getEnabledPlatforms(): array
    {
        $enabled = [];

        foreach ($this->platforms as $platform) {
            if (config("services.{$platform}.enabled", false)) {
                $enabled[] = $platform;
            }
        }

        return $enabled;
    }

    /**
     * Check if image URL is valid and accessible
     */
    protected function isValidImageUrl(?string $url): bool
    {
        if (!$url) return false;

        // Convert relative URLs to absolute
        if (!str_starts_with($url, 'http')) {
            $url = asset($url);
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Get analytics for social media posts
     */
    public function getAnalytics(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        $posts = SocialMediaPost::where('created_at', '>=', $startDate)->get();

        $analytics = [
            'total_posts' => $posts->count(),
            'successful_posts' => $posts->where('status', 'published')->count(),
            'failed_posts' => $posts->where('status', 'failed')->count(),
            'scheduled_posts' => $posts->where('status', 'scheduled')->count(),
            'platforms' => [],
            'content_types' => [],
            'daily_breakdown' => []
        ];

        // Platform breakdown
        foreach ($this->platforms as $platform) {
            $platformPosts = $posts->where('platform', $platform);
            $analytics['platforms'][$platform] = [
                'total' => $platformPosts->count(),
                'successful' => $platformPosts->where('status', 'published')->count(),
                'failed' => $platformPosts->where('status', 'failed')->count(),
            ];
        }

        // Content type breakdown
        foreach (['event', 'news'] as $type) {
            $typePosts = $posts->where('content_type', $type);
            $analytics['content_types'][$type] = [
                'total' => $typePosts->count(),
                'successful' => $typePosts->where('status', 'published')->count(),
                'failed' => $typePosts->where('status', 'failed')->count(),
            ];
        }

        return $analytics;
    }
}
