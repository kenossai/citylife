<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧹 Cleaning up duplicate Pastoral Reminders...\n";
echo "==============================================\n";

// Find duplicate birthday reminders
$duplicates = \App\Models\PastoralReminder::select('member_id', 'reminder_type', 'reminder_date')
    ->selectRaw('COUNT(*) as count, GROUP_CONCAT(id) as ids')
    ->where('reminder_type', 'birthday')
    ->where('is_active', true)
    ->groupBy('member_id', 'reminder_type', 'reminder_date')
    ->having('count', '>', 1)
    ->get();

if ($duplicates->isEmpty()) {
    echo "✅ No duplicates found.\n";
} else {
    echo "Found " . $duplicates->count() . " sets of duplicates:\n\n";

    foreach ($duplicates as $duplicate) {
        $member = \App\Models\Member::find($duplicate->member_id);
        echo "👤 {$member->first_name} {$member->last_name} - {$duplicate->reminder_date} ({$duplicate->count} duplicates)\n";

        $ids = explode(',', $duplicate->ids);
        $keepId = array_shift($ids); // Keep the first one (oldest)

        echo "   Keeping ID: {$keepId}\n";
        echo "   Deleting IDs: " . implode(', ', $ids) . "\n";

        // Delete the duplicate reminders and their notifications
        foreach ($ids as $deleteId) {
            $reminderToDelete = \App\Models\PastoralReminder::find($deleteId);
            if ($reminderToDelete) {
                // First delete associated notifications
                \App\Models\PastoralNotification::where('pastoral_reminder_id', $deleteId)->delete();

                // Then delete the reminder
                $reminderToDelete->delete();
            }
        }

        echo "   ✅ Cleaned up!\n\n";
    }

    echo "🎉 All duplicates have been cleaned up!\n";
}

echo "\n📊 Current state:\n";
$totalReminders = \App\Models\PastoralReminder::where('is_active', true)->count();
$birthdayReminders = \App\Models\PastoralReminder::where('reminder_type', 'birthday')->where('is_active', true)->count();
echo "Total active reminders: {$totalReminders}\n";
echo "Birthday reminders: {$birthdayReminders}\n";
