<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    protected $fillable = [
        'nomor_rak',
        'keterangan',
        'status',
    ];
}