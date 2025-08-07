<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'location',
        'target_group',
        'mission_type',
        'featured_image',
        'gallery_images',
        'contact_person',
        'contact_email',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('mission_type', $type);
    }
}
