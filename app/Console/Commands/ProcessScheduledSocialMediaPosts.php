<?php

namespace App\Console\Commands;

use App\Services\SocialMediaService;
use Illuminate\Console\Command;

class ProcessScheduledSocialMediaPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'social-media:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled social media posts that are ready to be published';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing scheduled social media posts...');

        $service = new SocialMediaService();
        $results = $service->processScheduledPosts();

        if (empty($results)) {
            $this->info('No scheduled posts ready for publishing.');
            return 0;
        }

        $this->info('Processed ' . count($results) . ' scheduled posts:');

        foreach ($results as $result) {
            $status = $result['result']['success'] ? 'SUCCESS' : 'FAILED';
            $platform = strtoupper($result['platform']);

            if ($result['result']['success']) {
                $this->line("  ✅ {$platform}: Post ID {$result['post_id']} - {$status}");
            } else {
                $error = $result['result']['error'] ?? 'Unknown error';
                $this->line("  ❌ {$platform}: Post ID {$result['post_id']} - {$status} ({$error})");
            }
        }

        $successCount = count(array_filter($results, fn($r) => $r['result']['success']));
        $failedCount = count($results) - $successCount;

        $this->newLine();
        $this->info("Summary: {$successCount} successful, {$failedCount} failed");

        return 0;
    }
}
