<?php

namespace App\Http\Requests\MasterProduk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('edit_master_satuan') ?? false;
    }

    public function rules(): array
    {
        $unitId = $this->route('satuan');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('units', 'name')->ignore($unitId)],
            'abbreviation' => ['required', 'string', 'max:20', Rule::unique('units', 'abbreviation')->ignore($unitId)],
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
