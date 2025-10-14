<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'membership_number',
        'title',
        'first_name',
        'last_name',
        'middle_name',
        'preferred_name',
        'date_of_birth',
        'gender',
        'marital_status',
        'spouse_is_member',
        'spouse_member_id',
        'email',
        'password',
        'phone',
        'alternative_phone',
        'address',
        'city',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'occupation',
        'employer',
        'membership_status',
        'first_visit_date',
        'membership_date',
        'baptism_status',
        'baptism_date',
        'previous_church',
        'ministries_involved',
        'skills_talents',
        'prayer_requests',
        'special_needs',
        'receives_newsletter',
        'receives_sms',
        'gdpr_consent',
        'gdpr_consent_date',
        'gdpr_consent_ip',
        'newsletter_consent',
        'newsletter_consent_date',
        'last_login_at',
        'last_login_ip',
        'photo',
        'notes',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'first_visit_date' => 'date',
        'membership_date' => 'date',
        'baptism_date' => 'date',
        'ministries_involved' => 'array',
        'skills_talents' => 'array',
        'receives_newsletter' => 'boolean',
        'receives_sms' => 'boolean',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'gdpr_consent' => 'boolean',
        'gdpr_consent_date' => 'datetime',
        'newsletter_consent' => 'boolean',
        'newsletter_consent_date' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // Auto-generate membership number when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (empty($member->membership_number)) {
                $member->membership_number = 'CL' . date('Y') . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Mutators to ensure data consistency
    public function setEmailAttribute($value)
    {
        // Normalize email: trim whitespace and convert to lowercase
        $this->attributes['email'] = !empty($value) ? strtolower(trim($value)) : null;
    }

    // Override the default username field for authentication
    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // Relationships
    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'user_id');
    }

    public function ministries()
    {
        return $this->belongsToMany(Ministry::class, 'member_ministry')->withTimestamps();
    }

    public function pastoralReminders()
    {
        return $this->hasMany(PastoralReminder::class);
    }

    public function pastoralNotifications()
    {
        return $this->hasMany(PastoralNotification::class);
    }

    // GDPR Relationships
    public function gdprConsents()
    {
        return $this->hasMany(GdprConsent::class);
    }

    public function gdprDataRequests()
    {
        return $this->hasMany(GdprDataRequest::class);
    }

    public function gdprAuditLogs()
    {
        return $this->hasMany(GdprAuditLog::class);
    }

    public function technicalDepartments()
    {
        return $this->hasMany(TechnicalDepartmentMember::class);
    }

    public function activeTechnicalDepartments()
    {
        return $this->hasMany(TechnicalDepartmentMember::class)->where('is_active', true);
    }

    public function worshipDepartments()
    {
        return $this->hasMany(WorshipDepartmentMember::class);
    }

    public function activeWorshipDepartments()
    {
        return $this->hasMany(WorshipDepartmentMember::class)->where('is_active', true);
    }

    public function preacherDepartments()
    {
        return $this->hasMany(PreacherDepartmentMember::class);
    }

    public function activePreacherDepartments()
    {
        return $this->hasMany(PreacherDepartmentMember::class)->where('is_active', true);
    }

    // Spouse relationship
    public function spouse()
    {
        return $this->belongsTo(Member::class, 'spouse_member_id');
    }

    // Members who have this member as their spouse
    public function spouseOf()
    {
        return $this->hasOne(Member::class, 'spouse_member_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMembers($query)
    {
        return $query->where('membership_status', 'member');
    }

    public function scopeVisitors($query)
    {
        return $query->where('membership_status', 'visitor');
    }

    public function scopeRegularAttendees($query)
    {
        return $query->where('membership_status', 'regular_attendee');
    }

    public function scopeBirthdays($query, $month = null)
    {
        $month = $month ?? now()->month;
        return $query->whereMonth('date_of_birth', $month);
    }

    // Accessors
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    public function getDisplayNameAttribute()
    {
        return $this->preferred_name ?: $this->first_name;
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function getFullAddressAttribute()
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

    public function getMembershipDurationAttribute()
    {
        if (!$this->membership_date) return null;

        return Carbon::parse($this->membership_date)->diffForHumans(null, true);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->membership_status) {
            'visitor' => '<span class="badge badge-info">Visitor</span>',
            'regular_attendee' => '<span class="badge badge-warning">Regular Attendee</span>',
            'member' => '<span class="badge badge-success">Member</span>',
            'inactive' => '<span class="badge badge-secondary">Inactive</span>',
            'transferred' => '<span class="badge badge-primary">Transferred</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    // Methods
    public function markAsMember()
    {
        $this->update([
            'membership_status' => 'member',
            'membership_date' => now(),
        ]);
    }

    public function getUpcomingBirthday()
    {
        if (!$this->date_of_birth) return null;

        $birthday = Carbon::parse($this->date_of_birth)->setYear(now()->year);

        if ($birthday->isPast()) {
            $birthday->addYear();
        }

        return $birthday;
    }
}
