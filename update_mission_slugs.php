<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Mission;
use Illuminate\Support\Str;

$missions = Mission::all();

foreach ($missions as $mission) {
    if (empty($mission->slug)) {
        $mission->slug = Str::slug($mission->title);
        $mission->save();
        echo "Updated mission: {$mission->title} -> {$mission->slug}\n";
    } else {
        echo "Mission already has slug: {$mission->title} -> {$mission->slug}\n";
    }
}

echo "All missions updated!\n";
