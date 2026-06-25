<?php

namespace App\Http\Requests\MasterProduk;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create_master_satuan') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:units,name',
            'abbreviation' => 'required|string|max:20|unique:units,abbreviation',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama satuan wajib diisi.',
            'name.unique' => 'Nama satuan sudah digunakan.',
            'abbreviation.required' => 'Singkatan wajib diisi.',
            'abbreviation.unique' => 'Singkatan sudah digunakan.',
        ];
    }
}
