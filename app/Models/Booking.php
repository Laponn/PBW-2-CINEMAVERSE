<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    // Biar gampang (mass assignment aman)
    protected $guarded = ['id'];

    /**
     * Booking ini milik user siapa
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Booking ini untuk jadwal (showtime) apa
     */
    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    /**
     * Detail tiket/kursi yang dipesan
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
