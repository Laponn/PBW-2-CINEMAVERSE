<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MovieController extends Controller
{
    public function index()
    {
        // Ganti 'selected_branch_id' menjadi 'branch_id' agar sinkron dengan modal
        $branchId = Session::get('branch_id');
        
        $query = Movie::where('status', 'now_showing');

        if ($branchId) {
            $query->whereHas('showtimes.studio', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $movies = $query->latest()->get();
        $featuredMovie = $movies->first(); 

        return view('welcome', compact('movies', 'featuredMovie'));
    }

    public function show($id)
    {
        // Pastikan key session sama: 'branch_id'
        $branchId = session('branch_id');

        $movie = Movie::with(['showtimes' => function($query) use ($branchId) {
            
            /** * CATATAN DEBUG: 
             * Jika di database showtimes Anda adalah tanggal lama (2025), 
             * baris 'now()' di bawah ini akan menghilangkan data tersebut.
             * Hapus sementara baris ini jika ingin mengetes data lama.
             */
            $query->where('start_time', '>=', now());

            if ($branchId) {
                $query->whereHas('studio', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
            
            $query->orderBy('start_time', 'asc')
                  ->with('studio.branch');

        }])->findOrFail($id);

        return view('movies.show', compact('movie'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $movies = Movie::where('title', 'like', "%{$query}%")->get();

        return view('search', compact('movies', 'query'));
    }
    public function ticket($id)
{
    $movie = Movie::findOrFail($id);
    $branchId = session('branch_id'); // Mengambil ID cabang dari session navigasi

    // 1. Ambil semua showtimes untuk film ini di cabang yang dipilih
    $showtimes = \App\Models\Showtime::where('movie_id', $id)
        ->whereHas('studio', function($q) use ($branchId) {
            if ($branchId) {
                $q->where('branch_id', $branchId);
            }
        })
        ->where('start_time', '>=', now()) // Pastikan jadwal belum terlewat
        ->with(['studio.branch'])
        ->orderBy('start_time', 'asc')
        ->get();

    // 2. Ekstrak tanggal unik untuk bagian "01 Pilih Tanggal"
    // Ini adalah solusi untuk error 'Undefined variable $availableDates'
    $availableDates = $showtimes->pluck('start_time')
        ->map(function($val) {
            return \Carbon\Carbon::parse($val)->format('Y-m-d');
        })
        ->unique()
        ->values();

    // 3. Pastikan semua variabel dikirim ke view
    return view('user.tiket', compact('movie', 'showtimes', 'availableDates'));
}
public function getShowtimeDetails($id)
{
    $showtime = \App\Models\Showtime::with(['studio.branch', 'studio.seats'])->findOrFail($id);
    
    // Ambil ID kursi yang sudah dipesan untuk jadwal ini
    $occupiedSeats = \App\Models\Ticket::whereHas('booking', function($q) {
            $q->where('payment_status', 'paid');
        })
        ->where('showtime_id', $id)
        ->pluck('seat_id')
        ->toArray();

    return response()->json([
        'price' => $showtime->price,
        'studio_name' => $showtime->studio->name,
        'branch_name' => $showtime->studio->branch->name,
        'seats' => $showtime->studio->seats, // Semua kursi di studio tersebut
        'occupied' => $occupiedSeats // Kursi yang sudah lunas
    ]);
}
}