<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\SupplierDebt;
use App\Models\Warehouse;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PurchaseOrderController extends Controller
{
    /**
     * List all purchase orders.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'user', 'items'])
            ->orderBy('created_at', 'desc');

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter periode
        if ($request->date_from) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Statistik
        $statsQuery = PurchaseOrder::query();
        if ($request->date_from) {
            $statsQuery->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $statsQuery->whereDate('order_date', '<=', $request->date_to);
        }

        $stats = [
            'total' => $statsQuery->count(),
            'draft' => (clone $statsQuery)->where('status', 'draft')->count(),
            'ordered' => (clone $statsQuery)->where('status', 'ordered')->count(),
            'partial' => (clone $statsQuery)->where('status', 'partial')->count(),
            'received' => (clone $statsQuery)->where('status', 'received')->count(),
            'cancelled' => (clone $statsQuery)->where('status', 'cancelled')->count(),
            'total_amount' => (clone $statsQuery)->whereIn('status', ['ordered', 'partial', 'received'])->sum('total_amount'),
            'late' => (clone $statsQuery)
                ->whereIn('status', ['ordered', 'partial'])
                ->whereNotNull('expected_date')
                ->whereDate('expected_date', '<', now())
                ->count(),
        ];

        // Export Excel jika diminta
        if ($request->export === 'excel') {
            return $this->exportExcel($orders, $stats);
        }

        return view('pembelian.order.index', compact('orders', 'stats'));
    }

    /**
     * Export PO to Excel (CSV)
     */
    private function exportExcel($orders, $stats)
    {
        $filename = 'purchase-orders-' . now()->format('Ymd-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders, $stats) {
            $output = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($output, ['No. PO', 'Supplier', 'Tgl Pesan', 'Tgl Estimasi', 'Status', 'Total', 'Item']);
            
            foreach ($orders as $order) {
                fputcsv($output, [
                    $order->po_number,
                    $order->supplier->name ?? '-',
                    $order->order_date->format('d/m/Y'),
                    $order->expected_date?->format('d/m/Y') ?? '-',
                    $order->statusLabel['label'] ?? $order->status,
                    $order->total_amount,
                    $order->items->count(),
                ]);
            }
            
            // Summary
            fputcsv($output, []);
            fputcsv($output, ['Ringkasan', '', '', '', '', '', '']);
            fputcsv($output, ['Total PO', $stats['total'], '', '', '', '', '']);
            fputcsv($output, ['Dalam Proses', $stats['ordered'] + $stats['partial'], '', '', '', '', '']);
            fputcsv($output, ['Selesai', $stats['received'], '', '', '', '', '']);
            fputcsv($output, ['Terlambat', $stats['late'], '', '', '', '', '']);
            
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dashboard Pembelian dengan grafik dan ringkasan.
     */
    public function dashboard(Request $request)
    {
        $period = $request->input('period', 'month'); // month, quarter, year
        
        $startDate = match($period) {
            'quarter' => now()->subMonths(3)->startOfMonth(),
            'year' => now()->subMonths(12)->startOfMonth(),
            default => now()->subMonths(1)->startOfMonth(),
        };
        
        $endDate = now()->endOfDay();

        // Statistik utama
        $stats = [
            'total_po' => PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])->count(),
            'total_value' => PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
                ->whereIn('status', ['ordered', 'partial', 'received'])
                ->sum('total_amount'),
            'completed' => PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
                ->where('status', 'received')
                ->count(),
            'late' => PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
                ->whereIn('status', ['ordered', 'partial'])
                ->whereNotNull('expected_date')
                ->whereDate('expected_date', '<', now())
                ->count(),
        ];

        // Data grafik PO per hari/bulan
        $chartData = PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
            ->selectRaw('DATE(order_date) as date, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top supplier
        $topSuppliers = PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['ordered', 'partial', 'received'])
            ->selectRaw('supplier_id, COUNT(*) as po_count, SUM(total_amount) as total_value')
            ->groupBy('supplier_id')
            ->with('supplier:id,name')
            ->orderByDesc('total_value')
            ->limit(5)
            ->get();

        // PO terbaru
        $recentOrders = PurchaseOrder::with(['supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // PO terlambat
        $lateOrders = PurchaseOrder::with(['supplier'])
            ->whereIn('status', ['ordered', 'partial'])
            ->whereNotNull('expected_date')
            ->whereDate('expected_date', '<', now())
            ->orderBy('expected_date')
            ->limit(5)
            ->get();

        return view('pembelian.dashboard', compact(
            'stats', 'chartData', 'topSuppliers', 'recentOrders', 'lateOrders', 'period'
        ));
    }

    /**
     * Show form to create a new PO.
     */
    public function create()
    {
        $suppliers = Supplier::where('active', true)->orderBy('name')->get();
        $poNumber = PurchaseOrder::generatePoNumber();

        return view('pembelian.order.create', compact('suppliers', 'poNumber'));
    }

    public function searchProducts(Request $request)
    {
        $id = $request->integer('id');
        $q = trim((string) $request->query('q', ''));

        $query = Product::query()
            ->with(['unit', 'unitConversions.unit']);

        if ($id > 0) {
            $query->where('id', $id);
        } else {
            if (mb_strlen($q) < 2) {
                return response()->json([]);
            }
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', '%'.$q.'%')
                    ->orWhere('sku', 'like', '%'.$q.'%')
                    ->orWhere('barcode', 'like', '%'.$q.'%');
            });
        }

        $products = $query->orderBy('name')->limit(20)->get();

        $data = $products->map(function (Product $p) {
            $conversions = [];
            $ucs = $p->unitConversions
                ->filter(fn ($uc) => $uc->unit)
                ->sortBy(function ($uc) {
                    if ($uc->is_base_unit) {
                        return -1;
                    }

                    return (int) $uc->conversion_factor;
                })
                ->values();

            foreach ($ucs as $uc) {
                $conversions[] = [
                    'unit_id' => $uc->unit_id,
                    'name' => $uc->unit->name,
                    'factor' => $uc->is_base_unit ? 1 : max(1, (int) $uc->conversion_factor),
                    'price' => (float) ($uc->purchase_price ?? 0),
                ];
            }

            if (count($conversions) === 0 && $p->unit) {
                $conversions[] = [
                    'unit_id' => $p->unit_id,
                    'name' => $p->unit->name,
                    'factor' => 1,
                    'price' => (float) ($p->purchase_price ?? 0),
                ];
            }

            return [
                'id' => $p->id,
                'name' => (string) $p->name,
                'sku' => (string) ($p->sku ?? ''),
                'purchase_price' => (float) ($p->purchase_price ?? 0),
                'unit_id' => $p->unit_id,
                'unit_name' => $p->unit?->name,
                'conversions' => $conversions,
            ];
        })->values();

        return response()->json($data);
    }

    /**
     * Store a new PO.
     */
    public function store(Request $request)
    {
        $rules = [
            'po_number' => 'required|unique:purchase_orders,po_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_id' => 'nullable|exists:units,id',
            'items.*.qty_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
        if (Schema::hasColumn('purchase_orders', 'payment_term')) {
            $rules['payment_term'] = 'required|in:cash,credit';
        }
        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            $items = $validated['items'] ?? [];
            $productIds = collect($items)->pluck('product_id')->map(fn ($v) => (int) $v)->filter()->values();
            $uniqueProductIds = $productIds->unique()->values();
            if ($uniqueProductIds->count() !== $productIds->count()) {
                DB::rollBack();

                return back()->with('error', 'Produk duplikat terdeteksi di daftar item.')->withInput();
            }

            $productsById = Product::query()
                ->whereIn('id', $uniqueProductIds->all())
                ->with(['unit', 'unitConversions.unit'])
                ->get()
                ->keyBy('id');

            $totalAmount = 0.0;

            $payload = [
                'po_number' => $validated['po_number'],
                'supplier_id' => $validated['supplier_id'],
                'status' => 'draft',
                'order_date' => $validated['order_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
                'user_id' => Auth::id(),
            ];
            if (Schema::hasColumn('purchase_orders', 'payment_term')) {
                $payload['payment_term'] = $validated['payment_term'] ?? 'credit';
            }
            if (($payload['payment_term'] ?? null) === 'cash') {
                $payload['due_date'] = null;
            }
            $po = PurchaseOrder::create($payload);

            foreach ($items as $item) {
                $productId = (int) $item['product_id'];
                $qty = (int) $item['qty_ordered'];
                $unitPrice = (float) $item['unit_price'];

                $product = $productsById->get($productId);
                if (! $product) {
                    throw new \RuntimeException('Produk tidak ditemukan.');
                }

                $baseUnitId = (int) ($product->unitConversions->firstWhere('is_base_unit', true)?->unit_id ?? $product->unit_id ?? 0);

                $unitId = isset($item['unit_id']) && $item['unit_id'] !== null ? (int) $item['unit_id'] : null;
                if (! $unitId && $baseUnitId) {
                    $unitId = $baseUnitId;
                }

                $conversionFactor = 1;
                if ($unitId && $baseUnitId && $unitId !== $baseUnitId) {
                    $uc = $product->unitConversions->firstWhere('unit_id', $unitId);
                    if (! $uc) {
                        throw new \RuntimeException("Satuan tidak valid untuk produk {$product->name}.");
                    }
                    $conversionFactor = max(1, (int) $uc->conversion_factor);
                }

                $subtotal = $qty * $unitPrice;
                $totalAmount += $subtotal;
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'conversion_factor' => $conversionFactor,
                    'qty_ordered' => $qty,
                    'qty_received' => 0,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            $po->update(['total_amount' => $totalAmount]);
            DB::commit();

            return redirect()->route('pembelian.order.show', ['order' => $po])
                ->with('success', "Purchase Order {$po->po_number} berhasil dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membuat PO: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Show PO detail.
     */
    public function show(PurchaseOrder $order)
    {
        $relations = ['supplier', 'user', 'items.product.unit'];
        if (Schema::hasTable('purchase_order_shortage_reports')) {
            $relations[] = 'shortageReports.reporter';
        }
        if (Schema::hasTable('purchase_order_receipts')) {
            $relations[] = 'receipts.receiver';
            $relations[] = 'receipts.items.product';
            $relations[] = 'receipts.resolver';
            $relations[] = 'receipts.purchaseReturn';
            $relations[] = 'receipts.reorderPurchaseOrder';
        }

        $order->load($relations);

        return view('pembelian.order.show', compact('order'));
    }

    /**
     * Show edit form (only for draft POs).
     */
    public function edit(PurchaseOrder $order)
    {
        if ($order->status !== 'draft') {
            return redirect()->route('pembelian.order.show', ['order' => $order])
                ->with('error', 'Hanya PO berstatus Draft yang dapat diedit.');
        }
        $suppliers = Supplier::where('active', true)->orderBy('name')->get();
        $order->load(['items.product.unit']);

        return view('pembelian.order.edit', compact('order', 'suppliers'));
    }

    /**
     * Append items to an existing Draft PO via query (from Reorder suggestions).
     * Accepts add[]=product_id & qty[]=quantity pairs.
     */
    public function appendItems(Request $request, PurchaseOrder $order)
    {
        if ($order->status !== 'draft') {
            return redirect()->route('pembelian.order.show', ['order' => $order])
                ->with('error', 'Hanya PO berstatus Draft yang dapat ditambahkan item.');
        }

        $adds = $request->input('add', []);
        $qtys = $request->input('qty', []);

        if (! is_array($adds) || empty($adds)) {
            return redirect()->route('pembelian.order.edit', ['order' => $order])
                ->with('warning', 'Tidak ada item yang ditambahkan.');
        }

        try {
            DB::beginTransaction();

            foreach ($adds as $i => $pid) {
                $productId = (int) $pid;
                $qty = (int) ($qtys[$i] ?? 1);
                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $product = Product::find($productId);
                if (! $product) {
                    continue;
                }

                $existing = $order->items()->where('product_id', $productId)->first();
                if ($existing) {
                    $existing->update([
                        'qty_ordered' => $existing->qty_ordered + $qty,
                    ]);
                } else {
                    $baseUnitId = (int) ($product->unitConversions()->where('is_base_unit', true)->value('unit_id') ?? $product->unit_id ?? 0);
                    $unitId = $baseUnitId ?: $product->unit_id;
                    $conversionFactor = 1;
                    $unitPrice = (float) ($product->purchase_price ?? 0);

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $order->id,
                        'product_id' => $productId,
                        'unit_id' => $unitId,
                        'conversion_factor' => $conversionFactor,
                        'qty_ordered' => $qty,
                        'unit_price' => $unitPrice,
                    ]);
                }
            }

            // Recalculate total
            $totalAmount = $order->items()->sum(DB::raw('qty_ordered * unit_price'));
            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('pembelian.order.edit', ['order' => $order])
                ->with('success', 'Item reorder berhasil ditambahkan ke PO Draft.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('pembelian.order.edit', ['order' => $order])
                ->with('error', 'Gagal menambahkan item ke PO: '.$e->getMessage());
        }
    }

    /**
     * Update an existing PO (only draft allowed).
     */
    public function update(Request $request, PurchaseOrder $order)
    {
        if ($order->status !== 'draft') {
            return redirect()->route('pembelian.order.show', ['order' => $order])
                ->with('error', 'Hanya PO berstatus Draft yang dapat diedit.');
        }

        $rules = [
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_id' => 'nullable|exists:units,id',
            'items.*.qty_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
        if (Schema::hasColumn('purchase_orders', 'payment_term')) {
            $rules['payment_term'] = 'required|in:cash,credit';
        }
        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            $items = $validated['items'] ?? [];
            $productIds = collect($items)->pluck('product_id')->map(fn ($v) => (int) $v)->filter()->values();
            $uniqueProductIds = $productIds->unique()->values();
            if ($uniqueProductIds->count() !== $productIds->count()) {
                DB::rollBack();

                return back()->with('error', 'Produk duplikat terdeteksi di daftar item.')->withInput();
            }

            $productsById = Product::query()
                ->whereIn('id', $uniqueProductIds->all())
                ->with(['unit', 'unitConversions.unit'])
                ->get()
                ->keyBy('id');

            $totalAmount = 0.0;

            $payload = [
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
            ];
            if (Schema::hasColumn('purchase_orders', 'payment_term')) {
                $payload['payment_term'] = $validated['payment_term'] ?? $order->payment_term;
            }
            if (($payload['payment_term'] ?? null) === 'cash') {
                $payload['due_date'] = null;
            }
            $order->update($payload);

            // Replace all items
            $order->items()->delete();
            foreach ($items as $item) {
                $productId = (int) $item['product_id'];
                $qty = (int) $item['qty_ordered'];
                $unitPrice = (float) $item['unit_price'];

                $product = $productsById->get($productId);
                if (! $product) {
                    throw new \RuntimeException('Produk tidak ditemukan.');
                }

                $baseUnitId = (int) ($product->unitConversions->firstWhere('is_base_unit', true)?->unit_id ?? $product->unit_id ?? 0);

                $unitId = isset($item['unit_id']) && $item['unit_id'] !== null ? (int) $item['unit_id'] : null;
                if (! $unitId && $baseUnitId) {
                    $unitId = $baseUnitId;
                }

                $conversionFactor = 1;
                if ($unitId && $baseUnitId && $unitId !== $baseUnitId) {
                    $uc = $product->unitConversions->firstWhere('unit_id', $unitId);
                    if (! $uc) {
                        throw new \RuntimeException("Satuan tidak valid untuk produk {$product->name}.");
                    }
                    $conversionFactor = max(1, (int) $uc->conversion_factor);
                }

                $subtotal = $qty * $unitPrice;
                $totalAmount += $subtotal;
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'conversion_factor' => $conversionFactor,
                    'qty_ordered' => $qty,
                    'qty_received' => 0,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);
            DB::commit();

            return redirect()->route('pembelian.order.show', ['order' => $order])
                ->with('success', 'Purchase Order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal update PO: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Delete a PO (only draft allowed).
     */
    public function destroy(PurchaseOrder $order)
    {
        if ($order->status !== 'draft') {
            return redirect()->route('pembelian.order')->with('error', 'Hanya PO Draft yang dapat dihapus.');
        }

        $poNumber = $order->po_number;
        $order->delete(); // cascade deletes items

        return redirect()->route('pembelian.order')->with('success', "PO {$poNumber} telah dihapus.");
    }

    /**
     * Update status (draft→ordered, ordered→cancelled).
     */
    public function updateStatus(Request $request, PurchaseOrder $order)
    {
        $request->validate(['status' => 'required|in:ordered,cancelled']);

        $allowed = [
            'draft' => ['ordered', 'cancelled'],
            'ordered' => ['cancelled'],
            'partial' => ['cancelled'],
        ];

        if (! in_array($request->status, $allowed[$order->status] ?? [])) {
            return back()->with('error', 'Perubahan status tidak diizinkan dari '.$order->statusLabel['label']);
        }

        $order->update(['status' => $request->status]);

        $label = match ($request->status) {
            'ordered' => 'Dipesan',
            'cancelled' => 'Dibatalkan',
            default => $request->status,
        };

        if ($request->status === 'ordered') {
            return back()->with('success', "Status PO diubah menjadi: {$label}. PO masuk antrian Terima dari PO (Admin 3).");
        }

        return back()->with('success', "Status PO diubah menjadi: {$label}.");
    }

    /**
     * Show form to receive goods.
     */
    public function receive(PurchaseOrder $order)
    {
        if (! in_array($order->status, ['ordered', 'partial'])) {
            return redirect()->route('pembelian.order.show', ['order' => $order])
                ->with('error', 'Hanya PO berstatus Dipesan atau Diterima Sebagian yang bisa diproses penerimaannya.');
        }

        $order->load(['supplier', 'items.product.unit']);
        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();

        return view('pembelian.order.receive', compact('order', 'warehouses'));
    }

    /**
     * Process goods receipt — adds stock via InboundController logic.
     */
    public function processReceive(Request $request, PurchaseOrder $order)
    {
        if (! in_array($order->status, ['ordered', 'partial'])) {
            return back()->with('error', 'PO ini tidak dapat diproses penerimaannya.');
        }

        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'receive_date' => 'required|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:purchase_order_items,id',
            'items.*.qty' => 'required|integer|min:0',
            'items.*.expired_date' => 'nullable|date',
            'items.*.batch_number' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $anyReceived = false;

            foreach ($request->items as $receive) {
                if ($receive['qty'] <= 0) {
                    continue;
                }

                $item = PurchaseOrderItem::query()
                    ->where('purchase_order_id', $order->id)
                    ->where('id', $receive['item_id'])
                    ->with(['product', 'unit'])
                    ->lockForUpdate()
                    ->first();
                if (! $item) {
                    DB::rollBack();

                    return back()->with('error', 'Item penerimaan tidak valid untuk PO ini.')->withInput();
                }
                $remaining = $item->qty_ordered - $item->qty_received;

                if ($receive['qty'] > $remaining) {
                    DB::rollBack();

                    return back()->with('error', "Qty diterima untuk {$item->product->name} melebihi sisa pesanan ({$remaining}).")->withInput();
                }

                // Update item received qty (still in PO unit)
                $item->qty_received += $receive['qty'];
                $item->save();

                // Calculate real base stock quantity
                $baseQty = $receive['qty'] * $item->conversion_factor;

                // Add base stock to warehouse (safe under concurrency)
                $productStock = $this->getOrCreateLockedProductStock(
                    $item->product_id,
                    (int) $request->warehouse_id,
                    $receive['batch_number'] ?? null,
                    $receive['expired_date'] ?? null
                );
                $productStock->increment('stock', $baseQty);

                // Update global product stock
                $item->product->increment('stock', $baseQty);

                // Record stock movement (in base qty)
                $unitName = $item->unit ? $item->unit->name : 'Pcs';
                $movementNote = "[Pembelian PO] {$order->po_number} — {$order->supplier->name} ({$receive['qty']} {$unitName})";

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $request->warehouse_id,
                    'location_id' => null,
                    'type' => 'in',
                    'source_type' => 'purchase_order',
                    'purchase_order_id' => $order->id,
                    'reference_number' => $order->po_number,
                    'batch_number' => $receive['batch_number'] ?? null,
                    'expired_date' => $receive['expired_date'] ?? null,
                    'quantity' => $baseQty,
                    'notes' => $movementNote,
                    'user_id' => Auth::id(),
                ]);

                $anyReceived = true;
            }

            if (! $anyReceived) {
                DB::rollBack();

                return back()->with('error', 'Tidak ada barang yang diterima. Masukkan qty > 0 minimal untuk satu item.');
            }

            // Determine new PO status
            $order->refresh();
            $order->load('items');
            $allReceived = $order->items->every(fn ($i) => $i->qty_received >= $i->qty_ordered);
            $order->update(['status' => $allReceived ? 'received' : 'partial']);

            // Auto-create Supplier Debt if not exists yet
            $isCredit = true;
            if (Schema::hasColumn('purchase_orders', 'payment_term')) {
                $isCredit = ($order->payment_term === 'credit');
            }
            if ($isCredit && $order->supplier_id && $order->total_amount > 0) {
                $exists = SupplierDebt::where('purchase_order_id', $order->id)->exists();
                if (! $exists) {
                    SupplierDebt::create([
                        'invoice_number' => SupplierDebt::generateInvoiceNumber(),
                        'supplier_id' => $order->supplier_id,
                        'purchase_order_id' => $order->id,
                        'transaction_date' => now()->toDateString(),
                        'due_date' => ($order->due_date ? $order->due_date->format('Y-m-d') : now()->addDays(30)->toDateString()),
                        'total_amount' => $order->total_amount,
                        'paid_amount' => 0,
                        'status' => 'unpaid',
                        'notes' => 'Auto: Hutang dari PO '.$order->po_number,
                    ]);
                }
            }

            DB::commit();

            $statusMsg = $allReceived ? 'Semua barang telah diterima penuh.' : 'Barang diterima sebagian.';

            return redirect()->route('pembelian.order.show', ['order' => $order])
                ->with('success', "Penerimaan berhasil diproses. {$statusMsg} Stok telah diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Kesalahan sistem: '.$e->getMessage())->withInput();
        }
    }

    private function getOrCreateLockedProductStock(int $productId, int $warehouseId, ?string $batchNumber, ?string $expiredDate): ProductStock
    {
        $query = ProductStock::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->whereNull('location_id');

        if ($batchNumber === null || $batchNumber === '') {
            $query->whereNull('batch_number');
        } else {
            $query->where('batch_number', $batchNumber);
        }

        if ($expiredDate === null || $expiredDate === '') {
            $query->whereNull('expired_date');
        } else {
            $query->where('expired_date', $expiredDate);
        }

        $stock = $query->lockForUpdate()->first();
        if ($stock) {
            return $stock;
        }

        try {
            return ProductStock::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'location_id' => null,
                'batch_number' => ($batchNumber === '' ? null : $batchNumber),
                'expired_date' => ($expiredDate === '' ? null : $expiredDate),
                'stock' => 0,
            ]);
        } catch (QueryException $e) {
            $stock = $query->lockForUpdate()->first();
            if ($stock) {
                return $stock;
            }

            throw $e;
        }
    }
}
