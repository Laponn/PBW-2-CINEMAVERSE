<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        // updateOrCreate akan mengecek: Apakah email ini ada?
        // Jika ADA: Update datanya (password/nama/role).
        // Jika TIDAK ADA: Buat baru.
        User::updateOrCreate(
            ['email' => 'admin@cv.com'], // Kunci pencarian (Search Key)
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Akun User Biasa
        User::updateOrCreate(
            ['email' => 'pal@cv.com'], // Kunci pencarian
            [
                'name' => 'Karbito Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
    }
}