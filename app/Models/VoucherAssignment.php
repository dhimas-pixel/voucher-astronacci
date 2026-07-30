<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherAssignment extends Model
{
    use HasFactory;

    protected $table = 't_voucher_assignments';

    protected $fillable = [
        'crew_name',
        'crew_id',
        'flight_number',
        'flight_date',
        'aircraft_type',
        'seat_1',
        'seat_2',
        'seat_3',
    ];
}
