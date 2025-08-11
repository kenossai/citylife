<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'start_date',
        'end_date',
        'location',
        'featured_image',
        'is_featured',
        'is_published',
        'recurring_settings',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'recurring_settings' => 'array',
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

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset('assets/images/events/event-2-5.jpg'); // default
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        // If the path starts with 'assets/' it's a public asset
        if (str_starts_with($this->featured_image, 'assets/')) {
            return asset($this->featured_image);
        }

        // Otherwise it's a storage file
        return asset('storage/' . $this->featured_image);
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('M j, Y @ g:i a');
    }
}
