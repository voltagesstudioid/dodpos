<?php

namespace App\Http\Controllers\Api\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasProduct;
use App\Models\KanvasVehicleStock;

class CatalogController extends Controller
{
    /**
     * Pencarian text (Search) katalog 
     */
    public function index(Request $request)
    {
        $keyword = $request->query('q', '');
        
        $query = KanvasProduct::where('status', 'active');
        
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('barcode', 'like', "%{$keyword}%");
            });
        }
        
        // Batasi result agar HP tidak lemot waktu render jika item 1000+
        $products = $query->limit(50)->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    /**
     * Fitur Scanner Barcode
     * Spesifik mencari data produk berdasarkan string barcode yg discan kamera HP
     */
    public function scan(Request $request)
    {
        $barcode = $request->query('barcode');

        if (!$barcode) {
            return response()->json(['status' => 'error', 'message' => 'Barcode tidak valid'], 400);
        }

        $product = KanvasProduct::where('barcode', $barcode)->where('status', 'active')->first();

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Barang tidak ditemukan di Katalog Utama'], 404);
        }

        // Opsional: Cek sisa stoknya di mobil (Bisa nol)
        $stock = KanvasVehicleStock::where('sales_id', auth()->id())
                                   ->where('product_id', $product->id)
                                   ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => $product,
                'leftover_qty' => $stock ? $stock->leftover_qty : 0
            ]
        ]);
    }
}
