<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContactSubmission;

class CleanSpamSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:clean-spam {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete spam contact submissions based on suspicious patterns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning for spam submissions...');

        $suspiciousPatterns = [
            'https?:\/\/proff?seo\.ru',
            'https?:\/\/.*\.ru\/prodvizhenie',
            'SEO.*promotion',
            'купить|продвижение|рейтинг',
            'bit\.ly|tinyurl|goo\.gl',
        ];

        $spamSubmissions = ContactSubmission::where(function ($query) use ($suspiciousPatterns) {
            foreach ($suspiciousPatterns as $pattern) {
                $query->orWhere('message', 'REGEXP', $pattern)
                      ->orWhere('subject', 'REGEXP', $pattern);
            }
        })->get();

        if ($spamSubmissions->isEmpty()) {
            $this->info('No spam submissions found.');
            return 0;
        }

        $this->info("Found {$spamSubmissions->count()} spam submissions:");

        foreach ($spamSubmissions as $submission) {
            $this->line("ID: {$submission->id} | Email: {$submission->email} | Subject: {$submission->subject}");
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry run mode - no submissions were deleted.');
            return 0;
        }

        if ($this->confirm('Do you want to delete these submissions?')) {
            $count = $spamSubmissions->count();
            ContactSubmission::whereIn('id', $spamSubmissions->pluck('id'))->delete();
            $this->info("Deleted {$count} spam submissions successfully.");
        } else {
            $this->info('Deletion cancelled.');
        }

        return 0;
    }
}
