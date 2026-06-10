<div>
    <div class="pf-alert pf-alert-danger" style="margin-bottom:1rem;">
        ⚠️ Menghapus akun bersifat <strong>permanen</strong> dan tidak bisa dibatalkan. Semua data yang terkait dengan akun ini akan hilang.
    </div>

    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin hapus akun? Tindakan ini TIDAK BISA dibatalkan.');">
        @csrf
        @method('delete')

        <div class="pf-fg" style="margin-bottom:1rem;">
            <label class="pf-fl">Konfirmasi Password <span class="req">*</span></label>
            <div class="pf-pw-wrap">
                <input name="password" type="password" id="deletePw" class="pf-fi @if($errors->userDeletion->get('password')) pf-fi-error @endif"
                       placeholder="Masukkan password untuk konfirmasi" required>
                <button type="button" class="pf-pw-toggle" onclick="togglePw('deletePw', this)">Lihat</button>
            </div>
            @if($errors->userDeletion->get('password'))
                <div class="pf-error-text">{{ $errors->userDeletion->get('password')[0] }}</div>
            @endif
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="submit" class="pf-btn pf-btn-danger">🗑️ Hapus Akun Saya</button>
        </div>
    </form>
</div>
