<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Daftar pelanggan untuk mobile app Pasgar.
     * Menggunakan CustomerResource + eager-load untuk menghindari N+1.
     */
    public function index(Request $request)
    {
        $query = Customer::where('category', 'pasgar');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'data'   => CustomerResource::collection($customers),
        ]);
    }
}
