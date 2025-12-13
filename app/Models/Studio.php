<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Studio extends Model
{
    protected $guarded = ['id'];

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