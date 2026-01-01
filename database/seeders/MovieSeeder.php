<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $movies = [
            [
                'title' => 'Spiderman: No Way Home',
                'description' => 'Identitas Spider-Man terungkap, Peter meminta bantuan Doctor Strange untuk membuat orang lupa, namun mantra itu malah membuka gerbang multiverse.',
                'duration_minutes' => 148,
                'release_date' => '2021-12-15',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=JfVOs4VSpmA',
                'genre' => 'Action, Adventure, Sci-Fi',
                'status' => 'now_showing'
            ],
            [
                'title' => 'The Batman',
                'description' => 'Batman mengungkap korupsi di Gotham City yang menghubungkan keluarganya sendiri dengan pembunuh berantai Riddler.',
                'duration_minutes' => 176,
                'release_date' => '2022-03-04',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=mqqft239XC6',
                'genre' => 'Crime, Drama, Mystery',
                'status' => 'now_showing'
            ],
            [
                'title' => 'Inception',
                'description' => 'Seorang pencuri yang mencuri rahasia melalui teknologi berbagi mimpi diberikan tugas untuk menanamkan ide ke dalam pikiran seseorang.',
                'duration_minutes' => 148,
                'release_date' => '2010-07-16',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/edv5CZvWj09upOsy2Y6IwDhK8bt.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
                'genre' => 'Sci-Fi, Action, Adventure',
                'status' => 'now_showing'
            ],
            [
                'title' => 'Interstellar',
                'description' => 'Sekelompok astronot melewati lubang cacing di luar angkasa dalam upaya untuk memastikan kelangsungan hidup umat manusia.',
                'duration_minutes' => 169,
                'release_date' => '2014-11-07',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
                'genre' => 'Sci-Fi, Drama, Adventure',
                'status' => 'now_showing'
            ],
            [
                'title' => 'John Wick: Chapter 4',
                'description' => 'John Wick menemukan jalan untuk mengalahkan High Table, namun ia harus menghadapi musuh baru dengan aliansi kuat di seluruh dunia.',
                'duration_minutes' => 169,
                'release_date' => '2023-03-24',
                'poster_url' => 'https://image.tmdb.org/t/p/w500/vZloFAK7NmvMGKE7VkF5UHaz0I.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=qEVUtrk8_B4',
                'genre' => 'Action, Thriller, Crime',
                'status' => 'now_showing'
            ]
        ];

        foreach ($movies as $movie) {
            Movie::updateOrCreate(
                ['title' => $movie['title']],
                $movie
            );
        }
    }
}
