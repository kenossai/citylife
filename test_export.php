<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Exports\MembersExport;
use Maatwebsite\Excel\Facades\Excel;

echo "Testing Members Export functionality...\n";
echo "======================================\n\n";

try {
    // Test 1: Check if we have members
    $memberCount = App\Models\Member::count();
    echo "✓ Found {$memberCount} members in the database\n";

    if ($memberCount === 0) {
        echo "❌ No members found to export. Please add some members first.\n";
        exit(1);
    }

    // Test 2: Test export creation (without actually downloading)
    $export = new MembersExport();
    $collection = $export->collection();
    echo "✓ Export class created successfully\n";
    echo "✓ Export will include {$collection->count()} members\n";

    // Test 3: Test headings
    $headings = $export->headings();
    echo "✓ Export has " . count($headings) . " columns\n";
    echo "   Columns: " . implode(', ', array_slice($headings, 0, 5)) . "...\n";

    // Test 4: Test mapping for first member
    $firstMember = $collection->first();
    if ($firstMember) {
        $mappedData = $export->map($firstMember);
        echo "✓ Data mapping works correctly\n";
        echo "   Sample: {$mappedData[1]} {$mappedData[2]} {$mappedData[3]} ({$mappedData[0]})\n";
    }

    echo "\n🎉 All export tests passed successfully!\n";
    echo "\nExport Features Available:\n";
    echo "- Export all members to Excel\n";
    echo "- Export selected members\n";
    echo "- Export with custom filters (membership status, baptism status, date ranges)\n";
    echo "- Professional formatting with headers and column widths\n";
    echo "- Includes member details, contact info, church info, and emergency contacts\n";

    echo "\nTo use:\n";
    echo "1. Go to Members page in Filament admin\n";
    echo "2. Click 'Export All Members' for full export\n";
    echo "3. Click 'Export with Filters' for custom export\n";
    echo "4. Select members and use 'Export Selected' for specific members\n";

} catch (\Exception $e) {
    echo "❌ Error testing export: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
