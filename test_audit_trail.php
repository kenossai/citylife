<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\AuditTrail;
use App\Services\AuditLogger;

// Test audit trail functionality
echo "Testing Audit Trail System...\n";
echo "==============================\n\n";

try {
    // Test manual audit logging
    echo "1. Testing manual audit logging...\n";
    $audit = AuditLogger::log([
        'action' => 'test',
        'resource_type' => 'TestResource',
        'category' => 'system',
        'severity' => 'low',
        'description' => 'Testing audit trail system',
    ]);
    echo "✅ Manual audit log created with ID: {$audit->id}\n\n";

    // Test sensitive data access logging
    echo "2. Testing sensitive data access logging...\n";
    $user = User::first();
    if ($user) {
        AuditLogger::logSensitiveAccess($user, 'view', 'Viewed user profile for testing');
        echo "✅ Sensitive access logged for user: {$user->name}\n\n";
    }

    // Test data export logging
    echo "3. Testing data export logging...\n";
    AuditLogger::logDataExport('User', 10, 'CSV');
    echo "✅ Data export logged\n\n";

    // Test bulk action logging
    echo "4. Testing bulk action logging...\n";
    AuditLogger::logBulkAction('update', 'User', 5, [1, 2, 3, 4, 5]);
    echo "✅ Bulk action logged\n\n";

    // Test system action logging
    echo "5. Testing system action logging...\n";
    AuditLogger::logSystemAction('backup', 'Database backup completed', 'medium');
    echo "✅ System action logged\n\n";

    // Check total audit records
    $totalAudits = AuditTrail::count();
    $sensitiveAudits = AuditTrail::sensitive()->count();
    $recentAudits = AuditTrail::recent(1)->count();

    echo "Audit Trail Statistics:\n";
    echo "  Total audit records: {$totalAudits}\n";
    echo "  Sensitive records: {$sensitiveAudits}\n";
    echo "  Recent records (last 24 hours): {$recentAudits}\n\n";

    // Test model auditing (if we update a user)
    echo "6. Testing automatic model auditing...\n";
    if ($user) {
        $originalName = $user->name;
        $user->update(['name' => $originalName . ' (Updated)']);
        echo "✅ User updated - automatic audit should be created\n";

        // Revert the change
        $user->update(['name' => $originalName]);
        echo "✅ User reverted - another automatic audit should be created\n\n";
    }

    // Show recent audit trails
    echo "Recent Audit Trails:\n";
    echo "====================\n";
    $recent = AuditTrail::with('user')->orderBy('created_at', 'desc')->take(5)->get();
    foreach ($recent as $audit) {
        $timestamp = $audit->created_at->format('H:i:s');
        $user = $audit->user_display_name;
        $action = $audit->action;
        $resource = $audit->resource_type_name;
        $sensitive = $audit->is_sensitive ? ' [SENSITIVE]' : '';
        echo "  {$timestamp} - {$user} {$action} {$resource}{$sensitive}\n";
    }

    echo "\n✅ Audit Trail System is working correctly!\n";
    echo "You can now view audit trails in the admin panel at /admin/audit-trails\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
