<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Movie;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function ticket(Movie $movie)
    {
        $branchId = session('selected_branch_id');

        // ambil semua showtime untuk film ini
        $showtimes = Showtime::with(['studio.branch'])
            ->where('movie_id', $movie->id)
            ->when($branchId, function ($query) use ($branchId) {
                $query->whereHas('studio.branch', function ($q) use ($branchId) {
                    $q->where('id', $branchId);
                });
            })
            ->orderBy('start_time')
            ->get();

        // ⚠ PENTING: pakai ->get() supaya hasilnya Collection, bukan Query Builder
        $movies = Movie::orderByRaw('id = ? desc', [$movie->id])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('usermovie.tiket', [
            'movie'     => $movie,
            'showtimes' => $showtimes,
            'movies'    => $movies,
        ]);
    }
}