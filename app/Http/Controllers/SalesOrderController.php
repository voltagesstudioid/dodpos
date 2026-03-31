<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Services\ReferenceNumberService;
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
            ->with(['unit', 'unitConversions.unit'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return collect($items)->map(function ($it) use ($productsById) {
            $pid = (int) ($it['product_id'] ?? 0);
            $product = $productsById->get($pid);
            $qty = (int) ($it['quantity'] ?? 1);
            $price = (float) ($it['price'] ?? ($product?->price ?? 0));

            return [
                'product_id' => $pid,
                'name' => $product?->name ?? ('Barang (ID: '.$pid.')'),
                'price' => $price,
                'quantity' => $qty,
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

        $totalCount = (clone $baseQuery)->count();
        $draftCount = (clone $baseQuery)->where('status', 'draft')->count();
        $confirmedCount = (clone $baseQuery)->where('status', 'confirmed')->count();
        $processingCount = (clone $baseQuery)->where('status', 'processing')->count();
        $completedCount = (clone $baseQuery)->where('status', 'completed')->count();
        $cancelledCount = (clone $baseQuery)->where('status', 'cancelled')->count();

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
            'totalCount',
            'draftCount',
            'confirmedCount',
            'processingCount',
            'completedCount',
            'cancelledCount'
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
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            $salesOrder->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('sales-order.show', $salesOrder)->with('success', 'Sales Order berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat membuat Sales Order. Silakan coba lagi.')->withInput();
        }
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['customer', 'user', 'items.product']);

        return view('penjualan.sales-order.show', compact('salesOrder'));
    }

    public function edit(SalesOrder $salesOrder)
    {
        if (in_array($salesOrder->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Sales Order yang sudah selesai atau dibatalkan tidak dapat diedit.');
        }

        $customers = Customer::orderBy('name')->get();
        $salesOrder->load('items.product');

        $existingItemsForJs = $this->hydrateItemsForJs(
            $salesOrder->items->map(fn ($it) => [
                'product_id' => $it->product_id,
                'price' => (float) $it->price,
                'quantity' => (int) $it->quantity,
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
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            $salesOrder->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('sales-order.show', $salesOrder)->with('success', 'Sales Order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

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
}
