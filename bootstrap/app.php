<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        then: function () {
            Route::middleware('web')->group(base_path('routes/debug.php'));
            Route::middleware('web')->group(base_path('routes/test-auth.php'));
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Temporarily disabled - causing 500 errors on homepage
        // $middleware->web(append: [
        //     \App\Http\Middleware\EnsureConsistentSession::class,
        // ]);

        // Ensure session is always available for web routes
        $middleware->web(prepend: [
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        // Register permission middleware alias
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
