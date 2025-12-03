<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // Menampilkan SEMUA Film (Homepage)
    public function index()
    {
        // 1. Ambil film yang sedang tayang
        $movies = Movie::where('status', 'now_showing')->get();
        
        // 2. Tentukan Featured Movie (Misalnya ambil film pertama dari list)
        // Jika list kosong, featuredMovie jadi null (biar gak error)
        $featuredMovie = $movies->first(); 

        // 3. Kirim KEDUA variabel ke view 'welcome'
        return view('welcome', compact('movies', 'featuredMovie'));
    }

    // Menampilkan SATU Film (Detail)
    public function show($id)
    {
        $movie = Movie::with(['showtimes.studio'])->findOrFail($id);
        return view('movie_detail', compact('movie'));
    }

    // Pencarian Film
    public function search(Request $request)
    {
        $query = $request->get('q');
        $movies = Movie::where('title', 'like', "%{$query}%")->get();

        return view('search', compact('movies', 'query'));
    }
}