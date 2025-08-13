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

// Course dashboard for users
Route::get('/my-courses', [CourseController::class, 'dashboard'])->name('courses.dashboard');

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

Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer.index');
Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store');

Route::get('/media', [MediaController::class, 'index'])->name('media.index');

// Teaching Series routes
Route::get('/media/teaching-series', [TeachingSeriesController::class, 'index'])->name('teaching-series.index');
Route::get('/media/teaching-series/{slug}', [TeachingSeriesController::class, 'show'])->name('teaching-series.show');

// CityLife TalkTime routes
Route::get('/media/citylife-talktime', [CityLifeTalkTimeController::class, 'index'])->name('citylife-talktime.index');
Route::get('/media/citylife-talktime/{talkTime}', [CityLifeTalkTimeController::class, 'show'])->name('citylife-talktime.show');

