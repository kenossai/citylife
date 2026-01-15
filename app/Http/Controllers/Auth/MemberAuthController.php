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

        // Find the member first to check their status
        $member = Member::where('email', strtolower(trim($credentials['email'])))->first();

        if (!$member) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // Check if email is verified
        if (!$member->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => 'Please verify your email address before logging in. Check your inbox for the verification link.',
            ]);
        }

        // Check if approved by admin
        if (!$member->isApproved()) {
            throw ValidationException::withMessages([
                'email' => 'Your account is pending admin approval. You will be notified via email once approved.',
            ]);
        }

        // Check if account is active
        if (!$member->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Please contact the administrator.',
            ]);
        }

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
        // HONEYPOT TRAP: If website field is filled, it's a bot
        if ($request->filled('website')) {
            Log::warning('Bot registration blocked (honeypot triggered)', [
                'ip' => $request->ip(),
                'email' => $request->email,
                'website' => $request->website,
            ]);

            // Pretend success to fool the bot
            return redirect()->route('member.login')
                ->with('success', 'Registration successful! Please check your email to verify your account.');
        }

        // TIMING ATTACK PROTECTION: Check form submission speed
        $formLoadTime = $request->input('form_load_time');
        if ($formLoadTime) {
            $timeTaken = time() - (int) $formLoadTime;
            if ($timeTaken < 3) {
                Log::warning('Bot registration blocked (too fast)', [
                    'ip' => $request->ip(),
                    'email' => $request->email,
                    'time_taken' => $timeTaken,
                ]);

                return redirect()->back()
                    ->withErrors(['email' => 'Please take your time filling out the form.'])
                    ->withInput();
            }
        }

        // DISPOSABLE EMAIL BLOCKING
        if ($this->isDisposableEmail($request->email)) {
            Log::warning('Bot registration blocked (disposable email)', [
                'ip' => $request->ip(),
                'email' => $request->email,
            ]);

            return redirect()->back()
                ->withErrors(['email' => 'Temporary or disposable email addresses are not allowed. Please use a permanent email address.'])
                ->withInput();
        }

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
            'email_verified_at' => null, // Not verified yet
            'approved_at' => null, // Not approved yet
        ]);

        // Generate and send email verification
        $verificationToken = $member->generateEmailVerificationToken();
        $member->notify(new \App\Notifications\MemberEmailVerification($verificationToken));

        // Add to newsletter subscribers if they consented (but mark as pending verification)
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

        // DO NOT log the user in - they must verify email first

        // Notify admins about new member registration for approval
        $this->notifyAdminsAboutNewMember($member);

        $successMessage = 'Registration successful! Please check your email to verify your account. After verification, an administrator will review and approve your account.';

        return redirect()->route('member.login')
            ->with('success', $successMessage);
    }

    /**
     * Notify admins about new member registration
     */
    private function notifyAdminsAboutNewMember(Member $member)
    {
        // Get all admin users
        $admins = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            // Send notification to admin (you can create a notification for this)
            Log::info('New member registered - pending approval', [
                'member_id' => $member->id,
                'member_email' => $member->email,
                'admin_notified' => $admin->email,
            ]);
        }
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(Request $request, $token)
    {
        $hashedToken = hash('sha256', $token);

        $member = Member::where('email_verification_token', $hashedToken)->first();

        if (!$member) {
            return redirect()->route('member.login')
                ->with('error', 'Invalid verification link.');
        }

        // Mark email as verified
        $member->markEmailAsVerified();

        Log::info('Member email verified', [
            'member_id' => $member->id,
            'email' => $member->email,
        ]);

        return redirect()->route('member.login')
            ->with('success', 'Email verified successfully! Your account is now pending admin approval. You will be notified via email once approved.');
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

    /**
     * Check if email is from a disposable/temporary email provider
     */
    private function isDisposableEmail(string $email): bool
    {
        $domain = strtolower(substr(strrchr($email, "@"), 1));

        $disposableDomains = [
            '10minutemail.com', '10minutemail.net', '10minutemail.org',
            'guerrillamail.com', 'guerrillamail.net', 'guerrillamailblock.com',
            'mailinator.com', 'mailinator.net', 'mailinator2.com',
            'temp-mail.org', 'tempmail.com', 'tempmail.net', 'tempmail.de',
            'throwaway.email', 'trashmail.com', 'trashmail.net',
            'yopmail.com', 'yopmail.net', 'yopmail.fr',
            'fakeinbox.com', 'fakemailgenerator.com',
            'getnada.com', 'getairmail.com',
            'sharklasers.com', 'grr.la',
            'maildrop.cc', 'mailnesia.com', 'mailcatch.com',
            'mintemail.com', 'mytemp.email', 'mytempmail.com',
            'dispostable.com', 'emailondeck.com',
            'burnermail.io', 'temp-mail.io',
            'disposablemail.com', 'spam4.me',
            'harakirimail.com', 'jetable.org',
            'mohmal.com', 'sharklasers.com',
            'throwawaymail.com', 'tmails.net',
        ];

        return in_array($domain, $disposableDomains);
    }

    /**
     * Show registration form with token (from invitation email)
     */
    public function showRegisterWithToken($token)
    {
        // Find the registration interest by token
        $interest = \App\Models\RegistrationInterest::where('token', $token)
            ->where('status', 'approved')
            ->whereNull('registered_at')
            ->first();

        if (!$interest) {
            return redirect()->route('member.register')
                ->withErrors(['token' => 'Invalid or expired registration link. Please submit a new interest.']);
        }

        // Check if token is expired (7 days)
        if ($interest->approved_at && $interest->approved_at->lt(now()->subDays(7))) {
            return redirect()->route('member.register')
                ->withErrors(['token' => 'This registration link has expired. Please submit a new interest.']);
        }

        // Redirect if already logged in
        if (Auth::guard('member')->check()) {
            return redirect()->route('courses.dashboard');
        }

        return view('auth.member.register-with-token', compact('interest', 'token'));
    }

    /**
     * Handle registration with token
     */
    public function registerWithToken(Request $request, $token)
    {
        // Find the registration interest by token
        $interest = \App\Models\RegistrationInterest::where('token', $token)
            ->where('status', 'approved')
            ->whereNull('registered_at')
            ->first();

        if (!$interest) {
            return redirect()->route('member.register')
                ->withErrors(['token' => 'Invalid or expired registration link.']);
        }

        // Check if token is expired (7 days)
        if ($interest->approved_at && $interest->approved_at->lt(now()->subDays(7))) {
            return redirect()->route('member.register')
                ->withErrors(['token' => 'This registration link has expired. Please submit a new interest.']);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members|in:' . $interest->email,
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'gdpr_consent' => 'required|accepted',
            'newsletter' => 'nullable|boolean',
        ], [
            'email.in' => 'The email address must match the invited email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $consentGiven = $request->has('newsletter') && $request->newsletter;

        // Create the member
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
            'gdpr_consent' => true,
            'gdpr_consent_date' => now(),
            'gdpr_consent_ip' => $request->ip(),
            'newsletter_consent' => $consentGiven,
            'newsletter_consent_date' => $consentGiven ? now() : null,
        ]);

        // Mark the registration interest as completed instead of deleting it
        $interest->update([
            'registered_at' => now(),
            'member_id' => $member->id,
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

        // Send email verification notification
        try {
            $verificationToken = $member->generateEmailVerificationToken();
            $member->notify(new \App\Notifications\MemberEmailVerification($verificationToken));

            Log::info('Email verification sent to new member from complete registration', [
                'member_id' => $member->id,
                'email' => $member->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email verification from complete registration', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // DO NOT log the user in - they need to verify email first
        // Auth::guard('member')->login($member);

        // Regenerate session for security
        $request->session()->regenerate();

        // Auto-enroll into Christian Development Course (even though they can't access it yet)
        $this->autoEnrollChristianDevelopmentCourse($member);

        // Clear any intended URL from session
        $request->session()->forget('url.intended');

        // Redirect to login with verification message
        return redirect()->route('member.login')
            ->with('success', 'Account created successfully! Please check your email (' . $member->email . ') to verify your email address. After verification, your account will need admin approval before you can login.');
    }
}
