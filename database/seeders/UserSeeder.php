<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@cinemaverse.com',
            'password' => Hash::make('password'), // Passwordnya: password
            'role' => 'admin',
        ]);

        // Buat Akun User Biasa (Contoh)
        User::create([
            'name' => 'Budi Customer',
            'email' => 'user@cinemaverse.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}