<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeachingSeries;

class BibleSchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = TeachingSeries::published()->orderBySortOrder();

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('summary', 'like', "%{$searchTerm}%")
                  ->orWhere('pastor', 'like', "%{$searchTerm}%")
                  ->orWhere('scripture_references', 'like', "%{$searchTerm}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->byCategory($request->input('category'));
        }

        // Apply pastor filter
        if ($request->filled('pastor')) {
            $query->where('pastor', $request->input('pastor'));
        }

        // Sort options
        $sortBy = $request->input('sort', 'date_desc');
        switch ($sortBy) {
            case 'date_asc':
                $query->orderByDate('asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            case 'date_desc':
            default:
                $query->orderByDate('desc');
                break;
        }

        $teachingSeries = $query->paginate(9);
        $categories = TeachingSeries::getCategories();
        $pastors = TeachingSeries::published()
            ->distinct('pastor')
            ->whereNotNull('pastor')
            ->where('pastor', '!=', '')
            ->pluck('pastor')
            ->sort()
            ->values();

        $featuredSeries = TeachingSeries::getFeaturedSeries(3);
        return view('pages.bible-school.index', compact('teachingSeries', 'categories', 'pastors', 'featuredSeries'));
    }
}
