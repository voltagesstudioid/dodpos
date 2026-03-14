<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarMember;
use App\Models\ProductStock;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StokOnHandController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kendaraan yang punya gudang virtual
        $vehicles = Vehicle::with(['warehouse', 'warehouse.productStocks.product.unit'])
            ->whereHas('warehouse')
            ->get();

        // Ambil member pasgar untuk mapping kendaraan → anggota
        $members = PasgarMember::with('user')->get()->keyBy('vehicle_id');

        // Hitung total stok on-hand per kendaraan
        $vehicleStocks = $vehicles->map(function ($vehicle) use ($members) {
            $stocks = ProductStock::with(['product.unit', 'product.category'])
                ->where('warehouse_id', $vehicle->warehouse_id)
                ->where('stock', '>', 0)
                ->get();

            $member = $members->get($vehicle->id);

            return [
                'vehicle'       => $vehicle,
                'member'        => $member,
                'stocks'        => $stocks,
                'total_items'   => $stocks->count(),
                'total_qty'     => $stocks->sum('stock'),
            ];
        })->filter(fn($v) => $v['total_items'] > 0 || $request->show_empty);

        // Filter by vehicle
        $selectedVehicleId = $request->vehicle_id;
        $detailStocks = collect();
        $selectedVehicle = null;

        if ($selectedVehicleId) {
            $selectedVehicle = Vehicle::with('warehouse')->find($selectedVehicleId);
            if ($selectedVehicle && $selectedVehicle->warehouse_id) {
                $detailStocks = ProductStock::with(['product.unit', 'product.category'])
                    ->where('warehouse_id', $selectedVehicle->warehouse_id)
                    ->where('stock', '>', 0)
                    ->orderBy('product_id')
                    ->get();
            }
        }

        return view('pasgar.stok-onhand.index', compact(
            'vehicleStocks', 'vehicles', 'members',
            'selectedVehicleId', 'selectedVehicle', 'detailStocks'
        ));
    }
}
