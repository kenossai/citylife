<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Member;
use App\Models\PastoralReminder;

// Get the first member
$member = Member::first();

if (!$member) {
    echo "No members found. Please seed the database first.\n";
    exit(1);
}

echo "Using member: {$member->first_name} {$member->last_name} ({$member->email})\n";

// Create a test birthday reminder for today
$reminder = PastoralReminder::create([
    'member_id' => $member->id,
    'reminder_type' => 'birthday',
    'reminder_date' => today(),
    'days_before_reminder' => 0, // Send today
    'is_annual' => true,
    'is_active' => true,
    'send_to_member' => true,
    'member_notification_type' => 'email',
    'notification_recipients' => ['test@example.com'],
    'member_message_template' => [
        'message' => '🎉 Happy Birthday, {first_name}! Wishing you a wonderful day filled with God\'s blessings. From all of us at City Life Christian Centre! 🎂'
    ]
]);

echo "Created test birthday reminder with ID: {$reminder->id}\n";
echo "Reminder date: {$reminder->reminder_date->format('Y-m-d')}\n";
echo "Notification due: " . ($reminder->reminder_date->copy()->subDays($reminder->days_before_reminder)->format('Y-m-d')) . "\n";
