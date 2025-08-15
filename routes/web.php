<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\TeachingSeriesController;
use App\Http\Controllers\CityLifeTalkTimeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-citylife', [AboutController::class, 'index'])->name('about');
Route::get('/about/core-values/{slug}', [AboutController::class, 'showCoreValue'])->name('about.core-value');


// Route for the course controller
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{slug}/register', [CourseController::class, 'showRegistrationForm'])->name('courses.register.form');
Route::post('/courses/{slug}/register', [CourseController::class, 'processRegistration'])->name('courses.register');

// Course lesson and quiz routes
Route::get('/courses/{slug}/lessons', [CourseController::class, 'lessons'])->name('courses.lessons');
Route::get('/courses/{courseSlug}/lessons/{lessonSlug}', [CourseController::class, 'showLesson'])->name('courses.lesson.show');
Route::get('/courses/{courseSlug}/lessons/{lessonSlug}/quiz', [CourseController::class, 'showQuiz'])->name('courses.lesson.quiz');
Route::post('/courses/{courseSlug}/lessons/{lessonSlug}/quiz', [CourseController::class, 'submitQuiz'])->name('courses.lesson.quiz.submit');

// Certificate download route
Route::get('/certificate/{enrollment_id}/download', [CourseController::class, 'downloadCertificate'])->name('certificate.download');

// Route for the events controller
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Route for the contact controller
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

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

// Protected Member Routes
Route::middleware('auth:member')->group(function () {
    Route::get('/my-courses', [CourseController::class, 'dashboard'])->name('courses.dashboard');
    Route::get('/courses/{slug}/lessons', [CourseController::class, 'lessons'])->name('courses.lessons');
    Route::get('/courses/{courseSlug}/lessons/{lessonSlug}', [CourseController::class, 'showLesson'])->name('courses.lesson.show');
    Route::post('/courses/{courseSlug}/lessons/{lessonSlug}/complete', [CourseController::class, 'completeLesson'])->name('courses.lesson.complete');
    Route::post('/courses/{courseSlug}/lessons/{lessonSlug}/quiz', [CourseController::class, 'submitQuiz'])->name('courses.lesson.quiz.submit');
    Route::get('/certificate/{enrollment_id}/download', [CourseController::class, 'downloadCertificate'])->name('certificate.download');
});

// Debug route to check authentication
Route::get('/auth-debug', function() {
    return response()->json([
        'member_guard_check' => \Illuminate\Support\Facades\Auth::guard('member')->check(),
        'member_guard_user' => \Illuminate\Support\Facades\Auth::guard('member')->user()?->email,
        'session_user_email' => session('user_email'),
        'all_session_data' => session()->all(),
    ]);
});

// Test route without middleware
Route::get('/test-dashboard', [CourseController::class, 'dashboard'])->name('test.dashboard');

Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer.index');
Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store');

Route::get('/media', [MediaController::class, 'index'])->name('media.index');

// Teaching Series routes
Route::get('/media/teaching-series', [TeachingSeriesController::class, 'index'])->name('teaching-series.index');
Route::get('/media/teaching-series/{slug}', [TeachingSeriesController::class, 'show'])->name('teaching-series.show');

// CityLife TalkTime routes
Route::get('/media/citylife-talktime', [CityLifeTalkTimeController::class, 'index'])->name('citylife-talktime.index');
Route::get('/media/citylife-talktime/{talkTime}', [CityLifeTalkTimeController::class, 'show'])->name('citylife-talktime.show');

