<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\DB;

echo "Testing course registration flow...\n\n";

// Find or create a test course
$course = Course::first();
if (!$course) {
    echo "No courses found. Please create a course first.\n";
    exit(1);
}

echo "Using course: {$course->title}\n";

// Test data for registration
$testData = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test.user@example.com',
    'phone' => '+1234567890',
    'membership_status' => 'visitor',
    'emergency_contact_name' => 'Emergency Contact',
    'emergency_contact_relationship' => 'Friend',
];

echo "Test registration data:\n";
echo "Email: {$testData['email']}\n\n";

// Simulate the registration logic from CourseController
try {
    DB::beginTransaction();

    echo "Step 1: Check if member already exists...\n";
    $member = Member::whereRaw('LOWER(email) = ?', [strtolower($testData['email'])])->first();

    if (!$member) {
        echo "✓ No existing member found. Creating new member...\n";

        $member = Member::create([
            'membership_number' => 'TEST-' . time(),
            'first_name' => $testData['first_name'],
            'last_name' => $testData['last_name'],
            'email' => strtolower($testData['email']),
            'phone' => $testData['phone'],
            'membership_status' => $testData['membership_status'],
            'emergency_contact_name' => $testData['emergency_contact_name'],
            'emergency_contact_relationship' => $testData['emergency_contact_relationship'],
            'first_visit_date' => now(),
            'is_active' => true,
        ]);

        echo "✓ New member created with ID: {$member->id}\n";
    } else {
        echo "✓ Existing member found with ID: {$member->id}\n";
        echo "  Member email: {$member->email}\n";
        echo "  Search email: " . strtolower($testData['email']) . "\n";
    }

    echo "\nStep 2: Check for existing enrollment...\n";
    $existingEnrollment = CourseEnrollment::where('course_id', $course->id)
        ->where('user_id', $member->id)
        ->first();

    if ($existingEnrollment) {
        echo "✗ Member is already enrolled in this course\n";
        DB::rollBack();
    } else {
        echo "✓ No existing enrollment found. Creating enrollment...\n";

        $enrollment = CourseEnrollment::create([
            'course_id' => $course->id,
            'user_id' => $member->id,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);

        echo "✓ Enrollment created with ID: {$enrollment->id}\n";

        // Update course enrollment count
        $actualCount = CourseEnrollment::where('course_id', $course->id)
            ->where('status', 'active')
            ->count();
        $course->update(['current_enrollments' => $actualCount]);

        echo "✓ Course enrollment count updated to: {$actualCount}\n";

        DB::commit();
        echo "\n✅ Registration completed successfully!\n";
    }

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n✗ Error during registration: " . $e->getMessage() . "\n";
}

// Now test registering the same email again
echo "\n" . str_repeat("=", 50) . "\n";
echo "Testing duplicate registration with same email...\n\n";

try {
    DB::beginTransaction();

    echo "Step 1: Check if member already exists...\n";
    $member = Member::whereRaw('LOWER(email) = ?', [strtolower($testData['email'])])->first();

    if (!$member) {
        echo "✗ ERROR: Member should exist but wasn't found!\n";
    } else {
        echo "✓ Existing member found with ID: {$member->id}\n";
    }

    echo "\nStep 2: Check for existing enrollment...\n";
    $existingEnrollment = CourseEnrollment::where('course_id', $course->id)
        ->where('user_id', $member->id)
        ->first();

    if ($existingEnrollment) {
        echo "✓ Member is already enrolled - preventing duplicate enrollment\n";
        DB::rollBack();
    } else {
        echo "✗ ERROR: Enrollment should exist but wasn't found!\n";
        DB::rollBack();
    }

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n✗ Error during duplicate test: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
