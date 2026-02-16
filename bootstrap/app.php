<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\EnsureConsistentSession::class,
        ]);

        // Register middleware aliases
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'member.auth' => \App\Http\Middleware\MemberAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token mismatch / session expired errors
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response, \Throwable $exception, \Illuminate\Http\Request $request) {
            // Check if it's a 419 error (CSRF token expired/session expired)
            if ($response->getStatusCode() === 419) {
                // If it's an admin route and user is authenticated, redirect to lock screen
                if ($request->is('admin/*') && auth()->check()) {
                    session(['lock_screen' => true]);
                    return redirect()->route('filament.admin.pages.lock-screen');
                }

                // Otherwise, show the default 419 error page
                return $response;
            }

            return $response;
        });
    })->create();
