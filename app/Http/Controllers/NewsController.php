<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('pages.news.index', compact('news'));
    }

    public function show($slug)
    {
        $article = News::where('slug', $slug)
            ->published()
            ->firstOrFail();

        return view('pages.news.show', compact('article'));
    }
}
