<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rota extends Model
{
    protected $fillable = [
        'title',
        'description',
        'department_type',
        'departments',
        'start_date',
        'end_date',
        'schedule_data',
        'notes',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'schedule_data' => 'array',
        'departments' => 'array',
        'is_published' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get department members for this rota's department type
    public function getDepartmentMembers()
    {
        switch ($this->department_type) {
            case 'worship':
                return WorshipDepartmentMember::with(['member', 'worshipDepartment'])
                    ->where('is_active', true)
                    ->get();
            case 'technical':
                return TechnicalDepartmentMember::with(['member', 'technicalDepartment'])
                    ->where('is_active', true)
                    ->get();
            case 'preacher':
                return PreacherDepartmentMember::with(['member', 'preacherDepartment'])
                    ->where('is_active', true)
                    ->get();
            default:
                return collect();
        }
    }

    // Get available roles for this department type
    public function getAvailableRoles()
    {
        return DepRole::active()
            ->forDepartment($this->department_type)
            ->get();
    }
}
