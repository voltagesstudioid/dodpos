<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralSales;
use App\Models\MineralRegional;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $regionalId = $request->input('regional_id');

        $query = MineralSales::with(['user', 'regional'])
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

        // Group by regional
        $grouped = $allSales->groupBy(function ($s) {
            return $s->regional_id ? $s->regional->nama : 'Tanpa Regional';
        });

        // Sort groups: named regionals alphabetically, "Tanpa Regional" last
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

        // Per-regional stats
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

        $vehicles = Vehicle::whereNull('sales_id')
            ->orderBy('license_plate')
            ->get();

        return view('mineral.sales.create', compact('users', 'regionals', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
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
        $validated['is_inti'] = $request->has('is_inti');

        $sales = MineralSales::create($validated);

        if (!empty($validated['vehicle_id'])) {
            Vehicle::where('id', $validated['vehicle_id'])->update([
                'sales_type' => MineralSales::class,
                'sales_id' => $sales->id,
            ]);
        }

        return redirect()->route('mineral.sales.index')
            ->with('success', 'Data Sales berhasil ditambahkan.');
    }

    public function show(MineralSales $sales)
    {
        $sales->load(['user', 'loadings.produk', 'penjualans.pelanggan', 'setorans']);
        
        return view('mineral.sales.show', compact('sales'));
    }

    public function edit(MineralSales $sales)
    {
        $users = User::all();
        $regionals = MineralRegional::where('status', 'aktif')->orderBy('nama')->get();
        $vehicles = Vehicle::whereNull('sales_id')
            ->orWhere(function ($q) use ($sales) {
                $q->where('sales_type', MineralSales::class)
                  ->where('sales_id', $sales->id);
            })
            ->orderBy('license_plate')
            ->get();
        return view('mineral.sales.edit', compact('sales', 'users', 'regionals', 'vehicles'));
    }

    public function update(Request $request, MineralSales $sales)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
            'regional_id' => 'nullable|exists:mineral_regional,id',
        ]);

        $validated['is_inti'] = $request->has('is_inti');

        Vehicle::where('sales_type', MineralSales::class)
            ->where('sales_id', $sales->id)
            ->update(['sales_type' => null, 'sales_id' => null]);

        if (!empty($validated['vehicle_id'])) {
            Vehicle::where('id', $validated['vehicle_id'])->update([
                'sales_type' => MineralSales::class,
                'sales_id' => $sales->id,
            ]);
        }

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
}
