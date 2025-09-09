<?php

namespace App\Http\Controllers;

use App\Models\Manhwa;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestManhwas = Manhwa::with(['genres', 'chapters'])
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        $popularManhwas = Manhwa::with(['genres', 'chapters'])
            ->orderBy('views', 'desc')
            ->limit(12)
            ->get();

        $genres = Genre::orderBy('name')->get();

        return view('home', compact('latestManhwas', 'popularManhwas', 'genres'));
    }
}
