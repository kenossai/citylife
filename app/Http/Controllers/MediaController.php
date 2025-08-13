<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        // Logic to display media items
        return view('pages.media.teaching-series');
    }
}
