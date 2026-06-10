<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarRegional;
use Illuminate\Http\Request;

class PasgarRegionalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $regionals = PasgarRegional::query()
            ->withCount('sales')
            ->when($search, function ($q) use ($search) {
                $q->where('kode_regional', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => PasgarRegional::count(),
            'aktif' => PasgarRegional::where('status', 'aktif')->count(),
            'nonaktif' => PasgarRegional::where('status', 'nonaktif')->count(),
        ];

        return view('pasgar.regional.index', compact('regionals', 'stats'));
    }

    public function create()
    {
        return view('pasgar.regional.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['kode_regional'] = PasgarRegional::generateKode();

        PasgarRegional::create($validated);

        return redirect()->route('pasgar.regional.index')->with('success', 'Regional berhasil ditambahkan.');
    }

    public function edit(PasgarRegional $regional)
    {
        return view('pasgar.regional.edit', compact('regional'));
    }

    public function update(Request $request, PasgarRegional $regional)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $regional->update($validated);

        return redirect()->route('pasgar.regional.index')->with('success', 'Regional berhasil diperbarui.');
    }

    public function destroy(PasgarRegional $regional)
    {
        if ($regional->sales()->exists()) {
            return redirect()->route('pasgar.regional.index')->with('error', 'Tidak dapat menghapus regional yang masih memiliki sales.');
        }

        $regional->delete();

        return redirect()->route('pasgar.regional.index')->with('success', 'Regional berhasil dihapus.');
    }
}
