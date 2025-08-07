<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_name',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website_url',
        'office_hours',
        'service_times',
        'map_embed_url',
        'latitude',
        'longitude',
        'facebook_url',
        'youtube_url',
        'instagram_url',
        'twitter_url',
        'directions',
        'parking_info',
        'is_active',
    ];

    protected $casts = [
        'service_times' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->city . ' ' . $this->postal_code . ', ' . $this->country;
    }
}
