<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MoviesExport;
use App\Imports\MoviesImport;

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
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required',
            'duration_minutes' => 'required|integer',
            'genre'            => 'nullable|string',
            'poster'           => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status'           => 'required|in:now_showing,coming_soon,ended',
            'trailer_url'      => 'nullable|string',
        ]);

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        }

        // Simpan data (release_date otomatis diatur ke hari ini)
        $validated['release_date'] = now();
        Movie::create($validated);

        return redirect()->route('admin.movies.index')->with('success', 'Film baru berhasil ditambahkan!');
    }

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required',
            'duration_minutes' => 'required|integer',
            'genre'            => 'nullable|string',
            'poster'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'           => 'required|in:now_showing,coming_soon,ended',
            'trailer_url'      => 'nullable|string',
        ]);

        if ($request->hasFile('poster')) {
            // Hapus file lama jika ada di storage lokal
            if ($movie->poster_url && !str_starts_with($movie->poster_url, 'http')) {
                $oldPath = str_replace('/storage/', '', $movie->poster_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan poster baru
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster_url'] = '/storage/' . $path;
        }

        // Update semua data yang sudah divalidasi
        $movie->update($validated);

        return redirect()->route('admin.movies.index')->with('success', 'Data film "' . $movie->title . '" berhasil diperbarui!');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->poster_url && !str_starts_with($movie->poster_url, 'http')) {
            $path = str_replace('/storage/', '', $movie->poster_url);
            Storage::disk('public')->delete($path);
        }

        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dihapus dari katalog.');
    }
    public function export()
{
    return Excel::download(new MoviesExport, 'movies.xlsx');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    Excel::import(new MoviesImport, $request->file('file'));

    return redirect()
        ->route('admin.movies.index')
        ->with('success', 'Data film berhasil diimport.');
}
}