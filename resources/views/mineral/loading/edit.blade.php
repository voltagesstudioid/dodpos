<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .fm-page { max-width:42rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .fm-nav { display:flex; align-items:center; gap:10px; margin-bottom:1.75rem; }
        .fm-back-btn {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            background:#fff; border:1.5px solid #e2e8f0; color:#64748b; text-decoration:none; transition:all 0.2s; flex-shrink:0;
        }
        .fm-back-btn:hover { background:#f8fafc; border-color:#cbd5e1; color:#2563eb; transform:translateX(-2px); }
        .fm-nav-text { font-size:0.8125rem; font-weight:600; color:#94a3b8; }
        .fm-nav-sep { color:#cbd5e1; }
        .fm-nav-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

        .fm-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:2rem; }
        .fm-hdr-ico {
            width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#d97706);
            box-shadow:0 8px 24px rgba(217,119,6,0.3);
        }
        .fm-hdr-ico svg { width:28px; height:28px; color:#fff; }
        .fm-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .fm-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        .fm-tip {
            display:flex; align-items:center; gap:0.625rem;
            padding:0.75rem 1rem; border-radius:12px; margin-bottom:1.5rem;
            background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1px solid #fde68a;
        }
        .fm-tip-ico {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; flex-shrink:0;
        }
        .fm-tip-ico svg { width:16px; height:16px; }
        .fm-tip-text { font-size:0.75rem; color:#92400e; line-height:1.5; }

        .fm-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden;
            box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; transition:box-shadow 0.3s;
        }
        .fm-card:hover { box-shadow:0 6px 24px rgba(0,0,0,0.07); }
        .fm-card-hdr { padding:1rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .fm-card-hdr.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .fm-card-hdr.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .fm-card-hdr.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .fm-card-ico {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .fm-card-ico svg { width:18px; height:18px; }
        .fm-card-ico.blue { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .fm-card-ico.green { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .fm-card-ico.purple { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }
        .fm-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .fm-card-desc { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .fm-card-body { padding:1.5rem; }

        .fm-grid { display:grid; gap:1.25rem; }
        .fm-grid-2 { grid-template-columns:1fr 1fr; }
        .fm-grid-3 { grid-template-columns:1fr 1fr 1fr; }
        .fm-full { grid-column:1 / -1; }

        .fm-lbl {
            display:flex; align-items:center; gap:0.375rem;
            font-size:0.6875rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.06em; color:#64748b; margin-bottom:0.5rem;
        }
        .fm-lbl-ico { color:#94a3b8; flex-shrink:0; }
        .fm-req { color:#ef4444; font-weight:800; }

        .fm-input {
            width:100%; padding:0.6875rem 0.875rem; border-radius:11px; border:1.5px solid #e2e8f0;
            background:#f8fafc; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit;
        }
        .fm-input:focus { border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        .fm-input::placeholder { color:#cbd5e1; }
        .fm-input.green:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.1); }

        select.fm-input {
            cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.75rem center; background-size:14px;
            padding-right:2.25rem;
        }
        textarea.fm-input { resize:none; min-height:80px; }

        .fm-input-wrap { position:relative; }
        .fm-input-suffix {
            position:absolute; right:0.875rem; top:50%; transform:translateY(-50%);
            font-size:0.75rem; font-weight:600; color:#94a3b8; pointer-events:none;
            background:#f1f5f9; padding:0.2rem 0.5rem; border-radius:6px;
        }
        .fm-input.has-suffix { padding-right:4rem; }

        .fm-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; display:flex; align-items:center; gap:0.25rem; }
        .fm-error { display:flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:500; color:#ef4444; margin-top:0.375rem; }

        .fm-stock {
            display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem;
            border-radius:10px; margin-top:0.5rem; font-size:0.75rem; font-weight:600; transition:all 0.2s;
        }
        .fm-stock.ok { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
        .fm-stock.warn { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
        .fm-stock.danger { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
        .fm-stock.hidden { display:none; }
        .fm-stock svg { width:14px; height:14px; flex-shrink:0; }

        /* Status radio cards */
        .fm-radio-group { display:flex; gap:0.625rem; }
        .fm-radio-card {
            display:flex; align-items:center; gap:0.625rem;
            padding:0.625rem 0.875rem; border-radius:12px; border:1.5px solid #e2e8f0;
            cursor:pointer; transition:all 0.2s; background:#fff; flex:1;
        }
        .fm-radio-card:hover { border-color:#cbd5e1; background:#f8fafc; }
        .fm-radio-card.selected { border-color:var(--c); background:var(--bg); box-shadow:0 0 0 3px var(--ring, transparent); }
        .fm-radio-card input[type="radio"] { display:none; }
        .fm-radio-check {
            width:20px; height:20px; border-radius:6px; border:2px solid #cbd5e1;
            flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:all 0.2s;
        }
        .fm-radio-check svg { width:11px; height:11px; color:#fff; opacity:0; transition:opacity 0.2s; }
        .fm-radio-card.selected .fm-radio-check { border-color:var(--c); background:var(--c); }
        .fm-radio-card.selected .fm-radio-check svg { opacity:1; }
        .fm-radio-title { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .fm-radio-desc { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        .fm-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding:1rem 0; margin-top:0.5rem; }
        .fm-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.75rem 1.5rem; border-radius:12px; font-size:0.8125rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.25s; font-family:inherit; text-decoration:none;
        }
        .fm-btn svg { width:16px; height:16px; }
        .fm-btn-cancel { background:#fff; border:1.5px solid #e2e8f0; color:#64748b; }
        .fm-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }
        .fm-btn-save {
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 6px 20px rgba(217,119,6,0.35);
        }
        .fm-btn-save:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(217,119,6,0.45); }

        @media(max-width:640px) {
            .fm-grid-2 { grid-template-columns:1fr; }
            .fm-grid-3 { grid-template-columns:1fr; }
            .fm-hdr-title { font-size:1.25rem; }
            .fm-actions { flex-direction:column-reverse; }
            .fm-btn { width:100%; justify-content:center; }
            .fm-radio-group { flex-direction:column; }
        }
    </style>
    @endpush

    <div class="fm-page">

        {{-- Breadcrumb nav --}}
        <nav class="fm-nav">
            <a href="{{ route('mineral.loading.index') }}" class="fm-back-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <span class="fm-nav-text">Loading Harian</span>
            <span class="fm-nav-sep">/</span>
            <span class="fm-nav-crumb">Edit Loading</span>
        </nav>

        {{-- Header --}}
        <div class="fm-hdr">
            <div class="fm-hdr-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <div>
                <div class="fm-hdr-title">Edit Loading</div>
                <div class="fm-hdr-sub">Ubah data distribusi mineral sales — {{ $loading->tanggal->format('d M Y') }}</div>
            </div>
        </div>

        {{-- Tip --}}
        <div class="fm-tip">
            <div class="fm-tip-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <div class="fm-tip-text">Perubahan jumlah loading akan menyesuaikan stok gudang secara otomatis. Field bertanda <strong>*</strong> wajib diisi.</div>
        </div>

        <form method="POST" action="{{ route('mineral.loading.update', $loading->id) }}">
            @csrf
            @method('PUT')

            {{-- Section 1: Informasi Loading --}}
            <div class="fm-card">
                <div class="fm-card-hdr blue">
                    <div class="fm-card-ico blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="fm-card-title">Informasi Loading</div>
                        <div class="fm-card-desc">Tanggal, sales, dan produk yang didistribusikan</div>
                    </div>
                </div>
                <div class="fm-card-body">
                    <div class="fm-grid fm-grid-2">
                        {{-- Tanggal --}}
                        <div>
                            <label class="fm-lbl">
                                <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal <span class="fm-req">*</span>
                            </label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $loading->tanggal->format('Y-m-d')) }}" required class="fm-input">
                            @error('tanggal')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        {{-- Sales --}}
                        <div>
                            <label class="fm-lbl">
                                <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Sales <span class="fm-req">*</span>
                            </label>
                            <select name="sales_id" required class="fm-input">
                                <option value="">Pilih Sales</option>
                                @foreach($sales as $s)
                                    <option value="{{ $s->id }}" {{ old('sales_id', $loading->sales_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}{{ $s->no_kendaraan ? ' (' . $s->no_kendaraan . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_id')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        {{-- Produk --}}
                        <div class="fm-full">
                            <label class="fm-lbl">
                                <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                Produk <span class="fm-req">*</span>
                            </label>
                            <select name="produk_id" id="produkSelect" required class="fm-input">
                                <option value="" data-stok="0" data-satuan="">Pilih Produk</option>
                                @foreach($produks as $p)
                                    <option value="{{ $p->id }}" data-stok="{{ $p->stok_gudang }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id', $loading->produk_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} — Stok: {{ number_format($p->stok_gudang) }} {{ $p->satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('produk_id')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Jumlah & Keterangan --}}
            <div class="fm-card">
                <div class="fm-card-hdr green">
                    <div class="fm-card-ico green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div>
                        <div class="fm-card-title">Jumlah & Keterangan</div>
                        <div class="fm-card-desc">Kuantitas loading dan catatan tambahan</div>
                    </div>
                </div>
                <div class="fm-card-body">
                    <div class="fm-grid fm-grid-2">
                        {{-- Jumlah Loading --}}
                        <div>
                            <label class="fm-lbl">
                                <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                Jumlah Loading <span class="fm-req">*</span>
                            </label>
                            <div class="fm-input-wrap">
                                <input type="number" name="jumlah_loading" id="jumlahInput" value="{{ old('jumlah_loading', $loading->jumlah_loading) }}" required min="0.01" step="any" class="fm-input has-suffix green">
                                <span class="fm-input-suffix" id="unitSuffix">—</span>
                            </div>
                            <div class="fm-stock hidden" id="stockIndicator">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span id="stockText"></span>
                            </div>
                            @error('jumlah_loading')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="fm-full">
                            <label class="fm-lbl">
                                <svg class="fm-lbl-ico" width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Keterangan
                            </label>
                            <textarea name="keterangan" rows="3" placeholder="Catatan tambahan (opsional)" class="fm-input green">{{ old('keterangan', $loading->keterangan) }}</textarea>
                            @error('keterangan')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Status --}}
            <div class="fm-card">
                <div class="fm-card-hdr purple">
                    <div class="fm-card-ico purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <div class="fm-card-title">Status Loading</div>
                        <div class="fm-card-desc">Tahapan distribusi barang</div>
                    </div>
                </div>
                <div class="fm-card-body">
                    <div class="fm-radio-group" id="statusGroup">
                        <label class="fm-radio-card {{ old('status', $loading->status) == 'loading' ? 'selected' : '' }}" style="--c:#2563eb;--bg:#eff6ff;--ring:rgba(37,99,235,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="status" value="loading" {{ old('status', $loading->status) == 'loading' ? 'checked' : '' }}>
                            <div class="fm-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div>
                                <div class="fm-radio-title">Loading</div>
                                <div class="fm-radio-desc">Baru dimuat</div>
                            </div>
                        </label>
                        <label class="fm-radio-card {{ old('status', $loading->status) == 'proses' ? 'selected' : '' }}" style="--c:#d97706;--bg:#fffbeb;--ring:rgba(217,119,6,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="status" value="proses" {{ old('status', $loading->status) == 'proses' ? 'checked' : '' }}>
                            <div class="fm-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div>
                                <div class="fm-radio-title">Proses</div>
                                <div class="fm-radio-desc">Sedang dijual</div>
                            </div>
                        </label>
                        <label class="fm-radio-card {{ old('status', $loading->status) == 'selesai' ? 'selected' : '' }}" style="--c:#059669;--bg:#ecfdf5;--ring:rgba(5,150,105,0.12);" onclick="selRadio(this)">
                            <input type="radio" name="status" value="selesai" {{ old('status', $loading->status) == 'selesai' ? 'checked' : '' }}>
                            <div class="fm-radio-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                            <div>
                                <div class="fm-radio-title">Selesai</div>
                                <div class="fm-radio-desc">Telah selesai</div>
                            </div>
                        </label>
                    </div>
                    @error('status')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="fm-actions">
                <a href="{{ route('mineral.loading.index') }}" class="fm-btn fm-btn-cancel">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    Batal
                </a>
                <button type="submit" class="fm-btn fm-btn-save">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Loading
                </button>
            </div>
        </form>

    </div>

    @push('scripts')
    <script>
    var OLD_JUMLAH = {{ (float)$loading->jumlah_loading }};

    function selRadio(card) {
        var group = card.closest('.fm-radio-group');
        group.querySelectorAll('.fm-radio-card').forEach(function(c) { c.classList.remove('selected'); });
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
    }

    (function() {
        var selProduk = document.getElementById('produkSelect');
        var inpJumlah = document.getElementById('jumlahInput');
        var unitSuffix = document.getElementById('unitSuffix');
        var stockInd = document.getElementById('stockIndicator');
        var stockText = document.getElementById('stockText');

        function fmt(n) {
            return n.toLocaleString('id-ID', {maximumFractionDigits: 2});
        }

        function updateUnit() {
            var opt = selProduk.options[selProduk.selectedIndex];
            var satuan = opt.getAttribute('data-satuan') || '';
            var stok = parseFloat(opt.getAttribute('data-stok')) || 0;

            // Add back old loading amount for same-product edit
            var effectiveStok = stok + OLD_JUMLAH;

            unitSuffix.textContent = satuan || '—';

            if (satuan) {
                stockInd.classList.remove('hidden');
                if (effectiveStok > 0) {
                    stockInd.className = 'fm-stock ok';
                    stockText.textContent = 'Stok tersedia (setelah restore): ' + fmt(effectiveStok) + ' ' + satuan;
                } else {
                    stockInd.className = 'fm-stock danger';
                    stockText.textContent = 'Stok tidak mencukupi!';
                }
            } else {
                stockInd.classList.add('hidden');
            }

            checkStock();
        }

        function checkStock() {
            var opt = selProduk.options[selProduk.selectedIndex];
            if (!opt || !opt.value) return;

            var stok = parseFloat(opt.getAttribute('data-stok')) || 0;
            var satuan = opt.getAttribute('data-satuan') || '';
            var jumlah = parseFloat(inpJumlah.value) || 0;
            var effectiveStok = stok + OLD_JUMLAH;

            if (jumlah > 0 && effectiveStok > 0) {
                stockInd.classList.remove('hidden');
                if (jumlah > effectiveStok) {
                    stockInd.className = 'fm-stock danger';
                    stockText.textContent = 'Melebihi stok! Tersedia: ' + fmt(effectiveStok) + ' ' + satuan + ', diinput: ' + fmt(jumlah) + ' ' + satuan;
                } else {
                    stockInd.className = 'fm-stock ok';
                    stockText.textContent = 'Stok tersedia (setelah restore): ' + fmt(effectiveStok) + ' ' + satuan + ' (sisa: ' + fmt(effectiveStok - jumlah) + ')';
                }
            }
        }

        selProduk.addEventListener('change', updateUnit);
        inpJumlah.addEventListener('input', checkStock);
        updateUnit();
    })();
    </script>
    @endpush
</x-app-layout>
