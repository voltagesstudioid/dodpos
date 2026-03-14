<x-guest-layout>
    <x-auth-page title="Reset Password" subtitle="Buat password baru untuk akun Anda.">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                        value="{{ old('email', $request->email) }}"
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
                <label for="password" class="form-label">Password Baru</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="form-input has-toggle"
                        placeholder="••••••••••">
                    <button type="button" class="toggle-pw" onclick="togglePassword()" id="pw-toggle-btn">
                        <svg id="pw-eye" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="pw-eye-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
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
                @error('password_confirmation')
                    <div class="form-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
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
                <a class="forgot-link" href="{{ route('login') }}">Kembali ke Login</a>
                <span style="font-size:0.8rem;color:rgba(255,255,255,0.35);">Pastikan password sesuai aturan sistem</span>
            </div>
        </form>
    </x-auth-page>

    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeIcon = document.getElementById('pw-eye');
        const eyeOffIcon = document.getElementById('pw-eye-off');
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            input.type = 'password';
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    }
    </script>
</x-guest-layout>
