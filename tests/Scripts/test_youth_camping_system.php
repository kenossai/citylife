<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test Youth Camping Registration System
echo "🏕️ Testing Youth Camping Registration System\n";
echo "=" . str_repeat("=", 60) . "\n\n";

echo "1. 🗃️ Testing Database Models\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test YouthCamping model
    $youthCamping = new \App\Models\YouthCamping();
    echo "✅ YouthCamping model loaded successfully\n";

    // Test fillable fields
    $fillableFields = $youthCamping->getFillable();
    echo "✅ YouthCamping has " . count($fillableFields) . " fillable fields\n";

    // Test YouthCampingRegistration model
    $registration = new \App\Models\YouthCampingRegistration();
    echo "✅ YouthCampingRegistration model loaded successfully\n";
    echo "✅ Registration has " . count($registration->getFillable()) . " fillable fields\n";

} catch (Exception $e) {
    echo "❌ Model test failed: " . $e->getMessage() . "\n";
}

echo "\n2. 🏗️ Testing Model Relationships\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test relationships exist
    $youthCamping = new \App\Models\YouthCamping();
    $registration = new \App\Models\YouthCampingRegistration();

    // Test if methods exist
    if (method_exists($youthCamping, 'registrations')) {
        echo "✅ YouthCamping->registrations() relationship exists\n";
    } else {
        echo "❌ YouthCamping->registrations() relationship missing\n";
    }

    if (method_exists($youthCamping, 'confirmedRegistrations')) {
        echo "✅ YouthCamping->confirmedRegistrations() relationship exists\n";
    } else {
        echo "❌ YouthCamping->confirmedRegistrations() relationship missing\n";
    }

    if (method_exists($registration, 'youthCamping')) {
        echo "✅ YouthCampingRegistration->youthCamping() relationship exists\n";
    } else {
        echo "❌ YouthCampingRegistration->youthCamping() relationship missing\n";
    }

} catch (Exception $e) {
    echo "❌ Relationship test failed: " . $e->getMessage() . "\n";
}

echo "\n3. 📊 Testing Database Tables\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Check if tables exist
    $tables = ['youth_campings', 'youth_camping_registrations'];

    foreach ($tables as $table) {
        $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
        if ($exists) {
            echo "✅ Table '{$table}' exists\n";

            // Count columns
            $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM {$table}");
            echo "   └─ Has " . count($columns) . " columns\n";
        } else {
            echo "❌ Table '{$table}' missing\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Database test failed: " . $e->getMessage() . "\n";
}

echo "\n4. 🎯 Testing Routes\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test if youth camping routes exist
    $routes = [
        'youth-camping.index' => '/youth/camping',
        'youth-camping.show' => '/youth/camping/{slug}',
        'youth-camping.register' => '/youth/camping/{slug}/register',
    ];

    foreach ($routes as $routeName => $path) {
        try {
            // This will throw an exception if route doesn't exist
            app('router')->getRoutes()->getByName($routeName);
            echo "✅ Route '{$routeName}' registered\n";
        } catch (Exception $e) {
            echo "❌ Route '{$routeName}' not found\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Route testing failed: " . $e->getMessage() . "\n";
}

echo "\n5. 🏗️ Testing Model Attributes and Methods\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    $youthCamping = new \App\Models\YouthCamping([
        'name' => 'Test Summer Camp',
        'year' => 2025,
        'start_date' => now()->addMonths(3),
        'end_date' => now()->addMonths(3)->addDays(3),
        'registration_opens_at' => now()->addWeeks(2),
        'registration_closes_at' => now()->addMonths(2),
        'max_participants' => 50,
        'is_published' => true,
        'is_registration_open' => false,
    ]);

    // Test computed attributes
    if (method_exists($youthCamping, 'getIsRegistrationAvailableAttribute')) {
        echo "✅ isRegistrationAvailable attribute method exists\n";
    }

    if (method_exists($youthCamping, 'getAvailableSpotsAttribute')) {
        echo "✅ availableSpots attribute method exists\n";
    }

    if (method_exists($youthCamping, 'getRegistrationStatusMessageAttribute')) {
        echo "✅ registrationStatusMessage attribute method exists\n";

        // Test the message
        $message = $youthCamping->registration_status_message;
        echo "   └─ Status message: '{$message}'\n";
    }

    // Test registration model attributes
    $registration = new \App\Models\YouthCampingRegistration([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'date_of_birth' => now()->subYears(16),
        'consent_photo_video' => true,
        'consent_medical_treatment' => true,
        'consent_activities' => true,
    ]);

    if (method_exists($registration, 'getFullNameAttribute')) {
        echo "✅ fullName attribute method exists\n";
        echo "   └─ Full name: '" . $registration->full_name . "'\n";
    }

    if (method_exists($registration, 'getHasAllConsentsAttribute')) {
        echo "✅ hasAllConsents attribute method exists\n";
        echo "   └─ Has all consents: " . ($registration->has_all_consents ? 'Yes' : 'No') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Model attributes test failed: " . $e->getMessage() . "\n";
}

echo "\n6. 🔧 Testing Commands\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Check if the management command exists
    $commandExists = class_exists(\App\Console\Commands\ManageYouthCampingRegistrations::class);

    if ($commandExists) {
        echo "✅ ManageYouthCampingRegistrations command exists\n";

        // Test command signature
        $command = new \App\Console\Commands\ManageYouthCampingRegistrations();
        $signature = $command->getName();
        echo "   └─ Command signature: '{$signature}'\n";
    } else {
        echo "❌ ManageYouthCampingRegistrations command missing\n";
    }

} catch (Exception $e) {
    echo "❌ Command test failed: " . $e->getMessage() . "\n";
}

echo "\n7. 📱 Testing Controller\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test if controller exists and has required methods
    $controller = new \App\Http\Controllers\YouthCampingController();
    echo "✅ YouthCampingController exists\n";

    $requiredMethods = ['index', 'show', 'register', 'processRegistration', 'registrationSuccess'];
    foreach ($requiredMethods as $method) {
        if (method_exists($controller, $method)) {
            echo "✅ Method '{$method}' exists\n";
        } else {
            echo "❌ Method '{$method}' missing\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Controller test failed: " . $e->getMessage() . "\n";
}

echo "\n8. 🎨 Testing Filament Resources\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test if Filament resources exist
    $resources = [
        \App\Filament\Resources\YouthCampingResource::class => 'YouthCampingResource',
        \App\Filament\Resources\YouthCampingRegistrationResource::class => 'YouthCampingRegistrationResource',
    ];

    foreach ($resources as $class => $name) {
        if (class_exists($class)) {
            echo "✅ {$name} exists\n";

            // Test if it has required methods
            if (method_exists($class, 'form') && method_exists($class, 'table')) {
                echo "   └─ Has form() and table() methods\n";
            } else {
                echo "   └─ Missing required methods\n";
            }
        } else {
            echo "❌ {$name} missing\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Filament resources test failed: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "🎉 Youth Camping Registration System Test Complete!\n";
echo "\nKey Features Implemented:\n";
echo "1. 🏕️ Youth Camping Management (Admin)\n";
echo "2. 📝 Registration System with Full Form\n";
echo "3. ⏰ Automatic Registration Opening/Closing\n";
echo "4. 👥 Participant Capacity Management\n";
echo "5. 💊 Medical Information Collection\n";
echo "6. 📋 Parent/Guardian Consent System\n";
echo "7. 💳 Payment Status Tracking\n";
echo "8. 📊 Registration Status Management\n";
echo "9. 🔧 Admin Interface for Management\n";
echo "10. 📅 Automatic Date-based Registration Control\n";

echo "\nNext Steps:\n";
echo "1. 🎨 Create Blade templates for public pages\n";
echo "2. 📧 Implement email confirmations\n";
echo "3. 🔄 Set up automatic command scheduling\n";
echo "4. 🧪 Create sample youth camping data\n";
echo "5. 🎯 Test public registration flow\n";

echo "\n🚀 The youth camping system is ready for configuration!\n";
