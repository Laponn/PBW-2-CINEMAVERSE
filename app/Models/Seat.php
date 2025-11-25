<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}