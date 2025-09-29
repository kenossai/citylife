<?php

namespace App\Traits;

use App\Services\AuditLogger;

trait Auditable
{
    protected static function bootAuditable()
    {
        // Log when a model is created
        static::created(function ($model) {
            if (method_exists($model, 'getAuditableData')) {
                $data = $model->getAuditableData();
            } else {
                $data = $model->getAttributes();
            }

            AuditLogger::logResourceAction(
                'create',
                $model,
                null,
                $data,
                $model->getAuditCategory(),
                $model->getAuditSeverity(),
                $model->isAuditSensitive(),
                "Created {$model->getAuditResourceName()}"
            );
        });

        // Log when a model is updated
        static::updated(function ($model) {
            $oldValues = [];
            $newValues = [];

            foreach ($model->getDirty() as $key => $newValue) {
                $oldValues[$key] = $model->getOriginal($key);
                $newValues[$key] = $newValue;
            }

            // Only log if there are actual changes
            if (!empty($oldValues)) {
                AuditLogger::logResourceAction(
                    'update',
                    $model,
                    $oldValues,
                    $newValues,
                    $model->getAuditCategory(),
                    $model->getAuditSeverity(),
                    $model->isAuditSensitive(),
                    "Updated {$model->getAuditResourceName()}"
                );
            }
        });

        // Log when a model is deleted
        static::deleted(function ($model) {
            if (method_exists($model, 'getAuditableData')) {
                $data = $model->getAuditableData();
            } else {
                $data = $model->getAttributes();
            }

            AuditLogger::logResourceAction(
                'delete',
                $model,
                $data,
                null,
                $model->getAuditCategory(),
                'high', // Deletions are always high severity
                $model->isAuditSensitive(),
                "Deleted {$model->getAuditResourceName()}"
            );
        });
    }

    /**
     * Get the audit category for this model
     */
    public function getAuditCategory(): string
    {
        return $this->auditCategory ?? 'general';
    }

    /**
     * Get the audit severity level for this model
     */
    public function getAuditSeverity(): string
    {
        return $this->auditSeverity ?? 'medium';
    }

    /**
     * Determine if this model contains sensitive data
     */
    public function isAuditSensitive(): bool
    {
        return $this->auditSensitive ?? false;
    }

    /**
     * Get a display name for this resource in audit logs
     */
    public function getAuditResourceName(): string
    {
        return $this->name ?? $this->title ?? $this->display_name ?? class_basename(static::class) . " #{$this->id}";
    }

    /**
     * Get the data that should be audited (override in model if needed)
     */
    public function getAuditableData(): array
    {
        $hidden = $this->getHidden();
        $attributes = $this->getAttributes();

        // Remove password and other sensitive fields from audit data
        $sensitiveFields = ['password', 'remember_token', 'api_token'];

        foreach (array_merge($hidden, $sensitiveFields) as $field) {
            unset($attributes[$field]);
        }

        return $attributes;
    }

    /**
     * Log a custom audit event for this model
     */
    public function auditLog(string $action, string $description = null, array $context = []): void
    {
        AuditLogger::logResourceAction(
            $action,
            $this,
            null,
            null,
            $this->getAuditCategory(),
            $this->getAuditSeverity(),
            $this->isAuditSensitive(),
            $description ?? "Custom action: {$action} on {$this->getAuditResourceName()}"
        );
    }
}
