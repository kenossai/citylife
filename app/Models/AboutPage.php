<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class AboutPage extends Model
{
    use HasFactory;

    protected $table = 'about_page';

    protected $fillable = [
        'title',
        'introduction',
        'featured_image',
        'church_name',
        'church_description',
        'affiliation',
        'location_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'social_media_links',
        'phone_number',
        'email_address',
        'address',
        'is_active',
        'sort_order',
        'slug',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'social_media_links' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function coreValues()
    {
        return $this->hasMany(CoreValue::class)->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Accessors
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone_number) {
            return null;
        }

        // Format phone number (you can customize this)
        return $this->phone_number;
    }

    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return null;
        }

        return asset('storage/' . $this->featured_image);
    }

    // Mutators
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value ?: $this->title);
    }
}
