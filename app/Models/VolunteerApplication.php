<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_type',
        'team',
        'name',
        'date_of_birth',
        'sex',
        'email',
        'mobile',
        'address',
        'medical_professional',
        'first_aid_certificate',
        'church_background',
        'employment_details',
        'support_mission',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'eligible_to_work',
        'data_processing_consent',
        'data_protection_consent',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'medical_professional' => 'boolean',
        'first_aid_certificate' => 'boolean',
        'eligible_to_work' => 'boolean',
        'data_processing_consent' => 'boolean',
        'data_protection_consent' => 'boolean',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    // Accessors
    public function getFormattedApplicationTypeAttribute()
    {
        return match($this->application_type) {
            'event_only' => 'For specific events only',
            'ongoing' => 'To join the team on an ongoing basis',
            default => $this->application_type,
        };
    }

    public function getFormattedTeamAttribute()
    {
        return match($this->team) {
            'stewarding' => 'Stewarding Team',
            'worship' => 'Worship Team',
            'technical' => 'Technical Team',
            'children' => 'Children\'s Ministry',
            'hospitality' => 'Hospitality Team',
            'prayer' => 'Prayer Team',
            'media' => 'Media Team',
            'facilities' => 'Facilities Team',
            default => $this->team,
        };
    }

    public function getFormattedStatusAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'under_review' => 'Under Review',
            default => $this->status,
        };
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? now()->diffInYears($this->date_of_birth) : null;
    }
}
