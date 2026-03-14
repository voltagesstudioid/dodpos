<x-guest-layout>
    <x-auth-page title="Daftar Akun" subtitle="Buat akun baru untuk mulai menggunakan DODPOS.">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input id="name" name="name" type="text" autocomplete="name" required autofocus
                        class="form-input"
                        value="{{ old('name') }}"
                        placeholder="Nama lengkap Anda">
                </div>
                @error('name')
                    <div class="form-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="form-input"
                        value="{{ old('email') }}"
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
                <label for="requested_role" class="form-label">Daftar Sebagai</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z"/><path d="M12 6v6l4 2"/>
                        </svg>
                    </span>
                    <select id="requested_role" name="requested_role" class="form-input">
                        <option value="">Pilih peran (opsional)</option>
                        @foreach(($roles ?? []) as $r)
                            <option value="{{ $r->key }}" {{ old('requested_role') === $r->key ? 'selected' : '' }}>{{ $r->label ?? strtoupper(str_replace('_', ' ', $r->key)) }}</option>
                        @endforeach
                    </select>
                </div>
                @error('requested_role')
                    <div class="form-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
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
            </div>

            <button type="submit" class="submit-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Daftar Sekarang
            </button>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau</span>
                <div class="divider-line"></div>
            </div>

            <div class="form-row" style="margin-bottom: 0;">
                <a class="forgot-link" href="{{ route('login') }}">Sudah punya akun? Masuk</a>
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
