<?php

namespace App\Http\Controllers\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasRoute;
use App\Models\KanvasRouteStore;
use App\Models\Customer;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $query = KanvasRoute::query()->withCount('stores')->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%' . $q . '%')
                    ->orWhere('area_description', 'like', '%' . $q . '%')
                    ->orWhere('day_of_week', 'like', '%' . $q . '%');
            });
        }

        if ($request->filled('day')) {
            $query->where('day_of_week', $request->day);
        }

        $totalRoutes = (clone $query)->count();
        $totalStores = (clone $query)->get()->sum('stores_count');
        $activeDaysCount = (clone $query)->distinct('day_of_week')->count('day_of_week');

        $dayOptions = KanvasRoute::query()
            ->select('day_of_week')
            ->whereNotNull('day_of_week')
            ->where('day_of_week', '<>', '')
            ->distinct()
            ->orderBy('day_of_week')
            ->pluck('day_of_week');

        $routes = $query->paginate(15)->withQueryString();

        return view('kanvas.route.index', compact(
            'routes',
            'totalRoutes',
            'totalStores',
            'activeDaysCount',
            'dayOptions',
        ));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        return view('kanvas.route.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'day_of_week' => 'required|string',
            'customer_ids' => 'required|array'
        ]);

        $route = KanvasRoute::create([
            'name' => $request->name,
            'day_of_week' => $request->day_of_week,
            'area_description' => $request->area_description,
        ]);

        foreach ($request->customer_ids as $index => $custId) {
            KanvasRouteStore::create([
                'route_id' => $route->id,
                'customer_id' => $custId,
                'sequence' => $index + 1
            ]);
        }

        return redirect()->route('kanvas.route.index')->with('success', 'Journey Plan berhasil dirancang!');
    }
}
