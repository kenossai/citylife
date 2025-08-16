<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepRole extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'department_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes for filtering
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDepartment($query, $departmentType)
    {
        return $query->where('department_type', $departmentType);
    }
}
