<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Course Enrollments table columns:\n";

$columns = DB::select('DESCRIBE course_enrollments');

foreach($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\nDone.\n";
