<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use App\Traits\Auditable;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true; // or add role check
    }
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable;

    // Audit configuration
    protected $auditCategory = 'personal';
    protected $auditSeverity = 'high';
    protected $auditSensitive = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'job_title',
        'department',
        'hire_date',
        'employment_status',
        'bio',
        'avatar',
        'preferences',
        'last_login_at',
        'last_login_ip',
        'is_active',
        'force_password_change',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hire_date' => 'date',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'force_password_change' => 'boolean',
            'preferences' => 'array',
        ];
    }

    // Relationships
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('assigned_at', 'assigned_by', 'expires_at', 'is_active', 'conditions')
            ->withTimestamps();
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    public function activeUserRoles(): HasMany
    {
        return $this->hasMany(UserRole::class)->active();
    }

    // Permission methods
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->count() === count($roles);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function hasPermissionInCategory(string $category): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('name', $permissions);
            })
            ->exists();
    }

    public function getAllPermissions(): Collection
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    // Role management methods
    public function assignRole(Role|string $role, ?User $assignedBy = null, ?\DateTimeInterface $expiresAt = null): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->userRoles()->updateOrCreate(
            ['role_id' => $role->id],
            [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy?->id,
                'expires_at' => $expiresAt,
                'is_active' => true,
            ]
        );
    }

    public function removeRole(Role|string $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->userRoles()->where('role_id', $role->id)->delete();
    }

    public function syncRoles(array $roles, ?User $assignedBy = null): void
    {
        $roleIds = [];
        foreach ($roles as $role) {
            if (is_string($role)) {
                $role = Role::where('name', $role)->firstOrFail();
            }
            $roleIds[] = $role->id;
        }

        // Remove roles not in the new list
        $this->userRoles()->whereNotIn('role_id', $roleIds)->delete();

        // Add new roles
        foreach ($roleIds as $roleId) {
            $this->userRoles()->updateOrCreate(
                ['role_id' => $roleId],
                [
                    'assigned_at' => now(),
                    'assigned_by' => $assignedBy?->id,
                    'is_active' => true,
                ]
            );
        }
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: $this->name;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: $this->email;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStaff($query)
    {
        return $query->whereIn('employment_status', ['active']);
    }

    public function scopeWithRole($query, string $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function scopeWithPermission($query, string $permission)
    {
        return $query->whereHas('roles.permissions', function ($q) use ($permission) {
            $q->where('name', $permission);
        });
    }
}
