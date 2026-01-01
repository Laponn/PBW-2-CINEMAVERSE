<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Studio extends Model
{
    protected $fillable = ['branch_id', 'name', 'type', 'base_price', 'capacity']; // Menambahkan base_price

    protected static function booted()
    {
        static::created(function ($studio) {
            $rows = ['A', 'B', 'C', 'D', 'E'];
            foreach ($rows as $row) {
                for ($i = 1; $i <= 10; $i++) {
                    $studio->seats()->create([
                        'row_label' => $row,
                        'seat_number' => $i,
                        'is_usable' => true,
                    ]);
                }
            }
        });
    }

    // Relasi ke Cabang
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    // Relasi ke Kursi (Satu studio punya BANYAK kursi)

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}