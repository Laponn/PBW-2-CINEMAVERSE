<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Studio;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED USER (Admin & Customer)
        User::updateOrCreate(['email' => 'admin@cv.com'], [
            'name' => 'Admin CinemaVerse',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::updateOrCreate(['email' => 'pal@cv.com'], [
            'name' => 'Karbit Customer',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        // 2. SEED BRANCHES
        $branches = [
            [
                'name' => 'CinemaVerse Bandung Telkom',
                'city' => 'Bandung',
                'address' => 'Jl. Terusan Buah Batu, Bojongsoang, Kec. Bojongsoang, Kabupaten Bandung',
                'latitude' => -6.974,
                'longitude' => 107.632,
            ],
            [
                'name' => 'CinemaVerse Jakarta Pusat',
                'city' => 'Jakarta',
                'address' => 'Jl. MH Thamrin No.1, Menteng, Jakarta Pusat',
                'latitude' => -6.186,
                'longitude' => 106.822,
            ],
            [
                'name' => 'CinemaVerse Surabaya Tunjungan',
                'city' => 'Surabaya',
                'address' => 'Jl. Tunjungan No.65, Genteng, Surabaya',
                'latitude' => -7.259,
                'longitude' => 112.738,
            ]
        ];

        foreach ($branches as $b) {
            $branch = Branch::updateOrCreate(['name' => $b['name']], $b);

            // 3. SEED STUDIOS per Cabang
            Studio::updateOrCreate(['name' => 'Studio 1', 'branch_id' => $branch->id], [
                'type' => 'regular',
                'base_price' => 35000, 
                'capacity' => 50
            ]);

            Studio::updateOrCreate(['name' => 'Studio 2 (VIP)', 'branch_id' => $branch->id], [
                'type' => 'vip',
                'base_price' => 60000,
                'capacity' => 30
            ]);
        }

        // 4. SEED MOVIES
        $this->call(MovieSeeder::class);

        // 5. SEED SHOWTIMES (DIPERBAIKI UNTUK TANGGAL 5 - 20 JANUARI)
        $allMovies = Movie::all();
        $allStudios = Studio::all();
        
        // Logika pembuatan rentang tanggal 5 sampai 20 Januari 2026
        $dates = [];
        $startDate = Carbon::create(2026, 1, 5); // Mulai 5 Jan
        for ($i = 0; $i <= 15; $i++) { // Looping selama 15 hari ke depan (sampai 20 Jan)
            $dates[] = $startDate->copy()->addDays($i)->format('Y-m-d');
        }

        $times = ['13:00', '15:30', '18:15', '21:00'];

        foreach ($allMovies as $movie) {
            // Ambil 2 studio secara acak untuk setiap film
            $randomStudios = $allStudios->random(2);
            
            foreach ($randomStudios as $studio) {
                foreach ($dates as $date) {
                    foreach ($times as $time) {
                        Showtime::create([
                            'movie_id' => $movie->id,
                            'studio_id' => $studio->id,
                            'start_time' => Carbon::parse("$date $time"),
                            'price' => ($studio->type === 'vip') ? ($studio->base_price + 15000) : $studio->base_price,
                            'end_time' => Carbon::parse("$date $time")->addMinutes($movie->duration_minutes + 20),
                        ]);
                    }
                }
            }
        }
    }
}