<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Studio;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    // MENAMPILKAN DAFTAR JADWAL
    public function index()
    {
        // Load relasi movie dan studio (beserta branch-nya) untuk ditampilkan
        $showtimes = Showtime::with(['movie', 'studio.branch'])
            ->orderBy('start_time', 'desc') // Urutkan dari jadwal terbaru
            ->get();
            
        return view('admin.showtimes.index', compact('showtimes'));
    }

    // FORM TAMBAH JADWAL
    public function create()
    {
        // Hanya tampilkan film yang statusnya 'now_showing' atau 'coming_soon'
        $movies = Movie::whereIn('status', ['now_showing', 'coming_soon'])->get();
        
        // Ambil studio beserta cabang untuk dropdown grouping
        $studios = Studio::with('branch')->get();
        
        return view('admin.showtimes.create', compact('movies', 'studios'));
    }

    // PROSES SIMPAN JADWAL
    public function store(Request $request)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'studio_id'  => 'required|exists:studios,id',
            'start_time' => 'required|date|after:now', // Jadwal harus masa depan
            'price'      => 'required|numeric|min:0',
        ]);

        // 1. Ambil data film untuk tahu durasinya
        $movie = Movie::findOrFail($request->movie_id);
        
        // 2. Hitung Waktu Selesai (Start + Durasi Film + 10 menit bersih-bersih)
        $startTime = Carbon::parse($request->start_time);
        $endTime   = $startTime->copy()->addMinutes($movie->duration_minutes + 10);

        // 3. Simpan
        Showtime::create([
            'movie_id'   => $request->movie_id,
            'studio_id'  => $request->studio_id,
            'start_time' => $startTime,
            'end_time'   => $endTime,
            'price'      => $request->price,
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Jadwal tayang berhasil dibuat!');
    }

    // FORM EDIT JADWAL
    public function edit(Showtime $showtime)
    {
        $movies = Movie::all(); // Tampilkan semua film untuk edit
        $studios = Studio::with('branch')->get();
        
        return view('admin.showtimes.edit', compact('showtime', 'movies', 'studios'));
    }

    // PROSES UPDATE JADWAL
    public function update(Request $request, Showtime $showtime)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'studio_id'  => 'required|exists:studios,id',
            'start_time' => 'required|date',
            'price'      => 'required|numeric|min:0',
        ]);

        // Hitung ulang end_time jika film atau jam berubah
        $movie = Movie::findOrFail($request->movie_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime   = $startTime->copy()->addMinutes($movie->duration_minutes + 10);

        $showtime->update([
            'movie_id'   => $request->movie_id,
            'studio_id'  => $request->studio_id,
            'start_time' => $startTime,
            'end_time'   => $endTime,
            'price'      => $request->price,
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Jadwal tayang berhasil diperbarui!');
    }

    // HAPUS JADWAL
    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        return redirect()->route('admin.showtimes.index')->with('success', 'Jadwal tayang dihapus');
    }
}