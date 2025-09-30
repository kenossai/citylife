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
        // Child Information
        'child_first_name',
        'child_last_name',
        'child_date_of_birth',
        'child_age',
        'child_gender',
        'child_grade_school',
        'child_t_shirt_size',
        // Parent/Guardian Information (who is registering)
        'parent_first_name',
        'parent_last_name',
        'parent_email',
        'parent_phone',
        'parent_relationship', // mother, father, guardian, etc.
        // Contact & Address Information
        'home_address',
        'city',
        'postal_code',
        'home_phone',
        'work_phone',
        // Emergency Contact (different from parent if needed)
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        // Medical Information
        'medical_conditions',
        'medications',
        'allergies',
        'dietary_requirements',
        'swimming_ability',
        'doctor_name',
        'doctor_phone',
        'health_card_number',
        // Consent & Permissions
        'consent_photo_video',
        'consent_medical_treatment',
        'consent_activities',
        'consent_pickup_authorized_persons',
        'pickup_authorized_persons', // JSON array of people authorized to pick up
        // Registration Management
        'special_needs',
        'additional_notes',
        'status',
        'payment_status',
        'payment_amount',
        'payment_method',
        'payment_reference',
        'registration_date',
        'confirmation_sent_at',
    ];

    protected $casts = [
        'child_date_of_birth' => 'date',
        'consent_photo_video' => 'boolean',
        'consent_medical_treatment' => 'boolean',
        'consent_activities' => 'boolean',
        'consent_pickup_authorized_persons' => 'boolean',
        'registration_date' => 'datetime',
        'confirmation_sent_at' => 'datetime',
        'medical_conditions' => 'array',
        'medications' => 'array',
        'allergies' => 'array',
        'dietary_requirements' => 'array',
        'pickup_authorized_persons' => 'array',
        'payment_amount' => 'decimal:2',
    ];

    /**
     * Get the camping this registration belongs to
     */
    public function youthCamping(): BelongsTo
    {
        return $this->belongsTo(YouthCamping::class);
    }

    /**
     * Get child's full name attribute
     */
    public function getChildFullNameAttribute(): string
    {
        return $this->child_first_name . ' ' . $this->child_last_name;
    }

    /**
     * Get parent's full name attribute
     */
    public function getParentFullNameAttribute(): string
    {
        return $this->parent_first_name . ' ' . $this->parent_last_name;
    }

    /**
     * Get child's age from date of birth
     */
    public function getChildCalculatedAgeAttribute(): int
    {
        return $this->child_date_of_birth ? $this->child_date_of_birth->age : ($this->child_age ?? 0);
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
            'child_first_name', 'child_last_name', 'child_date_of_birth',
            'parent_first_name', 'parent_last_name', 'parent_email', 'parent_phone',
            'home_address', 'city', 'emergency_contact_name', 'emergency_contact_phone'
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
