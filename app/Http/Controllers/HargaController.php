<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unitConversions.unit']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('harga.index', compact('products', 'categories'));
    }
}
