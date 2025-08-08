<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

echo "Testing duplicate prevention logic...\n";

// Check if we have any members with emails
$memberWithEmail = Member::whereNotNull('email')->where('email', '!=', '')->first();

if (!$memberWithEmail) {
    echo "No members with email found. Creating a test member...\n";
    $memberWithEmail = Member::create([
        'membership_number' => 'TEST' . time(),
        'first_name' => 'Test',
        'last_name' => 'Member',
        'email' => 'test@example.com'
    ]);
}

echo "Using member: {$memberWithEmail->first_name} {$memberWithEmail->last_name} ({$memberWithEmail->email})\n";

// Test case-insensitive lookup
$normalizedEmail = strtolower(trim($memberWithEmail->email));
echo "Normalized email: {$normalizedEmail}\n";

// Test the lookup logic from CourseController
$existingMember = Member::whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])->first();

if ($existingMember) {
    echo "✓ Case-insensitive lookup found existing member: {$existingMember->id}\n";
} else {
    echo "✗ Case-insensitive lookup failed\n";
}

// Test with different case variations
$testEmails = [
    strtoupper($memberWithEmail->email),
    strtolower($memberWithEmail->email),
    '  ' . $memberWithEmail->email . '  ', // with spaces
    ucfirst(strtolower($memberWithEmail->email))
];

foreach ($testEmails as $testEmail) {
    $normalized = strtolower(trim($testEmail));
    $found = Member::whereRaw('LOWER(TRIM(email)) = ?', [$normalized])->first();
    $status = $found ? '✓' : '✗';
    echo "{$status} Testing '{$testEmail}' -> normalized: '{$normalized}' -> " . ($found ? "found member {$found->id}" : "not found") . "\n";
}

echo "\nDuplicate prevention test completed.\n";
