@extends('layouts.app', ['title' => 'Edit Produk ' . $produk->nama])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .pe-wrap { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 780px; margin: 0 auto; padding: 1.25rem; }
    .pe-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .pe-header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(217,119,6,0.25); flex-shrink: 0; }
    .pe-header-icon svg { width: 26px; height: 26px; stroke: #fff; }
    .pe-header-text h1 { font-size: 1.35rem; font-weight: 800; color: #78350f; margin: 0; }
    .pe-header-text p { font-size: 0.8rem; color: #b45309; margin: 0; font-weight: 600; }
    .pe-code-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; font-weight: 700; color: #475569; margin-left: auto; }
    .pe-code-badge svg { width: 14px; height: 14px; stroke: #94a3b8; }
    .pe-back { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; color: #b45309; text-decoration: none; font-weight: 700; margin-bottom: 1rem; }
    .pe-back:hover { color: #92400e; text-decoration: underline; }

    .pe-card { background: #fff; border: 1px solid #fde68a; border-radius: 16px; overflow: hidden; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(120,53,15,0.04); }
    .pe-card-head { padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.6rem; }
    .pe-card-head.amber { background: linear-gradient(135deg, #fffbeb, #fef3c7); border-bottom: 1px solid #fde68a; }
    .pe-card-head.green { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-bottom: 1px solid #bbf7d0; }
    .pe-card-head.purple { background: linear-gradient(135deg, #faf5ff, #f3e8ff); border-bottom: 1px solid #e9d5ff; }
    .pe-card-head-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pe-card-head-icon svg { width: 18px; height: 18px; }
    .pe-card-head.amber .pe-card-head-icon { background: #fbbf24; }
    .pe-card-head.amber .pe-card-head-icon svg { stroke: #78350f; }
    .pe-card-head.green .pe-card-head-icon { background: #4ade80; }
    .pe-card-head.green .pe-card-head-icon svg { stroke: #14532d; }
    .pe-card-head.purple .pe-card-head-icon { background: #c084fc; }
    .pe-card-head.purple .pe-card-head-icon svg { stroke: #581c87; }
    .pe-card-head h3 { font-size: 0.85rem; font-weight: 700; margin: 0; }
    .pe-card-head.amber h3 { color: #78350f; }
    .pe-card-head.green h3 { color: #14532d; }
    .pe-card-head.purple h3 { color: #581c87; }
    .pe-card-body { padding: 1.25rem; }

    .pe-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .pe-group { margin-bottom: 1rem; }
    .pe-group:last-child { margin-bottom: 0; }
    .pe-group.full { grid-column: 1 / -1; }
    .pe-label { display: flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.45rem; }
    .pe-label .req { color: #ef4444; font-weight: 800; }
    .pe-label svg { width: 14px; height: 14px; stroke: #94a3b8; }
    .pe-input, .pe-select, .pe-textarea { width: 100%; padding: 0.7rem 0.9rem; border: 1.5px solid #e2e8f0; border-radius: 11px; font-size: 0.82rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; background: #fff; transition: all 0.2s; box-sizing: border-box; }
    .pe-input:focus, .pe-select:focus, .pe-textarea:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }
    .pe-input::placeholder { color: #94a3b8; }
    .pe-textarea { resize: vertical; min-height: 80px; }
    .pe-hint { font-size: 0.7rem; color: #94a3b8; margin-top: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
    .pe-hint svg { width: 12px; height: 12px; stroke: #94a3b8; }
    .pe-error { font-size: 0.72rem; color: #ef4444; margin-top: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
    .pe-error svg { width: 13px; height: 13px; stroke: #ef4444; flex-shrink: 0; }

    .pe-rp-wrap { position: relative; }
    .pe-rp-prefix { position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; font-weight: 700; color: #94a3b8; pointer-events: none; }
    .pe-rp-input { padding-left: 2.8rem; font-family: 'JetBrains Mono', monospace; font-weight: 600; }
    .pe-unit-suffix { position: absolute; right: 0.9rem; top: 50%; transform: translateY(-50%); font-size: 0.72rem; color: #94a3b8; font-weight: 600; pointer-events: none; }
    .pe-unit-input { padding-right: 3rem; }

    .pe-radio-group { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .pe-radio-card { position: relative; display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.1rem; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer; transition: all 0.2s; background: #fff; }
    .pe-radio-card:hover { border-color: #fbbf24; background: #fffbeb; }
    .pe-radio-card.selected { border-color: #f59e0b; background: #fffbeb; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
    .pe-radio-card input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }
    .pe-radio-dot { width: 18px; height: 18px; border-radius: 50%; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s; }
    .pe-radio-card.selected .pe-radio-dot { border-color: #f59e0b; background: #f59e0b; }
    .pe-radio-card.selected .pe-radio-dot::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; }
    .pe-radio-emoji { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .pe-radio-emoji.active { background: #dcfce7; }
    .pe-radio-emoji.inactive { background: #f1f5f9; }
    .pe-radio-text { flex: 1; }
    .pe-radio-title { font-size: 0.82rem; font-weight: 700; color: #1e293b; }
    .pe-radio-sub { font-size: 0.7rem; color: #94a3b8; margin-top: 0.1rem; }

    .pe-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; }
    .pe-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.7rem 1.5rem; border-radius: 11px; font-size: 0.82rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .pe-btn-primary { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; box-shadow: 0 3px 10px rgba(217,119,6,0.2); }
    .pe-btn-primary:hover { box-shadow: 0 5px 16px rgba(217,119,6,0.3); transform: translateY(-1px); }
    .pe-btn-primary svg { width: 16px; height: 16px; stroke: #fff; }
    .pe-btn-secondary { background: #fff; color: #64748b; border: 1.5px solid #e2e8f0; }
    .pe-btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
    .pe-btn-secondary svg { width: 16px; height: 16px; stroke: #64748b; }

    @media (max-width: 768px) { .pe-grid2 { grid-template-columns: 1fr; } .pe-radio-group { grid-template-columns: 1fr; } .pe-code-badge { margin-left: 0; } }
</style>
@endpush

@section('content')
<div class="pe-wrap">
    <div class="pe-header">
        <div class="pe-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <div class="pe-header-text">
            <h1>Edit Produk Gula</h1>
            <p>Ubah data produk {{ $produk->kode_produk }}</p>
        </div>
        <div class="pe-code-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
            {{ $produk->kode_produk }}
        </div>
    </div>

    <a href="{{ route('gula.produk.index') }}" class="pe-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Daftar Produk
    </a>

    <form method="POST" action="{{ route('gula.produk.update', $produk) }}">
        @csrf
        @method('PUT')

        {{-- Section 1: Informasi Dasar --}}
        <div class="pe-card">
            <div class="pe-card-head amber">
                <div class="pe-card-head-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <h3>Informasi Dasar Produk</h3>
            </div>
            <div class="pe-card-body">
                <div class="pe-group full">
                    <label class="pe-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        Nama Produk <span class="req">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required placeholder="Contoh: Gula Pasir 1kg, Gula Merah 500g, dll" class="pe-input">
                    @error('nama')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                </div>

                <div class="pe-grid2">
                    <div class="pe-group">
                        <label class="pe-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                            Jenis Produk
                        </label>
                        <select name="jenis_id" class="pe-select">
                            <option value="">Pilih jenis produk</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" data-nama="{{ $j->nama }}" {{ old('jenis_id', $produk->jenis_id) == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jenis" id="jenis-hidden" value="{{ old('jenis', $produk->jenis) }}">
                        @error('jenis_id')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                        <div class="pe-hint"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg><a href="{{ route('gula.setting.index') }}" style="color:#d97706;font-weight:600;text-decoration:none;">Kelola jenis & satuan →</a></div>
                    </div>
                    <div class="pe-group">
                        <label class="pe-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            Satuan <span class="req">*</span>
                        </label>
                        <select name="satuan_id" required class="pe-select" id="satuan-select">
                            <option value="">Pilih satuan</option>
                            @foreach($satuanList as $s)
                                <option value="{{ $s->id }}" data-nama="{{ $s->nama }}" {{ old('satuan_id', $produk->satuan_id) == $s->id ? 'selected' : '' }}>{{ $s->nama }}@if($s->singkatan) ({{ $s->singkatan }})@endif</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="satuan" id="satuan-hidden" value="{{ old('satuan', $produk->satuan) }}" required>
                        @error('satuan_id')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                        <div class="pe-hint"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg><a href="{{ route('gula.setting.index') }}" style="color:#d97706;font-weight:600;text-decoration:none;">Kelola jenis & satuan →</a></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Harga & Stok --}}
        <div class="pe-card">
            <div class="pe-card-head green">
                <div class="pe-card-head-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Harga & Stok</h3>
            </div>
            <div class="pe-card-body">
                <div class="pe-grid2">
                    <div class="pe-group">
                        <label class="pe-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            Harga Modal (HPP)
                        </label>
                        <div class="pe-rp-wrap">
                            <span class="pe-rp-prefix">Rp</span>
                            <input type="text" inputmode="numeric" data-currency name="harga_modal" value="{{ old('harga_modal', (int) $produk->harga_modal) }}" placeholder="0" class="pe-input pe-rp-input">
                        </div>
                        <div class="pe-hint"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Harga beli dari supplier</div>
                        @error('harga_modal')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                    <div class="pe-group">
                        <label class="pe-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8l-8 8"/><path d="M8 8h8v8"/></svg>
                            Harga Jual <span class="req">*</span>
                        </label>
                        <div class="pe-rp-wrap">
                            <span class="pe-rp-prefix">Rp</span>
                            <input type="text" inputmode="numeric" data-currency name="harga_jual" value="{{ old('harga_jual', (int) $produk->harga_jual) }}" required placeholder="0" class="pe-input pe-rp-input">
                        </div>
                        <div class="pe-hint"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Harga jual ke pelanggan</div>
                        @error('harga_jual')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                    <div class="pe-group">
                        <label class="pe-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            Stok Gudang Saat Ini
                        </label>
                        <div class="pe-rp-wrap">
                            <input type="number" name="stok_gudang" value="{{ old('stok_gudang', $produk->stok_gudang) }}" min="0" placeholder="0" class="pe-input pe-unit-input">
                            <span class="pe-unit-suffix">{{ $produk->satuan }}</span>
                        </div>
                        @error('stok_gudang')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                    <div class="pe-group">
                        <label class="pe-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            Stok Minimum
                        </label>
                        <div class="pe-rp-wrap">
                            <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $produk->stok_minimum) }}" min="0" placeholder="10" class="pe-input pe-unit-input">
                            <span class="pe-unit-suffix">{{ $produk->satuan }}</span>
                        </div>
                        <div class="pe-hint"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Batas stok rendah untuk peringatan</div>
                        @error('stok_minimum')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Status & Keterangan --}}
        <div class="pe-card">
            <div class="pe-card-head purple">
                <div class="pe-card-head-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </div>
                <h3>Status & Keterangan</h3>
            </div>
            <div class="pe-card-body">
                @php $currentStatus = old('status', $produk->status); @endphp
                <div class="pe-group">
                    <label class="pe-label">Status <span class="req">*</span></label>
                    <div class="pe-radio-group">
                        <label class="pe-radio-card {{ $currentStatus === 'aktif' ? 'selected' : '' }}" onclick="selectRadio(this)">
                            <input type="radio" name="status" value="aktif" {{ $currentStatus === 'aktif' ? 'checked' : '' }} required>
                            <div class="pe-radio-dot"></div>
                            <div class="pe-radio-emoji active">🟢</div>
                            <div class="pe-radio-text">
                                <div class="pe-radio-title">Aktif</div>
                                <div class="pe-radio-sub">Produk bisa dijual</div>
                            </div>
                        </label>
                        <label class="pe-radio-card {{ $currentStatus === 'nonaktif' ? 'selected' : '' }}" onclick="selectRadio(this)">
                            <input type="radio" name="status" value="nonaktif" {{ $currentStatus === 'nonaktif' ? 'checked' : '' }}>
                            <div class="pe-radio-dot"></div>
                            <div class="pe-radio-emoji inactive">⚫</div>
                            <div class="pe-radio-text">
                                <div class="pe-radio-title">Nonaktif</div>
                                <div class="pe-radio-sub">Sementara tidak dijual</div>
                            </div>
                        </label>
                    </div>
                    @error('status')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                </div>

                <div class="pe-group" style="margin-top: 1rem;">
                    <label class="pe-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Keterangan / Catatan
                    </label>
                    <textarea name="keterangan" class="pe-textarea" placeholder="Catatan tambahan tentang produk (opsional)">{{ old('keterangan', $produk->keterangan) }}</textarea>
                    @error('keterangan')<div class="pe-error"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pe-actions">
            <a href="{{ route('gula.produk.index') }}" class="pe-btn pe-btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Batal
            </a>
            <button type="submit" class="pe-btn pe-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Update Produk
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function selectRadio(card) {
    document.querySelectorAll('.pe-radio-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
}

// Sync hidden fields when dropdowns change
document.querySelector('select[name="jenis_id"]').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('jenis-hidden').value = opt.dataset.nama || '';
});
document.getElementById('satuan-select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('satuan-hidden').value = opt.dataset.nama || '';
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
