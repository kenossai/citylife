<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use App\Services\AuditLogger;
use Illuminate\Console\Command;

class BackupCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create
                            {type=database : The type of backup (full, database, files)}
                            {--name= : Custom name for the backup}
                            {--notes= : Notes for the backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the application data';

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
        $type = $this->argument('type');
        $name = $this->option('name');
        $notes = $this->option('notes');

        // Validate backup type
        if (!in_array($type, ['full', 'database', 'files'])) {
            $this->error('Invalid backup type. Must be one of: full, database, files');
            return 1;
        }

        $this->info("Creating {$type} backup...");

        $options = [
            'trigger_type' => 'command',
            'notes' => $notes,
        ];

        if ($name) {
            $options['name'] = $name;
        }

        try {
            $backup = match ($type) {
                'full' => $this->backupService->createFullBackup($options),
                'database' => $this->backupService->createDatabaseBackup($options),
                'files' => $this->backupService->createFilesBackup($options),
            };

            $this->info("Backup '{$backup->name}' created successfully!");
            $this->info("Backup ID: {$backup->id}");
            $this->info("Status: {$backup->status}");

            if ($backup->file_path) {
                $this->info("File Path: {$backup->file_path}");
            }

            if ($backup->file_size) {
                $this->info("File Size: " . $backup->getFormattedFileSizeAttribute());
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Backup failed: {$e->getMessage()}");

            AuditLogger::logSystemAction(
                'backup_command_failed',
                "Backup command failed: {$e->getMessage()}",
                'high',
                ['type' => $type, 'error' => $e->getMessage()]
            );

            return 1;
        }
    }
}
