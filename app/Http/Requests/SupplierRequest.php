<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier')?->id;

        return [
            'nama_supplier' => [
                'required',
                'string',
                'max:150',
                Rule::unique('supplier', 'nama_supplier')->ignore($supplierId),
            ],
            'kontak' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.unique' => 'Nama supplier sudah terdaftar.',
        ];
    }
}
