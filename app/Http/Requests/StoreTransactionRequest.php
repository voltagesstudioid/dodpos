<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk transaksi POS (Kasir Eceran & Grosir).
 * Memusatkan semua aturan validasi di satu tempat.
 */
class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi sudah ditangani oleh middleware role
    }

    public function rules(): array
    {
        return [
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
            'items.*.subtotal'   => 'required|numeric|min:0',
            'total_amount'       => 'required|numeric|min:0',
            'paid_amount'        => 'required|numeric|min:0',
            'payment_method'     => 'required|string|in:tunai,transfer,qris,debit,kredit',
            'customer_id'        => 'nullable|exists:customers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'              => 'Keranjang belanja tidak boleh kosong.',
            'items.min'                   => 'Minimal 1 item harus ada dalam transaksi.',
            'items.*.product_id.required' => 'Produk tidak valid.',
            'items.*.product_id.exists'   => 'Produk tidak ditemukan di database.',
            'items.*.quantity.min'        => 'Jumlah item minimal 1.',
            'items.*.price.min'           => 'Harga tidak boleh negatif.',
            'total_amount.required'       => 'Total belanja harus diisi.',
            'total_amount.min'            => 'Total belanja tidak boleh negatif.',
            'paid_amount.required'        => 'Jumlah bayar harus diisi.',
            'paid_amount.min'             => 'Jumlah bayar tidak boleh negatif.',
            'payment_method.required'     => 'Metode pembayaran harus dipilih.',
            'payment_method.in'           => 'Metode pembayaran tidak valid.',
            'customer_id.exists'          => 'Pelanggan tidak ditemukan.',
        ];
    }

    /**
     * Tambahan validasi setelah rules() — cek paid_amount vs total_amount.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $paymentMethod = $this->input('payment_method');
            $paidAmount    = (float) $this->input('paid_amount', 0);
            $totalAmount   = (float) $this->input('total_amount', 0);

            // Untuk non-kredit: bayar harus >= total
            if ($paymentMethod !== 'kredit' && $paidAmount < $totalAmount) {
                $validator->errors()->add(
                    'paid_amount',
                    'Jumlah bayar (Rp ' . number_format($paidAmount, 0, ',', '.') .
                    ') tidak boleh kurang dari total belanja (Rp ' . number_format($totalAmount, 0, ',', '.') . ').'
                );
            }

            // Untuk kredit: customer_id wajib
            if ($paymentMethod === 'kredit' && !$this->input('customer_id')) {
                $validator->errors()->add(
                    'customer_id',
                    'Pelanggan harus dipilih untuk pembayaran kredit.'
                );
            }
        });
    }
}
