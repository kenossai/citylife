<?php

namespace App\Services;

use App\Models\Rota;
use App\Models\DepRole;
use Carbon\Carbon;

class RotaGeneratorService
{
    public function generateRotaSchedule(Rota $rota): array
    {
        $schedule = [];
        $members = $rota->getDepartmentMembers();
        $roles = $rota->getAvailableRoles();

        // Get all Sundays between start and end date
        $sundays = $this->getSundaysBetweenDates($rota->start_date, $rota->end_date);

        // Group members by their roles
        $membersByRole = [];
        foreach ($members as $member) {
            $role = $member->role;
            if (!isset($membersByRole[$role])) {
                $membersByRole[$role] = [];
            }
            $membersByRole[$role][] = $member->member->first_name . ' ' . $member->member->last_name;
        }

        // For each Sunday, assign members to roles
        foreach ($sundays as $index => $sunday) {
            $schedule[$sunday] = [];

            foreach ($roles as $role) {
                $roleName = $role->name;

                if (isset($membersByRole[$roleName]) && !empty($membersByRole[$roleName])) {
                    // Get available members for this role
                    $availableMembers = $membersByRole[$roleName];

                    // Use round-robin assignment to ensure fair rotation
                    $memberIndex = $index % count($availableMembers);
                    $assignedMember = $availableMembers[$memberIndex];

                    $schedule[$sunday][$roleName] = $assignedMember;
                } else {
                    // No members available for this role
                    $schedule[$sunday][$roleName] = '';
                }
            }
        }

        return $schedule;
    }

    private function getSundaysBetweenDates($startDate, $endDate): array
    {
        $sundays = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Move to the first Sunday
        while ($current->dayOfWeek !== Carbon::SUNDAY) {
            $current->addDay();
        }

        // Collect all Sundays
        while ($current->lte($end)) {
            $sundays[] = $current->toDateString();
            $current->addWeek(); // Move to next Sunday
        }

        return $sundays;
    }

    public function generateWithRandomization(Rota $rota): array
    {
        $schedule = [];

        // Get all Sundays between start and end date
        $sundays = $this->getSundaysBetweenDates($rota->start_date, $rota->end_date);

        // Ensure departments is an array - fallback to single department_type if departments is null
        $departments = $rota->departments ?? [$rota->department_type ?? 'worship'];

        // Filter out null/empty departments
        $departments = array_filter($departments);

        if (empty($departments)) {
            return []; // Return empty schedule if no valid departments
        }

        // Collect all members and roles from all departments
        $allMembersByRole = [];
        $allRoles = [];

        foreach ($departments as $department) {
            // Skip invalid departments
            if (empty($department) || !is_string($department)) {
                continue;
            }

            $members = $this->getDepartmentMembers($department);
            $roles = $this->getAvailableRoles($department);

            // Skip if no members or roles for this department
            if ($members->isEmpty() || $roles->isEmpty()) {
                continue;
            }

            // Collect all roles
            foreach ($roles as $role) {
                $allRoles[$role->name] = $role->name;
            }

            // Group members by their roles
            foreach ($members as $member) {
                $role = $member->role;
                if (!isset($allMembersByRole[$role])) {
                    $allMembersByRole[$role] = [];
                }
                
                // Store member name as first name only (matching your Excel format)
                $memberName = $member->member->first_name;
                if (!in_array($memberName, $allMembersByRole[$role])) {
                    $allMembersByRole[$role][] = $memberName;
                }
            }
        }

        // Shuffle members in each role for randomization but maintain consistency
        $memberRotationTracker = [];
        foreach ($allMembersByRole as $role => $roleMembers) {
            shuffle($allMembersByRole[$role]);
            $memberRotationTracker[$role] = 0; // Track rotation index
        }

        // Generate comprehensive ministry schedule structure
        $schedule = $this->generateMinistryRoles($allMembersByRole, $sundays, $memberRotationTracker);

        return $schedule;
    }

    private function generateMinistryRoles(array $allMembersByRole, array $sundays, array &$memberRotationTracker): array
    {
        $schedule = [];

        // Define ministry structure based on your Excel format
        $ministryStructure = [
            // Preaching & Leadership
            'Preaching' => [],
            'Leading' => [],
            
            // Worship Team
            'Worship Leader' => [],
            'Lead/Second Guitar' => [],
            'Bass Guitar' => [],
            'Acoustic Guitar' => [],
            'Piano 1' => [],
            'Piano 2' => [],
            'Drums' => [],
            'Singers Team' => [],
            
            // Technical/Media Team  
            'TL For The Day' => [],
            'Media(Kelham)' => [],
            'PA(Kelham)' => [],
            'Visual(Kelham)' => [],
            'Training/Shadow' => [],
        ];

        // Map actual roles to ministry structure roles
        $roleMapping = [
            'Lead Pastor' => 'Preaching',
            'Assistant Pastor' => 'Preaching', 
            'Youth Pastor' => 'Preaching',
            'Bible Teacher' => 'Preaching',
            'Evangelist' => 'Preaching',
            
            'Worship Leader' => 'Worship Leader',
            'Lead Vocalist' => 'Leading',
            'Background Vocalist' => 'Singers Team',
            
            'Guitarist' => 'Lead/Second Guitar',
            'Lead Guitarist' => 'Lead/Second Guitar',
            'Bassist' => 'Bass Guitar',
            'Drummer' => 'Drums',
            'Keyboardist' => 'Piano 1',
            'Pianist' => 'Piano 2',
            
            'Sound Engineer' => 'PA(Kelham)',
            'Camera Operator' => 'Visual(Kelham)',
            'Graphics Designer' => 'Media(Kelham)',
            'Lighting Technician' => 'Visual(Kelham)',
            'Technical Director' => 'TL For The Day',
            'IT Support' => 'Training/Shadow',
        ];

        // Generate assignments for each Sunday
        foreach ($sundays as $index => $sunday) {
            foreach ($ministryStructure as $ministryRole => $assignments) {
                $schedule[$ministryRole][$sunday] = $this->assignMemberToRole(
                    $ministryRole,
                    $allMembersByRole,
                    $roleMapping,
                    $memberRotationTracker,
                    $index
                );
            }
        }

        return $schedule;
    }

    private function assignMemberToRole(string $ministryRole, array $allMembersByRole, array $roleMapping, array &$memberRotationTracker, int $weekIndex): string
    {
        // Find available members for this ministry role
        $availableMembers = [];
        
        foreach ($roleMapping as $actualRole => $mappedMinistryRole) {
            if ($mappedMinistryRole === $ministryRole && isset($allMembersByRole[$actualRole])) {
                $availableMembers = array_merge($availableMembers, $allMembersByRole[$actualRole]);
            }
        }

        // Remove duplicates
        $availableMembers = array_unique($availableMembers);

        if (empty($availableMembers)) {
            return ''; // No members available
        }

        // For certain roles, assign multiple people or specific patterns
        if ($ministryRole === 'Singers Team') {
            // Assign 2-3 singers
            $singers = [];
            $maxSingers = min(3, count($availableMembers));
            for ($i = 0; $i < $maxSingers; $i++) {
                $memberIndex = ($weekIndex + $i) % count($availableMembers);
                $singers[] = $availableMembers[$memberIndex];
            }
            return implode(', ', $singers);
        }

        // Regular single assignment with rotation
        if (!isset($memberRotationTracker[$ministryRole])) {
            $memberRotationTracker[$ministryRole] = 0;
        }

        $memberIndex = $memberRotationTracker[$ministryRole] % count($availableMembers);
        $assignedMember = $availableMembers[$memberIndex];
        
        // Increment rotation tracker
        $memberRotationTracker[$ministryRole]++;

        return $assignedMember;
    }

    private function getDepartmentMembers(string $department)
    {
        switch ($department) {
            case 'worship':
                return \App\Models\WorshipDepartmentMember::with(['member'])
                    ->where('is_active', true)
                    ->get();
            case 'technical':
                return \App\Models\TechnicalDepartmentMember::with(['member'])
                    ->where('is_active', true)
                    ->get();
            case 'preacher':
                return \App\Models\PreacherDepartmentMember::with(['member'])
                    ->where('is_active', true)
                    ->get();
            default:
                return collect();
        }
    }

    private function getAvailableRoles(string $department)
    {
        return DepRole::active()
            ->forDepartment($department)
            ->get();
    }
}
