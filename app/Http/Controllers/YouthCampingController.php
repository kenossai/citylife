<?php

namespace App\Http\Controllers;

use App\Models\YouthCamping;
use App\Models\YouthCampingRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class YouthCampingController extends Controller
{
    /**
     * Display all youth campings
     */
    public function index()
    {
        $currentCamping = YouthCamping::published()
            ->currentYear()
            ->upcoming()
            ->first();

        $pastCampings = YouthCamping::published()
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'desc')
            ->take(3)
            ->get();

        return view('youth-camping.index', compact('currentCamping', 'pastCampings'));
    }

    /**
     * Show a specific camping
     */
    public function show(YouthCamping $youthCamping)
    {
        if (!$youthCamping->is_published) {
            abort(404);
        }

        // Auto-update registration status based on dates
        $youthCamping->autoOpenRegistration();
        $youthCamping->autoCloseRegistration();
        $youthCamping->refresh();

        return view('youth-camping.show', compact('youthCamping'));
    }

    /**
     * Show registration form
     */
    public function register(YouthCamping $youthCamping)
    {
        if (!$youthCamping->is_published || !$youthCamping->is_registration_available) {
            return redirect()->route('youth-camping.show', $youthCamping)
                ->with('error', 'Registration is not currently available for this camping.');
        }

        return view('youth-camping.register', compact('youthCamping'));
    }

    /**
     * Process registration
     */
    public function processRegistration(Request $request, YouthCamping $youthCamping)
    {
        if (!$youthCamping->is_published || !$youthCamping->is_registration_available) {
            return redirect()->route('youth-camping.show', $youthCamping)
                ->with('error', 'Registration is not currently available for this camping.');
        }

        $validator = Validator::make($request->all(), [
            // Child Information
            'child_first_name' => 'required|string|max:255',
            'child_last_name' => 'required|string|max:255',
            'child_date_of_birth' => 'required|date|before:' . now()->subYears(5)->format('Y-m-d') . '|after:' . now()->subYears(18)->format('Y-m-d'),
            'child_gender' => 'nullable|in:male,female',
            'child_grade_school' => 'nullable|string|max:255',
            'child_t_shirt_size' => 'nullable|in:XS,S,M,L,XL',

            // Parent/Guardian Information
            'parent_first_name' => 'required|string|max:255',
            'parent_last_name' => 'required|string|max:255',
            'parent_email' => 'required|email|max:255',
            'parent_phone' => 'required|string|max:255',
            'parent_relationship' => 'required|in:mother,father,guardian,other',

            // Address Information
            'home_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'home_phone' => 'nullable|string|max:255',
            'work_phone' => 'nullable|string|max:255',

            // Emergency Contact
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',

            // Medical Information
            'medical_conditions' => 'nullable|array',
            'medications' => 'nullable|array',
            'allergies' => 'nullable|array',
            'dietary_requirements' => 'nullable|array',
            'swimming_ability' => 'nullable|in:non_swimmer,beginner,intermediate,advanced',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_phone' => 'nullable|string|max:255',
            'health_card_number' => 'nullable|string|max:255',

            // Consent Requirements
            'consent_photo_video' => 'accepted',
            'consent_medical_treatment' => 'accepted',
            'consent_activities' => 'accepted',
            'consent_pickup_authorized_persons' => 'nullable|boolean',
            'pickup_authorized_persons' => 'nullable|array',

            // Additional Information
            'special_needs' => 'nullable|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if there are still available spots
        if ($youthCamping->max_participants &&
            $youthCamping->confirmedRegistrations()->count() >= $youthCamping->max_participants) {
            return redirect()->route('youth-camping.show', $youthCamping)
                ->with('error', 'Sorry, this camping is now fully booked.');
        }

        // Check for duplicate registration by parent email + child name
        $existingRegistration = YouthCampingRegistration::where('youth_camping_id', $youthCamping->id)
            ->where('parent_email', $request->parent_email)
            ->where('child_first_name', $request->child_first_name)
            ->where('child_last_name', $request->child_last_name)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingRegistration) {
            return redirect()->route('youth-camping.show', $youthCamping)
                ->with('error', 'A registration for this child already exists for this camping.');
        }

        DB::beginTransaction();
        try {
            // Create registration
            $registration = YouthCampingRegistration::create([
                'youth_camping_id' => $youthCamping->id,

                // Child Information
                'child_first_name' => $request->child_first_name,
                'child_last_name' => $request->child_last_name,
                'child_date_of_birth' => $request->child_date_of_birth,
                'child_age' => now()->diffInYears($request->child_date_of_birth),
                'child_gender' => $request->child_gender,
                'child_grade_school' => $request->child_grade_school,
                'child_t_shirt_size' => $request->child_t_shirt_size,

                // Parent/Guardian Information
                'parent_first_name' => $request->parent_first_name,
                'parent_last_name' => $request->parent_last_name,
                'parent_email' => $request->parent_email,
                'parent_phone' => $request->parent_phone,
                'parent_relationship' => $request->parent_relationship,

                // Address Information
                'home_address' => $request->home_address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'home_phone' => $request->home_phone,
                'work_phone' => $request->work_phone,

                // Emergency Contact
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relationship' => $request->emergency_contact_relationship,

                // Medical Information
                'medical_conditions' => $request->medical_conditions ?? [],
                'medications' => $request->medications ?? [],
                'allergies' => $request->allergies ?? [],
                'dietary_requirements' => $request->dietary_requirements ?? [],
                'swimming_ability' => $request->swimming_ability,
                'doctor_name' => $request->doctor_name,
                'doctor_phone' => $request->doctor_phone,
                'health_card_number' => $request->health_card_number,

                // Consent
                'consent_photo_video' => true,
                'consent_medical_treatment' => true,
                'consent_activities' => true,
                'consent_pickup_authorized_persons' => $request->boolean('consent_pickup_authorized_persons'),
                'pickup_authorized_persons' => $request->pickup_authorized_persons ?? [],

                // Additional Information
                'special_needs' => $request->special_needs,
                'additional_notes' => $request->additional_notes,
                'status' => 'pending',
                'payment_status' => 'pending',
                'registration_date' => now(),
            ]);

            // Send confirmation email (you can implement this later)
            // $this->sendConfirmationEmail($registration);

            DB::commit();

            return redirect()->route('youth-camping.registration-success', [
                'youthCamping' => $youthCamping,
                'registration' => $registration
            ])->with('success', 'Registration submitted successfully!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'An error occurred while processing your registration. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show registration success page
     */
    public function registrationSuccess(YouthCamping $youthCamping, YouthCampingRegistration $registration)
    {
        // Verify the registration belongs to this camping
        if ($registration->youth_camping_id !== $youthCamping->id) {
            abort(404);
        }

        return view('youth-camping.registration-success', compact('youthCamping', 'registration'));
    }

    /**
     * Check registration status
     */
    public function checkRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'camping_id' => 'required|exists:youth_campings,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid data provided'], 400);
        }

        $registration = YouthCampingRegistration::where('youth_camping_id', $request->camping_id)
            ->where('email', $request->email)
            ->first();

        if (!$registration) {
            return response()->json(['error' => 'No registration found'], 404);
        }

        return response()->json([
            'status' => $registration->status,
            'payment_status' => $registration->payment_status,
            'registration_date' => $registration->registration_date->format('M j, Y'),
            'full_name' => $registration->full_name,
        ]);
    }

    /**
     * Send confirmation email (implement this method)
     */
    private function sendConfirmationEmail(YouthCampingRegistration $registration)
    {
        // TODO: Implement email sending
        // You can create a Mail class for this
    }
}
