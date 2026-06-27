<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .pcf-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Back navigation */
        .pcf-nav { display:flex; align-items:center; gap:10px; margin-bottom:1.75rem; }
        .pcf-back-btn {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            background:#fff; border:1.5px solid #e2e8f0; color:#64748b; text-decoration:none; transition:all 0.2s; flex-shrink:0;
        }
        .pcf-back-btn:hover { background:#f8fafc; border-color:#cbd5e1; color:#2563eb; transform:translateX(-2px); }
        .pcf-nav-text { font-size:0.8125rem; font-weight:600; color:#94a3b8; }
        .pcf-nav-sep { color:#cbd5e1; }
        .pcf-nav-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

        /* Page header */
        .pcf-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:2rem; }
        .pcf-hdr-ico {
            width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center;
            flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#1d4ed8);
            box-shadow:0 8px 24px rgba(37,99,235,0.3);
        }
        .pcf-hdr-ico svg { width:28px; height:28px; color:#fff; }
        .pcf-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pcf-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .pcf-kode-badge {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.25rem 0.625rem; border-radius:8px;
            background:linear-gradient(135deg,#eff6ff,#dbeafe);
            font-size:0.75rem; font-weight:700; color:#2563eb;
            font-family:'JetBrains Mono',monospace; letter-spacing:-0.01em;
            margin-top:0.375rem;
        }
        .pcf-kode-badge svg { flex-shrink:0; }

        /* Info tip */
        .pcf-tip {
            display:flex; align-items:center; gap:0.625rem;
            padding:0.75rem 1rem; border-radius:12px; margin-bottom:1.5rem;
            background:linear-gradient(135deg,#eff6ff,#f0f7ff); border:1px solid #bfdbfe;
        }
        .pcf-tip-ico {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; flex-shrink:0;
        }
        .pcf-tip-ico svg { width:16px; height:16px; }
        .pcf-tip-text { font-size:0.75rem; color:#1e40af; line-height:1.5; }

        /* Section card */
        .pcf-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden;
            box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; transition:box-shadow 0.3s;
        }
        .pcf-card:hover { box-shadow:0 6px 24px rgba(0,0,0,0.07); }
        .pcf-card-hdr { padding:1rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .pcf-card-hdr.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .pcf-card-hdr.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .pcf-card-hdr.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .pcf-card-hdr.pink { background:linear-gradient(135deg,#fce7f3,#fdf2f8); }
        .pcf-card-ico {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            flex-shrink:0;
        }
        .pcf-card-ico svg { width:18px; height:18px; }
        .pcf-card-ico.blue { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .pcf-card-ico.green { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .pcf-card-ico.purple { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }
        .pcf-card-ico.pink { background:linear-gradient(135deg,#ec4899,#db2777); color:#fff; }
        .pcf-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .pcf-card-desc { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .pcf-card-body { padding:1.5rem; }

        /* Form grid */
        .pcf-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
        .pcf-full { grid-column:1 / -1; }

        /* Labels */
        .pcf-lbl {
            display:flex; align-items:center; gap:0.375rem;
            font-size:0.6875rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.06em; color:#64748b; margin-bottom:0.5rem;
        }
        .pcf-lbl-ico { color:#94a3b8; flex-shrink:0; }
        .pcf-req { color:#ef4444; font-weight:800; }

        /* Inputs */
        .pcf-input {
            width:100%; padding:0.6875rem 0.875rem; border-radius:11px; border:1.5px solid #e2e8f0;
            background:#f8fafc; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit;
        }
        .pcf-input:focus { border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        .pcf-input::placeholder { color:#cbd5e1; }
        .pcf-input.green:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.1); }
        .pcf-input.purple:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,0.1); }

        select.pcf-input {
            cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.75rem center; background-size:14px;
            padding-right:2.25rem;
        }
        textarea.pcf-input { resize:none; min-height:80px; }

        .pcf-input-wrap { position:relative; }
        .pcf-input-prefix {
            position:absolute; left:0.875rem; top:50%; transform:translateY(-50%);
            font-size:0.8125rem; font-weight:600; color:#94a3b8; pointer-events:none;
        }
        .pcf-input.has-prefix { padding-left:2.75rem; }

        .pcf-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; display:flex; align-items:center; gap:0.25rem; }
        .pcf-err { font-size:0.75rem; font-weight:500; color:#ef4444; margin-top:0.375rem; display:flex; align-items:center; gap:0.375rem; }

        /* Radio cards — grid layout */
        .pcf-radio-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0.625rem; }
        .pcf-radio-card {
            display:flex; align-items:center; gap:0.75rem;
            padding:0.75rem 1rem; border-radius:12px; border:1.5px solid #e2e8f0;
            cursor:pointer; transition:all 0.2s; background:#fff;
        }
        .pcf-radio-card:hover { border-color:#cbd5e1; background:#f8fafc; }
        .pcf-radio-card.selected { border-color:var(--c); background:var(--bg); box-shadow:0 0 0 3px var(--ring, transparent); }
        .pcf-radio-card input[type="radio"] { display:none; }
        .pcf-radio-check {
            width:22px; height:22px; border-radius:7px; border:2px solid #cbd5e1;
            flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:all 0.2s;
        }
        .pcf-radio-check svg { width:12px; height:12px; color:#fff; opacity:0; transition:opacity 0.2s; }
        .pcf-radio-card.selected .pcf-radio-check { border-color:var(--c); background:var(--c); }
        .pcf-radio-card.selected .pcf-radio-check svg { opacity:1; }
        .pcf-radio-ico {
            width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center;
            font-size:0.9375rem; flex-shrink:0;
        }
        .pcf-radio-info { flex:1; min-width:0; }
        .pcf-radio-title { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .pcf-radio-desc { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        /* Section divider */
        .pcf-divider { height:1px; background:#f1f5f9; margin:1.25rem 0; }

        /* Note banner inside card */
        .pcf-note {
            display:flex; align-items:flex-start; gap:0.5rem;
            padding:0.625rem 0.875rem; border-radius:10px; margin-bottom:1.25rem;
            background:#f8fafc; border:1px dashed #cbd5e1;
            font-size:0.75rem; color:#64748b; line-height:1.5;
        }
        .pcf-note svg { flex-shrink:0; margin-top:1px; color:#94a3b8; }

        /* Actions */
        .pcf-actions {
            display:flex; align-items:center; justify-content:flex-end; gap:0.75rem;
            padding:1rem 0; margin-top:0.5rem;
        }
        .pcf-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.75rem 1.5rem; border-radius:12px; font-size:0.8125rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.25s; font-family:inherit; text-decoration:none;
        }
        .pcf-btn svg { width:16px; height:16px; }
        .pcf-btn-cancel { background:#fff; border:1.5px solid #e2e8f0; color:#64748b; }
        .pcf-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }
        .pcf-btn-save {
            background:linear-gradient(135deg,#3b82f6,#1d4ed8); color:#fff;
            box-shadow:0 6px 20px rgba(37,99,235,0.35);
        }
        .pcf-btn-save:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(37,99,235,0.45); }

        @media(max-width:640px) {
            .pcf-grid { grid-template-columns:1fr; }
            .pcf-hdr-title { font-size:1.25rem; }
            .pcf-actions { flex-direction:column-reverse; }
            .pcf-btn { width:100%; justify-content:center; }
            .pcf-radio-grid { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="pcf-page">

        {{-- Navigation --}}
        <nav class="pcf-nav">
            <a href="{{ route('mineral.pelanggan.index') }}" class="pcf-back-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <span class="pcf-nav-text">Data Pelanggan</span>
            <span class="pcf-nav-sep">/</span>
            <span class="pcf-nav-crumb">Edit</span>
        </nav>

        {{-- Header --}}
        <div class="pcf-hdr">
            <div class="pcf-hdr-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="pcf-hdr-title">Edit Pelanggan Mineral</div>
                <div class="pcf-hdr-sub">Ubah data pelanggan divisi mineral</div>
                <div class="pcf-kode-badge">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    {{ $pelanggan->kode_pelanggan }}
                </div>
            </div>
        </div>

        {{-- Tip banner --}}
        <div class="pcf-tip">
            <div class="pcf-tip-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <div class="pcf-tip-text">Pastikan data pelanggan diisi dengan lengkap dan benar. Field bertanda <strong>*</strong> wajib diisi.</div>
        </div>

        <form method="POST" action="{{ route('mineral.pelanggan.update', $pelanggan) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Section 1: Informasi Dasar --}}
            <div class="pcf-card">
                <div class="pcf-card-hdr blue">
                    <div class="pcf-card-ico blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <div class="pcf-card-title">Informasi Dasar</div>
                        <div class="pcf-card-desc">Data identitas toko dan pemilik</div>
                    </div>
                </div>
                <div class="pcf-card-body">
                    <div class="pcf-grid">
                        {{-- Nama Toko --}}
                        <div class="pcf-full">
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                                Nama Toko <span class="pcf-req">*</span>
                            </label>
                            <input type="text" name="nama_toko" value="{{ old('nama_toko', $pelanggan->nama_toko) }}" required placeholder="Contoh: Toko Maju Jaya" class="pcf-input" maxlength="100" autocomplete="off">
                            @error('nama_toko')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        {{-- Nama Pemilik --}}
                        <div>
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Nama Pemilik <span class="pcf-req">*</span>
                            </label>
                            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $pelanggan->nama_pemilik) }}" required placeholder="Nama lengkap pemilik" class="pcf-input" autocomplete="off">
                            @error('nama_pemilik')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                No HP / WhatsApp
                            </label>
                            <div class="pcf-input-wrap">
                                <span class="pcf-input-prefix">+62</span>
                                <input type="text" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp) }}" placeholder="81234567890" class="pcf-input has-prefix" inputmode="numeric">
                            </div>
                            @error('no_hp')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="pcf-full">
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" placeholder="email@contoh.com" class="pcf-input" autocomplete="off">
                            @error('email')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Informasi Lokasi --}}
            <div class="pcf-card">
                <div class="pcf-card-hdr green">
                    <div class="pcf-card-ico green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <div class="pcf-card-title">Informasi Lokasi <span style="font-weight:500; font-size:0.6875rem; color:#94a3b8; margin-left:4px;">(Opsional)</span></div>
                        <div class="pcf-card-desc">Alamat dan koordinat toko pelanggan</div>
                    </div>
                </div>
                <div class="pcf-card-body">
                    <div class="pcf-note">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div><strong>Alamat akan terisi otomatis</strong> saat sales pertama kali check-in kunjungan ke lokasi pelanggan. Kamu bisa lewati bagian ini jika belum tahu.</div>
                    </div>
                    <div class="pcf-grid">
                        {{-- Alamat --}}
                        <div class="pcf-full">
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Alamat Lengkap
                            </label>
                            <textarea name="alamat" rows="3" placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan" class="pcf-input green">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                            @error('alamat')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        {{-- Kecamatan --}}
                        <div>
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                Kecamatan
                            </label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $pelanggan->kecamatan) }}" placeholder="Nama kecamatan" class="pcf-input green" autocomplete="off">
                        </div>

                        {{-- Kota --}}
                        <div>
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Kota / Kabupaten
                            </label>
                            <input type="text" name="kota" value="{{ old('kota', $pelanggan->kota) }}" placeholder="Nama kota" class="pcf-input green" autocomplete="off">
                        </div>

                        {{-- Latitude --}}
                        <div>
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><line x1="2" y1="12" x2="22" y2="12" stroke-width="2"/></svg>
                                Latitude
                            </label>
                            <input type="number" step="any" name="latitude" value="{{ old('latitude', $pelanggan->latitude) }}" placeholder="-6.123456" class="pcf-input green">
                        </div>

                        {{-- Longitude --}}
                        <div>
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" stroke-width="2"/></svg>
                                Longitude
                            </label>
                            <input type="number" step="any" name="longitude" value="{{ old('longitude', $pelanggan->longitude) }}" placeholder="106.123456" class="pcf-input green">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Foto Toko --}}
            <div class="pcf-card">
                <div class="pcf-card-hdr pink">
                    <div class="pcf-card-ico pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                    <div>
                        <div class="pcf-card-title">Foto Toko</div>
                        <div class="pcf-card-desc">Upload foto terbaru atau ganti foto yang sudah ada</div>
                    </div>
                </div>
                <div class="pcf-card-body">
                    {{-- Current photo --}}
                    @if($pelanggan->foto_toko)
                    <div style="margin-bottom:1.25rem;">
                        <div style="font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; margin-bottom:0.5rem;">Foto Saat Ini</div>
                        <img src="{{ asset('storage/' . $pelanggan->foto_toko) }}" alt="Foto Toko" style="max-width:100%; max-height:250px; border-radius:12px; border:2px solid #e2e8f0; display:block;">
                    </div>
                    @endif

                    <div id="foto-preview-wrap" style="text-align:center; margin-bottom:1rem;">
                        <img id="foto-preview" src="" alt="Preview Baru" style="display:none; max-width:100%; max-height:300px; border-radius:12px; border:2px solid #e2e8f0;">
                    </div>
                    <div class="pcf-grid" style="gap:0.75rem;">
                        <div>
                            <label class="pcf-btn" style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:12px; border:2px dashed #f9a8d4; background:#fdf2f8; cursor:pointer; width:100%; font-size:0.8125rem; font-weight:600; color:#be185d; transition:all 0.2s;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                @if($pelanggan->foto_toko) Ganti Foto @else Upload Foto Toko @endif
                                <input type="file" name="foto_toko" id="inp-foto-toko" accept="image/*" capture="environment" style="display:none;">
                            </label>
                        </div>
                        <div style="display:flex; align-items:center;">
                            <div style="font-size:0.75rem; color:#64748b; line-height:1.5;">
                                @if($isSalesRole)
                                <strong style="color:#be185d;">Wajib upload foto toko.</strong><br>
                                Supervisor akan menentukan limit berdasarkan kondisi toko.
                                @else
                                Kosongkan jika tidak ingin mengubah foto.
                                @endif
                            </div>
                        </div>
                    </div>
                    @error('foto_toko')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Section 4: Tipe & Status --}}
            <div class="pcf-card">
                <div class="pcf-card-hdr purple">
                    <div class="pcf-card-ico purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                    </div>
                    <div>
                        <div class="pcf-card-title">Pengaturan</div>
                        <div class="pcf-card-desc">Tipe pelanggan, regional, @if($isSalesRole) dan informasi lainnya @else status, dan limit hutang @endif</div>
                    </div>
                </div>
                <div class="pcf-card-body">
                    @php
                        $currentTipe = old('tipe', $pelanggan->tipe);
                        $currentStatus = old('status', $pelanggan->status);
                    @endphp

                    {{-- Tipe Pelanggan --}}
                    <label class="pcf-lbl">Tipe Pelanggan <span class="pcf-req">*</span></label>
                    <div class="pcf-radio-grid" id="tipeGroup">
                        <label class="pcf-radio-card {{ $currentTipe == 'eceran' ? 'selected' : '' }}" style="--c:#2563eb;--bg:#eff6ff;--ring:rgba(37,99,235,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="tipe" value="eceran" {{ $currentTipe == 'eceran' ? 'checked' : '' }} required>
                            <div class="pcf-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="pcf-radio-ico" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">🛒</div>
                            <div class="pcf-radio-info">
                                <div class="pcf-radio-title">Eceran</div>
                                <div class="pcf-radio-desc">Toko kecil / ritel</div>
                            </div>
                        </label>
                        <label class="pcf-radio-card {{ $currentTipe == 'grosir' ? 'selected' : '' }}" style="--c:#7c3aed;--bg:#f5f3ff;--ring:rgba(124,58,237,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="tipe" value="grosir" {{ $currentTipe == 'grosir' ? 'checked' : '' }}>
                            <div class="pcf-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="pcf-radio-ico" style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);">🏭</div>
                            <div class="pcf-radio-info">
                                <div class="pcf-radio-title">Grosir</div>
                                <div class="pcf-radio-desc">Pembelian besar</div>
                            </div>
                        </label>
                        <label class="pcf-radio-card {{ $currentTipe == 'agen' ? 'selected' : '' }}" style="--c:#ea580c;--bg:#fff7ed;--ring:rgba(234,88,12,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="tipe" value="agen" {{ $currentTipe == 'agen' ? 'checked' : '' }}>
                            <div class="pcf-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="pcf-radio-ico" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);">🤝</div>
                            <div class="pcf-radio-info">
                                <div class="pcf-radio-title">Agen</div>
                                <div class="pcf-radio-desc">Mitra distribusi</div>
                            </div>
                        </label>
                    </div>
                    @error('tipe')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror

                    @if(! $isSalesRole)
                    <div class="pcf-divider"></div>

                    {{-- Status --}}
                    <label class="pcf-lbl">Status <span class="pcf-req">*</span></label>
                    <div class="pcf-radio-grid" id="statusGroup">
                        <label class="pcf-radio-card {{ $currentStatus == 'aktif' ? 'selected' : '' }}" style="--c:#059669;--bg:#ecfdf5;--ring:rgba(5,150,105,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="status" value="aktif" {{ $currentStatus == 'aktif' ? 'checked' : '' }} required>
                            <div class="pcf-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="pcf-radio-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">✅</div>
                            <div class="pcf-radio-info">
                                <div class="pcf-radio-title">Aktif</div>
                                <div class="pcf-radio-desc">Bisa bertransaksi</div>
                            </div>
                        </label>
                        <label class="pcf-radio-card {{ $currentStatus == 'nonaktif' ? 'selected' : '' }}" style="--c:#64748b;--bg:#f8fafc;--ring:rgba(100,116,139,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="status" value="nonaktif" {{ $currentStatus == 'nonaktif' ? 'checked' : '' }}>
                            <div class="pcf-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="pcf-radio-ico" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);">⏸️</div>
                            <div class="pcf-radio-info">
                                <div class="pcf-radio-title">Nonaktif</div>
                                <div class="pcf-radio-desc">Sementara berhenti</div>
                            </div>
                        </label>
                        <label class="pcf-radio-card {{ $currentStatus == 'blacklist' ? 'selected' : '' }}" style="--c:#dc2626;--bg:#fef2f2;--ring:rgba(220,38,38,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="status" value="blacklist" {{ $currentStatus == 'blacklist' ? 'checked' : '' }}>
                            <div class="pcf-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div class="pcf-radio-ico" style="background:linear-gradient(135deg,#fef2f2,#fee2e2);">🚫</div>
                            <div class="pcf-radio-info">
                                <div class="pcf-radio-title">Blacklist</div>
                                <div class="pcf-radio-desc">Diblokir permanen</div>
                            </div>
                        </label>
                    </div>
                    @error('status')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror

                    <div class="pcf-divider"></div>

                    {{-- Limit Hutang --}}
                    <div class="pcf-grid">
                        <div>
                            <label class="pcf-lbl">
                                <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Limit Hutang
                            </label>
                            <div class="pcf-input-wrap">
                                <span class="pcf-input-prefix">Rp</span>
                                <input type="text" name="limit_hutang" id="limitHutang" value="{{ old('limit_hutang', $pelanggan->limit_hutang) }}" placeholder="0" class="pcf-input has-prefix purple" inputmode="numeric">
                            </div>
                            <div class="pcf-hint">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kosongkan jika tidak ada limit hutang
                            </div>
                            @error('limit_hutang')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                    @endif

                    <div class="pcf-divider"></div>

                    {{-- Regional Kerja --}}
                    <label class="pcf-lbl">
                        <svg class="pcf-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Regional Kerja
                    </label>
                    <select name="regional_id" class="pcf-input" style="max-width:400px;">
                        <option value="">— Tanpa Regional —</option>
                        @foreach($regionals as $rg)
                            <option value="{{ $rg->id }}" {{ old('regional_id', $pelanggan->regional_id) == $rg->id ? 'selected' : '' }}>{{ $rg->nama }} ({{ $rg->kode_regional }})</option>
                        @endforeach
                    </select>
                    <div class="pcf-hint">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pilih regional untuk menentukan area kerja dan harga khusus
                    </div>
                    @error('regional_id')<div class="pcf-err"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="pcf-actions">
                <a href="{{ route('mineral.pelanggan.index') }}" class="pcf-btn pcf-btn-cancel">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    Batal
                </a>
                <button type="submit" class="pcf-btn pcf-btn-save">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Pelanggan
                </button>
            </div>
        </form>

    </div>

    @push('scripts')
    <script>
    function selRadio(card) {
        var group = card.closest('.pcf-radio-grid');
        group.querySelectorAll('.pcf-radio-card').forEach(function(c) { c.classList.remove('selected'); });
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
    }

    // Foto preview
    document.getElementById('inp-foto-toko').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(ev) {
            var preview = document.getElementById('foto-preview');
            preview.src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // Format limit hutang: only digits
    var lh = document.getElementById('limitHutang');
    if (lh) {
        lh.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '');
        });
        lh.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseInt(this.value).toLocaleString('id-ID');
            }
        });
        lh.addEventListener('focus', function() {
            if (this.value) {
                this.value = this.value.replace(/\./g, '').replace(/,/g, '');
            }
        });
    }
    </script>
    @endpush
</x-app-layout>
