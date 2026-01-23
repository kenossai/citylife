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
            // Try to restore authentication from session
            $restored = $this->tryRestoreAuthentication();

            if (!$restored) {
                // Store intended URL for redirect after login
                session(['url.intended' => $request->fullUrl()]);

                // Handle AJAX requests differently
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'error' => 'Please login to continue.',
                        'redirect' => route('member.login')
                    ], 401);
                }

                return redirect()
                    ->route('member.login')
                    ->with('warning', 'Please login to continue.');
            }
        }

        $member = Auth::guard('member')->user();

        // Check if email is verified
        if (!$member->hasVerifiedEmail()) {
            Auth::guard('member')->logout();

            // Handle AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Please verify your email address before accessing the system. Check your inbox for the verification link.',
                    'redirect' => route('member.login')
                ], 403);
            }

            return redirect()
                ->route('member.login')
                ->with('error', 'Please verify your email address before accessing the system. Check your inbox for the verification link.');
        }

        // Check if admin has approved the member
        if (!$member->isApproved()) {
            Auth::guard('member')->logout();

            // Handle AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Your account is pending admin approval. You will be notified via email once approved.',
                    'redirect' => route('member.login')
                ], 403);
            }

            return redirect()
                ->route('member.login')
                ->with('error', 'Your account is pending admin approval. You will be notified via email once approved.');
        }

        // Check if member is active
        if (!$member->is_active) {
            Auth::guard('member')->logout();

            // Handle AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Your account has been deactivated. Please contact the administrator.',
                    'redirect' => route('member.login')
                ], 403);
            }

            return redirect()
                ->route('member.login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        return $next($request);
    }

    /**
     * Try to restore authentication from session data
     */
    private function tryRestoreAuthentication(): bool
    {
        $sessionData = session()->all();
        $memberId = null;

        // Find any session key that starts with 'login_member_'
        foreach ($sessionData as $key => $value) {
            if (str_starts_with($key, 'login_member_')) {
                $memberId = $value;
                break;
            }
        }

        if ($memberId) {
            $member = \App\Models\Member::find($memberId);
            if ($member && $member->is_active) {
                // Re-authenticate the user in the guard
                Auth::guard('member')->login($member, true);
                \Log::info('MemberAuthenticated middleware: Re-authenticated member from session', [
                    'email' => $member->email,
                    'member_id' => $memberId
                ]);
                return true;
            }
        }

        return false;
    }
}
