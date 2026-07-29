<?php

namespace Database\Seeders;

use App\Models\AircraftLayout;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AircraftLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AircraftLayout::insert([
            [
                'aircraft_type' => 'ATR',
                'min_row' => 1,
                'max_row' => 18,
                'available_seats' => 'A,C,D,F'
            ],
            [
                'aircraft_type' => 'Airbus 320',
                'min_row' => 1,
                'max_row' => 32,
                'available_seats' => 'A,B,C,D,E,F'
            ],
            [
                'aircraft_type' => 'Boeing 737 Max',
                'min_row' => 1,
                'max_row' => 32,
                'available_seats' => 'A,B,C,D,E,F'
            ],
        ]);
    }
}
