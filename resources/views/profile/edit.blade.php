<x-app-layout>
    <x-slot name="header">Profil Saya</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .pf-page { max-width:68rem; margin:0 auto; padding:1.5rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .pf-grid { display:grid; grid-template-columns:320px 1fr; gap:1.5rem; align-items:start; }

        /* Profile Card */
        .pf-card { background:#fff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden; box-shadow:0 4px 20px -5px rgba(0,0,0,0.06); }
        .pf-card-gradient { padding:2rem 1.5rem; text-align:center; background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 50%,#a855f7 100%); position:relative; }
        .pf-card-gradient::after { content:''; position:absolute; bottom:0; left:0; right:0; height:40px; background:linear-gradient(to top,#fff,transparent); }

        .pf-avatar-wrap { position:relative; display:inline-block; margin-bottom:1rem; }
        .pf-avatar { width:100px; height:100px; border-radius:50%; overflow:hidden; border:4px solid rgba(255,255,255,0.3); display:flex; align-items:center; justify-content:center; margin:0 auto; background:rgba(255,255,255,0.15); }
        .pf-avatar img { width:100%; height:100%; object-fit:cover; }
        .pf-avatar-letter { font-weight:800; color:#fff; font-size:2.5rem; }
        .pf-status-dot { position:absolute; bottom:4px; right:4px; width:18px; height:18px; border-radius:50%; background:#10b981; border:3px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.15); }

        .pf-card-body { padding:1.5rem; text-align:center; }
        .pf-name { font-size:1.25rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem; }
        .pf-email { font-size:0.8125rem; color:#64748b; margin-bottom:1rem; }

        .pf-badges { display:flex; justify-content:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.25rem; }
        .pf-badge { display:inline-flex; align-items:center; gap:0.3rem; padding:0.25rem 0.65rem; border-radius:999px; font-size:0.6875rem; font-weight:700; }
        .pf-badge.id { background:#f1f5f9; color:#475569; }
        .pf-badge.role { background:#e0e7ff; color:#4338ca; }
        .pf-badge.division { background:#fef3c7; color:#92400e; }

        .pf-info-list { text-align:left; }
        .pf-info-item { display:flex; align-items:center; gap:0.65rem; padding:0.6rem 0; border-bottom:1px solid #f1f5f9; }
        .pf-info-item:last-child { border-bottom:none; }
        .pf-info-ico { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.875rem; flex-shrink:0; }
        .pf-info-lbl { font-size:0.6875rem; color:#94a3b8; font-weight:600; }
        .pf-info-val { font-size:0.8125rem; font-weight:600; color:#1e293b; }

        /* Main Content */
        .pf-section { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .pf-section-hdr { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.75rem; }
        .pf-section-ico { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .pf-section-title { font-size:0.9375rem; font-weight:700; color:#1e293b; }
        .pf-section-sub { font-size:0.75rem; color:#64748b; }
        .pf-section-body { padding:1.5rem; }

        .pf-alert { padding:0.75rem 1rem; border-radius:10px; font-size:0.8125rem; font-weight:600; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; }
        .pf-alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
        .pf-alert-danger { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        .pf-alert-info { background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; }

        .pf-fg { margin-bottom:1.25rem; }
        .pf-fl { display:block; font-size:0.8125rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .pf-fl .req { color:#ef4444; }
        .pf-fi { width:100%; padding:0.625rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.8125rem; font-family:inherit; transition:all 0.2s; box-sizing:border-box; background:#fff; }
        .pf-fi:focus { outline:none; border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
        .pf-fi-error { border-color:#ef4444; }
        .pf-fi-error:focus { box-shadow:0 0 0 3px rgba(239,68,68,0.1); }
        .pf-error-text { font-size:0.72rem; color:#dc2626; font-weight:600; margin-top:0.375rem; }
        .pf-hint { font-size:0.72rem; color:#94a3b8; margin-top:0.375rem; }

        .pf-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }

        /* Photo Upload */
        .pf-photo-area { display:flex; gap:1.25rem; align-items:center; padding:1.25rem; background:#f8fafc; border-radius:12px; border:1.5px dashed #cbd5e1; margin-bottom:1.5rem; }
        .pf-photo-preview { width:72px; height:72px; border-radius:16px; overflow:hidden; background:#f1f5f9; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid #e2e8f0; }
        .pf-photo-preview img { width:100%; height:100%; object-fit:cover; }
        .pf-photo-preview .letter { font-weight:800; color:#94a3b8; font-size:1.75rem; }
        .pf-photo-info { flex:1; }
        .pf-photo-label { font-size:0.8125rem; font-weight:600; color:#334155; margin-bottom:0.35rem; }
        .pf-file-input { font-size:0.8125rem; font-family:inherit; }
        .pf-file-hint { font-size:0.72rem; color:#94a3b8; margin-top:0.35rem; }

        /* Buttons */
        .pf-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:700; text-decoration:none; border:none; cursor:pointer; transition:all 0.2s; font-family:inherit; }
        .pf-btn-primary { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; box-shadow:0 2px 8px rgba(79,70,229,0.3); }
        .pf-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,0.4); }
        .pf-btn-danger { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; box-shadow:0 2px 8px rgba(220,38,38,0.2); }
        .pf-btn-danger:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(220,38,38,0.3); }
        .pf-btn-ghost { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; }
        .pf-btn-ghost:hover { background:#f8fafc; }

        .pf-save-bar { display:flex; justify-content:flex-end; align-items:center; gap:1rem; padding-top:1.25rem; border-top:1px solid #f1f5f9; }
        .pf-saved-msg { display:flex; align-items:center; gap:0.35rem; color:#059669; font-weight:700; font-size:0.8125rem; }

        /* Password toggle */
        .pf-pw-wrap { position:relative; }
        .pf-pw-toggle { position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#94a3b8; font-size:0.75rem; font-weight:600; font-family:inherit; padding:0.25rem; }
        .pf-pw-toggle:hover { color:#6366f1; }

        /* Danger Zone */
        .pf-danger-zone { border-color:#fecaca; }
        .pf-danger-zone .pf-section-hdr { background:#fef2f2; }

        @media(max-width:768px) {
            .pf-grid { grid-template-columns:1fr; }
            .pf-row { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    @php $u = auth()->user(); @endphp

    <div class="pf-page">
        @if(session('error'))
            <div class="pf-alert pf-alert-danger">❌ {{ session('error') }}</div>
        @endif

        <div class="pf-grid">
            {{-- LEFT: Profile Card --}}
            <div>
                <div class="pf-card">
                    <div class="pf-card-gradient">
                        <div class="pf-avatar-wrap">
                            @php
                                $photoUrl = $u->profile_photo_path ? route('profile.photo', $u->id) : null;
                            @endphp
                            <div class="pf-avatar">
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $u->name }}">
                                @else
                                    <span class="pf-avatar-letter">{{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="pf-status-dot" title="Aktif"></div>
                        </div>
                    </div>
                    <div class="pf-card-body">
                        <div class="pf-name">{{ $u->name ?? '-' }}</div>
                        <div class="pf-email">{{ $u->email ?? '-' }}</div>

                        <div class="pf-badges">
                            <span class="pf-badge id">ID: {{ str_pad($u->id ?? 0, 4, '0', STR_PAD_LEFT) }}</span>
                            <span class="pf-badge role">{{ ucfirst($u->role ?? 'User') }}</span>
                            <span class="pf-badge division">{{ ucfirst($u->division ?? 'Umum') }}</span>
                        </div>

                        <div class="pf-info-list">
                            <div class="pf-info-item">
                                <div class="pf-info-ico" style="background:#f0fdf4;">🆔</div>
                                <div>
                                    <div class="pf-info-lbl">NIK</div>
                                    <div class="pf-info-val">{{ $u->nik ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="pf-info-item">
                                <div class="pf-info-ico" style="background:#eff6ff;">📧</div>
                                <div>
                                    <div class="pf-info-lbl">Email</div>
                                    <div class="pf-info-val">{{ $u->email ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="pf-info-item">
                                <div class="pf-info-ico" style="background:#fef3c7;">🔑</div>
                                <div>
                                    <div class="pf-info-lbl">Status Akun</div>
                                    <div class="pf-info-val" style="color:{{ $u->active ? '#059669' : '#dc2626' }};">
                                        {{ $u->active ? 'Aktif' : 'Nonaktif' }}
                                    </div>
                                </div>
                            </div>
                            <div class="pf-info-item">
                                <div class="pf-info-ico" style="background:#f1f5f9;">📅</div>
                                <div>
                                    <div class="pf-info-lbl">Bergabung</div>
                                    <div class="pf-info-val">{{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Forms --}}
            <div>
                {{-- Personal Info Section --}}
                <div class="pf-section">
                    <div class="pf-section-hdr">
                        <div class="pf-section-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#059669;">👤</div>
                        <div>
                            <div class="pf-section-title">Informasi Personal</div>
                            <div class="pf-section-sub">Perbarui nama, NIK, email, dan foto profil Anda</div>
                        </div>
                    </div>
                    <div class="pf-section-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Change Password Section --}}
                <div class="pf-section">
                    <div class="pf-section-hdr">
                        <div class="pf-section-ico" style="background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e;">🔒</div>
                        <div>
                            <div class="pf-section-title">Ubah Password</div>
                            <div class="pf-section-sub">Kosongkan jika tidak ingin mengubah password</div>
                        </div>
                    </div>
                    <div class="pf-section-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div class="pf-section pf-danger-zone">
                    <div class="pf-section-hdr">
                        <div class="pf-section-ico" style="background:#fef2f2; color:#dc2626;">⚠️</div>
                        <div>
                            <div class="pf-section-title" style="color:#991b1b;">Zona Berbahaya</div>
                            <div class="pf-section-sub">Tindakan ini tidak dapat dibatalkan</div>
                        </div>
                    </div>
                    <div class="pf-section-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
