<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    // Agar kolom bisa diisi (Mass Assignment)
   protected $fillable = ['title', 'description', 'duration_minutes', 'release_date', 'poster_url', 'trailer_url', 'genre', 'status']; // Menambahkan genre & trailer

    // Relasi: Satu Film punya BANYAK Jadwal Tayang
    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }
}