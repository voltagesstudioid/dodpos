<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        // Redirect based on role
        $user = Auth::user();
        $userRole = strtolower(trim((string) $user->role));
        
        // Check if user has sales role (support: 'sales', 'sales_minyak', 'sales minyak', etc)
        $isSales = $userRole === 'sales' || str_starts_with($userRole, 'sales_') || str_starts_with($userRole, 'sales ');
        
        if ($isSales) {
            return redirect()->route('sales.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
