<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermission('system.manage_roles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermission('system.manage_roles');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermission('system.manage_roles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        // Prevent non-super-admins from editing system roles
        if ($role->is_system_role && !$user->hasRole('super_admin')) {
            return false;
        }

        return $user->hasRole('super_admin') || $user->hasPermission('system.manage_roles');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        // System roles cannot be deleted
        if ($role->is_system_role) {
            return false;
        }

        // Roles with assigned users cannot be deleted
        if ($role->users()->count() > 0) {
            return false;
        }

        return $user->hasRole('super_admin') || $user->hasPermission('system.manage_roles');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole('super_admin') || $user->hasPermission('system.manage_roles');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        // Only super admin can force delete roles
        return $user->hasRole('super_admin');
    }
}
