<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class GdprAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'action',
        'data_type',
        'description',
        'old_values',
        'new_values',
        'performed_by',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Get audit action types
    public static function getActionTypes()
    {
        return [
            'data_access' => 'Data Access',
            'data_export' => 'Data Export',
            'data_deletion' => 'Data Deletion',
            'data_modification' => 'Data Modification',
            'consent_granted' => 'Consent Granted',
            'consent_withdrawn' => 'Consent Withdrawn',
            'privacy_settings_changed' => 'Privacy Settings Changed',
            'data_request_created' => 'Data Request Created',
            'data_request_processed' => 'Data Request Processed',
        ];
    }

    // Create audit log entry
    public static function logAction(array $data): self
    {
        return self::create(array_merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_by' => Auth::check() ? Auth::user()->name : 'System',
        ], $data));
    }

    // Scope for specific member
    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    // Scope for specific action
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Scope for date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
