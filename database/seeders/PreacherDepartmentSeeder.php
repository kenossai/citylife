<?php

namespace Database\Seeders;

use App\Models\PreacherDepartment;
use App\Models\PreacherDepartmentMember;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PreacherDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Sunday Service',
                'slug' => 'sunday-service',
                'description' => 'Main Sunday worship service preaching team',
                'head_of_department' => 'Pastor Jim Master',
                'is_active' => true,
            ],
            [
                'name' => 'Youth Ministry',
                'slug' => 'youth-ministry',
                'description' => 'Preaching and teaching for youth services and events',
                'head_of_department' => 'James Cutts',
                'is_active' => true,
            ],
            [
                'name' => 'Bible Study',
                'slug' => 'bible-study',
                'description' => 'Teaching team for small groups and Bible study sessions',
                'head_of_department' => 'Terence Williams',
                'is_active' => true,
            ],
            [
                'name' => 'Special Events',
                'slug' => 'special-events',
                'description' => 'Preaching team for special church events and conferences',
                'head_of_department' => 'Jim Master',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            PreacherDepartment::updateOrCreate(
                ['slug' => $dept['slug']],
                $dept
            );
        }

        // Create some sample preacher department members if members exist
        $members = Member::limit(8)->get();
        $preacherDepartments = PreacherDepartment::all();

        if ($members->count() > 0 && $preacherDepartments->count() > 0) {
            $roles = [
                'sunday-service' => ['Lead Pastor', 'Assistant Pastor', 'Teaching Pastor', 'Guest Speaker'],
                'youth-ministry' => ['Youth Pastor', 'Youth Leader', 'Children Pastor', 'Young Adult Pastor'],
                'bible-study' => ['Bible Teacher', 'Small Group Leader', 'Study Coordinator', 'Discipleship Leader'],
                'special-events' => ['Conference Speaker', 'Evangelist', 'Workshop Leader', 'Event Preacher'],
            ];

            foreach ($preacherDepartments as $department) {
                // Add 2-3 random members to each department
                $memberCount = rand(2, 3);
                $selectedMembers = $members->random($memberCount);

                foreach ($selectedMembers as $index => $member) {
                    // Skip if member is already in this department
                    if (PreacherDepartmentMember::where('preacher_department_id', $department->id)
                                               ->where('member_id', $member->id)
                                               ->exists()) {
                        continue;
                    }

                    $deptRoles = $roles[$department->slug] ?? ['Preacher'];

                    PreacherDepartmentMember::create([
                        'preacher_department_id' => $department->id,
                        'member_id' => $member->id,
                        'role' => $deptRoles[array_rand($deptRoles)],
                        'joined_date' => now()->subMonths(rand(1, 48)),
                        'is_active' => true,
                        'is_head' => $index === 0, // First member is head
                    ]);
                }
            }
        }
    }
}
