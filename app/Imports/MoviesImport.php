<?php

namespace App\Imports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MoviesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Movie([
            'title'            => $row['title'],
            'genre'            => $row['genre'] ?? null,
            'duration_minutes' => $row['duration_minutes'],
            'status'           => $row['status'],
            'release_date'     => $row['release_date'] ?? now(),
            'trailer_url'      => $row['trailer_url'] ?? null,
        ]);
    }
}