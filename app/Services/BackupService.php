<?php

namespace App\Services;

use App\Models\BackupLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;

class BackupService
{
    protected $backupDisk = 'local';
    protected $backupPath = 'backups';
    protected $maxRetries = 3;

    public function __construct()
    {
        // Ensure backup directory exists
        if (!Storage::disk($this->backupDisk)->exists($this->backupPath)) {
            Storage::disk($this->backupDisk)->makeDirectory($this->backupPath);
        }
    }

    /**
     * Create a full backup (database + files)
     */
    public function createFullBackup(array $options = []): BackupLog
    {
        return $this->createBackup('full', $options);
    }

    /**
     * Create a database-only backup
     */
    public function createDatabaseBackup(array $options = []): BackupLog
    {
        return $this->createBackup('database', $options);
    }

    /**
     * Create a files-only backup
     */
    public function createFilesBackup(array $options = []): BackupLog
    {
        return $this->createBackup('files', $options);
    }

    /**
     * Create a backup of specified type
     */
    protected function createBackup(string $type, array $options = []): BackupLog
    {
        $backupLog = $this->initializeBackupLog($type, $options);

        try {
            $backupLog->update(['status' => 'running', 'started_at' => now()]);

            // Log the backup start
            AuditLogger::logSystemAction(
                'backup_started',
                "Backup '{$backupLog->name}' started",
                'medium',
                ['backup_id' => $backupLog->id, 'type' => $type]
            );

            $backupPath = $this->getBackupPath($backupLog);

            switch ($type) {
                case 'full':
                    $this->createFullBackupFiles($backupLog, $backupPath, $options);
                    break;
                case 'database':
                    $this->createDatabaseBackupFile($backupLog, $backupPath, $options);
                    break;
                case 'files':
                    $this->createFilesBackupFile($backupLog, $backupPath, $options);
                    break;
            }

            $this->finalizeBackup($backupLog, $backupPath);

            // Log successful backup
            AuditLogger::logSystemAction(
                'backup_completed',
                "Backup '{$backupLog->name}' completed successfully",
                'low',
                ['backup_id' => $backupLog->id, 'file_size' => $backupLog->file_size]
            );

            return $backupLog;

        } catch (\Exception $e) {
            $this->handleBackupFailure($backupLog, $e);
            throw $e;
        }
    }

    /**
     * Initialize backup log entry
     */
    protected function initializeBackupLog(string $type, array $options): BackupLog
    {
        $name = $options['name'] ?? $this->generateBackupName($type);

        return BackupLog::create([
            'name' => $name,
            'type' => $type,
            'status' => 'pending',
            'trigger_type' => $options['trigger_type'] ?? 'manual',
            'created_by' => auth()->id() ?? null,
            'config' => $options,
            'notes' => $options['notes'] ?? null,
        ]);
    }

    /**
     * Generate backup name
     */
    protected function generateBackupName(string $type): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $appName = Str::slug(config('app.name', 'app'));

        return "{$appName}_{$type}_backup_{$timestamp}";
    }

    /**
     * Get backup file path
     */
    protected function getBackupPath(BackupLog $backupLog): string
    {
        $date = $backupLog->created_at->format('Y/m/d');
        return "{$this->backupPath}/{$date}/{$backupLog->name}";
    }

    /**
     * Create full backup (database + files)
     */
    protected function createFullBackupFiles(BackupLog $backupLog, string $backupPath, array $options): void
    {
        $tempDir = storage_path("temp/backup_{$backupLog->id}");
        $this->ensureDirectoryExists($tempDir);

        try {
            // Create database backup
            $dbFile = $tempDir . '/database.sql';
            $this->createDatabaseDump($dbFile, $options);

            // Create files backup
            $filesZip = $tempDir . '/files.zip';
            $this->createFilesArchive($filesZip, $options);

            // Create final archive
            $finalZipPath = storage_path("app/{$backupPath}.zip");
            $this->ensureDirectoryExists(dirname($finalZipPath));

            $this->createArchive($finalZipPath, [$dbFile, $filesZip]);

            // Update backup log
            $backupLog->update([
                'file_path' => "{$backupPath}.zip",
                'file_size' => filesize($finalZipPath),
                'compression' => 'zip',
                'database_tables' => $this->getDatabaseTables(),
                'file_directories' => $this->getBackupDirectories($options),
                'checksum' => hash_file('sha256', $finalZipPath),
            ]);

        } finally {
            // Clean up temporary directory
            $this->cleanupDirectory($tempDir);
        }
    }

    /**
     * Create database backup file
     */
    protected function createDatabaseBackupFile(BackupLog $backupLog, string $backupPath, array $options): void
    {
        $sqlFile = storage_path("app/{$backupPath}.sql");
        $this->ensureDirectoryExists(dirname($sqlFile));

        $this->createDatabaseDump($sqlFile, $options);

        $backupLog->update([
            'file_path' => "{$backupPath}.sql",
            'file_size' => filesize($sqlFile),
            'database_tables' => $this->getDatabaseTables(),
            'records_count' => $this->getTotalRecordsCount(),
            'checksum' => hash_file('sha256', $sqlFile),
        ]);
    }

    /**
     * Create files backup
     */
    protected function createFilesBackupFile(BackupLog $backupLog, string $backupPath, array $options): void
    {
        $zipFile = storage_path("app/{$backupPath}.zip");
        $this->ensureDirectoryExists(dirname($zipFile));

        $this->createFilesArchive($zipFile, $options);

        $backupLog->update([
            'file_path' => "{$backupPath}.zip",
            'file_size' => filesize($zipFile),
            'compression' => 'zip',
            'file_directories' => $this->getBackupDirectories($options),
            'checksum' => hash_file('sha256', $zipFile),
        ]);
    }

    /**
     * Create database dump
     */
    protected function createDatabaseDump(string $filePath, array $options): void
    {
        // Try mysqldump first, fall back to PHP-based backup
        if ($this->isMysqlDumpAvailable()) {
            $this->createMysqlDump($filePath);
        } else {
            $this->createPhpDatabaseDump($filePath);
        }
    }

    /**
     * Check if mysqldump is available
     */
    protected function isMysqlDumpAvailable(): bool
    {
        $result = Process::run('mysqldump --version');
        return $result->successful();
    }

    /**
     * Create MySQL dump using mysqldump command
     */
    protected function createMysqlDump(string $filePath): void
    {
        $connection = config('database.default');
        $config = config('database.connections')[$connection] ?? [];

        $database = $config['database'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 3306;

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($filePath)
        );

        $result = Process::run($command);

        if ($result->failed()) {
            throw new \Exception("Database backup failed: " . $result->errorOutput());
        }
    }

    /**
     * Create database dump using PHP (fallback method)
     */
    protected function createPhpDatabaseDump(string $filePath): void
    {
        $sql = "-- Database Backup Generated by CityLife\n";
        $sql .= "-- Date: " . now()->format('Y-m-d H:i:s') . "\n\n";

        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "START TRANSACTION;\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";

        // Get all tables
        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.' . config('database.default') . '.database');

        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];

            // Skip migration tables and cache tables
            if (in_array($tableName, ['migrations', 'cache', 'cache_locks', 'sessions', 'failed_jobs', 'job_batches'])) {
                continue;
            }

            $sql .= "\n-- --------------------------------------------------------\n";
            $sql .= "-- Table structure for table `{$tableName}`\n";
            $sql .= "-- --------------------------------------------------------\n\n";

            // Get table structure
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            // Get table data
            $rows = DB::table($tableName)->get();

            if ($rows->count() > 0) {
                $sql .= "-- Dumping data for table `{$tableName}`\n\n";
                $sql .= "INSERT INTO `{$tableName}` VALUES\n";

                $values = [];
                foreach ($rows as $row) {
                    $rowValues = [];
                    foreach ((array) $row as $value) {
                        if (is_null($value)) {
                            $rowValues[] = 'NULL';
                        } else {
                            $rowValues[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $values[] = '(' . implode(', ', $rowValues) . ')';
                }

                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }

        $sql .= "COMMIT;\n";

        file_put_contents($filePath, $sql);
    }

    /**
     * Create files archive
     */
    protected function createFilesArchive(string $zipPath, array $options): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception("Cannot create zip file: {$zipPath}");
        }

        $directories = $options['directories'] ?? [
            'storage/app',
            'public/storage',
            'resources',
            'config'
        ];

        foreach ($directories as $directory) {
            $fullPath = base_path($directory);
            if (is_dir($fullPath)) {
                $this->addDirectoryToZip($zip, $fullPath, $directory);
            }
        }

        $zip->close();
    }

    /**
     * Add directory to zip archive recursively
     */
    protected function addDirectoryToZip(ZipArchive $zip, string $sourcePath, string $localPath): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $filePath = $file->getRealPath();
            $relativePath = $localPath . '/' . substr($filePath, strlen($sourcePath) + 1);

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } elseif ($file->isFile()) {
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    /**
     * Create archive from multiple files
     */
    protected function createArchive(string $zipPath, array $files): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception("Cannot create archive: {$zipPath}");
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                $zip->addFile($file, basename($file));
            }
        }

        $zip->close();
    }

    /**
     * Finalize backup
     */
    protected function finalizeBackup(BackupLog $backupLog, string $backupPath): void
    {
        $completedAt = now();
        $duration = $completedAt->diffInSeconds($backupLog->started_at);

        $backupLog->update([
            'status' => 'completed',
            'completed_at' => $completedAt,
            'duration_seconds' => $duration,
            'is_restorable' => true,
            'expires_at' => $this->calculateExpirationDate($backupLog->type),
        ]);
    }

    /**
     * Handle backup failure
     */
    protected function handleBackupFailure(BackupLog $backupLog, \Exception $e): void
    {
        $backupLog->update([
            'status' => 'failed',
            'completed_at' => now(),
            'error_message' => $e->getMessage(),
            'error_details' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ],
            'retry_count' => ($backupLog->retry_count ?? 0) + 1,
        ]);

        // Log backup failure
        AuditLogger::logSystemAction(
            'backup_failed',
            "Backup '{$backupLog->name}' failed: {$e->getMessage()}",
            'high',
            ['backup_id' => $backupLog->id, 'error' => $e->getMessage()]
        );

        Log::error("Backup failed: {$e->getMessage()}", [
            'backup_id' => $backupLog->id,
            'exception' => $e
        ]);
    }

    /**
     * Get all database tables
     */
    protected function getDatabaseTables(): array
    {
        return DB::select('SHOW TABLES');
    }

    /**
     * Get total records count
     */
    protected function getTotalRecordsCount(): int
    {
        $tables = $this->getDatabaseTables();
        $total = 0;

        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $count = DB::table($tableName)->count();
            $total += $count;
        }

        return $total;
    }

    /**
     * Get backup directories
     */
    protected function getBackupDirectories(array $options): array
    {
        return $options['directories'] ?? [
            'storage/app',
            'public/storage',
            'resources',
            'config'
        ];
    }

    /**
     * Calculate expiration date
     */
    protected function calculateExpirationDate(string $type): Carbon
    {
        $retentionDays = config('backup.retention_days', [
            'full' => 90,
            'database' => 30,
            'files' => 60,
        ]);

        $days = $retentionDays[$type] ?? 30;

        return now()->addDays($days);
    }

    /**
     * Ensure directory exists
     */
    protected function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Clean up directory
     */
    protected function cleanupDirectory(string $path): void
    {
        if (is_dir($path)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }

            rmdir($path);
        }
    }

    /**
     * List all backups
     */
    public function listBackups(array $filters = [])
    {
        $query = BackupLog::query();

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate();
    }

    /**
     * Delete backup
     */
    public function deleteBackup(BackupLog $backupLog): bool
    {
        try {
            // Delete physical backup file
            if ($backupLog->file_path && Storage::disk($this->backupDisk)->exists($backupLog->file_path)) {
                Storage::disk($this->backupDisk)->delete($backupLog->file_path);
            }

            // Log deletion
            AuditLogger::logSystemAction(
                'backup_deleted',
                "Backup '{$backupLog->name}' was deleted",
                'medium',
                ['backup_id' => $backupLog->id]
            );

            // Delete database record
            $backupLog->delete();

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete backup: {$e->getMessage()}", [
                'backup_id' => $backupLog->id,
                'exception' => $e
            ]);

            return false;
        }
    }

    /**
     * Cleanup expired backups
     */
    public function cleanupExpiredBackups(): int
    {
        $expiredBackups = BackupLog::where('expires_at', '<', now())
            ->where('status', 'completed')
            ->get();

        $deletedCount = 0;

        foreach ($expiredBackups as $backup) {
            if ($this->deleteBackup($backup)) {
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            AuditLogger::logSystemAction(
                'backup_cleanup',
                "Cleaned up {$deletedCount} expired backups",
                'low',
                ['deleted_count' => $deletedCount]
            );
        }

        return $deletedCount;
    }
}
