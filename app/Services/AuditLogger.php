<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log(array $data): AuditTrail
    {
        $user = Auth::user();

        // Determine if we should set user_id based on user type
        $userId = null;
        $userName = null;
        $userEmail = null;

        if ($user) {
            // Only set user_id if the user is from the users table
            if ($user instanceof \App\Models\User) {
                $userId = $user->id;
                $userName = $user->name ?? $user->full_name ?? ($user->first_name . ' ' . $user->last_name);
                $userEmail = $user->email;
            } elseif ($user instanceof \App\Models\Member) {
                // For members, don't set user_id (since they're not in users table)
                $userId = null;
                $userName = $user->full_name ?? ($user->first_name . ' ' . $user->last_name);
                $userEmail = $user->email;
            }
        }

        $auditData = array_merge([
            'user_id' => $userId,
            'user_name' => $userName,
            'user_email' => $userEmail,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'resource_type' => 'General',
            'category' => 'general',
            'severity' => 'medium',
            'is_sensitive' => false,
        ], $data);

        return AuditTrail::create($auditData);
    }

    public static function logResourceAction(
        string $action,
        $resource,
        array $oldValues = null,
        array $newValues = null,
        string $category = 'general',
        string $severity = 'medium',
        bool $isSensitive = false,
        string $description = null
    ): AuditTrail {
        $resourceType = is_string($resource) ? $resource : get_class($resource);
        $resourceId = is_object($resource) ? $resource->id : null;
        $resourceName = null;

        // Try to get a display name for the resource
        if (is_object($resource)) {
            $resourceName = $resource->name ?? $resource->title ?? $resource->display_name ?? null;
        }

        return self::log([
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'resource_name' => $resourceName,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'category' => $category,
            'severity' => $severity,
            'is_sensitive' => $isSensitive,
            'description' => $description,
        ]);
    }

    public static function logAuthentication(string $action, $user = null): AuditTrail
    {
        $user = $user ?? Auth::user();

        // Determine if we should set user_id based on user type
        $userId = null;
        $userName = null;
        $userEmail = null;

        if ($user) {
            // Only set user_id if the user is from the users table
            if ($user instanceof \App\Models\User) {
                $userId = $user->id;
                $userName = $user->name ?? $user->full_name ?? ($user->first_name . ' ' . $user->last_name);
                $userEmail = $user->email;
            } elseif ($user instanceof \App\Models\Member) {
                // For members, don't set user_id (since they're not in users table)
                $userId = null;
                $userName = $user->full_name ?? ($user->first_name . ' ' . $user->last_name);
                $userEmail = $user->email;
            }
        }

        $auditData = [
            'user_id' => $userId,
            'user_name' => $userName,
            'user_email' => $userEmail,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'action' => $action,
            'resource_type' => $user ? get_class($user) : 'Unknown',
            'resource_id' => $user?->id,
            'resource_name' => $userName,
            'category' => 'authentication',
            'severity' => $action === 'login' ? 'low' : 'medium',
            'is_sensitive' => false,
            'description' => "User {$action}",
            'context' => [
                'user_type' => $user ? get_class($user) : 'Unknown',
                'user_email' => $userEmail,
            ],
        ];

        return AuditTrail::create($auditData);
    }

    public static function logSensitiveAccess(
        $resource,
        string $action = 'view',
        string $description = null
    ): AuditTrail {
        return self::logResourceAction(
            $action,
            $resource,
            null,
            null,
            'sensitive',
            'high',
            true,
            $description ?? "Accessed sensitive {$action}"
        );
    }

    public static function logDataExport(
        string $resourceType,
        int $recordCount,
        string $format = 'unknown'
    ): AuditTrail {
        return self::log([
            'action' => 'export',
            'resource_type' => $resourceType,
            'resource_id' => null,
            'resource_name' => null,
            'category' => 'sensitive',
            'severity' => 'high',
            'is_sensitive' => true,
            'description' => "Exported {$recordCount} {$resourceType} records in {$format} format",
            'context' => [
                'record_count' => $recordCount,
                'export_format' => $format,
            ],
        ]);
    }

    public static function logBulkAction(
        string $action,
        string $resourceType,
        int $recordCount,
        array $recordIds = []
    ): AuditTrail {
        return self::log([
            'action' => 'bulk_action',
            'resource_type' => $resourceType,
            'category' => 'administration',
            'severity' => 'medium',
            'is_sensitive' => false,
            'description' => "Performed {$action} on {$recordCount} {$resourceType} records",
            'context' => [
                'bulk_action' => $action,
                'record_count' => $recordCount,
                'record_ids' => $recordIds,
            ],
        ]);
    }

    public static function logSystemAction(
        string $action,
        string $description,
        string $severity = 'medium'
    ): AuditTrail {
        return self::log([
            'action' => $action,
            'resource_type' => 'System',
            'category' => 'system',
            'severity' => $severity,
            'is_sensitive' => false,
            'description' => $description,
        ]);
    }
}
