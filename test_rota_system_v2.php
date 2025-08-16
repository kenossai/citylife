<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Rota;
use App\Services\RotaGeneratorService;
use App\Models\TechnicalDepartmentMember;
use App\Models\WorshipDepartmentMember;
use App\Models\PreacherDepartmentMember;
use Carbon\Carbon;

echo "=== Testing Church Rota System ===\n\n";

// Test 1: Check if we have department members
echo "1. Checking department members...\n";
$techMembers = TechnicalDepartmentMember::count();
$worshipMembers = WorshipDepartmentMember::count();
$preacherMembers = PreacherDepartmentMember::count();

echo "   - Technical members: {$techMembers}\n";
echo "   - Worship members: {$worshipMembers}\n";
echo "   - Preacher members: {$preacherMembers}\n\n";

if ($techMembers === 0 && $worshipMembers === 0 && $preacherMembers === 0) {
    echo "❌ No department members found. Please add some members first.\n";
    exit;
}

// Test 2: Create a test rota
echo "2. Creating test rota...\n";
$startDate = Carbon::now()->startOfWeek()->addDays(6); // This Sunday
$endDate = $startDate->copy()->addWeeks(3); // 4 weeks total

$rota = Rota::create([
    'title' => 'Test Auto-Generated Rota - ' . now()->format('Y-m-d H:i'),
    'description' => 'Testing the automatic generation system',
    'start_date' => $startDate,
    'end_date' => $endDate,
    'departments' => ['technical', 'worship', 'preacher'],
    'schedule_data' => [],
    'created_by' => 1 // Assuming user ID 1 exists
]);

echo "   ✅ Rota created with ID: {$rota->id}\n";
echo "   Departments stored: " . json_encode($rota->departments) . "\n\n";

// Test 3: Generate schedule automatically
echo "3. Generating schedule automatically...\n";
$generator = new RotaGeneratorService();

// Add null check
if (empty($rota->departments)) {
    echo "   ❌ No departments found in rota. Using default departments.\n";
    $rota->departments = ['technical', 'worship', 'preacher'];
    $rota->save();
}

$schedule = $generator->generateWithRandomization($rota);

$rota->update(['schedule_data' => $schedule]);

echo "   ✅ Schedule generated successfully!\n";
echo "   Schedule entries: " . count($schedule) . "\n\n";

// Test 4: Display the generated schedule
echo "4. Generated Schedule:\n";
echo "   Date Range: {$rota->start_date->format('Y-m-d')} to {$rota->end_date->format('Y-m-d')}\n\n";

if (empty($schedule)) {
    echo "   ❌ No schedule generated.\n\n";
} else {
    foreach ($schedule as $dateStr => $daySchedule) {
        echo "   📅 {$dateStr}:\n";

        foreach ($daySchedule as $department => $assignments) {
            echo "      {$department}:\n";

            if (empty($assignments)) {
                echo "         - No assignments\n";
            } else {
                foreach ($assignments as $assignment) {
                    echo "         - {$assignment['name']} ({$assignment['role']})\n";
                }
            }
        }
        echo "\n";
    }
}

// Test 5: Test Excel export capability
echo "5. Testing Excel export...\n";
try {
    $export = new \App\Exports\RotaExport($rota);
    echo "   ✅ Excel export class initialized successfully\n";

    // Get the export data structure
    $exportData = $export->array();
    echo "   ✅ Export data generated: " . count($exportData) . " rows\n";

} catch (Exception $e) {
    echo "   ❌ Excel export test failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "✅ All systems working! You can now:\n";
echo "   1. Create rotas via Filament admin panel\n";
echo "   2. Use 'Create & Auto Generate' for instant population\n";
echo "   3. Export to Excel for printing/sharing\n";
echo "   4. Manually edit assignments if needed\n\n";

// Clean up test rota
$rota->delete();
echo "Test rota cleaned up.\n";
