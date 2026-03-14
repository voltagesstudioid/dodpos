<x-guest-layout>
    <x-auth-page title="Reset Password" subtitle="Masukkan OTP yang dikirim ke email Anda.">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" autocomplete="username" required autofocus
                        class="form-input"
                        value="{{ old('email', $email) }}"
                        placeholder="nama@perusahaan.com">
                </div>
                @error('email')
                    <div class="form-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="otp" class="form-label">Kode OTP</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2a7 7 0 0 0-7 7v3a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V9a7 7 0 0 0-7-7z"/><path d="M9 21h6"/><path d="M12 17v4"/>
                        </svg>
                    </span>
                    <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" required
                        class="form-input"
                        value="{{ old('otp') }}"
                        placeholder="6 digit">
                </div>
                @error('otp')
                    <div class="form-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password Baru</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="form-input"
                        placeholder="••••••••••">
                </div>
                @error('password')
                    <div class="form-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                        class="form-input"
                        placeholder="••••••••••">
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M20 6L9 17l-5-5"/><path d="M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0z" opacity=".2"/>
                </svg>
                Simpan Password Baru
            </button>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau</span>
                <div class="divider-line"></div>
            </div>

            <div class="form-row" style="margin-bottom: 0;">
                <a class="forgot-link" href="{{ route('password.request', ['email' => old('email', $email)]) }}">Kirim ulang OTP</a>
                <a class="forgot-link" href="{{ route('login') }}">Kembali ke Login</a>
            </div>
        </form>
    </x-auth-page>
</x-guest-layout>

