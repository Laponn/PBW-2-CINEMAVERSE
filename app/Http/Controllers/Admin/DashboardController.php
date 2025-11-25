<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung data untuk widget dashboard
        $totalMovies = Movie::count();
        $totalEarnings = Booking::where('payment_status', 'paid')->sum('total_price');

        return view('admin.dashboard', compact('totalMovies', 'totalEarnings'));
    }
}