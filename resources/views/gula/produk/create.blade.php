@extends('layouts.app', ['title' => 'Tambah Produk Gula'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .pc-wrap { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 780px; margin: 0 auto; padding: 1.25rem; }
    .pc-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .pc-header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(217,119,6,0.25); flex-shrink: 0; }
    .pc-header-icon svg { width: 26px; height: 26px; stroke: #fff; }
    .pc-header h1 { font-size: 1.35rem; font-weight: 800; color: #78350f; margin: 0; }
    .pc-header p { font-size: 0.8rem; color: #b45309; margin: 0; font-weight: 600; }
    .pc-back { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; color: #b45309; text-decoration: none; font-weight: 700; margin-bottom: 1rem; }
    .pc-back:hover { color: #92400e; text-decoration: underline; }

    .pc-card { background: #fff; border: 1px solid #fde68a; border-radius: 16px; overflow: hidden; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(120,53,15,0.04); }
    .pc-card-head { padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.6rem; }
    .pc-card-head.amber { background: linear-gradient(135deg, #fffbeb, #fef3c7); border-bottom: 1px solid #fde68a; }
    .pc-card-head.green { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-bottom: 1px solid #bbf7d0; }
    .pc-card-head.purple { background: linear-gradient(135deg, #faf5ff, #f3e8ff); border-bottom: 1px solid #e9d5ff; }
    .pc-card-head-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pc-card-head-icon svg { width: 18px; height: 18px; }
    .pc-card-head.amber .pc-card-head-icon { background: #fbbf24; }
    .pc-card-head.amber .pc-card-head-icon svg { stroke: #78350f; }
    .pc-card-head.green .pc-card-head-icon { background: #4ade80; }
    .pc-card-head.green .pc-card-head-icon svg { stroke: #14532d; }
    .pc-card-head.purple .pc-card-head-icon { background: #c084fc; }
    .pc-card-head.purple .pc-card-head-icon svg { stroke: #581c87; }
    .pc-card-head h3 { font-size: 0.85rem; font-weight: 700; margin: 0; }
    .pc-card-head.amber h3 { color: #78350f; }
    .pc-card-head.green h3 { color: #14532d; }
    .pc-card-head.purple h3 { color: #581c87; }
    .pc-card-body { padding: 1.25rem; }

    .pc-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .pc-group { margin-bottom: 1rem; }
    .pc-group:last-child { margin-bottom: 0; }
    .pc-group.full { grid-column: 1 / -1; }
    .pc-label { display: flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.45rem; }
    .pc-label .req { color: #ef4444; font-weight: 800; }
    .pc-label svg { width: 14px; height: 14px; stroke: #94a3b8; }
    .pc-input, .pc-select, .pc-textarea { width: 100%; padding: 0.7rem 0.9rem; border: 1.5px solid #e2e8f0; border-radius: 11px; font-size: 0.82rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; background: #fff; transition: all 0.2s; box-sizing: border-box; }
    .pc-input:focus, .pc-select:focus, .pc-textarea:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }
    .pc-input::placeholder { color: #94a3b8; }
    .pc-textarea { resize: vertical; min-height: 80px; }
    .pc-hint { font-size: 0.7rem; color: #94a3b8; margin-top: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
    .pc-hint svg { width: 12px; height: 12px; stroke: #94a3b8; }
    .pc-error { font-size: 0.72rem; color: #ef4444; margin-top: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
    .pc-error svg { width: 13px; height: 13px; stroke: #ef4444; flex-shrink: 0; }

    .pc-rp-wrap { position: relative; }
    .pc-rp-prefix { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; font-weight: 700; color: #94a3b8; pointer-events: none; }
    .pc-rp-input { padding-left: 2.8rem; font-family: 'JetBrains Mono', monospace; font-weight: 600; }
    .pc-unit-suffix { position: absolute; right: 0.9rem; top: 50%; transform: translateY(-50%); font-size: 0.72rem; color: #94a3b8; font-weight: 600; pointer-events: none; }
    .pc-unit-input { padding-right: 3rem; }

    .pc-radio-group { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .pc-radio-card { position: relative; display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.1rem; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all 0.2s; background: #fff; }
    .pc-radio-card:hover { border-color: #fbbf24; background: #fffbeb; }
    .pc-radio-card.selected { border-color: #f59e0b; background: #fffbeb; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .pc-radio-card input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }
    .pc-radio-dot { width: 18px; height: 18px; border-radius: 50%; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s; }
    .pc-radio-card.selected .pc-radio-dot { border-color: #f59e0b; background: #f59e0b; }
    .pc-radio-card.selected .pc-radio-dot::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; }
    .pc-radio-emoji { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .pc-radio-emoji.active { background: #dcfce7; }
    .pc-radio-emoji.inactive { background: #f1f5f9; }
    .pc-radio-text { flex: 1; }
    .pc-radio-title { font-size: 0.82rem; font-weight: 700; color: #1e293b; }
    .pc-radio-sub { font-size: 0.7rem; color: #94a3b8; margin-top: 0.1rem; }

    .pc-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; }
    .pc-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.7rem 1.5rem; border-radius: 11px; font-size: 0.82rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .pc-btn-primary { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; box-shadow: 0 3px 10px rgba(217,119,6,0.2); }
    .pc-btn-primary:hover { box-shadow: 0 5px 16px rgba(217,119,6,0.3); transform: translateY(-1px); }
    .pc-btn-primary svg { width: 16px; height: 16px; stroke: #fff; }
    .pc-btn-secondary { background: #fff; color: #64748b; border: 1.5px solid #e2e8f0; }
    .pc-btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
    .pc-btn-secondary svg { width: 16px; height: 16px; stroke: #64748b; }

    @media (max-width: 768px) { .pc-grid2 { grid-template-columns: 1fr; } .pc-radio-group { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="pc-wrap">
    <div class="pc-header">
        <div class="pc-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <h1>Tambah Produk Gula</h1>
            <p>Tambahkan produk baru ke katalog gula</p>
        </div>
    </div>

    <a href="{{ route('gula.produk.index') }}" class="pc-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Daftar Produk
    </a>

    <form method="POST" action="{{ route('gula.produk.store') }}">
        @csrf

        {{-- Section 1: Informasi Dasar --}}
        <div class="pc-card">
            <div class="pc-card-head amber">
                <div class="pc-card-head-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <h3>Informasi Dasar Produk</h3>
            </div>
            <div class="pc-card-body">
                <div class="pc-group full">
                    <label class="pc-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        Nama Produk <span class="req">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Gula Pasir 1kg, Gula Merah 500g, dll" class="pc-input">
                    @error('nama')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                </div>

                <div class="pc-grid2">
                    <div class="pc-group">
                        <label class="pc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                            Jenis Produk
                        </label>
                        <select name="jenis_id" class="pc-select">
                            <option value="">Pilih jenis produk</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" data-nama="{{ $j->nama }}" {{ old('jenis_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jenis" id="jenis-hidden" value="{{ old('jenis') }}">
                        @error('jenis_id')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                        <div class="pc-hint">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <a href="{{ route('gula.setting.index') }}" style="color:#d97706;font-weight:600;text-decoration:none;">Kelola jenis & satuan →</a>
                        </div>
                    </div>
                    <div class="pc-group">
                        <label class="pc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            Satuan <span class="req">*</span>
                        </label>
                        <select name="satuan_id" required class="pc-select" id="satuan-select">
                            <option value="">Pilih satuan</option>
                            @foreach($satuanList as $s)
                                <option value="{{ $s->id }}" data-nama="{{ $s->nama }}" {{ old('satuan_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}@if($s->singkatan) ({{ $s->singkatan }})@endif</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="satuan" id="satuan-hidden" value="{{ old('satuan') }}" required>
                        @error('satuan_id')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                        <div class="pc-hint">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <a href="{{ route('gula.setting.index') }}" style="color:#d97706;font-weight:600;text-decoration:none;">Kelola jenis & satuan →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Harga & Stok --}}
        <div class="pc-card">
            <div class="pc-card-head green">
                <div class="pc-card-head-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Harga & Stok</h3>
            </div>
            <div class="pc-card-body">
                <div class="pc-grid2">
                    <div class="pc-group">
                        <label class="pc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            Harga Modal (HPP)
                        </label>
                        <div class="pc-rp-wrap">
                            <span class="pc-rp-prefix">Rp</span>
                            <input type="number" name="harga_modal" value="{{ old('harga_modal') }}" placeholder="0" min="0" class="pc-input pc-rp-input">
                        </div>
                        <div class="pc-hint">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Harga beli dari supplier
                        </div>
                        @error('harga_modal')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                    <div class="pc-group">
                        <label class="pc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8l-8 8"/><path d="M8 8h8v8"/></svg>
                            Harga Jual <span class="req">*</span>
                        </label>
                        <div class="pc-rp-wrap">
                            <span class="pc-rp-prefix">Rp</span>
                            <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" required placeholder="0" min="0" class="pc-input pc-rp-input">
                        </div>
                        <div class="pc-hint">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Harga jual ke pelanggan
                        </div>
                        @error('harga_jual')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                    <div class="pc-group">
                        <label class="pc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            Stok Gudang Saat Ini
                        </label>
                        <div class="pc-rp-wrap">
                            <input type="number" name="stok_gudang" value="{{ old('stok_gudang', 0) }}" min="0" placeholder="0" class="pc-input pc-unit-input">
                            <span class="pc-unit-suffix" id="stok-gudang-unit">sak</span>
                        </div>
                        @error('stok_gudang')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                    <div class="pc-group">
                        <label class="pc-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            Stok Minimum
                        </label>
                        <div class="pc-rp-wrap">
                            <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 10) }}" min="0" placeholder="10" class="pc-input pc-unit-input">
                            <span class="pc-unit-suffix" id="stok-minimum-unit">sak</span>
                        </div>
                        <div class="pc-hint">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Batas stok rendah untuk peringatan
                        </div>
                        @error('stok_minimum')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Status & Keterangan --}}
        <div class="pc-card">
            <div class="pc-card-head purple">
                <div class="pc-card-head-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </div>
                <h3>Status & Keterangan</h3>
            </div>
            <div class="pc-card-body">
                <div class="pc-group">
                    <label class="pc-label">Status <span class="req">*</span></label>
                    <div class="pc-radio-group">
                        <label class="pc-radio-card {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}" onclick="selectRadio(this)">
                            <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }} required>
                            <div class="pc-radio-dot"></div>
                            <div class="pc-radio-emoji active">🟢</div>
                            <div class="pc-radio-text">
                                <div class="pc-radio-title">Aktif</div>
                                <div class="pc-radio-sub">Produk bisa dijual</div>
                            </div>
                        </label>
                        <label class="pc-radio-card {{ old('status') === 'nonaktif' ? 'selected' : '' }}" onclick="selectRadio(this)">
                            <input type="radio" name="status" value="nonaktif" {{ old('status') === 'nonaktif' ? 'checked' : '' }}>
                            <div class="pc-radio-dot"></div>
                            <div class="pc-radio-emoji inactive">⚫</div>
                            <div class="pc-radio-text">
                                <div class="pc-radio-title">Nonaktif</div>
                                <div class="pc-radio-sub">Sementara tidak dijual</div>
                            </div>
                        </label>
                    </div>
                    @error('status')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                </div>

                <div class="pc-group" style="margin-top: 1rem;">
                    <label class="pc-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Keterangan / Catatan
                    </label>
                    <textarea name="keterangan" class="pc-textarea" placeholder="Catatan tambahan tentang produk (opsional)">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="pc-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pc-actions">
            <a href="{{ route('gula.produk.index') }}" class="pc-btn pc-btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Batal
            </a>
            <button type="submit" class="pc-btn pc-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Produk
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function selectRadio(card) {
    document.querySelectorAll('.pc-radio-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
}

// Sync hidden fields when dropdowns change
document.querySelector('select[name="jenis_id"]').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('jenis-hidden').value = opt.dataset.nama || '';
});
document.getElementById('satuan-select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const nama = opt.dataset.nama || '';
    document.getElementById('satuan-hidden').value = nama;
    const unitLabel = nama || 'unit';
    document.getElementById('stok-gudang-unit').textContent = unitLabel;
    document.getElementById('stok-minimum-unit').textContent = unitLabel;
});
// Init on load
(function() {
    const jSel = document.querySelector('select[name="jenis_id"]');
    if (jSel.value) { jSel.dispatchEvent(new Event('change')); }
    const sSel = document.getElementById('satuan-select');
    if (sSel.value) { sSel.dispatchEvent(new Event('change')); }
})();
</script>
@endpush
@endsection
