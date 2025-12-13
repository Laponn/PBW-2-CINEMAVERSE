<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Studio;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Hanya admin yang boleh
       if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Hitung Data
        $totalMovies   = Movie::count();
        $totalEarnings = Booking::where('payment_status', 'paid')->sum('total_price');
        
        // Data Baru
        $totalBranches = Branch::count();
        $totalStudios  = Studio::count();

        return view('admin.dashboard', compact(
            'totalMovies', 
            'totalEarnings', 
            'totalBranches', 
            'totalStudios'
        ));
    }
}
