<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    // Biar Ticket::create() jalan
    protected $fillable = ['booking_id', 'seat_id', 'price'];

    /**
     * Tiket ini milik booking apa
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Tiket ini untuk bangku mana
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}
