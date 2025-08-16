<?php

namespace Database\Seeders;

use App\Models\WorshipDepartment;
use App\Models\WorshipDepartmentMember;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorshipDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Vocals',
                'slug' => 'vocals',
                'description' => 'Lead vocals, backing vocals, and choir members who lead the congregation in worship through song.',
                'head_of_department' => 'Sarah Johnson',
                'contact_email' => 'vocals@citylife.local',
                'requirements' => 'Good vocal range, ability to harmonize, commitment to regular practice, heart for worship ministry.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Instruments',
                'slug' => 'instruments',
                'description' => 'Musicians playing various instruments including guitar, bass, drums, piano, and other instruments.',
                'head_of_department' => 'Michael Thompson',
                'contact_email' => 'instruments@citylife.local',
                'requirements' => 'Proficiency in at least one instrument, ability to play contemporary worship songs, team player attitude.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Dance Ministry',
                'slug' => 'dance-ministry',
                'description' => 'Worship through movement and dance, expressing praise and worship through choreographed performances.',
                'head_of_department' => 'Grace Williams',
                'contact_email' => 'dance@citylife.local',
                'requirements' => 'Dance experience preferred, willingness to learn choreography, modest attire, heart for worship.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Worship Leadership',
                'slug' => 'worship-leadership',
                'description' => 'Leaders who coordinate worship services, select songs, and guide the worship experience.',
                'head_of_department' => 'David Miller',
                'contact_email' => 'worship-leader@citylife.local',
                'requirements' => 'Strong musical background, leadership experience, theological understanding of worship, communication skills.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Creative Arts',
                'slug' => 'creative-arts',
                'description' => 'Visual arts, banners, stage design, and other creative expressions for worship services.',
                'head_of_department' => 'Emma Davis',
                'contact_email' => 'creative@citylife.local',
                'requirements' => 'Artistic skills, creativity, ability to work with themes and seasonal decorations.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            WorshipDepartment::updateOrCreate(
                ['slug' => $dept['slug']],
                $dept
            );
        }

        // Create some sample worship department members if members exist
        $members = Member::limit(10)->get();
        $worshipDepartments = WorshipDepartment::all();

        if ($members->count() > 0 && $worshipDepartments->count() > 0) {
            $roles = [
                'vocals' => ['Lead Vocalist', 'Backing Vocalist', 'Choir Member', 'Worship Leader'],
                'instruments' => ['Lead Guitarist', 'Bass Guitarist', 'Drummer', 'Pianist', 'Keyboardist'],
                'dance-ministry' => ['Lead Dancer', 'Choreographer', 'Dance Team Member'],
                'worship-leadership' => ['Worship Pastor', 'Music Director', 'Song Leader'],
                'creative-arts' => ['Visual Artist', 'Banner Designer', 'Stage Designer', 'Creative Director'],
            ];

            foreach ($worshipDepartments as $department) {
                // Add 2-4 random members to each department
                $memberCount = rand(2, 4);
                $selectedMembers = $members->random($memberCount);

                foreach ($selectedMembers as $index => $member) {
                    // Skip if member is already in this department
                    if (WorshipDepartmentMember::where('worship_department_id', $department->id)
                                               ->where('member_id', $member->id)
                                               ->exists()) {
                        continue;
                    }

                    $deptRoles = $roles[$department->slug] ?? ['Team Member'];

                    WorshipDepartmentMember::create([
                        'worship_department_id' => $department->id,
                        'member_id' => $member->id,
                        'role' => $deptRoles[array_rand($deptRoles)],
                        'worship_bio' => 'Passionate worship team member with experience in church ministry.',
                        'joined_date' => now()->subMonths(rand(1, 24)),
                        'is_active' => true,
                        'is_head' => $index === 0, // First member is head
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }
    }
}
