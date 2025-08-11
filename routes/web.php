<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ContactController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-citylife', [AboutController::class, 'index'])->name('about');
Route::get('/about/core-values/{slug}', [AboutController::class, 'showCoreValue'])->name('about.core-value');


// Route for the course controller
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{slug}/register', [CourseController::class, 'showRegistrationForm'])->name('courses.register.form');
Route::post('/courses/{slug}/register', [CourseController::class, 'processRegistration'])->name('courses.register');

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
