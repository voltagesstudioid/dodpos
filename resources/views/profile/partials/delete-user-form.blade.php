<div>
    <div style="display:flex;flex-direction:column;gap:0.9rem;">
        <div class="alert alert-danger" role="alert" style="margin:0;">
            Menghapus akun bersifat permanen dan tidak bisa dibatalkan.
        </div>

        <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin hapus akun? Tindakan ini tidak bisa dibatalkan.');">
            @csrf
            @method('delete')

            <div class="form-group" style="margin:0;">
                <label for="delete_password" class="form-label">Konfirmasi Password</label>
                <input id="delete_password" name="password" type="password" class="form-input" placeholder="Masukkan password untuk konfirmasi">
                @if ($errors->userDeletion->get('password'))
                    <div class="form-error">{{ $errors->userDeletion->get('password')[0] }}</div>
                @endif
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;margin-top:0.85rem;">
                <button type="submit" class="btn-danger">🗑️ Hapus Akun</button>
            </div>
        </form>
    </div>
</div>
