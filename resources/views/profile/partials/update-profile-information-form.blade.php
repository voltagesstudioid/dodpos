<div>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('patch')

        {{-- Photo Upload --}}
        <div class="pf-photo-area">
            @php
                $formPhotoUrl = $user->profile_photo_path ? route('profile.photo', $user->id) : null;
            @endphp
            <div class="pf-photo-preview" id="photoPreview">
                @if($formPhotoUrl)
                    <img src="{{ $formPhotoUrl }}" alt="Preview" id="previewImg">
                @else
                    <span class="letter" id="previewLetter">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                @endif
            </div>
            <div class="pf-photo-info">
                <div class="pf-photo-label">📷 Unggah Foto Baru</div>
                <input id="photo" name="photo" type="file" class="pf-file-input" accept="image/png,image/jpeg">
                <div class="pf-file-hint">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                @if($errors->get('photo'))
                    <div class="pf-error-text" style="margin-top:0.25rem;">{{ $errors->get('photo')[0] }}</div>
                @endif
            </div>
        </div>

        {{-- Name & NIK --}}
        <div class="pf-row">
            <div class="pf-fg">
                <label class="pf-fl">Nama Lengkap <span class="req">*</span></label>
                <input name="name" type="text" class="pf-fi @if($errors->get('name')) pf-fi-error @endif"
                       value="{{ old('name', $user->name) }}" required autocomplete="name"
                       placeholder="Nama lengkap Anda">
                @if($errors->get('name'))
                    <div class="pf-error-text">{{ $errors->get('name')[0] }}</div>
                @endif
            </div>
            <div class="pf-fg">
                <label class="pf-fl">NIK <span class="req">*</span></label>
                <input name="nik" type="text" class="pf-fi @if($errors->get('nik')) pf-fi-error @endif"
                       value="{{ old('nik', $user->nik) }}" required autocomplete="off"
                       placeholder="Nomor Induk Karyawan">
                @if($errors->get('nik'))
                    <div class="pf-error-text">{{ $errors->get('nik')[0] }}</div>
                @endif
            </div>
        </div>

        {{-- Email --}}
        <div class="pf-row">
            <div class="pf-fg">
                <label class="pf-fl">Alamat Email <span class="req">*</span></label>
                <input name="email" type="email" class="pf-fi @if($errors->get('email')) pf-fi-error @endif"
                       value="{{ old('email', $user->email) }}" required autocomplete="username"
                       placeholder="email@contoh.com">
                @if($errors->get('email'))
                    <div class="pf-error-text">{{ $errors->get('email')[0] }}</div>
                @endif
            </div>
        </div>

        {{-- Email verification --}}
        @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="pf-alert pf-alert-info" style="margin-top:0.5rem;">
                <div style="flex:1;">
                    📧 Email Anda belum terverifikasi.
                    <button form="send-verification" type="submit" style="background:none;border:none;color:#2563eb;font-weight:700;cursor:pointer;text-decoration:underline;font-family:inherit;font-size:inherit;">Kirim ulang verifikasi</button>
                    @if(session('status') === 'verification-link-sent')
                        <div style="margin-top:0.35rem;color:#059669;font-weight:600;">✓ Link verifikasi baru sudah dikirim.</div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Submit --}}
        <div class="pf-save-bar">
            @if(session('status') === 'profile-updated')
                <div class="pf-saved-msg">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Tersimpan
                </div>
            @endif
            <button type="submit" class="pf-btn pf-btn-primary">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
function togglePw(id, btn) {
    var inp = document.getElementById(id);
    if (!inp) return;
    if (inp.type === 'password') {
        inp.type = 'text';
        btn.textContent = 'Sembunyi';
    } else {
        inp.type = 'password';
        btn.textContent = 'Lihat';
    }
}

// Photo preview
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('photo');
    var preview = document.getElementById('photoPreview');
    if (fileInput && preview) {
        fileInput.addEventListener('change', function() {
            var file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width:100%;height:100%;object-fit:cover;">';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
