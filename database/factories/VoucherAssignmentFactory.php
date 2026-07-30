<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VoucherAssignment>
 */
class VoucherAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'crew_name'     => fake()->name(),
            'crew_id'       => fake()->numerify('CRW-###'),
            'flight_number' => fake()->bothify('??-###'),
            'flight_date'   => fake()->date(),
            'aircraft_type' => 'ATR',
            'seat_1'        => '1A',
            'seat_2'        => '1B',
            'seat_3'        => '1C',
        ];
    }
}
