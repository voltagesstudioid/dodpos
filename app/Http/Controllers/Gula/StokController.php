<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaLoading;
use App\Models\GulaSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    public function index(Request $request)
    {
        $sales_id = $request->input('sales_id');

        // Determine which sales to show
        if ($this->isSales()) {
            $profile = GulaSales::where('user_id', Auth::id())->first();
            $salesIds = $profile ? [$profile->id] : [];
            $sales = collect([$profile])->filter();
        } else {
            $query = GulaSales::aktif();
            if ($sales_id) $query->where('id', $sales_id);
            $sales = $query->get();
            $salesIds = $sales->pluck('id')->toArray();
        }

        // All active sales for filter dropdown (always full list)
        $allSales = $this->isSales() ? $sales : GulaSales::aktif()->get();

        // Query loadings directly with proper per-product grouping
        $loadings = GulaLoading::with(['produk', 'sales'])
            ->whereIn('sales_id', $salesIds)
            ->get();

        $groupedBySales = $loadings->groupBy('sales_id');

        $stokPerSales = [];
        foreach ($sales as $s) {
            $salesLoadings = $groupedBySales->get($s->id, collect());

            $detail = $salesLoadings->groupBy('produk_id')->map(function ($items) {
                $produk = $items->first()->produk;
                return [
                    'produk'  => $produk,
                    'satuan'  => $produk->satuan ?? 'Unit',
                    'loading' => (int) $items->sum('jumlah_loading'),
                    'terjual' => (int) $items->sum('terjual'),
                    'sisa'    => (int) $items->sum('sisa_stok'),
                ];
            })->values();

            // Primary satuan: from the most-loaded product
            $primarySatuan = $detail->sortByDesc('loading')->first()['satuan'] ?? 'Unit';

            $stokPerSales[] = [
                'sales'          => $s,
                'total_loading'  => $detail->sum('loading'),
                'total_terjual'  => $detail->sum('terjual'),
                'total_sisa'     => $detail->sum('sisa'),
                'primary_satuan' => $primarySatuan,
                'detail'         => $detail,
            ];
        }

        $isSalesRole = $this->isSales();
        return view('gula.stok.index', compact('stokPerSales', 'allSales', 'isSalesRole'));
    }
}
