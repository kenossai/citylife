<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedIp extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_by',
        'spam_count',
        'last_attempt_at',
        'is_active',
        'auto_blocked',
    ];

    protected $casts = [
        'spam_count' => 'integer',
        'last_attempt_at' => 'datetime',
        'is_active' => 'boolean',
        'auto_blocked' => 'boolean',
    ];

    // Relationships
    public function blockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Methods
    public static function isBlocked(string $ipAddress): bool
    {
        return static::where('ip_address', $ipAddress)
            ->where('is_active', true)
            ->exists();
    }

    public static function blockIp(string $ipAddress, ?string $reason = null, ?int $userId = null, bool $autoBlocked = false): self
    {
        return static::updateOrCreate(
            ['ip_address' => $ipAddress],
            [
                'reason' => $reason ?? 'Blocked for spam activity',
                'blocked_by' => $userId ?? auth()->id(),
                'spam_count' => 1,
                'last_attempt_at' => now(),
                'is_active' => true,
                'auto_blocked' => $autoBlocked,
            ]
        );
    }

    public function incrementSpamCount(): void
    {
        $this->increment('spam_count');
        $this->update(['last_attempt_at' => now()]);
    }

    public function unblock(): void
    {
        $this->update(['is_active' => false]);
    }

    public function reblock(): void
    {
        $this->update(['is_active' => true]);
    }
}
