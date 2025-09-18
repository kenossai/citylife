<?php

// Add these optimizations to your AppServiceProvider boot() method

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

public function boot(): void
{
    // Prevent lazy loading in development to catch N+1 queries
    if (app()->environment('local')) {
        Model::preventLazyLoading();
    }

    // Enable query logging for performance monitoring (development only)
    if (app()->environment('local') && app()->isDownForMaintenance() === false) {
        DB::listen(function ($query) {
            if ($query->time > 1000) { // Log slow queries (>1 second)
                logger()->warning('Slow Query Detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms'
                ]);
            }
        });
    }

    // Optimize model caching
    Model::preventAccessingMissingAttributes();
    Model::preventSilentlyDiscardingAttributes();
}
