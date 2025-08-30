<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CityLifeMusic extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'artist',
        'album',
        'genre',
        'image',
        'audio_url',
        'spotify_url',
        'apple_music_url',
        'youtube_url',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($music) {
            if (empty($music->slug)) {
                $music->slug = Str::slug($music->title);
            }
        });

        static::updating(function ($music) {
            if ($music->isDirty('title') && empty($music->slug)) {
                $music->slug = Str::slug($music->title);
            }
        });
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByArtist(Builder $query, string $artist): Builder
    {
        return $query->where('artist', $artist);
    }

    public function scopeByGenre(Builder $query, string $genre): Builder
    {
        return $query->where('genre', $genre);
    }

    public function scopeOrderBySortOrder(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('artist', 'LIKE', "%{$search}%")
              ->orWhere('album', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    // Accessors
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('assets/images/defaults/music-default.jpg');
    }

    public function getExcerptAttribute(): string
    {
        return $this->description ? Str::limit(strip_tags($this->description), 150) : '';
    }

    // Static methods
    public static function getGenres(): array
    {
        return self::published()
            ->whereNotNull('genre')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre')
            ->toArray();
    }

    public static function getArtists(): array
    {
        return self::published()
            ->whereNotNull('artist')
            ->distinct()
            ->orderBy('artist')
            ->pluck('artist')
            ->toArray();
    }

    // Methods
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
