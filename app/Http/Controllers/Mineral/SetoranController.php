<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralSetoran;
use App\Models\MineralVehicleStock;
use App\Models\MineralWarehouseStock;
use App\Models\MineralWarehouseMutation;
use Illuminate\Http\Request;

class SetoranController extends Controller
{
    public function index()
    {
        $setorans = MineralSetoran::with(['sales', 'verifier'])->latest()->paginate(15);
        return view('mineral.setoran.index', compact('setorans'));
    }

    public function show(MineralSetoran $setoran)
    {
        $setoran->load(['sales', 'verifier']);
        
        // Ambil sisa stok kendaraan untuk laporan
        $vehicleStocks = MineralVehicleStock::with('product')
            ->where('sales_id', $setoran->sales_id)
            ->where('leftover_qty', '>', 0)
            ->get();

        return view('mineral.setoran.show', compact('setoran', 'vehicleStocks'));
    }

    public function verify(Request $request, MineralSetoran $setoran)
    {
        if ($setoran->status === 'verified') {
            return back()->with('error', 'Setoran sudah diverifikasi sebelumnya.');
        }

        \DB::transaction(function() use ($setoran) {
            $setoran->update([
                'status' => 'verified',
                'verified_by' => auth()->id()
            ]);

            // Kembalikan stok fisik truk ke gudang utama
            $vehicleStocks = MineralVehicleStock::where('sales_id', $setoran->sales_id)->get();
            
            foreach ($vehicleStocks as $vStock) {
                if ($vStock->leftover_qty > 0) {
                    $wStock = MineralWarehouseStock::firstOrCreate(
                        ['product_id' => $vStock->product_id],
                        ['qty_dus' => 0]
                    );
                    $wStock->increment('qty_dus', $vStock->leftover_qty);

                    MineralWarehouseMutation::create([
                        'product_id' => $vStock->product_id,
                        'type' => 'in_return',
                        'qty_dus' => $vStock->leftover_qty,
                        'user_id' => auth()->id(),
                        'notes' => 'Sisa stok setoran #${setoran->id} (Sales ID: ${setoran->sales_id})'
                    ]);
                }

                // Reset truck stock for tomorrow
                $vStock->update([
                    'initial_qty' => 0,
                    'sold_qty' => 0,
                    'leftover_qty' => 0
                ]);
            }
        });

        return redirect()->route('mineral.setoran.index')->with('success', 'Rekap Setoran berhasil divalidasi dan Sisa Stok dikembalikan ke Gudang.');
    }
}
