<?php

namespace Database\Seeders;

use App\Models\DepRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepRoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $roles = [
            // Worship Department Roles
            ['name' => 'Lead Vocalist', 'department_type' => 'worship'],
            ['name' => 'Background Vocalist', 'department_type' => 'worship'],
            ['name' => 'Worship Leader', 'department_type' => 'worship'],
            ['name' => 'Guitarist', 'department_type' => 'worship'],
            ['name' => 'Bassist', 'department_type' => 'worship'],
            ['name' => 'Drummer', 'department_type' => 'worship'],
            ['name' => 'Keyboardist', 'department_type' => 'worship'],
            ['name' => 'Violinist', 'department_type' => 'worship'],
            ['name' => 'Saxophonist', 'department_type' => 'worship'],
            ['name' => 'Dancer', 'department_type' => 'worship'],

            // Technical Department Roles
            ['name' => 'Sound Engineer', 'department_type' => 'technical'],
            ['name' => 'Camera Operator', 'department_type' => 'technical'],
            ['name' => 'Graphics Designer', 'department_type' => 'technical'],
            ['name' => 'Lighting Technician', 'department_type' => 'technical'],
            ['name' => 'Video Editor', 'department_type' => 'technical'],
            ['name' => 'Live Streaming Operator', 'department_type' => 'technical'],
            ['name' => 'Technical Director', 'department_type' => 'technical'],
            ['name' => 'IT Support', 'department_type' => 'technical'],

            // Preacher Department Roles
            ['name' => 'Lead Pastor', 'department_type' => 'preacher'],
            ['name' => 'Assistant Pastor', 'department_type' => 'preacher'],
            ['name' => 'Youth Pastor', 'department_type' => 'preacher'],
            ['name' => 'Children Pastor', 'department_type' => 'preacher'],
            ['name' => 'Bible Teacher', 'department_type' => 'preacher'],
            ['name' => 'Evangelist', 'department_type' => 'preacher'],
            ['name' => 'Elder', 'department_type' => 'preacher'],
            ['name' => 'Deacon', 'department_type' => 'preacher'],
        ];

        foreach ($roles as $role) {
            DepRole::updateOrCreate(
                ['name' => $role['name'], 'department_type' => $role['department_type']],
                [
                    'slug' => Str::slug($role['name']),
                    'is_active' => true,
                ]
            );
        }
    }
}
