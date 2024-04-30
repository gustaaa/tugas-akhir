<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingData extends Model
{
    use HasFactory;

    protected $table = 'training_data'; // Sesuaikan dengan nama tabel yang sesuai dengan migrasi

    protected $fillable = [
        'data', // Kolom yang dapat diisi dengan mass assignment
    ];

    protected $casts = [
        'data' => 'array', // Mengkonversi kolom 'matrix' menjadi tipe data array saat diambil dari database
    ];
}
