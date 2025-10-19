<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin user already exists
        $existingAdmin = User::where('email', 'admin@citylife.com')->first();
        
        if ($existingAdmin) {
            echo "Admin user already exists with email: admin@citylife.com\n";
            return;
        }

        // Create admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@citylife.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Assign super admin role if it exists
        try {
            $user->assignRole('super_admin');
            echo "Admin user created successfully with super_admin role!\n";
        } catch (Exception $e) {
            echo "Admin user created but role assignment failed: " . $e->getMessage() . "\n";
        }

        echo "Email: admin@citylife.com\n";
        echo "Password: password123\n";
    }
}