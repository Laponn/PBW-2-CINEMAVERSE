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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEED USER (Admin & Customer)
        // Pastikan role sesuai dengan migrasi ('admin', 'user')
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

        // 2. SEED BRANCHES (Lengkap dengan Lat/Lng untuk Peta)
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
            // Setiap cabang dibuatkan 2 studio (Regular & VIP)
            // base_price ditambahkan untuk menghindari Error 1364
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
            
            // Catatan: Jika Model Studio memiliki fungsi booted(), 
            // maka kursi A1-E10 akan otomatis terbuat di sini.
        }

        // 4. SEED MOVIES (Memanggil MovieSeeder yang berisi 12 film)
        $this->call(MovieSeeder::class);

        // 5. SEED SHOWTIMES (Jadwal Tayang Otomatis)
        $allMovies = Movie::all();
        $allStudios = Studio::all();
        
        // Membuat jadwal untuk hari ini dan besok agar web tidak kosong
        $dates = [Carbon::now()->format('Y-m-d'), Carbon::tomorrow()->format('Y-m-d')];
        $times = ['13:00', '15:30', '18:15', '21:00'];

        foreach ($allMovies as $movie) {
            // Ambil 2 studio secara acak untuk setiap film agar jadwal tersebar
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