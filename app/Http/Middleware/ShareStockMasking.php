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

        // Admin3 dan admin4: mask stok + sembunyikan data keuangan
        $hideFinancial = false;
        if (in_array($role, ['admin3', 'admin4'], true)) {
            $maskStock = true;
            $hideFinancial = true;
        }

        View::share('maskStock', $maskStock);
        View::share('maskStockWarehouseId', $warehouseId);
        View::share('hideFinancial', $hideFinancial);

        return $next($request);
    }
}
