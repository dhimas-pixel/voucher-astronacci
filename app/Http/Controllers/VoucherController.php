<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckVoucherRequest;
use App\Http\Requests\GenerateVoucherRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\CheckVoucherResource;
use App\Http\Resources\GenerateVoucherResource;
use App\Models\AircraftLayout;
use App\Models\VoucherAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function check(CheckVoucherRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $isVoucherExists = VoucherAssignment::query()
                ->where('flight_number', $validated['flight_number'])
                ->where('flight_date', $validated['flight_date'])
                ->exists();

            if ($isVoucherExists) {
                return (new ApiResponseResource([
                    'status'  => false,
                    'message' => 'Nomor Voucher & Tanggal Penerbangan sudah ada untuk penerbangan ini.',
                    'data'    => new CheckVoucherResource(['exists' => true])
                ]))->response()->setStatusCode(400);
            }

            return (new ApiResponseResource([
                'status'  => true,
                'message' => 'Voucher tersedia!',
                'data'    => new CheckVoucherResource(['exists' => false])
            ]))->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.',
                'data'    => null
            ], 500);
        }
    }

    public function generate(GenerateVoucherRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $isVoucherExists = VoucherAssignment::query()
                ->where('flight_number', $validated['flight_number'])
                ->where('flight_date', $validated['flight_date'])
                ->exists();

            if ($isVoucherExists) {
                return (new ApiResponseResource([
                    'status'  => 'error',
                    'message' => 'Nomor Voucher & Tanggal Penerbangan sudah ada untuk penerbangan ini.',
                    'data'    => null
                ]))->response()->setStatusCode(400);
            }

            $generatedSeats = $this->generateUniqueSeats(
                $validated['aircraft_type'],
                $validated['flight_number'],
                $validated['flight_date']
            );

            $voucher = VoucherAssignment::create([
                'crew_name'     => $validated['crew_name'],
                'crew_id'       => $validated['crew_id'],
                'flight_number' => $validated['flight_number'],
                'flight_date'   => $validated['flight_date'],
                'aircraft_type' => $validated['aircraft_type'],
                'seat_1'        => $generatedSeats[0],
                'seat_2'        => $generatedSeats[1],
                'seat_3'        => $generatedSeats[2],
            ]);

            return (new ApiResponseResource([
                'status'  => 'success',
                'message' => 'Voucher berhasil di-generate!',
                'data'    => new GenerateVoucherResource(['seats' => $generatedSeats])
            ]))->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan pada server saat generate voucher.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function generateUniqueSeats(string $aircraftType, string $flightNumber, string $flightDate): array
    {
        $layout = AircraftLayout::query()
            ->where('aircraft_type', $aircraftType)
            ->first();

        if (!$layout) {
            throw new \Exception("Tipe pesawat tidak ditemukan di database.");
        }

        $minRow = $layout->min_row;
        $maxRow = $layout->max_row;

        $cleanSeats = str_replace([',', ' '], '', $layout->available_seats);
        $letters = str_split($cleanSeats);

        $bookedSeats = VoucherAssignment::query()
            ->where('flight_number', $flightNumber)
            ->where('flight_date', $flightDate)
            ->get(['seat_1', 'seat_2', 'seat_3'])
            ->flatMap(function ($assignment) {
                return [$assignment->seat_1, $assignment->seat_2, $assignment->seat_3];
            })
            ->filter()
            ->toArray();

        $seats = [];
        $maxAttempts = 1000;
        $attempts = 0;

        while (count($seats) < 3 && $attempts < $maxAttempts) {
            $attempts++;

            $row = rand($minRow, $maxRow);
            $letter = $letters[array_rand($letters)];
            $seatNumber = $row . $letter;

            if (!in_array($seatNumber, $seats) && !in_array($seatNumber, $bookedSeats)) {
                $seats[] = $seatNumber;
            }
        }

        if (count($seats) < 3) {
            throw new \Exception('Pesawat sudah penuh, tidak dapat men-generate 3 kursi unik.');
        }

        return $seats;
    }
}
