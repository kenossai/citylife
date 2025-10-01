<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Test last_login_at functionality
echo "Testing last_login_at field...\n";
echo "===============================\n\n";

try {
    // Get first user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found in the database.\n";
        exit;
    }

    echo "Testing with user: {$user->name} (ID: {$user->id})\n";
    echo "Current last_login_at: " . ($user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'NULL') . "\n\n";

    // Update last_login_at
    $user->update([
        'last_login_at' => now(),
        'last_login_ip' => '127.0.0.1'
    ]);

    // Refresh and check
    $user->refresh();
    echo "✅ Updated last_login_at to: " . $user->last_login_at->format('Y-m-d H:i:s') . "\n";
    echo "✅ Updated last_login_ip to: " . $user->last_login_ip . "\n\n";

    // Check other users
    $usersWithoutLogin = User::whereNull('last_login_at')->count();
    $usersWithLogin = User::whereNotNull('last_login_at')->count();

    echo "Users without last_login_at: {$usersWithoutLogin}\n";
    echo "Users with last_login_at: {$usersWithLogin}\n\n";

    echo "✅ last_login_at field is working correctly!\n";
    echo "The login event listener should now update this field automatically when users log in.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
