<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan Dashboard Personal User (Sisi Tiket)
     */
    public function dashboard()
    {
        // Mengambil data user yang sedang login saat ini
        $user = Auth::user();

        // Eager Load relasi untuk riwayat booking agar performa cepat
        $user->load([
            'bookings.showtime.movie', 
            'bookings.showtime.studio.branch'
        ]);

        // Mengirim data user ke view dashboard personal
        return view('user.dashboard', compact('user'));
    }
}