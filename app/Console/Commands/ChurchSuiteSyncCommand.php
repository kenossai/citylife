<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Services\ChurchSuiteService;
use Illuminate\Console\Command;

class ChurchSuiteSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'churchsuite:sync
                            {--member=* : Specific member ID(s) to sync}
                            {--cdc-graduates : Sync all CDC graduates}
                            {--all : Sync all members}
                            {--test : Test connection only}
                            {--force : Force sync even if already synced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync members to ChurchSuite';

    protected ChurchSuiteService $service;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->service = app(ChurchSuiteService::class);

        // Test connection
        if ($this->option('test')) {
            return $this->testConnection();
        }

        // Check if configured
        if (!$this->service->isConfigured()) {
            $this->error('ChurchSuite is not configured!');
            $this->warn('Please add the following to your .env file:');
            $this->line('CHURCHSUITE_API_URL=https://api.churchsuite.com/v1');
            $this->line('CHURCHSUITE_ACCOUNT_NAME=your-account-name');
            $this->line('CHURCHSUITE_API_KEY=your-api-key');
            return 1;
        }

        // Sync specific members
        if ($this->option('member')) {
            return $this->syncSpecificMembers();
        }

        // Sync CDC graduates
        if ($this->option('cdc-graduates')) {
            return $this->syncCDCGraduates();
        }

        // Sync all members
        if ($this->option('all')) {
            return $this->syncAllMembers();
        }

        // Show help
        $this->info('ChurchSuite Sync Command');
        $this->line('');
        $this->line('Available options:');
        $this->line('  --test              Test ChurchSuite API connection');
        $this->line('  --member=ID         Sync specific member(s) by ID');
        $this->line('  --cdc-graduates     Sync all CDC course graduates');
        $this->line('  --all               Sync all members');
        $this->line('  --force             Force sync even if already synced');
        $this->line('');
        $this->line('Examples:');
        $this->line('  php artisan churchsuite:sync --test');
        $this->line('  php artisan churchsuite:sync --member=1 --member=2');
        $this->line('  php artisan churchsuite:sync --cdc-graduates');
        $this->line('  php artisan churchsuite:sync --all');

        return 0;
    }

    protected function testConnection(): int
    {
        $this->info('Testing ChurchSuite API connection...');

        $result = $this->service->testConnection();

        if ($result['success']) {
            $this->info('✅ ' . $result['message']);
            return 0;
        } else {
            $this->error('❌ ' . $result['message']);
            if (isset($result['error'])) {
                $this->line('Error: ' . $result['error']);
            }
            return 1;
        }
    }

    protected function syncSpecificMembers(): int
    {
        $memberIds = $this->option('member');
        $force = $this->option('force');

        $this->info("Syncing " . count($memberIds) . " member(s)...");
        $this->newLine();

        $successful = 0;
        $failed = 0;

        foreach ($memberIds as $memberId) {
            $member = Member::find($memberId);

            if (!$member) {
                $this->warn("Member ID {$memberId} not found");
                $failed++;
                continue;
            }

            // Skip if already synced unless force is used
            if (!$force && $member->churchsuite_sync_status === 'synced') {
                $this->line("⏭️  Skipping {$member->first_name} {$member->last_name} (already synced)");
                continue;
            }

            $this->line("Syncing: {$member->first_name} {$member->last_name} ({$member->email})");

            try {
                $result = $this->service->transferMember($member);

                if ($result['success']) {
                    $this->info("✅ Success - ChurchSuite ID: " . ($result['churchsuite_id'] ?? 'N/A'));
                    $successful++;
                } else {
                    $this->error("❌ Failed - " . $result['message']);
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->error("❌ Error - " . $e->getMessage());
                $failed++;
            }

            $this->newLine();
        }

        $this->info("Summary: {$successful} successful, {$failed} failed");
        return $failed > 0 ? 1 : 0;
    }

    protected function syncCDCGraduates(): int
    {
        $this->info('Syncing all CDC graduates...');
        $this->newLine();

        $results = $this->service->bulkTransferCDCGraduates();

        $this->info("Found {$results['total']} CDC graduates");
        $this->info("✅ Successful: {$results['successful']}");

        if ($results['failed'] > 0) {
            $this->error("❌ Failed: {$results['failed']}");
            $this->newLine();
            $this->warn('Errors:');
            foreach ($results['errors'] as $error) {
                $this->line("  • {$error['name']}: {$error['error']}");
            }
        }

        return $results['failed'] > 0 ? 1 : 0;
    }

    protected function syncAllMembers(): int
    {
        $force = $this->option('force');

        $query = Member::query()->where('is_active', true);

        if (!$force) {
            $query->where(function($q) {
                $q->whereNull('churchsuite_sync_status')
                  ->orWhere('churchsuite_sync_status', '!=', 'synced');
            });
        }

        $members = $query->get();

        if ($members->isEmpty()) {
            $this->info('No members to sync');
            return 0;
        }

        if (!$this->confirm("Sync {$members->count()} members to ChurchSuite?", true)) {
            $this->info('Cancelled');
            return 0;
        }

        $this->info("Syncing {$members->count()} members...");
        $this->newLine();

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        $successful = 0;
        $failed = 0;
        $errors = [];

        foreach ($members as $member) {
            try {
                $result = $this->service->transferMember($member);

                if ($result['success']) {
                    $successful++;
                } else {
                    $failed++;
                    $errors[] = "{$member->first_name} {$member->last_name}: {$result['message']}";
                }
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "{$member->first_name} {$member->last_name}: {$e->getMessage()}";
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Summary: {$successful} successful, {$failed} failed");

        if (!empty($errors)) {
            $this->newLine();
            $this->warn('Errors:');
            foreach ($errors as $error) {
                $this->line("  • {$error}");
            }
        }

        return $failed > 0 ? 1 : 0;
    }
}

