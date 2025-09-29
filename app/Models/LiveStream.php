<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LiveStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'platform',
        'stream_url',
        'embed_code',
        'thumbnail_url',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'status',
        'estimated_viewers',
        'peak_viewers',
        'stream_settings',
        'is_featured',
        'is_public',
        'enable_chat',
        'auto_record',
        'recording_url',
        'pastor_notes',
        'tags',
        'category',
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'stream_settings' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_public' => 'boolean',
        'enable_chat' => 'boolean',
        'auto_record' => 'boolean',
        'estimated_viewers' => 'integer',
        'peak_viewers' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stream) {
            if (empty($stream->slug)) {
                $stream->slug = Str::slug($stream->title);
            }
        });
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_start', '>', now());
    }

    // Static methods
    public static function getCurrentLiveStreams()
    {
        return static::live()->public()->orderBy('actual_start', 'desc')->get();
    }

    public static function getUpcomingStreams($limit = 5)
    {
        return static::upcoming()->public()->orderBy('scheduled_start', 'asc')->limit($limit)->get();
    }

    public static function getCategories()
    {
        return [
            'service' => 'Sunday Service',
            'prayer' => 'Prayer Meeting',
            'youth' => 'Youth Service',
            'bible_study' => 'Bible Study',
            'special_event' => 'Special Event',
            'conference' => 'Conference',
            'worship' => 'Worship Night',
            'outreach' => 'Outreach Event',
        ];
    }

    public static function getPlatforms()
    {
        return [
            'youtube' => 'YouTube',
            'vimeo' => 'Vimeo',
            'facebook' => 'Facebook Live',
            'custom' => 'Custom Platform',
        ];
    }
}
