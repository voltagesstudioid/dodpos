<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Vehicle::query()->with('warehouse')->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('license_plate', 'like', '%' . $q . '%')
                    ->orWhere('type', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhereHas('warehouse', function ($w) use ($q) {
                        $w->where('code', 'like', '%' . $q . '%')
                            ->orWhere('name', 'like', '%' . $q . '%');
                    });
            });
        }

        if ($request->filled('link')) {
            if ($request->link === 'linked') {
                $query->whereNotNull('warehouse_id');
            } elseif ($request->link === 'unlinked') {
                $query->whereNull('warehouse_id');
            }
        }

        $totalCount = (clone $query)->count();
        $linkedCount = (clone $query)->whereNotNull('warehouse_id')->count();
        $unlinkedCount = (clone $query)->whereNull('warehouse_id')->count();

        $vehicles = $query->paginate(10)->withQueryString();

        return view('pasgar.vehicles.index', compact('vehicles', 'totalCount', 'linkedCount', 'unlinkedCount'));
    }

    public function create()
    {
        return view('pasgar.vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // First create the virtual warehouse for this vehicle
            $warehouseName = "Kendaraan " . $request->license_plate;
            if ($request->type) {
                $warehouseName .= " (" . $request->type . ")";
            }
            
            $warehouse = \App\Models\Warehouse::create([
                'name' => $warehouseName,
                'code' => 'VH-' . strtoupper(str_replace(' ', '', $request->license_plate)),
                'description' => 'Virtual Warehouse untuk Pasgar Kendaraan ' . $request->license_plate,
                'active' => true,
            ]);

            // Then create the vehicle and link it
            \App\Models\Vehicle::create([
                'license_plate' => $request->license_plate,
                'type' => $request->type,
                'description' => $request->description,
                'warehouse_id' => $warehouse->id,
            ]);
        });

        return redirect()->route('pasgar.vehicles.index')
            ->with('success', 'Kendaraan Pasgar berhasil didaftarkan beserta Gudang Virtualnya.');
    }

    public function edit(\App\Models\Vehicle $vehicle)
    {
        return view('pasgar.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, \App\Models\Vehicle $vehicle)
    {
        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate,' . $vehicle->id,
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $vehicle) {
            $vehicle->update([
                'license_plate' => $request->license_plate,
                'type' => $request->type,
                'description' => $request->description,
            ]);

            // Update associated warehouse name if requested
            if ($vehicle->warehouse) {
                $warehouseName = "Kendaraan " . $vehicle->license_plate;
                if ($vehicle->type) {
                    $warehouseName .= " (" . $vehicle->type . ")";
                }

                $vehicle->warehouse->update([
                    'name' => $warehouseName,
                    'code' => 'VH-' . strtoupper(str_replace(' ', '', $vehicle->license_plate)),
                ]);
            }
        });

        return redirect()->route('pasgar.vehicles.index')
            ->with('success', 'Data Kendaraan Pasgar berhasil diperbarui.');
    }

    public function destroy(\App\Models\Vehicle $vehicle)
    {
        // Prevent deletion if the virtual warehouse has stock
        if ($vehicle->warehouse && $vehicle->warehouse->productStocks()->where('quantity', '>', 0)->exists()) {
            return redirect()->route('pasgar.vehicles.index')
                ->with('error', 'Kendaraan tidak dapat dihapus karena Gudang Berjalannya masih memiliki stok barang. Silakan lakukan Unloading terlebih dahulu.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($vehicle) {
            $warehouse = $vehicle->warehouse;
            $vehicle->delete();
            
            // Optionally delete the warehouse if it's empty, or just mark it inactive
            if ($warehouse) {
                $warehouse->update(['active' => false]);
            }
        });

        return redirect()->route('pasgar.vehicles.index')
            ->with('success', 'Kendaraan Pasgar berhasil dihapus dan Gudang Virtual dinonaktifkan.');
    }
}
