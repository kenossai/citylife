<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'background_image',
        'is_active',
        'sort_order',
        'slug',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    // Accessors
    public function getBackgroundImageUrlAttribute()
    {
        if (!$this->background_image) {
            return asset('assets/images/backgrounds/slider-1-2.jpeg'); // default
        }

        if (Str::startsWith($this->background_image, ['http://', 'https://'])) {
            return $this->background_image;
        }

        // If the path starts with 'assets/' it's a public asset
        if (Str::startsWith($this->background_image, 'assets/')) {
            return asset($this->background_image);
        }

        // Otherwise it's a storage file - use Storage facade for proper URL generation
        try {
            return \Storage::disk(config('filesystems.default'))->url($this->background_image);
        } catch (\Exception $e) {
            \Log::error('Failed to generate storage URL', [
                'file' => $this->background_image,
                'error' => $e->getMessage()
            ]);
            return asset('assets/images/backgrounds/slider-1-2.jpeg'); // fallback
        }
    }

    public function getOverlayStyleAttribute()
    {
        $opacity = $this->background_overlay_opacity / 100;
        return "background: linear-gradient(rgba(" . $this->hexToRgb($this->background_overlay_color) . ", {$opacity}), rgba(" . $this->hexToRgb($this->background_overlay_color) . ", {$opacity}))";
    }

    public function getButtonTargetAttribute()
    {
        return $this->button_opens_new_tab ? '_blank' : '_self';
    }

    public function getTextAlignmentClassAttribute()
    {
        return match($this->text_alignment) {
            'center' => 'text-center',
            'right' => 'text-end',
            default => 'text-start',
        };
    }

    public function getTextColorClassAttribute()
    {
        return $this->text_color === 'dark' ? 'text-dark' : 'text-light';
    }

    // Helper methods
    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        return implode(', ', [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ]);
    }

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title') && empty($model->getOriginal('slug'))) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
