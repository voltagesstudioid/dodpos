<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralSales;
use App\Models\MineralRegional;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $regionalId = $request->input('regional_id');

        $query = MineralSales::with(['user', 'regional', 'currentAssignment.vehicle'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kode_sales', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($regionalId, fn ($q) => $q->where('regional_id', $regionalId))
            ->orderBy('nama', 'asc');

        $allSales = $query->get();

        $grouped = $allSales->groupBy(function ($s) {
            return $s->regional_id ? $s->regional->nama : 'Tanpa Regional';
        });

        $sortedKeys = $grouped->keys()->sort(function ($a, $b) {
            if ($a === 'Tanpa Regional') return 1;
            if ($b === 'Tanpa Regional') return -1;
            return strcmp($a, $b);
        });
        $grouped = $sortedKeys->mapWithKeys(fn ($key) => [$key => $grouped[$key]]);

        $regionals = MineralRegional::where('status', 'aktif')->orderBy('nama')->get();

        $stats = [
            'total' => MineralSales::count(),
            'aktif' => MineralSales::where('status', 'aktif')->count(),
            'nonaktif' => MineralSales::where('status', 'nonaktif')->count(),
            'cuti' => MineralSales::where('status', 'cuti')->count(),
        ];

        $regionalStats = MineralRegional::where('status', 'aktif')
            ->withCount(['sales as sales_count' => fn ($q) => $q->where('status', 'aktif')])
            ->withCount(['sales as sales_total'])
            ->orderBy('nama')
            ->get();

        return view('mineral.sales.index', compact('grouped', 'regionals', 'stats', 'regionalStats'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')
            ->orWhereHas('employee', function ($q) {
                $q->whereNull('id');
            })
            ->get();

        $regionals = MineralRegional::where('status', 'aktif')->orderBy('nama')->get();

        return view('mineral.sales.create', compact('users', 'regionals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
            'regional_id' => 'nullable|exists:mineral_regional,id',
        ]);

        $validated['kode_sales'] = MineralSales::generateKode();

        $sales = MineralSales::create($validated);

        return redirect()->route('mineral.sales.index')
            ->with('success', 'Data Sales berhasil ditambahkan.');
    }

    public function show(MineralSales $sales)
    {
        $sales->load(['user', 'loadings.produk', 'penjualans.pelanggan', 'setorans', 'currentAssignment.vehicle', 'assignments.vehicle']);

        $vehicles = Vehicle::where('status', 'aktif')->orderBy('license_plate')->get();

        return view('mineral.sales.show', compact('sales', 'vehicles'));
    }

    public function edit(MineralSales $sales)
    {
        $users = User::all();
        $regionals = MineralRegional::where('status', 'aktif')->orderBy('nama')->get();

        return view('mineral.sales.edit', compact('sales', 'users', 'regionals'));
    }

    public function update(Request $request, MineralSales $sales)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
            'regional_id' => 'nullable|exists:mineral_regional,id',
        ]);

        $sales->update($validated);

        return redirect()->route('mineral.sales.index')
            ->with('success', 'Data Sales berhasil diperbarui.');
    }

    public function destroy(MineralSales $sales)
    {
        $sales->delete();

        return redirect()->route('mineral.sales.index')
            ->with('success', 'Data Sales berhasil dihapus.');
    }

    public function assignVehicle(Request $request, MineralSales $sales)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'role' => 'required|in:inti,sub',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);

        if (!VehicleAssignment::isVehicleAvailable($validated['vehicle_id'], $validated['tanggal_mulai'])) {
            return redirect()->back()->with('error', 'Kendaraan sudah di-assign pada tanggal tersebut.');
        }

        if (!VehicleAssignment::isSalesAvailable($sales->id, $validated['role'], $validated['tanggal_mulai'])) {
            return redirect()->back()->with('error', 'Sales sudah memiliki assignment dengan role yang sama pada tanggal tersebut.');
        }

        VehicleAssignment::create([
            'vehicle_id' => $validated['vehicle_id'],
            'sales_id' => $sales->id,
            'role' => $validated['role'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'status' => 'aktif',
            'keterangan' => $validated['keterangan'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('mineral.sales.show', $sales)
            ->with('success', 'Assignment kendaraan berhasil dibuat.');
    }

    public function endAssignment(VehicleAssignment $assignment)
    {
        $assignment->update([
            'tanggal_selesai' => now(),
            'status' => 'selesai',
        ]);

        return redirect()->back()->with('success', 'Assignment berhasil diakhiri.');
    }
}
