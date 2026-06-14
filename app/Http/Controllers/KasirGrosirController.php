<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\StoreSetting;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\ProductSearchService;
use App\Services\PosTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KasirGrosirController extends Controller
{
    private ProductSearchService $searchService;
    private PosTransactionService $transactionService;

    public function __construct(
        ProductSearchService $searchService,
        PosTransactionService $transactionService
    ) {
        $this->searchService = $searchService;
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        // Grosir memerlukan sesi eceran yang aktif (modal awal sudah dimasukkan)
        $eceranSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->where('user_id', Auth::id())
            ->first();

        if (!$eceranSession) {
            return redirect()->route('kasir.index')
                ->with('error', 'Sesi grosir tidak dapat diakses. Sesi eceran harus dibuka dengan modal awal terlebih dahulu.');
        }

        // Load products for grosir POS
        $products = $this->searchService->getProductsQuery()
            ->limit(20)
            ->get()
            ->map(fn ($p) => $this->searchService->formatProductGrosir($p));

        $customers = \App\Models\Customer::orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'phone', 'credit_limit', 'current_debt']);

        // Load vehicles for dropdown
        $vehicles = \App\Models\Vehicle::orderBy('license_plate')
            ->get(['id', 'license_plate', 'type']);

        $storeSetting = StoreSetting::current();

        return view('kasir.grosir', compact('products', 'customers', 'vehicles', 'storeSetting'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('q');
        $products = $this->searchService->searchProducts($query, 20)
            ->map(fn ($p) => $this->searchService->formatProductGrosir($p));

        return response()->json($products);
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->input('q');
        $customers = $this->searchService->searchCustomers($query, 20);

        return response()->json($customers);
    }

    public function store(Request $request)
    {
        // Verifikasi sesi eceran aktif sebelum menyimpan transaksi grosir
        $eceranSession = \App\Models\PosSession::where('status', 'open')
            ->where('type', 'eceran')
            ->where('user_id', Auth::id())
            ->first();

        if (!$eceranSession) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi eceran belum dibuka. Grosir tidak dapat digunakan.',
            ], 403);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'items.*.unit_qty' => 'required|integer|min:1',
            'items.*.unit_conversion_id' => 'nullable|exists:product_unit_conversions,id',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'price_tier' => 'nullable|in:eceran,grosir,jual1,jual2,jual3,minimal',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_name' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Grosir tidak perlu sesi sendiri — mengikuti sesi eceran

            // Lock and load products
            $productIds = collect($request->items)->pluck('product_id')->unique()->values()->toArray();
            $products = $this->transactionService->lockAndLoadProducts($productIds);

            // Lock customer for credit
            $customer = null;
            if ($request->payment_method === 'kredit') {
                if (! $request->customer_id) {
                    throw new \Exception('Pelanggan harus dipilih untuk pembayaran kredit.');
                }
                $customer = $this->transactionService->lockCustomer($request->customer_id);
                if (! $customer) {
                    throw new \Exception('Data pelanggan tidak valid.');
                }
            }

            $priceTier = $request->input('price_tier', 'grosir');
            $resolvedItems = [];
            $calculatedTotal = 0;
            $runningStock = [];

            foreach ($request->items as $item) {
                $product = $products->get($item['product_id']);
                if (! $product) {
                    throw new \Exception("Produk dengan ID {$item['product_id']} tidak ditemukan.");
                }

                $conversion = null;
                if (! empty($item['unit_conversion_id'])) {
                    $conversion = $product->unitConversions->firstWhere('id', $item['unit_conversion_id']);
                }
                $conversion ??= $product->unitConversions->firstWhere('is_base_unit', true)
                    ?? $product->unitConversions->sortBy('conversion_factor')->first();

                $unitQty = (int) $item['unit_qty'];
                $warehouseId = $item['warehouse_id'];
                $baseFactor = $conversion ? max(0.0001, (float) $conversion->conversion_factor) : 1;
                $baseQty = (int) round($unitQty * $baseFactor);

                // Check stock availability
                $key = $product->id . '_' . $warehouseId;
                $runningStock[$key] = ($runningStock[$key] ?? 0) + $baseQty;

                $availableStock = $product->productStocks
                    ->where('warehouse_id', $warehouseId)
                    ->sum('stock');

                if ($availableStock < $runningStock[$key]) {
                    $whRecord = $product->productStocks->firstWhere('warehouse_id', $warehouseId);
                    $whName = $whRecord?->warehouse?->name ?? 'Gudang Dipilih';
                    throw new \Exception('Stok produk "' . ($product->name ?? 'ID:' . $item['product_id']) . '" di ' . $whName . ' tidak mencukupi permintaan total keranjang (' . $runningStock[$key] . ' base unit). Tersedia: ' . $availableStock . ' base item.');
                }

                // Calculate price
                $unitPrice = $this->transactionService->calculatePrice($conversion, $priceTier, $product->price);
                $subtotal = round($unitPrice * $unitQty, 2);

                $pricePerBase = $baseFactor > 0 ? ($subtotal / $baseQty) : $unitPrice;
                $pricePerBase = round($pricePerBase, 4);

                $resolvedItems[] = [
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'unit_conversion_id' => $conversion ? $conversion->id : null,
                    'unit_qty' => $unitQty,
                    'unit_name' => $conversion ? ($conversion->unit->name ?? null) : null,
                    'quantity' => $baseQty,
                    'price' => $pricePerBase,
                    'subtotal' => $subtotal,
                ];
                $calculatedTotal += $subtotal;
            }

            $calculatedTotal = round($calculatedTotal, 2);

            // Validate payment
            $this->transactionService->validatePayment($request->payment_method, $request->paid_amount, $calculatedTotal);

            // Validate credit limit
            if ($request->payment_method === 'kredit') {
                $debtAmount = $calculatedTotal - $request->paid_amount;
                $this->transactionService->validateCreditLimit($debtAmount, $customer);
            }

            // Validate transfer reference
            $this->transactionService->validateTransferReference($request->payment_method, $request->payment_reference);

            // Create transaction
            $trx = $this->transactionService->createTransaction(
                [
                    'customer_id' => $request->customer_id,
                    'total_amount' => $calculatedTotal,
                    'paid_amount' => $request->paid_amount,
                    'payment_method' => $request->payment_method,
                    'payment_reference' => $request->payment_reference,
                    'sale_type' => 'grosir',
                    'vehicle_id' => $request->vehicle_id,
                    'driver_name' => $request->driver_name,
                ],
                $resolvedItems
            );

            // Deduct stock
            foreach ($resolvedItems as $item) {
                $product = $products->get($item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();

                $this->transactionService->deductWarehouseStock(
                    $item['product_id'],
                    $item['warehouse_id'],
                    $item['quantity'],
                    $trx->id,
                    $product->name,
                    'grosir'
                );
            }

            // Create pick orders for main warehouse items
            $this->transactionService->createPickOrdersIfNeeded($trx, $resolvedItems, 'grosir');

            // Create customer credit if needed
            if ($request->payment_method === 'kredit') {
                $debtAmount = $calculatedTotal - $request->paid_amount;
                if ($debtAmount > 0) {
                    $this->transactionService->createCustomerCredit(
                        $request->customer_id,
                        $trx->id,
                        $debtAmount,
                        'Pembelian POS Grosir - #' . $trx->id
                    );
                    $customer->refreshDebt();
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'change' => $trx->change_amount, 'transaction_id' => $trx->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KasirGrosirController::store — Gagal transaksi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $this->transactionService->getSafeErrorMessage($e),
            ], 500);
        }
    }
}
