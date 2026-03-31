<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectMobileSales
{
    /**
     * Handle an incoming request.
     * Redirect sales users on mobile devices to mobile-optimized sales pages.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Only for sales role
        if ($user && ($user->hasRole('sales') || $user->role === 'sales')) {
            $agent = $request->header('User-Agent');
            
            // Check if mobile device
            $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod/', $agent);
            
            // If mobile and accessing admin dashboard, redirect to sales dashboard
            if ($isMobile && $request->is('dashboard')) {
                return redirect()->route('sales.dashboard');
            }
        }
        
        return $next($request);
    }
}
