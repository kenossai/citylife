<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::published()
            ->upcoming()
            ->orderBy('start_date')
            ->paginate(12);

        return view('pages.event.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->with(['eventAnchor', 'contactPerson', 'speakers'])
            ->published()
            ->firstOrFail();

        return view('pages.event.show', compact('event'));
    }
}
