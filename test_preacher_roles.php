<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DepRole;
use App\Models\PreacherDepartmentMember;

echo "=== Preacher Department Analysis ===\n\n";

echo "Available Preacher Roles in DepRole:\n";
$preacherRoles = DepRole::forDepartment('preacher')->get();
foreach ($preacherRoles as $role) {
    echo "  - {$role->name}\n";
}

echo "\nPreacher Department Members:\n";
$preacherMembers = PreacherDepartmentMember::with('member')->get();
foreach ($preacherMembers as $member) {
    echo "  - {$member->member->first_name} {$member->member->last_name} (Role: {$member->role})\n";
}

echo "\nRole Matching Analysis:\n";
foreach ($preacherMembers as $member) {
    $hasMatchingRole = $preacherRoles->where('name', $member->role)->count() > 0;
    echo "  - {$member->member->first_name} {$member->member->last_name}: ";
    echo $hasMatchingRole ? "✅ Has matching role" : "❌ No matching role in DepRole table";
    echo " (Member role: '{$member->role}')\n";
}
