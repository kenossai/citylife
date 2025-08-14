<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MemberAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        // Redirect if already logged in
        if (Auth::guard('member')->check()) {
            return redirect()->route('courses.dashboard');
        }

        return view('auth.member.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('member')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Store user email in session for backward compatibility
            session(['user_email' => Auth::guard('member')->user()->email]);

            // Check for intended course from session
            $intendedCourse = session('intended_course');
            if ($intendedCourse) {
                session()->forget('intended_course');
                return redirect()->route('courses.register.form', $intendedCourse)
                    ->with('success', 'Welcome back, ' . Auth::guard('member')->user()->first_name . '! You can now register for the course.');
            }

            return redirect()->intended(route('courses.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('member')->user()->first_name . '!');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        // Redirect if already logged in
        if (Auth::guard('member')->check()) {
            return redirect()->route('courses.dashboard');
        }

        return view('auth.member.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $member = Member::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'membership_status' => 'member',
            'is_active' => true,
            'receives_newsletter' => true,
            'receives_sms' => false,
        ]);

        // Log the user in
        Auth::guard('member')->login($member);

        // Store user email in session for backward compatibility
        session(['user_email' => $member->email]);

        return redirect()->route('courses.dashboard')
            ->with('success', 'Registration successful! Welcome to City Life Church.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('member')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('member.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
