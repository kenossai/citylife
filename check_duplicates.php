<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use Illuminate\Support\Facades\DB;

echo "Checking for potential email duplicates in the database...\n\n";

// Get all members with emails
$members = Member::whereNotNull('email')
    ->where('email', '!=', '')
    ->orderBy('email')
    ->get(['id', 'first_name', 'last_name', 'email', 'created_at']);

echo "Total members with emails: " . $members->count() . "\n\n";

// Group by normalized email to find duplicates
$emailGroups = $members->groupBy(function($member) {
    return strtolower(trim($member->email));
});

$duplicatesFound = false;

foreach ($emailGroups as $normalizedEmail => $group) {
    if ($group->count() > 1) {
        $duplicatesFound = true;
        echo "DUPLICATE EMAIL FOUND: {$normalizedEmail}\n";
        echo "Number of records: " . $group->count() . "\n";

        foreach ($group as $member) {
            echo "  - ID: {$member->id}, Name: {$member->first_name} {$member->last_name}, Email: '{$member->email}', Created: {$member->created_at}\n";
        }
        echo "\n";
    }
}

if (!$duplicatesFound) {
    echo "✅ No email duplicates found!\n\n";
}

// Check for recent registrations (last 24 hours)
echo "Recent registrations (last 24 hours):\n";
$recentMembers = Member::where('created_at', '>=', now()->subDay())
    ->orderBy('created_at', 'desc')
    ->get(['id', 'first_name', 'last_name', 'email', 'created_at']);

if ($recentMembers->count() > 0) {
    foreach ($recentMembers as $member) {
        echo "  - ID: {$member->id}, Name: {$member->first_name} {$member->last_name}, Email: '{$member->email}', Created: {$member->created_at}\n";
    }
} else {
    echo "  No recent registrations found.\n";
}

echo "\n";

// Test the exact lookup logic used in CourseController
echo "Testing email lookup logic:\n";
$testEmails = [
    'Test.User@Example.com',
    'test.user@example.com',
    'TEST.USER@EXAMPLE.COM',
    '  test.user@example.com  '
];

foreach ($testEmails as $testEmail) {
    $normalized = strtolower(trim($testEmail));
    $found = Member::whereRaw('LOWER(email) = ?', [$normalized])->first();
    echo "Testing '{$testEmail}' -> normalized: '{$normalized}' -> " . ($found ? "found member {$found->id}" : "not found") . "\n";
}

echo "\nDatabase check completed.\n";
