<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Logic to display a list of courses
        $courses = Course::published()->get();
        return view('pages.course.index', compact('courses'));
    }

    public function show($id)
    {
        // Logic to display a single course
        $course = Course::findOrFail($id);
        return view('pages.course.show', compact('course'));
    }
}
