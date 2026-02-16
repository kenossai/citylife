<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for lock screen and login pages
        if ($request->is('admin/lock-screen') || $request->is('admin/login') || $request->is('admin/logout')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (Auth::check()) {
            // Check if lock screen is set
            if (session()->has('lock_screen') && !$request->is('admin/lock-screen')) {
                return redirect('/admin/lock-screen');
            }

            $lastActivity = session('last_activity_time');
            $timeout = config('session.lifetime') * 60; // Convert to seconds

            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                // Session has timed out - lock the screen instead of logging out
                if ($request->is('admin/*')) {
                    session(['lock_screen' => true]);
                    session(['locked_user_id' => Auth::id()]);
                    session(['locked_user_email' => Auth::user()->email]);

                    // Handle AJAX requests differently
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'message' => 'Session timeout.',
                            'redirect' => '/admin/lock-screen'
                        ], 423);
                    }

                    return redirect('/admin/lock-screen');
                }
            }

            // Update last activity time
            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
