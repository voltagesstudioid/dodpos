<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakSales;
use App\Models\MinyakRegional;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $regionalId = $request->input('regional_id');

        $query = MinyakSales::with(['user', 'regional', 'vehicle'])
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

        $regionals = MinyakRegional::where('status', 'aktif')->orderBy('nama')->get();

        $stats = [
            'total' => MinyakSales::count(),
            'aktif' => MinyakSales::where('status', 'aktif')->count(),
            'nonaktif' => MinyakSales::where('status', 'nonaktif')->count(),
            'cuti' => MinyakSales::where('status', 'cuti')->count(),
        ];

        // Per-regional stats
        $regionalStats = MinyakRegional::where('status', 'aktif')
            ->withCount(['sales as sales_count' => fn ($q) => $q->where('status', 'aktif')])
            ->withCount(['sales as sales_total'])
            ->orderBy('nama')
            ->get();

        return view('minyak.sales.index', compact('grouped', 'regionals', 'stats', 'regionalStats'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')
            ->orWhereHas('employee', function ($q) {
                $q->whereNull('id'); // Users without sales association
            })
            ->get();

        $regionals = MinyakRegional::aktif()->orderBy('nama')->get();

        // Available vehicles (not assigned to any sales, or sales_type is null)
        $vehicles = Vehicle::whereNull('sales_id')
            ->orderBy('license_plate')
            ->get();

        return view('minyak.sales.create', compact('users', 'regionals', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'regional_id' => 'nullable|exists:minyak_regional,id',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $validated['kode_sales'] = MinyakSales::generateKode();

        $salesRecord = MinyakSales::create($validated);

        // Assign vehicle (polymorphic)
        if (!empty($validated['vehicle_id'])) {
            Vehicle::where('id', $validated['vehicle_id'])->update([
                'sales_type' => MinyakSales::class,
                'sales_id' => $salesRecord->id,
            ]);
        }

        return redirect()->route('minyak.sales.index')
            ->with('success', 'Data Sales berhasil ditambahkan.');
    }

    public function show(MinyakSales $sales)
    {
        $sales->load(['user', 'vehicle', 'loadings.produk', 'penjualans.pelanggan', 'setorans']);
        
        return view('minyak.sales.show', compact('sales'));
    }

    public function edit(MinyakSales $sales)
    {
        $users = User::all();
        $regionals = MinyakRegional::aktif()->orderBy('nama')->get();

        // Available vehicles (not assigned OR currently assigned to this sales)
        $vehicles = Vehicle::whereNull('sales_id')
            ->orWhere(function ($q) use ($sales) {
                $q->where('sales_type', MinyakSales::class)
                  ->where('sales_id', $sales->id);
            })
            ->orderBy('license_plate')
            ->get();

        return view('minyak.sales.edit', compact('sales', 'users', 'regionals', 'vehicles'));
    }

    public function update(Request $request, MinyakSales $sales)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'regional_id' => 'nullable|exists:minyak_regional,id',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $sales->update($validated);

        // Unassign old vehicle
        Vehicle::where('sales_type', MinyakSales::class)
            ->where('sales_id', $sales->id)
            ->update(['sales_type' => null, 'sales_id' => null]);

        // Assign new vehicle (polymorphic)
        if (!empty($validated['vehicle_id'])) {
            Vehicle::where('id', $validated['vehicle_id'])->update([
                'sales_type' => MinyakSales::class,
                'sales_id' => $sales->id,
            ]);
        }

        return redirect()->route('minyak.sales.index')
            ->with('success', 'Data Sales berhasil diperbarui.');
    }

    public function destroy(MinyakSales $sales)
    {
        $sales->delete();

        return redirect()->route('minyak.sales.index')
            ->with('success', 'Data Sales berhasil dihapus.');
    }
}
