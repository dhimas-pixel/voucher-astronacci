<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crew_name'     => ['required', 'string', 'max:255'],
            'crew_id'       => ['required', 'string', 'max:100'],
            'flight_number' => ['required', 'string'],
            'flight_date'   => ['required', 'date', 'after_or_equal:today'],
            'aircraft_type' => ['required', 'string', 'exists:m_aircraft_layouts,aircraft_type']
        ];
    }

    public function messages(): array
    {
        return [
            'flight_number.required' => 'Nomor penerbangan wajib diisi.',
            'flight_date.required'   => 'Tanggal penerbangan wajib diisi.',
            'flight_date.date'       => 'Format tanggal tidak valid.',
            'flight_date.after_or_equal' => 'Tanggal penerbangan harus setelah tanggal hari ini atau sama dengan hari ini.',
            'crew_name.required'     => 'Nama crew wajib diisi.',
            'crew_id.required'       => 'ID crew wajib diisi.',
            'aircraft_type.required' => 'Tipe pesawat wajib diisi.',
            'aircraft_type.exists'  => 'Tipe pesawat tidak valid.',
        ];
    }
}
