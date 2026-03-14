<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Return list kendaraan yang aktif beserta info gudang.
     * Digunakan oleh mobile app untuk pilih kendaraan saat canvas order.
     */
    public function index(Request $request)
    {
        $vehicles = Vehicle::with('warehouse')
            ->whereNotNull('warehouse_id')
            ->orderBy('name')
            ->get()
            ->map(fn($v) => [
                'id'             => $v->id,
                'name'           => $v->name,
                'plate_number'   => $v->plate_number ?? '-',
                'warehouse_id'   => $v->warehouse_id,
                'warehouse_name' => $v->warehouse?->name ?? '-',
            ]);

        return response()->json([
            'status' => 'success',
            'data'   => $vehicles,
        ]);
    }
}
