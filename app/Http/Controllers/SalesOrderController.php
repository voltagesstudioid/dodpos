<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StoreSetting;
use App\Services\ReferenceNumberService;
use App\Services\StockService;
use App\Support\SearchSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    private function buildConversionsFromProduct(Product $product): array
    {
        $conversions = [];

        if ($product->unit) {
            $conversions[] = ['factor' => 1, 'label' => $product->unit->name];
        } else {
            $conversions[] = ['factor' => 1, 'label' => 'Satuan Dasar'];
        }

        foreach ($product->unitConversions as $uc) {
            if ($uc->unit) {
                $conversions[] = ['factor' => (int) $uc->conversion_factor, 'label' => $uc->unit->name];
            }
        }

        return $conversions;
    }

    private function hydrateItemsForJs(array $items): array
    {
        $productIds = collect($items)
            ->pluck('product_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (count($productIds) === 0) {
            return [];
        }

        $productsById = Product::query()
            ->withTrashed()
            ->with(['unit', 'unitConversions.unit'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return collect($items)->map(function ($it) use ($productsById) {
            $pid = (int) ($it['product_id'] ?? 0);
            $product = $productsById->get($pid);
            $qty = (int) ($it['quantity'] ?? 1);
            $price = (float) ($it['price'] ?? ($product?->price ?? 0));
            $unitName = $it['unit_name'] ?? null;
            $unitFactor = (int) ($it['unit_factor'] ?? 1);
            $warehouseId = $it['warehouse_id'] ?? null;

            return [
                'product_id' => $pid,
                'name' => $product?->name ?? ('Barang (ID: '.$pid.')'),
                'price' => $price,
                'quantity' => $qty,
                'unit_name' => $unitName,
                'unit_factor' => $unitFactor,
                'warehouse_id' => $warehouseId ? (int) $warehouseId : null,
                'subtotal' => $price * $qty,
                'conversions' => $product ? $this->buildConversionsFromProduct($product) : [],
            ];
        })->values()->all();
    }

    public function searchProducts(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $sanitizedQ = SearchSanitizer::sanitize($q);

        $query = Product::query()
            ->with(['productStocks.warehouse', 'unit', 'unitConversions.unit'])
            ->select(['id', 'name', 'sku', 'barcode', 'price', 'stock', 'min_stock'])
            ->whereNull('deleted_at');

        if ($q !== '') {
            $query->where(function ($x) use ($sanitizedQ) {
                $x->where('name', 'like', "%{$sanitizedQ}%")
                    ->orWhere('sku', 'like', "%{$sanitizedQ}%")
                    ->orWhere('barcode', 'like', "%{$sanitizedQ}%");
            });
        }

        $products = $query->orderBy('name')->limit(50)->get()->map(function ($p) {
            $byWh = [];
            foreach ($p->productStocks as $ps) {
                $wname = $ps->warehouse?->name ?? 'Gudang';
                $byWh[$wname] = ($byWh[$wname] ?? 0) + (int) $ps->stock;
            }
            // Sort desc by stock and take top 5 for brevity
            arsort($byWh);
            $stocksByWarehouse = [];
            foreach (array_slice($byWh, 0, 5, true) as $wn => $qty) {
                $stocksByWarehouse[] = ['warehouse' => $wn, 'stock' => $qty];
            }
            $conversions = [];
            if ($p->unit) {
                $conversions[] = ['factor' => 1, 'label' => $p->unit->name];
            } else {
                $conversions[] = ['factor' => 1, 'label' => 'Satuan Dasar'];
            }
            foreach ($p->unitConversions as $uc) {
                if ($uc->unit) {
                    $conversions[] = ['factor' => (int) $uc->conversion_factor, 'label' => $uc->unit->name];
                }
            }

            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'barcode' => $p->barcode,
                'stock' => (int) $p->stock,
                'price' => (float) $p->price,
                'min_stock' => (int) ($p->min_stock ?? 0),
                'stocks_by_warehouse' => $stocksByWarehouse,
                'conversions' => $conversions,
            ];
        });

        return response()->json($products);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sanitizedSearch = SearchSanitizer::sanitize($search);
        $status = $request->input('status');
        $date = $request->input('date');
        $deliveryFilter = $request->input('delivery'); // today, tomorrow, week, overdue

        $baseQuery = SalesOrder::query();

        if ($search) {
            $baseQuery->where(function ($q) use ($sanitizedSearch) {
                $q->where('so_number', 'like', "%{$sanitizedSearch}%")
                    ->orWhereHas('customer', function ($c) use ($sanitizedSearch) {
                        $c->where('name', 'like', "%{$sanitizedSearch}%");
                    })
                    ->orWhereHas('user', function ($u) use ($sanitizedSearch) {
                        $u->where('name', 'like', "%{$sanitizedSearch}%");
                    });
            });
        }

        if ($date) {
            $baseQuery->whereDate('order_date', $date);
        }

        // Delivery date filter
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        if ($deliveryFilter === 'today') {
            $baseQuery->whereDate('delivery_date', $today);
        } elseif ($deliveryFilter === 'tomorrow') {
            $baseQuery->whereDate('delivery_date', $tomorrow);
        } elseif ($deliveryFilter === 'week') {
            $baseQuery->whereBetween('delivery_date', [$today, now()->addDays(7)->toDateString()]);
        } elseif ($deliveryFilter === 'overdue') {
            $baseQuery->whereDate('delivery_date', '<', $today)
                      ->whereIn('status', ['draft', 'confirmed', 'processing']);
        }

        $totalCount = (clone $baseQuery)->count();
        $draftCount = (clone $baseQuery)->where('status', 'draft')->count();
        $confirmedCount = (clone $baseQuery)->where('status', 'confirmed')->count();
        $processingCount = (clone $baseQuery)->where('status', 'processing')->count();
        $completedCount = (clone $baseQuery)->where('status', 'completed')->count();
        $cancelledCount = (clone $baseQuery)->where('status', 'cancelled')->count();

        // Delivery stats (non-cancelled, non-completed)
        $activeBase = SalesOrder::whereIn('status', ['draft', 'confirmed', 'processing']);
        $siapKirimCount = (clone $activeBase)->whereDate('delivery_date', $tomorrow)->count();
        $kirimHariIniCount = (clone $activeBase)->whereDate('delivery_date', $today)->count();
        $overdueCount = (clone $activeBase)->whereDate('delivery_date', '<', $today)->count();

        $listQuery = (clone $baseQuery)->with(['customer', 'user'])->latest();

        if ($status) {
            $listQuery->where('status', $status);
        }

        $salesOrders = $listQuery->paginate(10)->withQueryString();

        return view('penjualan.sales-order.index', compact(
            'salesOrders',
            'search',
            'status',
            'date',
            'deliveryFilter',
            'totalCount',
            'draftCount',
            'confirmedCount',
            'processingCount',
            'completedCount',
            'cancelledCount',
            'siapKirimCount',
            'kirimHariIniCount',
            'overdueCount'
        ));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $oldItemsForJs = $this->hydrateItemsForJs(session()->getOldInput('items', []));

        return view('penjualan.sales-order.create', compact('customers', 'oldItemsForJs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:draft,confirmed,processing,completed,cancelled',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit_name' => 'nullable|string|max:50',
            'items.*.unit_factor' => 'nullable|integer|min:1',
            'items.*.warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        try {
            DB::beginTransaction();

            // Generate SO Number — aman dari race condition dengan lockForUpdate
            $soNumber = $this->generateSoNumber();

            $salesOrder = SalesOrder::create([
                'so_number' => $soNumber,
                'customer_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date,
                'status' => $request->status,
                'notes' => $request->notes,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['price'];
                $subtotal = $qty * $price;
                $totalAmount += $subtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_name' => $item['unit_name'] ?? null,
                    'unit_factor' => (int) ($item['unit_factor'] ?? 1),
                    'warehouse_id' => $item['warehouse_id'] ?? null,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            $salesOrder->update(['total_amount' => $totalAmount]);

            if ($request->status === 'completed') {
                $salesOrder->load('items');
                $this->processStockDeductionForCompletedSO($salesOrder);
            }

            DB::commit();

            return redirect()->route('sales-order.show', $salesOrder)->with('success', 'Sales Order berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            if (str_contains($message, 'Stok tidak mencukupi')) {
                return back()->with('error', $message)->withInput();
            }

            return back()->with('error', 'Terjadi kesalahan saat membuat Sales Order. Silakan coba lagi.')->withInput();
        }
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['customer', 'user', 'items.product']);
        $storeSettings = StoreSetting::current();

        return view('penjualan.sales-order.show', compact('salesOrder', 'storeSettings'));
    }

    public function edit(SalesOrder $salesOrder)
    {
        if (in_array($salesOrder->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Sales Order yang sudah selesai atau dibatalkan tidak dapat diedit.');
        }

        $customers = Customer::orderBy('name')->get();
        $salesOrder->load(['items.product', 'user']);

        $existingItemsForJs = $this->hydrateItemsForJs(
            $salesOrder->items->map(fn ($it) => [
                'product_id' => $it->product_id,
                'price' => (float) $it->price,
                'quantity' => (int) $it->quantity,
                'unit_name' => $it->unit_name,
                'unit_factor' => (int) ($it->unit_factor ?? 1),
                'warehouse_id' => $it->warehouse_id,
            ])->values()->all()
        );

        $oldItemsForJs = $this->hydrateItemsForJs(session()->getOldInput('items', []));

        return view('penjualan.sales-order.edit', compact('salesOrder', 'customers', 'existingItemsForJs', 'oldItemsForJs'));
    }

    public function update(Request $request, SalesOrder $salesOrder)
    {
        if (in_array($salesOrder->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Sales Order yang sudah selesai atau dibatalkan tidak dapat diedit.');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:draft,confirmed,processing,completed,cancelled',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit_name' => 'nullable|string|max:50',
            'items.*.unit_factor' => 'nullable|integer|min:1',
            'items.*.warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        try {
            DB::beginTransaction();

            $salesOrder->update([
                'customer_id' => $request->customer_id,
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Clear old items
            $salesOrder->items()->delete();

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['price'];
                $subtotal = $qty * $price;
                $totalAmount += $subtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_name' => $item['unit_name'] ?? null,
                    'unit_factor' => (int) ($item['unit_factor'] ?? 1),
                    'warehouse_id' => $item['warehouse_id'] ?? null,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            $salesOrder->update(['total_amount' => $totalAmount]);

            // Refresh items after delete+recreate
            $salesOrder->load('items');

            // Detect if status just changed to completed → deduct stock
            $wasCompleted = in_array($salesOrder->getOriginal('status'), ['completed']);
            if ($request->status === 'completed' && !$wasCompleted) {
                $this->processStockDeductionForCompletedSO($salesOrder);
            }

            DB::commit();

            return redirect()->route('sales-order.show', $salesOrder)->with('success', 'Sales Order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            if (str_contains($message, 'Stok tidak mencukupi')) {
                return back()->with('error', $message)->withInput();
            }

            return back()->with('error', 'Terjadi kesalahan saat memperbarui Sales Order. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy(SalesOrder $salesOrder)
    {
        if (in_array($salesOrder->status, ['completed'])) {
            return back()->with('error', 'Sales Order yang sudah selesai tidak dapat dihapus.');
        }

        try {
            DB::beginTransaction();
            $salesOrder->items()->delete();
            $salesOrder->delete();
            DB::commit();

            return redirect()->route('sales-order.index')->with('success', 'Sales Order berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }

    /**
     * Generate SO Number yang aman dari race condition menggunakan DB lock.
     * @deprecated Use ReferenceNumberService::generateSoNumber()
     */
    private function generateSoNumber(): string
    {
        return ReferenceNumberService::generateSoNumber();
    }

    /**
     * Deduct product stock when Sales Order is completed.
     * Deduction is per-item based on each item's warehouse_id.
     * If an item has no warehouse specified, falls back to FIFO across all warehouses.
     * Must be called inside a DB transaction.
     *
     * @throws \Exception if stock is insufficient
     */
    private function processStockDeductionForCompletedSO(SalesOrder $salesOrder): void
    {
        $salesOrder->load(['items.product', 'items.warehouse']);

        $grouped = [];
        foreach ($salesOrder->items as $item) {
            $qty = (int) $item->quantity;
            if ($qty <= 0) continue;

            $warehouseId = $item->warehouse_id;
            $key = $item->product_id . '|' . ($warehouseId ?? 'null');

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'product_id'  => $item->product_id,
                    'quantity'    => 0,
                    'warehouse_id'=> $warehouseId,
                    'warehouse_name' => $item->warehouse?->name,
                    'product_name'   => $item->product?->name ?? 'ID: '.$item->product_id,
                ];
            }
            $grouped[$key]['quantity'] += $qty;
        }

        if (empty($grouped)) return;

        $reference = 'SO-'.$salesOrder->so_number;
        $userId = Auth::id();

        foreach ($grouped as $entry) {
            $productName = $entry['product_name'];

            if ($entry['warehouse_id']) {
                // Validate warehouse-specific stock
                $error = StockService::validateWarehouseStock(
                    [['product_id' => $entry['product_id'], 'quantity' => $entry['quantity'], 'name' => $productName]],
                    $entry['warehouse_id']
                );
                if ($error) {
                    throw new \Exception(
                        "Stok di gudang {$entry['warehouse_name']} tidak mencukupi untuk {$error['product']}. Tersedia: {$error['available']}, dibutuhkan: {$error['requested']}."
                    );
                }

                $notes = '[SO] Sales Order #'.$salesOrder->so_number.' (Gudang: '.($entry['warehouse_name'] ?? '-').')';

                StockService::deductGlobalStock($entry['product_id'], $entry['quantity']);
                StockService::deductSpecificWarehouseStock(
                    $entry['product_id'],
                    $entry['quantity'],
                    $entry['warehouse_id'],
                    $reference,
                    'sales_order',
                    $notes,
                    $userId
                );
            } else {
                // No warehouse — FIFO across all warehouses
                $error = StockService::validateStock(
                    [['product_id' => $entry['product_id'], 'quantity' => $entry['quantity'], 'name' => $productName]]
                );
                if ($error) {
                    throw new \Exception(
                        "Stok tidak mencukupi untuk {$error['product']}. Tersedia: {$error['available']}, dibutuhkan: {$error['requested']}."
                    );
                }

                $notes = '[SO] Sales Order #'.$salesOrder->so_number;

                StockService::deductGlobalStock($entry['product_id'], $entry['quantity']);
                StockService::deductWarehouseStockFIFO(
                    $entry['product_id'],
                    $entry['quantity'],
                    $reference,
                    'sales_order',
                    $notes,
                    $userId
                );
            }
        }
    }
}
