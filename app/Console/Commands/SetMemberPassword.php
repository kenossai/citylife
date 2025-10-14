<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;

class SetMemberPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:set-password
                            {--email= : Member email address}
                            {--membership= : Member membership number}
                            {--password= : New password (default: password123)}
                            {--all : Set password for all members}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set password for member(s)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $membership = $this->option('membership');
        $password = $this->option('password') ?: 'password123';
        $all = $this->option('all');

        if ($all) {
            $count = Member::whereNull('password')->count();
            if ($count === 0) {
                $this->info('All members already have passwords.');
                return;
            }

            if ($this->confirm("Set password '$password' for $count members without passwords?")) {
                Member::whereNull('password')->update(['password' => Hash::make($password)]);
                $this->info("Updated $count members with password '$password'");
            }
            return;
        }

        if (!$email && !$membership) {
            $this->error('Please provide either --email, --membership, or --all option');
            return;
        }

        $member = null;
        if ($email) {
            $member = Member::where('email', $email)->first();
        } elseif ($membership) {
            $member = Member::where('membership_number', $membership)->first();
        }

        if (!$member) {
            $this->error('Member not found');
            return;
        }

        $member->update(['password' => Hash::make($password)]);
        $this->info("Password updated for {$member->first_name} {$member->last_name} ({$member->email})");
    }
}
