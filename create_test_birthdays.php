<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Member;
use Carbon\Carbon;

echo "🎂 CREATING TEST BIRTHDAY MEMBERS\n";
echo "=================================\n\n";

$testMembers = [
    [
        'name' => 'Sarah Johnson',
        'email' => 'sarah.johnson@test.com',
        'phone' => '0114 555 0001',
        'days_until_birthday' => 0, // Today
        'age_turning' => 32
    ],
    [
        'name' => 'Michael Davis',
        'email' => 'michael.davis@test.com',
        'phone' => '0114 555 0002',
        'days_until_birthday' => 1, // Tomorrow
        'age_turning' => 45
    ],
    [
        'name' => 'Emma Wilson',
        'email' => 'emma.wilson@test.com',
        'phone' => '0114 555 0003',
        'days_until_birthday' => 3, // In 3 days
        'age_turning' => 28
    ],
    [
        'name' => 'James Brown',
        'email' => 'james.brown@test.com',
        'phone' => '0114 555 0004',
        'days_until_birthday' => 7, // In a week
        'age_turning' => 56
    ],
    [
        'name' => 'Lisa Garcia',
        'email' => 'lisa.garcia@test.com',
        'phone' => '0114 555 0005',
        'days_until_birthday' => 14, // In 2 weeks
        'age_turning' => 39
    ]
];

$created = 0;

foreach ($testMembers as $testMember) {
    // Calculate the birth date based on days until birthday and age turning
    $birthdayThisYear = Carbon::today()->addDays($testMember['days_until_birthday']);
    $dateOfBirth = $birthdayThisYear->copy()->subYears($testMember['age_turning']);

    $names = explode(' ', $testMember['name']);
    $firstName = $names[0];
    $lastName = $names[1] ?? '';

    // Check if member already exists
    $existingMember = Member::where('email', $testMember['email'])->first();

    if (!$existingMember) {
        $member = Member::create([
            'membership_number' => 'TEST' . date('Y') . str_pad(Member::count() + 1, 4, '0', STR_PAD_LEFT),
            'title' => in_array($firstName, ['Sarah', 'Emma', 'Lisa']) ? 'Ms' : 'Mr',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $testMember['email'],
            'phone' => $testMember['phone'],
            'date_of_birth' => $dateOfBirth,
            'gender' => in_array($firstName, ['Sarah', 'Emma', 'Lisa']) ? 'female' : 'male',
            'marital_status' => 'Single',
            'address' => rand(1, 999) . ' Test Street',
            'city' => 'Sheffield',
            'postal_code' => 'S' . rand(1, 99) . ' ' . rand(1, 9) . strtoupper(chr(rand(65, 90))) . strtoupper(chr(rand(65, 90))),
            'country' => 'United Kingdom',
            'membership_status' => ['member', 'regular_attendee'][rand(0, 1)],
            'first_visit_date' => Carbon::today()->subDays(rand(30, 365)),
            'membership_date' => rand(0, 1) ? Carbon::today()->subDays(rand(30, 200)) : null,
            'baptism_status' => ['Baptized', 'Not Baptized'][rand(0, 1)],
            'is_active' => true,
        ]);

        $created++;

        $urgency = match($testMember['days_until_birthday']) {
            0 => '🚨 TODAY',
            1 => '⚠️  TOMORROW',
            default => "📅 {$testMember['days_until_birthday']} days"
        };

        echo "✅ Created: {$testMember['name']} - {$urgency} (turning {$testMember['age_turning']})\n";
        echo "   Birthday: {$dateOfBirth->format('M j, Y')} → {$birthdayThisYear->format('M j, Y')}\n";
        echo "   Contact: {$testMember['phone']} | {$testMember['email']}\n\n";
    } else {
        echo "⚠️  Skipped: {$testMember['name']} (already exists)\n\n";
    }
}

echo "🎉 SUMMARY:\n";
echo "  Created {$created} new test members with upcoming birthdays\n";
echo "  Total active members: " . Member::active()->count() . "\n";
echo "  Members with birthdays: " . Member::active()->whereNotNull('date_of_birth')->count() . "\n\n";

// Show current upcoming birthdays
$upcomingBirthdays = Member::active()
    ->whereNotNull('date_of_birth')
    ->get()
    ->filter(function ($member) {
        $today = Carbon::today();
        $thisYearBirthday = $member->date_of_birth->copy()->year($today->year);

        if ($thisYearBirthday->isPast()) {
            $thisYearBirthday->addYear();
        }

        return $today->diffInDays($thisYearBirthday) <= 30;
    })
    ->sortBy(function ($member) {
        $today = Carbon::today();
        $thisYearBirthday = $member->date_of_birth->copy()->year($today->year);

        if ($thisYearBirthday->isPast()) {
            $thisYearBirthday->addYear();
        }

        return $today->diffInDays($thisYearBirthday);
    });

echo "📊 CURRENT UPCOMING BIRTHDAYS ({$upcomingBirthdays->count()}):\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

foreach ($upcomingBirthdays->take(10) as $member) {
    $today = Carbon::today();
    $thisYearBirthday = $member->date_of_birth->copy()->year($today->year);

    if ($thisYearBirthday->isPast()) {
        $thisYearBirthday->addYear();
    }

    $daysUntil = $today->diffInDays($thisYearBirthday);
    $age = $thisYearBirthday->year - $member->date_of_birth->year;

    $urgency = match(true) {
        $daysUntil == 0 => '🚨',
        $daysUntil == 1 => '⚠️ ',
        $daysUntil <= 7 => '📅',
        default => '📆'
    };

    echo "{$urgency} {$member->first_name} {$member->last_name} - {$daysUntil} days (turning {$age})\n";
}

echo "\n🎊 The Birthday Dashboard Widget is now ready with test data!\n";
echo "Go to the Filament admin dashboard to see the widget in action.\n";
