<?php

namespace App\Http\Controllers;

use App\Models\CityLifeTalkTime;
use Illuminate\Http\Request;

class CityLifeTalkTimeController extends Controller
{
    public function index(Request $request)
    {
        $query = CityLifeTalkTime::published()
            ->orderBySortOrder();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('host', 'like', "%{$searchTerm}%")
                  ->orWhere('guest', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by host
        if ($request->filled('host')) {
            $query->where('host', $request->get('host'));
        }

        // Filter by featured
        if ($request->filled('featured') && $request->get('featured') === '1') {
            $query->featured();
        }

        $talkTimes = $query->paginate(12);
        
        // Get featured episodes for homepage
        $featuredTalkTimes = CityLifeTalkTime::published()
            ->featured()
            ->orderBySortOrder()
            ->take(3)
            ->get();

        // Get available hosts for filter
        $hosts = CityLifeTalkTime::published()
            ->whereNotNull('host')
            ->distinct()
            ->orderBy('host')
            ->pluck('host');

        return view('pages.media.citylife-talktime.index', compact(
            'talkTimes',
            'featuredTalkTimes',
            'hosts'
        ));
    }

    public function show(CityLifeTalkTime $talkTime)
    {
        // Only show published episodes to public
        if (!$talkTime->is_published) {
            abort(404);
        }

        // Get related episodes (same host or recent episodes)
        $relatedTalkTimes = CityLifeTalkTime::published()
            ->where('id', '!=', $talkTime->id)
            ->where(function ($query) use ($talkTime) {
                if ($talkTime->host) {
                    $query->where('host', $talkTime->host);
                }
            })
            ->orderBySortOrder()
            ->take(6)
            ->get();

        // If no related by host, get recent episodes
        if ($relatedTalkTimes->count() < 3) {
            $relatedTalkTimes = CityLifeTalkTime::published()
                ->where('id', '!=', $talkTime->id)
                ->orderBySortOrder()
                ->take(6)
                ->get();
        }

        return view('pages.media.citylife-talktime.show', compact(
            'talkTime',
            'relatedTalkTimes'
        ));
    }
}
