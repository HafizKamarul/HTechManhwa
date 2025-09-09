<?php

namespace App\Http\Controllers;

use App\Models\Manhwa;
use App\Models\Genre;
use Illuminate\Http\Request;

class ManhwaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Manhwa::with(['genres', 'chapters']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        // Genre filter
        if ($request->has('genre') && $request->genre) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort', 'title');
        $sortOrder = $request->get('order', 'asc');
        
        if ($sortBy === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'popular') {
            $query->orderBy('views', 'desc');
        } elseif ($sortBy === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $manhwas = $query->paginate(20);
        $genres = Genre::orderBy('name')->get();

        return view('manhwas.index', compact('manhwas', 'genres'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Manhwa $manhwa)
    {
        $manhwa->load(['genres', 'chapters' => function ($query) {
            $query->orderBy('chapter_number');
        }]);

        // Increment view count
        $manhwa->increment('views');

        return view('manhwas.show', compact('manhwa'));
    }
}
