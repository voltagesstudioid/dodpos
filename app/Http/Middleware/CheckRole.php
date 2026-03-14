<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,kasir')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
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

        // Support pipe-separated roles in a single argument (e.g., 'supervisor|admin3')
        // as well as multiple arguments passed by Laravel's middleware parameter syntax
        $allowed = [];
        foreach ($roles as $roleArg) {
            foreach (preg_split('/[|,]/', $roleArg) as $r) {
                $r = Str::lower(trim($r));
                if ($r !== '') {
                    $allowed[] = $r;
                }
            }
        }
        $actual = Str::lower(trim((string) $user->role));

        if (! in_array($actual, $allowed, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda tidak memiliki akses ke fitur ini.',
                ], 403);
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
