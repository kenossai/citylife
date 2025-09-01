<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftAidDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'postcode',
        'phone',
        'email',
        'gift_aid_code',
        'confirmation_date',
        'confirm_declaration',
        'is_active',
    ];

    protected $casts = [
        'confirmation_date' => 'date',
        'confirm_declaration' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Scope for active declarations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
