<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sermon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'speaker',
        'series',
        'scripture_reference',
        'sermon_date',
        'audio_url',
        'video_url',
        'thumbnail',
        'duration',
        'is_featured',
        'is_published',
    ];

    protected $casts = [
        'sermon_date' => 'date',
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

    public function scopeBySeries($query, $series)
    {
        return $query->where('series', $series);
    }
}
