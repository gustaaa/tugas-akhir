<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\User::insert([
            [
                'name' => 'Gustania Nirmala',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Administrator',
                'gambar' => 'images/team-img-02.JPG',
            ],
            [
                'name' => 'Gustania Nirmala',
                'username' => 'operator',
                'email' => 'operator@admin.com',
                'password' => Hash::make('password'),
                'role' => 'Operator',
                'gambar' => 'images/team-img-04.JPG',
            ],
        ]);
    }
}
