<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Member;
use App\Models\PastoralReminder;

// Get a member with a membership date for anniversary testing
$member = Member::whereNotNull('membership_date')->first();

if (!$member) {
    echo "No members found with membership date. Using first member and setting a membership date.\n";
    $member = Member::first();
    $member->update(['membership_date' => today()->subYears(2)]);
}

echo "Using member: {$member->first_name} {$member->last_name} ({$member->email})\n";
echo "Membership date: {$member->membership_date->format('Y-m-d')}\n";

// Create a test membership anniversary reminder for today
$reminder = PastoralReminder::create([
    'member_id' => $member->id,
    'reminder_type' => 'membership_anniversary',
    'reminder_date' => $member->membership_date,
    'days_before_reminder' => 0, // Send today (simulating the anniversary is today)
    'is_annual' => true,
    'is_active' => true,
    'send_to_member' => true,
    'member_notification_type' => 'email',
    'year_created' => $member->membership_date->year,
    'notification_recipients' => ['test@example.com'],
    'member_message_template' => [
        'message' => '🏠 Happy {years_text}Membership Anniversary, {first_name}! Thank you for being such a valued part of our church family for {years} years! 🙏'
    ]
]);

// Update the reminder date to today to trigger it
$reminder->update(['reminder_date' => today()]);

echo "Created test membership anniversary reminder with ID: {$reminder->id}\n";
echo "Anniversary years: {$reminder->years_count}\n";
echo "Formatted message: {$reminder->formatted_member_message}\n";
