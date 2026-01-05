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
        // Anti-spam: Check honeypot field (should be empty)
        if ($request->filled('website') || $request->filled('url')) {
            Log::warning('Contact form spam detected: honeypot field filled', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            // Pretend success to bot
            return redirect()->route('contact')->with('success',
                'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
            );
        }

        // Anti-spam: Rate limiting (max 3 submissions per hour from same IP)
        $recentSubmissions = ContactSubmission::where('ip_address', $request->ip())
            ->where('created_at', '>', now()->subHour())
            ->count();

        if ($recentSubmissions >= 3) {
            Log::warning('Contact form spam detected: rate limit exceeded', [
                'ip' => $request->ip(),
                'count' => $recentSubmissions,
            ]);
            return redirect()->route('contact')->with('error',
                'Too many submission attempts. Please try again later.'
            );
        }

        // Anti-spam: Check for suspicious content
        $suspiciousPatterns = [
            'https?:\/\/proff?seo\.ru',
            'https?:\/\/.*\.ru\/prodvizhenie',
            'SEO.*promotion',
            'купить|продвижение|рейтинг',
            'bit\.ly|tinyurl|goo\.gl',
        ];

        $combinedText = $request->input('message') . ' ' . $request->input('subject');
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $combinedText)) {
                Log::warning('Contact form spam detected: suspicious content', [
                    'ip' => $request->ip(),
                    'pattern' => $pattern,
                    'email' => $request->input('email'),
                ]);
                // Pretend success to bot
                return redirect()->route('contact')->with('success',
                    'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
                );
            }
        }

        // Anti-spam: Time-based validation (form loaded timestamp check)
        if ($request->filled('form_time')) {
            $formTime = (int) $request->input('form_time');
            $currentTime = time();
            $timeDiff = $currentTime - $formTime;

            // Form filled too quickly (less than 3 seconds) - likely a bot
            if ($timeDiff < 3) {
                Log::warning('Contact form spam detected: filled too quickly', [
                    'ip' => $request->ip(),
                    'time_diff' => $timeDiff,
                ]);
                return redirect()->route('contact')->with('success',
                    'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
                );
            }
        }

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
