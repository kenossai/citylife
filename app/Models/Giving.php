<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giving extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'giving_type',
        'description',
        'content',
        'payment_methods',
        'bank_name',
        'account_name',
        'account_number',
        'sort_code',
        'online_giving_url',
        'instructions',
        'suggested_amount',
        'featured_image',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'suggested_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

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
        return $query->where('giving_type', $type);
    }

    public function getPaymentMethodsArrayAttribute()
    {
        return explode(',', $this->payment_methods);
    }
}
