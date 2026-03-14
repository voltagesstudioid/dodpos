<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
    /**
     * Index — daftar setoran minyak dari seluruh sales
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $salesId = $request->get('sales_id');

        $query = DB::table('minyak_setorans as ms')
            ->join('users as u', 'u.id', '=', 'ms.user_id')
            ->leftJoin('vehicles as v', 'v.id', '=', 'ms.vehicle_id')
            ->select(
                'ms.*',
                'u.name as sales_name',
                'v.license_plate as kendaraan'
            )
            ->whereDate('ms.tanggal', $tanggal)
            ->orderByDesc('ms.created_at');

        if ($salesId) {
            $query->where('ms.user_id', $salesId);
        }

        $setorans      = $query->get();
        $salesList     = User::where('role', 'pasgar')->orderBy('name')->get();
        $totalSetoran  = $setorans->sum('jumlah_setoran');
        $totalRetur    = $setorans->sum('jumlah_retur');

        return view('minyak.setoran.index', compact(
            'setorans', 'salesList', 'tanggal', 'salesId',
            'totalSetoran', 'totalRetur'
        ));
    }

    /**
     * Form tambah setoran
     */
    public function create()
    {
        $salesList = User::where('role', 'pasgar')->orderBy('name')->get();
        $kendaraan = Vehicle::orderBy('license_plate')->get();
        return view('minyak.setoran.create', compact('salesList', 'kendaraan'));
    }

    /**
     * Simpan setoran baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'vehicle_id'     => 'nullable|exists:vehicles,id',
            'tanggal'        => 'required|date',
            'jumlah_setoran' => 'required|numeric|min:0',
            'jumlah_retur'   => 'nullable|numeric|min:0',
            'catatan'        => 'nullable|string|max:500',
        ]);

        DB::table('minyak_setorans')->insert([
            'user_id'        => $request->user_id,
            'vehicle_id'     => $request->vehicle_id,
            'tanggal'        => $request->tanggal,
            'jumlah_setoran' => $request->jumlah_setoran,
            'jumlah_retur'   => $request->jumlah_retur ?? 0,
            'catatan'        => $request->catatan,
            'created_by'     => auth()->id(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('minyak.setoran.index')
            ->with('success', 'Setoran minyak berhasil disimpan.');
    }

    /**
     * Hapus setoran
     */
    public function destroy($id)
    {
        DB::table('minyak_setorans')->where('id', $id)->delete();
        return back()->with('success', 'Data setoran berhasil dihapus.');
    }
}
