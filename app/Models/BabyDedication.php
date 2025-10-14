<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BabyDedication extends Model
{
    use HasFactory;

    protected $fillable = [
        // Baby Information
        'baby_first_name',
        'baby_middle_name',
        'baby_last_name',
        'baby_date_of_birth',
        'baby_gender',
        'baby_place_of_birth',
        'baby_special_notes',

        // Parent Information
        'father_first_name',
        'father_last_name',
        'father_email',
        'father_phone',
        'father_is_member',
        'father_membership_number',
        'mother_first_name',
        'mother_last_name',
        'mother_email',
        'mother_phone',
        'mother_is_member',
        'mother_membership_number',

        // Address
        'address',
        'city',
        'postal_code',
        'country',

        // Dedication Details
        'preferred_dedication_date',
        'preferred_service',
        'special_requests',
        'photography_consent',
        'video_consent',

        // Church Information
        'regular_attendees',
        'how_long_attending',
        'previous_church',
        'baptized_parents',
        'faith_commitment',

        // Emergency Contact
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',

        // Admin fields
        'status',
        'scheduled_date',
        'scheduled_service',
        'admin_notes',
        'approved_by',
        'approved_at',

        // GDPR
        'gdpr_consent',
        'gdpr_consent_date',
        'gdpr_consent_ip',
        'newsletter_consent',
    ];

    protected $casts = [
        'baby_date_of_birth' => 'date',
        'preferred_dedication_date' => 'date',
        'scheduled_date' => 'date',
        'approved_at' => 'datetime',
        'gdpr_consent_date' => 'datetime',
        'father_is_member' => 'boolean',
        'mother_is_member' => 'boolean',
        'photography_consent' => 'boolean',
        'video_consent' => 'boolean',
        'regular_attendees' => 'boolean',
        'baptized_parents' => 'boolean',
        'gdpr_consent' => 'boolean',
        'newsletter_consent' => 'boolean',
    ];

    // Relationships
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function fatherMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'father_membership_number', 'membership_number');
    }

    public function motherMember(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'mother_membership_number', 'membership_number');
    }

    // Accessors
    public function getBabyFullNameAttribute(): string
    {
        $name = $this->baby_first_name;
        if ($this->baby_middle_name) {
            $name .= ' ' . $this->baby_middle_name;
        }
        $name .= ' ' . $this->baby_last_name;
        return $name;
    }

    public function getFatherFullNameAttribute(): string
    {
        return $this->father_first_name . ' ' . $this->father_last_name;
    }

    public function getMotherFullNameAttribute(): string
    {
        return $this->mother_first_name . ' ' . $this->mother_last_name;
    }

    public function getBabyAgeAttribute(): string
    {
        if (!$this->baby_date_of_birth) return 'Unknown';

        $age = Carbon::parse($this->baby_date_of_birth)->diff(Carbon::now());

        if ($age->y > 0) {
            return $age->y . ' year' . ($age->y > 1 ? 's' : '') .
                   ($age->m > 0 ? ' and ' . $age->m . ' month' . ($age->m > 1 ? 's' : '') : '');
        } elseif ($age->m > 0) {
            return $age->m . ' month' . ($age->m > 1 ? 's' : '');
        } else {
            return $age->d . ' day' . ($age->d > 1 ? 's' : '');
        }
    }

    public function getFullAddressAttribute(): string
    {
        $address = $this->address;
        if ($this->city) {
            $address .= ', ' . $this->city;
        }
        if ($this->postal_code) {
            $address .= ' ' . $this->postal_code;
        }
        if ($this->country && $this->country !== 'United Kingdom') {
            $address .= ', ' . $this->country;
        }
        return $address;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge badge-warning">Pending Review</span>',
            'approved' => '<span class="badge badge-info">Approved</span>',
            'scheduled' => '<span class="badge badge-primary">Scheduled</span>',
            'completed' => '<span class="badge badge-success">Completed</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['approved', 'scheduled'])
                    ->where('scheduled_date', '>=', now());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('scheduled_date', now()->month)
                    ->whereYear('scheduled_date', now()->year);
    }

    // Methods
    public function approve(User $user): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);
    }

    public function schedule(string $date, string $service): void
    {
        $this->update([
            'status' => 'scheduled',
            'scheduled_date' => $date,
            'scheduled_service' => $service,
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
        ]);
    }

    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'admin_notes' => $reason ? "Cancelled: {$reason}" : 'Cancelled',
        ]);
    }

    // Static methods
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public static function getServices(): array
    {
        return [
            'morning' => 'Morning Service',
            'evening' => 'Evening Service',
            'either' => 'Either Service',
        ];
    }
}
