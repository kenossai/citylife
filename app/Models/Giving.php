<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giving extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'giving_type',
        'payment_method',
        'given_date',
        'reference_number',
        'notes',
        'is_anonymous',
        'gift_aid_eligible',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'given_date' => 'date',
        'is_anonymous' => 'boolean',
        'gift_aid_eligible' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('giving_type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('given_date', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('given_date', now()->month)
                    ->whereYear('given_date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('given_date', now()->year);
    }

    public function scopeGiftAidEligible($query)
    {
        return $query->where('gift_aid_eligible', true);
    }

    public function getFormattedAmountAttribute()
    {
        return '£' . number_format($this->amount, 2);
    }

    public function getDonorDisplayNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->member ? $this->member->full_name : $this->donor_name;
    }
}
