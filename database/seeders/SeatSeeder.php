<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Studio;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        // contoh: per baris 10 kursi
        $perRow = 10;

        foreach (Studio::all() as $studio) {
            // hapus dulu biar tidak dobel saat seed ulang (opsional)
            Seat::where('studio_id', $studio->id)->delete();

            $capacity = (int) $studio->capacity;
            if ($capacity <= 0) continue;

            $rowsNeeded = (int) ceil($capacity / $perRow);

            $seatCount = 0;
            for ($r = 0; $r < $rowsNeeded; $r++) {
                $rowLabel = chr(ord('A') + $r); // A, B, C...

                for ($n = 1; $n <= $perRow; $n++) {
                    if ($seatCount >= $capacity) break;

                    Seat::create([
                        'studio_id' => $studio->id,
                        'row_label' => $rowLabel,
                        'seat_number' => $n,
                        'is_usable' => true,
                    ]);

                    $seatCount++;
                }
            }
        }
    }
}
