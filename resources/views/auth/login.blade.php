<x-guest-layout>
<x-auth-page title="Selamat Datang" subtitle="Masuk ke akun DODPOS Anda untuk melanjutkan">
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Email Field -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                </span>
                <input id="email" name="email" type="email" autocomplete="email" required autofocus
                    class="form-input"
                    value="{{ old('email') }}"
                    placeholder="nama@perusahaan.com">
            </div>
            @error('email')
                <div class="form-error">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="form-input has-toggle"
                    placeholder="••••••••">
                <button type="button" class="toggle-pw" onclick="togglePassword()" id="pw-toggle-btn" title="Tampilkan password">
                    <svg id="pw-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg id="pw-eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" y1="2" x2="22" y2="22"></line>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="form-error">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember & Forgot -->
        <div class="form-row">
            <label class="remember-label">
                <input id="remember-me" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                Biarkan saya tetap masuk
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="submit-btn" id="login-btn">
            Masuk ke Sistem
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </form>

    <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">ATAU</span>
        <div class="divider-line"></div>
    </div>
    
    <x-slot name="footer">
        @if (Route::has('register'))
            Belum punya akun? <a href="{{ route('register') }}" class="forgot-link" style="margin-left:5px">Daftar sekarang</a>
        @endif
    </x-slot>
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

// Add simple loading state to button
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('login-btn');
    btn.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> Memproses...`;
    btn.style.opacity = '0.7';
    btn.style.cursor = 'not-allowed';
});
</script>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }
</style>
</x-guest-layout>
