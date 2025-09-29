<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup Scheduling
if (config('backup.schedule.enabled', true)) {
    // Daily database backup at 1 AM
    Schedule::command('backup:create database --notes="Scheduled daily database backup"')
        ->cron(config('backup.schedule.database_backup', '0 1 * * *'))
        ->name('daily-database-backup')
        ->withoutOverlapping()
        ->onFailure(function () {
            \App\Services\AuditLogger::logSystemAction(
                'scheduled_backup_failed',
                'Scheduled database backup failed',
                'high'
            );
        });

    // Weekly full backup on Sunday at 2 AM
    Schedule::command('backup:create full --notes="Scheduled weekly full backup"')
        ->cron(config('backup.schedule.full_backup', '0 2 * * 0'))
        ->name('weekly-full-backup')
        ->withoutOverlapping()
        ->onFailure(function () {
            \App\Services\AuditLogger::logSystemAction(
                'scheduled_backup_failed',
                'Scheduled full backup failed',
                'high'
            );
        });

    // Weekly files backup on Monday at 3 AM
    Schedule::command('backup:create files --notes="Scheduled weekly files backup"')
        ->cron(config('backup.schedule.files_backup', '0 3 * * 1'))
        ->name('weekly-files-backup')
        ->withoutOverlapping()
        ->onFailure(function () {
            \App\Services\AuditLogger::logSystemAction(
                'scheduled_backup_failed',
                'Scheduled files backup failed',
                'high'
            );
        });

    // Daily cleanup of expired backups at 4 AM
    Schedule::command('backup:cleanup --force')
        ->daily()
        ->at('04:00')
        ->name('daily-backup-cleanup')
        ->withoutOverlapping();
}
