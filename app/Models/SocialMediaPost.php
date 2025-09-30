<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialMediaPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_type',
        'content_id',
        'platform',
        'content',
        'status',
        'platform_post_id',
        'response_data',
        'scheduled_at',
        'published_at',
        'error_message',
    ];

    protected $casts = [
        'response_data' => 'array',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    /**
     * Get the parent content model (Event, News, etc.)
     */
    public function content(): MorphTo
    {
        return $this->morphTo('content', 'content_type', 'content_id');
    }

    /**
     * Scope to get posts for a specific platform
     */
    public function scopeForPlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope to get published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get failed posts
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope to get scheduled posts
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope to get ready-to-publish scheduled posts
     */
    public function scopeReadyToPublish($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<=', now());
    }

    /**
     * Get platform display name
     */
    public function getPlatformDisplayNameAttribute(): string
    {
        return match($this->platform) {
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            default => ucfirst($this->platform)
        };
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'published' => 'success',
            'failed' => 'danger',
            'scheduled' => 'warning',
            'draft' => 'secondary',
            default => 'primary'
        };
    }

    /**
     * Check if post was successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'published' && !empty($this->platform_post_id);
    }

    /**
     * Check if post can be retried
     */
    public function canRetry(): bool
    {
        return in_array($this->status, ['failed', 'scheduled']) &&
               $this->content_type &&
               $this->content_id;
    }

    /**
     * Get content model dynamically
     */
    public function getContentModel()
    {
        return match($this->content_type) {
            'event' => Event::find($this->content_id),
            'news' => News::find($this->content_id),
            default => null
        };
    }

    /**
     * Get platform URL if available
     */
    public function getPlatformUrlAttribute(): ?string
    {
        if (!$this->platform_post_id || $this->status !== 'published') {
            return null;
        }

        return match($this->platform) {
            'facebook' => "https://www.facebook.com/{$this->platform_post_id}",
            'twitter' => "https://twitter.com/i/web/status/{$this->platform_post_id}",
            'instagram' => "https://www.instagram.com/p/{$this->platform_post_id}/",
            'linkedin' => "https://www.linkedin.com/feed/update/{$this->platform_post_id}/",
            default => null
        };
    }
}
