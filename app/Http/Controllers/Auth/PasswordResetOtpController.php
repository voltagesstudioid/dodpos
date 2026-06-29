<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordResetOtpController extends Controller
{
    private const OTP_EXPIRES_MINUTES = 10;
    private const OTP_MAX_ATTEMPTS = 5;

    public function create(Request $request): View
    {
        return view('auth.reset-password-otp', [
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = (string) $validated['email'];
        $otp = (string) $validated['otp'];

        $this->cleanupExpiredTokens();

        $attemptKey = 'otp_attempts:' . $email;
        $attempts = (int) Cache::get($attemptKey, 0);

        if ($attempts >= self::OTP_MAX_ATTEMPTS) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            Cache::forget($attemptKey);

            return back()->withInput(['email' => $email])->withErrors(['otp' => 'Terlalu banyak percobaan OTP salah. Silakan minta OTP baru.']);
        }

        $row = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (! $row || ! isset($row->token) || ! isset($row->created_at)) {
            return back()->withInput(['email' => $email])->withErrors(['otp' => 'OTP tidak valid atau sudah kedaluwarsa.']);
        }

        $createdAt = Carbon::parse($row->created_at);
        if ($createdAt->diffInMinutes(now()) > self::OTP_EXPIRES_MINUTES) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            Cache::forget($attemptKey);

            return back()->withInput(['email' => $email])->withErrors(['otp' => 'OTP sudah kedaluwarsa. Silakan minta OTP baru.']);
        }

        if (! Hash::check($otp, (string) $row->token)) {
            $newAttempts = $attempts + 1;
            Cache::put($attemptKey, $newAttempts, now()->addMinutes(self::OTP_EXPIRES_MINUTES + 5));
            $remaining = self::OTP_MAX_ATTEMPTS - $newAttempts;

            return back()->withInput(['email' => $email])->withErrors(['otp' => 'OTP tidak valid. Sisa percobaan: ' . max(0, $remaining) . 'x']);
        }

        $user = User::query()->where('email', $email)->first();
        if (! $user) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            Cache::forget($attemptKey);

            return back()->withInput(['email' => $email])->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        $user->forceFill([
            'password' => Hash::make((string) $validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        Cache::forget($attemptKey);

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    private function cleanupExpiredTokens(): void
    {
        DB::table('password_reset_tokens')
            ->where('created_at', '<', now()->subMinutes(self::OTP_EXPIRES_MINUTES))
            ->delete();
    }
}
