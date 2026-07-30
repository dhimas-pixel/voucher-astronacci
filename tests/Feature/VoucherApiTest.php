<?php

namespace Tests\Feature;

use App\Http\Resources\GenerateVoucherResource;
use App\Models\VoucherAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherApiTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_can_check_valid_flight()
    {
        $response = $this->postJson('/api/check', [
            'flight_number' => 'GA-123',
            'flight_date'   => '2027-08-31',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status'  => true,
                'message' => 'Voucher tersedia!',
                'data' => [
                    'exists' => false
                ]
            ]);
    }

    public function test_can_check_invalid_flight()
    {
        VoucherAssignment::factory()->create([
            'flight_number' => 'GA-123',
            'flight_date'   => '2026-12-31',
        ]);

        $response = $this->postJson('/api/check', [
            'flight_number' => 'GA-123',
            'flight_date'   => '2026-12-31',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status'  => false,
                'message' => 'Nomor Voucher & Tanggal Penerbangan sudah ada untuk penerbangan ini.',
                'data' => [
                    'exists' => true
                ]
            ]);
    }

    public function test_cannot_back_date_flight()
    {
        $response = $this->postJson('/api/check', [
            'flight_number' => 'GA-123',
            'flight_date'   => '2025-12-31',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status'  => false,
                'message' => 'Tanggal penerbangan harus setelah tanggal hari ini atau sama dengan hari ini.',
            ]);
    }

    public function test_required_form_field_flight()
    {
        $response = $this->postJson('/api/check', []);

        $response->assertStatus(422)
            ->assertJson([
                'status'  => false,
            ]);
    }

    public function test_can_generate_voucher()
    {
        $response = $this->postJson('/api/generate', [
            'crew_name'     => 'John Doe',
            'crew_id'       => '1234567890',
            'flight_number' => 'GA-123',
            'flight_date'   => '2027-08-31',
            'aircraft_type' => 'ATR',
        ]);

        $response->assertStatus(201)

            ->assertJson([
                'status'  => true,
                'message' => 'Voucher berhasil di-generate!',
            ])
            ->assertJsonStructure([
                'data' => [
                    'seats'
                ]
            ])
            ->assertJsonCount(3, 'data.seats');
        $this->assertDatabaseHas('t_voucher_assignments', [
            'crew_name'     => 'John Doe',
            'crew_id'       => '1234567890',
            'flight_number' => 'GA-123',
        ]);
    }
}
