<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_masuk' => ['required', 'date'],
            'onderdil_id' => ['required', 'exists:onderdil,id'],
            'supplier_id' => ['required', 'exists:supplier,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Tanggal masuk tidak valid.',
            'onderdil_id.required' => 'Onderdil wajib dipilih.',
            'onderdil_id.exists' => 'Onderdil tidak valid.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier tidak valid.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
        ];
    }
}
