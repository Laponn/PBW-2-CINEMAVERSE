<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        Movie::create([
            'title' => 'Avengers: Secret Wars',
            'description' => 'Pertarungan multiverse dimulai.',
            'duration_minutes' => 150,
            'release_date' => now(),
            'poster_url' => 'https://via.placeholder.com/300x450',
            'trailer_url' => 'dQw4w9WgXcQ', // ID Youtube
            'status' => 'now_showing'
        ]);

        Movie::create([
            'title' => 'Inception 2',
            'description' => 'Mimpi di dalam mimpi.',
            'duration_minutes' => 120,
            'release_date' => now(),
            'poster_url' => 'https://via.placeholder.com/300x450',
            'trailer_url' => 'YoHD9XEInc0',
            'status' => 'now_showing'
        ]);
    }
}