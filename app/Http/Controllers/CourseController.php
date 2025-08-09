<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Member;
use App\Models\CourseEnrollment;
use App\Notifications\CourseRegistrationConfirmation;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Logic to display a list of courses with pagination
        $courses = Course::published()
            ->orderBy('sort_order')
            ->orderBy('start_date')
            ->paginate(6); // Load 6 courses per page

        // If it's an AJAX request for infinite scroll
        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.course.partials.course-cards', compact('courses'))->render(),
                'has_more' => $courses->hasMorePages(),
                'next_page' => $courses->hasMorePages() ? $courses->currentPage() + 1 : null
            ]);
        }

        return view('pages.course.index', compact('courses'));
    }

    public function show($slug)
    {
        // Logic to display a single course
        $course = Course::where('slug', $slug)->firstOrFail();

        // Check if current session has enrollment for this course (for guests)
        $userEnrollment = null;
        $isEnrolled = false;

        // Try to find enrollment by session email (if available from recent registration)
        if (session('user_email')) {
            $member = Member::where('email', session('user_email'))->first();
            if ($member) {
                $userEnrollment = CourseEnrollment::where('course_id', $course->id)
                    ->where('user_id', $member->id)
                    ->first();
                $isEnrolled = $userEnrollment !== null;
            }
        }

        // Get the actual enrollment count (active enrollments only)
        $actualEnrollmentCount = CourseEnrollment::where('course_id', $course->id)
            ->where('status', 'active')
            ->count();

        return view('pages.course.show', compact('course', 'userEnrollment', 'isEnrolled', 'actualEnrollmentCount'));
    }

    public function showRegistrationForm($slug)
    {
        // Logic to show the course registration form
        $course = Course::where('slug', $slug)->firstOrFail();

        // Check if registration is open
        if (!$course->is_registration_open) {
            return redirect()->route('courses.show', $slug)
                ->with('error', 'Registration for this course is currently closed.');
        }

        return view('pages.course.form', compact('course'));
    }

    public function processRegistration(Request $request, $slug)
    {
        // Log the incoming request for debugging
        Log::info('Course registration form submitted', [
            'slug' => $slug,
            'request_data' => $request->except(['_token']), // Log everything except token
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);

        // Find the course
        $course = Course::where('slug', $slug)->firstOrFail();

        // Check if registration is open
        if (!$course->is_registration_open) {
            return redirect()->route('courses.show', $slug)
                ->with('error', 'Registration for this course is currently closed.');
        }

        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'membership_status' => 'required|in:visitor,regular_attendee,member',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'terms_agreement' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Normalize email for consistent matching
            $normalizedEmail = strtolower(trim($validated['email']));
            
            // Check if member already exists by email (case insensitive)
            $member = Member::whereRaw('LOWER(TRIM(email)) = ?', [$normalizedEmail])->first();

            if (!$member) {
                // Generate a unique membership number
                $membershipNumber = 'CL' . date('Y') . str_pad(Member::count() + 1, 4, '0', STR_PAD_LEFT);
                
                // Create new member
                $member = Member::create([
                    'membership_number' => $membershipNumber,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $normalizedEmail, // Store normalized email
                    'phone' => $validated['phone'],
                    'membership_status' => $validated['membership_status'],
                    'emergency_contact_name' => $validated['emergency_contact_name'],
                    'emergency_contact_relationship' => $validated['emergency_contact_relationship'],
                    'first_visit_date' => now(),
                    'is_active' => true,
                ]);

                Log::info('New member created during course registration', [
                    'member_id' => $member->id,
                    'email' => $member->email,
                    'course_id' => $course->id
                ]);
            } else {
                // Update existing member with any new information (only if not empty)
                $updateData = [];

                if (!empty($validated['phone']) && $validated['phone'] !== $member->phone) {
                    $updateData['phone'] = $validated['phone'];
                }

                if (!empty($validated['emergency_contact_name']) && $validated['emergency_contact_name'] !== $member->emergency_contact_name) {
                    $updateData['emergency_contact_name'] = $validated['emergency_contact_name'];
                }

                if (!empty($validated['emergency_contact_relationship']) && $validated['emergency_contact_relationship'] !== $member->emergency_contact_relationship) {
                    $updateData['emergency_contact_relationship'] = $validated['emergency_contact_relationship'];
                }

                // Update name if current member has incomplete name info
                if (empty($member->first_name) || empty($member->last_name)) {
                    if (!empty($validated['first_name'])) {
                        $updateData['first_name'] = $validated['first_name'];
                    }
                    if (!empty($validated['last_name'])) {
                        $updateData['last_name'] = $validated['last_name'];
                    }
                }

                // Ensure member is active
                if (!$member->is_active) {
                    $updateData['is_active'] = true;
                }

                if (!empty($updateData)) {
                    $member->update($updateData);
                    Log::info('Existing member updated during course registration', [
                        'member_id' => $member->id,
                        'email' => $member->email,
                        'updated_fields' => array_keys($updateData),
                        'course_id' => $course->id
                    ]);
                } else {
                    Log::info('Existing member found, no updates needed', [
                        'member_id' => $member->id,
                        'email' => $member->email,
                        'course_id' => $course->id
                    ]);
                }
            }

            // Check if already enrolled
            $existingEnrollment = CourseEnrollment::where('course_id', $course->id)
                ->where('user_id', $member->id)
                ->first();

            if ($existingEnrollment) {
                DB::rollBack();
                return redirect()->route('courses.show', $slug)
                    ->with('error', 'You are already enrolled in this course.');
            }

            // Create course enrollment
            $enrollment = CourseEnrollment::create([
                'course_id' => $course->id,
                'user_id' => $member->id,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            // Update course enrollment count with actual count
            $actualCount = CourseEnrollment::where('course_id', $course->id)
                ->where('status', 'active')
                ->count();
            $course->update(['current_enrollments' => $actualCount]);

            // Send notifications (email and SMS)
            try {
                // Send email notification
                $member->notify(new CourseRegistrationConfirmation($course, $enrollment));

                // Send SMS notification if phone number is available
                if ($member->phone) {
                    $smsService = app(SmsService::class);
                    $notification = new CourseRegistrationConfirmation($course, $enrollment);
                    $message = $notification->getSmsMessage($member);
                    $phone = $smsService->formatPhone($member->phone);
                    $smsService->send($phone, $message);
                }
            } catch (\Exception $e) {
                // Log notification error but don't fail the registration
                Log::error('Notification sending failed during course registration', [
                    'course_id' => $course->id,
                    'member_id' => $member->id,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();

            // Store user email in session for enrollment tracking
            session(['user_email' => $member->email]);

            // Redirect with success message and notification status
            $successMessage = 'Successfully registered for ' . $course->title . '! ';
            $successMessage .= 'A confirmation email has been sent to ' . $member->email;

            if ($member->phone) {
                $successMessage .= ' and an SMS notification has been sent to your phone';
            }

            $successMessage .= '. We will contact you soon with more details.';

            return redirect()->route('courses.show', $slug)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the detailed error for debugging
            Log::error('Course registration failed', [
                'course_id' => $course->id,
                'email' => $validated['email'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred during registration: ' . $e->getMessage());
        }
    }
}

