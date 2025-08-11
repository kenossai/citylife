<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ContactSubmission;
use App\Models\Contact;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    public function index()
    {
        $contactInfo = Contact::active()->first();
        return view('pages.contact.index', compact('contactInfo'));
    }

    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:2000',
                'gdpr_consent' => 'required|accepted',
            ], [
                'gdpr_consent.required' => 'You must consent to our data protection policy to submit this form.',
                'gdpr_consent.accepted' => 'You must consent to our data protection policy to submit this form.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Contact form validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token', 'gdpr_consent']),
            ]);
            return redirect()->route('contact')->withErrors($e->errors())->withInput();
        }

        try {
            // Store the contact submission in database
            $submission = ContactSubmission::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'gdpr_consent' => true,
                'ip_address' => $request->ip(),
                'status' => 'new',
            ]);

            // Try to send email notification to admin
            try {
                $adminEmail = Contact::active()->first()?->email ?? config('mail.from.address');
                Mail::to($adminEmail)->send(new ContactFormSubmitted($submission));
                Log::info('Contact form email sent successfully', ['admin_email' => $adminEmail]);
            } catch (\Exception $mailException) {
                // Log mail error but don't fail the submission
                Log::error('Contact form email failed but submission saved', [
                    'error' => $mailException->getMessage(),
                    'submission_id' => $submission->id,
                ]);
            }

            // Log the contact form submission
            Log::info('Contact form submitted successfully', [
                'submission_id' => $submission->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return redirect()->route('contact')->with('success',
                'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
            );

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['_token', 'gdpr_consent']),
            ]);

            return redirect()->route('contact')->with('error',
                'We apologize, but there was an issue sending your message. Please try again or contact us directly.'
            )->withInput();
        }
    }
}
