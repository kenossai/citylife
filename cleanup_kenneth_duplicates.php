<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\DB;

echo "Cleaning up Kenneth's duplicate records...\n\n";

// Get Kenneth's records
$kenneth42 = Member::find(42); // kenossai1@gmail.com - original
$kenneth61 = Member::find(61); // june@gmail.com
$kenneth62 = Member::find(62); // kenossai@gmail.com - duplicate

if (!$kenneth42 || !$kenneth61 || !$kenneth62) {
    echo "Some Kenneth records not found. Exiting...\n";
    exit(1);
}

echo "Found Kenneth's records:\n";
echo "ID 42: {$kenneth42->email}\n";
echo "ID 61: {$kenneth61->email}\n";
echo "ID 62: {$kenneth62->email}\n\n";

try {
    DB::beginTransaction();

    // Move any enrollments from duplicate records to the original
    $enrollments61 = CourseEnrollment::where('user_id', 61)->get();
    $enrollments62 = CourseEnrollment::where('user_id', 62)->get();

    echo "Moving enrollments to main record (ID 42):\n";

    foreach ($enrollments61 as $enrollment) {
        // Check if enrollment already exists for the main record
        $existing = CourseEnrollment::where('course_id', $enrollment->course_id)
            ->where('user_id', 42)
            ->first();

        if (!$existing) {
            $enrollment->update(['user_id' => 42]);
            echo "  - Moved enrollment {$enrollment->id} from ID 61 to ID 42\n";
        } else {
            echo "  - Enrollment already exists for ID 42, deleting duplicate from ID 61\n";
            $enrollment->delete();
        }
    }

    foreach ($enrollments62 as $enrollment) {
        // Check if enrollment already exists for the main record
        $existing = CourseEnrollment::where('course_id', $enrollment->course_id)
            ->where('user_id', 42)
            ->first();

        if (!$existing) {
            $enrollment->update(['user_id' => 42]);
            echo "  - Moved enrollment {$enrollment->id} from ID 62 to ID 42\n";
        } else {
            echo "  - Enrollment already exists for ID 42, deleting duplicate from ID 62\n";
            $enrollment->delete();
        }
    }

    // Update the main record with the most recent email if needed
    echo "\nUpdating main record (ID 42):\n";
    if ($kenneth42->email === 'kenossai1@gmail.com') {
        // Keep the current email for now, but we could update to kenossai@gmail.com if preferred
        echo "  - Keeping original email: {$kenneth42->email}\n";
    }

    // Delete the duplicate records
    echo "\nDeleting duplicate records:\n";
    $kenneth61->delete();
    echo "  - Deleted ID 61 ({$kenneth61->email})\n";

    $kenneth62->delete();
    echo "  - Deleted ID 62 ({$kenneth62->email})\n";

    DB::commit();
    echo "\n✅ Cleanup completed successfully!\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error during cleanup: " . $e->getMessage() . "\n";
}

echo "\nFinal Kenneth record:\n";
$finalKenneth = Member::find(42);
if ($finalKenneth) {
    echo "ID: {$finalKenneth->id}\n";
    echo "Name: {$finalKenneth->first_name} {$finalKenneth->last_name}\n";
    echo "Email: {$finalKenneth->email}\n";
    echo "Enrollments: " . CourseEnrollment::where('user_id', 42)->count() . "\n";
}

echo "\nCleanup complete.\n";
