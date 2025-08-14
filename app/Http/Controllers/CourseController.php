<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Member;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use App\Notifications\CourseRegistrationConfirmation;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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

        // Check if current user has enrollment for this course
        $userEnrollment = null;
        $isEnrolled = false;

        // Check if user is authenticated via member guard
        if (Auth::guard('member')->check()) {
            $member = Auth::guard('member')->user();
            $userEnrollment = CourseEnrollment::where('course_id', $course->id)
                ->where('user_id', $member->id)
                ->first();
            $isEnrolled = $userEnrollment !== null;
        } else {
            // Fallback: Try to find enrollment by session email (if available from recent registration)
            if (session('user_email')) {
                $member = Member::where('email', session('user_email'))->first();
                if ($member) {
                    $userEnrollment = CourseEnrollment::where('course_id', $course->id)
                        ->where('user_id', $member->id)
                        ->first();
                    $isEnrolled = $userEnrollment !== null;
                }
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
        // Check if user is authenticated
        if (!Auth::guard('member')->check()) {
            return redirect()->route('member.login')
                ->with('info', 'Please login or register to enroll in courses.')
                ->with('intended_course', $slug);
        }

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

    /**
     * Display lessons for an enrolled user
     */
    public function lessons($slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        // Check if user is enrolled
        $userEnrollment = $this->getUserEnrollment($course);

        if (!$userEnrollment) {
            return redirect()->route('courses.show', $slug)
                ->with('error', 'You must be enrolled in this course to access lessons.');
        }

        $lessons = $course->lessons()
            ->published()
            ->available()
            ->orderBy('lesson_number')
            ->get();

        // Get progress for each lesson
        $progress = [];
        foreach ($lessons as $lesson) {
            $progress[$lesson->id] = LessonProgress::where('course_enrollment_id', $userEnrollment->id)
                ->where('course_lesson_id', $lesson->id)
                ->first();
        }

        return view('pages.course.lessons', compact('course', 'lessons', 'userEnrollment', 'progress'));
    }

    /**
     * Show a specific lesson
     */
    public function showLesson($courseSlug, $lessonSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $lesson = $course->lessons()->where('slug', $lessonSlug)->firstOrFail();

        // Check if user is enrolled
        $userEnrollment = $this->getUserEnrollment($course);

        if (!$userEnrollment) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'You must be enrolled in this course to access lessons.');
        }

        // Get or create lesson progress
        $progress = LessonProgress::firstOrCreate([
            'course_enrollment_id' => $userEnrollment->id,
            'course_lesson_id' => $lesson->id,
        ]);

        // Mark as started if not already
        if ($progress->status === 'not_started') {
            $progress->markAsStarted();
        }

        return view('pages.course.lesson', compact('course', 'lesson', 'userEnrollment', 'progress'));
    }

    /**
     * Show quiz for a lesson
     */
    public function showQuiz($courseSlug, $lessonSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $lesson = $course->lessons()->where('slug', $lessonSlug)->firstOrFail();

        // Check if user is enrolled
        $userEnrollment = $this->getUserEnrollment($course);

        if (!$userEnrollment) {
            return redirect()->route('courses.show', $courseSlug)
                ->with('error', 'You must be enrolled in this course to access quizzes.');
        }

        // Check if lesson has quiz questions
        if (!$lesson->quiz_questions) {
            return redirect()->route('courses.lesson.show', [$courseSlug, $lessonSlug])
                ->with('error', 'This lesson does not have a quiz.');
        }

        // Get or create lesson progress
        $progress = LessonProgress::firstOrCreate([
            'course_enrollment_id' => $userEnrollment->id,
            'course_lesson_id' => $lesson->id,
        ]);

        // Mark as started if not already
        if ($progress->status === 'not_started') {
            $progress->markAsStarted();
        }

        $quizQuestions = json_decode($lesson->quiz_questions, true);

        return view('pages.course.quiz', compact('course', 'lesson', 'userEnrollment', 'progress', 'quizQuestions'));
    }

    /**
     * Submit quiz answers
     */
    public function submitQuiz(Request $request, $courseSlug, $lessonSlug)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $lesson = $course->lessons()->where('slug', $lessonSlug)->firstOrFail();

        // Check if user is enrolled
        $userEnrollment = $this->getUserEnrollment($course);

        if (!$userEnrollment) {
            return response()->json(['error' => 'Not enrolled in course'], 403);
        }

        // Get lesson progress
        $progress = LessonProgress::where('course_enrollment_id', $userEnrollment->id)
            ->where('course_lesson_id', $lesson->id)
            ->first();

        if (!$progress) {
            return response()->json(['error' => 'Lesson progress not found'], 404);
        }

        $quizQuestions = json_decode($lesson->quiz_questions, true);
        $answers = $request->input('answers', []);

        // Calculate score
        $totalQuestions = count($quizQuestions);
        $correctAnswers = 0;

        foreach ($quizQuestions as $index => $question) {
            if ($question['type'] === 'multiple_choice' && isset($question['correct_answer'])) {
                $userAnswer = $answers[$index] ?? '';
                if ($userAnswer === $question['correct_answer']) {
                    $correctAnswers++;
                }
            }
        }

        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        // Update progress
        $progress->increment('attempts');
        $progress->update([
            'quiz_score' => $score,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update enrollment progress based on attendance, not quiz completion
        $userEnrollment->updateProgressFromAttendance();

        return response()->json([
            'success' => true,
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'passed' => $score >= 70, // Assuming 70% is passing
            'redirect_url' => route('courses.lessons', $courseSlug)
        ]);
    }

    /**
     * User dashboard to view their courses
     */
    public function dashboard(Request $request)
    {
        // Check if user is authenticated via member guard
        if (Auth::guard('member')->check()) {
            $member = Auth::guard('member')->user();
        } else {
            // Fallback to old email-based system for backward compatibility
            $email = $request->input('email') ?: session('user_email');

            if (!$email) {
                return redirect()->route('member.login')
                    ->with('info', 'Please login to access your course dashboard.');
            }

            $member = Member::where('email', $email)->first();

            if (!$member) {
                return redirect()->route('member.login')
                    ->with('error', 'Please login to access your courses.');
            }

            // Store email in session for backward compatibility
            session(['user_email' => $email]);
        }

        $enrollments = CourseEnrollment::where('user_id', $member->id)
            ->with(['course.lessons', 'progress'])
            ->orderBy('enrollment_date', 'desc')
            ->get();

        return view('pages.course.dashboard', compact('member', 'enrollments'));
    }

    /**
     * Helper method to get user enrollment
     */
    private function getUserEnrollment($course)
    {
        // First check if user is authenticated via member guard
        if (Auth::guard('member')->check()) {
            $member = Auth::guard('member')->user();
        } else {
            // Fallback to session-based system
            $email = session('user_email');

            if (!$email) {
                return null;
            }

            $member = Member::where('email', $email)->first();

            if (!$member) {
                return null;
            }
        }

        return CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $member->id)
            ->first();
    }

    public function downloadCertificate($enrollment_id)
    {
        $enrollment = CourseEnrollment::findOrFail($enrollment_id);

        // Check if certificate is issued and file exists
        if (!$enrollment->certificate_issued || !$enrollment->certificate_file_path) {
            abort(404, 'Certificate not available');
        }

        // Check if the current user is authorized to download this certificate
        if (Auth::guard('member')->check()) {
            $member = Auth::guard('member')->user();
        } else {
            // Fallback to session-based system
            $email = session('user_email');
            if (!$email) {
                abort(403, 'Unauthorized');
            }

            $member = Member::where('email', $email)->first();
            if (!$member) {
                abort(403, 'Unauthorized');
            }
        }

        if ($member->id !== $enrollment->user_id) {
            abort(403, 'Unauthorized');
        }

        $filePath = storage_path('app/public/' . $enrollment->certificate_file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Certificate file not found');
        }

        $fileName = 'Certificate_' . $enrollment->course->title . '_' . $member->first_name . '_' . $member->last_name . '.pdf';

        return response()->download($filePath, $fileName);
    }
}

