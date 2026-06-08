<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EoqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'onderdil_id' => ['required', 'exists:onderdil,id'],
            'kebutuhan_tahunan' => ['required', 'integer', 'min:1'],
            'biaya_pemesanan' => ['required', 'integer', 'min:1'],
            'biaya_penyimpanan' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'onderdil_id.required' => 'Onderdil wajib dipilih.',
            'onderdil_id.exists' => 'Onderdil tidak valid.',
            'kebutuhan_tahunan.required' => 'Kebutuhan tahunan wajib diisi.',
            'kebutuhan_tahunan.integer' => 'Kebutuhan tahunan harus berupa angka.',
            'kebutuhan_tahunan.min' => 'Kebutuhan tahunan minimal 1.',
            'biaya_pemesanan.required' => 'Biaya pemesanan wajib diisi.',
            'biaya_pemesanan.integer' => 'Biaya pemesanan harus berupa angka.',
            'biaya_pemesanan.min' => 'Biaya pemesanan minimal 1.',
            'biaya_penyimpanan.required' => 'Biaya penyimpanan wajib diisi.',
            'biaya_penyimpanan.integer' => 'Biaya penyimpanan harus berupa angka.',
            'biaya_penyimpanan.min' => 'Biaya penyimpanan minimal 1.',
        ];
    }
}
