<?php

namespace App\Imports;

use App\Models\Studio;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudiosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Studio([
            'branch_id'  => $row['branch_id'],
            'name'       => $row['name'],
            'type'       => $row['type'],
            'base_price' => $row['base_price'],
            'capacity'   => $row['capacity'],
        ]);
    }
}
