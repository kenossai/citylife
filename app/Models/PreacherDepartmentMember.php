<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreacherDepartmentMember extends Model
{
    protected $fillable = [
        'preacher_department_id',
        'member_id',
        'role',
        'joined_date',
        'is_active',
        'is_head',
    ];

    protected $casts = [
        'joined_date' => 'date',
        'is_active' => 'boolean',
        'is_head' => 'boolean',
    ];

    public function preacherDepartment(): BelongsTo
    {
        return $this->belongsTo(PreacherDepartment::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
