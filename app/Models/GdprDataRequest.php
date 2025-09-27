<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GdprDataRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'request_type',
        'status',
        'request_details',
        'requested_data_types',
        'requested_at',
        'completed_at',
        'completion_notes',
        'processed_by',
        'exported_files',
    ];

    protected $casts = [
        'requested_data_types' => 'array',
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
        'exported_files' => 'array',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Get request types
    public static function getRequestTypes()
    {
        return [
            'export' => 'Data Export (Article 15 - Right of Access)',
            'deletion' => 'Data Deletion (Article 17 - Right to Erasure)',
            'rectification' => 'Data Rectification (Article 16 - Right to Rectification)',
            'portability' => 'Data Portability (Article 20 - Right to Data Portability)',
        ];
    }

    // Get status options
    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pending Review',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
        ];
    }

    // Get available data types for export/deletion
    public static function getDataTypes()
    {
        return [
            'personal_info' => 'Personal Information',
            'contact_details' => 'Contact Details',
            'membership_info' => 'Membership Information',
            'attendance_records' => 'Attendance Records',
            'giving_records' => 'Giving Records',
            'course_enrollments' => 'Course Enrollments',
            'pastoral_care' => 'Pastoral Care Records',
            'communications' => 'Communication History',
            'consents' => 'Consent Records',
            'audit_logs' => 'Audit Logs',
        ];
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('request_type', $type);
    }

    // Mark as processing
    public function markAsProcessing(): self
    {
        $this->update([
            'status' => 'processing',
            'processed_by' => Auth::user()->name ?? 'System',
        ]);

        GdprAuditLog::logAction([
            'member_id' => $this->member_id,
            'action' => 'data_request_processing',
            'description' => "GDPR {$this->request_type} request marked as processing",
            'old_values' => ['status' => 'pending'],
            'new_values' => ['status' => 'processing'],
        ]);

        return $this;
    }

    // Mark as completed
    public function markAsCompleted(string $notes = null, array $exportedFiles = []): self
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $notes,
            'exported_files' => $exportedFiles,
            'processed_by' => Auth::user()->name ?? 'System',
        ]);

        GdprAuditLog::logAction([
            'member_id' => $this->member_id,
            'action' => 'data_request_completed',
            'description' => "GDPR {$this->request_type} request completed",
            'old_values' => ['status' => 'processing'],
            'new_values' => ['status' => 'completed'],
        ]);

        return $this;
    }

    // Mark as rejected
    public function markAsRejected(string $reason): self
    {
        $this->update([
            'status' => 'rejected',
            'completion_notes' => $reason,
            'processed_by' => Auth::user()->name ?? 'System',
        ]);

        GdprAuditLog::logAction([
            'member_id' => $this->member_id,
            'action' => 'data_request_rejected',
            'description' => "GDPR {$this->request_type} request rejected: {$reason}",
            'old_values' => ['status' => $this->getOriginal('status')],
            'new_values' => ['status' => 'rejected', 'reason' => $reason],
        ]);

        return $this;
    }

    // Check if request is overdue (more than 30 days old)
    public function isOverdue(): bool
    {
        return $this->requested_at->diffInDays(now()) > 30 &&
               in_array($this->status, ['pending', 'processing']);
    }

    // Get days remaining for completion (GDPR requires response within 30 days)
    public function getDaysRemaining(): int
    {
        $daysPassed = $this->requested_at->diffInDays(now());
        return max(0, 30 - $daysPassed);
    }
}
