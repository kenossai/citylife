<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Rota;
use App\Services\RotaGeneratorService;
use Carbon\Carbon;

echo "=== Testing Fixed Rota Generation (Sundays Only) ===\n\n";

// Create a test rota
echo "1. Creating test rota for 4 weeks...\n";
$startDate = Carbon::now()->next('Sunday'); // Next Sunday
$endDate = $startDate->copy()->addWeeks(3); // 4 weeks total

$rota = Rota::create([
    'title' => 'Fixed Sunday Rota Test - ' . now()->format('Y-m-d H:i'),
    'description' => 'Testing fixed Sunday-only generation',
    'departments' => ['worship', 'technical', 'preacher'],
    'start_date' => $startDate,
    'end_date' => $endDate,
    'schedule_data' => [],
    'created_by' => 1
]);

echo "   ✅ Rota created with ID: {$rota->id}\n";
echo "   📅 Date range: {$startDate->format('Y-m-d (l)')} to {$endDate->format('Y-m-d (l)')}\n\n";

// Generate schedule
echo "2. Generating Sunday-only schedule...\n";
$generator = new RotaGeneratorService();
$schedule = $generator->generateWithRandomization($rota);

echo "   📊 Schedule structure analysis:\n";
echo "   - Total roles: " . count($schedule) . "\n";

if (!empty($schedule)) {
    $firstRole = array_key_first($schedule);
    $datesForFirstRole = array_keys($schedule[$firstRole]);
    echo "   - Sample role: {$firstRole}\n";
    echo "   - Dates for this role: " . count($datesForFirstRole) . "\n";
    echo "   - Date examples: " . implode(', ', array_slice($datesForFirstRole, 0, 2)) . "\n";

    // Verify all dates are Sundays
    $allSundays = true;
    foreach ($datesForFirstRole as $dateStr) {
        $date = Carbon::parse($dateStr);
        if ($date->dayOfWeek !== Carbon::SUNDAY) {
            $allSundays = false;
            break;
        }
    }
    echo "   - All dates are Sundays: " . ($allSundays ? "✅ YES" : "❌ NO") . "\n";
}

// Update rota with generated schedule
$rota->update(['schedule_data' => $schedule]);
echo "   ✅ Schedule saved to rota\n\n";

// Display the schedule in the expected format
echo "3. Generated Schedule (Role → Date → Member format):\n";
if (empty($schedule)) {
    echo "   ❌ No schedule generated\n";
} else {
    foreach ($schedule as $role => $dateAssignments) {
        echo "   🎵 {$role}:\n";
        foreach ($dateAssignments as $date => $memberName) {
            $dayName = Carbon::parse($date)->format('M j (l)');
            $member = empty($memberName) ? '(No assignment)' : $memberName;
            echo "      {$dayName}: {$member}\n";
        }
        echo "\n";
    }
}

// Test Excel export structure
echo "4. Testing Excel export structure...\n";
try {
    $export = new \App\Exports\RotaExport($rota);
    $exportData = $export->array();
    $headings = $export->headings();

    echo "   ✅ Export initialized successfully\n";
    echo "   📊 Export data rows: " . count($exportData) . "\n";
    echo "   📅 Column headings: " . implode(', ', array_slice($headings, 0, 5)) . "...\n";

    if (!empty($exportData)) {
        echo "   📝 Sample row: " . implode(' | ', array_slice($exportData[0], 0, 3)) . "...\n";
    }
} catch (Exception $e) {
    echo "   ❌ Excel export failed: " . $e->getMessage() . "\n";
}

echo "\n5. Sunday Verification:\n";
echo "   📅 Expected Sundays in range:\n";
$current = $startDate->copy();
while ($current->lte($endDate)) {
    echo "      {$current->format('Y-m-d (l)')}\n";
    $current->addWeek();
}

// Clean up
$rota->delete();
echo "\n=== Test Complete ===\n";
echo "✅ The rota should now:\n";
echo "   1. Generate only Sunday dates\n";
echo "   2. Assign members to specific roles\n";
echo "   3. Use proper role → date → member structure\n";
echo "   4. Export correctly to Excel format\n";
