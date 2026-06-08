<?php

namespace App\Http\Requests;

use App\Models\Onderdil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OnderdilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $onderdilId = $this->route('onderdil')?->id;

        return [
            'kode_onderdil' => [
                'required',
                'string',
                'max:50',
                Rule::unique('onderdil', 'kode_onderdil')->ignore($onderdilId),
            ],
            'nama_onderdil' => ['required', 'string', 'max:150'],
            'jenis' => ['required', 'string', 'max:80'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:1'],
            'supplier_id' => ['required', 'exists:supplier,id'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_onderdil.required' => 'Kode onderdil wajib diisi.',
            'kode_onderdil.unique' => 'Kode onderdil sudah digunakan.',
            'nama_onderdil.required' => 'Nama onderdil wajib diisi.',
            'jenis.required' => 'Jenis onderdil wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok_minimum.required' => 'Batas stok minimum wajib diisi.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier tidak valid.',
        ];
    }
}
