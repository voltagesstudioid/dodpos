<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarMember;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = PasgarMember::with(['user', 'vehicle.warehouse']);

        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        $members = $query->latest()->paginate(15)->withQueryString();

        // Summary
        $totalMembers  = PasgarMember::count();
        $activeMembers = PasgarMember::where('active', true)->count();

        return view('pasgar.anggota.index', compact('members', 'totalMembers', 'activeMembers'));
    }

    public function create()
    {
        // Hanya user dengan role pasgar yang belum punya profil member
        $existingUserIds = PasgarMember::pluck('user_id');
        $users    = User::where('role', 'pasgar')
                        ->whereNotIn('id', $existingUserIds)
                        ->where('active', true)
                        ->orderBy('name')
                        ->get();
        $vehicles = Vehicle::with('warehouse')->orderBy('license_plate')->get();

        return view('pasgar.anggota.create', compact('users', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id|unique:pasgar_members,user_id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'area'       => 'nullable|string|max:255',
            'active'     => 'boolean',
            'notes'      => 'nullable|string',
        ]);

        PasgarMember::create([
            'user_id'    => $request->user_id,
            'vehicle_id' => $request->vehicle_id,
            'area'       => $request->area,
            'active'     => $request->boolean('active', true),
            'notes'      => $request->notes,
        ]);

        return redirect()->route('pasgar.anggota.index')
            ->with('success', 'Anggota Pasgar berhasil ditambahkan.');
    }

    public function show(PasgarMember $anggota)
    {
        $anggota->load(['user', 'vehicle.warehouse']);

        // Stok on-hand kendaraan
        $stockOnHand = collect();
        if ($anggota->vehicle && $anggota->vehicle->warehouse_id) {
            $stockOnHand = \App\Models\ProductStock::with('product.unit')
                ->where('warehouse_id', $anggota->vehicle->warehouse_id)
                ->where('stock', '>', 0)
                ->get();
        }

        // Penjualan hari ini
        $todaySales = \App\Models\SalesOrder::where('user_id', $anggota->user_id)
            ->whereDate('order_date', today())
            ->sum('total_amount');

        // Riwayat penjualan 7 hari
        $recentSales = \App\Models\SalesOrder::with('customer')
            ->where('user_id', $anggota->user_id)
            ->orderByDesc('order_date')
            ->limit(10)
            ->get();

        // Setoran terbaru
        $recentDeposits = $anggota->deposits()->with('verifier')->latest()->limit(5)->get();

        return view('pasgar.anggota.show', compact(
            'anggota', 'stockOnHand', 'todaySales', 'recentSales', 'recentDeposits'
        ));
    }

    public function edit(PasgarMember $anggota)
    {
        $vehicles = Vehicle::with('warehouse')->orderBy('license_plate')->get();
        return view('pasgar.anggota.edit', compact('anggota', 'vehicles'));
    }

    public function update(Request $request, PasgarMember $anggota)
    {
        $request->validate([
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'area'       => 'nullable|string|max:255',
            'active'     => 'boolean',
            'notes'      => 'nullable|string',
        ]);

        $anggota->update([
            'vehicle_id' => $request->vehicle_id,
            'area'       => $request->area,
            'active'     => $request->boolean('active', true),
            'notes'      => $request->notes,
        ]);

        return redirect()->route('pasgar.anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(PasgarMember $anggota)
    {
        $anggota->delete();
        return redirect()->route('pasgar.anggota.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
