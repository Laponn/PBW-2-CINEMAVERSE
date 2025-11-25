<?php

use App\Models\Branch;
use App\Models\Studio;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Cabang Contoh
        $branch = Branch::create([
            'name' => 'CinemaVerse Jakarta Pusat',
            'city' => 'Jakarta',
            'address' => 'Jl. MH Thamrin No. 1'
        ]);

        // 2. Buat Studio Regular (10 Baris x 12 Kolom = 120 Kursi)
        $studioRegular = Studio::create([
            'branch_id' => $branch->id,
            'name' => 'Studio 1 (Regular)',
            'type' => 'regular',
            'base_price' => 40000,
            'capacity' => 120
        ]);

        // Generate Kursi Regular (A-J)
        $this->generateSeats($studioRegular, 10, 12);

        // 3. Buat Studio VIP (5 Baris x 8 Kolom = 40 Kursi)
        $studioVIP = Studio::create([
            'branch_id' => $branch->id,
            'name' => 'The Premiere (VIP)',
            'type' => 'vip',
            'base_price' => 100000,
            'capacity' => 40
        ]);

        // Generate Kursi VIP (A-E)
        $this->generateSeats($studioVIP, 5, 8);
    }

    // Fungsi Otomatis Generate Kursi
    private function generateSeats($studio, $rows, $cols)
    {
        $rowLabels = range('A', 'Z'); // Array Huruf A-Z

        for ($r = 0; $r < $rows; $r++) {
            $currentRowLabel = $rowLabels[$r]; // Ambil Huruf (A, B, C...)

            for ($c = 1; $c <= $cols; $c++) {
                Seat::create([
                    'studio_id' => $studio->id,
                    'row_label' => $currentRowLabel,
                    'seat_number' => $c,
                    'is_usable' => true // Default bagus
                ]);
            }
        }
    }
}
