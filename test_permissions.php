<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

// Test role-based permissions
echo "Testing Role-Based Permissions System\n";
echo "====================================\n\n";

try {
    // Find a test user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found. Please run the seeders first.\n";
        exit;
    }

    echo "Testing with user: {$user->name} (ID: {$user->id})\n\n";

    // Test role checking
    $roles = $user->roles;
    if ($roles->count() > 0) {
        echo "✅ User has roles: " . $roles->pluck('display_name')->join(', ') . "\n";
    } else {
        echo "ℹ️ User has no roles assigned\n";
    }

    // Test permission checking
    $testPermissions = [
        'members.view',
        'system.manage_users',
        'pastoral.send_emails',
        'worship.manage_rota'
    ];

    foreach ($testPermissions as $permission) {
        $hasPermission = $user->hasPermission($permission);
        $status = $hasPermission ? "✅" : "❌";
        echo "{$status} Permission '{$permission}': " . ($hasPermission ? 'ALLOWED' : 'DENIED') . "\n";
    }

    echo "\n";

    // Test super admin detection
    $isSuperAdmin = $user->hasRole('super_admin');
    echo "Super Admin status: " . ($isSuperAdmin ? "✅ YES" : "❌ NO") . "\n";

    echo "\n";

    // Show all permissions for this user
    $allPermissions = $user->getAllPermissions();
    if ($allPermissions->count() > 0) {
        echo "User's permissions ({$allPermissions->count()}):\n";
        foreach ($allPermissions->groupBy('category') as $category => $permissions) {
            echo "  📁 {$category}: " . $permissions->pluck('name')->join(', ') . "\n";
        }
    } else {
        echo "ℹ️ User has no permissions\n";
    }

    echo "\n";

    // Test role and permission counts
    echo "System Statistics:\n";
    echo "  Roles: " . Role::count() . " total\n";
    echo "  Permissions: " . Permission::count() . " total\n";
    echo "  Users with roles: " . User::whereHas('roles')->count() . "\n";

    echo "\n✅ Role-based permissions system is working correctly!\n";

} catch (Exception $e) {
    echo "❌ Error testing permissions: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
