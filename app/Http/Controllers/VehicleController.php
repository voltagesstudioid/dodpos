<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use App\Models\GulaSales;
use App\Models\MineralSales;
use App\Models\MinyakSales;
use App\Models\PasgarSales;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $vehicles = $query->withCount('expenses')
            ->with(['currentAssignment.sales'])
            ->orderBy('license_plate')
            ->paginate(20);

        // Stats
        $totalVehicles = Vehicle::count();
        $activeVehicles = Vehicle::where('status', 'aktif')->count();
        $assignedVehicles = VehicleAssignment::where('status', 'aktif')->distinct()->count('vehicle_id');
        $totalCapacity = Vehicle::where('status', 'aktif')->sum('capacity');
        $totalExpensesCount = Vehicle::withCount('expenses')->get()->sum('expenses_count');

        return view('operasional.kendaraan.index', compact(
            'vehicles', 'totalVehicles', 'activeVehicles', 'assignedVehicles', 'totalCapacity', 'totalExpensesCount'
        ));
    }

    /**
     * Export Kendaraan to CSV
     */
    public function export()
    {
        $vehicles = Vehicle::withCount('expenses')
            ->with(['currentAssignment.sales'])
            ->orderBy('license_plate')
            ->get();

        $filename = 'data-kendaraan-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($vehicles) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Plat Nomor', 'Jenis/Tipe', 'Kapasitas', 'Status', 'Driver Saat Ini', 'Role', 'Keterangan', 'Jumlah Penggunaan']);
            
            foreach ($vehicles as $v) {
                fputcsv($file, [
                    $v->license_plate,
                    $v->type ?? '-',
                    $v->capacity,
                    $v->status,
                    $v->currentAssignment?->sales?->nama ?? '-',
                    $v->currentAssignment?->role ?? '-',
                    $v->description ?? '-',
                    $v->expenses_count,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function create()
    {
        return view('operasional.kendaraan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string|unique:vehicles,license_plate|max:255',
            'type' => 'nullable|string|max:255',
            'capacity' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,maintenance,standby,nonaktif',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['license_plate', 'type', 'capacity', 'status', 'description']);

        Vehicle::create($data);
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $kendaraan)
    {
        return view('operasional.kendaraan.edit', compact('kendaraan'));
    }

    public function update(Request $request, Vehicle $kendaraan)
    {
        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate,' . $kendaraan->id,
            'type' => 'nullable|string|max:255',
            'capacity' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,maintenance,standby,nonaktif',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['license_plate', 'type', 'capacity', 'status', 'description']);

        $kendaraan->update($data);
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil dihapus.');
    }
}
