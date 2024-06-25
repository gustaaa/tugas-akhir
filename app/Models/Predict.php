<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predict extends Model
{
    use HasFactory;
    protected $fillable = [
        'C1',
        'C2',
        'C3',
        'C4',
    ];

    public $timestamps = true;
}
