<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_id' => 'nullable|exists:units,id',
            'conversion_factor' => 'nullable|numeric|min:1',
            'type' => 'required|in:po,transfer',
            'notes' => 'nullable|string|max:1000',
            // from_warehouse_id and to_warehouse_id are auto-assigned by controller
        ];
    }
}
