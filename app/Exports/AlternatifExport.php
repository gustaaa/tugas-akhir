<?php

namespace App\Exports;

use App\Models\alternatif;
use Maatwebsite\Excel\Concerns\FromCollection;

class AlternatifExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return alternatif::Select(
            'id',

            'C1',
            'C2',
            'C3',
            'C4',
            'C5',

        )->get();
    }
    public function headings(): array
    {
        return [
            'ID',

            'C1',
            'C2',
            'C3',
            'C4',
            'C5',

        ];
    }
}
