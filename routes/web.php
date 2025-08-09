<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CourseController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-citylife', [AboutController::class, 'index'])->name('about');
Route::get('/about/core-values/{slug}', [AboutController::class, 'showCoreValue'])->name('about.core-value');


// Route for the course controller
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{slug}/register', [CourseController::class, 'showRegistrationForm'])->name('courses.register.form');
Route::post('/courses/{slug}/register', [CourseController::class, 'processRegistration'])->name('courses.register');
