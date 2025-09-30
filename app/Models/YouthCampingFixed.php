<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class YouthCamping extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'year',
        'start_date',
        'end_date',
        'location',
        'max_participants',
        'cost_per_person',
        'registration_opens_at',
        'registration_closes_at',
        'featured_image',
        'requirements',
        'what_to_bring',
        'activities',
        'contact_person',
        'contact_email',
        'contact_phone',
        'is_published',
        'is_registration_open',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_opens_at' => 'datetime',
        'registration_closes_at' => 'datetime',
        'is_published' => 'boolean',
        'is_registration_open' => 'boolean',
        'requirements' => 'array',
        'what_to_bring' => 'array',
        'activities' => 'array',
        'cost_per_person' => 'decimal:2',
    ];

    /**
     * Get all registrations for this camping
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(YouthCampingRegistration::class);
    }

    /**
     * Get confirmed registrations
     */
    public function confirmedRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'confirmed');
    }

    /**
     * Get pending registrations
     */
    public function pendingRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'pending');
    }

    /**
     * Check if registration is currently open
     */
    public function getIsRegistrationAvailableAttribute(): bool
    {
        if (!$this->is_published || !$this->is_registration_open) {
            return false;
        }

        $now = now();

        // Check if registration period is active
        if ($this->registration_opens_at && $now->lt($this->registration_opens_at)) {
            return false;
        }

        if ($this->registration_closes_at && $now->gt($this->registration_closes_at)) {
            return false;
        }

        // Check if there are available spots
        if ($this->max_participants && $this->confirmedRegistrations()->count() >= $this->max_participants) {
            return false;
        }

        return true;
    }

    /**
     * Get available spots remaining
     */
    public function getAvailableSpotsAttribute(): ?int
    {
        if (!$this->max_participants) {
            return null;
        }

        return max(0, $this->max_participants - $this->confirmedRegistrations()->count());
    }

    /**
     * Get registration status message
     */
    public function getRegistrationStatusMessageAttribute(): string
    {
        if (!$this->is_published) {
            return 'Not yet announced';
        }

        if (!$this->is_registration_open) {
            return 'Registration not open';
        }

        $now = now();

        if ($this->registration_opens_at && $now->lt($this->registration_opens_at)) {
            return 'Registration opens ' . $this->registration_opens_at->format('M j, Y \a\t g:i A');
        }

        if ($this->registration_closes_at && $now->gt($this->registration_closes_at)) {
            return 'Registration closed';
        }

        if ($this->max_participants && $this->confirmedRegistrations()->count() >= $this->max_participants) {
            return 'Fully booked';
        }

        return 'Registration open';
    }

    /**
     * Check if camping is upcoming (within next 6 months)
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_date && $this->start_date->between(now(), now()->addMonths(6));
    }

    /**
     * Scope to get current year camping
     */
    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }

    /**
     * Scope to get published campings
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get upcoming campings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }

    /**
     * Auto-open registration when date approaches
     */
    public function autoOpenRegistration(): void
    {
        if (!$this->is_registration_open && $this->registration_opens_at && now()->gte($this->registration_opens_at)) {
            $this->update(['is_registration_open' => true]);
        }
    }

    /**
     * Auto-close registration when deadline passes
     */
    public function autoCloseRegistration(): void
    {
        if ($this->is_registration_open && $this->registration_closes_at && now()->gt($this->registration_closes_at)) {
            $this->update(['is_registration_open' => false]);
        }
    }

    /**
     * Get route key name for URLs
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($camping) {
            if (empty($camping->slug)) {
                $camping->slug = \Illuminate\Support\Str::slug($camping->name . ' ' . $camping->year);
            }
        });
    }
}
