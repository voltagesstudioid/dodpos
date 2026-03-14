<?php

namespace App\Http\Controllers\Api\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        // Untuk tahap ini, tampilkan semua pelanggan yang jadi tanggung jawab Sales (Bisa filter by Rute nanti)
        // Kita juga tambahkan data apakah toko ini punya hutang yang belum lunas (CustomerCredit)
        
        $customers = \App\Models\Customer::with(['credits' => function($q) {
            $q->where('status', 'unpaid');
        }])->get();

        $formatted = $customers->map(function($customer) {
            $totalAngsuran = $customer->credits->sum('amount');
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'address' => $customer->address,
                'phone' => $customer->phone,
                'has_debt' => $totalAngsuran > 0,
                'total_debt' => $totalAngsuran
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted
        ]);
    }
}
