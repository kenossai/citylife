<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = \App\Models\Banner::active()->ordered()->get();
        $events = \App\Models\Event::published()->upcoming()->orderBy('start_date')->limit(3)->get();

        return view('index', compact('banners', 'events'));
    }
}
