<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'admin_email',
        'admin_phone',
        'maintenance_mode',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
    ];

    /**
     * Get the single settings row, creating a default one if it
     * doesn't exist yet.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'company_name'     => 'DC Visitor',
            'admin_email'      => null,
            'admin_phone'      => null,
            'maintenance_mode' => false,
        ]);
    }
}
