<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosTransactionService
{
    /**
     * Validate and lock products for transaction.
     * Returns keyed collection of products with pessimistic lock.
     *
     * @param array $productIds
     * @return \Illuminate\Support\Collection
     */
    public function lockAndLoadProducts(array $productIds): \Illuminate\Support\Collection
    {
        // Sort IDs to prevent deadlock
        sort($productIds);

        return Product::with(['unitConversions.unit', 'productStocks.warehouse'])
            ->whereIn('id', $productIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
    }

    /**
     * Lock customer record for credit transaction.
     */
    public function lockCustomer(int $customerId): ?\App\Models\Customer
    {
        return \App\Models\Customer::lockForUpdate()->find($customerId);
    }

    /**
     * Calculate price based on price tier and conversion.
     */
    public function calculatePrice($conversion, string $priceTier, float $defaultPrice): float
    {
        if (! $conversion) {
            return $defaultPrice;
        }

        return match ($priceTier) {
            'eceran' => $conversion->sell_price_ecer,
            'grosir' => ($conversion->sell_price_grosir > 0 ? $conversion->sell_price_grosir : $conversion->sell_price_ecer),
            'jual1' => (($conversion->sell_price_jual1 ?? 0) > 0 ? $conversion->sell_price_jual1 : $conversion->sell_price_ecer),
            'jual2' => (($conversion->sell_price_jual2 ?? 0) > 0 ? $conversion->sell_price_jual2 : $conversion->sell_price_ecer),
            'jual3' => (($conversion->sell_price_jual3 ?? 0) > 0 ? $conversion->sell_price_jual3 : $conversion->sell_price_ecer),
            default => $conversion->sell_price_ecer,
        };
    }

    /**
     * Deduct warehouse stock using FIFO.
     * Creates stock movement records.
     *
     * @throws \Exception
     */
    public function deductWarehouseStock(
        int $productId,
        int $warehouseId,
        int $qty,
        int $transactionId,
        string $productName,
        string $posType // 'eceran' or 'grosir'
    ): void {
        $remaining = $qty;

        $stocks = ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('stock', '>', 0)
            ->orderBy('expired_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) {
                break;
            }

            $deduct = min($stock->stock, $remaining);
            $stock->stock -= $deduct;
            $stock->save();

            // Catat ke StockMovement
            $referencePrefix = $posType === 'grosir' ? 'POS-GROSIR' : 'POS-ECERAN';
            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'location_id' => $stock->location_id,
                'type' => 'out',
                'source_type' => 'pos_transaction',
                'reference_number' => $referencePrefix . '-' . $transactionId,
                'batch_number' => $stock->batch_number,
                'expired_date' => $stock->expired_date,
                'quantity' => $deduct,
                'balance' => $stock->stock,
                'notes' => '[' . strtoupper($posType) . '] Transaksi #' . $transactionId,
                'user_id' => Auth::id(),
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            throw new \Exception('Inkonsistensi data: Stok detail gudang untuk produk "' . $productName . '" tidak mencukupi, kurang ' . $remaining . ' pcs.');
        }
    }

    /**
     * Create customer credit record for debt transactions.
     */
    public function createCustomerCredit(
        int $customerId,
        int $transactionId,
        float $debtAmount,
        string $description
    ): void {
        \App\Models\CustomerCredit::create([
            'credit_number' => \App\Models\CustomerCredit::generateNumber('debt'),
            'customer_id' => $customerId,
            'transaction_id' => $transactionId,
            'type' => 'debt',
            'transaction_date' => today(),
            'due_date' => today()->addDays(30),
            'amount' => $debtAmount,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'description' => $description,
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Create transaction with details.
     *
     * @param array $data
     * @param array $items
     * @return Transaction
     */
    public function createTransaction(array $data, array $items): Transaction
    {
        $trx = Transaction::create([
            'user_id' => Auth::id(),
            'customer_id' => $data['customer_id'] ?? null,
            'total_amount' => $data['total_amount'],
            'paid_amount' => $data['paid_amount'],
            'change_amount' => max(0, $data['paid_amount'] - $data['total_amount']),
            'payment_method' => $data['payment_method'],
            'payment_reference' => $data['payment_reference'] ?? null,
            'status' => 'completed',
        ]);

        foreach ($items as $item) {
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'product_id' => $item['product_id'],
                'warehouse_id' => $item['warehouse_id'],
                'unit_conversion_id' => $item['unit_conversion_id'] ?? null,
                'unit_qty' => $item['unit_qty'] ?? null,
                'unit_name' => $item['unit_name'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        return $trx;
    }

    /**
     * Check if POS session is active.
     *
     * @throws \Exception
     */
    public function validatePosSession(): void
    {
        $activeSession = \App\Models\PosSession::where('status', 'open')->latest()->first();
        if (! $activeSession) {
            throw new \Exception('Sesi kasir sudah ditutup. Silakan refresh halaman atau buka sesi baru.');
        }
    }

    /**
     * Validate payment amount.
     *
     * @throws \Exception
     */
    public function validatePayment(string $paymentMethod, float $paidAmount, float $totalAmount): void
    {
        if ($paymentMethod !== 'kredit' && $paidAmount < $totalAmount) {
            throw new \Exception('Jumlah bayar tidak boleh kurang dari total belanja (Rp ' . number_format($totalAmount, 0, ',', '.') . ').');
        }
    }

    /**
     * Validate credit limit.
     *
     * @throws \Exception
     */
    public function validateCreditLimit(float $debtAmount, $customer): void
    {
        if ($debtAmount > 0 && $customer->credit_limit > 0 && $customer->remaining_credit_limit < $debtAmount) {
            throw new \Exception('Sisa limit kredit tidak mencukupi untuk transaksi ini.');
        }
    }

    /**
     * Validate transfer reference.
     *
     * @throws \Exception
     */
    public function validateTransferReference(string $paymentMethod, ?string $reference): void
    {
        if ($paymentMethod === 'transfer' && blank($reference)) {
            throw new \Exception('ID transaksi transfer wajib diisi.');
        }
    }

    /**
     * Get safe error message for frontend display.
     */
    public function getSafeErrorMessage(\Exception $e): string
    {
        $message = $e->getMessage();
        $safeKeywords = ['Produk', 'kredit', 'Stok', 'Pelanggan', 'Jumlah bayar', 'ID transaksi', 'Inkonsistensi', 'Sisa limit'];

        foreach ($safeKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return $message;
            }
        }

        return 'Transaksi gagal diproses. Silakan coba lagi.';
    }
}
