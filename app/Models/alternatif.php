<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alternatif extends Model
{
    use HasFactory;
    protected $fillable = [
        'C1',
        'C2',
        'C3',
        'C4',
        'C5',
    ];

    public $timestamps = false;
}
