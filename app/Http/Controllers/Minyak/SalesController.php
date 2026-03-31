<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakSales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $sales = MinyakSales::with(['user'])
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
            'total' => MinyakSales::count(),
            'aktif' => MinyakSales::where('status', 'aktif')->count(),
            'nonaktif' => MinyakSales::where('status', 'nonaktif')->count(),
            'cuti' => MinyakSales::where('status', 'cuti')->count(),
        ];

        return view('minyak.sales.index', compact('sales', 'stats'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')
            ->orWhereHas('employee', function ($q) {
                $q->whereNull('id'); // Users without sales association
            })
            ->get();

        return view('minyak.sales.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'no_kendaraan' => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $validated['kode_sales'] = MinyakSales::generateKode();

        MinyakSales::create($validated);

        return redirect()->route('minyak.sales.index')
            ->with('success', 'Data Sales berhasil ditambahkan.');
    }

    public function show(MinyakSales $sales)
    {
        $sales->load(['user', 'loadings.produk', 'penjualans.pelanggan', 'setorans']);
        
        return view('minyak.sales.show', compact('sales'));
    }

    public function edit(MinyakSales $sales)
    {
        $users = User::all();
        return view('minyak.sales.edit', compact('sales', 'users'));
    }

    public function update(Request $request, MinyakSales $sales)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'no_kendaraan' => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
            'target_harian' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        $sales->update($validated);

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
