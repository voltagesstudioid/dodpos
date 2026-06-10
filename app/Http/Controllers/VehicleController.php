<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
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

        $vehicles = $query->withCount('expenses')->with('sales')->orderBy('license_plate')->paginate(20);

        // Stats
        $totalVehicles = Vehicle::count();
        $assignedVehicles = Vehicle::whereNotNull('sales_id')->count();
        $totalExpensesCount = Vehicle::withCount('expenses')->get()->sum('expenses_count');

        return view('operasional.kendaraan.index', compact(
            'vehicles', 'totalVehicles', 'assignedVehicles', 'totalExpensesCount'
        ));
    }

    /**
     * Export Kendaraan to CSV
     */
    public function export()
    {
        $vehicles = Vehicle::withCount('expenses')->with('sales')->orderBy('license_plate')->get();

        $filename = 'data-kendaraan-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($vehicles) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Plat Nomor', 'Jenis/Tipe', 'Ditugaskan Kepada', 'Divisi', 'Keterangan', 'Jumlah Penggunaan']);
            
            foreach ($vehicles as $v) {
                fputcsv($file, [
                    $v->license_plate,
                    $v->type ?? '-',
                    $v->sales ? $v->sales->nama : '-',
                    $v->sales ? $v->getSalesModuleLabel() : '-',
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
        $salesList = $this->getAllSales();
        return view('operasional.kendaraan.create', compact('salesList'));
    }

    /**
     * Collect active sales from all modules for dropdown.
     */
    private function getAllSales(): array
    {
        $list = [];

        $list['Gula'] = GulaSales::where('status', 'aktif')
            ->select('id', 'kode_sales', 'nama', 'no_kendaraan')
            ->orderBy('nama')->get()->toArray();

        $list['Mineral'] = MineralSales::where('status', 'aktif')
            ->select('id', 'kode_sales', 'nama', 'no_kendaraan')
            ->orderBy('nama')->get()->toArray();

        $list['Minyak'] = MinyakSales::where('status', 'aktif')
            ->select('id', 'kode_sales', 'nama', 'no_kendaraan')
            ->orderBy('nama')->get()->toArray();

        $list['Pasgar'] = PasgarSales::where('status', 'aktif')
            ->select('id', 'kode_sales', 'nama', 'no_kendaraan')
            ->orderBy('nama')->get()->toArray();

        return $list;
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string|unique:vehicles,license_plate|max:255',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sales_assignment' => 'nullable|string',
        ]);

        $data = $request->only(['license_plate', 'type', 'description']);

        // Parse sales assignment: format "ClassName:id"
        if ($request->filled('sales_assignment')) {
            [$type, $id] = explode(':', $request->input('sales_assignment'));
            $data['sales_type'] = $type;
            $data['sales_id'] = $id;

            // Also sync no_kendaraan on the sales model
            $salesModel = $type::find($id);
            if ($salesModel && !$salesModel->no_kendaraan) {
                $salesModel->update(['no_kendaraan' => $request->input('license_plate')]);
            }
        }

        Vehicle::create($data);
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $kendaraan)
    {
        $salesList = $this->getAllSales();
        return view('operasional.kendaraan.edit', compact('kendaraan', 'salesList'));
    }

    public function update(Request $request, Vehicle $kendaraan)
    {
        $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate,' . $kendaraan->id,
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sales_assignment' => 'nullable|string',
        ]);

        $data = $request->only(['license_plate', 'type', 'description']);

        // Clear old assignment
        $data['sales_type'] = null;
        $data['sales_id'] = null;

        // Parse new sales assignment
        if ($request->filled('sales_assignment')) {
            [$type, $id] = explode(':', $request->input('sales_assignment'));
            $data['sales_type'] = $type;
            $data['sales_id'] = $id;

            $salesModel = $type::find($id);
            if ($salesModel && !$salesModel->no_kendaraan) {
                $salesModel->update(['no_kendaraan' => $request->input('license_plate')]);
            }
        }

        $kendaraan->update($data);
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil dihapus.');
    }
}
