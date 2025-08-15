<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ministry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'leader',
        'assistant_leader',
        'leader_image',
        'assistant_leader_image',
        'contact_email',
        'meeting_time',
        'meeting_location',
        'featured_image',
        'ministry_type',
        'is_featured',
        'requirements',
        'how_to_join',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relationships
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_ministry')->withPivot('role', 'joined_date', 'left_date', 'is_active')->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->belongsToMany(Member::class, 'member_ministry')->wherePivot('is_active', true)->withPivot('role', 'joined_date')->withTimestamps();
    }

    public function leaders()
    {
        return $this->belongsToMany(Member::class, 'member_ministry')->wherePivot('role', 'Leader')->wherePivot('is_active', true)->withPivot('joined_date')->withTimestamps();
    }
}
