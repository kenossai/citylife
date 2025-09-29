<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action',
        'resource_type',
        'resource_id',
        'resource_name',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'old_values',
        'new_values',
        'attributes',
        'category',
        'severity',
        'is_sensitive',
        'description',
        'context',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'attributes' => 'array',
        'context' => 'array',
        'is_sensitive' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeSensitive($query)
    {
        return $query->where('is_sensitive', true);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByResource($query, string $resourceType, string $resourceId = null)
    {
        $query = $query->where('resource_type', $resourceType);

        if ($resourceId) {
            $query->where('resource_id', $resourceId);
        }

        return $query;
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Helper methods
    public function getResourceTypeNameAttribute(): string
    {
        return class_basename($this->resource_type);
    }

    public function getUserDisplayNameAttribute(): string
    {
        return $this->user ? $this->user->name : ($this->user_name ?? 'Unknown User');
    }

    public static function getActions(): array
    {
        return [
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'restore' => 'Restored',
            'view' => 'Viewed',
            'export' => 'Exported',
            'import' => 'Imported',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'access' => 'Accessed',
            'download' => 'Downloaded',
            'upload' => 'Uploaded',
            'search' => 'Searched',
            'bulk_action' => 'Bulk Action',
        ];
    }

    public static function getCategories(): array
    {
        return [
            'general' => 'General',
            'sensitive' => 'Sensitive Data',
            'financial' => 'Financial',
            'personal' => 'Personal Information',
            'medical' => 'Medical/Health',
            'pastoral' => 'Pastoral Care',
            'membership' => 'Membership',
            'authentication' => 'Authentication',
            'administration' => 'Administration',
            'system' => 'System',
        ];
    }

    public static function getSeverityLevels(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ];
    }
}
