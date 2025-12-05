<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
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
            // If database tables don't exist yet, show setup message
            if (str_contains($e->getMessage(), "doesn't exist") || str_contains($e->getMessage(), 'SQLSTATE')) {
                return response()->view('setup-required', [
                    'error' => 'Database tables are not set up yet. Please run migrations or access /admin to complete setup.'
                ], 503);
            }
            throw $e;
        }
    }
}
