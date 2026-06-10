<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralLoading;
use App\Models\MineralSales;
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
            $profile = MineralSales::where('user_id', Auth::id())->first();
            $salesIds = $profile ? [$profile->id] : [];
            $sales = collect([$profile])->filter();
        } else {
            $query = MineralSales::aktif();
            if ($sales_id) $query->where('id', $sales_id);
            $sales = $query->get();
            $salesIds = $sales->pluck('id')->toArray();
        }

        // Query loadings directly with proper per-product grouping
        $loadings = MineralLoading::with(['produk', 'sales'])
            ->whereIn('sales_id', $salesIds)
            ->get();

        $groupedBySales = $loadings->groupBy('sales_id');

        $stokPerSales = [];
        foreach ($sales as $s) {
            $salesLoadings = $groupedBySales->get($s->id, collect());
            $totalLoading = $salesLoadings->sum('jumlah_loading');
            $totalTerjual = $salesLoadings->sum('terjual');
            $totalSisa    = $salesLoadings->sum('sisa_stok');

            $detail = $salesLoadings->groupBy('produk_id')->map(function ($items) {
                return [
                    'produk'  => $items->first()->produk,
                    'loading' => (float) $items->sum('jumlah_loading'),
                    'terjual' => (float) $items->sum('terjual'),
                    'sisa'    => (float) $items->sum('sisa_stok'),
                ];
            })->values();

            $stokPerSales[] = [
                'sales'         => $s,
                'total_loading' => $totalLoading,
                'total_terjual' => $totalTerjual,
                'total_sisa'    => $totalSisa,
                'detail'        => $detail,
            ];
        }

        $isSalesRole = $this->isSales();
        return view('mineral.stok.index', compact('stokPerSales', 'sales', 'isSalesRole'));
    }
}
