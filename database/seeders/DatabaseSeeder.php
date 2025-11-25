<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StudioSeeder::class,   // 1. Gedung dulu
            MovieSeeder::class,    // 2. Film
            ShowtimeSeeder::class, // 3. Jadwal
        ]);
    }
}