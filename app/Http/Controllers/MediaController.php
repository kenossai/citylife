<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeachingSeries;

class MediaController extends Controller
{
    public function index()
    {
        // Get featured and latest teaching series for the media overview
        $featuredSeries = TeachingSeries::getFeaturedSeries(6);
        $latestSeries = TeachingSeries::getLatestSeries(8);
        $categories = TeachingSeries::getCategories();

        return view('pages.media.index', compact('featuredSeries', 'latestSeries', 'categories'));
    }
}
