<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Member;

class TestEmail extends Command
{
    protected $signature = 'mail:test {email?}';
    protected $description = 'Test email configuration by sending a test email';

    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';

        $this->info("Sending test email to: {$email}");

        try {
            Mail::raw('This is a test email from CityLife Church. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - CityLife Church');
            });

            $this->info('✓ Email sent successfully!');
            $this->info('Check your inbox (or Mailtrap if using sandbox)');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email');
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
