<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id',
        'title',
        'subtitle',
        'isbn',
        'isbn13',
        'description',
        'short_description',
        'publisher',
        'published_date',
        'edition',
        'language',
        'pages',
        'format',
        'cover_image',
        'back_cover_image',
        'sample_pages',
        'price',
        'currency',
        'purchase_link',
        'amazon_link',
        'preview_link',
        'category',
        'tags',
        'topics',
        'is_active',
        'is_featured',
        'sort_order',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'rating',
        'reviews_count',
    ];

    protected $casts = [
        'published_date' => 'date',
        'sample_pages' => 'array',
        'tags' => 'array',
        'topics' => 'array',
        'meta_keywords' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'pages' => 'integer',
        'sort_order' => 'integer',
        'views_count' => 'integer',
        'reviews_count' => 'integer',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByAuthor($query, $teamMemberId)
    {
        return $query->where('team_member_id', $teamMemberId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('published_date', 'desc');
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        if (!$this->price) {
            return null;
        }

        $symbols = [
            'GBP' => '£',
            'USD' => '$',
            'EUR' => '€',
        ];

        $symbol = $symbols[$this->currency] ?? $this->currency;
        return $symbol . number_format($this->price, 2);
    }

    public function getAuthorNameAttribute()
    {
        return $this->author ? $this->author->full_name : null;
    }

    public function getPublishedYearAttribute()
    {
        return $this->published_date ? $this->published_date->format('Y') : null;
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function updateRating(float $rating, int $count)
    {
        $this->update([
            'rating' => $rating,
            'reviews_count' => $count,
        ]);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title);
            }
        });
    }
}
