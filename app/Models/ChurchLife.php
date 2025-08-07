<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChurchLife extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'section_type',
        'featured_image',
        'gallery_images',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'gallery_images' => 'array',
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

    public function scopeBySection($query, $section)
    {
        return $query->where('section_type', $section);
    }
}
