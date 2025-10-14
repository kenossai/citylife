<?php

namespace App\Http\Controllers;

use App\Models\BabyDedication;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogger;

class BabyDedicationController extends Controller
{
    public function index()
    {
        return view('baby-dedication.index');
    }

    public function create()
    {
        return view('baby-dedication.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Baby Information
            'baby_first_name' => 'required|string|max:255',
            'baby_middle_name' => 'nullable|string|max:255',
            'baby_last_name' => 'required|string|max:255',
            'baby_date_of_birth' => 'required|date|before:today',
            'baby_gender' => 'required|in:male,female',
            'baby_place_of_birth' => 'nullable|string|max:255',
            'baby_special_notes' => 'nullable|string|max:1000',

            // Father Information
            'father_first_name' => 'required|string|max:255',
            'father_last_name' => 'required|string|max:255',
            'father_email' => 'required|email|max:255',
            'father_phone' => 'required|string|max:255',
            'father_is_member' => 'boolean',
            'father_membership_number' => 'nullable|string|exists:members,membership_number',

            // Mother Information
            'mother_first_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'mother_email' => 'required|email|max:255',
            'mother_phone' => 'required|string|max:255',
            'mother_is_member' => 'boolean',
            'mother_membership_number' => 'nullable|string|exists:members,membership_number',

            // Address
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',

            // Dedication Details
            'preferred_dedication_date' => 'nullable|date|after:today',
            'preferred_service' => 'required|in:morning,evening,either',
            'special_requests' => 'nullable|string|max:1000',
            'photography_consent' => 'boolean',
            'video_consent' => 'boolean',

            // Church Information
            'regular_attendees' => 'boolean',
            'how_long_attending' => 'nullable|string|max:255',
            'previous_church' => 'nullable|string|max:255',
            'baptized_parents' => 'boolean',
            'faith_commitment' => 'nullable|string|max:1000',

            // Emergency Contact
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',

            // Consent
            'gdpr_consent' => 'required|accepted',
            'newsletter_consent' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare data for creation
        $data = $validator->validated();
        $data['gdpr_consent_date'] = now();
        $data['gdpr_consent_ip'] = $request->ip();
        $data['status'] = 'pending';

        // Create the baby dedication record
        $dedication = BabyDedication::create($data);

        // Log the submission
        AuditLogger::logResourceAction(
            'create',
            $dedication,
            null,
            $data,
            'pastoral',
            'medium',
            false,
            'Baby dedication application submitted'
        );

        // Send notification email (you can implement this later)
        // $this->sendNotificationEmail($dedication);

        return redirect()->route('baby-dedication.success')
            ->with('success', 'Your baby dedication application has been submitted successfully. We will contact you within 2-3 business days.');
    }

    public function success()
    {
        return view('baby-dedication.success');
    }

    public function show(BabyDedication $babyDedication)
    {
        return view('baby-dedication.show', compact('babyDedication'));
    }

    // API endpoint to check member status
    public function checkMemberStatus(Request $request)
    {
        $membershipNumber = $request->get('membership_number');

        if (!$membershipNumber) {
            return response()->json(['exists' => false]);
        }

        $member = Member::where('membership_number', $membershipNumber)->first();

        if ($member) {
            return response()->json([
                'exists' => true,
                'name' => $member->full_name,
                'email' => $member->email,
                'phone' => $member->phone,
            ]);
        }

        return response()->json(['exists' => false]);
    }

    // Admin methods (you can protect these with middleware)
    public function adminIndex()
    {
        $dedications = BabyDedication::with(['approvedBy', 'fatherMember', 'motherMember'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.baby-dedications.index', compact('dedications'));
    }

    public function adminShow(BabyDedication $babyDedication)
    {
        $babyDedication->load(['approvedBy', 'fatherMember', 'motherMember']);
        return view('admin.baby-dedications.show', compact('babyDedication'));
    }

    public function approve(Request $request, BabyDedication $babyDedication)
    {
        $user = Auth::user();
        $babyDedication->approve($user);

        AuditLogger::logResourceAction(
            'approve',
            $babyDedication,
            ['status' => 'pending'],
            ['status' => 'approved'],
            'pastoral',
            'medium',
            false,
            'Baby dedication application approved'
        );

        return redirect()->back()
            ->with('success', 'Baby dedication application approved successfully.');
    }

    public function schedule(Request $request, BabyDedication $babyDedication)
    {
        $request->validate([
            'scheduled_date' => 'required|date|after:today',
            'scheduled_service' => 'required|in:morning,evening',
        ]);

        $babyDedication->schedule(
            $request->scheduled_date,
            $request->scheduled_service
        );

        AuditLogger::logResourceAction(
            'schedule',
            $babyDedication,
            null,
            [
                'scheduled_date' => $request->scheduled_date,
                'scheduled_service' => $request->scheduled_service
            ],
            'pastoral',
            'medium',
            false,
            'Baby dedication scheduled'
        );

        return redirect()->back()
            ->with('success', 'Baby dedication scheduled successfully.');
    }

    private function sendNotificationEmail(BabyDedication $dedication)
    {
        // You can implement email notifications here
        // For example, notify pastoral team about new application
    }
}
