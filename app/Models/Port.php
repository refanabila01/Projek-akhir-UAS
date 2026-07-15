<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $fillable = [
        'name',
        'code',
        'country_code',
        'latitude',
        'longitude',
        'congestion_status',
        'wpi_number',
        'region',
        'delay_hours',
    ];
}
