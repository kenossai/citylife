<?php

namespace Database\Seeders;

use App\Models\TechnicalDepartment;
use App\Models\TechnicalDepartmentMember;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnicalDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Audio/PA System',
                'slug' => 'audio-pa-system',
                'description' => 'Sound engineering, mixing, and audio equipment management for worship services and events.',
                'head_of_department' => 'James Wilson',
                'contact_email' => 'audio@citylife.local',
                'requirements' => 'Basic understanding of audio equipment, willingness to learn sound mixing, attention to detail.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Media & Projection',
                'slug' => 'media-projection',
                'description' => 'Operating projection systems, slides, videos, and visual media during services.',
                'head_of_department' => 'Lisa Anderson',
                'contact_email' => 'media@citylife.local',
                'requirements' => 'Computer literacy, attention to timing, familiarity with presentation software.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Lighting',
                'slug' => 'lighting',
                'description' => 'Stage lighting design and operation to enhance worship atmosphere and visibility.',
                'head_of_department' => 'Robert Garcia',
                'contact_email' => 'lighting@citylife.local',
                'requirements' => 'Understanding of lighting systems, creativity with ambiance, technical aptitude.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Live Streaming',
                'slug' => 'live-streaming',
                'description' => 'Broadcasting services online, managing cameras, and ensuring quality live streams.',
                'head_of_department' => 'Jennifer Brown',
                'contact_email' => 'streaming@citylife.local',
                'requirements' => 'Video production knowledge, internet streaming experience, camera operation skills.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'IT Support',
                'slug' => 'it-support',
                'description' => 'Technical support for all church technology, network maintenance, and troubleshooting.',
                'head_of_department' => 'Kevin Martinez',
                'contact_email' => 'it@citylife.local',
                'requirements' => 'IT background, troubleshooting skills, network knowledge, problem-solving abilities.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            TechnicalDepartment::updateOrCreate(
                ['slug' => $dept['slug']],
                $dept
            );
        }

        // Create some sample technical department members if members exist
        $members = Member::limit(12)->get();
        $techDepartments = TechnicalDepartment::all();

        if ($members->count() > 0 && $techDepartments->count() > 0) {
            $roles = [
                'audio-pa-system' => ['Sound Engineer', 'Audio Technician', 'Mix Engineer', 'PA Operator'],
                'media-projection' => ['Media Operator', 'Slide Technician', 'Video Coordinator', 'Graphics Operator'],
                'lighting' => ['Lighting Designer', 'Light Operator', 'Stage Technician'],
                'live-streaming' => ['Stream Director', 'Camera Operator', 'Video Editor', 'Broadcast Technician'],
                'it-support' => ['IT Manager', 'Network Administrator', 'Help Desk Technician', 'Systems Administrator'],
            ];

            foreach ($techDepartments as $department) {
                // Add 2-3 random members to each department
                $memberCount = rand(2, 3);
                $selectedMembers = $members->random($memberCount);

                foreach ($selectedMembers as $index => $member) {
                    // Skip if member is already in this department
                    if (TechnicalDepartmentMember::where('technical_department_id', $department->id)
                                                 ->where('member_id', $member->id)
                                                 ->exists()) {
                        continue;
                    }

                    $deptRoles = $roles[$department->slug] ?? ['Technician'];

                    TechnicalDepartmentMember::create([
                        'technical_department_id' => $department->id,
                        'member_id' => $member->id,
                        'role' => $deptRoles[array_rand($deptRoles)],
                        'tech_bio' => 'Experienced technical team member serving the church\'s technology needs.',
                        'joined_date' => now()->subMonths(rand(1, 36)),
                        'is_active' => true,
                        'is_head' => $index === 0, // First member is head
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }
    }
}
