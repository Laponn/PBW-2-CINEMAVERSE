<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $guarded = ['id'];

    // Satu cabang punya banyak studio
    public function studios()
    {
        return $this->hasMany(Studio::class);
    }
}
