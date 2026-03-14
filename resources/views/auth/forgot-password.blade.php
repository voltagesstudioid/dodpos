<x-guest-layout>
    <x-auth-page title="Lupa Password" subtitle="Masukkan email Anda, kami kirim kode OTP untuk reset password.">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus
                        class="form-input"
                        value="{{ old('email', request('email')) }}"
                        placeholder="nama@perusahaan.com">
                </div>
                @error('email')
                    <div class="form-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 12l-4 4-1.4-1.4L18.2 13H11v-2h7.2l-1.6-1.6L18 8l4 4z"/><path d="M2 6v12a2 2 0 0 0 2 2h9v-2H4V6h9V4H4a2 2 0 0 0-2 2z"/>
                </svg>
                Kirim OTP
            </button>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau</span>
                <div class="divider-line"></div>
            </div>

            <div class="form-row" style="margin-bottom: 0;">
                <a class="forgot-link" href="{{ route('login') }}">Kembali ke Login</a>
                <span style="font-size:0.8rem;color:rgba(255,255,255,0.35);">Cek folder spam jika email tidak masuk</span>
            </div>
        </form>
    </x-auth-page>
</x-guest-layout>
