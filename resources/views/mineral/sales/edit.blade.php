<x-app-layout>
    <x-slot name="header">Edit Sales Mineral</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .mne-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* ── BREADCRUMB ── */
        .mne-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
        .mne-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .mne-back:hover { color:#2563eb; }
        .mne-sep { color:#cbd5e1; font-size:0.8125rem; }
        .mne-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

        /* ── HEADER CARD ── */
        .mne-top { background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:1.375rem 1.5rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
        .mne-top-l { display:flex; align-items:center; gap:0.875rem; }
        .mne-avatar { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.125rem; font-weight:800; color:#fff; flex-shrink:0; background:linear-gradient(135deg,#3b82f6,#2563eb); box-shadow:0 4px 12px rgba(37,99,235,0.25); }
        .mne-top-name { font-size:1.0625rem; font-weight:800; color:#0f172a; letter-spacing:-0.01em; }
        .mne-top-meta { display:flex; align-items:center; gap:6px; margin-top:1px; }
        .mne-code { font-family:'JetBrains Mono',monospace; font-size:0.6875rem; font-weight:600; color:#2563eb; background:#eff6ff; padding:2px 7px; border-radius:5px; border:1px solid #bfdbfe; }

        /* ── FORM CARD ── */
        .mne-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .mne-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .mne-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .mne-card-ico svg { width:17px; height:17px; }
        .mne-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .mne-card-body { padding:1.375rem; }

        .mne-card.blue .mne-card-hdr { background:linear-gradient(135deg,#eff6ff,#f0f7ff); }
        .mne-card.blue .mne-card-ico { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .mne-card.green .mne-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .mne-card.green .mne-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .mne-card.purple .mne-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .mne-card.purple .mne-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        /* ── FORM FIELDS ── */
        .mne-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
        .mne-full { grid-column:1 / -1; }
        .mne-fg { display:flex; flex-direction:column; gap:0.375rem; }
        .mne-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
        .mne-lbl svg { width:13px; height:13px; color:#94a3b8; }
        .mne-req { color:#ef4444; }
        .mne-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
        .mne-inp, .mne-txt {
            width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
            background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
            transition:all 0.2s; outline:none;
        }
        .mne-inp:focus, .mne-txt:focus { border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .mne-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
        .mne-txt { resize:vertical; min-height:80px; line-height:1.5; }
        .mne-inp::placeholder, .mne-txt::placeholder { color:#cbd5e1; }
        .mne-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
        .mne-inp.is-invalid, .mne-txt.is-invalid { border-color:#fecaca; background:#fef2f2; }

        /* ── RADIO CARDS ── */
        .mne-radios { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
        .mne-radio { position:relative; }
        .mne-radio input { position:absolute; opacity:0; pointer-events:none; }
        .mne-radio-card {
            display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px;
            border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff;
        }
        .mne-radio-card:hover { border-color:#93c5fd; background:#f8faff; }
        .mne-radio input:checked ~ .mne-radio-card { border-color:#3b82f6; background:linear-gradient(135deg,#eff6ff,#dbeafe); box-shadow:0 2px 8px rgba(59,130,246,0.12); }
        .mne-radio-dot { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.875rem; }
        .mne-radio-dot.ok { background:#ecfdf5; }
        .mne-radio-dot.off { background:#f1f5f9; }
        .mne-radio-text { font-size:0.8125rem; font-weight:600; color:#0f172a; }
        .mne-radio-sub { font-size:0.6875rem; color:#94a3b8; font-weight:500; }

        /* ── ACTIONS ── */
        .mne-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
        .mne-btn {
            display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
            border:1px solid transparent; text-decoration:none; font-family:inherit;
        }
        .mne-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
        .mne-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
        .mne-btn-primary {
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
            box-shadow:0 4px 14px rgba(37,99,235,0.3);
        }
        .mne-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(37,99,235,0.4); }

        @media(max-width:640px) {
            .mne-grid2 { grid-template-columns:1fr; }
            .mne-radios { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="mne-page">

        {{-- ─── BREADCRUMB ─── --}}
        <nav class="mne-nav">
            <a href="{{ route('mineral.sales.index') }}" class="mne-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Sales
            </a>
            <span class="mne-sep">/</span>
            <span class="mne-crumb">Edit Data</span>
        </nav>

        {{-- ─── TOP INFO ─── --}}
        <div class="mne-top">
            <div class="mne-top-l">
                <div class="mne-avatar">{{ strtoupper(substr($sales->nama, 0, 1)) }}</div>
                <div>
                    <div class="mne-top-name">{{ $sales->nama }}</div>
                    <div class="mne-top-meta">
                        <span class="mne-code">{{ $sales->kode_sales }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('mineral.sales.update', $sales->id) }}">
            @csrf
            @method('PUT')

            {{-- ─── CARD 1: Informasi Pribadi (Blue) ─── --}}
            <div class="mne-card blue">
                <div class="mne-card-hdr">
                    <div class="mne-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="mne-card-title">Informasi Pribadi</div>
                </div>
                <div class="mne-card-body">
                    <div class="mne-grid2">
                        <div class="mne-fg">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Nama Lengkap <span class="mne-req">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama', $sales->nama) }}" required
                                class="mne-inp @error('nama') is-invalid @enderror" placeholder="Nama lengkap sales">
                            @error('nama')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mne-fg">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                No HP <span class="mne-opt">(Opsional)</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $sales->no_hp) }}"
                                class="mne-inp mono @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                            @error('no_hp')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mne-fg">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                Email <span class="mne-opt">(Opsional)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $sales->email) }}"
                                class="mne-inp @error('email') is-invalid @enderror" placeholder="email@example.com">
                            @error('email')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mne-fg">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                Target Harian <span class="mne-opt">(Opsional)</span>
                            </label>
                            <input type="number" name="target_harian" value="{{ old('target_harian', $sales->target_harian) }}"
                                class="mne-inp mono @error('target_harian') is-invalid @enderror" placeholder="0" min="0" step="0.01">
                            @error('target_harian')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mne-fg mne-full">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Alamat <span class="mne-opt">(Opsional)</span>
                            </label>
                            <textarea name="alamat" rows="2" class="mne-txt @error('alamat') is-invalid @enderror" placeholder="Alamat lengkap sales...">{{ old('alamat', $sales->alamat) }}</textarea>
                            @error('alamat')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── CARD 2: Informasi Kendaraan (Green) ─── --}}
            <div class="mne-card green">
                <div class="mne-card-hdr">
                    <div class="mne-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div class="mne-card-title">Informasi Kendaraan</div>
                </div>
                <div class="mne-card-body">
                    <div class="mne-grid2">
                        <div class="mne-fg">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                No Kendaraan (Plat) <span class="mne-opt">(Opsional)</span>
                            </label>
                            <input type="text" name="no_kendaraan" value="{{ old('no_kendaraan', $sales->no_kendaraan) }}"
                                class="mne-inp mono @error('no_kendaraan') is-invalid @enderror"
                                placeholder="B 1234 ABC" style="text-transform:uppercase;">
                            @error('no_kendaraan')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mne-fg">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 12l-4-4-4 4"/><path d="M12 16V8"/></svg>
                                Jenis Kendaraan <span class="mne-opt">(Opsional)</span>
                            </label>
                            <input type="text" name="jenis_kendaraan" value="{{ old('jenis_kendaraan', $sales->jenis_kendaraan) }}"
                                class="mne-inp @error('jenis_kendaraan') is-invalid @enderror"
                                placeholder="Motor, Pickup, Truck">
                            @error('jenis_kendaraan')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── CARD 3: Status & Keterangan (Purple) ─── --}}
            <div class="mne-card purple">
                <div class="mne-card-hdr">
                    <div class="mne-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="mne-card-title">Status & Keterangan</div>
                </div>
                <div class="mne-card-body">
                    <div style="display:flex;flex-direction:column;gap:1.125rem;">
                        <div class="mne-fg">
                            <label class="mne-lbl" style="margin-bottom:0.5rem;">Status Sales <span class="mne-req">*</span></label>
                            <div class="mne-radios">
                                <label class="mne-radio">
                                    <input type="radio" name="status" value="aktif" {{ old('status', $sales->status) === 'aktif' ? 'checked' : '' }} required>
                                    <div class="mne-radio-card">
                                        <div class="mne-radio-dot ok">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                        <div>
                                            <div class="mne-radio-text">Aktif</div>
                                            <div class="mne-radio-sub">Sales beroperasi normal</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="mne-radio">
                                    <input type="radio" name="status" value="nonaktif" {{ old('status', $sales->status) === 'nonaktif' ? 'checked' : '' }}>
                                    <div class="mne-radio-card">
                                        <div class="mne-radio-dot off">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        </div>
                                        <div>
                                            <div class="mne-radio-text">Nonaktif</div>
                                            <div class="mne-radio-sub">Sales tidak beroperasi</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('status')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mne-fg">
                            <label class="mne-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                                Keterangan <span class="mne-opt">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" rows="2" class="mne-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan...">{{ old('keterangan', $sales->keterangan) }}</textarea>
                            @error('keterangan')<div class="mne-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ACTIONS ─── --}}
            <div class="mne-actions">
                <a href="{{ route('mineral.sales.index') }}" class="mne-btn mne-btn-ghost">Batal</a>
                <button type="submit" class="mne-btn mne-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
