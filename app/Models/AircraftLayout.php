<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AircraftLayout extends Model
{
    protected $table = 'm_aircraft_layouts';

    protected $fillable = [
        'aircraft_type',
        'min_row',
        'max_row',
        'available_seats',
    ];
}
