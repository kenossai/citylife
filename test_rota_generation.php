<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Rota;
use App\Services\RotaGeneratorService;

// Test the enhanced rota generation
echo "Testing enhanced rota generation...\n";

// Get the latest rota
$rota = Rota::latest()->first();

if (!$rota) {
    echo "No rota found. Creating a test rota...\n";
    $rota = Rota::create([
        'title' => 'Test Enhanced Rota Generation',
        'description' => 'Testing the new enhanced rota generation system',
        'departments' => ['worship', 'technical', 'preacher'],
        'start_date' => '2025-11-03',
        'end_date' => '2025-11-24',
        'schedule_data' => [],
        'is_published' => false,
        'created_by' => 1,
    ]);
}

echo "Using rota: {$rota->title}\n";

// Generate the schedule
$generator = new RotaGeneratorService();
$schedule = $generator->generateWithRandomization($rota);

echo "Generated " . count($schedule) . " roles\n";

// Show sample assignments
echo "\nSample role assignments:\n";
$count = 0;
foreach ($schedule as $role => $assignments) {
    if ($count >= 5) break;
    echo "- {$role}: " . count($assignments) . " date assignments\n";
    if (count($assignments) > 0) {
        $firstDate = array_key_first($assignments);
        $firstAssignment = $assignments[$firstDate];
        echo "  Example: {$firstDate} -> {$firstAssignment}\n";
    }
    $count++;
}

// Update the rota with the generated schedule
$rota->update(['schedule_data' => $schedule]);

echo "\nRota updated successfully!\n";
echo "You can now test the Excel export in the admin panel.\n";
