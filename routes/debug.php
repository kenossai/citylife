<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/debug-session', function (Request $request) {
    return response()->json([
        'session_id' => $request->session()->getId(),
        'session_data' => $request->session()->all(),
        'member_auth_check' => Auth::guard('member')->check(),
        'member_user' => Auth::guard('member')->user(),
        'default_auth_check' => Auth::check(),
        'default_user' => Auth::user(),
        'cookies' => $request->cookies->all(),
        'headers' => [
            'host' => $request->header('host'),
            'user-agent' => $request->userAgent(),
            'cookie' => $request->header('cookie'),
        ],
        'route_name' => $request->route()->getName(),
        'session_config' => [
            'cookie_name' => config('session.cookie'),
            'domain' => config('session.domain'),
            'path' => config('session.path'),
            'secure' => config('session.secure'),
            'http_only' => config('session.http_only'),
            'same_site' => config('session.same_site'),
            'lifetime' => config('session.lifetime'),
        ]
    ]);
})->name('debug.session');

Route::get('/test-session', function (Request $request) {
    // Force set a test value in session
    $request->session()->put('test_value', 'Session is working!');
    $request->session()->put('timestamp', now()->toISOString());

    // Force session save
    $request->session()->save();

    return response()->json([
        'message' => 'Session test value set',
        'session_id' => $request->session()->getId(),
        'test_value' => $request->session()->get('test_value'),
        'timestamp' => $request->session()->get('timestamp'),
    ])->cookie(
        config('session.cookie'),
        $request->session()->getId(),
        config('session.lifetime'),
        '/',
        null,
        false,
        true,
        false,
        'lax'
    );
})->name('test.session');
