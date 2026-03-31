<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Services\ReferenceNumberService;
use App\Support\Roles;
use App\Support\SearchSanitizer;
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
        $whId = auth()->user()->employee?->warehouse_id;
        $pendingTransferQuery = StockMovement::where('type', 'transfer_in')->where('status', 'pending');
        if ($whId) {
            $pendingTransferQuery->where('warehouse_id', $whId);
        }
        $pendingTransferCount = $pendingTransferQuery->count();

        return view('gudang.transfer.index', compact(
            'transfers', 'pendingReqCount', 'pendingTransferCount'
        ));
    }

    public function create()
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if (! Roles::canTransfer($role)) {
            abort(403);
        }

        return redirect()->route('gudang.transfer.requests');
    }

    public function store(Request $request)
    {
        return back()->with('error', 'Transfer harus dibuat dari permintaan yang disetujui Supervisor.');
    }

    public function approvedRequests(Request $request)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if (! in_array($role, Roles::transferApprovers(), true)) {
            abort(403);
        }

        $search = trim((string) $request->input('search'));
        $sanitizedSearch = SearchSanitizer::sanitize($search);

        $query = ProductRequest::query()
            ->with(['user', 'product', 'fromWarehouse', 'toWarehouse'])
            ->where('type', 'transfer')
            ->where('status', 'approved')
            ->whereNull('transfer_reference')
            ->orderByDesc('created_at');

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

        $requests = $query->paginate(15)->withQueryString();

        return view('gudang.transfer.requests', compact('requests'));
    }

    public function processFromRequest(ProductRequest $productRequest)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if (! in_array($role, Roles::transferApprovers(), true)) {
            abort(403);
        }
        if ($productRequest->type !== 'transfer' || $productRequest->status !== 'approved') {
            return back()->with('error', 'Permintaan transfer tidak valid atau belum disetujui.');
        }
        if ($productRequest->transfer_reference) {
            return back()->with('error', 'Permintaan transfer ini sudah diproses menjadi dokumen transfer.');
        }

        $fromWarehouseId = (int) ($productRequest->from_warehouse_id ?: 1);
        $toWarehouseId = (int) ($productRequest->to_warehouse_id ?: 2);

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

            /** @var \App\Models\ProductStock $stock */
            foreach ($availableStocks as $stock) {
                if ($remainingQty <= 0) {
                    break;
                }

                $deductQty = min($stock->stock, $remainingQty);
                $stock->stock -= $deductQty;
                $stock->save();

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
                    'quantity_in_unit' => $quantityInUnit,
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
                    'quantity_in_unit' => $quantityInUnit,
                    'balance' => 0,
                    'notes' => $notesWithUnit,
                    'user_id' => Auth::id(),
                ]);

                $remainingQty -= $deductQty;
            }

            $productRequest->transfer_reference = $referenceNumber;
            $productRequest->save();

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

            // Note: Global product stock remains unchanged during transfer
            DB::commit();

            return redirect()->route('gudang.transfer')->with('success', 'Transfer stok berhasil dihapus dan dikembalikan ke posisi semula.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
