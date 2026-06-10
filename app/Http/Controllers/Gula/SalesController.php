<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaSales;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $sales = GulaSales::with(['user', 'vehicle'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_sales', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => GulaSales::count(),
            'aktif' => GulaSales::where('status', 'aktif')->count(),
            'nonaktif' => GulaSales::where('status', 'nonaktif')->count(),
            'cuti' => GulaSales::where('status', 'cuti')->count(),
        ];

        return view('gula.sales.index', compact('sales', 'stats'));
    }

    public function create()
    {
        $vehicles = Vehicle::whereNull('sales_id')
            ->orderBy('license_plate')
            ->get();

        return view('gula.sales.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif,cuti',
            'keterangan' => 'nullable|string',
        ]);

        $validated['kode_sales'] = GulaSales::generateKode();

        $salesRecord = GulaSales::create($validated);

        // Assign vehicle (polymorphic)
        if (!empty($validated['vehicle_id'])) {
            Vehicle::where('id', $validated['vehicle_id'])->update([
                'sales_type' => GulaSales::class,
                'sales_id' => $salesRecord->id,
            ]);
        }

        return redirect()->route('gula.sales.index')
            ->with('success', 'Data Sales berhasil ditambahkan.');
    }

    public function show(GulaSales $sales)
    {
        $sales->load(['user', 'vehicle', 'loadings.produk', 'penjualans.pelanggan', 'setorans']);

        return view('gula.sales.show', compact('sales'));
    }

    public function edit(GulaSales $sales)
    {
        // Available vehicles: unassigned OR currently assigned to this sales
        $vehicles = Vehicle::where(function ($q) use ($sales) {
                $q->whereNull('sales_id')
                    ->orWhere(function ($sub) use ($sales) {
                        $sub->where('sales_type', GulaSales::class)
                            ->where('sales_id', $sales->id);
                    });
            })
            ->orderBy('license_plate')
            ->get();

        return view('gula.sales.edit', compact('sales', 'vehicles'));
    }

    public function update(Request $request, GulaSales $sales)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif,cuti',
            'keterangan' => 'nullable|string',
        ]);

        $sales->update([
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'],
            'target_harian' => $validated['target_harian'] ?? 0,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
        ]);

        // Handle vehicle reassignment
        $currentVehicle = $sales->vehicle;
        $newVehicleId = $validated['vehicle_id'] ?? null;

        // Unassign old vehicle if changed
        if ($currentVehicle && $newVehicleId && $currentVehicle->id !== (int) $newVehicleId) {
            Vehicle::where('id', $currentVehicle->id)->update([
                'sales_type' => null,
                'sales_id' => null,
            ]);
        } elseif ($currentVehicle && !$newVehicleId) {
            // User cleared the vehicle selection
            Vehicle::where('id', $currentVehicle->id)->update([
                'sales_type' => null,
                'sales_id' => null,
            ]);
        }

        // Assign new vehicle
        if ($newVehicleId) {
            Vehicle::where('id', $newVehicleId)->update([
                'sales_type' => GulaSales::class,
                'sales_id' => $sales->id,
            ]);
        }

        return redirect()->route('gula.sales.index')
            ->with('success', 'Data Sales berhasil diperbarui.');
    }

    public function destroy(GulaSales $sales)
    {
        // Unassign vehicle
        if ($sales->vehicle) {
            $sales->vehicle->update([
                'sales_type' => null,
                'sales_id' => null,
            ]);
        }

        $sales->delete();

        return redirect()->route('gula.sales.index')
            ->with('success', 'Data Sales berhasil dihapus.');
    }
}
