<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Showtime extends Model
{
    protected $guarded = ['id'];

    // Relasi ke Movie (Jadwal ini memutar film apa?)
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    // Relasi ke Studio (Jadwal ini main di ruangan mana?)
    // <-- INI YANG BIKIN ERROR TADI
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    // Relasi ke Booking (Untuk cek kursi yang sudah dibooking)
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}