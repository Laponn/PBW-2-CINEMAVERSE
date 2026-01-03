<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class UpcomingMovieController extends Controller
{
    public function comingSoon()
    {
        $movies = Movie::where('status', 'coming_soon')
            ->orderBy('release_date', 'asc')
            ->get();

        return view('movies.coming-soon', compact('movies'));
    }

     public function create()
    {
        return view('admin.movies.create');
    }

    // ======================
    // ADMIN - STORE
    // ======================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'poster_url' => 'nullable|url',
            'trailer_url' => 'nullable|url',
            'genre' => 'nullable|string|max:100',
            'status' => 'required|in:now_showing,coming_soon,ended',
        ]);

        Movie::create($validated);

        return redirect()
            ->route('admin.movies.create')
            ->with('success', 'Movie berhasil ditambahkan');
    }


}
