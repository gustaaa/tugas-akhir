<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::Select('id', 'name', 'username', 'email', 'password', 'role')->get();
    }
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Username',
            'Email',
            'Password',
            'Role'
        ];
    }
}
