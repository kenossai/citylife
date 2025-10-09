<?php

namespace App\Http\Controllers;

use App\Services\CookieConsentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CookieConsentController extends Controller
{
    protected $cookieConsentService;

    public function __construct(CookieConsentService $cookieConsentService)
    {
        $this->cookieConsentService = $cookieConsentService;
    }

    /**
     * Save cookie consent preferences
     */
    public function saveConsent(Request $request): JsonResponse
    {
        $request->validate([
            'essential' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'functional' => 'boolean',
        ]);

        $preferences = $request->only(['essential', 'analytics', 'marketing', 'functional']);
        $cookie = $this->cookieConsentService->saveConsent($preferences);

        return response()->json([
            'success' => true,
            'message' => 'Cookie preferences saved successfully'
        ])->cookie($cookie);
    }

    /**
     * Get current consent preferences
     */
    public function getConsent(Request $request): JsonResponse
    {
        $consent = $this->cookieConsentService->getConsent($request);

        return response()->json([
            'consent' => $consent,
            'show_banner' => $this->cookieConsentService->shouldShowBanner($request)
        ]);
    }

    /**
     * Get cookie categories and information
     */
    public function getCookieInfo(): JsonResponse
    {
        $categories = $this->cookieConsentService->getCookieCategories();

        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Accept all cookies
     */
    public function acceptAll(Request $request): JsonResponse
    {
        $preferences = [
            'essential' => true,
            'analytics' => true,
            'marketing' => true,
            'functional' => true,
        ];

        $cookie = $this->cookieConsentService->saveConsent($preferences);

        return response()->json([
            'success' => true,
            'message' => 'All cookies accepted'
        ])->cookie($cookie);
    }

    /**
     * Reject all non-essential cookies
     */
    public function rejectAll(Request $request): JsonResponse
    {
        $preferences = [
            'essential' => true,
            'analytics' => false,
            'marketing' => false,
            'functional' => false,
        ];

        $cookie = $this->cookieConsentService->saveConsent($preferences);
        $cookiesToRemove = $this->cookieConsentService->removeNonEssentialCookies();

        $response = response()->json([
            'success' => true,
            'message' => 'Non-essential cookies rejected'
        ])->cookie($cookie);

        // Add cookies to remove
        foreach ($cookiesToRemove as $cookieToRemove) {
            $response->cookie($cookieToRemove);
        }

        return $response;
    }

    /**
     * Show cookie policy page
     */
    public function cookiePolicy()
    {
        $categories = $this->cookieConsentService->getCookieCategories();

        return view('pages.cookie-policy', compact('categories'));
    }

    /**
     * Get analytics code if consent given
     */
    public function getAnalyticsCode(Request $request): JsonResponse
    {
        $analyticsCode = $this->cookieConsentService->getAnalyticsCode($request);

        return response()->json([
            'analytics_code' => $analyticsCode,
            'has_consent' => $this->cookieConsentService->hasConsent($request, 'analytics')
        ]);
    }
}
