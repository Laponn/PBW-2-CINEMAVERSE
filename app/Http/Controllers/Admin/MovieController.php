<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    // Helper kecil buat cek admin
    private function ensureAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->ensureAdmin();

        $movies = Movie::latest()->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $request->validate([
            'title'             => 'required',
            'description'       => 'required',
            'duration_minutes'  => 'required|integer',
            'poster'            => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status'            => 'required',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Movie::create([
            'title'            => $request->title,
            'description'      => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'release_date'     => now(),
            'poster_url'       => $posterPath ? '/storage/' . $posterPath : null,
            'trailer_url'      => $request->trailer_url,
            'status'           => $request->status,
        ]);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil disimpan!');
    }

    public function edit(Movie $movie)
    {
        $this->ensureAdmin();

        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $this->ensureAdmin();

        $request->validate([
            'title'  => 'required',
            'poster' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $data['poster_url'] = '/storage/' . $path;
        }

        $movie->update($data);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil diupdate!');
    }

    public function destroy(Movie $movie)
    {
        $this->ensureAdmin();

        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Film dihapus!');
    }
}
