<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use App\Services\AuditLogger;
use Illuminate\Console\Command;

class BackupCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleanup
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired backup files';

    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('Scanning for expired backups...');

        try {
            if ($dryRun) {
                $expiredBackups = \App\Models\BackupLog::where('expires_at', '<', now())
                    ->where('status', 'completed')
                    ->get();

                if ($expiredBackups->isEmpty()) {
                    $this->info('No expired backups found.');
                    return 0;
                }

                $this->info("Found {$expiredBackups->count()} expired backup(s):");

                $this->table(
                    ['ID', 'Name', 'Type', 'Size', 'Expired At'],
                    $expiredBackups->map(function ($backup) {
                        return [
                            $backup->id,
                            $backup->name,
                            $backup->type,
                            $backup->getFormattedFileSizeAttribute(),
                            $backup->expires_at->format('Y-m-d H:i:s'),
                        ];
                    })->toArray()
                );

                $this->info('Run without --dry-run to actually delete these backups.');
                return 0;
            }

            // Actual cleanup
            $deletedCount = $this->backupService->cleanupExpiredBackups();

            if ($deletedCount > 0) {
                $this->info("Successfully cleaned up {$deletedCount} expired backup(s).");

                AuditLogger::logSystemAction(
                    'backup_cleanup_command',
                    "Cleanup command deleted {$deletedCount} expired backups",
                    'low',
                    ['deleted_count' => $deletedCount]
                );
            } else {
                $this->info('No expired backups found to clean up.');
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Cleanup failed: {$e->getMessage()}");

            AuditLogger::logSystemAction(
                'backup_cleanup_failed',
                "Backup cleanup command failed: {$e->getMessage()}",
                'medium',
                ['error' => $e->getMessage()]
            );

            return 1;
        }
    }
}
