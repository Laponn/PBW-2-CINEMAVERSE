<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingMovie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'release_date',
        'poster_url',
        'trailer_url',
        'genre',
        'status',
    ];
}
