<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // Menampilkan SEMUA Film (Homepage)
    public function index()
    {
        $movies = Movie::where('status', 'now_showing')->get();
        return view('welcome', compact('movies'));
    }

    // Menampilkan SATU Film (Detail)
    public function show($id)
    {
        // Kita butuh relasi showtimes (jadwal) karena ditampilkan di halaman detail film
        $movie = Movie::with(['showtimes.studio'])->findOrFail($id);
        return view('movie_detail', compact('movie'));
    }
}