<?php

namespace App\Console\Commands;

use App\Models\PastoralReminder;
use App\Models\PastoralNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendPastoralReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pastoral:send-reminders {--dry-run : Run without actually sending notifications}';

    /**
     * The console command description.
     */
    protected $description = 'Send due pastoral care reminders to staff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No notifications will be sent');
            $this->newLine();
        }

        // Get reminders that are due today
        $dueReminders = PastoralReminder::active()
            ->whereRaw('DATE_ADD(reminder_date, INTERVAL -days_before_reminder DAY) = CURDATE()')
            ->with(['member'])
            ->get();

        if ($dueReminders->isEmpty()) {
            $this->info('No pastoral reminders are due today.');
            return 0;
        }

        $this->info("Found {$dueReminders->count()} reminder(s) due today:");
        $this->newLine();

        foreach ($dueReminders as $reminder) {
            $member = $reminder->member;
            $this->line("- {$reminder->reminder_type_label} for {$member->first_name} {$member->last_name} on {$reminder->reminder_date->format('M j')}");

            if (!$dryRun) {
                // Create email notification
                PastoralNotification::createForReminder($reminder, 'email');

                // Update last sent timestamp
                $reminder->update(['last_sent_at' => now()]);

                $this->info("  ✓ Notification sent");
            } else {
                $this->comment("  → Would send notification");
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->comment('DRY RUN completed. Run without --dry-run to actually send notifications.');
        } else {
            $this->info('All due pastoral reminders have been sent successfully!');
        }

        return 0;
    }
}
