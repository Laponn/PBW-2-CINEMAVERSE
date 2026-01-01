<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Ticket;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    public function ticket(Movie $movie)
    {
        // Gunakan 'branch_id' agar sinkron dengan navigasi navbar
        $branchId = session('branch_id');

        $showtimes = Showtime::with(['studio.branch'])
            ->where('movie_id', $movie->id)
            ->where('start_time', '>=', now()) // Hanya jadwal masa depan
            ->when($branchId, function ($q) use ($branchId) {
                $q->whereHas('studio', function ($s) use ($branchId) {
                    $s->where('branch_id', $branchId);
                });
            })
            ->orderBy('start_time')
            ->get();

        // Ambil tanggal unik untuk tombol pilihan tanggal
        $availableDates = $showtimes->pluck('start_time')
            ->map(fn($val) => Carbon::parse($val)->format('Y-m-d'))
            ->unique()
            ->values();

        // List film lainnya untuk rekomendasi di bawah
        $movies = Movie::where('id', '!=', $movie->id)
            ->where('status', 'now_showing')
            ->latest()
            ->take(6)
            ->get();

        return view('user.tiket', compact('movie', 'showtimes', 'availableDates', 'movies'));
    }

    public function getDetails($id)
    {
        $showtime = Showtime::with(['studio.seats', 'studio.branch'])->findOrFail($id);

        // Kursi terisi (hanya yang sudah dibayar atau pending)
        $occupiedSeats = Ticket::whereHas('booking', function ($q) use ($id) {
                $q->where('showtime_id', $id)
                  ->whereIn('payment_status', ['pending', 'paid']);
            })
            ->pluck('seat_id')
            ->toArray();

        return response()->json([
            'price'       => (float) $showtime->price,
            'studio_name' => $showtime->studio->name,
            'studio_type' => $showtime->studio->type,
            'branch_name' => $showtime->studio->branch->name,
            'seats'       => $showtime->studio->seats,
            'occupied'    => $occupiedSeats,
        ]);
    }
}