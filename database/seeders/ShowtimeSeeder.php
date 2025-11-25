<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Studio;
use App\Models\Showtime;

class ShowtimeSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil Film Pertama
        $movie = Movie::first();
        
        // Ambil Studio Regular & VIP
        $studioRegular = Studio::where('type', 'regular')->first();
        $studioVIP = Studio::where('type', 'vip')->first();

        // Pastikan Data Ada Sebelum Buat Jadwal (Mencegah Error Null)
        if ($movie && $studioRegular) {
            Showtime::create([
                'movie_id' => $movie->id,
                'studio_id' => $studioRegular->id,
                'start_time' => now()->setTime(14, 00),
                'end_time' => now()->setTime(16, 30),
                'price' => 45000
            ]);
        }

        if ($movie && $studioVIP) {
            Showtime::create([
                'movie_id' => $movie->id,
                'studio_id' => $studioVIP->id,
                'start_time' => now()->setTime(19, 00),
                'end_time' => now()->setTime(21, 30),
                'price' => 100000
            ]);
        }
    }
}