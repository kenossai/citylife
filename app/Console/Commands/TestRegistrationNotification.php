<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\RegistrationInterest;
use App\Notifications\NewRegistrationInterest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class TestRegistrationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:registration-notification {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the admin notification when a new registration interest is submitted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info('Testing admin notification for registration interest...');
        $this->newLine();

        // Create a test registration interest
        $interest = RegistrationInterest::create([
            'email' => $email,
            'status' => 'pending',
        ]);

        $this->line("✓ Created registration interest for: {$email}");
        $this->newLine();

        // Get all active admin users
        $admins = User::where('is_active', true)->get();

        if ($admins->isEmpty()) {
            $this->error('No active admin users found!');
            return 1;
        }

        $this->info("Found {$admins->count()} active admin user(s):");
        foreach ($admins as $admin) {
            $this->line("  - {$admin->name} ({$admin->email})");
        }
        $this->newLine();

        // Send notifications
        Notification::send($admins, new NewRegistrationInterest($interest));

        $this->info('✓ Notifications sent successfully!');
        $this->newLine();

        $this->comment('Check the admin email inbox(es) for the notification.');
        $this->comment('Also check the database notifications table for in-app notifications.');
        $this->newLine();

        // Clean up test data
        if ($this->confirm('Do you want to delete the test registration interest?', true)) {
            $interest->delete();
            $this->info('✓ Test data cleaned up.');
        }

        return 0;
    }
}
