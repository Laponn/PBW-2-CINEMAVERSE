<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    // Menampilkan Kursi berdasarkan ID Jadwal
    public function show($id)
    {
        // 1. Ambil Jadwal + Studio + Kursi Fisiknya
        $showtime = Showtime::with(['movie', 'studio.seats'])->findOrFail($id);
        
        // 2. Cek Kursi mana yang sudah laku terjual (Logika Merah/Hijau)
        $bookedSeatIds = Ticket::whereHas('booking', function ($query) use ($id) {
            $query->where('showtime_id', $id)
                  ->whereIn('payment_status', ['paid', 'pending']);
        })->pluck('seat_id')->toArray();

        // 3. Lempar ke View 'seat_selection'
        return view('seat_selection', compact('showtime', 'bookedSeatIds'));
    }
}