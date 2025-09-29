<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    public function index()
    {
        $liveStreams = LiveStream::getCurrentLiveStreams();
        $upcomingStreams = LiveStream::getUpcomingStreams(10);
        $featuredStreams = LiveStream::getFeaturedStreams();

        return view('pages.live-streams.index', compact('liveStreams', 'upcomingStreams', 'featuredStreams'));
    }

    public function show(LiveStream $liveStream)
    {
        // Only show public streams
        if (!$liveStream->is_public) {
            abort(404);
        }

        // Increment viewer count if stream is live
        if ($liveStream->is_live) {
            $liveStream->updateViewerCount($liveStream->estimated_viewers + 1);
        }

        $relatedStreams = LiveStream::where('category', $liveStream->category)
            ->where('id', '!=', $liveStream->id)
            ->public()
            ->whereIn('status', ['scheduled', 'live'])
            ->orderBy('scheduled_start', 'asc')
            ->limit(3)
            ->get();

        return view('pages.live-streams.show', compact('liveStream', 'relatedStreams'));
    }

    public function embed(LiveStream $liveStream)
    {
        // Only show public streams
        if (!$liveStream->is_public) {
            abort(404);
        }

        return view('pages.live-streams.embed', compact('liveStream'));
    }

    public function updateViewers(Request $request, LiveStream $liveStream)
    {
        $request->validate([
            'viewers' => 'required|integer|min:0'
        ]);

        $liveStream->updateViewerCount($request->viewers);

        return response()->json(['success' => true]);
    }
}
