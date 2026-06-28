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
        .fm-section-ico { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .fm-section-ico.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .fm-section-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .fm-section-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .fm-section-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .fm-section-desc { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .fm-section-body { padding:1.5rem; }

        .fm-grid { display:grid; gap:1.25rem; }
        .fm-grid-2 { grid-template-columns:repeat(2,1fr); }
        .fm-full { grid-column:1/-1; }

        .fm-lbl { display:flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; margin-bottom:0.5rem; }
        .fm-lbl-ico { color:#94a3b8; flex-shrink:0; }
        .fm-req { color:#ef4444; font-weight:800; }

        .fm-input { width:100%; padding:0.6875rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; }
        .fm-input:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.12); }
        .fm-input::placeholder { color:#cbd5e1; }
        .fm-input.green:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,0.12); }
        .fm-input.purple:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,0.12); }

        select.fm-input { cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; background-size:14px; padding-right:2.25rem; }

        textarea.fm-input { resize:none; min-height:80px; }

        .fm-input-wrap { position:relative; }
        .fm-input-prefix { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); font-size:0.8125rem; font-weight:600; color:#94a3b8; pointer-events:none; }
        .fm-input-suffix { position:absolute; right:0.875rem; top:50%; transform:translateY(-50%); font-size:0.75rem; font-weight:500; color:#94a3b8; pointer-events:none; }
        .fm-input.has-prefix { padding-left:2.75rem; }
        .fm-input.has-suffix { padding-right:3rem; }

        .fm-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; display:flex; align-items:center; gap:0.25rem; }
        .fm-error { display:flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:500; color:#ef4444; margin-top:0.375rem; }

        .fm-radio-group { display:flex; gap:0.75rem; }
        .fm-radio-card { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border-radius:12px; border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff; flex:1; }
        .fm-radio-card:hover { border-color:#cbd5e1; background:#f8fafc; }
        .fm-radio-card.selected { border-color:var(--sel-color); background:var(--sel-bg); }
        .fm-radio-card input[type="radio"] { display:none; }
        .fm-radio-dot { width:18px; height:18px; border-radius:50%; border:2px solid #cbd5e1; flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:all 0.2s; }
        .fm-radio-card.selected .fm-radio-dot { border-color:var(--sel-color); }
        .fm-radio-card.selected .fm-radio-dot::after { content:''; width:8px; height:8px; border-radius:50%; background:var(--sel-color); }
        .fm-radio-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .fm-radio-info { flex:1; min-width:0; }
        .fm-radio-title { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .fm-radio-desc { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        .fm-tip { display:flex; align-items:flex-start; gap:0.625rem; padding:0.875rem 1rem; border-radius:10px; margin-bottom:1.5rem; background:linear-gradient(135deg,#eff6ff,#f0f7ff); border:1px solid #bfdbfe; }
        .fm-tip-ico { color:#3b82f6; flex-shrink:0; margin-top:1px; }
        .fm-tip-text { font-size:0.75rem; color:#1e40af; line-height:1.5; }

        .fm-profit { display:flex; align-items:center; gap:0.5rem; padding:0.625rem 0.875rem; border-radius:10px; margin-top:0.625rem; font-size:0.8125rem; font-weight:600; transition:all 0.3s; }
        .fm-profit.positive { background:linear-gradient(135deg,#ecfdf5,#d1fae5); border:1px solid #a7f3d0; color:#065f46; }
        .fm-profit.negative { background:linear-gradient(135deg,#fef2f2,#fee2e2); border:1px solid #fecaca; color:#991b1b; }
        .fm-profit.neutral  { background:#f8fafc; border:1px solid #e2e8f0; color:#94a3b8; }
        .fm-profit-pct { font-size:0.7rem; font-weight:700; padding:0.125rem 0.375rem; border-radius:6px; margin-left:auto; }
        .fm-profit.positive .fm-profit-pct { background:rgba(5,150,105,0.15); color:#059669; }
        .fm-profit.negative .fm-profit-pct { background:rgba(239,68,68,0.15); color:#ef4444; }

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
            .fm-radio-group { flex-direction:column; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="fm-page">

            <a href="{{ route('mineral.produk.index') }}" class="fm-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Data Produk
            </a>

            <div class="fm-hdr">
                <div class="fm-hdr-ico">🚰</div>
                <div>
                    <div class="fm-hdr-title">Tambah Produk Mineral</div>
                    <div class="fm-hdr-sub">Input data produk Air Mineral baru untuk dijual</div>
                </div>
            </div>

            <form method="POST" action="{{ route('mineral.produk.store') }}" id="produkForm">
                @csrf

                <div class="fm-tip">
                    <svg class="fm-tip-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="fm-tip-text">Field bertanda <strong>*</strong> wajib diisi. Harga modal (HPP) bersifat opsional — isi jika ingin melacak margin keuntungan.</div>
                </div>

                {{-- Section 1: Informasi Dasar --}}
                <div class="fm-section">
                    <div class="fm-section-hdr">
                        <div class="fm-section-ico amber">📋</div>
                        <div>
                            <div class="fm-section-title">Informasi Dasar Produk</div>
                            <div class="fm-section-desc">Nama, jenis, dan satuan produk</div>
                        </div>
                    </div>
                    <div class="fm-section-body">
                        <div class="fm-grid fm-grid-2">
                            <div class="fm-full">
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    Nama Produk <span class="fm-req">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Aqua Galon 19L, Le Minerale 600ml" class="fm-input" autocomplete="off">
                                @error('nama')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                    Jenis Produk
                                </label>
                                <select name="jenis" class="fm-input">
                                    <option value="">Pilih jenis produk</option>
                                    @foreach($jenisList as $j)
                                        <option value="{{ $j->nama }}" {{ old('jenis') == $j->nama ? 'selected' : '' }}>{{ $j->nama }}</option>
                                    @endforeach
                                </select>
                                @error('jenis')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    Satuan <span class="fm-req">*</span>
                                </label>
                                <select name="satuan" required class="fm-input">
                                    <option value="">Pilih satuan</option>
                                    @foreach($satuanList as $s)
                                        <option value="{{ $s->nama }}" {{ old('satuan') == $s->nama ? 'selected' : '' }}>{{ $s->nama }}@if($s->singkatan) ({{ $s->singkatan }})@endif</option>
                                    @endforeach
                                </select>
                                @error('satuan')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Harga & Stok --}}
                <div class="fm-section">
                    <div class="fm-section-hdr">
                        <div class="fm-section-ico green">💰</div>
                        <div>
                            <div class="fm-section-title">Harga & Stok</div>
                            <div class="fm-section-desc">Pengaturan harga jual, modal, dan stok gudang</div>
                        </div>
                    </div>
                    <div class="fm-section-body">
                        <div class="fm-grid fm-grid-2">
                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    Harga Modal (HPP)
                                </label>
                                <div class="fm-input-wrap">
                                    <span class="fm-input-prefix">Rp</span>
                                    <input type="text" id="hargaModal" inputmode="numeric" value="{{ old('harga_modal') }}" placeholder="0" class="fm-input has-prefix green">
                                    <input type="hidden" name="harga_modal" id="hargaModalHidden">
                                </div>
                                <div class="fm-hint">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Harga beli dari supplier (opsional)
                                </div>
                                @error('harga_modal')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Harga Jual <span class="fm-req">*</span>
                                </label>
                                <div class="fm-input-wrap">
                                    <span class="fm-input-prefix">Rp</span>
                                    <input type="text" id="hargaJual" inputmode="numeric" value="{{ old('harga_jual') }}" required placeholder="0" class="fm-input has-prefix green">
                                    <input type="hidden" name="harga_jual" id="hargaJualHidden">
                                </div>
                                <div class="fm-hint">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Harga jual ke pelanggan
                                </div>
                                @error('harga_jual')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div class="fm-full" id="profitPreview" style="display:none;">
                                <div class="fm-profit neutral" id="profitBox">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="profitIco"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    <span id="profitText">Margin: Rp 0</span>
                                    <span class="fm-profit-pct" id="profitPct">0%</span>
                                </div>
                            </div>

                            <div>
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Batas Stok Minimum
                                </label>
                                <div class="fm-input-wrap">
                                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 10) }}" min="0" placeholder="10" class="fm-input has-suffix green">
                                    <span class="fm-input-suffix">unit</span>
                                </div>
                                <div class="fm-hint">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Peringatan muncul jika stok ≤ angka ini
                                </div>
                                @error('stok_minimum')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Status & Keterangan --}}
                <div class="fm-section">
                    <div class="fm-section-hdr">
                        <div class="fm-section-ico purple">⚙️</div>
                        <div>
                            <div class="fm-section-title">Status & Keterangan</div>
                            <div class="fm-section-desc">Status aktif produk dan catatan tambahan</div>
                        </div>
                    </div>
                    <div class="fm-section-body">
                        <div class="fm-grid fm-grid-2">
                            <div class="fm-full">
                                <label class="fm-lbl">Status <span class="fm-req">*</span></label>
                                <div class="fm-radio-group" id="statusGroup">
                                    <label class="fm-radio-card {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}" style="--sel-color:#059669;--sel-bg:#ecfdf5;" onclick="selectRadio(this)">
                                        <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }} required>
                                        <div class="fm-radio-dot"></div>
                                        <div class="fm-radio-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">🟢</div>
                                        <div class="fm-radio-info">
                                            <div class="fm-radio-title">Aktif</div>
                                            <div class="fm-radio-desc">Produk bisa dijual</div>
                                        </div>
                                    </label>
                                    <label class="fm-radio-card {{ old('status') == 'nonaktif' ? 'selected' : '' }}" style="--sel-color:#64748b;--sel-bg:#f8fafc;" onclick="selectRadio(this)">
                                        <input type="radio" name="status" value="nonaktif" {{ old('status') == 'nonaktif' ? 'checked' : '' }}>
                                        <div class="fm-radio-dot"></div>
                                        <div class="fm-radio-ico" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);">⚫</div>
                                        <div class="fm-radio-info">
                                            <div class="fm-radio-title">Nonaktif</div>
                                            <div class="fm-radio-desc">Sementara tidak dijual</div>
                                        </div>
                                    </label>
                                </div>
                                @error('status')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div class="fm-full">
                                <label class="fm-lbl">
                                    <svg class="fm-lbl-ico" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Keterangan / Catatan
                                </label>
                                <textarea name="keterangan" rows="3" placeholder="Catatan tambahan tentang produk (opsional)" class="fm-input purple">{{ old('keterangan') }}</textarea>
                                @error('keterangan')<div class="fm-error"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fm-actions">
                    <a href="{{ route('mineral.produk.index') }}" class="fm-btn-cancel">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="fm-btn-save">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Produk
                    </button>
                </div>
            </form>

        </div>
    </div>

    @push('scripts')
    <script>
    // Radio card selection
    function selectRadio(card) {
        const container = card.closest('.fm-radio-group');
        container.querySelectorAll('.fm-radio-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
    }

    // Auto-format currency input with thousand separators
    function formatCurrency(input, hiddenInput) {
        input.addEventListener('input', function() {
            // Strip non-numeric characters
            let raw = this.value.replace(/[^\d]/g, '');
            // Update hidden input with raw number
            hiddenInput.value = raw;
            // Format with thousand separators
            if (raw.length > 0) {
                this.value = parseInt(raw).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
            updateProfit();
        });
        // Format initial value on load
        if (input.value && !isNaN(input.value.replace(/\./g,'').replace(/,/g,''))) {
            const raw = input.value.replace(/[^\d]/g, '');
            if (raw) {
                hiddenInput.value = raw;
                input.value = parseInt(raw).toLocaleString('id-ID');
            }
        }
    }

    const hargaModalInput  = document.getElementById('hargaModal');
    const hargaModalHidden = document.getElementById('hargaModalHidden');
    const hargaJualInput   = document.getElementById('hargaJual');
    const hargaJualHidden  = document.getElementById('hargaJualHidden');

    formatCurrency(hargaModalInput, hargaModalHidden);
    formatCurrency(hargaJualInput,  hargaJualHidden);

    // Live profit margin preview
    function updateProfit() {
        const modal = parseInt(hargaModalHidden.value) || 0;
        const jual  = parseInt(hargaJualHidden.value)  || 0;
        const preview = document.getElementById('profitPreview');
        const box     = document.getElementById('profitBox');
        const text    = document.getElementById('profitText');
        const pct     = document.getElementById('profitPct');

        if (jual > 0 && modal > 0) {
            preview.style.display = 'block';
            const profit = jual - modal;
            const margin = ((profit / jual) * 100).toFixed(1);
            const formatted = 'Rp ' + Math.abs(profit).toLocaleString('id-ID');

            if (profit > 0) {
                box.className = 'fm-profit positive';
                text.textContent = 'Keuntungan: ' + formatted + ' per unit';
                pct.textContent  = margin + '%';
            } else if (profit < 0) {
                box.className = 'fm-profit negative';
                text.textContent = 'Rugi: ' + formatted + ' per unit';
                pct.textContent  = margin + '%';
            } else {
                box.className = 'fm-profit neutral';
                text.textContent = 'Tidak ada keuntungan (jual = modal)';
                pct.textContent  = '0%';
            }
        } else {
            preview.style.display = 'none';
        }
    }
    updateProfit();

    // Unsaved changes warning
    let formDirty = false;
    document.getElementById('produkForm').addEventListener('input', () => { formDirty = true; });
    document.getElementById('produkForm').addEventListener('change', () => { formDirty = true; });
    document.getElementById('produkForm').addEventListener('submit', () => { formDirty = false; });
    window.addEventListener('beforeunload', function(e) {
        if (formDirty) { e.preventDefault(); e.returnValue = ''; }
    });
    </script>
    @endpush
</x-app-layout>
