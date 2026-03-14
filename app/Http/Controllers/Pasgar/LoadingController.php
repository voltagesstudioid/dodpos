<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\Vehicle;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $query = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'creator'])
            ->latest();

        $allowedStatuses = ['pending', 'approved', 'rejected', 'disiapkan'];

        if ($request->filled('status') && in_array($request->status, $allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('transfer_number', 'like', '%' . $q . '%')
                    ->orWhereHas('fromWarehouse', fn ($w) => $w->where('name', 'like', '%' . $q . '%'))
                    ->orWhereHas('toWarehouse', fn ($w) => $w->where('name', 'like', '%' . $q . '%'))
                    ->orWhereHas('creator', fn ($u) => $u->where('name', 'like', '%' . $q . '%'));
            });
        }

        $totalCount = (clone $query)->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $approvedCount = (clone $query)->where('status', 'approved')->count();
        $otherCount = max(0, $totalCount - $pendingCount - $approvedCount);

        $loadings = $query->paginate(10)->withQueryString();

        return view('pasgar.loading.index', compact('loadings', 'totalCount', 'pendingCount', 'approvedCount', 'otherCount'));
    }

    public function create()
    {
        $mainWarehouses = Warehouse::whereDoesntHave('vehicle')->where('active', true)->get();
        $vehicles       = Vehicle::with('warehouse')->get();

        // Muat semua produk aktif (gunakan kolom yang benar: tidak ada is_active, gunakan filter lain)
        $products = Product::with('unit')->orderBy('name')->get();

        return view('pasgar.loading.create', compact('mainWarehouses', 'vehicles', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'              => 'required|date',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'vehicle_id'        => 'required|exists:vehicles,id',
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if (!$vehicle->warehouse_id) {
            return back()->with('error', 'Kendaraan ini belum ditautkan dengan Gudang Virtual.');
        }

        DB::transaction(function () use ($request, $vehicle) {
            $transfer = StockTransfer::create([
                'transfer_number'  => StockTransfer::generateNumber(),
                'date'             => $request->date,
                'from_warehouse_id'=> $request->from_warehouse_id,
                'to_warehouse_id'  => $vehicle->warehouse_id,
                'notes'            => $request->notes,
                'status'           => 'pending',
                'created_by'       => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'notes'      => $item['notes'] ?? null,
                ]);
            }
        });

        return redirect()->route('pasgar.loadings.index')
            ->with('success', 'Loading barang berhasil dibuat dan menunggu persetujuan.');
    }

    public function show(StockTransfer $loading)
    {
        $loading->load(['fromWarehouse', 'toWarehouse', 'items.product', 'creator']);
        return view('pasgar.loading.show', compact('loading'));
    }

    public function edit(StockTransfer $loading)
    {
        if ($loading->status !== 'pending') {
            return back()->with('error', 'Loading yang sudah diproses tidak dapat diedit.');
        }

        $mainWarehouses = Warehouse::whereDoesntHave('vehicle')->where('active', true)->get();
        $vehicles       = Vehicle::with('warehouse')->get();
        $products       = Product::with('unit')->orderBy('name')->get();

        return view('pasgar.loading.edit', compact('loading', 'mainWarehouses', 'vehicles', 'products'));
    }

    public function update(Request $request, StockTransfer $loading)
    {
        if ($loading->status !== 'pending') {
            return back()->with('error', 'Loading yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $loading) {
            $loading->update(['notes' => $request->notes]);
            $loading->items()->delete();

            foreach ($request->items as $item) {
                $loading->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'notes'      => $item['notes'] ?? null,
                ]);
            }
        });

        return redirect()->route('pasgar.loadings.show', $loading)
            ->with('success', 'Loading berhasil diperbarui.');
    }

    public function destroy(StockTransfer $loading)
    {
        if ($loading->status !== 'pending') {
            return back()->with('error', 'Hanya loading berstatus pending yang dapat dihapus.');
        }

        $loading->items()->delete();
        $loading->delete();

        return redirect()->route('pasgar.loadings.index')
            ->with('success', 'Loading berhasil dihapus.');
    }

    /**
     * Approve loading — pindahkan stok dari gudang asal ke gudang kendaraan.
     */
    public function approve(Request $request, StockTransfer $loading)
    {
        if ($loading->status !== 'pending') {
            return back()->with('error', 'Loading ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
        ]);

        // Set from_warehouse_id dari pilihan admin
        $loading->from_warehouse_id = $request->from_warehouse_id;
        $loading->save();

        try {
            DB::beginTransaction();

            $loading->load(['items.product', 'fromWarehouse', 'toWarehouse']);

            foreach ($loading->items as $item) {
                $sourceStock = ProductStock::where('product_id', $item->product_id)
                    ->where('warehouse_id', $loading->from_warehouse_id)
                    ->lockForUpdate()
                    ->first();

                if (!$sourceStock || $sourceStock->stock < $item->quantity) {
                    throw new \Exception(
                        "Stok produk \"{$item->product->name}\" di gudang {$loading->fromWarehouse->name} tidak mencukupi. " .
                        "Tersedia: " . ($sourceStock->stock ?? 0) . ", Dibutuhkan: {$item->quantity}"
                    );
                }

                // ── KELUAR DARI GUDANG ASAL ──────────────────────────
                $sourceStock->stock -= $item->quantity;
                $sourceStock->save();

                StockMovement::create([
                    'product_id'       => $item->product_id,
                    'warehouse_id'     => $loading->from_warehouse_id,
                    'type'             => 'out',
                    'source_type'      => 'stock_transfer',
                    'reference_number' => $loading->transfer_number,
                    'quantity'         => $item->quantity,
                    'balance'          => $sourceStock->stock,
                    'notes'            => '[Pesanan Pasgar] Keluar ke: ' . $loading->toWarehouse->name,
                    'user_id'          => Auth::id(),
                ]);

                // ── MASUK KE KENDARAAN (GUDANG TUJUAN) ───────────────
                $destStock = ProductStock::firstOrCreate(
                    ['product_id' => $item->product_id, 'warehouse_id' => $loading->to_warehouse_id, 'location_id' => null],
                    ['stock' => 0]
                );

                $destStock->stock += $item->quantity;
                $destStock->save();

                StockMovement::create([
                    'product_id'       => $item->product_id,
                    'warehouse_id'     => $loading->to_warehouse_id,
                    'type'             => 'in',
                    'source_type'      => 'stock_transfer',
                    'reference_number' => $loading->transfer_number,
                    'quantity'         => $item->quantity,
                    'balance'          => $destStock->stock,
                    'notes'            => '[Pesanan Pasgar] Masuk dari: ' . $loading->fromWarehouse->name,
                    'user_id'          => Auth::id(),
                ]);
            }

            $loading->update([
                'status'      => 'approved',
                'approved_by' => Auth::id(),
            ]);

            DB::commit();

            return back()->with('success', "Pesanan disetujui langsung. Stok dipindahkan dari {$loading->fromWarehouse->name} ke Kendaraan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses mutasi: ' . $e->getMessage());
        }
    }




    /**
     * Set status loading ke "disiapkan" — admin pilih gudang asal dan tandai barang siap.
     * OPSI B: Admin yang menentukan dari gudang mana barang diambil.
     */
    public function disiapkan(Request $request, StockTransfer $loading)
    {
        if ($loading->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan berstatus pending yang dapat diubah ke disiapkan.');
        }

        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $loading->update([
            'status'            => 'disiapkan',
            'from_warehouse_id' => $request->from_warehouse_id,
        ]);

        $warehouse = Warehouse::find($request->from_warehouse_id);

        return back()->with('success',
            "Pesanan ditandai \"Disiapkan\" dari gudang: {$warehouse->name}. Sales dapat melakukan Cross Check.");
    }
}
