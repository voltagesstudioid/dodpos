<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\TransferReceipt;
use App\Models\TransferReceiptItem;
use App\Support\SearchSanitizer;
use App\Support\WarehouseConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenerimaanTransferController extends Controller
{
    /**
     * Tampilkan daftar transfer yang masuk ke Gudang Cabang (Admin 4)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        // Get branch warehouse ID dynamically from config
        $warehouseId = WarehouseConfig::getBranchId();

        // Only allow Admin 4, Admin with access, or Supervisor
        if (! WarehouseConfig::canAccess($role, $warehouseId)) {
            abort(403, 'Anda tidak memiliki akses ke Penerimaan Transfer cabang.');
        }

        $search = trim((string) $request->input('search'));
        $status = trim((string) $request->input('status'));
        $sanitizedSearch = SearchSanitizer::sanitize($search);

        // Ambil semua dokumen transfer yang masuk ke gudang ini
        $referenceQuery = StockMovement::query()
            ->where('type', 'transfer_in')
            ->where('warehouse_id', $warehouseId)
            ->whereIn('status', ['pending', 'partial', 'completed'])
            ->when($status !== '' && in_array($status, ['pending', 'partial', 'completed'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search !== '', function ($query) use ($sanitizedSearch) {
                $query->where(function ($q) use ($sanitizedSearch) {
                    $q->where('reference_number', 'like', "%{$sanitizedSearch}%")
                        ->orWhere('notes', 'like', "%{$sanitizedSearch}%");
                });
            })
            ->selectRaw('reference_number, MAX(created_at) as latest_created_at, MAX(status) as current_status')
            ->groupBy('reference_number')
            ->orderByDesc('latest_created_at');

        $paginatedRefs = $referenceQuery->paginate(15)->withQueryString();

        $referenceNumbers = collect($paginatedRefs->items())
            ->pluck('reference_number')
            ->filter()
            ->values()
            ->all();

        $ins = StockMovement::with(['product', 'warehouse', 'location', 'user', 'unit'])
            ->where('type', 'transfer_in')
            ->whereIn('reference_number', $referenceNumbers)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('reference_number');

        $receiptLatest = TransferReceipt::query()
            ->where('warehouse_id', $warehouseId)
            ->whereIn('reference_number', $referenceNumbers)
            ->orderByDesc('id')
            ->get()
            ->groupBy('reference_number')
            ->map(fn ($rows) => $rows->first());

        $transferDocuments = collect($referenceNumbers)->map(function ($ref) use ($ins) {
            $inItems = $ins->get($ref, collect());
            $firstIn = $inItems->first();

            return (object) [
                'reference_number' => $ref,
                'created_at' => $firstIn?->created_at,
                'status' => $firstIn?->status ?? 'pending',
                'to_warehouse' => $firstIn?->warehouse,
                'total_qty' => (int) $inItems->sum('quantity'),
                'total_items' => (int) $inItems->count(),
                'products_preview' => $inItems->pluck('product.name')->filter()->unique()->take(3)->values(),
                'total_products' => (int) $inItems->pluck('product.name')->filter()->unique()->count(),
            ];
        });

        $transferDocuments = $transferDocuments->map(function ($doc) use ($receiptLatest, $ins) {
            $ref = $doc->reference_number;
            $inItems = $ins->get($ref, collect());

            $status = 'pending';
            if ($inItems->every(fn ($m) => $m->status === 'completed')) {
                $status = 'completed';
            } elseif ($inItems->contains(fn ($m) => $m->status === 'partial')) {
                $status = 'partial';
            }

            $receipt = $receiptLatest->get($ref);
            $doc->status = $receipt?->status ?? $status;
            $doc->last_received_at = $receipt?->created_at;

            return $doc;
        });

        // Re-paginate the mapped collection correctly
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

        return view('gudang.terima_transfer.index', compact('transfers'));
    }

    /**
     * Tampilkan detail transfer untuk proses Cross-Check
     */
    public function show($reference_number)
    {
        $warehouseId = WarehouseConfig::getBranchId(); // Gudang Cabang

        $ins = StockMovement::with(['product', 'warehouse', 'location', 'unit'])
            ->where('reference_number', $reference_number)
            ->where('type', 'transfer_in')
            ->where('warehouse_id', $warehouseId)
            ->whereIn('status', ['pending', 'partial', 'completed'])
            ->orderBy('created_at')
            ->get();

        if ($ins->isEmpty()) {
            abort(404, 'Data Transfer tidak ditemukan.');
        }

        $firstIn = $ins->first();
        $isPending = $ins->every(fn ($m) => $m->status === 'pending');

        $summary = (object) [
            'reference_number' => $reference_number,
            'created_at' => $firstIn->created_at,
            'status' => $firstIn->status,
            'to_warehouse' => $firstIn->warehouse,
            'total_qty' => (int) $ins->sum('quantity'),
            'total_qty_in_unit' => $ins->first()?->quantity_in_unit,
            'unit_name' => $ins->first()?->unit?->name ?? 'satuan dasar',
            'conversion_factor' => $ins->first()?->conversion_factor ?? 1,
            'total_items' => (int) $ins->count(),
        ];

        $receipts = TransferReceipt::with(['items.product'])
            ->where('warehouse_id', $warehouseId)
            ->where('reference_number', $reference_number)
            ->orderByDesc('id')
            ->get();

        return view('gudang.terima_transfer.show', compact('ins', 'summary', 'isPending', 'receipts'));
    }

    /**
     * Proses Penerimaan (Cross-Check ACC). Update status jadi completed dan tambah stok cabang.
     */
    public function receive(Request $request, $reference_number)
    {
        $warehouseId = WarehouseConfig::getBranchId();

        $request->validate([
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.received_qty' => 'required|integer|min:0',
            'items.*.quality_ok' => 'nullable|boolean',
            'items.*.spec_ok' => 'nullable|boolean',
            'items.*.packaging_ok' => 'nullable|boolean',
            'items.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $movementsIn = StockMovement::where('reference_number', $reference_number)
                ->where('type', 'transfer_in')
                ->where('warehouse_id', $warehouseId)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->get();

            if ($movementsIn->isEmpty()) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Transfer ini sudah diterima atau tidak valid.');
            }

            $payloadItems = $request->input('items', []);
            $payloadById = collect($payloadItems)->mapWithKeys(function ($row, $key) {
                $id = is_numeric($key) ? (int) $key : (int) ($row['id'] ?? 0);

                return $id > 0 ? [$id => $row] : [];
            });

            $receipt = TransferReceipt::create([
                'reference_number' => $reference_number,
                'warehouse_id' => $warehouseId,
                'received_by' => Auth::id(),
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            $anyPartial = false;

            /** @var \App\Models\StockMovement $movIn */
            foreach ($movementsIn as $movIn) {
                $row = (array) ($payloadById->get((int) $movIn->id) ?? []);
                $expected = (int) $movIn->quantity;
                $received = (int) ($row['received_qty'] ?? 0);

                if ($received > $expected) {
                    DB::rollBack();

                    return back()->with('error', 'Qty diterima tidak boleh melebihi qty dikirim.');
                }

                $rejected = $expected - $received;
                $qtyOk = $rejected === 0;
                if (! $qtyOk) {
                    $anyPartial = true;
                }

                $qualityOk = array_key_exists('quality_ok', $row) ? (bool) $row['quality_ok'] : true;
                $specOk = array_key_exists('spec_ok', $row) ? (bool) $row['spec_ok'] : true;
                $packagingOk = array_key_exists('packaging_ok', $row) ? (bool) $row['packaging_ok'] : true;

                TransferReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'stock_movement_id' => $movIn->id,
                    'product_id' => $movIn->product_id,
                    'expected_qty' => $expected,
                    'received_qty' => $received,
                    'rejected_qty' => $rejected,
                    'qty_ok' => $qtyOk,
                    'quality_ok' => $qualityOk,
                    'spec_ok' => $specOk,
                    'packaging_ok' => $packagingOk,
                    'notes' => $row['notes'] ?? null,
                ]);

                if ($received > 0) {
                    $destStock = ProductStock::where([
                        'product_id' => $movIn->product_id,
                        'warehouse_id' => $warehouseId,
                        'batch_number' => $movIn->batch_number,
                        'expired_date' => $movIn->expired_date,
                    ])->lockForUpdate()->first();

                    if (! $destStock) {
                        $destStock = ProductStock::create([
                            'product_id' => $movIn->product_id,
                            'warehouse_id' => $warehouseId,
                            'location_id' => null,
                            'batch_number' => $movIn->batch_number,
                            'expired_date' => $movIn->expired_date,
                            'stock' => 0,
                        ]);
                    }

                    $destStock->stock += $received;
                    $destStock->save();

                    $movIn->balance = $destStock->stock;
                }

                $movIn->status = $qtyOk ? 'completed' : 'partial';
                $movIn->save();
            }

            $receipt->status = $anyPartial ? 'partial' : 'completed';
            $receipt->save();

            $outStatus = $anyPartial ? 'partial' : 'completed';
            StockMovement::where('reference_number', $reference_number)
                ->where('type', 'transfer_out')
                ->update(['status' => $outStatus]);

            if (! $anyPartial) {
                \App\Models\ProductRequest::where('transfer_reference', $reference_number)
                    ->update(['status' => 'completed']);
            }

            DB::commit();

            $msg = $anyPartial
                ? 'Cross-check tersimpan (parsial). Item yang tidak sesuai tercatat sebagai selisih.'
                : 'Barang transfer berhasil di-ACC (Cross-Check Selesai). Stok gudang cabang telah ditambahkan.';

            return redirect()->route('gudang.terima_transfer.show', $reference_number)->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage());
        }
    }
}
