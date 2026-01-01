<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //

    protected $fillable = ['name', 'city', 'address', 'latitude', 'longitude'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // Satu cabang punya banyak studio
    public function studios()
    {
        return $this->hasMany(Studio::class);
    }
}
