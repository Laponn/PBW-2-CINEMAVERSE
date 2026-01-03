<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComingSoonMovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('status', 'coming_soon')
            ->orderBy('release_date', 'asc')
            ->get();

        return view('admin.comingsoon-movie.index', compact('movies'));
    }

    public function create()
    {
        return view('admin.comingsoon-movie.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'release_date'     => 'required|date',
            'genre'            => 'nullable|string|max:255',
            'poster'           => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'trailer_url'      => 'nullable|url|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        }

        $validated['status'] = 'coming_soon';

        Movie::create($validated);

        return redirect()
            ->route('admin.comingsoon-movie.index')
            ->with('success', 'Film coming soon berhasil ditambahkan!');
    }

    public function edit(Movie $comingsoon_movie)
    {
        if ($comingsoon_movie->status !== 'coming_soon') {
            abort(404);
        }

        $movie = $comingsoon_movie;
        return view('admin.comingsoon-movie.edit', compact('movie'));
    }

    public function update(Request $request, Movie $comingsoon_movie)
    {
        if ($comingsoon_movie->status !== 'coming_soon') {
            abort(404);
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'release_date'     => 'required|date',
            'genre'            => 'nullable|string|max:255',
            'poster'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'trailer_url'      => 'nullable|url|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            if ($comingsoon_movie->poster_url && !str_starts_with($comingsoon_movie->poster_url, 'http')) {
                $oldPath = str_replace('/storage/', '', $comingsoon_movie->poster_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        }

        $validated['status'] = 'coming_soon';

        $comingsoon_movie->update($validated);

        return redirect()
            ->route('admin.comingsoon-movie.index')
            ->with('success', 'Film coming soon berhasil diperbarui!');
    }

    public function destroy(Movie $comingsoon_movie)
    {
        if ($comingsoon_movie->status !== 'coming_soon') {
            abort(404);
        }

        if ($comingsoon_movie->poster_url && !str_starts_with($comingsoon_movie->poster_url, 'http')) {
            $path = str_replace('/storage/', '', $comingsoon_movie->poster_url);
            Storage::disk('public')->delete($path);
        }

        $comingsoon_movie->delete();

        return redirect()
            ->route('admin.comingsoon-movie.index')
            ->with('success', 'Film coming soon berhasil dihapus!');
    }

    public function show(Movie $comingsoon_movie)
    {
        abort(404);
    }
}
