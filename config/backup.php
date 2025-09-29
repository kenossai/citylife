<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure backup settings for your application.
    |
    */

    'disk' => env('BACKUP_DISK', 'local'),

    'path' => env('BACKUP_PATH', 'backups'),

    'retention_days' => [
        'full' => env('BACKUP_RETENTION_FULL', 90),
        'database' => env('BACKUP_RETENTION_DATABASE', 30),
        'files' => env('BACKUP_RETENTION_FILES', 60),
    ],

    'compression' => env('BACKUP_COMPRESSION', 'zip'),

    'encryption' => env('BACKUP_ENCRYPTION', false),

    'max_retries' => env('BACKUP_MAX_RETRIES', 3),

    'timeout' => env('BACKUP_TIMEOUT', 3600), // 1 hour

    'directories' => [
        'storage/app',
        'public/storage',
        'resources',
        'config',
    ],

    'exclude_directories' => [
        'storage/logs',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'node_modules',
        'vendor',
        '.git',
    ],

    'database' => [
        'timeout' => env('BACKUP_DB_TIMEOUT', 1800), // 30 minutes
        'single_transaction' => true,
        'add_drop_table' => true,
        'add_locks' => true,
    ],

    'notifications' => [
        'success' => env('BACKUP_NOTIFY_SUCCESS', false),
        'failure' => env('BACKUP_NOTIFY_FAILURE', true),
        'channels' => ['mail'], // mail, slack, etc.
    ],

    'schedule' => [
        'enabled' => env('BACKUP_SCHEDULE_ENABLED', true),
        'full_backup' => env('BACKUP_SCHEDULE_FULL', '0 2 * * 0'), // Weekly on Sunday at 2 AM
        'database_backup' => env('BACKUP_SCHEDULE_DATABASE', '0 1 * * *'), // Daily at 1 AM
        'files_backup' => env('BACKUP_SCHEDULE_FILES', '0 3 * * 1'), // Weekly on Monday at 3 AM
    ],
];
