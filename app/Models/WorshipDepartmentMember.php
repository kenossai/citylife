<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorshipDepartmentMember extends Model
{
    protected $fillable = [
        'worship_department_id',
        'member_id',
        'role',
        'worship_bio',
        'joined_date',
        'is_active',
        'is_head',
        'sort_order',
    ];

    protected $casts = [
        'joined_date' => 'date',
        'is_active' => 'boolean',
        'is_head' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function worshipDepartment(): BelongsTo
    {
        return $this->belongsTo(WorshipDepartment::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
