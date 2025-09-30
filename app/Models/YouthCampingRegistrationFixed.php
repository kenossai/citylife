<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YouthCampingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'youth_camping_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'age',
        'gender',
        'address',
        'city',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'medical_conditions',
        'medications',
        'dietary_requirements',
        'swimming_ability',
        'parent_guardian_name',
        'parent_guardian_email',
        'parent_guardian_phone',
        'consent_photo_video',
        'consent_medical_treatment',
        'consent_activities',
        'additional_notes',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'registration_date',
        'confirmation_sent_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'consent_photo_video' => 'boolean',
        'consent_medical_treatment' => 'boolean',
        'consent_activities' => 'boolean',
        'registration_date' => 'datetime',
        'confirmation_sent_at' => 'datetime',
        'medical_conditions' => 'array',
        'medications' => 'array',
        'dietary_requirements' => 'array',
    ];

    /**
     * Get the camping this registration belongs to
     */
    public function youthCamping(): BelongsTo
    {
        return $this->belongsTo(YouthCamping::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get age from date of birth
     */
    public function getCalculatedAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : ($this->age ?? 0);
    }

    /**
     * Check if all required consents are given
     */
    public function getHasAllConsentsAttribute(): bool
    {
        return $this->consent_photo_video &&
               $this->consent_medical_treatment &&
               $this->consent_activities;
    }

    /**
     * Check if registration is complete
     */
    public function getIsCompleteAttribute(): bool
    {
        $requiredFields = [
            'first_name', 'last_name', 'email', 'phone', 'date_of_birth',
            'address', 'city', 'emergency_contact_name', 'emergency_contact_phone',
            'parent_guardian_name', 'parent_guardian_email'
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return $this->has_all_consents;
    }

    /**
     * Scope for confirmed registrations
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid registrations
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Confirm the registration
     */
    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmation_sent_at' => now()
        ]);
    }

    /**
     * Cancel the registration
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Mark payment as received
     */
    public function markPaid(string $method = null, string $reference = null): void
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_method' => $method,
            'payment_reference' => $reference
        ]);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->registration_date)) {
                $registration->registration_date = now();
            }
            if (empty($registration->status)) {
                $registration->status = 'pending';
            }
            if (empty($registration->payment_status)) {
                $registration->payment_status = 'pending';
            }
        });
    }
}
