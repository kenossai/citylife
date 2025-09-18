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

    /**
     * Scope for confirmed declarations
     */
    public function scopeConfirmed($query)
    {
        return $query->where('confirm_declaration', true);
    }

    /**
     * Scope for this year's declarations
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    /**
     * Scope for this month's declarations
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Get the donor's givings that are gift aid eligible
     */
    public function eligibleGivings()
    {
        return $this->hasMany(\App\Models\Giving::class, 'donor_email', 'email')
            ->where('gift_aid_eligible', true);
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute(): string
    {
        return $this->address . ', ' . $this->postcode;
    }
}
