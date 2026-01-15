<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Notifications\MemberEmailVerification;

class SendVerificationEmails extends Command
{
    protected $signature = 'members:send-verification {email?}';
    protected $description = 'Send verification email to unverified members or a specific member';

    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            // Send to specific member
            $member = Member::where('email', $email)->first();

            if (!$member) {
                $this->error("Member with email {$email} not found!");
                return Command::FAILURE;
            }

            if ($member->hasVerifiedEmail()) {
                $this->warn("Member {$email} is already verified!");
                return Command::SUCCESS;
            }

            $this->sendVerificationEmail($member);
            $this->info("✓ Verification email sent to {$email}");
        } else {
            // Send to all unverified members
            $members = Member::whereNull('email_verified_at')->get();

            if ($members->isEmpty()) {
                $this->info('No unverified members found!');
                return Command::SUCCESS;
            }

            $this->info("Found {$members->count()} unverified member(s)");

            foreach ($members as $member) {
                $this->sendVerificationEmail($member);
                $this->info("✓ Sent to {$member->email}");
            }

            $this->info("✓ All verification emails sent!");
        }

        return Command::SUCCESS;
    }

    private function sendVerificationEmail(Member $member)
    {
        $token = $member->generateEmailVerificationToken();
        $member->notify(new MemberEmailVerification($token));
    }
}
