<?php

namespace App\Imports;

use App\Models\alternatif;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class DataImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new alternatif([
            'C1' => $row['c1'],
            'C2' => $row['c2'],
            'C3' => $row['c3'],
            'C4' => $row['c4'],
            'C5' => $row['c5'],
        ]);
    }
}
