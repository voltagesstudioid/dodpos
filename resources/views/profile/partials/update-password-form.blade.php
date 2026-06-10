<div>
    <form method="post" action="{{ route('password.update') }}" id="passwordForm">
        @csrf
        @method('put')

        <div class="pf-fg">
            <label class="pf-fl">Password Saat Ini <span class="req">*</span></label>
            <div class="pf-pw-wrap">
                <input name="current_password" type="password" id="currentPw" class="pf-fi @if($errors->updatePassword->get('current_password')) pf-fi-error @endif"
                       autocomplete="current-password" required placeholder="Masukkan password saat ini">
                <button type="button" class="pf-pw-toggle" onclick="togglePw('currentPw', this)">Lihat</button>
            </div>
            @if($errors->updatePassword->get('current_password'))
                <div class="pf-error-text">{{ $errors->updatePassword->get('current_password')[0] }}</div>
            @endif
        </div>

        <div class="pf-row">
            <div class="pf-fg">
                <label class="pf-fl">Password Baru <span class="req">*</span></label>
                <div class="pf-pw-wrap">
                    <input name="password" type="password" id="newPw" class="pf-fi @if($errors->updatePassword->get('password')) pf-fi-error @endif"
                           autocomplete="new-password" required placeholder="Minimal 8 karakter">
                    <button type="button" class="pf-pw-toggle" onclick="togglePw('newPw', this)">Lihat</button>
                </div>
                @if($errors->updatePassword->get('password'))
                    <div class="pf-error-text">{{ $errors->updatePassword->get('password')[0] }}</div>
                @endif
            </div>
            <div class="pf-fg">
                <label class="pf-fl">Konfirmasi Password <span class="req">*</span></label>
                <div class="pf-pw-wrap">
                    <input name="password_confirmation" type="password" id="confirmPw" class="pf-fi @if($errors->updatePassword->get('password_confirmation')) pf-fi-error @endif"
                           autocomplete="new-password" required placeholder="Ulangi password baru">
                    <button type="button" class="pf-pw-toggle" onclick="togglePw('confirmPw', this)">Lihat</button>
                </div>
                @if($errors->updatePassword->get('password_confirmation'))
                    <div class="pf-error-text">{{ $errors->updatePassword->get('password_confirmation')[0] }}</div>
                @endif
            </div>
        </div>

        <div class="pf-save-bar">
            @if(session('status') === 'password-updated')
                <div class="pf-saved-msg">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Password Diperbarui
                </div>
            @endif
            <button type="submit" class="pf-btn pf-btn-primary">🔑 Ubah Password</button>
        </div>
    </form>
</div>
