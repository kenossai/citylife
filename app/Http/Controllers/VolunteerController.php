<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerApplication;

class VolunteerController extends Controller
{
    public function index()
    {
        // Logic to display the volunteer page
        return view('pages.volunteer.index');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'application_type' => 'required|in:event_only,ongoing',
            'team' => 'required|string',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'sex' => 'nullable|in:male,female,prefer_not_to_say',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:255',
            'address' => 'required|string',
            'medical_professional' => 'required|in:yes,no',
            'first_aid_certificate' => 'required|in:yes,no',
            'church_background' => 'required|string',
            'employment_details' => 'required|string',
            'support_mission' => 'required|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',
            'eligible_to_work' => 'required|in:yes,no',
            'data_processing_consent' => 'required|accepted',
            'data_protection_consent' => 'required|accepted',
        ]);

        // Convert radio button values to boolean
        $validatedData['medical_professional'] = $request->medical_professional === 'yes';
        $validatedData['first_aid_certificate'] = $request->first_aid_certificate === 'yes';
        $validatedData['eligible_to_work'] = $request->eligible_to_work === 'yes';
        $validatedData['data_processing_consent'] = true; // Already validated as accepted
        $validatedData['data_protection_consent'] = true; // Already validated as accepted

        try {
            // Create the volunteer application
            $application = VolunteerApplication::create($validatedData);

            // Redirect with success message
            return redirect()->back()->with('success', 'Your volunteer application has been submitted successfully! We will contact you soon.');

        } catch (\Exception $e) {
            // Log the error for debugging
            logger()->error('Volunteer application submission failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'name' => $request->name,
            ]);

            // Redirect with error message
            return redirect()->back()
                ->withErrors(['submission' => 'There was an error submitting your application. Please try again.'])
                ->withInput();
        }
    }
}
