<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PosPickOrder;
use App\Models\PosPickOrderItem;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PosTransactionService
{
    private PriceService $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

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
     * Menggunakan PriceService untuk konsistensi.
     */
    public function calculatePrice($conversion, string $priceTier, float $defaultPrice): float
    {
        if (! $conversion) {
            return $defaultPrice;
        }

        return $this->priceService->getPrice($conversion, $priceTier, $defaultPrice);
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
        $sourceWarehouseId = $data['source_warehouse_id'] ?? (count($items) > 0 ? $items[0]['warehouse_id'] : null);

        $trx = Transaction::create([
            'user_id' => Auth::id(),
            'customer_id' => $data['customer_id'] ?? null,
            'source_warehouse_id' => $sourceWarehouseId,
            'vehicle_id' => $data['vehicle_id'] ?? null,
            'driver_name' => $data['driver_name'] ?? null,
            'total_amount' => $data['total_amount'],
            'paid_amount' => $data['paid_amount'],
            'change_amount' => max(0, $data['paid_amount'] - $data['total_amount']),
            'payment_method' => $data['payment_method'],
            'payment_reference' => $data['payment_reference'] ?? null,
            'sale_type' => $data['sale_type'] ?? 'eceran',
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
    public function validatePosSession(string $type = 'eceran'): void
    {
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', $type)
            ->latest()
            ->first();
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
        if (in_array($paymentMethod, ['transfer', 'qris']) && blank($reference)) {
            $label = $paymentMethod === 'qris' ? 'QRIS' : 'transfer';
            throw new \Exception('ID transaksi ' . $label . ' wajib diisi.');
        }
    }

    /**
     * Get safe error message for frontend display.
     */
    public function getSafeErrorMessage(\Exception $e): string
    {
        $message = $e->getMessage();
        $safeKeywords = ['Produk', 'kredit', 'Stok', 'Pelanggan', 'Jumlah bayar', 'ID transaksi', 'Inkonsistensi', 'Sisa limit', 'Sesi kasir'];

        foreach ($safeKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return $message;
            }
        }

        return 'Transaksi gagal diproses. Silakan coba lagi.';
    }

    /**
     * Buat pick orders otomatis jika ada item dari Gudang Utama.
     * Dipanggil setelah transaksi berhasil disimpan.
     *
     * @param Transaction $trx          Transaksi yang baru selesai
     * @param array       $resolvedItems Items sudah diresolve (dengan warehouse_id, unit_qty, dll)
     * @param string      $posType       'eceran' | 'grosir'
     */
    public function createPickOrdersIfNeeded(Transaction $trx, array $resolvedItems, string $posType = 'eceran'): void
    {
        // Ambil semua gudang utama
        $mainWarehouseIds = Warehouse::where('type', 'utama')->where('active', true)->pluck('id')->toArray();
        if (empty($mainWarehouseIds)) {
            return; // Tidak ada gudang utama yang didefinisikan
        }

        // Filter items yang berasal dari gudang utama, kelompokkan per warehouse
        $itemsByWarehouse = [];
        foreach ($resolvedItems as $item) {
            if (in_array($item['warehouse_id'], $mainWarehouseIds)) {
                $itemsByWarehouse[$item['warehouse_id']][] = $item;
            }
        }

        if (empty($itemsByWarehouse)) {
            return; // Tidak ada item dari gudang utama
        }

        // Buat satu pick order per gudang utama
        foreach ($itemsByWarehouse as $warehouseId => $items) {
            $pickOrder = PosPickOrder::create([
                'pick_number'    => PosPickOrder::generateNumber(),
                'transaction_id' => $trx->id,
                'warehouse_id'   => $warehouseId,
                'status'         => 'pending',
                'pos_type'       => $posType,
                'requested_by'   => Auth::id(),
            ]);

            // Ambil transaction_details yang terkait untuk link
            $trxDetails = $trx->details()->where('warehouse_id', $warehouseId)->get()->keyBy('product_id');

            foreach ($items as $item) {
                $trxDetail = $trxDetails->get($item['product_id']);
                PosPickOrderItem::create([
                    'pick_order_id'        => $pickOrder->id,
                    'transaction_detail_id' => $trxDetail?->id,
                    'product_id'           => $item['product_id'],
                    'quantity'             => $item['quantity'],   // base unit qty
                    'unit_qty'             => $item['unit_qty'],
                    'unit_name'            => $item['unit_name'] ?? null,
                ]);
            }
        }
    }
}

