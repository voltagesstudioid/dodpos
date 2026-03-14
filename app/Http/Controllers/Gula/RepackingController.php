<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepackingController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\GulaRepacking::query()
            ->with(['product', 'user'])
            ->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('notes', 'like', '%' . $q . '%')
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'like', '%' . $q . '%'))
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', '%' . $q . '%'));
            });
        }

        if ($request->filled('product_id')) {
            $query->where('gula_product_id', $request->product_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $totalCount = (clone $query)->count();
        $totalKarung = (float) (clone $query)->sum('minus_qty_karung');
        $totalEceran = (float) (clone $query)->sum('plus_qty_eceran');
        $totalSusut = (float) (clone $query)->sum('loss_qty_eceran');
        $todayCount = (clone $query)->whereDate('date', today())->count();

        $productOptions = \App\Models\GulaProduct::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $repackings = $query->paginate(15)->withQueryString();

        return view('gula.repacking.index', compact(
            'repackings',
            'productOptions',
            'totalCount',
            'totalKarung',
            'totalEceran',
            'totalSusut',
            'todayCount',
        ));
    }

    public function create()
    {
        $products = \App\Models\GulaProduct::where('is_active', true)->with('warehouseStocks')->get();
        return view('gula.repacking.create', compact('products'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'gula_product_id' => 'required|exists:gula_products,id',
            'date' => 'required|date',
            'qty_karung_dibongkar' => 'required|numeric|min:1',
            'loss_qty_eceran' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $product = \App\Models\GulaProduct::findOrFail($validated['gula_product_id']);
            $stock = $product->warehouseStocks()->first();

            if (!$stock || $stock->qty_karung < $validated['qty_karung_dibongkar']) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'qty_karung_dibongkar' => 'Stok Karung tidak mencukupi untuk dibongkar.'
                ]);
            }

            // Hitung konversi eceran dari karungan (Berdasarkan master "qty_per_karung")
            $expectedEceran = $validated['qty_karung_dibongkar'] * $product->qty_per_karung;
            $lossEceran = $validated['loss_qty_eceran'] ?? 0;
            
            // Real eceran yang didapat (Kurangi susut misal tumpah/basah)
            $actualEceranAdded = $expectedEceran - $lossEceran;

            // Catat history repacking
            \App\Models\GulaRepacking::create([
                'gula_product_id' => $product->id,
                'user_id' => Auth::id(),
                'date' => $validated['date'],
                'minus_qty_karung' => $validated['qty_karung_dibongkar'],
                'plus_qty_eceran' => $actualEceranAdded,
                'loss_qty_eceran' => $lossEceran,
                'notes' => $validated['notes'],
            ]);

            // Update Stok Master Gudang (- Karung, + Eceran)
            $stock->decrement('qty_karung', $validated['qty_karung_dibongkar']);
            $stock->increment('qty_eceran', $actualEceranAdded);
        });

        return redirect()->route('gula.repacking.index')->with('success', 'Bongkar karung Gula berhasil diproses. Stok telah dikurangi dan eceran bertambah.');
    }
}
