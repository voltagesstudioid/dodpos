<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\StoreSetting;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KasirEceranController extends Controller
{
    public function index()
    {
        $activeSession = \App\Models\PosSession::where('status', 'open')
            ->latest()
            ->first();

        if (! $activeSession) {
            return view('kasir.closed');
        }

        // Load awal: 20 produk pertama
        $products = $this->getProductsQuery()->limit(20)->get()->map(fn ($p) => $this->formatProduct($p));

        // Load awal: 20 pelanggan pertama
        $customers = \App\Models\Customer::orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'phone', 'credit_limit', 'current_debt']);

        $storeSetting = StoreSetting::current();

        return view('kasir.eceran', compact('products', 'customers', 'storeSetting'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('q');
        $products = $this->getProductsQuery();

        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$query}%"));
            });
        }

        $results = $products->limit(20)->get()->map(fn ($p) => $this->formatProduct($p));

        return response()->json($results);
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->input('q');
        $customers = \App\Models\Customer::orderBy('name');

        if ($query) {
            $customers->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            });
        }

        return response()->json($customers->limit(20)->get(['id', 'name', 'phone', 'credit_limit', 'current_debt']));
    }

    private function getProductsQuery()
    {
        return Product::with(['category', 'unitConversions.unit', 'productStocks.warehouse'])->orderBy('name');
    }

    private function formatProduct($p)
    {
        $base = $p->unitConversions->firstWhere('is_base_unit', true)
              ?? $p->unitConversions->sortBy('conversion_factor')->first();

        // Jumlahkan stok per-gudang (multi-batch/lokasi dalam 1 gudang dijumlah)
        $stockBreakdown = $p->productStocks
            ->groupBy('warehouse_id')
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'warehouse_id' => $first->warehouse_id,
                    'warehouse' => $first->warehouse?->name ?? 'Gudang',
                    'qty' => $rows->sum('stock'),
                ];
            })
            ->filter(fn ($ps) => $ps['qty'] > 0)
            ->values()
            ->toArray();

        return [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'category' => $p->category?->name ?? '-',
            'stock' => $p->stock,
            'stock_breakdown' => $stockBreakdown,
            'unit' => $base?->unit->name ?? 'pcs',
            'prices' => [
                'eceran' => $base ? (float) $base->sell_price_ecer : (float) $p->price,
                'grosir' => $base ? (float) ($base->sell_price_grosir > 0 ? $base->sell_price_grosir : $base->sell_price_ecer) : (float) $p->price,
                'jual1' => $base ? (float) (($base->sell_price_jual1 ?? 0) > 0 ? $base->sell_price_jual1 : $base->sell_price_ecer) : (float) $p->price,
                'jual2' => $base ? (float) (($base->sell_price_jual2 ?? 0) > 0 ? $base->sell_price_jual2 : $base->sell_price_ecer) : (float) $p->price,
                'jual3' => $base ? (float) (($base->sell_price_jual3 ?? 0) > 0 ? $base->sell_price_jual3 : $base->sell_price_ecer) : (float) $p->price,
            ],
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'price_tier' => 'nullable|in:eceran,grosir,jual1,jual2,jual3',
        ]);

        try {
            DB::beginTransaction();

            // 0. Session guard validation
            $activeSession = \App\Models\PosSession::where('status', 'open')->latest()->first();
            if (! $activeSession) {
                throw new \Exception('Sesi kasir sudah ditutup. Silakan refresh halaman atau buka sesi baru.');
            }

            // 1. Sort IDs to prevent deadlock and bulk fetch with Pessimistic Lock
            $productIds = collect($request->items)->pluck('product_id')->unique()->sort()->values()->toArray();

            $products = Product::with(['unitConversions.unit', 'productStocks.warehouse'])
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 2. Lock customer for credit
            $customer = null;
            if ($request->payment_method === 'kredit') {
                if (! $request->customer_id) {
                    throw new \Exception('Pelanggan harus dipilih untuk pembayaran kredit.');
                }
                $customer = \App\Models\Customer::lockForUpdate()->find($request->customer_id);
                if (! $customer) {
                    throw new \Exception('Data pelanggan tidak valid.');
                }
            }

            $priceTier = $request->input('price_tier', 'eceran');
            $resolvedItems = [];
            $calculatedTotal = 0;

            // 3. Price calc & check stock using in-memory loaded products
            $priceTier = $request->input('price_tier', 'eceran');
            $resolvedItems = [];
            $calculatedTotal = 0;
            $runningStock = [];

            foreach ($request->items as $item) {
                $product = $products->get($item['product_id']);
                if (! $product) {
                    throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan.");
                }

                $qty = (int) $item['quantity'];
                $warehouseId = $item['warehouse_id'];

                // Akumulasi stok terpakai untuk gudang yang sama
                $key = $product->id.'_'.$warehouseId;
                $runningStock[$key] = ($runningStock[$key] ?? 0) + $qty;

                // Hitung total stok di gudang tersebut (bisa multi-batch/multi-lokasi)
                $availableStock = $product->productStocks
                    ->where('warehouse_id', $warehouseId)
                    ->sum('stock');

                if ($availableStock < $runningStock[$key]) {
                    // Ambil nama gudang dari relasi (sudah di-eager-load)
                    $whRecord = $product->productStocks->firstWhere('warehouse_id', $warehouseId);
                    $whName = $whRecord?->warehouse?->name ?? 'Gudang Dipilih';
                    throw new \Exception('Stok produk "'.($product->name ?? 'ID:'.$item['product_id']).'" di '.$whName.' tidak mencukupi permintaan ('.$runningStock[$key].' pcs). Tersedia: '.$availableStock.' pcs.');
                }

                $baseConversion = $product->unitConversions->firstWhere('is_base_unit', true)
                    ?? $product->unitConversions->sortBy('conversion_factor')->first();
                $serverPrice = $product->price;
                if ($baseConversion) {
                    $serverPrice = match ($priceTier) {
                        'grosir' => ($baseConversion->sell_price_grosir > 0 ? $baseConversion->sell_price_grosir : $baseConversion->sell_price_ecer),
                        'jual1' => (($baseConversion->sell_price_jual1 ?? 0) > 0 ? $baseConversion->sell_price_jual1 : $baseConversion->sell_price_ecer),
                        'jual2' => (($baseConversion->sell_price_jual2 ?? 0) > 0 ? $baseConversion->sell_price_jual2 : $baseConversion->sell_price_ecer),
                        'jual3' => (($baseConversion->sell_price_jual3 ?? 0) > 0 ? $baseConversion->sell_price_jual3 : $baseConversion->sell_price_ecer),
                        default => $baseConversion->sell_price_ecer,
                    };
                }

                $serverPrice = (float) $serverPrice;
                $qty = (int) $item['quantity'];
                $subtotal = round($serverPrice * $qty, 2);

                $resolvedItems[] = [
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $qty,
                    'price' => $serverPrice,
                    'subtotal' => $subtotal,
                ];
                $calculatedTotal += $subtotal;
            }

            $calculatedTotal = round($calculatedTotal, 2);

            if ($request->payment_method !== 'kredit' && $request->paid_amount < $calculatedTotal) {
                throw new \Exception('Jumlah bayar tidak boleh kurang dari total belanja (Rp '.number_format($calculatedTotal, 0, ',', '.').').');
            }

            if ($request->payment_method === 'kredit') {
                $debtAmount = $calculatedTotal - $request->paid_amount;
                if ($debtAmount > 0) {
                    if ($customer->remaining_credit_limit < $debtAmount && $customer->credit_limit > 0) {
                        throw new \Exception('Sisa limit kredit tidak mencukupi untuk transaksi ini.');
                    }
                }
            }

            if ($request->payment_method === 'transfer' && blank($request->payment_reference)) {
                throw new \Exception('ID transaksi transfer wajib diisi.');
            }

            $trx = Transaction::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'total_amount' => $calculatedTotal,
                'paid_amount' => $request->paid_amount,
                'change_amount' => max(0, $request->paid_amount - $calculatedTotal),
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'status' => 'completed',
            ]);

            foreach ($resolvedItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok global
                $product = $products->get($item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();

                // Kurangi stok per-gudang (specific warehouse)
                $this->deductWarehouseStock($item['product_id'], $item['warehouse_id'], $item['quantity'], $trx->id, $product->name);
            }

            if ($request->payment_method === 'kredit') {
                $debtAmount = $calculatedTotal - $request->paid_amount;
                if ($debtAmount > 0) {
                    \App\Models\CustomerCredit::create([
                        'credit_number' => \App\Models\CustomerCredit::generateNumber('debt'),
                        'customer_id' => $request->customer_id,
                        'transaction_id' => $trx->id,
                        'type' => 'debt',
                        'transaction_date' => today(),
                        'due_date' => today()->addDays(30),
                        'amount' => $debtAmount,
                        'paid_amount' => 0,
                        'status' => 'unpaid',
                        'description' => 'Pembelian POS Eceran - #'.$trx->id,
                        'created_by' => Auth::id(),
                    ]);
                    $customer->refreshDebt();
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'change' => $trx->change_amount, 'transaction_id' => $trx->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KasirEceranController::store — Gagal transaksi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            $safeMessage = str_contains($e->getMessage(), 'Produk') || str_contains($e->getMessage(), 'kredit') || str_contains($e->getMessage(), 'Stok') || str_contains($e->getMessage(), 'Pelanggan') || str_contains($e->getMessage(), 'Jumlah bayar') || str_contains($e->getMessage(), 'ID transaksi') || str_contains($e->getMessage(), 'Inkonsistensi')
                ? $e->getMessage()
                : 'Transaksi gagal diproses. Silakan coba lagi.';

            return response()->json([
                'success' => false,
                'message' => $safeMessage,
            ], 500);
        }
    }

    /**
     * Kurangi stok di product_stocks secara FIFO pada GUDANG TERTENTU.
     */
    private function deductWarehouseStock(int $productId, int $warehouseId, int $qty, int $transactionId, string $productName): void
    {
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
            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'location_id' => $stock->location_id,
                'type' => 'out',
                'reference_number' => 'POS-ECERAN-'.$transactionId,
                'batch_number' => $stock->batch_number,
                'expired_date' => $stock->expired_date,
                'quantity' => $deduct,
                'balance' => $stock->stock,
                'notes' => '[POS Eceran] Transaksi #'.$transactionId,
                'user_id' => Auth::id(),
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            throw new \Exception('Inkonsistensi data: Stok detail gudang untuk produk "'.$productName.'" tidak mencukupi, kurang '.$remaining.' pcs.');
        }
    }
}
