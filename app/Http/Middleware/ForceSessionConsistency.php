<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ForceSessionConsistency
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to restore session from database if cookies are missing
        $this->restoreSessionIfNeeded($request);

        $response = $next($request);

        // Ensure session is properly saved and cookies are set
        $this->ensureSessionPersistence($request, $response);

        return $response;
    }

    private function restoreSessionIfNeeded(Request $request)
    {
        // If no session cookie but we have session data in database, try to restore it
        if (!$request->cookies->has(config('session.cookie')) && $request->hasSession()) {
            $sessionId = $request->session()->getId();

            // Check if session exists in database
            $sessionData = DB::table('sessions')
                ->where('id', $sessionId)
                ->first();

            if ($sessionData) {
                // Session exists, but cookie might be missing
                Log::info('Session exists in database but cookie missing', [
                    'session_id' => $sessionId,
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }
    }

    private function ensureSessionPersistence(Request $request, Response $response)
    {
        if ($request->hasSession()) {
            $sessionName = config('session.cookie');
            $sessionId = $request->session()->getId();

            // Force save session data
            $request->session()->save();

            // Create multiple cookie formats to ensure compatibility
            $cookies = [
                // Standard Laravel cookie
                cookie($sessionName, $sessionId, config('session.lifetime'), '/', null, false, true, false, 'lax'),
                // Backup cookie with different settings
                cookie($sessionName . '_backup', $sessionId, config('session.lifetime'), '/', null, false, false, false, 'none'),
            ];

            foreach ($cookies as $cookie) {
                $response->headers->setCookie($cookie);
            }

            // Also set via header for maximum compatibility
            $cookieHeader = sprintf(
                '%s=%s; Path=/; HttpOnly; SameSite=lax; Max-Age=%d',
                $sessionName,
                $sessionId,
                config('session.lifetime') * 60
            );

            $response->headers->set('Set-Cookie', $cookieHeader, false);

            // Debug logging
            if (config('app.debug')) {
                Log::info('Setting session cookies', [
                    'session_id' => $sessionId,
                    'cookie_name' => $sessionName,
                    'member_auth' => Auth::guard('member')->check(),
                ]);
            }
        }
    }
}
