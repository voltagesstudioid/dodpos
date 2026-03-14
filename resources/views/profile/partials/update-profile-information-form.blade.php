<div>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div style="display:flex; flex-direction:column; gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Foto Profil -->
            <div style="display: flex; gap: 1.25rem; align-items: flex-end; padding-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9;">
                @php
                    $photoUrl = $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) : null;
                @endphp
                <div style="width: 80px; height: 80px; border-radius: 18px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="font-weight: 800; color: #94a3b8; font-size: 2rem;">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
                    @endif
                </div>
                <div style="flex: 1;">
                    <label for="photo" class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Unggah Foto Baru</label>
                    <input id="photo" name="photo" type="file" class="form-input" accept="image/png,image/jpeg" style="padding: 0.6rem; background: #f8fafc; border: 1px dashed #cbd5e1;">
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem;">Format dukungan: JPG, JPEG, PNG. Maksimal 2MB.</div>
                    @if ($errors->get('photo'))
                        <div class="form-error" style="margin-top: 0.5rem;">{{ $errors->get('photo')[0] }}</div>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="margin: 0;">
                    <label for="name" class="form-label" style="font-weight: 600; color: #475569;">Nama Lengkap <span class="required" style="color: #ef4444;">*</span></label>
                    <input id="name" name="name" type="text" class="form-input" style="background:#f8fafc;" value="{{ old('name', $user->name) }}" required autocomplete="name">
                    @if ($errors->get('name'))
                        <div class="form-error">{{ $errors->get('name')[0] }}</div>
                    @endif
                </div>

                <div class="form-group" style="margin: 0;">
                    <label for="nik" class="form-label" style="font-weight: 600; color: #475569;">NIK <span class="required" style="color: #ef4444;">*</span></label>
                    <input id="nik" name="nik" type="text" class="form-input" style="background:#f8fafc;" value="{{ old('nik', $user->nik) }}" required autocomplete="off">
                    @if ($errors->get('nik'))
                        <div class="form-error">{{ $errors->get('nik')[0] }}</div>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="margin: 0;">
                    <label for="email" class="form-label" style="font-weight: 600; color: #475569;">Alamat Email <span class="required" style="color: #ef4444;">*</span></label>
                    <input id="email" name="email" type="email" class="form-input" style="background:#f8fafc;" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @if ($errors->get('email'))
                        <div class="form-error">{{ $errors->get('email')[0] }}</div>
                    @endif
                </div>

                <div class="form-group" style="margin: 0;">
                    <label for="password_profile" class="form-label" style="font-weight: 600; color: #475569;">Password <span class="required" style="color: #ef4444;">*</span></label>
                    <input id="password_profile" name="password" type="password" class="form-input" style="background:#f8fafc;" placeholder="Masukkan password untuk menyimpan" required autocomplete="new-password">
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.35rem;">Diperlukan untuk memvalidasi perubahan profil Anda.</div>
                    @if ($errors->get('password'))
                        <div class="form-error">{{ $errors->get('password')[0] }}</div>
                    @endif
                </div>
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-info" role="alert" style="margin-bottom: 1rem;">
                <div style="flex:1;">
                    Email Anda belum terverifikasi.
                    <div style="margin-top:0.5rem;">
                        <button form="send-verification" type="submit" class="btn-secondary btn-sm">Kirim ulang verifikasi</button>
                    </div>
                    @if (session('status') === 'verification-link-sent')
                        <div style="margin-top:0.6rem;font-size:0.8125rem;color:#1d4ed8;">Link verifikasi baru sudah dikirim.</div>
                    @endif
                </div>
            </div>
        @endif

        <div style="display:flex;gap:1.5rem;align-items:center;justify-content:flex-end;padding-top: 1rem; border-top: 1px solid #f1f5f9;">
            @if (session('status') === 'profile-updated')
                <div style="display: flex; align-items: center; gap: 0.4rem; color: #10b981; font-weight: 600; font-size: 0.9rem; animation: fadeIn 0.3s;">
                    <svg xmlns="http://www.w3.org/Dom/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Tersimpan
                </div>
            @endif
            <button type="submit" class="btn-primary" style="padding: 0.6rem 1.75rem; border-radius: 99px;">Simpan Perubahan</button>
        </div>
    </form>
</div>
