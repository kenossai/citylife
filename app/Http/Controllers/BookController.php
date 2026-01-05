<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::with('teamMember')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('published_date', 'desc');

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by author if provided
        if ($request->has('author') && $request->author) {
            $query->where('team_member_id', $request->author);
        }

        // Filter by format if provided
        if ($request->has('format') && $request->format) {
            $query->where('format', $request->format);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('subtitle', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%");
            });
        }

        $books = $query->paginate(12);

        // Get featured authors (those who have published books)
        $featuredAuthors = TeamMember::whereHas('books', function($query) {
                $query->where('is_active', true);
            })
            ->withCount('books')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->limit(4)
            ->get();

        return view('pages.books.index', compact('books', 'featuredAuthors'));
    }

    /**
     * Display the specified book.
     */
    public function show($slug)
    {
        $book = Book::with('teamMember')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $book->increment('views_count');

        // Get related books (same category or same author)
        $relatedBooks = Book::where('is_active', true)
            ->where('id', '!=', $book->id)
            ->where(function($query) use ($book) {
                $query->where('category', $book->category)
                      ->orWhere('team_member_id', $book->team_member_id);
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_date', 'desc')
            ->limit(3)
            ->get();

        return view('pages.books.show', compact('book', 'relatedBooks'));
    }
}
