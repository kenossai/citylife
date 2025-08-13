<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CityLifeTalkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'video_url',
        'host',
        'guest',
        'episode_date',
        'duration_minutes',
        'episode_number',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'episode_date' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'duration_minutes' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($talkTime) {
            if (empty($talkTime->slug)) {
                $talkTime->slug = Str::slug($talkTime->title);
            }
            
            // Auto-generate episode number if not provided
            if (empty($talkTime->episode_number)) {
                $talkTime->episode_number = static::generateEpisodeNumber();
            }
        });

        static::updating(function ($talkTime) {
            if ($talkTime->isDirty('title') && empty($talkTime->slug)) {
                $talkTime->slug = Str::slug($talkTime->title);
            }
        });
    }

    /**
     * Generate the next episode number in sequence
     */
    protected static function generateEpisodeNumber()
    {
        // Get the current year for season numbering
        $currentYear = now()->year;
        $season = $currentYear - 2023; // Start with Season 1 in 2024
        
        // Count episodes created this year
        $episodeCount = static::whereYear('created_at', $currentYear)->count() + 1;
        
        // Format as S{season}E{episode} with zero padding
        return sprintf('S%02dE%02d', $season, $episodeCount);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrderByDate($query, $direction = 'desc')
    {
        return $query->orderBy('episode_date', $direction);
    }

    public function scopeOrderBySortOrder($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('episode_date', 'desc');
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return null;
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return sprintf('%d hr %d min', $hours, $minutes);
        }

        return sprintf('%d min', $minutes);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return asset('assets/images/defaults/talktime-default.jpg');
    }

    public function getExcerptAttribute()
    {
        return Str::limit($this->description, 150);
    }

    public function getFormattedEpisodeDateAttribute()
    {
        if (!$this->episode_date) {
            return null;
        }

        return $this->episode_date->format('M d, Y');
    }

    // Route key binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
