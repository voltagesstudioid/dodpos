<?php

namespace App\Http\Controllers\Api\Minyak;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuteController extends Controller
{
    /**
     * GET /api/minyak/rute
     * Daftar pelanggan/warung yang harus dikunjungi hari ini.
     * Menampilkan pelanggan yang terdaftar sebagai langganan minyak,
     * beserta status sudah dikunjungi atau belum berdasarkan transaksi hari ini.
     */
    public function index(Request $request)
    {
        $user    = $request->user();
        $tanggal = today()->format('Y-m-d');

        // Semua pelanggan minyak — nanti bisa difilter per wilayah/route
        $customers = Customer::where('category', 'minyak')->orderBy('name')->get(['id', 'name', 'address', 'phone']);

        // Transaksi hari ini untuk menandai yang sudah dikunjungi
        $sudahDikunjungi = DB::table('minyak_transaksis')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->whereNotNull('customer_id')
            ->pluck('customer_id')
            ->toArray();

        $data = $customers->map(function ($c) use ($sudahDikunjungi) {
            return [
                'id'              => $c->id,
                'name'            => $c->name,
                'address'         => $c->address ?? '-',
                'phone'           => $c->phone ?? '-',
                'sudah_dikunjungi'=> in_array($c->id, $sudahDikunjungi),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $data,
            'total'           => $customers->count(),
            'sudah_dikunjungi'=> count($sudahDikunjungi),
        ]);
    }
}
