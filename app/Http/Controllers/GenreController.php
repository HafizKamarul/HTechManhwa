<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of genres.
     */
    public function index()
    {
        $genres = Genre::withCount('manhwas')
            ->orderBy('name')
            ->get();

        return view('genres.index', compact('genres'));
    }

    /**
     * Show the specified genre and its manhwas.
     */
    public function show(Genre $genre, Request $request)
    {
        $query = $genre->manhwas()->with(['genres', 'chapters']);

        // Search functionality within genre
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort', 'title');
        
        if ($sortBy === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'popular') {
            $query->orderBy('views', 'desc');
        } elseif ($sortBy === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy($sortBy, 'asc');
        }

        $manhwas = $query->paginate(20);
        $allGenres = Genre::orderBy('name')->get();

        return view('genres.show', compact('genre', 'manhwas', 'allGenres'));
    }
}
