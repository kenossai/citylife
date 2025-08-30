<?php

namespace App\Http\Controllers;

use App\Models\CityLifeMusic;
use Illuminate\Http\Request;

class CityLifeMusicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CityLifeMusic::published()->orderBySortOrder();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by artist
        if ($request->filled('artist')) {
            $query->byArtist($request->artist);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->byGenre($request->genre);
        }

        // Filter by featured
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'title':
                    $query->orderBy('title');
                    break;
                case 'artist':
                    $query->orderBy('artist');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBySortOrder();
            }
        }

        $music = $query->paginate(12);

        // Get featured music for homepage
        $featuredMusic = CityLifeMusic::published()
            ->featured()
            ->orderBySortOrder()
            ->take(3)
            ->get();

        // Get available artists and genres for filters
        $artists = CityLifeMusic::getArtists();
        $genres = CityLifeMusic::getGenres();

        return view('pages.media.citylife-music.index', compact(
            'music',
            'featuredMusic',
            'artists',
            'genres'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(CityLifeMusic $music)
    {
        // Only show published music to public
        if (!$music->is_published) {
            abort(404);
        }

        // Get related music by same artist or genre
        $relatedMusic = CityLifeMusic::published()
            ->where('id', '!=', $music->id)
            ->where(function ($query) use ($music) {
                $query->where('artist', $music->artist)
                      ->orWhere('genre', $music->genre);
            })
            ->orderBySortOrder()
            ->take(6)
            ->get();

        return view('pages.media.citylife-music.show', compact('music', 'relatedMusic'));
    }
}
