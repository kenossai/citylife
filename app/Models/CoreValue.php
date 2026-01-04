<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CoreValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'bible_verse',
        'bible_reference',
        'icon',
        'featured_image',
        'background_color',
        'text_color',
        'sort_order',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'about_page_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function aboutPage()
    {
        return $this->belongsTo(AboutPage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Accessors
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return null;
        }

        return asset('storage/' . $this->featured_image);
    }

    public function getShortTitleAttribute()
    {
        return Str::limit($this->title ?? '', 20);
    }

    public function getExcerptAttribute()
    {
        if ($this->short_description) {
            return $this->short_description;
        }

        if ($this->description) {
            return Str::limit(strip_tags($this->description), 150);
        }

        return '';
    }

    // Mutators
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value ?: $this->title);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        // Auto-generate slug if not set
        if (!$this->slug) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }
}
