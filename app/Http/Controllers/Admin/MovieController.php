<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::latest()->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'duration_minutes' => 'required|integer',
            'poster' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'status' => 'required'
        ]);

        // Upload Gambar
        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Movie::create([
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'release_date' => now(), // Default hari ini
            'poster_url' => $posterPath ? '/storage/' . $posterPath : null,
            'trailer_url' => $request->trailer_url,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil disimpan!');
    }

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required',
            'poster' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('poster')) {
            // Hapus gambar lama (Optional, jika mau hemat storage)
            // if($movie->poster_url) {
            //      $oldPath = str_replace('/storage/', '', $movie->poster_url);
            //      Storage::disk('public')->delete($oldPath);
            // }

            $path = $request->file('poster')->store('posters', 'public');
            $data['poster_url'] = '/storage/' . $path;
        }

        $movie->update($data);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil diupdate!');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Film dihapus!');
    }
}