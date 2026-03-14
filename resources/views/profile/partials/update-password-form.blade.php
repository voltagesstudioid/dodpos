<div>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">Password Saat Ini <span class="required">*</span></label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" required>
            @if ($errors->updatePassword->get('current_password'))
                <div class="form-error">{{ $errors->updatePassword->get('current_password')[0] }}</div>
            @endif
        </div>

        <div class="form-row" style="margin-bottom: 1rem;">
            <div class="form-group" style="margin:0;">
                <label for="update_password_password" class="form-label">Password Baru <span class="required">*</span></label>
                <input id="update_password_password" name="password" type="password" class="form-input" autocomplete="new-password" required>
                @if ($errors->updatePassword->get('password'))
                    <div class="form-error">{{ $errors->updatePassword->get('password')[0] }}</div>
                @endif
            </div>

            <div class="form-group" style="margin:0;">
                <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" required>
                @if ($errors->updatePassword->get('password_confirmation'))
                    <div class="form-error">{{ $errors->updatePassword->get('password_confirmation')[0] }}</div>
                @endif
            </div>
        </div>

        <div style="display:flex;gap:0.75rem;align-items:center;justify-content:flex-end;flex-wrap:wrap;">
            @if (session('status') === 'password-updated')
                <span style="font-size:0.8125rem;color:#16a34a;font-weight:800;">Tersimpan</span>
            @endif
            <button type="submit" class="btn-primary">🔑 Simpan</button>
        </div>
    </form>
</div>
