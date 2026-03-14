<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ── Account Lockout — max 5 percobaan per email per 15 menit ──
        $lockKey = 'login_attempts:' . $request->email;
        if (RateLimiter::tooManyAttempts($lockKey, 5)) {
            $seconds = RateLimiter::availableIn($lockKey);
            return response()->json([
                'status'  => 'error',
                'message' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ], 429);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        // Cek user, password, dan role
        if (! $user || ! \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            RateLimiter::hit($lockKey, 900); // 900 detik = 15 menit
            return response()->json([
                'status'  => 'error',
                'message' => 'Kredensial tidak valid.',
            ], 401);
        }

        if (! in_array($user->role, ['pasgar', 'supervisor'])) {
            RateLimiter::hit($lockKey, 900);
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun Anda tidak memiliki akses ke Aplikasi Sales/Pasgar.',
            ], 403);
        }

        if (! $user->active) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun Anda dinonaktifkan.',
            ], 403);
        }

        // Login berhasil — reset counter lockout
        RateLimiter::clear($lockKey);

        // Hapus token lama agar tidak menumpuk (satu user = satu token aktif)
        $user->tokens()->where('name', 'pasgar-app-token')->delete();

        $token = $user->createToken('pasgar-app-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $token,
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                // Hanya field yang dibutuhkan — jangan ekspos seluruh model
                'user' => $request->user()->only(['id', 'name', 'email', 'role']),
            ],
        ]);
    }

    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}
