<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Has complete access to all system functions',
                'color' => 'red',
                'priority' => 1000,
                'is_system_role' => true,
                'permissions' => ['*'] // All permissions
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Has administrative access to most functions',
                'color' => 'orange',
                'priority' => 900,
                'is_system_role' => false,
                'permissions' => [
                    'members.*', 'courses.*', 'pastoral.*', 'worship.*', 'technical.*',
                    'gdpr.*', 'communications.*', 'reports.*'
                ]
            ],
            [
                'name' => 'pastor',
                'display_name' => 'Pastor',
                'description' => 'Access to pastoral care and member management',
                'color' => 'blue',
                'priority' => 800,
                'is_system_role' => false,
                'permissions' => [
                    'members.view_all', 'members.edit', 'pastoral.*',
                    'communications.send_emails', 'communications.send_sms',
                    'reports.view_analytics', 'reports.generate_reports',
                    'books.*'
                ]
            ],
            [
                'name' => 'member_coordinator',
                'display_name' => 'Member Coordinator',
                'description' => 'Manages member information and data',
                'color' => 'green',
                'priority' => 700,
                'is_system_role' => false,
                'permissions' => [
                    'members.view_all', 'members.create', 'members.edit',
                    'members.export', 'courses.view_all', 'courses.manage_enrollments',
                    'communications.send_emails', 'gdpr.manage_consents'
                ]
            ],
            [
                'name' => 'worship_leader',
                'display_name' => 'Worship Leader',
                'description' => 'Manages worship departments and schedules',
                'color' => 'purple',
                'priority' => 600,
                'is_system_role' => false,
                'permissions' => [
                    'worship.*', 'members.view_all',
                    'communications.send_emails'
                ]
            ],
            [
                'name' => 'technical_coordinator',
                'display_name' => 'Technical Coordinator',
                'description' => 'Manages technical departments and equipment',
                'color' => 'indigo',
                'priority' => 600,
                'is_system_role' => false,
                'permissions' => [
                    'technical.*', 'members.view_all',
                    'communications.send_emails'
                ]
            ],
            [
                'name' => 'volunteer_coordinator',
                'display_name' => 'Volunteer Coordinator',
                'description' => 'Manages volunteers and department assignments',
                'color' => 'teal',
                'priority' => 500,
                'is_system_role' => false,
                'permissions' => [
                    'members.view_all', 'worship.manage_departments',
                    'technical.manage_departments', 'communications.send_emails'
                ]
            ],
            [
                'name' => 'communications_manager',
                'display_name' => 'Communications Manager',
                'description' => 'Manages all communications with members',
                'color' => 'pink',
                'priority' => 400,
                'is_system_role' => false,
                'permissions' => [
                    'communications.*', 'members.view_all',
                    'reports.view_analytics'
                ]
            ],
            [
                'name' => 'staff',
                'display_name' => 'General Staff',
                'description' => 'Basic staff access with limited permissions',
                'color' => 'gray',
                'priority' => 200,
                'is_system_role' => false,
                'permissions' => [
                    'members.view_all', 'reports.view_analytics'
                ]
            ],
            [
                'name' => 'volunteer',
                'display_name' => 'Volunteer',
                'description' => 'Limited access for volunteers',
                'color' => 'yellow',
                'priority' => 100,
                'is_system_role' => false,
                'permissions' => [
                    'reports.view_analytics'
                ]
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = \App\Models\Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // Assign permissions to role
            if (in_array('*', $permissions)) {
                // Assign all permissions for super admin
                $allPermissions = \App\Models\Permission::all();
                $role->permissions()->sync($allPermissions->pluck('id'));
            } else {
                $permissionIds = [];
                foreach ($permissions as $permissionPattern) {
                    if (str_ends_with($permissionPattern, '.*')) {
                        // Pattern like 'members.*'
                        $category = str_replace('.*', '', $permissionPattern);
                        $categoryPermissions = \App\Models\Permission::where('category', $category)->get();
                        $permissionIds = array_merge($permissionIds, $categoryPermissions->pluck('id')->toArray());
                    } else {
                        // Exact permission name
                        $permission = \App\Models\Permission::where('name', $permissionPattern)->first();
                        if ($permission) {
                            $permissionIds[] = $permission->id;
                        }
                    }
                }
                $role->permissions()->sync(array_unique($permissionIds));
            }
        }

        $this->command->info('Roles and role-permission assignments seeded successfully!');
    }
}
