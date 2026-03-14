<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->route('login');
        }

        if (isset($user->active) && ! $user->active) {
            if ($request->expectsJson()) {
                $user->currentAccessToken()?->delete();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun Anda dinonaktifkan.',
                ], 403);
            }

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan.');
        }

        return $next($request);
    }
}
