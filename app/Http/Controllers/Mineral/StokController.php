<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralLoading;
use App\Models\MineralSales;
use App\Models\MineralProduk;
use App\Models\Vehicle;
use App\Models\VehicleStock;
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
        $vehicle_id = $request->input('vehicle_id');

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

        // ── Sales-based stock (from loading records) ──
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

        // ── Vehicle-based physical stock (from vehicle_stocks table) ──
        $vehicleQuery = VehicleStock::with(['vehicle.currentAssignment.sales', 'produk'])
            ->whereHas('produk', fn($q) => $q->where('status', 'aktif'))
            ->whereHas('vehicle', fn($q) => $q->aktif());

        if ($vehicle_id) {
            $vehicleQuery->where('vehicle_id', $vehicle_id);
        }

        $vehicleStockRows = $vehicleQuery->get();

        $groupedByVehicle = $vehicleStockRows->groupBy('vehicle_id');

        $vehicles = Vehicle::aktif()
            ->whereIn('id', $groupedByVehicle->keys())
            ->with('currentAssignment.sales')
            ->get()
            ->keyBy('id');

        $stokPerVehicle = [];
        foreach ($groupedByVehicle as $vId => $rows) {
            $vehicle = $vehicles->get($vId);
            if (!$vehicle) continue;

            $assignment = $vehicle->currentAssignment;
            $detail = $rows->map(function ($vs) {
                return [
                    'produk' => $vs->produk,
                    'jumlah' => (float) $vs->jumlah,
                ];
            })->sortByDesc('jumlah')->values();

            $stokPerVehicle[] = [
                'vehicle'    => $vehicle,
                'assignment' => $assignment,
                'total_stok' => $detail->sum('jumlah'),
                'detail'     => $detail,
            ];
        }

        // Sort by total_stok descending
        usort($stokPerVehicle, fn($a, $b) => $b['total_stok'] <=> $a['total_stok']);

        $isSalesRole = $this->isSales();

        $allVehicles = Vehicle::aktif()
            ->whereHas('stocks', fn($q) => $q->whereHas('produk', fn($pq) => $pq->where('status', 'aktif')))
            ->get();

        return view('mineral.stok.index', compact(
            'stokPerSales', 'sales', 'stokPerVehicle', 'allVehicles', 'isSalesRole'
        ));
    }
}
