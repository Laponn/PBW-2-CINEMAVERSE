<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Booking extends Model
{
    // Biar gampang (mass assignment aman)
   protected $fillable = ['user_id', 'showtime_id', 'booking_code', 'total_price', 'payment_status'];

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
