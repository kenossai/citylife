<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if user is not authenticated
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Skip if already on the password change page or logout route
        if ($request->is('admin/change-password') ||
            $request->routeIs('filament.admin.auth.logout')) {
            return $next($request);
        }

        // Redirect to password change page if flag is set
        if ($user->force_password_change) {
            return redirect('/admin/change-password');
        }

        return $next($request);
    }
}
