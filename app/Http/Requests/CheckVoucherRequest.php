<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckVoucherRequest extends FormRequest
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
            'flight_number' => ['required', 'string'],
            'flight_date'   => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'flight_number.required' => 'Nomor penerbangan wajib diisi.',
            'flight_date.required'   => 'Tanggal penerbangan wajib diisi.',
            'flight_date.date'       => 'Format tanggal tidak valid.',
            'flight_date.after_or_equal' => 'Tanggal penerbangan harus setelah tanggal hari ini atau sama dengan hari ini.',
        ];
    }
}
