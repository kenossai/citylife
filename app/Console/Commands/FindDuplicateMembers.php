<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\CourseEnrollment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FindDuplicateMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:find-duplicates {--merge : Merge duplicate members}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find (and optionally merge) duplicate members based on email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding duplicate members...');

        // Find duplicates by email (case insensitive)
        $duplicates = DB::table('members')
            ->select(DB::raw('LOWER(email) as email_lower'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('LOWER(email)'))
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicate members found!');
            return;
        }

        $this->warn("Found {$duplicates->count()} email addresses with duplicates:");

        foreach ($duplicates as $duplicate) {
            $members = Member::whereRaw('LOWER(email) = ?', [$duplicate->email_lower])
                ->orderBy('created_at')
                ->get();

            $this->newLine();
            $this->info("Email: {$duplicate->email_lower} ({$duplicate->count} duplicates)");

            $tableData = $members->map(function ($member) {
                return [
                    $member->id,
                    $member->first_name . ' ' . $member->last_name,
                    $member->email,
                    $member->phone ?? 'N/A',
                    $member->created_at->format('Y-m-d H:i'),
                    $member->is_active ? 'Yes' : 'No'
                ];
            })->toArray();

            $this->table(
                ['ID', 'Name', 'Email', 'Phone', 'Created', 'Active'],
                $tableData
            );

            if ($this->option('merge')) {
                $this->mergeDuplicateMembers($members);
            }
        }

        if (!$this->option('merge')) {
            $this->newLine();
            $this->info('To merge duplicates, run: php artisan members:find-duplicates --merge');
        }
    }

    private function mergeDuplicateMembers($members)
    {
        if ($members->count() < 2) {
            return;
        }

        // Keep the oldest member (first created)
        $primaryMember = $members->first();
        $duplicateMembers = $members->skip(1);

        $this->info("Merging into member ID {$primaryMember->id} (oldest)...");

        foreach ($duplicateMembers as $duplicate) {
            // Move all enrollments to the primary member
            $enrollmentsMoved = CourseEnrollment::where('user_id', $duplicate->id)
                ->whereNotExists(function ($query) use ($primaryMember) {
                    $query->select(DB::raw(1))
                        ->from('course_enrollments as ce2')
                        ->whereColumn('ce2.course_id', 'course_enrollments.course_id')
                        ->where('ce2.user_id', $primaryMember->id);
                })
                ->update(['user_id' => $primaryMember->id]);

            // Delete duplicate enrollments (where primary member already enrolled)
            $duplicateEnrollmentsDeleted = CourseEnrollment::where('user_id', $duplicate->id)->delete();

            // Merge any missing information to primary member
            $updateData = [];
            if (empty($primaryMember->phone) && !empty($duplicate->phone)) {
                $updateData['phone'] = $duplicate->phone;
            }
            if (empty($primaryMember->emergency_contact_name) && !empty($duplicate->emergency_contact_name)) {
                $updateData['emergency_contact_name'] = $duplicate->emergency_contact_name;
            }
            if (empty($primaryMember->emergency_contact_relationship) && !empty($duplicate->emergency_contact_relationship)) {
                $updateData['emergency_contact_relationship'] = $duplicate->emergency_contact_relationship;
            }

            if (!empty($updateData)) {
                $primaryMember->update($updateData);
            }

            // Delete the duplicate member
            $duplicate->delete();

            $this->info("  → Deleted duplicate member ID {$duplicate->id}");
            if ($enrollmentsMoved > 0) {
                $this->info("  → Moved {$enrollmentsMoved} enrollments");
            }
            if ($duplicateEnrollmentsDeleted > 0) {
                $this->info("  → Removed {$duplicateEnrollmentsDeleted} duplicate enrollments");
            }
        }

        $this->info("✅ Merged {$duplicateMembers->count()} duplicates into member ID {$primaryMember->id}");
    }
}
