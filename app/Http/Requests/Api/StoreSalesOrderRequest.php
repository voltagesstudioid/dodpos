<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Form Request untuk API Sales Order (Pasgar mobile app).
 * Mengembalikan JSON error response agar kompatibel dengan Flutter.
 */
class StoreSalesOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi ditangani middleware role:pasgar,admin_sales,admin
    }

    public function rules(): array
    {
        return [
            'customer_id'        => 'required|exists:customers,id',
            'order_type'         => 'required|in:preorder,canvas',
            'payment_method'     => 'required|string|in:tunai,transfer,kredit',
            'vehicle_id'         => 'required_if:order_type,canvas|nullable|exists:vehicles,id',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
            'notes'              => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'        => 'Pelanggan harus dipilih.',
            'customer_id.exists'          => 'Pelanggan tidak ditemukan.',
            'order_type.required'         => 'Tipe order harus diisi (preorder/canvas).',
            'order_type.in'               => 'Tipe order harus preorder atau canvas.',
            'payment_method.required'     => 'Metode pembayaran harus dipilih.',
            'payment_method.in'           => 'Metode pembayaran tidak valid.',
            'vehicle_id.required_if'      => 'Kendaraan harus dipilih untuk order kanvas.',
            'vehicle_id.exists'           => 'Kendaraan tidak ditemukan.',
            'items.required'              => 'Item pesanan tidak boleh kosong.',
            'items.min'                   => 'Minimal 1 item harus ada dalam pesanan.',
            'items.*.product_id.required' => 'Produk tidak valid.',
            'items.*.product_id.exists'   => 'Produk tidak ditemukan.',
            'items.*.quantity.min'        => 'Jumlah item minimal 1.',
            'items.*.price.min'           => 'Harga tidak boleh negatif.',
        ];
    }

    /**
     * Override failedValidation agar mengembalikan JSON (bukan redirect HTML).
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'status'  => 'error',
                'message' => 'Data tidak valid.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
