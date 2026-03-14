<?php

namespace App\Http\Controllers\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasSetoran;
use App\Models\KanvasVehicleStock;
use Illuminate\Support\Facades\Auth;

class SetoranController extends Controller
{
    public function index(Request $request)
    {
        $query = KanvasSetoran::query()
            ->with(['sales', 'verifier'])
            ->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('id', 'like', '%' . $q . '%')
                    ->orWhereHas('sales', fn ($u) => $u->where('name', 'like', '%' . $q . '%'))
                    ->orWhereHas('verifier', fn ($u) => $u->where('name', 'like', '%' . $q . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $totalCount = (clone $query)->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $verifiedCount = (clone $query)->where('status', 'verified')->count();
        $todayCount = (clone $query)->whereDate('created_at', today())->count();

        $verifiedTodayCash = (float) KanvasSetoran::whereDate('created_at', today())
            ->where('status', 'verified')
            ->sum('actual_cash');

        $setorans = $query->paginate(15)->withQueryString();

        return view('kanvas.setoran.index', compact(
            'setorans',
            'totalCount',
            'pendingCount',
            'verifiedCount',
            'todayCount',
            'verifiedTodayCash',
        ));
    }

    public function show($id)
    {
        $setoran = KanvasSetoran::with('sales')->findOrFail($id);
        
        // Cek sisa mobil yang belum nol (di saat disetor)
        $leftovers = KanvasVehicleStock::with('product')
                            ->where('sales_id', $setoran->sales_id)
                            ->where('leftover_qty', '>', 0)
                            ->get();

        return view('kanvas.setoran.show', compact('setoran', 'leftovers'));
    }

    public function verify($id)
    {
        $setoran = KanvasSetoran::findOrFail($id);
        
        // Pindahkan sisa stok mobil kanvas kembali ke Gudang Utama
        $leftovers = KanvasVehicleStock::where('sales_id', $setoran->sales_id)
                        ->where('leftover_qty', '>', 0)
                        ->get();
                        
        foreach ($leftovers as $l) {
            // Unloading (Kembali ke Warehouse)
            $whStock = \App\Models\KanvasWarehouseStock::where('product_id', $l->product_id)->first();
            if ($whStock) {
                $whStock->increment('qty_tersedia', $l->leftover_qty);
            }
            
            // Nol-kan sisa barang di mobil
            $l->update(['initial_qty' => 0, 'sold_qty' => 0, 'leftover_qty' => 0]);
        }

        $setoran->update([
            'status' => 'verified',
            'verifier_id' => Auth::id()
        ]);

        return redirect()->route('kanvas.setoran.index')->with('success', 'Setoran divalidasi dan barang dikembalikan ke gudang otomatis.');
    }
}
