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
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\BabyDedicationController;

// Serve storage files (using /media instead of /storage to avoid nginx conflicts)
Route::get('/media/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    \Log::info('Storage file requested', [
        'path' => $path,
        'full_path' => $filePath,
        'exists' => file_exists($filePath),
        'is_file' => is_file($filePath),
    ]);
    
    if (!file_exists($filePath)) {
        abort(404, 'File not found: ' . $path);
    }
    
    return response()->file($filePath);
})->where('path', '.*');

// Simple test route - no dependencies
Route::get('/test', function () {
    return response('Laravel is working! Time: ' . now());
});

// Database debug route - check tables and users
Route::get('/db-debug', function () {
    try {
        $users = \App\Models\User::all();
        $members = \App\Models\Member::all();

        // Get table list
        $tables = \DB::select('SHOW TABLES');

        return response()->json([
            'database' => env('DB_DATABASE'),
            'users_count' => $users->count(),
            'users' => $users->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'created_at' => $u->created_at,
            ]),
            'members_count' => $members->count(),
            'tables_count' => count($tables),
            'tables' => array_map(fn($t) => array_values((array)$t)[0], $tables),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
});

// Create admin user manually (for Laravel Cloud initial setup)
Route::get('/setup-admin', function () {
    try {
        // Check if admin already exists
        $existingAdmin = \App\Models\User::where('email', 'admin@citylife.com')->first();

        if ($existingAdmin) {
            return response()->json([
                'message' => 'Admin user already exists',
                'email' => 'admin@citylife.com',
                'action' => 'Login at /admin with your credentials',
            ]);
        }

        // Create new admin
        $user = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@citylife.com',
            'password' => \Hash::make('CityLife2025!'),
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully!',
            'email' => 'admin@citylife.com',
            'password' => 'CityLife2025!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'next_step' => 'Login at /admin with these credentials. CHANGE PASSWORD IMMEDIATELY after login.',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
});

// Session debug
Route::get('/session-debug', function () {
    try {
        $sessionId = session()->getId();
        session()->put('test_key', 'test_value');

        return response()->json([
            'session_driver' => config('session.driver'),
            'session_id' => $sessionId,
            'session_data' => session()->all(),
            'app_key_set' => !empty(config('app.key')),
            'app_url' => config('app.url'),
            'session_domain' => config('session.domain'),
            'session_secure' => config('session.secure'),
            'session_same_site' => config('session.same_site'),
            'csrf_token' => csrf_token(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ]);
    }
});

// Storage debug
Route::get('/storage-debug', function () {
    try {
        $publicDiskConfig = config('filesystems.disks.public');
        $s3DiskConfig = config('filesystems.disks.s3');
        $defaultDisk = config('filesystems.default');
        
        // List files in current disk
        $files = \Storage::disk($defaultDisk)->allFiles();
        
        // Get URL for first file if exists
        $sampleUrl = null;
        if (count($files) > 0) {
            $sampleUrl = \Storage::disk($defaultDisk)->url($files[0]);
        }
        
        return response()->json([
            'default_disk' => $defaultDisk,
            'public_disk_config' => $publicDiskConfig,
            's3_disk_config' => [
                'driver' => $s3DiskConfig['driver'] ?? null,
                'key_set' => !empty($s3DiskConfig['key']),
                'secret_set' => !empty($s3DiskConfig['secret']),
                'region' => $s3DiskConfig['region'] ?? null,
                'bucket' => $s3DiskConfig['bucket'] ?? null,
                'endpoint' => $s3DiskConfig['endpoint'] ?? null,
            ],
            'app_url' => config('app.url'),
            'app_env' => config('app.env'),
            'storage_path' => storage_path('app/public'),
            'files_count' => count($files),
            'sample_files' => array_slice($files, 0, 5),
            'sample_url' => $sampleUrl,
            'storage_exists' => file_exists(storage_path('app/public')),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
});

// Clear session and logout
Route::get('/clear-session', function () {
    try {
        \Auth::guard('web')->logout();
        \Auth::guard('member')->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        // Also clear the cookie
        \Cookie::queue(\Cookie::forget(config('session.cookie')));
        
        return response()->json([
            'success' => true,
            'message' => 'Session and cookies cleared. Visit /force-login to login again',
        ])->withoutCookie(config('session.cookie'));
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ]);
    }
});

// Reset admin password
Route::get('/reset-admin-password', function () {
    try {
        $admin = \App\Models\User::find(2); // The logged-in user from session
        
        if (!$admin) {
            return response()->json([
                'error' => 'User ID 2 not found',
                'suggestion' => 'Visit /db-debug to see all users',
            ]);
        }
        
        // Update password
        $admin->password = \Hash::make('CityLife2025!');
        $admin->save();
        
        // Clear all sessions
        \Auth::guard('web')->logout();
        session()->flush();
        
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully!',
            'user' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ],
            'credentials' => [
                'email' => $admin->email,
                'password' => 'CityLife2025!',
            ],
            'next_step' => 'Login at /admin with the above credentials',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
});

// Test login credentials
Route::get('/test-login', function (\Illuminate\Http\Request $request) {
    try {
        $email = $request->input('email', 'admin@citylife.com');
        $password = $request->input('password', 'CityLife2025!');
        
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'email' => $email,
            ]);
        }
        
        $passwordMatches = \Hash::check($password, $user->password);
        
        return response()->json([
            'user_found' => true,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'password_matches' => $passwordMatches,
            'can_login' => $passwordMatches,
            'message' => $passwordMatches ? 'Credentials are valid!' : 'Password does not match',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ]);
    }
});

// Direct login bypass for Laravel Cloud
Route::get('/force-login', function () {
    try {
        $user = \App\Models\User::where('email', 'admin@citylife.com')->first();
        
        if (!$user) {
            return response()->json([
                'error' => 'Admin user not found',
            ]);
        }
        
        // Clear any existing auth
        \Auth::guard('web')->logout();
        session()->flush();
        session()->regenerate();
        
        // Force login
        \Auth::guard('web')->login($user, true);
        
        // Verify login worked
        $isLoggedIn = \Auth::guard('web')->check();
        $currentUser = \Auth::guard('web')->user();
        
        if (!$isLoggedIn) {
            return response()->json([
                'error' => 'Login failed',
                'auth_check' => $isLoggedIn,
                'session_data' => session()->all(),
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully!',
            'user' => [
                'id' => $currentUser->id,
                'name' => $currentUser->name,
                'email' => $currentUser->email,
            ],
            'auth_check' => $isLoggedIn,
            'next_steps' => [
                'Try accessing /admin now',
                'If 403 persists, use /simple-dashboard instead',
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
});

// Simple dashboard bypass
Route::get('/simple-dashboard', function () {
    if (!\Auth::guard('web')->check()) {
        return redirect('/force-login');
    }
    
    $user = \Auth::guard('web')->user();
    
    return response()->json([
        'logged_in' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
        'message' => 'You are logged in! The /admin Filament panel has issues on Laravel Cloud.',
        'workaround' => 'Use your local environment to manage content via Filament, or contact Laravel Cloud support about the 403 Forbidden issue.',
        'available_routes' => [
            '/db-debug' => 'View database contents',
            '/session-debug' => 'View session info',
            '/health' => 'Health check',
        ],
    ]);
});

// Health Check for Laravel Cloud
Route::get('/health', function () {
    try {
        // Check database connection
        \DB::connection()->getPdo();

        // Check cache
        \Cache::get('health_check_test');

        return response()->json([
            'status' => 'healthy',
            'database' => 'connected',
            'cache' => 'working',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'error' => $e->getMessage(),
            'timestamp' => now()->toIso8601String(),
        ], 503);
    }
})->name('health');

// Cookie Consent Routes
Route::prefix('cookie-consent')->group(function () {
    Route::post('/save', [CookieConsentController::class, 'saveConsent'])->name('cookie-consent.save');
    Route::get('/get', [CookieConsentController::class, 'getConsent'])->name('cookie-consent.get');
    Route::get('/info', [CookieConsentController::class, 'getCookieInfo'])->name('cookie-consent.info');
    Route::post('/accept-all', [CookieConsentController::class, 'acceptAll'])->name('cookie-consent.accept-all');
    Route::post('/reject-all', [CookieConsentController::class, 'rejectAll'])->name('cookie-consent.reject-all');
    Route::get('/analytics-code', [CookieConsentController::class, 'getAnalyticsCode'])->name('cookie-consent.analytics');
});

// Cookie Policy Page
Route::get('/cookie-policy', [CookieConsentController::class, 'cookiePolicy'])->name('cookie-policy');

// Privacy Policy Page
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');

// Homepage
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

// Youth Camping routes
Route::get('/youth/camping', [App\Http\Controllers\YouthCampingController::class, 'index'])->name('youth-camping.index');
Route::get('/youth/camping/{youthCamping:slug}', [App\Http\Controllers\YouthCampingController::class, 'show'])->name('youth-camping.show');
Route::get('/youth/camping/{youthCamping:slug}/register', [App\Http\Controllers\YouthCampingController::class, 'register'])->name('youth-camping.register');
Route::post('/youth/camping/{youthCamping:slug}/register', [App\Http\Controllers\YouthCampingController::class, 'processRegistration'])->name('youth-camping.register.submit');
Route::get('/youth/camping/{youthCamping:slug}/registration-success/{registration}', [App\Http\Controllers\YouthCampingController::class, 'registrationSuccess'])->name('youth-camping.registration-success');
Route::post('/youth/camping/check-registration', [App\Http\Controllers\YouthCampingController::class, 'checkRegistration'])->name('youth-camping.check-registration');

// Baby Dedication Routes
Route::prefix('baby-dedication')->name('baby-dedication.')->group(function () {
    Route::get('/', [BabyDedicationController::class, 'index'])->name('index');
    Route::get('/register', [BabyDedicationController::class, 'create'])->name('create');
    Route::post('/register', [BabyDedicationController::class, 'store'])->name('store');
    Route::get('/success', [BabyDedicationController::class, 'success'])->name('success');

    // API endpoint for checking member status
    Route::get('/check-member', [BabyDedicationController::class, 'checkMemberStatus'])->name('check-member');
});

