<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MoviesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Movie::select(
            'id',
            'title',
            'genre',
            'duration_minutes',
            'status',
            'release_date',
            'trailer_url'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Genre',
            'Duration (Minutes)',
            'Status',
            'Release Date',
            'Trailer URL',
        ];
    }
}