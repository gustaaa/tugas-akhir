<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    use HasFactory;

    protected $table = 'bobot'; // Sesuaikan dengan nama tabel yang sesuai dengan migrasi

    protected $fillable = [
        'matrix', // Kolom yang dapat diisi dengan mass assignment
    ];

    protected $casts = [
        'matrix' => 'array', // Mengkonversi kolom 'matrix' menjadi tipe data array saat diambil dari database
    ];
}
