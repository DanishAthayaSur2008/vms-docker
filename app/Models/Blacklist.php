<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $fillable = [
        'nama',
        'no_hp',
        'email',
        'alasan',
        'blacklisted_by',
    ];

    public function blacklistedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'blacklisted_by');
    }
}