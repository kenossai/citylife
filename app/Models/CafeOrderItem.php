<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'customizations',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'customizations' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });
        
        static::updating(function ($item) {
            if ($item->isDirty(['quantity', 'unit_price'])) {
                $item->total_price = $item->quantity * $item->unit_price;
            }
        });
    }

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(CafeOrder::class, 'order_id');
    }

    /**
     * Get the product that owns the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(CafeProduct::class, 'product_id');
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalAttribute(): string
    {
        return '£' . number_format($this->total_price, 2);
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return '£' . number_format($this->unit_price, 2);
    }

    /**
     * Get customizations as readable text.
     */
    public function getCustomizationsTextAttribute(): string
    {
        if (!$this->customizations) {
            return '';
        }
        
        $text = [];
        foreach ($this->customizations as $key => $value) {
            if ($value) {
                $text[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
            }
        }
        
        return implode(', ', $text);
    }
}
