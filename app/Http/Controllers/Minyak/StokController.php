<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakLoading;
use App\Models\MinyakSales;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $sales_id = $request->input('sales_id');
        
        $sales = MinyakSales::aktif()
            ->with(['loadings.produk' => function ($q) {
                $q->where('sisa_stok', '>', 0);
            }])
            ->when($sales_id, function ($query) use ($sales_id) {
                $query->where('id', $sales_id);
            })
            ->get();

        $stokPerSales = [];
        foreach ($sales as $s) {
            $totalLoading = $s->loadings->sum('jumlah_loading');
            $totalTerjual = $s->loadings->sum('terjual');
            $totalSisa = $s->loadings->sum('sisa_stok');
            
            $stokPerSales[] = [
                'sales' => $s,
                'total_loading' => $totalLoading,
                'total_terjual' => $totalTerjual,
                'total_sisa' => $totalSisa,
                'detail' => $s->loadings->groupBy('produk_id')->map(function ($items) {
                    return [
                        'produk' => $items->first()->produk,
                        'loading' => $items->sum('jumlah_loading'),
                        'terjual' => $items->sum('terjual'),
                        'sisa' => $items->sum('sisa_stok'),
                    ];
                }),
            ];
        }

        return view('minyak.stok.index', compact('stokPerSales', 'sales'));
    }
}
