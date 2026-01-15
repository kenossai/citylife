<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Course Registration Flow\n";
echo "=================================\n\n";

// Simulate course registration flow
$course = \App\Models\Course::first();
if (!$course) {
    echo "No course found to test with\n";
    exit;
}

echo "Testing with course: {$course->title}\n\n";

// Delete test member if exists
$testEmail = 'coursetest' . time() . '@example.com';
\App\Models\Member::where('email', $testEmail)->delete();

// Create member like in course registration
$membershipNumber = 'CL' . date('Y') . str_pad(\App\Models\Member::count() + 1, 4, '0', STR_PAD_LEFT);

$member = \App\Models\Member::create([
    'membership_number' => $membershipNumber,
    'first_name' => 'Course',
    'last_name' => 'Test',
    'email' => strtolower(trim($testEmail)),
    'phone' => '1234567890',
    'membership_status' => 'visitor',
    'emergency_contact_name' => 'Emergency Contact',
    'emergency_contact_relationship' => 'Friend',
    'first_visit_date' => now(),
    'is_active' => true,
    'email_verified_at' => null,
    'approved_at' => null,
]);

echo "✓ Member created: {$member->email}\n";

// Send email verification
try {
    $verificationToken = $member->generateEmailVerificationToken();
    echo "✓ Verification token generated\n";
    
    $member->notify(new \App\Notifications\MemberEmailVerification($verificationToken));
    echo "✓ Email verification notification sent!\n";
} catch (\Exception $e) {
    echo "✗ Failed to send verification: {$e->getMessage()}\n";
    echo "Stack trace: {$e->getTraceAsString()}\n";
}

// Create enrollment
$enrollment = \App\Models\CourseEnrollment::create([
    'course_id' => $course->id,
    'user_id' => $member->id,
    'enrollment_date' => now(),
    'status' => 'active',
]);

echo "✓ Enrollment created\n";

// Send course confirmation
try {
    $member->notify(new \App\Notifications\CourseRegistrationConfirmation($course, $enrollment));
    echo "✓ Course confirmation notification sent!\n";
} catch (\Exception $e) {
    echo "✗ Failed to send course confirmation: {$e->getMessage()}\n";
    echo "Stack trace: {$e->getTraceAsString()}\n";
}

echo "\n=================================\n";
echo "Check Mailtrap - you should see 2 emails:\n";
echo "1. Email Verification\n";
echo "2. Course Registration Confirmation\n";
echo "=================================\n";
