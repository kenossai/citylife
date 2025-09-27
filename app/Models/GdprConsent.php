<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GdprConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'consent_type',
        'consent_given',
        'consent_date',
        'consent_withdrawn_date',
        'consent_method',
        'ip_address',
        'user_agent',
        'consent_details',
        'withdrawal_reason',
    ];

    protected $casts = [
        'consent_given' => 'boolean',
        'consent_date' => 'datetime',
        'consent_withdrawn_date' => 'datetime',
        'consent_details' => 'array',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Scope for active consents
    public function scopeActive($query)
    {
        return $query->where('consent_given', true)
                    ->whereNull('consent_withdrawn_date');
    }

    // Scope for withdrawn consents
    public function scopeWithdrawn($query)
    {
        return $query->where('consent_given', false)
                    ->whereNotNull('consent_withdrawn_date');
    }

    // Get consent types
    public static function getConsentTypes()
    {
        return [
            'data_processing' => 'Data Processing',
            'marketing_email' => 'Marketing Emails',
            'marketing_sms' => 'Marketing SMS',
            'newsletter' => 'Newsletter',
            'event_notifications' => 'Event Notifications',
            'pastoral_reminders' => 'Pastoral Care Reminders',
            'photo_usage' => 'Photo & Video Usage',
            'third_party_sharing' => 'Third Party Data Sharing',
        ];
    }

    // Get consent methods
    public static function getConsentMethods()
    {
        return [
            'web_form' => 'Website Form',
            'email' => 'Email',
            'phone' => 'Phone Call',
            'in_person' => 'In Person',
            'paper_form' => 'Paper Form',
            'import' => 'Data Import',
        ];
    }

    // Check if consent is currently valid
    public function isValidConsent(): bool
    {
        return $this->consent_given &&
               $this->consent_date &&
               !$this->consent_withdrawn_date;
    }

    // Withdraw consent
    public function withdraw(string $reason = null): self
    {
        $this->update([
            'consent_given' => false,
            'consent_withdrawn_date' => now(),
            'withdrawal_reason' => $reason,
        ]);

        // Log the withdrawal
        GdprAuditLog::create([
            'member_id' => $this->member_id,
            'action' => 'consent_withdrawn',
            'description' => "Consent withdrawn for {$this->consent_type}",
            'old_values' => ['consent_given' => true],
            'new_values' => ['consent_given' => false, 'reason' => $reason],
            'performed_by' => Auth::user()->name ?? 'System',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $this;
    }

    // Grant consent
    public function grant(array $details = []): self
    {
        $this->update([
            'consent_given' => true,
            'consent_date' => now(),
            'consent_withdrawn_date' => null,
            'consent_details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Log the consent
        GdprAuditLog::create([
            'member_id' => $this->member_id,
            'action' => 'consent_granted',
            'description' => "Consent granted for {$this->consent_type}",
            'new_values' => ['consent_given' => true],
            'performed_by' => Auth::user()->name ?? 'System',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $this;
    }
}
