<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Course;
use App\Models\Member;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\Validator;

echo "Testing course registration validation...\n\n";

// Test data that should be valid
$testData = [
    'first_name' => 'Test',
    'last_name' => 'Registration',
    'email' => 'test.registration@example.com',
    'phone' => '+1234567890',
    'membership_status' => 'visitor',
    'emergency_contact_name' => 'Emergency Contact',
    'emergency_contact_relationship' => 'Friend',
    'terms_agreement' => '1',
];

// Test the validation rules from CourseController
$rules = [
    'first_name' => 'required|string|max:255',
    'last_name' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'phone' => 'required|string|max:20',
    'membership_status' => 'required|in:visitor,regular_attendee,member',
    'emergency_contact_name' => 'required|string|max:255',
    'emergency_contact_relationship' => 'nullable|string|max:255',
    'terms_agreement' => 'required|accepted',
];

echo "Testing validation rules...\n";
$validator = Validator::make($testData, $rules);

if ($validator->fails()) {
    echo "❌ Validation failed:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "  - {$error}\n";
    }
} else {
    echo "✅ Validation passed!\n";
}

echo "\nTesting course availability...\n";
$course = Course::where('is_registration_open', true)->first();

if ($course) {
    echo "✅ Course found: {$course->title}\n";
    echo "   Registration open: " . ($course->is_registration_open ? 'Yes' : 'No') . "\n";
} else {
    echo "❌ No courses with open registration found\n";
}

echo "\nTesting member creation...\n";

// Clean up any previous test member
Member::where('email', 'test.registration@example.com')->delete();

try {
    $membershipNumber = 'TEST' . time();

    $member = Member::create([
        'membership_number' => $membershipNumber,
        'first_name' => $testData['first_name'],
        'last_name' => $testData['last_name'],
        'email' => strtolower(trim($testData['email'])),
        'phone' => $testData['phone'],
        'membership_status' => $testData['membership_status'],
        'emergency_contact_name' => $testData['emergency_contact_name'],
        'emergency_contact_relationship' => $testData['emergency_contact_relationship'],
        'first_visit_date' => now(),
        'is_active' => true,
    ]);

    echo "✅ Member created successfully!\n";
    echo "   ID: {$member->id}\n";
    echo "   Email: {$member->email}\n";
    echo "   Membership Number: {$member->membership_number}\n";

    // Clean up
    $member->delete();
    echo "✅ Test member cleaned up\n";

} catch (\Exception $e) {
    echo "❌ Member creation failed: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
