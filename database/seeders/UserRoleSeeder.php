<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign super admin role to admin@citylife.com
        $admin = \App\Models\User::where('email', 'admin@citylife.com')->first();
        if ($admin) {
            $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
            if ($superAdminRole) {
                $admin->assignRole($superAdminRole);
                $this->command->info('Super admin role assigned to admin@citylife.com');
            }
        }

        // Assign staff role to test@example.com
        $testUser = \App\Models\User::where('email', 'test@example.com')->first();
        if ($testUser) {
            $staffRole = \App\Models\Role::where('name', 'staff')->first();
            if ($staffRole) {
                $testUser->assignRole($staffRole);
                $this->command->info('Staff role assigned to test@example.com');
            }
        }

        // Update user fields for better display
        \App\Models\User::where('email', 'admin@citylife.com')->update([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'job_title' => 'System Administrator',
            'department' => 'IT',
            'employment_status' => 'active',
            'is_active' => true,
            'hire_date' => now()->subYears(2),
        ]);

        \App\Models\User::where('email', 'test@example.com')->update([
            'first_name' => 'Test',
            'last_name' => 'Staff',
            'job_title' => 'Staff Member',
            'department' => 'General',
            'employment_status' => 'active',
            'is_active' => true,
            'hire_date' => now()->subMonths(6),
        ]);

        $this->command->info('User roles and information updated successfully!');
    }
}
