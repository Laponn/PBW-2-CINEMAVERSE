<?php

namespace App\Http\Controllers; // Namespace harus Controller

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
        $movie = Movie::with(['showtimes.studio'])->findOrFail($id);
        return view('movie_detail', compact('movie'));
    }
    public function search(Request $request)
{
    $query = $request->get('q');

    $movies = Movie::where('title', 'like', "%{$query}%")->get();

    return view('search', compact('movies', 'query'));
}

}