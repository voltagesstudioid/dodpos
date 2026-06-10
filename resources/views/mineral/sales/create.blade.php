<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .fm-page { max-width:52rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .fm-back { display:inline-flex; align-items:center; gap:0.5rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; padding:0.5rem 0.75rem; border-radius:10px; transition:all 0.2s; margin-bottom:1.25rem; }
        .fm-back:hover { background:#f1f5f9; color:#334155; }

        .fm-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:2rem; }
        .fm-hdr-ico { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.625rem; flex-shrink:0; background:linear-gradient(135deg,#3b82f6,#2563eb); box-shadow:0 8px 24px rgba(37,99,235,0.3); }
        .fm-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .fm-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        .fm-section { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .fm-section-hdr { display:flex; align-items:center; gap:0.75rem; padding:1rem 1.5rem; border-bottom:1px solid #e2e8f0; }
        .fm-section-ico { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .fm-section-ico svg { width:17px; height:17px; }
        .fm-section-ico.blue { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .fm-section-ico.green { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .fm-section-ico.purple { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }
        .fm-section-hdr.blue { background:linear-gradient(135deg,#eff6ff,#f0f7ff); }
        .fm-section-hdr.green { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .fm-section-hdr.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .fm-section-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .fm-section-desc { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .fm-section-body { padding:1.5rem; }

        .fm-grid { display:grid; gap:1.25rem; }
        .fm-grid-2 { grid-template-columns:repeat(2,1fr); }
        .fm-full { grid-column:1/-1; }

        .fm-lbl { display:flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; margin-bottom:0.5rem; }
        .fm-lbl-ico { color:#94a3b8; flex-shrink:0; }
        .fm-req { color:#ef4444; font-weight:800; }
        .fm-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }

        .fm-input { width:100%; padding:0.6875rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fcfcfd; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; }
        .fm-input:focus { border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .fm-input::placeholder { color:#cbd5e1; }
        .fm-input.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }

        textarea.fm-input { resize:vertical; min-height:80px; line-height:1.5; }

        .fm-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; }
        .fm-error { font-size:0.75rem; font-weight:500; color:#ef4444; margin-top:0.375rem; }

        .fm-radio-group { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .fm-radio-card { position:relative; }
        .fm-radio-card input { position:absolute; opacity:0; pointer-events:none; }
        .fm-radio-face {
            display:flex; align-items:center; gap:0.625rem; padding:0.875rem 1rem; border-radius:12px;
            border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff;
        }
        .fm-radio-face:hover { border-color:#93c5fd; background:#f8faff; }
        .fm-radio-card input:checked ~ .fm-radio-face { border-color:#3b82f6; background:linear-gradient(135deg,#eff6ff,#dbeafe); box-shadow:0 2px 8px rgba(59,130,246,0.12); }
        .fm-radio-dot { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.875rem; }
        .fm-radio-dot.ok { background:#ecfdf5; }
        .fm-radio-dot.warn { background:#fffbeb; }
        .fm-radio-dot.off { background:#f1f5f9; }
        .fm-radio-title { font-size:0.8125rem; font-weight:600; color:#0f172a; }

        .fm-tip { display:flex; align-items:flex-start; gap:0.625rem; padding:0.875rem 1rem; border-radius:10px; margin-bottom:1.5rem; background:linear-gradient(135deg,#eff6ff,#f0f7ff); border:1px solid #bfdbfe; }
        .fm-tip-ico { color:#3b82f6; flex-shrink:0; margin-top:1px; }
        .fm-tip-text { font-size:0.75rem; color:#1e40af; line-height:1.5; }

        .fm-actions { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1.25rem 0; margin-top:0.5rem; }
        .fm-btn-save { display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem; border-radius:12px; font-size:0.8125rem; font-weight:700; border:none; cursor:pointer; transition:all 0.25s; font-family:inherit; background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 6px 20px rgba(37,99,235,0.35); }
        .fm-btn-save:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(37,99,235,0.45); }
        .fm-btn-cancel { display:inline-flex; align-items:center; gap:0.375rem; padding:0.75rem 1.25rem; border-radius:12px; font-size:0.8125rem; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; }
        .fm-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }

        @media(max-width:640px) {
            .fm-grid-2 { grid-template-columns:1fr; }
            .fm-hdr-title { font-size:1.25rem; }
            .fm-actions { flex-direction:column-reverse; }
            .fm-btn-save, .fm-btn-cancel { width:100%; justify-content:center; }
            .fm-radio-group { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="fm-page">

            <a href="{{ route('mineral.sales.index') }}" class="fm-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Sales
            </a>

            <div class="fm-hdr">
                <div class="fm-hdr-ico">🚰</div>
                <div>
                    <div class="fm-hdr-title">Tambah Sales Baru</div>
                    <div class="fm-hdr-sub">Isi data lengkap sales dan kendaraan</div>
                </div>
            </div>

            <form method="POST" action="{{ route('mineral.sales.store') }}">
                @csrf

                <div class="fm-tip">
                    <svg class="fm-tip-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="fm-tip-text">Pastikan data sales diisi dengan lengkap dan benar. Field bertanda <strong>*</strong> wajib diisi.</div>
                </div>

                {{-- Section 1: Informasi Pribadi --}}
                <div class="fm-section">
                    <div class="fm-section-hdr blue">
                        <div class="fm-section-ico blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <div class="fm-section-title">Informasi Pribadi</div>
                            <div class="fm-section-desc">Data identitas sales</div>
                        </div>
                    </div>
                    <div class="fm-section-body">
                        <div class="fm-grid fm-grid-2">
                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Nama Lengkap <span class="fm-req">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap" class="fm-input">
                                @error('nama')<div class="fm-error">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    No HP <span class="fm-opt">(Opsional)</span>
                                </label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" class="fm-input mono">
                                @error('no_hp')<div class="fm-error">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Email <span class="fm-opt">(Opsional)</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="fm-input">
                                @error('email')<div class="fm-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="fm-full">
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Alamat <span class="fm-opt">(Opsional)</span>
                                </label>
                                <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap" class="fm-input">{{ old('alamat') }}</textarea>
                                @error('alamat')<div class="fm-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Informasi Kendaraan --}}
                <div class="fm-section">
                    <div class="fm-section-hdr green">
                        <div class="fm-section-ico green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                        <div>
                            <div class="fm-section-title">Informasi Kendaraan</div>
                            <div class="fm-section-desc">Data kendaraan operasional sales</div>
                        </div>
                    </div>
                    <div class="fm-section-body">
                        <div class="fm-grid fm-grid-2">
                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                    No Kendaraan (Plat) <span class="fm-opt">(Opsional)</span>
                                </label>
                                <input type="text" name="no_kendaraan" value="{{ old('no_kendaraan') }}" placeholder="Contoh: B 1234 ABC" class="fm-input mono" style="text-transform:uppercase;">
                                @error('no_kendaraan')<div class="fm-error">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    Jenis Kendaraan <span class="fm-opt">(Opsional)</span>
                                </label>
                                <input type="text" name="jenis_kendaraan" value="{{ old('jenis_kendaraan') }}" placeholder="Motor, Pickup, Truck" class="fm-input">
                                @error('jenis_kendaraan')<div class="fm-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Status --}}
                <div class="fm-section">
                    <div class="fm-section-hdr purple">
                        <div class="fm-section-ico purple">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div>
                            <div class="fm-section-title">Status</div>
                            <div class="fm-section-desc">Status keaktifan sales</div>
                        </div>
                    </div>
                    <div class="fm-section-body">
                        {{-- Regional Kerja --}}
                        <div style="margin-bottom:1.25rem;">
                            <label class="fm-lbl">
                                <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                Regional Kerja <span class="fm-opt">(Opsional)</span>
                            </label>
                            <select name="regional_id" class="fm-input" style="max-width:400px;">
                                <option value="">— Tanpa Regional —</option>
                                @foreach($regionals as $rg)
                                    <option value="{{ $rg->id }}" {{ old('regional_id') == $rg->id ? 'selected' : '' }}>{{ $rg->nama }} ({{ $rg->kode_regional }})</option>
                                @endforeach
                            </select>
                            <div class="fm-hint">Pilih regional untuk menentukan area kerja sales</div>
                            @error('regional_id')<div class="fm-error">{{ $message }}</div>@enderror
                        </div>

                        <label class="fm-lbl" style="margin-bottom:0.75rem;">Status Sales <span class="fm-req">*</span></label>
                        <div class="fm-radio-group">
                            <label class="fm-radio-card">
                                <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }} required>
                                <div class="fm-radio-face">
                                    <div class="fm-radio-dot ok">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </div>
                                    <div class="fm-radio-title">Aktif</div>
                                </div>
                            </label>
                            <label class="fm-radio-card">
                                <input type="radio" name="status" value="cuti" {{ old('status') == 'cuti' ? 'checked' : '' }}>
                                <div class="fm-radio-face">
                                    <div class="fm-radio-dot warn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    </div>
                                    <div class="fm-radio-title">Cuti</div>
                                </div>
                            </label>
                            <label class="fm-radio-card">
                                <input type="radio" name="status" value="nonaktif" {{ old('status') == 'nonaktif' ? 'checked' : '' }}>
                                <div class="fm-radio-face">
                                    <div class="fm-radio-dot off">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    </div>
                                    <div class="fm-radio-title">Nonaktif</div>
                                </div>
                            </label>
                        </div>
                        @error('status')<div class="fm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="fm-actions">
                    <a href="{{ route('mineral.sales.index') }}" class="fm-btn-cancel">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="fm-btn-save">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Data
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
