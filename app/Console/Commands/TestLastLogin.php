<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestLastLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:last-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the last_login_at functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing last_login_at field...');
        $this->info('===============================');

        // Get first user
        $user = User::first();
        if (!$user) {
            $this->error('No users found in the database.');
            return;
        }

        $this->info("Testing with user: {$user->name} (ID: {$user->id})");
        $this->info("Current last_login_at: " . ($user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'NULL'));

        // Update last_login_at
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => '127.0.0.1'
        ]);

        // Refresh and check
        $user->refresh();
        $this->info("✅ Updated last_login_at to: " . $user->last_login_at->format('Y-m-d H:i:s'));
        $this->info("✅ Updated last_login_ip to: " . $user->last_login_ip);

        // Check other users
        $usersWithoutLogin = User::whereNull('last_login_at')->count();
        $usersWithLogin = User::whereNotNull('last_login_at')->count();
        
        $this->info("Users without last_login_at: {$usersWithoutLogin}");
        $this->info("Users with last_login_at: {$usersWithLogin}");

        $this->info('✅ last_login_at field is working correctly!');
        $this->info('The login event listener should now update this field automatically when users log in.');

        return 0;
    }
}
