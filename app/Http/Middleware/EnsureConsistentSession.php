<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureConsistentSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only process if session exists and this is not an AJAX request
        if ($request->hasSession() && !$request->ajax()) {
            $session = $request->session();

            // Check if we have member login data but Auth::guard('member')->check() returns false
            $memberSessionKeys = collect($session->all())->keys()->filter(function($key) {
                return str_starts_with($key, 'login_member_');
            });

            $memberId = null;
            foreach ($memberSessionKeys as $key) {
                $memberId = $session->get($key);
                if ($memberId) {
                    break;
                }
            }

            // Only re-authenticate if we have session data but no auth
            if ($memberId && !Auth::guard('member')->check()) {
                try {
                    $member = \App\Models\Member::find($memberId);
                    if ($member && $member->is_active) {
                        Auth::guard('member')->login($member, true);
                    }
                } catch (\Exception $e) {
                    // Silently fail - don't disrupt the request
                }
            }
        }

        return $next($request);
    }
}
