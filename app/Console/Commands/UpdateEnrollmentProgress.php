<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourseEnrollment;

class UpdateEnrollmentProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollment:update-progress {--enrollment-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update enrollment progress based on attendance records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enrollmentId = $this->option('enrollment-id');

        if ($enrollmentId) {
            // Update specific enrollment
            $enrollment = CourseEnrollment::find($enrollmentId);
            if (!$enrollment) {
                $this->error("Enrollment with ID {$enrollmentId} not found.");
                return 1;
            }

            $enrollment->updateProgressFromAttendance();
            $this->info("Updated progress for enrollment ID: {$enrollmentId}");
        } else {
            // Update all enrollments
            $this->info('Updating progress for all enrollments...');

            $enrollments = CourseEnrollment::with(['course', 'attendance'])->get();
            $bar = $this->output->createProgressBar($enrollments->count());
            $bar->start();

            foreach ($enrollments as $enrollment) {
                $enrollment->updateProgressFromAttendance();
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Updated progress for {$enrollments->count()} enrollments.");
        }

        return 0;
    }
}
