<?php

namespace App\Exports;

use App\Models\Studio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudiosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Studio::select(
            'branch_id',
            'name',
            'type',
            'base_price',
            'capacity'
        )->get();
    }

    public function headings(): array
    {
        return [
            'branch_id',
            'name',
            'type',
            'base_price',
            'capacity',
        ];
    }
}
