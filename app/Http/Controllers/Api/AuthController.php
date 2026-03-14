<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

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
            throw ValidationException::withMessages([
                'email' => ['Terlalu banyak percobaan login. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($lockKey, 900); // 15 menit lockout
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // Login berhasil — reset counter lockout
        RateLimiter::clear($lockKey);

        // Cek status aktif user
        if (isset($user->active) && ! $user->active) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ], 403);
        }

        // Hapus token lama agar tidak menumpuk
        $user->tokens()->where('name', 'auth_token')->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'token'   => $user->createToken('auth_token')->plainTextToken,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    /**
     * Register endpoint telah dinonaktifkan karena alasan keamanan.
     * Pembuatan user hanya bisa dilakukan oleh admin melalui web panel.
     */
    public function register(Request $request)
    {
        return response()->json([
            'status'  => 'error',
            'message' => 'Registrasi tidak diizinkan. Hubungi administrator untuk membuat akun.',
        ], 403);
    }
}
