<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\GulaSetoran::query()
            ->with(['sales', 'verifiedBy'])
            ->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('id', 'like', '%' . $q . '%')
                    ->orWhere('notes', 'like', '%' . $q . '%')
                    ->orWhereHas('sales', fn ($u) => $u->where('name', 'like', '%' . $q . '%'))
                    ->orWhereHas('verifiedBy', fn ($u) => $u->where('name', 'like', '%' . $q . '%'));
            });
        }

        $allowedStatuses = ['pending', 'verified', 'rejected'];
        if ($request->filled('status') && in_array($request->status, $allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $totalCount = (clone $query)->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $verifiedCount = (clone $query)->where('status', 'verified')->count();
        $rejectedCount = (clone $query)->where('status', 'rejected')->count();

        $totalCash = (float) (clone $query)->sum('total_cash');
        $totalPiutang = (float) (clone $query)->sum('total_piutang');

        $todayCount = (clone $query)->whereDate('date', today())->count();

        $setorans = $query->paginate(15)->withQueryString();

        return view('gula.setoran.index', compact(
            'setorans',
            'totalCount',
            'pendingCount',
            'verifiedCount',
            'rejectedCount',
            'totalCash',
            'totalPiutang',
            'todayCount',
        ));
    }

    public function show(\App\Models\GulaSetoran $setoran)
    {
        $setoran->load('sales', 'verifiedBy');
        
        // Ambil stok tersisa dari kendaraan sales ini (Snapshot)
        $vehicleStocks = \App\Models\GulaVehicleStock::where('sales_id', $setoran->sales_id)
            ->with(['product', 'vehicle'])
            ->get();
            
        // Ambil transaksi hari ini dari sales ini
        $transactions = \App\Models\GulaTransaction::where('sales_id', $setoran->sales_id)
            ->whereDate('date', $setoran->date)
            ->with(['customer', 'items'])
            ->get();
            
        return view('gula.setoran.show', compact('setoran', 'vehicleStocks', 'transactions'));
    }

    public function verify(\App\Models\GulaSetoran $setoran)
    {
        if ($setoran->status === 'verified') {
            return back()->with('error', 'Setoran ini sudah divalidasi dan ditutup sebelumnya.');
        }

        DB::transaction(function () use ($setoran) {
            // Update status setoran menjadi terverifikasi
            $setoran->update([
                'status' => 'verified',
                'verified_by' => Auth::id()
            ]);

            // Kembalikan sisa stok kendaraan kembali ke Gudang Utama
            $vehicleStocks = \App\Models\GulaVehicleStock::where('sales_id', $setoran->sales_id)->get();
            
            foreach ($vehicleStocks as $vStock) {
                if ($vStock->qty_karung > 0 || $vStock->qty_eceran > 0) {
                    $warehouse = \App\Models\GulaWarehouseStock::firstOrCreate(
                        ['gula_product_id' => $vStock->gula_product_id],
                        ['qty_karung' => 0, 'qty_bal' => 0, 'qty_eceran' => 0]
                    );

                    // Tambahkan balik ke Gudang
                    $warehouse->increment('qty_karung', $vStock->qty_karung);
                    $warehouse->increment('qty_eceran', $vStock->qty_eceran);

                    // Kosongkan muatan kendaraan (Selesai shift/Closing)
                    $vStock->update([
                        'qty_karung' => 0,
                        'qty_eceran' => 0
                    ]);
                }
            }
            
            // Tandai surat jalan loading hari ini sebagai selesai
            \App\Models\GulaLoading::where('sales_id', $setoran->sales_id)
                ->whereDate('date', $setoran->date)
                ->where('status', 'loaded')
                ->update(['status' => 'returned']);
        });

        return redirect()->route('gula.setoran.index')->with('success', 'Setoran telah berhasil divalidasi. Sisa stok armada ditarik & dikembalikan ke Gudang Utama.');
    }
}
