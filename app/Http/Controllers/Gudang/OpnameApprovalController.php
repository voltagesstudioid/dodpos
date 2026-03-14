<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\StockOpnameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpnameApprovalController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureSupervisor();

        $query = StockOpnameSession::with(['warehouse', 'creator'])
            ->where('status', 'submitted')
            ->orderByDesc('submitted_at')
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('reference_number', 'like', '%'.$q.'%')
                    ->orWhereHas('creator', function ($u) use ($q) {
                        $u->where('name', 'like', '%'.$q.'%');
                    })
                    ->orWhereHas('warehouse', function ($w) use ($q) {
                        $w->where('name', 'like', '%'.$q.'%');
                    });
            });
        }

        $sessions = $query->paginate(15)->withQueryString();

        return view('gudang.opname_approval.index', compact('sessions'));
    }

    public function show(StockOpnameSession $session)
    {
        $this->ensureSupervisor();

        $session->load(['warehouse', 'creator', 'items.product']);

        return view('gudang.opname_approval.show', compact('session'));
    }

    public function approve(Request $request, StockOpnameSession $session)
    {
        $this->ensureSupervisor();

        $request->validate([
            'approval_notes' => 'nullable|string|max:2000',
        ]);

        if ($session->status !== 'submitted') {
            return back()->with('error', 'Sesi ini tidak dalam status submitted.');
        }

        $session->load(['items', 'warehouse']);
        if ($session->items->count() === 0) {
            return back()->with('error', 'Sesi tidak memiliki item.');
        }

        try {
            DB::beginTransaction();

            $session = StockOpnameSession::whereKey($session->id)->lockForUpdate()->first();
            if (! $session || $session->status !== 'submitted') {
                DB::rollBack();

                return back()->with('error', 'Sesi sudah diproses oleh user lain.');
            }

            $referenceNumber = $session->reference_number ?: $this->generateRef('OPN');

            $items = $session->items()->with('product')->lockForUpdate()->get();
            foreach ($items as $item) {
                $diff = (int) $item->difference_qty;
                if ($diff === 0) {
                    continue;
                }

                $product = Product::whereKey($item->product_id)->lockForUpdate()->first();
                if (! $product) {
                    DB::rollBack();

                    return back()->with('error', 'Produk tidak ditemukan untuk salah satu item.');
                }

                $warehouseId = (int) $session->warehouse_id;

                if ($diff > 0) {
                    $stock = ProductStock::firstOrCreate([
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                        'location_id' => null,
                        'batch_number' => null,
                        'expired_date' => null,
                    ], ['stock' => 0]);
                    $stock->stock += $diff;
                    $stock->save();

                    $product->stock += $diff;
                    $product->save();

                    StockMovement::create([
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                        'location_id' => null,
                        'type' => 'adjustment',
                        'status' => 'completed',
                        'source_type' => 'opname_session',
                        'reference_number' => $referenceNumber,
                        'batch_number' => null,
                        'expired_date' => null,
                        'quantity' => $diff,
                        'balance' => $stock->stock,
                        'notes' => "[OPNAME] Session #{$session->id} | Sistem: {$item->system_qty}, Fisik: {$item->physical_qty}".($item->notes ? " | {$item->notes}" : ''),
                        'user_id' => Auth::id(),
                    ]);
                } else {
                    $qtyToRemove = abs($diff);

                    $stockRecords = ProductStock::where('product_id', $product->id)
                        ->where('warehouse_id', $warehouseId)
                        ->where('stock', '>', 0)
                        ->orderBy('expired_date', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->lockForUpdate()
                        ->get();

                    $totalAvailable = (int) $stockRecords->sum('stock');
                    if ($totalAvailable < $qtyToRemove) {
                        DB::rollBack();

                        return back()->with('error', "Stok {$product->sku} - {$product->name} tidak mencukupi untuk penyesuaian minus. Tersedia: {$totalAvailable}.");
                    }

                    $remaining = $qtyToRemove;
                    foreach ($stockRecords as $stock) {
                        if ($remaining <= 0) {
                            break;
                        }
                        $deduct = min($stock->stock, $remaining);
                        $stock->stock -= $deduct;
                        $stock->save();
                        $remaining -= $deduct;
                    }

                    $product->stock += $diff;
                    if ($product->stock < 0) {
                        DB::rollBack();

                        return back()->with('error', 'Approval opname dibatalkan karena stok global menjadi minus.');
                    }
                    $product->save();

                    StockMovement::create([
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                        'location_id' => null,
                        'type' => 'adjustment',
                        'status' => 'completed',
                        'source_type' => 'opname_session',
                        'reference_number' => $referenceNumber,
                        'batch_number' => null,
                        'expired_date' => null,
                        'quantity' => $diff,
                        'balance' => (int) ProductStock::where('product_id', $product->id)->where('warehouse_id', $warehouseId)->sum('stock'),
                        'notes' => "[OPNAME] Session #{$session->id} | Sistem: {$item->system_qty}, Fisik: {$item->physical_qty}".($item->notes ? " | {$item->notes}" : ''),
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            $session->status = 'approved';
            $session->reference_number = $referenceNumber;
            $session->approved_by = Auth::id();
            $session->approved_at = now();
            $session->approval_notes = $request->approval_notes;
            $session->save();

            DB::commit();

            return redirect()->route('gudang.opname_sessions.index')->with('success', 'Sesi opname berhasil di-approve dan stok telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage());
        }
    }

    public function reject(Request $request, StockOpnameSession $session)
    {
        $this->ensureSupervisor();

        $request->validate([
            'approval_notes' => 'required|string|max:2000',
        ]);

        if ($session->status !== 'submitted') {
            return back()->with('error', 'Sesi ini tidak dalam status submitted.');
        }

        $session->status = 'rejected';
        $session->approved_by = Auth::id();
        $session->approved_at = now();
        $session->approval_notes = $request->approval_notes;
        $session->save();

        return redirect()->route('gudang.opname_sessions.index')->with('success', 'Sesi opname ditolak.');
    }

    private function ensureSupervisor(): void
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if ($role !== 'supervisor') {
            abort(403);
        }
    }

    private function generateRef(string $prefix): string
    {
        $date = date('Ymd');
        $base = "{$prefix}-{$date}";
        $last = StockOpnameSession::where('reference_number', 'like', $base.'-%')
            ->orderBy('reference_number', 'desc')
            ->first();

        if (! $last || ! $last->reference_number) {
            return $base.'-001';
        }
        $lastNum = (int) substr($last->reference_number, -3);

        return $base.'-'.str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}
