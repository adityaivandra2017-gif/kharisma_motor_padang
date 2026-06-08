<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_keluar' => ['required', 'date'],
            'onderdil_id' => ['required', 'exists:onderdil,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Tanggal keluar tidak valid.',
            'onderdil_id.required' => 'Onderdil wajib dipilih.',
            'onderdil_id.exists' => 'Onderdil tidak valid.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
        ];
    }
}
