<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\CourseEnrollment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonitorMemberRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:monitor {--recent=24 : Show registrations from last N hours}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor member registrations and detect potential issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('recent');
        
        $this->info("Member Registration Monitor");
        $this->info("=" . str_repeat("=", 50));
        
        // Check for recent registrations
        $this->info("\n📊 Recent Activity (last {$hours} hours):");
        
        $recentMembers = Member::where('created_at', '>=', now()->subHours($hours))
            ->orderBy('created_at', 'desc')
            ->get();
            
        if ($recentMembers->count() > 0) {
            $this->table(
                ['ID', 'Name', 'Email', 'Status', 'Created'],
                $recentMembers->map(function($member) {
                    return [
                        $member->id,
                        "{$member->first_name} {$member->last_name}",
                        $member->email,
                        $member->membership_status,
                        $member->created_at->format('Y-m-d H:i:s')
                    ];
                })
            );
        } else {
            $this->line("  No recent registrations found.");
        }
        
        // Check for potential duplicates
        $this->info("\n🔍 Duplicate Detection:");
        
        $duplicates = DB::select("
            SELECT 
                LOWER(TRIM(email)) as normalized_email,
                COUNT(*) as count,
                GROUP_CONCAT(id) as member_ids
            FROM members 
            WHERE email IS NOT NULL AND email != ''
            GROUP BY LOWER(TRIM(email))
            HAVING COUNT(*) > 1
        ");
        
        if (count($duplicates) > 0) {
            $this->error("  ⚠️  Found " . count($duplicates) . " duplicate email(s):");
            foreach ($duplicates as $duplicate) {
                $this->line("    • {$duplicate->normalized_email} ({$duplicate->count} records: IDs {$duplicate->member_ids})");
            }
        } else {
            $this->info("  ✅ No duplicate emails found!");
        }
        
        // Check course enrollments
        $this->info("\n📚 Course Enrollment Summary:");
        
        $recentEnrollments = CourseEnrollment::where('enrollment_date', '>=', now()->subHours($hours))
            ->with(['course', 'user'])
            ->orderBy('enrollment_date', 'desc')
            ->get();
            
        if ($recentEnrollments->count() > 0) {
            $this->table(
                ['Course', 'Member', 'Email', 'Enrolled'],
                $recentEnrollments->map(function($enrollment) {
                    return [
                        $enrollment->course->title ?? 'Unknown',
                        "{$enrollment->user->first_name} {$enrollment->user->last_name}",
                        $enrollment->user->email,
                        $enrollment->enrollment_date->format('Y-m-d H:i:s')
                    ];
                })
            );
        } else {
            $this->line("  No recent course enrollments found.");
        }
        
        // Database integrity check
        $this->info("\n🔧 Database Integrity:");
        
        // Check for members without emails
        $membersWithoutEmail = Member::whereNull('email')->orWhere('email', '')->count();
        if ($membersWithoutEmail > 0) {
            $this->warn("  ⚠️  {$membersWithoutEmail} members without email addresses");
        } else {
            $this->info("  ✅ All members have email addresses");
        }
        
        // Check for orphaned enrollments
        $orphanedEnrollments = CourseEnrollment::leftJoin('members', 'course_enrollments.user_id', '=', 'members.id')
            ->whereNull('members.id')
            ->count();
            
        if ($orphanedEnrollments > 0) {
            $this->error("  ⚠️  {$orphanedEnrollments} orphaned course enrollments found");
        } else {
            $this->info("  ✅ No orphaned course enrollments");
        }
        
        // Summary statistics
        $this->info("\n📈 Summary Statistics:");
        $totalMembers = Member::count();
        $activeMembers = Member::where('is_active', true)->count();
        $totalEnrollments = CourseEnrollment::where('status', 'active')->count();
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Members', $totalMembers],
                ['Active Members', $activeMembers],
                ['Active Enrollments', $totalEnrollments],
                ['Recent Registrations', $recentMembers->count()],
                ['Recent Enrollments', $recentEnrollments->count()],
            ]
        );
        
        $this->info("\n✅ Monitoring complete!");
        
        return 0;
    }
}
