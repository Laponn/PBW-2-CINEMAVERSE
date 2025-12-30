<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class ShowtimeController extends Controller
{
    public function ticket(Movie $movie)
    {
        $branchId = session('selected_branch_id');

        $showtimes = Showtime::with(['studio.branch'])
            ->where('movie_id', $movie->id)
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('studio.branch', function ($b) use ($branchId) {
                    $b->where('id', $branchId);
                });
            })
            ->orderBy('start_time')
            ->get();

        // list film untuk "Film lainnya"
        $movies = Movie::orderByRaw('id = ? desc', [$movie->id])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('usermovie.tiket', compact('movie', 'showtimes', 'movies'));
    }

    public function getDetails($id)
    {
        $showtime = Showtime::with(['studio.seats', 'studio.branch'])->findOrFail($id);

        // Kursi yang sudah diambil untuk showtime ini (pending / paid)
        $occupiedSeats = Ticket::whereHas('booking', function ($q) use ($id) {
                $q->where('showtime_id', $id)
                  ->whereIn('payment_status', ['pending', 'paid']);
            })
            ->pluck('seat_id')
            ->toArray();

        return response()->json([
            'price'       => (float) $showtime->price,
            'studio_name' => $showtime->studio->name,
            'studio_type' => $showtime->studio->type,         // regular / vip
            'branch_name' => $showtime->studio->branch->name,
            'seats'       => $showtime->studio->seats,        // seats: row_label, seat_number, is_usable
            'occupied'    => $occupiedSeats,
        ]);
    }
}
