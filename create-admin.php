<?php

/**
 * Quick admin user creation script for Laravel Cloud
 * Run with: php create-admin.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Creating admin user...\n";

// Delete existing admin if it exists
User::where('email', 'admin@citylife.com')->delete();

// Create new admin user
$user = User::create([
    'name' => 'Admin User',
    'email' => 'admin@citylife.com',
    'password' => Hash::make('Admin@2025!'),
    'email_verified_at' => now(),
]);

echo "✅ Admin user created successfully!\n";
echo "Email: admin@citylife.com\n";
echo "Password: Admin@2025!\n";
echo "\nYou can now login at /admin\n";
