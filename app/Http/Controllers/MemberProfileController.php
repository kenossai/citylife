<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MemberProfileController extends Controller
{
    /**
     * Show member dashboard/profile
     */
    public function index()
    {
        $member = auth()->guard('member')->user();

        return view('member.profile.index', compact('member'));
    }

    /**
     * Update member profile
     */
    public function update(Request $request)
    {
        $member = auth()->guard('member')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
        ]);

        $member->update($validated);

        return redirect()->route('member.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $member = auth()->guard('member')->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $member->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $member->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('member.profile')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Update communication preferences
     */
    public function updatePreferences(Request $request)
    {
        $member = auth()->guard('member')->user();

        $validated = $request->validate([
            'receive_newsletter' => 'boolean',
            'receive_event_updates' => 'boolean',
            'receive_course_updates' => 'boolean',
            'receive_sms' => 'boolean',
        ]);

        $member->update($validated);

        return redirect()->route('member.profile')
            ->with('success', 'Communication preferences updated successfully!');
    }
}
