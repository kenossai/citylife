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
                    <h1>🏗️ Setup in Progress</h1>
                    <p>Database tables not found. Running migrations...</p>
                    <p><a href="/test">Test Laravel</a> | <a href="/health">Check Health</a> | <a href="/admin">Admin Panel</a></p>
                    </body></html>', 200);
            }

            // Get data with defaults if empty
            $banners = \App\Models\Banner::active()->ordered()->get();
            $events = \App\Models\Event::published()->upcoming()->orderBy('start_date')->limit(3)->get();
            $section = \App\Models\BecomingSection::getActiveSection();
            $aboutPage = AboutPage::active()
                ->with(['coreValues' => function($query) {
                    $query->active()->ordered();
                }])
                ->first();

            // Check if view exists
            if (!view()->exists('index')) {
                return response('<html><body style="font-family: sans-serif; padding: 50px; text-align: center;">
                    <h1>⚠️ View Missing</h1>
                    <p>The index.blade.php view file is missing or not cached properly.</p>
                    <p><a href="/admin">Go to Admin Panel</a></p>
                    </body></html>', 200);
            }

            return view('index', compact('banners', 'events', 'section', 'aboutPage'));

        } catch (\Throwable $e) {
            // Catch all errors including fatal ones
            $error = htmlspecialchars($e->getMessage());
            $file = htmlspecialchars($e->getFile());
            $line = $e->getLine();

            return response("<!DOCTYPE html>
            <html>
            <body style='font-family: monospace; padding: 50px; background: #f5f5f5;'>
                <h1 style='color: #e53e3e;'>Error on Homepage</h1>
                <div style='background: white; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                    <h3>Error Message:</h3>
                    <pre style='background: #fff5f5; padding: 15px; border-left: 4px solid #e53e3e;'>{$error}</pre>
                    <p><strong>File:</strong> {$file}</p>
                    <p><strong>Line:</strong> {$line}</p>
                </div>
                <p>
                    <a href='/test' style='color: #3182ce;'>Test Route</a> |
                    <a href='/health' style='color: #3182ce;'>Health Check</a> |
                    <a href='/admin' style='color: #3182ce;'>Admin Panel</a>
                </p>
            </body>
            </html>", 200);
        }
    }
}
