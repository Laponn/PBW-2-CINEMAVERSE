<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MovieController extends Controller
{
    // Menampilkan SEMUA Film (Homepage)
    public function index()
    {
    // 1. Ambil ID cabang yang dipilih dari Session
        $selectedBranchId = Session::get('selected_branch_id');
        
        // 2. Query Dasar: Film yang statusnya 'now_showing'
        $query = Movie::where('status', 'now_showing');

        // 3. Jika user sudah memilih cabang, filter filmnya
        if ($selectedBranchId) {
            $query->whereHas('showtimes.studio', function($q) use ($selectedBranchId) {
                $q->where('branch_id', $selectedBranchId);
            });
        }

        // 4. Ambil datanya
        $movies = $query->latest()->get();

        // 5. Cek apakah ada film 'featured' (opsional, ambil acak satu)
        $featuredMovie = $movies->first(); 

        return view('welcome', compact('movies', 'featuredMovie'));
    }

    // Menampilkan SATU Film (Detail)
    public function show($id)
    {
       $selectedBranchId = session('selected_branch_id');

    // Ambil Movie beserta Showtimes-nya
    // Tapi filter Showtimes-nya berdasarkan Cabang yang dipilih
    $movie = Movie::with(['showtimes' => function($query) use ($selectedBranchId) {
        
        // 1. Hanya jadwal masa depan
        $query->where('start_time', '>=', now());

        // 2. Filter berdasarkan cabang (jika ada)
        if ($selectedBranchId) {
            $query->whereHas('studio', function($q) use ($selectedBranchId) {
                $q->where('branch_id', $selectedBranchId);
            });
        }
        
        // 3. Urutkan waktu & load data studio+branch
        $query->orderBy('start_time', 'asc')
              ->with('studio.branch');

    }])->findOrFail($id);

    return view('movies.show', compact('movie'));
    }

    // Pencarian Film
    public function search(Request $request)
    {
        $query = $request->get('q');
        $movies = Movie::where('title', 'like', "%{$query}%")->get();

        return view('search', compact('movies', 'query'));
    }
}