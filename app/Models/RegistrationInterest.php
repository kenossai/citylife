<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RegistrationInterest extends Model
{
    protected $fillable = [
        'email',
        'status',
        'token',
        'approved_by',
        'approved_at',
        'registered_at',
        'member_id',
        'notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'registered_at' => 'datetime',
    ];

    /**
     * Generate a unique registration token
     */
    public function generateToken(): string
    {
        $this->token = Str::random(64);
        $this->save();
        return $this->token;
    }

    /**
     * Get the user who approved this interest
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the registered member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Scope for pending interests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved interests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if interest is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if interest is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if registration is complete
     */
    public function isRegistered(): bool
    {
        return !is_null($this->registered_at) && !is_null($this->user_id);
    }
}
