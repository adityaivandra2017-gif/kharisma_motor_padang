<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'onderdil_id' => ['required', 'exists:onderdil,id'],
            'lead_time' => ['required', 'integer', 'min:1'],
            'kebutuhan_per_hari' => ['required', 'integer', 'min:1'],
            'safety_stock' => ['required', 'integer', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'onderdil_id.required' => 'Onderdil wajib dipilih.',
            'onderdil_id.exists' => 'Onderdil tidak valid.',
            'lead_time.required' => 'Lead time wajib diisi.',
            'lead_time.integer' => 'Lead time harus berupa angka.',
            'lead_time.min' => 'Lead time minimal 1 hari.',
            'kebutuhan_per_hari.required' => 'Kebutuhan per hari wajib diisi.',
            'kebutuhan_per_hari.integer' => 'Kebutuhan per hari harus berupa angka.',
            'kebutuhan_per_hari.min' => 'Kebutuhan per hari minimal 1.',
            'safety_stock.required' => 'Safety stock wajib diisi.',
            'safety_stock.integer' => 'Safety stock harus berupa angka.',
            'safety_stock.min' => 'Safety stock minimal 0.',
        ];
    }
}
