<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalDepartmentMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'technical_department_id',
        'member_id',
        'role',
        'tech_bio',
        'joined_date',
        'is_active',
        'is_head',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_head' => 'boolean',
        'joined_date' => 'date',
        'skills' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHeads($query)
    {
        return $query->where('is_head', true);
    }

    // Relationships
    public function technicalDepartment()
    {
        return $this->belongsTo(TechnicalDepartment::class);
    }

    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class);
    }

    // Accessor methods to get member data
    public function getNameAttribute()
    {
        return $this->member?->name;
    }

    public function getEmailAttribute()
    {
        return $this->member?->email;
    }

    public function getPhoneAttribute()
    {
        return $this->member?->phone;
    }

    public function getProfileImageAttribute()
    {
        return $this->member?->profile_picture;
    }
}
