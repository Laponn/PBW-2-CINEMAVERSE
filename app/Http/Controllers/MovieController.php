<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // list film sedang tayang
    public function index()
    {
        $movies = Movie::where('status', 'now_showing')
            ->latest()
            ->get();

        return view('movies.index', compact('movies'));
    }

    public function show($id)
    {
        $movie = Movie::with(['showtimes.studio.branch'])->findOrFail($id);

    $availableDates = $movie->showtimes()
        ->selectRaw('DATE(start_time) as date')
        ->distinct()
        ->orderBy('date')
        ->pluck('date');

    $showtimes = $movie->showtimes; 

    return view('movies.show', compact('movie', 'availableDates', 'showtimes'));
    }

    public function search(Request $request)
    {
        $q = $request->query('q');

        $movies = Movie::where('status', 'now_showing')
            ->when($q, fn($query) => $query->where('title', 'like', "%{$q}%"))
            ->latest()
            ->get();

        return view('movies.index', compact('movies', 'q'));
    }

    // public coming soon
    public function comingSoon()
    {
        $movies = Movie::where('status', 'coming_soon')
            ->orderBy('release_date', 'asc')
            ->get();

        // Pastikan nama file view sesuai
        return view('movies.coming-soon', compact('movies'));
        // kalau file kamu masih cominSoon.blade.php:
        // return view('movies.cominSoon', compact('movies'));
    }
}
