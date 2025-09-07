<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SessionAuthProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share member authentication state with all views
        View::composer('*', function ($view) {
            $memberAuth = [
                'check' => Auth::guard('member')->check(),
                'user' => Auth::guard('member')->user(),
                'id' => Auth::guard('member')->id(),
            ];

            $view->with('memberAuth', $memberAuth);
        });

        // Set default guard based on route
        $this->app['router']->matched(function ($event) {
            $route = $event->route;

            // If accessing member routes, ensure member guard is active
            if (str_contains($route->getName() ?? '', 'member.') ||
                str_contains($route->getName() ?? '', 'courses.')) {
                config(['auth.defaults.guard' => 'member']);
            }
        });
    }
}
