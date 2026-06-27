<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarPenjualan;
use App\Models\PasgarSales;
use App\Models\PasgarLoading;
use App\Models\PasgarSetoran;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasgarDashboardController extends Controller
{
    public function index()
    {
        // Sales users see their own dedicated dashboard
        $user = Auth::user();
        $isSalesRole = str_starts_with(strtolower($user->role ?? ''), 'sales_') || ($user->role ?? '') === 'sales';
        if ($user && $isSalesRole) {
            return redirect()->route('pasgar.sales.dashboard');
        }

        $today = today();

        $salesCount = PasgarSales::aktif()->count();
        $loadingTodayCount = PasgarLoading::whereDate('tanggal', $today)->count();
        
        $setoranPendingCount = PasgarSetoran::where('status', 'pending')->count();
        
        $totalPenjualanHariIni = PasgarPenjualan::whereDate('tanggal', $today)->sum('total');
        $totalSetoranHariIni = PasgarSetoran::whereDate('tanggal', $today)->where('status', 'terverifikasi')->sum('total_setor');
        
        $recentTransactions = PasgarPenjualan::with(['sales', 'pelanggan'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();
            
        $pendingSetorans = PasgarSetoran::with('sales')
            ->where('status', 'pending')
            ->orderBy('tanggal', 'asc')
            ->take(5)
            ->get();
        
        return view('pasgar.dashboard', compact(
            'salesCount', 
            'loadingTodayCount', 
            'setoranPendingCount',
            'totalPenjualanHariIni',
            'totalSetoranHariIni',
            'recentTransactions',
            'pendingSetorans'
        ));
    }

    public function sales(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = PasgarSales::with(['vehicle'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kode_sales', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('nama', 'asc');

        $sales = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => PasgarSales::count(),
            'aktif' => PasgarSales::where('status', 'aktif')->count(),
            'nonaktif' => PasgarSales::where('status', 'nonaktif')->count(),
        ];

        return view('pasgar.sales.index', compact('sales', 'stats'));
    }

    public function salesCreate()
    {
        $vehicles = Vehicle::whereNull('sales_id')
            ->orderBy('license_plate')
            ->get();

        return view('pasgar.sales.create', compact('vehicles'));
    }

    public function salesStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'target_harian' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['kode_sales'] = PasgarSales::generateKode();
        $validated['status'] = 'aktif';

        $salesRecord = PasgarSales::create($validated);

        // Assign vehicle (polymorphic)
        if (!empty($validated['vehicle_id'])) {
            Vehicle::where('id', $validated['vehicle_id'])->update([
                'sales_type' => PasgarSales::class,
                'sales_id' => $salesRecord->id,
            ]);
        }

        return redirect()->route('pasgar.sales.index')->with('success', 'Data Sales Pasgar berhasil ditambahkan.');
    }

    public function salesEdit(PasgarSales $sales)
    {
        // Available vehicles: unassigned OR currently assigned to this sales
        $vehicles = Vehicle::where(function ($q) use ($sales) {
                $q->whereNull('sales_id')
                    ->orWhere(function ($sub) use ($sales) {
                        $sub->where('sales_type', PasgarSales::class)
                            ->where('sales_id', $sales->id);
                    });
            })
            ->orderBy('license_plate')
            ->get();

        return view('pasgar.sales.edit', compact('sales', 'vehicles'));
    }

    public function salesUpdate(Request $request, PasgarSales $sales)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $sales->update([
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
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
                'sales_type' => PasgarSales::class,
                'sales_id' => $sales->id,
            ]);
        }

        return redirect()->route('pasgar.sales.index')->with('success', 'Data Sales Pasgar berhasil diperbarui.');
    }
}
