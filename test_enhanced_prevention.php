<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\DB;

echo "Testing enhanced duplicate prevention system...\n\n";

// Find a course for testing
$course = Course::first();
if (!$course) {
    echo "No courses found. Please create a course first.\n";
    exit(1);
}

echo "Using course: {$course->title}\n\n";

// Test different email variations that should be considered the same
$emailVariations = [
    'Test.Prevention@Example.COM',
    '  test.prevention@example.com  ',
    'TEST.PREVENTION@EXAMPLE.COM',
    'test.prevention@example.com'
];

$testData = [
    'first_name' => 'Test',
    'last_name' => 'Prevention',
    'phone' => '+1234567890',
    'membership_status' => 'visitor',
    'emergency_contact_name' => 'Emergency Contact',
    'emergency_contact_relationship' => 'Friend',
];

foreach ($emailVariations as $index => $email) {
    echo "Test " . ($index + 1) . ": Registering with email '{$email}'\n";

    try {
        DB::beginTransaction();

        // Normalize email for consistent matching
        $normalizedEmail = strtolower(trim($email));

        echo "  Normalized email: '{$normalizedEmail}'\n";

        // Check if member already exists by email (case insensitive with trim)
        $member = Member::whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])->first();

        if (!$member) {
            echo "  ✓ No existing member found. Creating new member...\n";

            $member = Member::create(array_merge($testData, [
                'email' => $email, // The mutator will normalize this
            ]));

            echo "  ✓ New member created with ID: {$member->id}\n";
            echo "  ✓ Stored email: '{$member->email}'\n";
        } else {
            echo "  ✓ Existing member found with ID: {$member->id}\n";
            echo "  ✓ Stored email: '{$member->email}'\n";
        }

        // Check for existing enrollment
        $existingEnrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $member->id)
            ->first();

        if ($existingEnrollment) {
            echo "  ✓ Member is already enrolled - preventing duplicate enrollment\n";
            DB::rollBack();
            echo "  → Registration blocked (as expected)\n";
        } else {
            echo "  ✓ No existing enrollment found. Creating enrollment...\n";

            $enrollment = CourseEnrollment::create([
                'course_id' => $course->id,
                'user_id' => $member->id,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            echo "  ✓ Enrollment created with ID: {$enrollment->id}\n";
            DB::commit();
            echo "  → Registration successful\n";
        }

    } catch (\Exception $e) {
        DB::rollBack();
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }

    echo "\n";
}

// Check final state
echo "Final verification:\n";
$members = Member::whereRaw('LOWER(TRIM(email)) = ?', ['test.prevention@example.com'])->get();
echo "Total members with normalized email 'test.prevention@example.com': " . $members->count() . "\n";

foreach ($members as $member) {
    echo "  - ID: {$member->id}, Email: '{$member->email}'\n";
}

$enrollments = CourseEnrollment::where('course_id', $course->id)
    ->whereIn('user_id', $members->pluck('id'))
    ->count();
echo "Total enrollments for these members in this course: {$enrollments}\n";

echo "\n✅ Enhanced duplicate prevention test completed!\n";
