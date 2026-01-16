<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ContactSubmission;
use App\Models\Contact;
use App\Models\BlockedIp;
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
        $userIp = $request->ip();

        // Anti-spam: Block known spam IP addresses (config file + database)
        $configBlockedIPs = config('spam-protection.blocked_ips', []);
        $isDatabaseBlocked = BlockedIp::isBlocked($userIp);

        if (in_array($userIp, $configBlockedIPs) || $isDatabaseBlocked) {
            // Increment spam count if IP is in database
            if ($isDatabaseBlocked) {
                $blockedIp = BlockedIp::where('ip_address', $userIp)->first();
                $blockedIp?->incrementSpamCount();
            }

            Log::warning('Contact form blocked: IP in blacklist', [
                'ip' => $userIp,
                'source' => $isDatabaseBlocked ? 'database' : 'config',
            ]);
            return redirect()->route('contact')->with('success',
                'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
            );
        }

        // Anti-spam: Check honeypot field (should be empty)
        $honeypotFields = config('spam-protection.honeypot_fields', ['website', 'url']);
        foreach ($honeypotFields as $field) {
            if ($request->filled($field)) {
                Log::warning('Contact form spam detected: honeypot field filled', [
                    'ip' => $request->ip(),
                    'field' => $field,
                    'user_agent' => $request->userAgent(),
                ]);
                // Pretend success to bot
                return redirect()->route('contact')->with('success',
                    'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
                );
            }
        }

        // Anti-spam: Rate limiting (max submissions per hour from same IP)
        $rateLimit = config('spam-protection.rate_limit_per_hour', 3);
        $recentSubmissions = ContactSubmission::where('ip_address', $request->ip())
            ->where('created_at', '>', now()->subHour())
            ->count();

        if ($recentSubmissions >= $rateLimit) {
            Log::warning('Contact form spam detected: rate limit exceeded', [
                'ip' => $request->ip(),
                'count' => $recentSubmissions,
            ]);
            return redirect()->route('contact')->with('error',
                'Too many submission attempts. Please try again later.'
            );
        }

        // Anti-spam: Check foconfig('spam-protection.suspicious_patterns', []);

        $combinedText = $request->input('message') . ' ' . $request->input('subject') . ' ' . $request->input('name');

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $combinedText)) {
                Log::warning('Contact form spam detected: suspicious content', [
                    'ip' => $request->ip(),
                    'pattern' => $pattern,
                    'email' => $request->input('email'),
                    'matched_text' => substr($combinedText, 0, 200),
                ]);
                // Pretend success to bot
                return redirect()->route('contact')->with('success',
                    'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
                );
            }
        }

        // Anti-spam: Block disposable email domains
        $disposableEmailDomains = config('spam-protection.disposable_email_domains', []);

        $emailDomain = strtolower(substr(strrchr($request->input('email'), "@"), 1));
        if (in_array($emailDomain, $disposableEmailDomains)) {
            Log::warning('Contact form spam detected: disposable email', [
                'ip' => $request->ip(),
                'email' => $request->input('email'),
                'domain' => $emailDomain,
            ]);
            return redirect()->route('contact')->with('error',
                'Please use a permanent email address. Temporary email services are not accepted.'
            );
        }

        // Anti-spam: Check for excessive URLs in message
        $maxUrls = config('spam-protection.max_urls_in_message', 2);
        $urlCount = preg_match_all('/https?:\/\/[^\s]+/i', $request->input('message'), $matches);
        if ($urlCount > $maxUrlsmatch_all('/https?:\/\/[^\s]+/i', $request->input('message'), $matches);
        if ($urlCount > 2) {
            Log::warning('Contact form spam detected: excessive URLs', [
                'ip' => $request->ip(),
                'url_count' => $urlCount,
                'email' => $request->input('email'),
            ]);
            return redirect()->route('contact')->with('success',
                'Thank you for reaching out! Your message has been received and we will get back to you soon. God bless!'
            );
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
            $minTime = config('spam-protection.minimum_form_time', 3);
            // Form filled too quickly - likely a bot
            if ($timeDiff < $minTimetactSubmission::create([
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
