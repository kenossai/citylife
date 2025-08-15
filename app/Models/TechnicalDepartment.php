<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TechnicalDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'head_of_department',
        'head_image',
        'contact_email',
        'requirements',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
        return $this->hasMany(TechnicalDepartmentMember::class);
    }

    public function activeMembers()
    {
        return $this->hasMany(TechnicalDepartmentMember::class)->where('is_active', true);
    }

    public function headOfDepartment()
    {
        return $this->hasOne(TechnicalDepartmentMember::class)->where('is_head', true)->where('is_active', true);
    }
}
