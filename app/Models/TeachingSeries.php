<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TeachingSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'summary',
        'image',
        'video_url',
        'audio_url',
        'sermon_notes',
        'sermon_notes_content',
        'sermon_notes_content_type',
        'pastor',
        'team_member_id',
        'category',
        'tags',
        'series_date',
        'duration_minutes',
        'scripture_references',
        'views_count',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'series_date' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'duration_minutes' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teachingSeries) {
            if (empty($teachingSeries->slug)) {
                $teachingSeries->slug = Str::slug($teachingSeries->title);
            }
        });

        static::updating(function ($teachingSeries) {
            if ($teachingSeries->isDirty('title') && empty($teachingSeries->getOriginal('slug'))) {
                $teachingSeries->slug = Str::slug($teachingSeries->title);
            }
        });
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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Relationships
    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function scopeOrderByDate($query, $direction = 'desc')
    {
        return $query->orderBy('series_date', $direction);
    }

    public function scopeOrderBySortOrder($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('series_date', 'desc');
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

        return asset('assets/images/defaults/teaching-series-default.jpg');
    }

    public function getSermonNotesUrlAttribute()
    {
        if ($this->sermon_notes) {
            return asset('storage/' . $this->sermon_notes);
        }

        return null;
    }

    public function getExcerptAttribute()
    {
        if ($this->summary) {
            return Str::limit($this->summary, 150);
        }

        return Str::limit($this->description, 150);
    }

    public function getSermonNotesTextAttribute()
    {
        if ($this->sermon_notes_content) {
            // Strip HTML tags and return plain text for SEO purposes
            return strip_tags($this->sermon_notes_content);
        }

        return null;
    }

    public function getHasSermonNotesContentAttribute()
    {
        return !empty($this->sermon_notes_content);
    }

    // Mutators
    public function setTagsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['tags'] = json_encode(explode(',', $value));
        } else {
            $this->attributes['tags'] = json_encode($value);
        }
    }

    // Helper methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public static function getCategories()
    {
        return self::distinct('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();
    }

    public static function getFeaturedSeries($limit = 3)
    {
        return self::published()
            ->featured()
            ->orderBySortOrder()
            ->limit($limit)
            ->get();
    }

    public static function getLatestSeries($limit = 6)
    {
        return self::published()
            ->orderByDate()
            ->limit($limit)
            ->get();
    }
}
