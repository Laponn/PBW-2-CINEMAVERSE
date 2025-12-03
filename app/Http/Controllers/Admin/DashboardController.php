<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Hanya admin yang boleh
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $totalMovies   = Movie::count();
        $totalEarnings = Booking::where('payment_status', 'paid')->sum('total_price');

        return view('admin.dashboard', compact('totalMovies', 'totalEarnings'));
    }
}
