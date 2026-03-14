<?php

namespace App\Http\Controllers\Api\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasRoute;
use App\Models\KanvasRouteStore;
use Carbon\Carbon;

class RouteController extends Controller
{
    /**
     * Dapatkan rute untuk sales hari ini
     * Implementasi sederhana (Misal ambil rute berdasarkan nama hari saat ini)
     */
    public function index(Request $request)
    {
        $hariIni = Carbon::now()->locale('id')->dayName;

        // Cari rute Kanvas yang match dengan hari ini
        $routes = KanvasRoute::where('day_of_week', $hariIni)->with(['stores.customer'])->get();

        $listToko = [];
        foreach ($routes as $route) {
            foreach ($route->stores as $st) {
                if ($st->customer) {
                    $listToko[] = [
                        'customer_id' => $st->customer->id,
                        'name' => $st->customer->name,
                        'address' => $st->customer->address,
                        'phone' => $st->customer->phone,
                        'sequence' => $st->sequence,
                        'route_name' => $route->name,
                    ];
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'day' => $hariIni,
            'data' => collect($listToko)->sortBy('sequence')->values()->all()
        ]);
    }

    /**
     * Endpoint Check-In
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Check-In GPS GPS berhasil (' . $request->latitude . ', ' . $request->longitude . ')'
        ]);
    }
}
