<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GivingController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\TeachingSeriesController;
use App\Http\Controllers\CityLifeTalkTimeController;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\MissionController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-citylife', [AboutController::class, 'index'])->name('about');
Route::get('/about/core-values/{slug}', [AboutController::class, 'showCoreValue'])->name('about.core-value');


// Route for the course controller
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{slug}/register', [CourseController::class, 'showRegistrationForm'])->name('courses.register.form');
Route::post('/courses/{slug}/register', [CourseController::class, 'processRegistration'])->name('courses.register');

// Certificate download route (public access)
Route::get('/certificate/{enrollment_id}/download', [CourseController::class, 'downloadCertificate'])->name('certificate.download');

// Route for the events controller
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Route for news/announcements
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Route for ministries
Route::get('/ministries', [MinistryController::class, 'index'])->name('ministries.index');
Route::get('/ministries/{slug}', [MinistryController::class, 'show'])->name('ministries.show');
Route::get('/ministries/{slug}/contact', [MinistryController::class, 'contact'])->name('ministries.contact');
Route::post('/ministries/{slug}/contact', [MinistryController::class, 'submitContact'])->name('ministries.contact.submit');

// Route for missions
Route::get('/missions', [MissionController::class, 'index'])->name('missions.index');
Route::get('/missions/home', [MissionController::class, 'home'])->name('missions.home');
Route::get('/missions/abroad', [MissionController::class, 'abroad'])->name('missions.abroad');
Route::get('/missions/{mission}', [MissionController::class, 'show'])->name('missions.show');

// Route for the contact controller
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Route for giving
Route::get('/giving', [GivingController::class, 'index'])->name('giving.index');
Route::post('/giving/gift-aid', [GivingController::class, 'submitGiftAid'])->name('giving.gift-aid');

Route::get('/team/pastoral', [TeamController::class, 'pastoral'])->name('team.pastoral');
Route::get('/team/leadership', [TeamController::class, 'leadership'])->name('team.leadership');
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
Route::get('/team/{slug}', [TeamController::class, 'show'])->name('team.member');

// Fallback login route (redirects to member login)
Route::get('/login', function() {
    return redirect()->route('member.login');
})->name('login');

// Member Authentication Routes
Route::prefix('member')->name('member.')->group(function () {
    Route::get('login', [App\Http\Controllers\Auth\MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\MemberAuthController::class, 'login'])->name('login.submit');
    Route::get('register', [App\Http\Controllers\Auth\MemberAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\MemberAuthController::class, 'register'])->name('register.submit');
    Route::post('logout', [App\Http\Controllers\Auth\MemberAuthController::class, 'logout'])->name('logout');
});

// Protected Member Routes (using internal auth logic instead of middleware)
Route::get('/my-courses', [CourseController::class, 'dashboard'])->name('courses.dashboard');
Route::get('/courses/{slug}/lessons', [CourseController::class, 'lessons'])->name('courses.lessons');
Route::get('/courses/{courseSlug}/lessons/{lessonSlug}', [CourseController::class, 'showLesson'])->name('courses.lesson.show');
Route::get('/courses/{courseSlug}/lessons/{lessonSlug}/quiz', [CourseController::class, 'showQuiz'])->name('courses.lesson.quiz');
Route::post('/courses/{courseSlug}/lessons/{lessonSlug}/complete', [CourseController::class, 'completeLesson'])->name('courses.lesson.complete');
Route::post('/courses/{courseSlug}/lessons/{lessonSlug}/quiz', [CourseController::class, 'submitQuiz'])->name('courses.lesson.quiz.submit');

// Debug route to check authentication
Route::get('/auth-debug', function() {
    // Find member session key dynamically
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

    return response()->json([
        'member_guard_check' => \Illuminate\Support\Facades\Auth::guard('member')->check(),
        'member_guard_user' => \Illuminate\Support\Facades\Auth::guard('member')->user()?->email,
        'session_user_email' => session('user_email'),
        'session_member_id' => $memberId,
        'session_key_found' => $memberSessionKey,
        'session_id' => session()->getId(),
        'all_session_data' => session()->all(),
    ]);
});

// Test route without middleware
Route::get('/test-dashboard', [CourseController::class, 'dashboard'])->name('test.dashboard');

// Simple auth test
Route::get('/auth-test', function() {
    $memberCheck = \Illuminate\Support\Facades\Auth::guard('member')->check();
    $memberUser = \Illuminate\Support\Facades\Auth::guard('member')->user();

    return response()->json([
        'status' => 'Auth Test',
        'member_guard_check' => $memberCheck,
        'member_guard_user' => $memberUser?->email ?? 'null',
        'member_guard_id' => $memberUser?->id ?? 'null',
        'session_data' => session()->all(),
        'session_id' => session()->getId(),
        'message' => $memberCheck ? 'Member is authenticated' : 'Member is NOT authenticated'
    ]);
});

// Session inspection route
Route::get('/session-debug', function() {
    $sessionData = session()->all();

    return response()->json([
        'session_id' => session()->getId(),
        'all_session_data' => $sessionData,
        'auth_session_keys' => array_filter(array_keys($sessionData), function($key) {
            return strpos($key, 'login_') === 0 || strpos($key, 'password_') === 0;
        })
    ]);
});

// Manual auth test
Route::get('/manual-auth-test', function() {
    // Find member session key dynamically
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

    // Try to retrieve the member manually
    $member = null;
    if ($memberId) {
        $member = \App\Models\Member::find($memberId);
    }

    return response()->json([
        'session_key_found' => $memberSessionKey,
        'member_id_from_session' => $memberId,
        'member_retrieved' => $member ? [
            'id' => $member->id,
            'email' => $member->email,
            'is_active' => $member->is_active
        ] : null,
        'auth_guard_check' => \Illuminate\Support\Facades\Auth::guard('member')->check(),
        'auth_guard_user' => \Illuminate\Support\Facades\Auth::guard('member')->user()?->email,
        'message' => $member ? 'Member found manually' : 'Member not found'
    ]);
});

// Force login test
Route::get('/force-login-test', function() {
    $member = \App\Models\Member::find(44);
    if ($member) {
        \Illuminate\Support\Facades\Auth::guard('member')->login($member);
        return response()->json([
            'manual_login_attempted' => true,
            'member_email' => $member->email,
            'auth_check_after_manual_login' => \Illuminate\Support\Facades\Auth::guard('member')->check(),
            'auth_user_after_manual_login' => \Illuminate\Support\Facades\Auth::guard('member')->user()?->email,
            'session_after_manual_login' => session()->all()
        ]);
    }
    return response()->json(['error' => 'Member not found']);
});

Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer.index');
Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store');

Route::get('/media', [MediaController::class, 'index'])->name('media.index');

// Teaching Series routes
Route::get('/media/teaching-series', [TeachingSeriesController::class, 'index'])->name('teaching-series.index');
Route::get('/media/teaching-series/{slug}', [TeachingSeriesController::class, 'show'])->name('teaching-series.show');

// CityLife TalkTime routes
Route::get('/media/citylife-talktime', [CityLifeTalkTimeController::class, 'index'])->name('citylife-talktime.index');
Route::get('/media/citylife-talktime/{talkTime}', [CityLifeTalkTimeController::class, 'show'])->name('citylife-talktime.show');

// CityLife Music routes
Route::get('/media/citylife-music', [App\Http\Controllers\CityLifeMusicController::class, 'index'])->name('citylife-music.index');
Route::get('/media/citylife-music/{music}', [App\Http\Controllers\CityLifeMusicController::class, 'show'])->name('citylife-music.show');

// SEO routes
Route::get('/sitemap.xml', [App\Http\Controllers\SEOController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SEOController::class, 'robots'])->name('robots');

