<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bias extends Model
{
    use HasFactory;

    protected $table = 'bias'; // Sesuaikan dengan nama tabel yang sesuai dengan migrasi

    protected $fillable = [
        'data', // Kolom yang dapat diisi dengan mass assignment
    ];

    protected $casts = [
        'data' => 'array', // Mengkonversi kolom 'matrix' menjadi tipe data array saat diambil dari database
    ];
}
