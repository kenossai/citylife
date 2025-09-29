<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status',
        'file_path',
        'file_size',
        'compression',
        'started_at',
        'completed_at',
        'duration_seconds',
        'database_tables',
        'file_directories',
        'records_count',
        'total_size',
        'checksum',
        'encryption',
        'is_restorable',
        'expires_at',
        'restore_notes',
        'error_message',
        'error_details',
        'retry_count',
        'created_by',
        'trigger_type',
        'config',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'database_tables' => 'array',
        'file_directories' => 'array',
        'error_details' => 'array',
        'config' => 'array',
        'is_restorable' => 'boolean',
        'file_size' => 'integer',
        'total_size' => 'integer',
        'records_count' => 'integer',
        'duration_seconds' => 'integer',
        'retry_count' => 'integer',
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRestorable($query)
    {
        return $query->where('is_restorable', true)->where('status', 'completed');
    }

    // Helper methods
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = $this->file_size;

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'success',
            'running' => 'warning',
            'failed' => 'danger',
            'partial' => 'warning',
            'pending' => 'gray',
            default => 'gray'
        };
    }

    public function canRestore(): bool
    {
        return $this->is_restorable &&
               $this->status === 'completed' &&
               $this->file_path &&
               file_exists(storage_path('app/backups/' . $this->file_path));
    }

    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'running' => 'Running',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'partial' => 'Partial',
        ];
    }

    public static function getTypes(): array
    {
        return [
            'database' => 'Database Only',
            'files' => 'Files Only',
            'full' => 'Full System',
        ];
    }
}
