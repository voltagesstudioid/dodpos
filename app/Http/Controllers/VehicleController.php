<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('operasional.kendaraan.index', compact('vehicles'));
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
            'description' => 'nullable|string'
        ]);
        Vehicle::create($request->all());
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
            'description' => 'nullable|string'
        ]);
        $kendaraan->update($request->all());
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('operasional.kendaraan.index')->with('success', 'Data Kendaraan berhasil dihapus.');
    }
}
