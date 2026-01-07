<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Livewire\Filament\NotificationIcon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('filament.notification-icon', NotificationIcon::class);

        // Register model observers for social media auto-posting
        \App\Models\Event::observe(\App\Observers\EventObserver::class);
        \App\Models\News::observe(\App\Observers\NewsObserver::class);

        // Track user login timestamps
        Event::listen(Login::class, function (Login $event) {
            $event->user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ]);

            // Log authentication event
            \App\Services\AuditLogger::logAuthentication('login', $event->user);
        });

        Paginator::useBootstrapFive();
    }
}
