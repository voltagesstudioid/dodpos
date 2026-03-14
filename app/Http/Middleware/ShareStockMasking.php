<?php

namespace App\Http\Middleware;

use App\Models\StockOpnameSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class ShareStockMasking
{
    public function handle(Request $request, Closure $next)
    {
        $maskStock = false;
        $warehouseId = null;

        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        if (in_array($role, ['admin3', 'admin4'], true) && Schema::hasTable('stock_opname_sessions') && class_exists(StockOpnameSession::class)) {
            $start = now()->startOfDay();
            $end = now()->endOfDay();

            $hasOpname = StockOpnameSession::query()
                ->whereIn('status', ['submitted', 'approved'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('submitted_at', [$start, $end])
                        ->orWhereBetween('approved_at', [$start, $end])
                        ->orWhereBetween('created_at', [$start, $end]);
                })
                ->exists();

            $maskStock = ! $hasOpname;
        }

        View::share('maskStock', $maskStock);
        View::share('maskStockWarehouseId', $warehouseId);

        return $next($request);
    }
}
