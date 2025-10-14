<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (if not exists)
        User::firstOrCreate(
            ['email' => 'admin@citylife.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // Create test user (if not exists)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            // Core system data (run first)
            PermissionSeeder::class,
            RoleSeeder::class,
            UserRoleSeeder::class,

            // Website content
            AboutPageSeeder::class,
            BannerSeeder::class,
            BecomingSectionSeeder::class,

            // Members and related data
            MemberSeeder::class,

            // Courses
            CourseSeeder::class,
            CourseLessonSeeder::class,

            // Team and staff
            TeamMemberSeeder::class,

            // Departments and roles
            TechnicalDepartmentSeeder::class,
            WorshipDepartmentSeeder::class,
            PreacherDepartmentSeeder::class,
            DepRoleSeeder::class,

            // Events and activities
            EventSeeder::class,

            // Media and content
            TeachingSeriesSeeder::class,
            CityLifeTalkTimeSeeder::class,
            CityLifeMusicSeeder::class,

            // Ministries and missions
            MinistrySeeder::class,
            MissionSeeder::class,

            // Cafe system
            CafeSettingsSeeder::class,
            CafeDataSeeder::class,

            // Additional data (depends on members/users)
            ContactSubmissionSeeder::class,
            PastoralReminderSeeder::class,
            RotaSeeder::class,
        ]);
    }
}
