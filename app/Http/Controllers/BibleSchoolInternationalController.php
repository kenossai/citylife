<?php

namespace App\Http\Controllers;

use App\Models\BibleSchoolEvent;
use App\Models\BibleSchoolVideo;
use App\Models\BibleSchoolAudio;
use App\Models\BibleSchoolAccessCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BibleSchoolInternationalController extends Controller
{
    /**
     * Display the bible school landing page
     */
    public function index()
    {
        $events = BibleSchoolEvent::active()
            ->with(['videos', 'audios'])
            ->orderBy('year', 'desc')
            ->get();

        $years = BibleSchoolEvent::active()
            ->distinct()
            ->pluck('year')
            ->sort()
            ->reverse()
            ->values();

        return view('bible-school-international.index', compact('events', 'years'));
    }

    /**
     * Display events archive by year
     */
    public function archive($year)
    {
        $events = BibleSchoolEvent::active()
            ->byYear($year)
            ->with(['videos', 'audios'])
            ->get();

        $years = BibleSchoolEvent::active()
            ->distinct()
            ->pluck('year')
            ->sort()
            ->reverse()
            ->values();

        return view('bible-school-international.archive', compact('events', 'year', 'years'));
    }

    /**
     * Display a specific event
     */
    public function event($id)
    {
        $event = BibleSchoolEvent::active()
            ->with(['videos' => function($query) {
                $query->active()->orderBy('order');
            }, 'audios' => function($query) {
                $query->active()->orderBy('order');
            }])
            ->findOrFail($id);

        // Check if user has access for this event
        $hasAccess = Session::has("bible_school_access_{$event->id}");

        return view('bible-school-international.event', compact('event', 'hasAccess'));
    }

    /**
     * Display video detail page
     */
    public function video($eventId, $videoId)
    {
        $event = BibleSchoolEvent::active()->findOrFail($eventId);
        $video = BibleSchoolVideo::active()
            ->where('bible_school_event_id', $eventId)
            ->findOrFail($videoId);

        // Check if user has access
        $hasAccess = Session::has("bible_school_access_{$event->id}");

        return view('bible-school-international.video', compact('event', 'video', 'hasAccess'));
    }

    /**
     * Display audio detail page
     */
    public function audio($eventId, $audioId)
    {
        $event = BibleSchoolEvent::active()->findOrFail($eventId);
        $audio = BibleSchoolAudio::active()
            ->where('bible_school_event_id', $eventId)
            ->findOrFail($audioId);

        // Check if user has access
        $hasAccess = Session::has("bible_school_access_{$event->id}");

        return view('bible-school-international.audio', compact('event', 'audio', 'hasAccess'));
    }

    /**
     * Verify access code
     */
    public function verifyCode(Request $request, $eventId)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);

        $event = BibleSchoolEvent::active()->findOrFail($eventId);

        $accessCode = BibleSchoolAccessCode::where('code', strtoupper($request->access_code))
            ->where('bible_school_event_id', $eventId)
            ->first();

        if (!$accessCode || !$accessCode->isValid()) {
            return back()->with('error', 'Invalid or expired access code. Please check your code and try again.');
        }

        // Record usage
        $accessCode->recordUsage();

        // Store access in session
        Session::put("bible_school_access_{$eventId}", [
            'code_id' => $accessCode->id,
            'student_name' => $accessCode->student_name,
            'granted_at' => now(),
        ]);

        return back()->with('success', 'Access granted! You can now view all resources for this event.');
    }

    /**
     * Verify access code for specific resource
     */
    public function verifyResourceCode(Request $request, $eventId, $resourceType, $resourceId)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);

        $event = BibleSchoolEvent::active()->findOrFail($eventId);

        $accessCode = BibleSchoolAccessCode::where('code', strtoupper($request->access_code))
            ->where('bible_school_event_id', $eventId)
            ->first();

        if (!$accessCode || !$accessCode->isValid()) {
            return back()->with('error', 'Invalid or expired access code. Please check your code and try again.');
        }

        // Record usage
        $accessCode->recordUsage();

        // Store access in session
        Session::put("bible_school_access_{$eventId}", [
            'code_id' => $accessCode->id,
            'student_name' => $accessCode->student_name,
            'granted_at' => now(),
        ]);

        return back()->with('success', 'Access granted! The resource is now unlocked.');
    }
}
