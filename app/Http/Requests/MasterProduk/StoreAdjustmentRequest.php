<?php

namespace App\Http\Requests\MasterProduk;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('view_penyesuaian_stok') ?? false;
    }

    public function rules(): array
    {
        return [
            'product_id'   => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'tipe'         => 'required|in:masuk,koreksi',
            'jumlah'       => 'nullable|numeric|min:0',
            'unit_id'      => 'nullable|integer',
            'items'        => 'nullable|array',
            'items.*.unit_id' => 'nullable|exists:units,id',
            'items.*.jumlah'  => 'nullable|numeric|min:0',
            'items.*.factor'  => 'nullable|numeric|min:0.0001',
            'keterangan'   => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk wajib dipilih.',
            'warehouse_id.required' => 'Gudang wajib dipilih.',
            'tipe.required' => 'Tipe penyesuaian wajib dipilih.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah harus lebih dari 0.',
        ];
    }
}
