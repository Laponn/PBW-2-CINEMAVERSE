<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Studio;
use App\Models\Seat;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Cabang
        $branch = Branch::create([
            'name' => 'CinemaVerse Jakarta Pusat',
            'city' => 'Jakarta',
            'address' => 'Jl. MH Thamrin No. 1'
        ]);

        // 2. Buat Studio Regular (10 Baris x 12 Kolom)
        $studioRegular = Studio::create([
            'branch_id' => $branch->id,
            'name' => 'Studio 1 (Regular)',
            'type' => 'regular',
            'base_price' => 40000,
            'capacity' => 120
        ]);
        $this->generateSeats($studioRegular, 10, 12);

        // 3. Buat Studio VIP (5 Baris x 8 Kolom)
        $studioVIP = Studio::create([
            'branch_id' => $branch->id,
            'name' => 'The Premiere (VIP)',
            'type' => 'vip',
            'base_price' => 100000,
            'capacity' => 40
        ]);
        $this->generateSeats($studioVIP, 5, 8);
    }

    // Fungsi Generate Kursi (A1, A2, ... B1, B2...)
    private function generateSeats($studio, $rows, $cols)
    {
        $rowLabels = range('A', 'Z'); // A sampai Z

        for ($r = 0; $r < $rows; $r++) {
            $currentRowLabel = $rowLabels[$r]; // Ambil huruf baris (A, B, dst)

            for ($c = 1; $c <= $cols; $c++) {
                Seat::create([
                    'studio_id' => $studio->id,
                    'row_label' => $currentRowLabel,
                    'seat_number' => $c,
                    'is_usable' => true 
                ]);
            }
        }
    }
}