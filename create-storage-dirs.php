<?php

/**
 * Create required storage directories for Laravel Cloud deployment
 * This runs BEFORE composer autoload to ensure directories exist
 */

$directories = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/framework/testing',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
        echo "Created directory: $dir\n";
    }
}

echo "✅ All storage directories ready\n";
