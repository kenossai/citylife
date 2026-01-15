<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MemberAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if member is authenticated
        if (!Auth::guard('member')->check()) {
            // Store intended URL for redirect after login
            session(['url.intended' => $request->fullUrl()]);

            return redirect()
                ->route('member.login')
                ->with('warning', 'Please login to continue.');
        }

        $member = Auth::guard('member')->user();

        // Check if email is verified
        if (!$member->hasVerifiedEmail()) {
            Auth::guard('member')->logout();

            return redirect()
                ->route('member.login')
                ->with('error', 'Please verify your email address before accessing the system. Check your inbox for the verification link.');
        }

        // Check if admin has approved the member
        if (!$member->isApproved()) {
            Auth::guard('member')->logout();

            return redirect()
                ->route('member.login')
                ->with('error', 'Your account is pending admin approval. You will be notified via email once approved.');
        }

        // Check if member is active
        if (!$member->is_active) {
            Auth::guard('member')->logout();

            return redirect()
                ->route('member.login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        return $next($request);
    }
}
