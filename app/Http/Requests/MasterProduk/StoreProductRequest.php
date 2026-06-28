<?php

namespace App\Http\Requests\MasterProduk;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create_master_produk') ?? false;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();

        // Strip dots from main numeric fields
        $fieldsToStrip = ['price', 'purchase_price', 'stock', 'min_stock'];
        foreach ($fieldsToStrip as $field) {
            if (isset($input[$field])) {
                $input[$field] = (float) str_replace('.', '', (string) $input[$field]);
            }
        }

        // Strip dots from units array
        if (isset($input['units']) && is_array($input['units'])) {
            $unitFields = [
                'purchase_price', 'sell_price_ecer', 'sell_price_grosir',
                'sell_price_jual1', 'sell_price_jual2', 'sell_price_jual3', 'sell_price_minimal'
            ];
            foreach ($input['units'] as $idx => $unit) {
                foreach ($unitFields as $uf) {
                    if (isset($unit[$uf])) {
                        $input['units'][$idx][$uf] = (float) str_replace('.', '', (string) $unit[$uf]);
                    }
                }
            }
        }

        $this->replace($input);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'sku' => 'nullable|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'units' => 'nullable|array',
            'units.*.unit_id' => 'required|exists:units,id',
            'units.*.conversion_factor' => 'required|numeric|min:0.0001',
            'units.*.purchase_price' => 'required|numeric|min:0',
            'units.*.sell_price_ecer' => 'required|numeric|min:0',
            'units.*.sell_price_grosir' => 'required|numeric|min:0',
            'units.*.sell_price_jual1' => 'nullable|numeric|min:0',
            'units.*.sell_price_jual2' => 'nullable|numeric|min:0',
            'units.*.sell_price_jual3' => 'nullable|numeric|min:0',
            'units.*.sell_price_minimal' => 'nullable|numeric|min:0',
            'units.*.is_base_unit' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'price.required' => 'Harga jual wajib diisi.',
            'price.numeric' => 'Harga jual harus berupa angka.',
            'sku.unique' => 'SKU sudah digunakan.',
            'barcode.unique' => 'Barcode sudah digunakan.',
        ];
    }
}
