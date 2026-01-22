<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'position',
        'team_type',
        'email',
        'phone',
        'bio',
        'short_description',
        'ministry_focus',
        'responsibilities',
        'spouse_name',
        'joined_church',
        'started_ministry',
        'profile_image',
        'featured_image',
        'books_written',
        'courses_taught',
        'ministry_areas',
        'calling_testimony',
        'achievements',
        'sort_order',
        'is_active',
        'is_featured',
        'show_contact_info',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'responsibilities' => 'array',
        'books_written' => 'array',
        'courses_taught' => 'array',
        'ministry_areas' => 'array',
        'meta_keywords' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_contact_info' => 'boolean',
        'joined_church' => 'integer',
        'started_ministry' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePastoral($query)
    {
        return $query->where('team_type', 'pastoral');
    }

    public function scopeLeadership($query)
    {
        return $query->where('team_type', 'leadership');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('last_name');
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Relationships
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'team_member_id');
    }

    public function publishedBooks(): HasMany
    {
        return $this->hasMany(Book::class, 'team_member_id')->where('is_active', true);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        $name = '';
        if ($this->title) {
            $name .= $this->title . ' ';
        }
        $name .= $this->first_name . ' ' . $this->last_name;
        return $name;
    }

    public function getDisplayNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return asset('assets/images/team/default-team-member.jpg');
        }

        // If it's already a full URL, return it
        if (str_starts_with($this->profile_image, 'http://') || str_starts_with($this->profile_image, 'https://')) {
            return $this->profile_image;
        }

        // If the path starts with 'assets/' it's a public asset
        if (str_starts_with($this->profile_image, 'assets/')) {
            return asset($this->profile_image);
        }

        // Otherwise it's a storage file (S3 or local)
        try {
            return \Storage::disk('s3')->url($this->profile_image);
        } catch (\Exception $e) {
            // Fallback to local storage if S3 fails
            return asset('storage/' . $this->profile_image);
        }
    }

    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return $this->profile_image_url;
        }
        return asset('storage/' . $this->featured_image);
    }

    public function getExcerptAttribute()
    {
        if ($this->short_description) {
            return $this->short_description;
        }

        if ($this->bio) {
            return Str::limit(strip_tags($this->bio), 150);
        }

        return 'Team member at ' . config('app.name');
    }

    public function getYearsOfServiceAttribute()
    {
        if (!$this->joined_church) {
            return null;
        }

        return now()->year - $this->joined_church;
    }

    public function getYearsInMinistryAttribute()
    {
        if (!$this->started_ministry) {
            return null;
        }

        return now()->year - $this->started_ministry;
    }

    public function getBooksWithImagesAttribute()
    {
        if (!$this->books_written || !is_array($this->books_written)) {
            return [];
        }

        return collect($this->books_written)->map(function ($book) {
            if (isset($book['cover_image'])) {
                $book['cover_image_url'] = asset('storage/' . $book['cover_image']);
            }
            return $book;
        })->toArray();
    }

    // Mutators
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['slug'] = Str::slug($this->first_name . '-' . $this->last_name);
        } else {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    // Helper methods
    public function isPastoral()
    {
        return $this->team_type === 'pastoral';
    }

    public function isLeadership()
    {
        return $this->team_type === 'leadership';
    }

    public function hasContactInfo()
    {
        return $this->show_contact_info && ($this->email || $this->phone);
    }

    public function getTeamTypeDisplayAttribute()
    {
        return ucfirst($this->team_type) . ' Team';
    }

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->first_name . '-' . $model->last_name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty(['first_name', 'last_name']) && empty($model->getOriginal('slug'))) {
                $model->slug = Str::slug($model->first_name . '-' . $model->last_name);
            }
        });
    }
}
