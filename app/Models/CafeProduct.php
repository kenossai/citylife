<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CafeProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'ingredients',
        'price',
        'cost_price',
        'image',
        'gallery',
        'stock_quantity',
        'track_stock',
        'size',
        'dietary_info',
        'nutritional_info',
        'preparation_time',
        'temperature',
        'is_available',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'gallery' => 'array',
        'dietary_info' => 'array',
        'nutritional_info' => 'array',
        'track_stock' => 'boolean',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CafeCategory::class, 'category_id');
    }

    /**
     * Get the order items for this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(CafeOrderItem::class, 'product_id');
    }

    /**
     * Scope a query to only include available products.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
              ->orWhere('stock_quantity', '>', 0);
        });
    }

    /**
     * Scope a query to order products.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the image URL with default fallback.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        
        return asset('assets/images/cafe/product-default.jpg');
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '£' . number_format($this->price, 2);
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        if (!$this->track_stock) {
            return true;
        }
        
        return $this->stock_quantity > 0;
    }

    /**
     * Reduce stock quantity.
     */
    public function reduceStock(int $quantity): void
    {
        if ($this->track_stock) {
            $this->decrement('stock_quantity', $quantity);
        }
    }

    /**
     * Increase stock quantity.
     */
    public function increaseStock(int $quantity): void
    {
        if ($this->track_stock) {
            $this->increment('stock_quantity', $quantity);
        }
    }

    /**
     * Get stock status.
     */
    public function getStockStatusAttribute(): string
    {
        if (!$this->track_stock) {
            return 'Not tracked';
        }
        
        if ($this->stock_quantity <= 0) {
            return 'Out of stock';
        }
        
        if ($this->stock_quantity <= 5) {
            return 'Low stock';
        }
        
        return 'In stock';
    }

    /**
     * Get dietary badges.
     */
    public function getDietaryBadgesAttribute(): array
    {
        $badges = [];
        
        if ($this->dietary_info) {
            foreach ($this->dietary_info as $info) {
                switch (strtolower($info)) {
                    case 'vegan':
                        $badges[] = ['label' => 'Vegan', 'color' => 'green'];
                        break;
                    case 'vegetarian':
                        $badges[] = ['label' => 'Vegetarian', 'color' => 'blue'];
                        break;
                    case 'gluten_free':
                        $badges[] = ['label' => 'Gluten Free', 'color' => 'orange'];
                        break;
                    case 'dairy_free':
                        $badges[] = ['label' => 'Dairy Free', 'color' => 'purple'];
                        break;
                    case 'sugar_free':
                        $badges[] = ['label' => 'Sugar Free', 'color' => 'red'];
                        break;
                }
            }
        }
        
        return $badges;
    }
}
