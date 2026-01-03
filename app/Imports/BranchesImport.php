<?php

namespace App\Imports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BranchesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Branch([
            'name'      => $row['nama_cabang'],
            'city'      => $row['kota'],
            'address'   => $row['alamat_lengkap'],
            'latitude'  => $row['latitude'],
            'longitude' => $row['longitude'],
        ]);
    }
}