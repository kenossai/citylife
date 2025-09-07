<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Navigation tracking route
Route::get('/track-nav', function() {
    $sessionData = session()->all();
    $memberSessionKey = null;
    $memberId = null;

    foreach($sessionData as $key => $value) {
        if (str_starts_with($key, 'login_member_')) {
            $memberSessionKey = $key;
            $memberId = $value;
            break;
        }
    }

    $trackingData = [
        'timestamp' => now()->toISOString(),
        'url' => request()->fullUrl(),
        'referer' => request()->header('referer'),
        'session_id' => session()->getId(),
        'member_session_key' => $memberSessionKey,
        'member_id_from_session' => $memberId,
        'auth_guard_check' => Auth::guard('member')->check(),
        'auth_guard_user_id' => Auth::guard('member')->user()?->id,
        'cookies' => request()->cookies->all(),
        'user_agent' => request()->userAgent()
    ];

    Log::info('Navigation Tracking', $trackingData);

    return response()->json($trackingData);
});
