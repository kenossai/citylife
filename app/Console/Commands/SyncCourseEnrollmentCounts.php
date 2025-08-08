<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Console\Command;

class SyncCourseEnrollmentCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:sync-enrollment-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync course enrollment counts with actual enrollment records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing course enrollment counts...');

        $courses = Course::all();
        $totalFixed = 0;

        foreach ($courses as $course) {
            // Get actual active enrollment count
            $actualCount = CourseEnrollment::where('course_id', $course->id)
                ->where('status', 'active')
                ->count();

            $oldCount = $course->current_enrollments ?? 0;

            if ($oldCount != $actualCount) {
                $course->update(['current_enrollments' => $actualCount]);
                $this->info("Course '{$course->title}': {$oldCount} → {$actualCount}");
                $totalFixed++;
            }
        }

        if ($totalFixed > 0) {
            $this->info("✅ Fixed {$totalFixed} course enrollment counts");
        } else {
            $this->info("✅ All course enrollment counts are already correct");
        }

        // Show summary
        $this->newLine();
        $this->info('Summary:');
        $this->table(
            ['Course', 'Enrollments'],
            $courses->map(function ($course) {
                return [
                    $course->title,
                    $course->fresh()->current_enrollments ?? 0
                ];
            })->toArray()
        );
    }
}
