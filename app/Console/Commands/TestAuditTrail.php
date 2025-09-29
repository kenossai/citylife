<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AuditTrail;
use App\Services\AuditLogger;

class TestAuditTrail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:audit-trail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the audit trail system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Audit Trail System...');
        $this->info('==============================');

        // Test manual audit logging
        $this->info('1. Testing manual audit logging...');
        $audit = AuditLogger::log([
            'action' => 'test',
            'resource_type' => 'TestResource',
            'category' => 'system',
            'severity' => 'low',
            'description' => 'Testing audit trail system via command',
        ]);
        $this->info("✅ Manual audit log created with ID: {$audit->id}");

        // Test sensitive data access logging
        $this->info('2. Testing sensitive data access logging...');
        $user = User::first();
        if ($user) {
            AuditLogger::logSensitiveAccess($user, 'view', 'Viewed user profile for testing');
            $this->info("✅ Sensitive access logged for user: {$user->name}");
        }

        // Test data export logging
        $this->info('3. Testing data export logging...');
        AuditLogger::logDataExport('User', 10, 'CSV');
        $this->info('✅ Data export logged');

        // Test bulk action logging
        $this->info('4. Testing bulk action logging...');
        AuditLogger::logBulkAction('update', 'User', 5, [1, 2, 3, 4, 5]);
        $this->info('✅ Bulk action logged');

        // Test system action logging
        $this->info('5. Testing system action logging...');
        AuditLogger::logSystemAction('backup', 'Database backup completed', 'medium');
        $this->info('✅ System action logged');

        // Check total audit records
        $totalAudits = AuditTrail::count();
        $sensitiveAudits = AuditTrail::sensitive()->count();
        $recentAudits = AuditTrail::recent(1)->count();

        $this->info('Audit Trail Statistics:');
        $this->info("  Total audit records: {$totalAudits}");
        $this->info("  Sensitive records: {$sensitiveAudits}");
        $this->info("  Recent records (last 24 hours): {$recentAudits}");

        // Test model auditing (if we update a user)
        $this->info('6. Testing automatic model auditing...');
        if ($user) {
            $originalName = $user->name;
            $user->update(['name' => $originalName . ' (Updated)']);
            $this->info('✅ User updated - automatic audit should be created');

            // Revert the change
            $user->update(['name' => $originalName]);
            $this->info('✅ User reverted - another automatic audit should be created');
        }

        // Show recent audit trails
        $this->info('Recent Audit Trails:');
        $this->info('====================');
        $recent = AuditTrail::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        foreach ($recent as $audit) {
            $timestamp = $audit->created_at->format('H:i:s');
            $user = $audit->user_display_name;
            $action = $audit->action;
            $resource = $audit->resource_type_name;
            $sensitive = $audit->is_sensitive ? ' [SENSITIVE]' : '';
            $this->info("  {$timestamp} - {$user} {$action} {$resource}{$sensitive}");
        }

        $this->info('✅ Audit Trail System is working correctly!');
        $this->info('You can now view audit trails in the admin panel at /admin/audit-trails');

        return 0;
    }
}
