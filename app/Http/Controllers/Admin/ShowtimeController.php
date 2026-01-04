<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class ShowtimeController extends Controller
{
    public function ticket(Movie $movie)
    {
        // Ambil branch_id dari session (hasil pilihan di navbar)
        $branchId = session('branch_id');

        $query = Showtime::with(['studio.branch'])
            ->where('movie_id', $movie->id)
            ->where('start_time', '>=', now()->subHours(2)); // Beri toleransi 2 jam agar jadwal yang baru mulai tetap muncul

        // Jika ada cabang dipilih, filter berdasarkan cabang tersebut
        if ($branchId) {
            $query->whereHas('studio', function ($s) use ($branchId) {
                $s->where('branch_id', $branchId);
            });
        }

        $showtimes = $query->orderBy('start_time')->get();

        // Ambil tanggal unik untuk tombol pilihan tanggal
        $availableDates = $showtimes->pluck('start_time')
            ->map(fn($val) => Carbon::parse($val)->format('Y-m-d'))
            ->unique()
            ->values();

        $movies = Movie::where('id', '!=', $movie->id)
            ->where('status', 'now_showing')
            ->latest()->take(6)->get();

        return view('user.tiket', compact('movie', 'showtimes', 'availableDates', 'movies'));
    }

    public function getDetails($id)
    {
        // Pastikan relasi 'seats' dan 'branch' terpanggil
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