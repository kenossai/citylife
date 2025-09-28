<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // System Administration
            ['name' => 'system.manage_users', 'display_name' => 'Manage Staff Users', 'category' => 'system', 'description' => 'Create, edit, and delete staff user accounts', 'is_system_permission' => true],
            ['name' => 'system.manage_roles', 'display_name' => 'Manage Roles & Permissions', 'category' => 'system', 'description' => 'Create, edit roles and assign permissions', 'is_system_permission' => true],
            ['name' => 'system.view_logs', 'display_name' => 'View System Logs', 'category' => 'system', 'description' => 'Access system logs and audit trails', 'is_system_permission' => true],
            ['name' => 'system.backup_restore', 'display_name' => 'Backup & Restore', 'category' => 'system', 'description' => 'Create backups and restore system data', 'is_system_permission' => true],
            ['name' => 'system.settings', 'display_name' => 'System Settings', 'category' => 'system', 'description' => 'Modify system-wide settings and configuration', 'is_system_permission' => true],

            // Member Management
            ['name' => 'members.view_all', 'display_name' => 'View All Members', 'category' => 'members', 'description' => 'View all member profiles and information'],
            ['name' => 'members.create', 'display_name' => 'Create Members', 'category' => 'members', 'description' => 'Add new members to the system'],
            ['name' => 'members.edit', 'display_name' => 'Edit Members', 'category' => 'members', 'description' => 'Modify member information and profiles'],
            ['name' => 'members.delete', 'display_name' => 'Delete Members', 'category' => 'members', 'description' => 'Remove members from the system'],
            ['name' => 'members.export', 'display_name' => 'Export Member Data', 'category' => 'members', 'description' => 'Export member data to various formats'],
            ['name' => 'members.view_sensitive', 'display_name' => 'View Sensitive Data', 'category' => 'members', 'description' => 'Access sensitive member information (SSN, medical info, etc.)'],

            // Course Management
            ['name' => 'courses.view_all', 'display_name' => 'View All Courses', 'category' => 'courses', 'description' => 'View all courses and enrollments'],
            ['name' => 'courses.create', 'display_name' => 'Create Courses', 'category' => 'courses', 'description' => 'Create new courses and programs'],
            ['name' => 'courses.edit', 'display_name' => 'Edit Courses', 'category' => 'courses', 'description' => 'Modify course content and settings'],
            ['name' => 'courses.delete', 'display_name' => 'Delete Courses', 'category' => 'courses', 'description' => 'Remove courses from the system'],
            ['name' => 'courses.manage_enrollments', 'display_name' => 'Manage Enrollments', 'category' => 'courses', 'description' => 'Enroll/unenroll members from courses'],

            // Pastoral Care
            ['name' => 'pastoral.view_all', 'display_name' => 'View Pastoral Information', 'category' => 'pastoral', 'description' => 'View pastoral care information and reminders'],
            ['name' => 'pastoral.create_reminders', 'display_name' => 'Create Pastoral Reminders', 'category' => 'pastoral', 'description' => 'Create pastoral care reminders'],
            ['name' => 'pastoral.manage_visits', 'display_name' => 'Manage Visits', 'category' => 'pastoral', 'description' => 'Manage and track pastoral visits'],
            ['name' => 'pastoral.confidential_notes', 'display_name' => 'Access Confidential Notes', 'category' => 'pastoral', 'description' => 'Access confidential pastoral notes'],

            // Worship Management
            ['name' => 'worship.manage_departments', 'display_name' => 'Manage Worship Departments', 'category' => 'worship', 'description' => 'Manage worship departments and members'],
            ['name' => 'worship.manage_schedules', 'display_name' => 'Manage Worship Schedules', 'category' => 'worship', 'description' => 'Create and manage worship schedules/rotas'],
            ['name' => 'worship.assign_roles', 'display_name' => 'Assign Worship Roles', 'category' => 'worship', 'description' => 'Assign roles to worship team members'],

            // Technical Management
            ['name' => 'technical.manage_departments', 'display_name' => 'Manage Technical Departments', 'category' => 'technical', 'description' => 'Manage technical departments and members'],
            ['name' => 'technical.manage_schedules', 'display_name' => 'Manage Technical Schedules', 'category' => 'technical', 'description' => 'Create and manage technical schedules/rotas'],
            ['name' => 'technical.manage_equipment', 'display_name' => 'Manage Equipment', 'category' => 'technical', 'description' => 'Manage technical equipment and inventory'],

            // GDPR & Compliance
            ['name' => 'gdpr.manage_consents', 'display_name' => 'Manage GDPR Consents', 'category' => 'gdpr', 'description' => 'Manage GDPR consent records'],
            ['name' => 'gdpr.process_requests', 'display_name' => 'Process Data Requests', 'category' => 'gdpr', 'description' => 'Process GDPR data subject requests'],
            ['name' => 'gdpr.view_audit_logs', 'display_name' => 'View GDPR Audit Logs', 'category' => 'gdpr', 'description' => 'View GDPR compliance audit trails'],
            ['name' => 'gdpr.export_data', 'display_name' => 'Export Member Data', 'category' => 'gdpr', 'description' => 'Export member data for GDPR compliance'],

            // Communications
            ['name' => 'communications.send_emails', 'display_name' => 'Send Emails', 'category' => 'communications', 'description' => 'Send emails to members and groups'],
            ['name' => 'communications.send_sms', 'display_name' => 'Send SMS', 'category' => 'communications', 'description' => 'Send SMS messages to members'],
            ['name' => 'communications.manage_templates', 'display_name' => 'Manage Templates', 'category' => 'communications', 'description' => 'Create and edit communication templates'],

            // Reports & Analytics
            ['name' => 'reports.view_analytics', 'display_name' => 'View Analytics', 'category' => 'reports', 'description' => 'View system analytics and dashboards'],
            ['name' => 'reports.generate_reports', 'display_name' => 'Generate Reports', 'category' => 'reports', 'description' => 'Generate various reports'],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
