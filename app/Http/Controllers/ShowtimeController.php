<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    /**
     * Halaman tiket – menampilkan daftar jadwal film
     */
    public function show($movie)
    {
        $branchId = session('selected_branch_id');

        $showtimes = Showtime::with(['studio.branch'])
            ->where('movie_id', $movie->id)
            ->when($branchId, function ($query) use ($branchId) {
                $query->whereHas('studio.branch', function ($q) use ($branchId) {
                    $q->where('id', $branchId);
                });
            })
            ->orderBy('start_time')
            ->get();

        return view('tiket', compact('movie', 'showtimes'));
    }
}

