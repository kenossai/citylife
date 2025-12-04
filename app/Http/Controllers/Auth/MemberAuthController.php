<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\NewsletterSubscriber;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Notifications\CourseRegistrationConfirmation;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

        // Debug logging
        Log::info('Login attempt for: ' . $credentials['email']);

        if (Auth::guard('member')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::guard('member')->user();
            Log::info('Login successful for: ' . $user->email);
            Log::info('Session ID after login: ' . $request->session()->getId());
            Log::info('Auth check after login: ' . (Auth::guard('member')->check() ? 'true' : 'false'));

            // Check for intended course from session
            $intendedCourse = session('intended_course');
            if ($intendedCourse) {
                session()->forget('intended_course');
                return redirect()->route('courses.register.form', $intendedCourse)
                    ->with('success', 'Welcome back, ' . $user->first_name . '! You can now register for the course.');
            }

            return redirect()->intended(route('courses.dashboard'))
                ->with('success', 'Welcome back, ' . $user->first_name . '!');
        }

        Log::info('Login failed for: ' . $credentials['email']);
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
            'gdpr_consent' => 'required|accepted',
            'newsletter' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $consentGiven = $request->has('newsletter') && $request->newsletter;

        $member = Member::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'membership_status' => 'regular_attendee',
            'is_active' => true,
            'receives_newsletter' => $consentGiven,
            'receives_sms' => false,
            'gdpr_consent' => true, // Required for registration
            'gdpr_consent_date' => now(),
            'gdpr_consent_ip' => $request->ip(),
            'newsletter_consent' => $consentGiven,
            'newsletter_consent_date' => $consentGiven ? now() : null,
        ]);

        // Add to newsletter subscribers if they consented
        if ($consentGiven) {
            NewsletterSubscriber::subscribe($request->email, [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'source' => 'member_registration',
                'gdpr_consent' => true,
                'gdpr_consent_date' => now(),
                'gdpr_consent_ip' => $request->ip(),
            ]);
        }

        // Log the user in
        Auth::guard('member')->login($member);

        // Regenerate session for security
        $request->session()->regenerate();

        // Auto-enroll the new member into the Christian Development Course
        $this->autoEnrollChristianDevelopmentCourse($member);

        $successMessage = 'Registration successful! Welcome to City Life Church.';
        if ($consentGiven) {
            $successMessage .= ' You have been subscribed to our newsletter.';
        }

        return redirect()->intended(route('courses.dashboard'))
            ->with('success', $successMessage);
    }

    /**
     * Auto-enroll new member into Christian Development Course
     */
    private function autoEnrollChristianDevelopmentCourse(Member $member)
    {
        try {
            DB::beginTransaction();

            // Find the Christian Development Course by slug or title
            $course = Course::where('slug', 'christian-development')
                ->orWhere('title', 'LIKE', '%Christian Development%')
                ->first();

            if (!$course) {
                Log::info('Christian Development course not found. Skipping auto-enrollment.', [
                    'member_id' => $member->id,
                    'member_email' => $member->email,
                ]);
                DB::rollBack();
                return;
            }

            // Check if already enrolled
            $existingEnrollment = CourseEnrollment::where('course_id', $course->id)
                ->where('user_id', $member->id)
                ->first();

            if ($existingEnrollment) {
                Log::info('Member already enrolled in Christian Development course.', [
                    'member_id' => $member->id,
                    'course_id' => $course->id,
                ]);
                DB::rollBack();
                return;
            }

            // Create enrollment
            $enrollment = CourseEnrollment::create([
                'course_id' => $course->id,
                'user_id' => $member->id,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            // Update course enrollment count
            $actualCount = CourseEnrollment::where('course_id', $course->id)
                ->where('status', 'active')
                ->count();
            $course->update(['current_enrollments' => $actualCount]);

            Log::info('Member auto-enrolled in Christian Development course.', [
                'member_id' => $member->id,
                'course_id' => $course->id,
                'enrollment_id' => $enrollment->id,
            ]);

            // Send notifications
            try {
                $member->notify(new CourseRegistrationConfirmation($course, $enrollment));

                // Send SMS if phone number is available
                if ($member->phone) {
                    $smsService = app(SmsService::class);
                    $notification = new CourseRegistrationConfirmation($course, $enrollment);
                    $message = $notification->getSmsMessage($member);
                    $phone = $smsService->formatPhone($member->phone);
                    $smsService->send($phone, $message);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send auto-enrollment notifications.', [
                    'error' => $e->getMessage(),
                    'member_id' => $member->id,
                    'course_id' => $course->id,
                ]);
                // Don't fail the enrollment if notification fails
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Auto-enrollment in Christian Development course failed.', [
                'error' => $e->getMessage(),
                'member_id' => $member->id,
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't throw - we don't want to break registration if auto-enrollment fails
        }
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
