<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorshipDepartment extends Model
{
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
        'sort_order' => 'integer',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(WorshipDepartmentMember::class);
    }

    public function activeMembers(): HasMany
    {
        return $this->hasMany(WorshipDepartmentMember::class)->where('is_active', true);
    }

    public function headMember(): HasMany
    {
        return $this->hasMany(WorshipDepartmentMember::class)->where('is_head', true);
    }
}
