<?php

namespace App\Http\Controllers;

use App\Models\BibleSchoolEvent;
use App\Models\BibleSchoolVideo;
use App\Models\BibleSchoolAudio;
use App\Models\BibleSchoolAccessCode;
use App\Models\BibleSchoolSpeaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BibleSchoolInternationalController extends Controller
{
    /**
     * Display the bible school about page
     */
    public function about()
    {
        return view('bible-school-international.about');
    }

    /**
     * Display all speakers with their sessions
     */
    public function resources()
    {
        $speakers = BibleSchoolSpeaker::active()
            ->with(['events' => function($query) {
                $query->active()
                    ->with(['videos' => function($q) {
                        $q->active();
                    }, 'audios' => function($q) {
                        $q->active();
                    }])
                    ->orderBy('year', 'desc');
            }])
            ->get();

        $years = BibleSchoolEvent::active()
            ->distinct()
            ->pluck('year')
            ->sort()
            ->reverse()
            ->values();

        return view('bible-school-international.resources', compact('speakers', 'years'));
    }

    /**
     * Display speakers archive by year
     */
    public function archive($year)
    {
        $speakers = BibleSchoolSpeaker::active()
            ->with(['events' => function($query) use ($year) {
                $query->active()
                    ->where('year', $year)
                    ->with(['videos' => function($q) {
                        $q->active();
                    }, 'audios' => function($q) {
                        $q->active();
                    }])
                    ->orderBy('year', 'desc');
            }])
            ->whereHas('events', function($query) use ($year) {
                $query->active()->where('year', $year);
            })
            ->get();

        $years = BibleSchoolEvent::active()
            ->distinct()
            ->pluck('year')
            ->sort()
            ->reverse()
            ->values();

        return view('bible-school-international.archive', compact('speakers', 'year', 'years'));
    }

    /**
     * Display speaker detail page with their resources
     */
    public function speaker($id)
    {
        $speaker = BibleSchoolSpeaker::active()
            ->with(['events' => function($query) {
                $query->active()
                    ->with(['videos' => function($q) {
                        $q->active()->orderBy('order');
                    }, 'audios' => function($q) {
                        $q->active()->orderBy('order');
                    }])
                    ->orderBy('year', 'desc');
            }])
            ->findOrFail($id);

        // Check if user has access for this speaker
        $hasAccess = Session::has("bible_school_speaker_access_{$speaker->id}");

        return view('bible-school-international.speaker', compact('speaker', 'hasAccess'));
    }

    /**
     * Verify access code for speaker resources
     */
    public function verifySpeakerCode(Request $request, $speakerId)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);

        $speaker = BibleSchoolSpeaker::active()->findOrFail($speakerId);

        // Get all events this speaker is associated with
        $eventIds = $speaker->events()->pluck('bible_school_events.id');

        // Check if code is valid for any of the speaker's events
        $accessCode = BibleSchoolAccessCode::whereIn('bible_school_event_id', $eventIds)
            ->where('code', strtoupper($request->access_code))
            ->first();

        if (!$accessCode || !$accessCode->isValid()) {
            return back()->with('error', 'Invalid or expired access code. Please check your code and try again.');
        }

        // Record usage
        $accessCode->recordUsage();

        // Store access in session
        Session::put("bible_school_speaker_access_{$speakerId}", [
            'code_id' => $accessCode->id,
            'student_name' => $accessCode->student_name,
            'granted_at' => now(),
        ]);

        return back()->with('success', 'Access granted! You can now view all resources for this speaker.');
    }
}
