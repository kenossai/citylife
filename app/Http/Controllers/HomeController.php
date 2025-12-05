<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Check if tables exist first
            if (!\Schema::hasTable('banners')) {
                return response('<html><body style="font-family: sans-serif; padding: 50px; text-align: center;">
                    <h1>🏗️ Setup Required</h1>
                    <p>Database tables are being created. Please wait a moment and refresh.</p>
                    <p><a href="/health">Check Health Status</a> | <a href="/admin">Admin Panel</a></p>
                    <p style="color: #666; font-size: 12px; margin-top: 30px;">Missing table: banners</p>
                    </body></html>', 503);
            }

            $banners = \App\Models\Banner::active()->ordered()->get();
            $events = \App\Models\Event::published()->upcoming()->orderBy('start_date')->limit(3)->get();
            $section = \App\Models\BecomingSection::getActiveSection();
            $aboutPage = AboutPage::active()
                ->with(['coreValues' => function($query) {
                    $query->active()->ordered();
                }])
                ->first();

            return view('index', compact('banners', 'events', 'section', 'aboutPage'));
        } catch (\Exception $e) {
            // Show detailed error for debugging
            return response('<html><body style="font-family: monospace; padding: 50px;">
                <h1 style="color: red;">Error 500</h1>
                <h3>Error Details:</h3>
                <pre style="background: #f5f5f5; padding: 20px; border-radius: 5px;">' . 
                htmlspecialchars($e->getMessage()) . 
                '</pre>
                <p><a href="/health">Check Health</a> | <a href="/admin">Go to Admin</a></p>
                </body></html>', 500);
        }
    }
}
