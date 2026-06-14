<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Services\ReferenceNumberService;
use App\Support\Roles;
use App\Support\SearchSanitizer;
use App\Support\WarehouseConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /** @deprecated Use ReferenceNumberService::generateTransferRef() */
    private function generateRef(string $prefix = 'TRF'): string
    {
        return ReferenceNumberService::generateTransferRef();
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $sanitizedSearch = SearchSanitizer::sanitize($search);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Riwayat ditampilkan per dokumen transfer (reference_number), bukan per item.
        // Tetap bisa cari berdasarkan no referensi / nama barang.
        $referenceQuery = StockMovement::query()
            ->where('type', 'transfer_out')
            ->when($search !== '', function ($query) use ($sanitizedSearch) {
                $query->where(function ($q) use ($sanitizedSearch) {
                    $q->where('reference_number', 'like', "%{$sanitizedSearch}%")
                        ->orWhereHas('product', function ($p) use ($sanitizedSearch) {
                            $p->where('name', 'like', "%{$sanitizedSearch}%")
                                ->orWhere('sku', 'like', "%{$sanitizedSearch}%");
                        });
                });
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->selectRaw('reference_number, MAX(created_at) as latest_created_at')
            ->groupBy('reference_number')
            ->orderByDesc('latest_created_at');

        $paginatedRefs = $referenceQuery->paginate(15)->withQueryString();

        $referenceNumbers = collect($paginatedRefs->items())
            ->pluck('reference_number')
            ->filter()
            ->values()
            ->all();

        $outs = StockMovement::with(['product', 'warehouse', 'location', 'user'])
            ->where('type', 'transfer_out')
            ->whereIn('reference_number', $referenceNumbers)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('reference_number');

        $ins = StockMovement::with(['warehouse', 'location'])
            ->where('type', 'transfer_in')
            ->whereIn('reference_number', $referenceNumbers)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('reference_number');

        $transferDocuments = collect($referenceNumbers)->map(function ($ref) use ($outs, $ins) {
            $outItems = $outs->get($ref, collect());
            $inItems = $ins->get($ref, collect());

            $firstOut = $outItems->first();
            $firstIn = $inItems->first();

            return (object) [
                'reference_number' => $ref,
                'created_at' => optional($outItems->max('created_at')) ? \Carbon\Carbon::parse($outItems->max('created_at')) : ($firstOut?->created_at),
                'from_warehouse' => $firstOut?->warehouse,
                'from_location' => $firstOut?->location,
                'to_warehouse' => $firstIn?->warehouse,
                'to_location' => $firstIn?->location,
                'user' => $firstOut?->user,
                'total_qty' => (int) $outItems->sum('quantity'),
                'total_items' => (int) $outItems->count(),
                'total_products' => (int) $outItems->pluck('product_id')->filter()->unique()->count(),
                'products_preview' => $outItems->pluck('product.name')->filter()->unique()->take(3)->values(),
                'first_movement_id' => $firstOut?->id,
            ];
        });

        $transfers = new \Illuminate\Pagination\LengthAwarePaginator(
            $transferDocuments->values(),
            $paginatedRefs->total(),
            $paginatedRefs->perPage(),
            $paginatedRefs->currentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        // Stats untuk badge (hindari N+1 di view)
        $pendingReqCount = ProductRequest::where('status', 'approved')->count();
        $whId = WarehouseConfig::getAllowedId(strtolower((string) (Auth::user()?->role ?? '')));
        $pendingTransferQuery = StockMovement::where('type', 'transfer_in')->where('status', 'pending');
        if ($whId) {
            $pendingTransferQuery->where('warehouse_id', $whId);
        }
        $pendingTransferCount = $pendingTransferQuery->count();

        // Stats tambahan untuk KPI cards di index
        $startOfMonth = now()->startOfMonth();
        $totalTransferInMonth = StockMovement::where('type', 'transfer_out')->where('created_at', '>=', $startOfMonth)->select('reference_number')->distinct()->count('reference_number');
        $totalQtyTransferredMonth = StockMovement::where('type', 'transfer_out')->where('created_at', '>=', $startOfMonth)->sum('quantity');

        return view('gudang.transfer.index', compact(
            'transfers', 'pendingReqCount', 'pendingTransferCount', 'totalTransferInMonth', 'totalQtyTransferredMonth'
        ));
    }

    public function create()
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if (! Roles::canTransfer($role)) {
            abort(403);
        }

        $userWhId = WarehouseConfig::getAllowedId($role);

        // For admin3/admin4: only show products with stock in their own warehouse
        if ($userWhId) {
            $products = \App\Models\Product::with(['productStocks' => function ($q) use ($userWhId) {
                    $q->where('warehouse_id', $userWhId)->where('stock', '>', 0);
                }, 'unitConversions.unit', 'unit'])
                ->whereHas('productStocks', function ($q) use ($userWhId) {
                    $q->where('warehouse_id', $userWhId)->where('stock', '>', 0);
                })
                ->orderBy('name')
                ->get();
            $warehouses = \App\Models\Warehouse::where('active', true)->orderBy('name')->get();
        } else {
            // Supervisor: all products and warehouses
            $products = \App\Models\Product::with(['productStocks', 'unitConversions.unit', 'unit'])
                ->where('stock', '>', 0)
                ->orderBy('name')
                ->get();
            $warehouses = \App\Models\Warehouse::where('active', true)->orderBy('name')->get();
        }

        return view('gudang.transfer.create', compact('products', 'warehouses', 'userWhId'));
    }

    public function store(Request $request)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if (! Roles::canTransfer($role)) {
            abort(403);
        }

        $request->validate([
            'reference_number' => 'required|string|unique:stock_movements,reference_number',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_factor' => 'required|numeric|min:0.0001',
            'items.*.unit_label' => 'nullable|string',
        ]);

        $fromWarehouseId = (int) $request->from_warehouse_id;
        $toWarehouseId = (int) $request->to_warehouse_id;

        // Admin lain hanya bisa proses transfer dari gudang tempatnya bertugas.
        if ($role !== 'supervisor') {
            $userWhId = WarehouseConfig::getAllowedId($role);
            if (!$userWhId || $userWhId !== $fromWarehouseId) {
                return back()->with('error', 'Anda tidak berhak mengirim transfer dari gudang asal ini.')->withInput();
            }
        }

        try {
            DB::beginTransaction();
            $referenceNumber = $request->reference_number;
            $firstOut = null;

            foreach ($request->items as $item) {
                $productId = $item['product_id'];
                $qtyInput = (float) $item['quantity'];
                $unitFactor = (float) $item['unit_factor'];
                $unitLabel = $item['unit_label'] ?? 'Satuan Dasar';
                $qtyToTransfer = (int) round($qtyInput * $unitFactor);

                if ($qtyToTransfer <= 0) continue;

                $notesWithUnit = trim("Transfer Langsung".($request->notes ? ' | '.$request->notes : '')." | Input: {$qtyInput} {$unitLabel} (={$qtyToTransfer} satuan dasar)");

                $availableStocks = \App\Models\ProductStock::where('product_id', $productId)
                    ->where('warehouse_id', $fromWarehouseId)
                    ->where('stock', '>', 0)
                    ->orderBy('expired_date', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->get();

                $totalAvailable = (int) $availableStocks->sum('stock');
                if ($totalAvailable < $qtyToTransfer) {
                    $product = \App\Models\Product::find($productId);
                    $productLabel = $product ? "{$product->sku} - {$product->name}" : "ID {$productId}";
                    DB::rollBack();
                    return back()->with('error', "Stok {$productLabel} di gudang asal tidak mencukupi. Maksimal tersedia: {$totalAvailable} satuan dasar.")->withInput();
                }

                $remainingQty = $qtyToTransfer;
                $totalDeducted = 0;

                foreach ($availableStocks as $stock) {
                    if ($remainingQty <= 0) break;

                    $deductQty = min($stock->stock, $remainingQty);
                    $stock->stock -= $deductQty;
                    $stock->save();

                    $totalDeducted += $deductQty;
                    $qtyInUnitForThisBatch = $unitFactor > 0 ? $deductQty / $unitFactor : $deductQty;

                    $out = \App\Models\StockMovement::create([
                        'product_id' => $productId,
                        'warehouse_id' => $fromWarehouseId,
                        'location_id' => null,
                        'type' => 'transfer_out',
                        'status' => 'pending',
                        'reference_number' => $referenceNumber,
                        'batch_number' => $stock->batch_number,
                        'expired_date' => $stock->expired_date,
                        'quantity' => $deductQty,
                        'unit_id' => null, 
                        'conversion_factor' => $unitFactor,
                        'quantity_in_unit' => $qtyInUnitForThisBatch,
                        'balance' => $stock->stock,
                        'notes' => $notesWithUnit,
                        'user_id' => Auth::id(),
                    ]);

                    if (! $firstOut) $firstOut = $out;

                    \App\Models\StockMovement::create([
                        'product_id' => $productId,
                        'warehouse_id' => $toWarehouseId,
                        'location_id' => null,
                        'type' => 'transfer_in',
                        'status' => 'pending',
                        'reference_number' => $referenceNumber,
                        'batch_number' => $stock->batch_number,
                        'expired_date' => $stock->expired_date,
                        'quantity' => $deductQty,
                        'unit_id' => null,
                        'conversion_factor' => $unitFactor,
                        'quantity_in_unit' => $qtyInUnitForThisBatch,
                        'balance' => 0,
                        'notes' => $notesWithUnit,
                        'user_id' => Auth::id(),
                    ]);

                    $remainingQty -= $deductQty;
                }

                if ($totalDeducted > 0) {
                    \App\Models\Product::where('id', $productId)->decrement('stock', $totalDeducted);
                }
            }

            DB::commit();

            if ($firstOut) {
                return redirect()->route('gudang.transfer.show', $firstOut)->with('success', 'Dokumen transfer berhasil dibuat dan stok telah dikeluarkan.');
            }

            return redirect()->route('gudang.transfer')->with('success', 'Dokumen transfer berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage())->withInput();
        }
    }

    public function approvedRequests(Request $request)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        $userWhId = \App\Support\WarehouseConfig::getAllowedId($role);

        // Semua role yang punya akses gudang boleh buka menu ini
        if (! in_array($role, ['supervisor', 'admin3', 'admin4', 'gudang'], true)) {
            abort(403);
        }

        $search = trim((string) $request->input('search'));
        $statusFilter = trim((string) $request->input('status', 'pending'));
        $sanitizedSearch = SearchSanitizer::sanitize($search);

        $baseQuery = ProductRequest::query()
            ->with(['user', 'product', 'fromWarehouse', 'toWarehouse'])
            ->where('type', 'transfer')
            ->where('status', 'approved');

        // Jika bukan supervisor, hanya bisa melihat request yang 'from_warehouse_id' nya sama dengan gudang mereka
        if ($role !== 'supervisor') {
            if (!$userWhId) {
                // Jika user tidak punya gudang terhubung, tidak bisa melihat apa-apa
                $baseQuery->whereRaw('1 = 0');
            } else {
                $baseQuery->where('from_warehouse_id', $userWhId);
            }
        }

        // Stats KPI
        $totalCount = (clone $baseQuery)->count();
        $pendingCount = (clone $baseQuery)->whereNull('transfer_reference')->count();
        $processedCount = (clone $baseQuery)->whereNotNull('transfer_reference')->count();

        $query = clone $baseQuery;

        if ($statusFilter === 'pending') {
            $query->whereNull('transfer_reference');
        } elseif ($statusFilter === 'processed') {
            $query->whereNotNull('transfer_reference');
        }

        if ($search !== '') {
            $query->where(function ($q) use ($sanitizedSearch) {
                $q->orWhereHas('product', function ($p) use ($sanitizedSearch) {
                    $p->where('name', 'like', "%{$sanitizedSearch}%")
                        ->orWhere('sku', 'like', "%{$sanitizedSearch}%");
                })
                ->orWhereHas('user', function ($u) use ($sanitizedSearch) {
                    $u->where('name', 'like', "%{$sanitizedSearch}%")
                        ->orWhere('role', 'like', "%{$sanitizedSearch}%");
                });
            });
        }

        $requests = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        // Calculate badges count for tabs
        $pendingReqCount = ProductRequest::where('status', 'approved')->whereNull('transfer_reference')->count();
        $pendingTransferQuery = StockMovement::where('type', 'transfer_in')->where('status', 'pending');
        if ($userWhId) {
            $pendingTransferQuery->where('warehouse_id', $userWhId);
        }
        $pendingTransferCount = $pendingTransferQuery->count();

        return view('gudang.transfer.requests', compact(
            'requests', 'totalCount', 'pendingCount', 'processedCount', 'statusFilter',
            'pendingReqCount', 'pendingTransferCount'
        ));
    }

    public function processFromRequest(ProductRequest $productRequest)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        $fromWarehouseId = (int) ($productRequest->from_warehouse_id ?: WarehouseConfig::getMainId());
        $toWarehouseId = (int) ($productRequest->to_warehouse_id ?: WarehouseConfig::getBranchId());

        // Supervisor bisa proses transfer dari mana saja.
        // Admin lain hanya bisa proses transfer jika mereka bertugas di gudang asal.
        if ($role !== 'supervisor') {
            $userWhId = \App\Support\WarehouseConfig::getAllowedId($role);
            if (!$userWhId || $userWhId !== $fromWarehouseId) {
                abort(403, 'Anda tidak berhak memproses transfer keluar dari gudang asal ini.');
            }
        }

        if ($productRequest->type !== 'transfer' || $productRequest->status !== 'approved') {
            return back()->with('error', 'Permintaan transfer tidak valid atau belum disetujui.');
        }
        if ($productRequest->transfer_reference) {
            return back()->with('error', 'Permintaan transfer ini sudah diproses menjadi dokumen transfer.');
        }

        // Handle unit conversion
        $conversionFactor = (float) ($productRequest->conversion_factor ?: 1);
        $quantityInUnit = (float) ($productRequest->quantity ?: 0);
        $qtyToTransfer = (int) round($quantityInUnit * $conversionFactor);

        if ($qtyToTransfer <= 0) {
            return back()->with('error', 'Qty permintaan transfer tidak valid.');
        }
        if ($fromWarehouseId === $toWarehouseId) {
            return back()->with('error', 'Gudang asal dan tujuan tidak boleh sama.');
        }

        try {
            DB::beginTransaction();

            $referenceNumber = $this->generateRef('TRF');
            $unitName = $productRequest->unit?->name ?? 'satuan dasar';
            $notesWithUnit = trim("Auto dari permintaan #{$productRequest->id}".($productRequest->notes ? ' | '.$productRequest->notes : '')." | Input: {$quantityInUnit} {$unitName} (={$qtyToTransfer} satuan dasar)");

            $availableStocks = ProductStock::where('product_id', $productRequest->product_id)
                ->where('warehouse_id', $fromWarehouseId)
                ->where('stock', '>', 0)
                ->orderBy('expired_date', 'asc')
                ->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->get();

            $totalAvailable = (int) $availableStocks->sum('stock');
            if ($totalAvailable < $qtyToTransfer) {
                $product = Product::find($productRequest->product_id);
                $productLabel = $product ? "{$product->sku} - {$product->name}" : "ID {$productRequest->product_id}";
                DB::rollBack();

                return back()->with('error', "Stok {$productLabel} di gudang asal tidak mencukupi. Maksimal tersedia: {$totalAvailable} satuan dasar.");
            }

            $remainingQty = $qtyToTransfer;
            $firstOut = null;
            $totalDeducted = 0;

            /** @var \App\Models\ProductStock $stock */
            foreach ($availableStocks as $stock) {
                if ($remainingQty <= 0) {
                    break;
                }

                $deductQty = min($stock->stock, $remainingQty);
                $stock->stock -= $deductQty;
                $stock->save();

                $totalDeducted += $deductQty;

                $qtyInUnitForThisBatch = $conversionFactor > 0 ? $deductQty / $conversionFactor : $deductQty;

                $out = StockMovement::create([
                    'product_id' => $productRequest->product_id,
                    'warehouse_id' => $fromWarehouseId,
                    'location_id' => null,
                    'type' => 'transfer_out',
                    'status' => 'pending',
                    'reference_number' => $referenceNumber,
                    'batch_number' => $stock->batch_number,
                    'expired_date' => $stock->expired_date,
                    'quantity' => $deductQty,
                    'unit_id' => $productRequest->unit_id,
                    'conversion_factor' => $conversionFactor,
                    'quantity_in_unit' => $qtyInUnitForThisBatch,
                    'balance' => $stock->stock,
                    'notes' => $notesWithUnit,
                    'user_id' => Auth::id(),
                ]);

                if (! $firstOut) {
                    $firstOut = $out;
                }

                StockMovement::create([
                    'product_id' => $productRequest->product_id,
                    'warehouse_id' => $toWarehouseId,
                    'location_id' => null,
                    'type' => 'transfer_in',
                    'status' => 'pending',
                    'reference_number' => $referenceNumber,
                    'batch_number' => $stock->batch_number,
                    'expired_date' => $stock->expired_date,
                    'quantity' => $deductQty,
                    'unit_id' => $productRequest->unit_id,
                    'conversion_factor' => $conversionFactor,
                    'quantity_in_unit' => $qtyInUnitForThisBatch,
                    'balance' => 0,
                    'notes' => $notesWithUnit,
                    'user_id' => Auth::id(),
                ]);

                $remainingQty -= $deductQty;
            }

            $productRequest->transfer_reference = $referenceNumber;
            $productRequest->save();

            // Keep products.stock in sync with SUM(product_stocks.stock)
            if ($totalDeducted > 0) {
                Product::where('id', $productRequest->product_id)->decrement('stock', $totalDeducted);
            }

            DB::commit();

            if ($firstOut) {
                return redirect()->route('gudang.transfer.show', $firstOut)->with('success', 'Dokumen transfer berhasil dibuat dari permintaan.');
            }

            return redirect()->route('gudang.transfer')->with('success', 'Dokumen transfer berhasil dibuat dari permintaan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage());
        }
    }

    public function show(StockMovement $transfer)
    {
        abort_if($transfer->type !== 'transfer_out', 404);

        $outs = StockMovement::with(['product', 'warehouse', 'location', 'user', 'unit'])
            ->where('reference_number', $transfer->reference_number)
            ->where('type', 'transfer_out')
            ->orderBy('created_at')
            ->get();

        $ins = StockMovement::with(['product', 'warehouse', 'location', 'unit'])
            ->where('reference_number', $transfer->reference_number)
            ->where('type', 'transfer_in')
            ->orderBy('created_at')
            ->get();

        $summary = (object) [
            'reference_number' => $transfer->reference_number,
            'created_at' => $outs->max('created_at') ? \Carbon\Carbon::parse($outs->max('created_at')) : $transfer->created_at,
            'user' => $outs->first()?->user,
            'from_warehouse' => $outs->first()?->warehouse,
            'from_location' => $outs->first()?->location,
            'to_warehouse' => $ins->first()?->warehouse,
            'to_location' => $ins->first()?->location,
            'total_qty' => (int) $outs->sum('quantity'),
            'total_items' => (int) $outs->count(),
            'total_products' => (int) $outs->pluck('product_id')->filter()->unique()->count(),
            'notes_preview' => $outs->pluck('notes')->filter()->unique()->take(3)->values(),
        ];

        $productSummary = $outs
            ->groupBy('product_id')
            ->map(function ($rows) {
                $first = $rows->first();

                return (object) [
                    'product_name' => $first?->product?->name ?? 'Produk Dihapus',
                    'sku' => $first?->product?->sku ?? '-',
                    'qty' => (int) $rows->sum('quantity'),
                    'qty_in_unit' => $rows->first()?->quantity_in_unit,
                    'unit_name' => $rows->first()?->unit?->name ?? 'satuan dasar',
                    'conversion_factor' => $rows->first()?->conversion_factor ?? 1,
                    'rows' => (int) $rows->count(),
                ];
            })
            ->values();

        return view('gudang.transfer.show', compact('transfer', 'outs', 'ins', 'summary', 'productSummary'));
    }

    public function destroy(StockMovement $transfer)
    {
        abort_if($transfer->type !== 'transfer_out', 404);

        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if (! in_array($role, Roles::transferApprovers(), true)) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $refNumber = $transfer->reference_number;

            $hasReceipt = \App\Models\TransferReceipt::where('reference_number', $refNumber)->exists();
            if ($hasReceipt) {
                DB::rollBack();

                return back()->with('error', 'Transfer ini sudah pernah diterima (ada dokumen cross-check) dan tidak bisa dihapus. Gunakan proses retur/penyesuaian.');
            }

            $hasNonPendingIn = StockMovement::where('reference_number', $refNumber)
                ->where('type', 'transfer_in')
                ->where('status', '!=', 'pending')
                ->exists();
            if ($hasNonPendingIn) {
                DB::rollBack();

                return back()->with('error', 'Transfer ini sudah diproses di gudang tujuan dan tidak bisa dihapus.');
            }

            $movements = StockMovement::where('reference_number', $refNumber)->get();

            /** @var \App\Models\StockMovement $mov */
            foreach ($movements as $mov) {
                // Revert stock in product_stocks
                $query = ProductStock::where('product_id', $mov->product_id)
                    ->where('warehouse_id', $mov->warehouse_id);

                if ($mov->batch_number) {
                    $query->where('batch_number', $mov->batch_number);
                } else {
                    $query->whereNull('batch_number');
                }

                if ($mov->expired_date) {
                    $query->where('expired_date', $mov->expired_date);
                } else {
                    $query->whereNull('expired_date');
                }

                // Cegah Race Condition saat rollback dengan lockForUpdate
                $stockRecord = $query->lockForUpdate()->first();

                if ($mov->type === 'transfer_out') {
                    // Revert OUT by adding back
                    if ($stockRecord) {
                        $stockRecord->stock += $mov->quantity;
                        $stockRecord->save();
                    } else {
                        ProductStock::create([
                            'product_id' => $mov->product_id,
                            'warehouse_id' => $mov->warehouse_id,
                            'location_id' => null,
                            'batch_number' => $mov->batch_number,
                            'expired_date' => $mov->expired_date,
                            'stock' => $mov->quantity,
                        ]);
                    }
                }

                $mov->delete();
            }

            ProductRequest::where('transfer_reference', $refNumber)
                ->update(['transfer_reference' => null, 'status' => 'approved']);

            // Keep products.stock in sync — restore global stock when cancelling transfer
            $outTotal = $movements->where('type', 'transfer_out')->sum('quantity');
            if ($outTotal > 0) {
                $firstProduct = $movements->firstWhere('type', 'transfer_out');
                if ($firstProduct) {
                    Product::where('id', $firstProduct->product_id)->increment('stock', $outTotal);
                }
            }

            DB::commit();

            return redirect()->route('gudang.transfer')->with('success', 'Transfer stok berhasil dihapus dan dikembalikan ke posisi semula.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
