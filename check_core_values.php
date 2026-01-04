<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CoreValue;
use App\Models\AboutPage;

echo "Checking Core Values...\n\n";

try {
    $coreValues = CoreValue::all();

    echo "Total Core Values: " . $coreValues->count() . "\n\n";

    foreach ($coreValues as $value) {
        echo "ID: {$value->id}\n";
        echo "Title: " . ($value->title ?? 'NULL') . "\n";
        echo "Slug: " . ($value->slug ?? 'NULL') . "\n";
        echo "Description: " . (strlen($value->description ?? '') > 0 ? 'SET' : 'NULL') . "\n";
        echo "About Page ID: " . ($value->about_page_id ?? 'NULL') . "\n";
        echo "Featured Image: " . ($value->featured_image ?? 'NULL') . "\n";

        // Test excerpt accessor
        try {
            $excerpt = $value->excerpt;
            echo "Excerpt: OK (length: " . strlen($excerpt) . ")\n";
        } catch (\Exception $e) {
            echo "Excerpt: ERROR - " . $e->getMessage() . "\n";
        }

        // Test relationship
        try {
            $aboutPage = $value->aboutPage;
            echo "About Page: " . ($aboutPage ? $aboutPage->title : 'NULL') . "\n";
        } catch (\Exception $e) {
            echo "About Page: ERROR - " . $e->getMessage() . "\n";
        }

        echo str_repeat("-", 50) . "\n";
    }

    // Check for core values with missing slugs
    $missingSlug = CoreValue::whereNull('slug')->count();
    echo "\nCore values with missing slug: {$missingSlug}\n";

    // Check for core values with empty description
    $emptyDesc = CoreValue::where('description', '')->orWhereNull('description')->count();
    echo "Core values with empty description: {$emptyDesc}\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
