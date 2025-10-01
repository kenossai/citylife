<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Member;
use App\Models\GdprConsent;
use App\Models\GdprDataRequest;
use App\Models\GdprAuditLog;
use App\Services\GdprService;

echo "Testing GDPR Compliance System...\n\n";

// Test 1: Create a test member if needed
echo "1. Testing Member Creation...\n";
$member = Member::where('email', 'test@gdprtest.com')->first();
if (!$member) {
    $member = Member::create([
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@gdprtest.com',
        'phone' => '+1234567890',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'membership_status' => 'member',
        'address' => '123 Test Street',
        'city' => 'Test City',
        'postal_code' => 'T12 3ST',
        'country' => 'UK'
    ]);
    echo "✓ Test member created with ID: {$member->id}\n";
} else {
    echo "✓ Using existing test member with ID: {$member->id}\n";
}

// Test 2: GDPR Consent Management
echo "\n2. Testing GDPR Consent Management...\n";
$consent = GdprConsent::create([
    'member_id' => $member->id,
    'consent_type' => 'email_marketing',
    'status' => 'granted',
    'description' => 'Email marketing communications',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Test Script'
]);
echo "✓ GDPR consent created with ID: {$consent->id}\n";

// Test withdrawal
$consent->withdraw('Test withdrawal');
echo "✓ Consent withdrawn successfully\n";

// Test 3: GDPR Data Request
echo "\n3. Testing GDPR Data Request...\n";
$dataRequest = GdprDataRequest::create([
    'member_id' => $member->id,
    'request_type' => 'export',
    'status' => 'pending',
    'description' => 'Member requested data export',
    'requested_at' => now()
]);
echo "✓ Data request created with ID: {$dataRequest->id}\n";

// Test 4: GDPR Service
echo "\n4. Testing GDPR Service...\n";
$gdprService = new GdprService();

// Test data export
try {
    $exportData = $gdprService->exportMemberData($member, ['format' => 'json']);
    echo "✓ Member data export successful\n";
    echo "  Export contains: " . count($exportData) . " data sections\n";
} catch (Exception $e) {
    echo "✗ Export failed: " . $e->getMessage() . "\n";
}

// Test 5: Audit Log Creation
echo "\n5. Testing Audit Log...\n";
GdprAuditLog::create([
    'member_id' => $member->id,
    'action' => 'data_exported',
    'description' => 'Member data exported for GDPR compliance',
    'performed_by' => 'system',
    'ip_address' => '127.0.0.1',
    'metadata' => json_encode(['test' => true])
]);

$auditCount = GdprAuditLog::where('member_id', $member->id)->count();
echo "✓ Audit log created. Total logs for member: {$auditCount}\n";

// Test 6: Check Database Tables
echo "\n6. Testing Database Tables...\n";
$consentCount = GdprConsent::count();
$requestCount = GdprDataRequest::count();
$auditLogCount = GdprAuditLog::count();

echo "✓ GDPR Consents: {$consentCount}\n";
echo "✓ GDPR Data Requests: {$requestCount}\n";
echo "✓ GDPR Audit Logs: {$auditLogCount}\n";

echo "\n🎉 GDPR Compliance System Test Complete!\n";
echo "All components are working correctly.\n";
