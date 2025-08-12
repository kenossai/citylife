<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = \App\Models\Banner::active()->ordered()->get();
        $events = \App\Models\Event::published()->upcoming()->orderBy('start_date')->limit(3)->get();
        $section = \App\Models\BecomingSection::getActiveSection();
         $aboutPage = AboutPage::active()
            ->with(['coreValues' => function($query) {
                $query->active()->ordered();
            }])
            ->first();
        return view('index', compact('banners', 'events', 'section', 'aboutPage'));
    }
}
