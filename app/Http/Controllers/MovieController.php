<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    // detail film (punya kamu: movies/show.blade.php)
    public function show($id)
{
    $movie = Movie::with(['showtimes.studio.branch'])
        ->findOrFail($id);

    // Ambil semua showtime
    $showtimes = $movie->showtimes;

    // Ambil tanggal unik dari start_time
    $availableDates = $showtimes
        ->map(fn ($st) => Carbon::parse($st->start_time)->format('Y-m-d'))
        ->unique()
        ->values();

    return view('movies.show', compact(
        'movie',
        'showtimes',
        'availableDates'
    ));
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
