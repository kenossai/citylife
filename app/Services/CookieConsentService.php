<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CookieConsentService
{
    const COOKIE_NAME = 'citylife_cookie_consent';
    const COOKIE_EXPIRY_DAYS = 365;

    /**
     * Get the current cookie consent preferences
     */
    public function getConsent(Request $request): ?array
    {
        $cookieValue = $request->cookie(self::COOKIE_NAME);

        if ($cookieValue) {
            try {
                return json_decode($cookieValue, true);
            } catch (\Exception $e) {
                Log::warning('Failed to decode cookie consent', ['error' => $e->getMessage()]);
            }
        }

        return null;
    }

    /**
     * Save cookie consent preferences
     */
    public function saveConsent(array $preferences): \Symfony\Component\HttpFoundation\Cookie
    {
        $consentData = [
            'essential' => true, // Always true
            'analytics' => $preferences['analytics'] ?? false,
            'marketing' => $preferences['marketing'] ?? false,
            'functional' => $preferences['functional'] ?? false,
            'timestamp' => Carbon::now()->toISOString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Log consent for compliance
        $this->logConsent($consentData);

        return Cookie::make(
            self::COOKIE_NAME,
            json_encode($consentData),
            self::COOKIE_EXPIRY_DAYS * 24 * 60, // minutes
            '/',
            null,
            true, // secure
            false, // httpOnly - false so JavaScript can read it
            false, // raw
            'lax' // sameSite
        );
    }

    /**
     * Check if user has consented to a specific cookie type
     */
    public function hasConsent(Request $request, string $type): bool
    {
        $consent = $this->getConsent($request);

        if (!$consent) {
            return false;
        }

        return isset($consent[$type]) && $consent[$type] === true;
    }

    /**
     * Get all cookie categories and their descriptions
     */
    public function getCookieCategories(): array
    {
        return [
            'essential' => [
                'name' => 'Essential Cookies',
                'description' => 'These cookies are necessary for the website to function and cannot be disabled.',
                'required' => true,
                'cookies' => [
                    'citylife_cookie_consent' => 'Stores your cookie preferences',
                    'XSRF-TOKEN' => 'Security token to prevent cross-site request forgery',
                    'laravel_session' => 'Session management and user authentication',
                ]
            ],
            'analytics' => [
                'name' => 'Analytics Cookies',
                'description' => 'These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously.',
                'required' => false,
                'cookies' => [
                    '_ga' => 'Google Analytics - distinguishes users',
                    '_gid' => 'Google Analytics - distinguishes users',
                    '_gat' => 'Google Analytics - throttle request rate',
                ]
            ],
            'marketing' => [
                'name' => 'Marketing Cookies',
                'description' => 'These cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging.',
                'required' => false,
                'cookies' => [
                    'fbp' => 'Facebook Pixel - tracks user interactions',
                    '_fbp' => 'Facebook Pixel - browser identification',
                ]
            ],
            'functional' => [
                'name' => 'Functional Cookies',
                'description' => 'These cookies enable the website to provide enhanced functionality and personalization.',
                'required' => false,
                'cookies' => [
                    'preferred_language' => 'Remembers your language preference',
                    'theme_preference' => 'Remembers your theme preference',
                ]
            ]
        ];
    }

    /**
     * Generate Google Analytics code if consent is given
     */
    public function getAnalyticsCode(Request $request): ?string
    {
        if (!$this->hasConsent($request, 'analytics')) {
            return null;
        }

        $gaId = config('services.google_analytics.id');
        if (!$gaId) {
            return null;
        }

        return "
        <!-- Google Analytics -->
        <script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaId}\"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{$gaId}', {
                anonymize_ip: true,
                cookie_flags: 'SameSite=None;Secure'
            });
        </script>
        ";
    }

    /**
     * Log consent for compliance purposes
     */
    private function logConsent(array $consentData): void
    {
        try {
            Log::info('Cookie consent saved', [
                'consent' => $consentData,
                'url' => request()->url(),
                'referer' => request()->header('referer'),
            ]);

            // You could also save to database for detailed audit trail
            // ConsentLog::create($consentData);
        } catch (\Exception $e) {
            Log::error('Failed to log cookie consent', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove non-essential cookies
     */
    public function removeNonEssentialCookies(): array
    {
        $essentialCookies = [
            self::COOKIE_NAME,
            'XSRF-TOKEN',
            'laravel_session',
            '_token'
        ];

        $cookiesToRemove = [];

        // Get all cookies from request
        foreach ($_COOKIE as $cookieName => $cookieValue) {
            if (!in_array($cookieName, $essentialCookies)) {
                $cookiesToRemove[] = Cookie::forget($cookieName);
            }
        }

        return $cookiesToRemove;
    }

    /**
     * Check if consent banner should be shown
     */
    public function shouldShowBanner(Request $request): bool
    {
        return $this->getConsent($request) === null;
    }

    /**
     * Get consent summary for admin dashboard
     */
    public function getConsentSummary(): array
    {
        // This would typically query a database table
        // For now, return mock data
        return [
            'total_consents' => 0,
            'analytics_consent_rate' => 0,
            'marketing_consent_rate' => 0,
            'functional_consent_rate' => 0,
            'recent_consents' => []
        ];
    }
}
