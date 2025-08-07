<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Logic to display a list of courses with pagination
        $courses = Course::published()
            ->orderBy('sort_order')
            ->orderBy('start_date')
            ->paginate(6); // Load 6 courses per page

        // If it's an AJAX request for infinite scroll
        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.course.partials.course-cards', compact('courses'))->render(),
                'has_more' => $courses->hasMorePages(),
                'next_page' => $courses->hasMorePages() ? $courses->currentPage() + 1 : null
            ]);
        }

        return view('pages.course.index', compact('courses'));
    }

    public function show($slug)
    {
        // Logic to display a single course
        $course = Course::where('slug', $slug)->firstOrFail();
        return view('pages.course.show', compact('course'));
    }
}
