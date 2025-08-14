<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // If the request is for member guard routes, redirect to member login
            if ($request->is('my-courses*') || $request->is('courses/*/lessons*')) {
                return route('member.login');
            }
            
            // Default fallback
            return route('login');
        }
        
        return null;
    }
}
