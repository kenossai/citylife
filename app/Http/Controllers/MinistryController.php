<?php

namespace App\Http\Controllers;

use App\Models\Ministry;
use Illuminate\Http\Request;

class MinistryController extends Controller
{
    /**
     * Display a listing of ministries
     */
    public function index()
    {
        $ministries = Ministry::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.ministries.index', compact('ministries'));
    }

    /**
     * Display the specified ministry
     */
    public function show($slug)
    {
        $ministry = Ministry::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get active members with their roles
        $members = $ministry->activeMembers()
            ->orderByPivot('role')
            ->orderBy('first_name')
            ->get();

        return view('pages.ministries.show', compact('ministry', 'members'));
    }

    /**
     * Show ministry contact form
     */
    public function contact($slug)
    {
        $ministry = Ministry::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.ministries.contact', compact('ministry'));
    }

    /**
     * Handle ministry contact form submission
     */
    public function submitContact(Request $request, $slug)
    {
        $ministry = Ministry::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // Here you can send an email or store the inquiry
        // For now, we'll just redirect with success message

        return redirect()->route('ministries.show', $slug)
            ->with('success', 'Thank you for your interest! We will contact you soon.');
    }
}
