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
                $allMembersByRole[$role][] = $member->member->first_name . ' ' . $member->member->last_name;
            }
        }

        // Shuffle members in each role for randomization
        foreach ($allMembersByRole as $role => $roleMembers) {
            shuffle($allMembersByRole[$role]);
        }

        // Generate schedule: Structure should be [role][date] = member_name
        // This matches your Excel format where roles are rows and dates are columns
        foreach ($allRoles as $roleName) {
            $schedule[$roleName] = [];

            foreach ($sundays as $index => $sunday) {
                if (isset($allMembersByRole[$roleName]) && !empty($allMembersByRole[$roleName])) {
                    // Get available members for this role
                    $availableMembers = $allMembersByRole[$roleName];

                    // Use round-robin with the shuffled list
                    $memberIndex = $index % count($availableMembers);
                    $assignedMember = $availableMembers[$memberIndex];

                    $schedule[$roleName][$sunday] = $assignedMember;
                } else {
                    // No members available for this role
                    $schedule[$roleName][$sunday] = '';
                }
            }
        }

        return $schedule;
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
