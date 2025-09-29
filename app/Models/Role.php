<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'color',
        'priority',
        'is_system_role',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_system_role' => 'boolean',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'settings' => 'array',
    ];

    // Relationships
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
            ->withPivot('conditions')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withPivot('assigned_at', 'assigned_by', 'expires_at', 'is_active', 'conditions')
            ->withTimestamps();
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system_role', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system_role', false);
    }

    public function scopeOrderByPriority($query, $direction = 'desc')
    {
        return $query->orderBy('priority', $direction);
    }

    // Permission methods
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function hasPermissionInCategory(string $category): bool
    {
        return $this->permissions()->where('category', $category)->exists();
    }

    public function grantPermission(Permission|string $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }

    public function revokePermission(Permission|string $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->detach($permission->id);
    }

    // Static helper methods
    public static function getDefaultRoles(): array
    {
        return [
            'super_admin' => 'Super Administrator',
            'admin' => 'Administrator',
            'pastor' => 'Pastor',
            'finance_manager' => 'Finance Manager',
            'volunteer_coordinator' => 'Volunteer Coordinator',
            'member_coordinator' => 'Member Coordinator',
            'event_manager' => 'Event Manager',
            'communications_manager' => 'Communications Manager',
            'worship_leader' => 'Worship Leader',
            'technical_coordinator' => 'Technical Coordinator',
            'staff' => 'General Staff',
            'volunteer' => 'Volunteer',
        ];
    }
}
