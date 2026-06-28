<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .msc-page { max-width:52rem; margin:0 auto; padding:0 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .msc-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.5rem; }
        .msc-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .msc-back:hover { color:#0891b2; }
        .msc-sep { color:#cbd5e1; font-size:0.8125rem; }
        .msc-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

        .msc-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.75rem; }
        .msc-hdr-ico { width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; background:linear-gradient(135deg,#06b6d4,#0891b2); box-shadow:0 8px 24px rgba(8,145,178,0.3); }
        .msc-hdr-title { font-size:1.375rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .msc-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        .msc-tip { display:flex; align-items:flex-start; gap:0.625rem; padding:0.875rem 1rem; border-radius:10px; margin-bottom:1.5rem; background:linear-gradient(135deg,#ecfeff,#f0fdfa); border:1px solid #99f6e4; }
        .msc-tip-ico { color:#0d9488; flex-shrink:0; margin-top:1px; }
        .msc-tip-txt { font-size:0.75rem; color:#115e59; line-height:1.5; }

        .msc-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .msc-card-hdr { display:flex; align-items:center; gap:0.75rem; padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; }
        .msc-card-ico { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .msc-card-ico svg { width:17px; height:17px; }
        .msc-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .msc-card-desc { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .msc-card-body { padding:1.5rem; }

        .msc-card.cyan .msc-card-hdr { background:linear-gradient(135deg,#ecfeff,#f0fdfa); }
        .msc-card.cyan .msc-card-ico { background:linear-gradient(135deg,#06b6d4,#0891b2); color:#fff; }
        .msc-card.green .msc-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .msc-card.green .msc-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .msc-card.purple .msc-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .msc-card.purple .msc-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        .msc-grid { display:grid; gap:1.25rem; }
        .msc-grid-2 { grid-template-columns:repeat(2,1fr); }
        .msc-full { grid-column:1/-1; }

        .msc-lbl { display:flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; margin-bottom:0.5rem; }
        .msc-lbl-ico { color:#94a3b8; flex-shrink:0; }
        .msc-req { color:#ef4444; font-weight:800; }
        .msc-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }

        .msc-inp { width:100%; padding:0.6875rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fcfcfd; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; box-sizing:border-box; }
        .msc-inp:focus { border-color:#06b6d4; background:#fff; box-shadow:0 0 0 3px rgba(6,182,212,0.12); }
        .msc-inp.is-invalid { border-color:#fca5a5; background:#fef2f2; }
        .msc-inp::placeholder { color:#cbd5e1; }
        .msc-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }

        textarea.msc-inp { resize:vertical; min-height:80px; line-height:1.5; }

        .msc-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; }
        .msc-err { font-size:0.75rem; font-weight:500; color:#ef4444; margin-top:0.375rem; }

        .msc-money { display:flex; align-items:stretch; border:1.5px solid #e2e8f0; border-radius:10px; overflow:hidden; transition:all 0.2s; background:#fcfcfd; }
        .msc-money:focus-within { border-color:#06b6d4; box-shadow:0 0 0 3px rgba(6,182,212,0.12); }
        .msc-money-pfx { display:flex; align-items:center; padding:0 0.875rem; background:#f8fafc; color:#64748b; font-weight:700; font-size:0.8125rem; border-right:1px solid #e2e8f0; white-space:nowrap; }
        .msc-money-inp { flex:1; padding:0.6875rem 0.875rem; border:none; background:transparent; font-size:0.875rem; font-weight:700; font-family:'JetBrains Mono',monospace; outline:none; min-width:0; }

        .msc-radios { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .msc-radio { position:relative; }
        .msc-radio input { position:absolute; opacity:0; pointer-events:none; }
        .msc-radio-face {
            display:flex; align-items:center; gap:0.625rem; padding:0.875rem 1rem; border-radius:12px;
            border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff;
        }
        .msc-radio-face:hover { border-color:#5eead4; background:#f4fdfa; }
        .msc-radio input:checked ~ .msc-radio-face { border-color:#14b8a6; background:linear-gradient(135deg,#ecfeff,#ccfbf1); box-shadow:0 2px 8px rgba(20,184,166,0.12); }
        .msc-radio-dot { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.875rem; }
        .msc-radio-dot.green { background:#ecfdf5; }
        .msc-radio-dot.amber { background:#fffbeb; }
        .msc-radio-dot.gray { background:#f1f5f9; }
        .msc-radio-lbl { font-size:0.8125rem; font-weight:600; color:#0f172a; }

        .msc-actions { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding-top:0.5rem; }
        .msc-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem; border-radius:12px; font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.25s; font-family:inherit; text-decoration:none; }
        .msc-btn-primary { border:none; background:linear-gradient(135deg,#06b6d4,#0891b2); color:#fff; box-shadow:0 6px 20px rgba(8,145,178,0.35); }
        .msc-btn-primary:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(8,145,178,0.45); }
        .msc-btn-cancel { border:1.5px solid #e2e8f0; background:#fff; color:#64748b; }
        .msc-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }

        @media(max-width:640px) {
            .msc-grid-2 { grid-template-columns:1fr; }
            .msc-hdr-title { font-size:1.125rem; }
            .msc-radios { grid-template-columns:1fr; }
            .msc-actions { flex-direction:column-reverse; }
            .msc-btn { width:100%; justify-content:center; }
        }
    </style>
    @endpush

    <div class="msc-page">

        <nav class="msc-nav">
            <a href="{{ route('mineral.sales.index') }}" class="msc-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Sales
            </a>
            <span class="msc-sep">/</span>
            <span class="msc-crumb">Tambah Baru</span>
        </nav>

        <div class="msc-hdr">
            <div class="msc-hdr-ico">
                <svg width="28" height="28" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
            </div>
            <div>
                <div class="msc-hdr-title">Tambah Sales Baru</div>
                <div class="msc-hdr-sub">Isi data lengkap sales dan kendaraan operasional</div>
            </div>
        </div>

        <form method="POST" action="{{ route('mineral.sales.store') }}">
            @csrf

            <div class="msc-tip">
                <svg class="msc-tip-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="msc-tip-txt">Pastikan data sales diisi dengan lengkap dan benar. Field bertanda <strong>*</strong> wajib diisi, sisanya opsional.</div>
            </div>

            {{-- Section 1: Informasi Pribadi --}}
            <div class="msc-card cyan">
                <div class="msc-card-hdr">
                    <div class="msc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <div class="msc-card-title">Informasi Pribadi</div>
                        <div class="msc-card-desc">Data identitas dan kontak sales</div>
                    </div>
                </div>
                <div class="msc-card-body">
                    <div class="msc-grid msc-grid-2">
                        <div>
                            <label class="msc-lbl">
                                <svg class="msc-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Nama Lengkap <span class="msc-req">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap" class="msc-inp @error('nama') is-invalid @enderror">
                            @error('nama')<div class="msc-err">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="msc-lbl">
                                <svg class="msc-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                No HP <span class="msc-opt">(Opsional)</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" class="msc-inp mono @error('no_hp') is-invalid @enderror">
                            @error('no_hp')<div class="msc-err">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="msc-lbl">
                                <svg class="msc-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Email <span class="msc-opt">(Opsional)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="msc-inp @error('email') is-invalid @enderror">
                            @error('email')<div class="msc-err">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="msc-lbl">
                                <svg class="msc-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                Target Harian <span class="msc-opt">(Opsional)</span>
                            </label>
                            <div class="msc-money">
                                <span class="msc-money-pfx">Rp</span>
                                <input type="text" inputmode="decimal" name="target_harian" value="{{ old('target_harian') }}" placeholder="0" class="msc-money-inp" data-currency>
                            </div>
                            @error('target_harian')<div class="msc-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="msc-full">
                            <label class="msc-lbl">
                                <svg class="msc-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Alamat <span class="msc-opt">(Opsional)</span>
                            </label>
                            <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap" class="msc-inp @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                            @error('alamat')<div class="msc-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Informasi Kendaraan --}}
            <div class="msc-card green">
                <div class="msc-card-hdr">
                    <div class="msc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div>
                        <div class="msc-card-title">Informasi Kendaraan</div>
                        <div class="msc-card-desc">Tugaskan kendaraan operasional sales</div>
                    </div>
                </div>
                <div class="msc-card-body">
                    <div>
                        <label class="msc-lbl">
                            <svg class="msc-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            Tugaskan Kendaraan <span class="msc-opt">(Opsional)</span>
                        </label>
                        <select name="vehicle_id" class="msc-inp @error('vehicle_id') is-invalid @enderror" style="max-width:400px;">
                            <option value="">-- Pilih dari Data Kendaraan --</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                                    {{ strtoupper($v->license_plate) }}@if($v->type) · {{ $v->type }}@endif
                                </option>
                            @endforeach
                        </select>
                        <div class="msc-hint">Data kendaraan diambil dari menu Operasional &rarr; Data Kendaraan</div>
                        @error('vehicle_id')<div class="msc-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Status & Regional --}}
            <div class="msc-card purple">
                <div class="msc-card-hdr">
                    <div class="msc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <div class="msc-card-title">Status & Regional</div>
                        <div class="msc-card-desc">Status keaktifan dan area kerja</div>
                    </div>
                </div>
                <div class="msc-card-body">
                    <div style="margin-bottom:1.25rem;">
                        <label class="msc-lbl">
                            <svg class="msc-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            Regional Kerja <span class="msc-opt">(Opsional)</span>
                        </label>
                        <select name="regional_id" class="msc-inp @error('regional_id') is-invalid @enderror" style="max-width:400px;">
                            <option value="">— Tanpa Regional —</option>
                            @foreach($regionals as $rg)
                                <option value="{{ $rg->id }}" {{ old('regional_id') == $rg->id ? 'selected' : '' }}>{{ $rg->nama }} ({{ $rg->kode_regional }})</option>
                            @endforeach
                        </select>
                        <div class="msc-hint">Pilih regional untuk menentukan area kerja sales</div>
                        @error('regional_id')<div class="msc-err">{{ $message }}</div>@enderror
                    </div>

                    <div style="margin-bottom:1.5rem; display:flex; align-items:center; gap:12px;">
                        <input type="checkbox" name="is_inti" id="cb-is-inti" value="1" {{ old('is_inti') ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#06b6d4; cursor:pointer;">
                        <div>
                            <label for="cb-is-inti" style="font-size:0.875rem; font-weight:700; color:#0f172a; cursor:pointer;">Jadikan sebagai Mobil Inti</label>
                            <div class="msc-hint" style="margin-top:0;">Centang jika sales ini bertugas membawa stok utama untuk didistribusikan ke sales sub (Mobil Sub).</div>
                        </div>
                    </div>

                    <label class="msc-lbl" style="margin-bottom:0.75rem;">Status Sales <span class="msc-req">*</span></label>
                    <div class="msc-radios">
                        <label class="msc-radio">
                            <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }} required>
                            <div class="msc-radio-face">
                                <div class="msc-radio-dot green">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <span class="msc-radio-lbl">Aktif</span>
                            </div>
                        </label>
                        <label class="msc-radio">
                            <input type="radio" name="status" value="cuti" {{ old('status') == 'cuti' ? 'checked' : '' }}>
                            <div class="msc-radio-face">
                                <div class="msc-radio-dot amber">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                </div>
                                <span class="msc-radio-lbl">Cuti</span>
                            </div>
                        </label>
                        <label class="msc-radio">
                            <input type="radio" name="status" value="nonaktif" {{ old('status') == 'nonaktif' ? 'checked' : '' }}>
                            <div class="msc-radio-face">
                                <div class="msc-radio-dot gray">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                </div>
                                <span class="msc-radio-lbl">Nonaktif</span>
                            </div>
                        </label>
                    </div>
                    @error('status')<div class="msc-err">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Section 4: Keterangan --}}
            <div class="msc-card" style="border-color:#e2e8f0;">
                <div class="msc-card-hdr" style="background:#f8fafc;">
                    <div class="msc-card-ico" style="background:#e2e8f0; color:#64748b;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                    </div>
                    <div>
                        <div class="msc-card-title">Keterangan</div>
                        <div class="msc-card-desc">Catatan tambahan (opsional)</div>
                    </div>
                </div>
                <div class="msc-card-body">
                    <textarea name="keterangan" rows="3" placeholder="Catatan tambahan tentang sales..." class="msc-inp @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="msc-err">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="msc-actions">
                <a href="{{ route('mineral.sales.index') }}" class="msc-btn msc-btn-cancel">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <button type="submit" class="msc-btn msc-btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Data
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
