<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_usable' => 'boolean',
    ];

    // Kursi ini milik studio mana?
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    // Kursi ini pernah dipakai di tiket mana saja?
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // Opsional: biar gampang tampil "A1", "B12", dll
    public function getLabelAttribute(): string
    {
        return $this->row_label . $this->seat_number;
    }
}
