<?php

namespace App\Http\Controllers\Api\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class NooController extends Controller
{
    /**
     * Daftarkan warung/toko baru (NOO) lewat HP Kanvas
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'address' => 'required|string',
            'type' => 'required|in:retail,wholesaler',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'type' => $request->type,
            'status' => 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'New Open Outlet Berhasil',
            'data' => $customer
        ]);
    }
}
