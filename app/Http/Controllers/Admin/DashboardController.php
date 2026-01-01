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
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Data untuk Kartu Statistik
        $totalMovies   = Movie::count();
        $totalEarnings = Booking::where('payment_status', 'paid')->sum('total_price');
        $totalBranches = Branch::count();
        $totalStudios  = Studio::count();

        // Ambil 5 film terbaru saja (untuk ringkasan, bukan index)
        $movies = Movie::latest()->take(5)->get(); 

        return view('admin.dashboard', compact(
            'totalMovies', 
            'totalEarnings', 
            'totalBranches', 
            'totalStudios',
            'movies'
        ));
    }
}