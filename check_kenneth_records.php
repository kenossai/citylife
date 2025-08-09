<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Member;
use Illuminate\Support\Facades\DB;

echo "Checking Kenneth's member records...\n\n";

// Find all Kenneth members
$kennethMembers = Member::where(function($query) {
    $query->where('first_name', 'like', '%Kenneth%')
          ->orWhere('last_name', 'like', '%Kenneth%')
          ->orWhere('email', 'like', '%kenossai%');
})->orderBy('id')->get();

echo "Found " . $kennethMembers->count() . " Kenneth-related records:\n\n";

foreach ($kennethMembers as $member) {
    echo "ID: {$member->id}\n";
    echo "Name: {$member->first_name} {$member->last_name}\n";
    echo "Email: '{$member->email}'\n";
    echo "Membership Number: {$member->membership_number}\n";
    echo "Created: {$member->created_at}\n";
    echo "Active: " . ($member->is_active ? 'Yes' : 'No') . "\n";
    echo str_repeat("-", 50) . "\n";
}

// Check for email duplicates specifically
echo "\nChecking for email duplicates:\n";
$emailGroups = Member::whereNotNull('email')
    ->where('email', '!=', '')
    ->get()
    ->groupBy(function($member) {
        return strtolower(trim($member->email));
    });

foreach ($emailGroups as $normalizedEmail => $group) {
    if ($group->count() > 1) {
        echo "DUPLICATE: {$normalizedEmail} ({$group->count()} records)\n";
        foreach ($group as $member) {
            echo "  - ID: {$member->id}, Email: '{$member->email}', Created: {$member->created_at}\n";
        }
        echo "\n";
    }
}

echo "Analysis complete.\n";
