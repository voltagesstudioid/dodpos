<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetOtpRequestController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = (string) $validated['email'];
        $user = User::query()->where('email', $email)->first();

        if ($user && ($user->active ?? true)) {
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                ['token' => Hash::make($otp), 'created_at' => now()]
            );

            Mail::to($email)->send(new PasswordResetOtpMail(
                name: (string) ($user->name ?? ''),
                otp: $otp,
                expiresMinutes: 10
            ));
        }

        return redirect()
            ->route('password.reset', ['email' => $email])
            ->with('status', 'Jika email terdaftar, kode OTP sudah dikirim. Silakan cek inbox/spam.');
    }
}
