<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MediaContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'media_type',
        'series_name',
        'speaker_artist',
        'release_date',
        'video_url',
        'audio_url',
        'download_url',
        'thumbnail',
        'duration',
        'scripture_reference',
        'tags',
        'views_count',
        'downloads_count',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'release_date' => 'date',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('media_type', $type);
    }

    public function scopeBySeries($query, $series)
    {
        return $query->where('series_name', $series);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }
}
