<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Daftar produk untuk mobile app Pasgar.
     * Menggunakan ProductResource untuk transformasi yang konsisten.
     * Eager-load relasi untuk menghindari N+1 query.
     */
    public function index(Request $request)
    {
        $query = Product::with(['unit', 'category', 'unitConversions.unit'])
            ->where('stock', '>', 0); // Hanya produk yang ada stoknya

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(50);

        return response()->json([
            'status' => 'success',
            'data'   => ProductResource::collection($products),
            'meta'   => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
        ]);
    }
}
