<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleSchoolOtpToken extends Model
{
    protected $fillable = [
        'email',
        'code',
        'year',
        'bible_school_speaker_id',
        'expires_at',
        'used_at',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
        'year'       => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(BibleSchoolSpeaker::class, 'bible_school_speaker_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Generate a unique BS###### code.
     */
    public static function generateCode(): string
    {
        do {
            $code = 'BS' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (
            self::where('code', $code)
                ->where('expires_at', '>', now())
                ->whereNull('used_at')
                ->exists()
        );

        return $code;
    }

    /**
     * Is this token still valid (not used, not expired)?
     */
    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }

    /**
     * Mark the token as used.
     */
    public function markUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeValid($query)
    {
        return $query->whereNull('used_at')
                     ->where('expires_at', '>', now());
    }
}
