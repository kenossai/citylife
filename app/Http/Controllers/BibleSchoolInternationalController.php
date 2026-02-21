<?php

namespace App\Http\Controllers;

use App\Mail\BibleSchoolVerificationMail;
use App\Models\BibleSchoolEvent;
use App\Models\BibleSchoolOtpToken;
use App\Models\BibleSchoolSpeaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
    public function speaker(Request $request, $id)
    {
        $requestedYear = $request->query('year');

        $speaker = BibleSchoolSpeaker::active()
            ->with(['events' => function ($query) use ($requestedYear) {
                $q = $query->active();
                if ($requestedYear) {
                    $q->where('year', $requestedYear);
                }
                $q->with(['videos' => function ($q) {
                        $q->active()->orderBy('order');
                    }, 'audios' => function ($q) {
                        $q->active()->orderBy('order');
                    }])
                    ->orderBy('year', 'desc');
            }])
            ->findOrFail($id);

        // Only check access for the requested year; fall back to all speaker years
        $speakerYears    = $speaker->events->pluck('year')->unique();
        $yearsToCheck    = $requestedYear ? collect([(int) $requestedYear]) : $speakerYears;
        $accessibleYears = $yearsToCheck->filter(fn ($year) =>
            Session::has("bible_school_year_access_{$year}")
        )->values();

        $hasAccess = $accessibleYears->isNotEmpty();

        return view('bible-school-international.speaker',
            compact('speaker', 'hasAccess', 'accessibleYears', 'requestedYear'));
    }

    /**
     * Step 1 – Accept the user's email, store a BS###### token and send it
     */
    public function sendEmailCode(Request $request, $speakerId)
    {
        $request->validate([
            'email'   => 'required|email',
            'consent' => 'accepted',
            'year'    => 'required|integer',
        ], [
            'consent.accepted' => 'You must agree to the data processing terms to continue.',
        ]);

        $speaker = BibleSchoolSpeaker::active()->findOrFail($speakerId);

        // Use the year the user is trying to unlock; validate it belongs to this speaker
        $validYears = $speaker->events()->active()->pluck('year')->unique()->all();
        $year       = in_array((int) $request->year, $validYears)
                        ? (int) $request->year
                        : ($speaker->events()->active()->max('year') ?? now()->year);

        // Expire any previous unused tokens for this email / year
        BibleSchoolOtpToken::where('email', strtolower(trim($request->email)))
            ->where('year', $year)
            ->whereNull('used_at')
            ->update(['expires_at' => now()]);

        // Generate and persist the new token
        $code = BibleSchoolOtpToken::generateCode();
        BibleSchoolOtpToken::create([
            'email'                   => strtolower(trim($request->email)),
            'code'                    => $code,
            'year'                    => $year,
            'bible_school_speaker_id' => $speakerId,
            'expires_at'              => now()->addMinutes(10),
            'ip_address'              => $request->ip(),
        ]);

        Mail::to($request->email)->send(new BibleSchoolVerificationMail($code, $speaker->name));

        return back()
            ->with('bsi_pending_email', $request->email)
            ->with('bsi_pending_year', $year)
            ->with('bsi_step', 'verify');
    }

    public function verifyEmailCode(Request $request, $speakerId)
    {
        $request->validate([
            'otp'   => 'required|string|max:30',
            'email' => 'required|email',
            'year'  => 'required|integer',
        ]);

        $speaker = BibleSchoolSpeaker::active()->findOrFail($speakerId);

        $requestedYear = (int) $request->year;
        $validYears    = $speaker->events()->active()->pluck('year')->unique()->all();

        // Lock lookup to the specific year being unlocked
        $token = BibleSchoolOtpToken::where('email', strtolower(trim($request->email)))
            ->where('code', strtoupper(trim($request->otp)))
            ->where('year', in_array($requestedYear, $validYears) ? $requestedYear : 0)
            ->valid()
            ->first();

        if (! $token) {
            return back()
                ->with('bsi_pending_email', $request->email)
                ->with('bsi_pending_year', $requestedYear)
                ->with('bsi_step', 'verify')
                ->with('error', 'The code you entered is incorrect or has expired. Please try again.');
        }

        $token->markUsed();

        // Grant access only for the specific year this token was issued for
        Session::put("bible_school_year_access_{$token->year}", [
            'email'      => $token->email,
            'year'       => $token->year,
            'granted_at' => now()->toDateTimeString(),
        ]);

        return redirect()->route('bible-school-international.speaker', ['id' => $speakerId, 'year' => $token->year])
            ->with('success', 'Access granted! You can now view all ' . $token->year . ' resources for this speaker.');
    }
}
