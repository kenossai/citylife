<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Member;
use App\Models\CourseEnrollment;
use App\Notifications\CourseRegistrationConfirmation;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TestNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications {email} {phone?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test course registration notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $phone = $this->argument('phone');

        $this->info('Testing notification system...');

        // Create a test member
        $member = Member::firstOrCreate(
            ['email' => $email],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'phone' => $phone,
                'membership_status' => 'visitor',
                'emergency_contact_name' => 'Emergency Contact',
                'is_active' => true
            ]
        );

        // Get a test course
        $course = Course::first();
        if (!$course) {
            $this->error('No courses found. Please create a course first.');
            return;
        }

        // Create a test enrollment
        $enrollment = CourseEnrollment::firstOrCreate([
            'course_id' => $course->id,
            'user_id' => $member->id,
        ], [
            'enrollment_date' => now(),
            'status' => 'active'
        ]);

        // Test email
        $this->info('Testing email notification...');
        try {
            $member->notify(new CourseRegistrationConfirmation($course, $enrollment));
            $this->info('✅ Email notification queued successfully for: ' . $email);
        } catch (\Exception $e) {
            $this->error('❌ Email notification failed: ' . $e->getMessage());
        }

        // Test SMS
        if ($phone) {
            $this->info('Testing SMS notification...');
            try {
                $smsService = app(SmsService::class);
                $notification = new CourseRegistrationConfirmation($course, $enrollment);
                $message = $notification->getSmsMessage($member);
                $formattedPhone = $smsService->formatPhone($phone);

                $result = $smsService->send($formattedPhone, $message);

                if ($result) {
                    $this->info('✅ SMS logged successfully for: ' . $formattedPhone);
                    $this->info('Check storage/logs/laravel.log for SMS entry');
                } else {
                    $this->error('❌ SMS failed');
                }
            } catch (\Exception $e) {
                $this->error('❌ SMS failed: ' . $e->getMessage());
            }
        }

        // Check queue jobs
        $this->info('Checking queue status...');
        $pendingJobs = DB::table('jobs')->count();
        $this->info('Pending queue jobs: ' . $pendingJobs);

        if ($pendingJobs > 0) {
            $this->warn('There are pending jobs in the queue. Run: php artisan queue:work');
        }

        $this->info('Test completed! Check Mailpit at http://localhost:8025');
    }
}
