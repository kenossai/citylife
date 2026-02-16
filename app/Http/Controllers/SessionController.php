<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Update session activity time
     */
    public function ping(Request $request)
    {
        if (Auth::check()) {
            session(['last_activity_time' => time()]);
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'unauthenticated'], 401);
    }

    /**
     * Lock the screen
     */
    public function lock(Request $request)
    {
        if (Auth::check()) {
            session(['lock_screen' => true]);
            session(['locked_user_id' => Auth::id()]);
            session(['locked_user_email' => Auth::user()->email]);
            return response()->json(['status' => 'locked']);
        }

        return response()->json(['status' => 'unauthenticated'], 401);
    }

    /**
     * Check session status
     */
    public function checkSession(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'expired'], 419);
        }

        if (session()->has('lock_screen')) {
            return response()->json(['status' => 'locked'], 423);
        }

        return response()->json(['status' => 'active']);
    }
}
