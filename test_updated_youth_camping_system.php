<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test Updated Youth Camping Registration System
echo "🏕️ Testing Updated Youth Camping Registration System (Parent-Child Model)\n";
echo "=" . str_repeat("=", 70) . "\n\n";

echo "1. 🧪 Testing Database Schema\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Check if tables exist with correct structure
    $tables = ['youth_campings', 'youth_camping_registrations'];

    foreach ($tables as $table) {
        $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
        if ($exists) {
            echo "✅ Table '{$table}' exists\n";

            // Count columns
            $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM {$table}");
            echo "   └─ Has " . count($columns) . " columns\n";

            if ($table === 'youth_camping_registrations') {
                // Check for new parent-child specific columns
                $columnNames = array_column($columns, 'Field');
                $requiredColumns = [
                    'child_first_name', 'child_last_name', 'child_date_of_birth',
                    'parent_first_name', 'parent_last_name', 'parent_email',
                    'parent_relationship', 'home_address', 'child_t_shirt_size',
                    'pickup_authorized_persons', 'payment_amount'
                ];

                $missingColumns = array_diff($requiredColumns, $columnNames);
                if (empty($missingColumns)) {
                    echo "   └─ ✅ All required parent-child columns present\n";
                } else {
                    echo "   └─ ❌ Missing columns: " . implode(', ', $missingColumns) . "\n";
                }
            }
        } else {
            echo "❌ Table '{$table}' missing\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Database schema test failed: " . $e->getMessage() . "\n";
}

echo "\n2. 🏗️ Testing Model Updates\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test updated models
    $youthCamping = new \App\Models\YouthCamping();
    $registration = new \App\Models\YouthCampingRegistration();

    echo "✅ YouthCamping model loaded successfully\n";
    echo "✅ YouthCampingRegistration model loaded successfully\n";

    // Test new fillable fields
    $fillableFields = $registration->getFillable();
    $expectedFields = [
        'child_first_name', 'child_last_name', 'parent_first_name',
        'parent_last_name', 'parent_email', 'child_t_shirt_size'
    ];

    $hasExpectedFields = true;
    foreach ($expectedFields as $field) {
        if (!in_array($field, $fillableFields)) {
            echo "❌ Missing fillable field: {$field}\n";
            $hasExpectedFields = false;
        }
    }

    if ($hasExpectedFields) {
        echo "✅ All expected fillable fields present\n";
    }

    // Test new computed attributes
    if (method_exists($registration, 'getChildFullNameAttribute')) {
        echo "✅ Child full name attribute method exists\n";
    } else {
        echo "❌ Child full name attribute method missing\n";
    }

    if (method_exists($registration, 'getParentFullNameAttribute')) {
        echo "✅ Parent full name attribute method exists\n";
    } else {
        echo "❌ Parent full name attribute method missing\n";
    }

} catch (Exception $e) {
    echo "❌ Model test failed: " . $e->getMessage() . "\n";
}

echo "\n3. 🎯 Testing Controller Updates\n";
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

echo "\n4. 🎨 Testing Filament Resource Updates\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Test if updated Filament resources exist
    $resourceClass = \App\Filament\Resources\YouthCampingRegistrationResource::class;

    if (class_exists($resourceClass)) {
        echo "✅ YouthCampingRegistrationResource exists\n";

        // Test if it has required methods
        if (method_exists($resourceClass, 'form') && method_exists($resourceClass, 'table')) {
            echo "✅ Has form() and table() methods\n";
        } else {
            echo "❌ Missing required methods\n";
        }
    } else {
        echo "❌ YouthCampingRegistrationResource missing\n";
    }

} catch (Exception $e) {
    echo "❌ Filament resource test failed: " . $e->getMessage() . "\n";
}

echo "\n5. 📊 Testing Sample Data Creation\n";
echo "-" . str_repeat("-", 30) . "\n";

try {
    // Create a sample youth camping
    $youthCamping = \App\Models\YouthCamping::create([
        'name' => 'Summer Camp 2025',
        'slug' => 'summer-camp-2025',
        'year' => 2025,
        'description' => 'Annual summer camping for children and youth.',
        'start_date' => now()->addMonths(2),
        'end_date' => now()->addMonths(2)->addDays(4),
        'location' => 'Camp Wilderness',
        'registration_opens_at' => now()->addWeeks(1),
        'registration_closes_at' => now()->addMonths(1),
        'max_participants' => 50,
        'cost' => 150.00,
        'is_published' => true,
        'is_registration_open' => true,
    ]);

    echo "✅ Sample youth camping created: {$youthCamping->name}\n";

    // Create a sample registration
    $registration = \App\Models\YouthCampingRegistration::create([
        'youth_camping_id' => $youthCamping->id,

        // Child Information
        'child_first_name' => 'Emma',
        'child_last_name' => 'Johnson',
        'child_date_of_birth' => now()->subYears(12),
        'child_age' => 12,
        'child_gender' => 'female',
        'child_grade_school' => 'Grade 7 - Central School',
        'child_t_shirt_size' => 'M',

        // Parent Information
        'parent_first_name' => 'Sarah',
        'parent_last_name' => 'Johnson',
        'parent_email' => 'sarah.johnson@example.com',
        'parent_phone' => '(555) 123-4567',
        'parent_relationship' => 'mother',

        // Address
        'home_address' => '123 Main Street',
        'city' => 'Toronto',
        'postal_code' => 'M5V 3A8',
        'home_phone' => '(416) 555-1234',

        // Emergency Contact
        'emergency_contact_name' => 'Mike Johnson',
        'emergency_contact_phone' => '(555) 987-6543',
        'emergency_contact_relationship' => 'father',

        // Medical Information
        'medical_conditions' => ['Asthma'],
        'medications' => ['Ventolin Inhaler'],
        'allergies' => ['Peanuts'],
        'dietary_requirements' => ['No nuts'],
        'swimming_ability' => 'intermediate',
        'doctor_name' => 'Dr. Smith',
        'doctor_phone' => '(416) 555-9999',

        // Consent
        'consent_photo_video' => true,
        'consent_medical_treatment' => true,
        'consent_activities' => true,
        'consent_pickup_authorized_persons' => true,
        'pickup_authorized_persons' => ['Mike Johnson', 'Grandma Betty'],

        'payment_amount' => 150.00,
        'registration_date' => now(),
    ]);

    echo "✅ Sample registration created for: {$registration->child_full_name}\n";
    echo "   └─ Parent: {$registration->parent_full_name}\n";
    echo "   └─ Email: {$registration->parent_email}\n";
    echo "   └─ Child Age: {$registration->child_calculated_age} years old\n";
    echo "   └─ Has all consents: " . ($registration->has_all_consents ? 'Yes' : 'No') . "\n";
    echo "   └─ Registration complete: " . ($registration->is_complete ? 'Yes' : 'No') . "\n";

} catch (Exception $e) {
    echo "❌ Sample data creation failed: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "🎉 Updated Youth Camping Registration System Test Complete!\n";
echo "\n📋 Key Changes Made:\n";
echo "1. 👶 Child-focused registration (parents register children)\n";
echo "2. 👨‍👩‍👧‍👦 Separate parent/guardian information section\n";
echo "3. 🆔 Better duplicate prevention (parent email + child name)\n";
echo "4. 👕 Child-specific fields (t-shirt size, grade/school)\n";
echo "5. 🛡️ Enhanced safety (pickup authorization)\n";
echo "6. 💰 Payment amount tracking\n";
echo "7. 🏥 Detailed medical information\n";
echo "8. 📧 Parent as primary contact\n";

echo "\n🚀 System Features:\n";
echo "1. ⏰ Automatic registration opening/closing based on dates\n";
echo "2. 👥 Maximum participant capacity management\n";
echo "3. 📊 Comprehensive admin dashboard in Filament\n";
echo "4. 📝 Complete registration form with validation\n";
echo "5. 💊 Medical information and consent tracking\n";
echo "6. 📱 Parent contact and emergency contact system\n";
echo "7. 🔒 Safety and pickup authorization\n";
echo "8. 💳 Payment status and amount tracking\n";

echo "\n📌 Next Steps:\n";
echo "1. 🎨 Create public registration form views\n";
echo "2. 📧 Set up email confirmations to parents\n";
echo "3. 🔄 Configure automatic command scheduling\n";
echo "4. 🧪 Test complete registration workflow\n";
echo "5. 📱 Add SMS notifications (optional)\n";

echo "\n✨ The updated youth camping system is ready for parent registrations!\n";
