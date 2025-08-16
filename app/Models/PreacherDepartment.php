<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreacherDepartment extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'head_of_department',
        'head_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(PreacherDepartmentMember::class);
    }

    public function activeMembers(): HasMany
    {
        return $this->hasMany(PreacherDepartmentMember::class)->where('is_active', true);
    }

    public function headMember(): HasMany
    {
        return $this->hasMany(PreacherDepartmentMember::class)->where('is_head', true);
    }
}
