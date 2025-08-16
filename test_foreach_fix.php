<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Rota;
use App\Services\RotaGeneratorService;
use Carbon\Carbon;

echo "=== Testing Rota Generation Edge Cases ===\n\n";

// Test Case 1: Rota with null departments
echo "1. Testing rota with null departments...\n";
$rota1 = Rota::create([
    'title' => 'Test Null Departments',
    'department_type' => 'worship',
    'start_date' => Carbon::now()->next('Sunday'),
    'end_date' => Carbon::now()->next('Sunday')->addWeek(),
    'schedule_data' => [],
    'departments' => null, // Explicitly null
    'created_by' => 1
]);

$generator = new RotaGeneratorService();
try {
    $schedule1 = $generator->generateWithRandomization($rota1);
    echo "   ✅ Success! Generated " . count($schedule1) . " schedule entries\n";
} catch (Exception $e) {
    echo "   ❌ Failed: " . $e->getMessage() . "\n";
}

// Test Case 2: Rota with empty departments array
echo "\n2. Testing rota with empty departments array...\n";
$rota2 = Rota::create([
    'title' => 'Test Empty Departments',
    'department_type' => 'technical',
    'start_date' => Carbon::now()->next('Sunday'),
    'end_date' => Carbon::now()->next('Sunday')->addWeek(),
    'schedule_data' => [],
    'departments' => [], // Empty array
    'created_by' => 1
]);

try {
    $schedule2 = $generator->generateWithRandomization($rota2);
    echo "   ✅ Success! Generated " . count($schedule2) . " schedule entries\n";
} catch (Exception $e) {
    echo "   ❌ Failed: " . $e->getMessage() . "\n";
}

// Test Case 3: Rota with valid departments array
echo "\n3. Testing rota with valid departments array...\n";
$rota3 = Rota::create([
    'title' => 'Test Valid Departments',
    'start_date' => Carbon::now()->next('Sunday'),
    'end_date' => Carbon::now()->next('Sunday')->addWeek(),
    'schedule_data' => [],
    'departments' => ['worship', 'technical'], // Valid array
    'created_by' => 1
]);

try {
    $schedule3 = $generator->generateWithRandomization($rota3);
    echo "   ✅ Success! Generated " . count($schedule3) . " schedule entries\n";

    // Show sample of generated data
    if (!empty($schedule3)) {
        $firstDate = array_key_first($schedule3);
        echo "   Sample assignment for {$firstDate}:\n";
        foreach ($schedule3[$firstDate] as $dept => $assignments) {
            echo "     {$dept}: " . count($assignments) . " assignments\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Failed: " . $e->getMessage() . "\n";
}

// Clean up
$rota1->delete();
$rota2->delete();
$rota3->delete();

echo "\n=== Test Complete ===\n";
echo "✅ The foreach error should now be fixed!\n";
