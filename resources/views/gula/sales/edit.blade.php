@extends('layouts.app', ['title' => 'Edit Sales Gula'])

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .gse-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* ── BREADCRUMB ── */
        .gse-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
        .gse-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .gse-back:hover { color:#d97706; }
        .gse-sep { color:#cbd5e1; font-size:0.8125rem; }
        .gse-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

        /* ── HEADER CARD ── */
        .gse-top { background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:1.375rem 1.5rem; margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.04); }
        .gse-top-l { display:flex; align-items:center; gap:0.875rem; }
        .gse-avatar { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.125rem; font-weight:800; color:#fff; flex-shrink:0; background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 4px 12px rgba(245,158,11,0.25); }
        .gse-top-name { font-size:1.0625rem; font-weight:800; color:#0f172a; letter-spacing:-0.01em; }
        .gse-top-meta { display:flex; align-items:center; gap:6px; margin-top:1px; }
        .gse-code { font-family:'JetBrains Mono',monospace; font-size:0.6875rem; font-weight:600; color:#b45309; background:#fffbeb; padding:2px 7px; border-radius:5px; border:1px solid #fde68a; }

        /* ── FORM CARD ── */
        .gse-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .gse-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .gse-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .gse-card-ico svg { width:17px; height:17px; }
        .gse-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .gse-card-body { padding:1.375rem; }

        .gse-card.amber .gse-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef9ee); }
        .gse-card.amber .gse-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
        .gse-card.green .gse-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .gse-card.green .gse-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .gse-card.purple .gse-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .gse-card.purple .gse-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        /* ── FORM FIELDS ── */
        .gse-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
        .gse-full { grid-column:1 / -1; }
        .gse-fg { display:flex; flex-direction:column; gap:0.375rem; }
        .gse-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
        .gse-lbl svg { width:13px; height:13px; color:#94a3b8; }
        .gse-req { color:#ef4444; }
        .gse-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
        .gse-inp, .gse-txt {
            width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
            background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
            transition:all 0.2s; outline:none;
        }
        .gse-inp:focus, .gse-txt:focus { border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .gse-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
        .gse-txt { resize:vertical; min-height:80px; line-height:1.5; }
        .gse-inp::placeholder, .gse-txt::placeholder { color:#cbd5e1; }
        .gse-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
        .gse-inp.is-invalid, .gse-txt.is-invalid { border-color:#fecaca; background:#fef2f2; }

        /* ── RADIO CARDS ── */
        .gse-radios { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; }
        .gse-radio { position:relative; }
        .gse-radio input { position:absolute; opacity:0; pointer-events:none; }
        .gse-radio-card {
            display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px;
            border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff;
        }
        .gse-radio-card:hover { border-color:#fde68a; background:#fffaf5; }
        .gse-radio input:checked ~ .gse-radio-card { border-color:#f59e0b; background:linear-gradient(135deg,#fffbeb,#fef3c7); box-shadow:0 2px 8px rgba(245,158,11,0.12); }
        .gse-radio-dot { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .gse-radio-dot.ok { background:#ecfdf5; }
        .gse-radio-dot.off { background:#f1f5f9; }
        .gse-radio-text { font-size:0.8125rem; font-weight:600; color:#0f172a; }
        .gse-radio-sub { font-size:0.6875rem; color:#94a3b8; font-weight:500; }

        /* ── ACTIONS ── */
        .gse-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
        .gse-btn {
            display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
            border:1px solid transparent; text-decoration:none; font-family:inherit;
        }
        .gse-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
        .gse-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
        .gse-btn-primary {
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 4px 14px rgba(245,158,11,0.3);
        }
        .gse-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(245,158,11,0.4); }

        @media(max-width:640px) {
            .gse-grid2 { grid-template-columns:1fr; }
            .gse-radios { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    @section('content')
    <div class="gse-page">

        {{-- ─── BREADCRUMB ─── --}}
        <nav class="gse-nav">
            <a href="{{ route('gula.sales.index') }}" class="gse-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Sales
            </a>
            <span class="gse-sep">/</span>
            <span class="gse-crumb">Edit Data</span>
        </nav>

        {{-- ─── TOP INFO ─── --}}
        <div class="gse-top">
            <div class="gse-top-l">
                <div class="gse-avatar">{{ strtoupper(substr($sales->nama, 0, 1)) }}</div>
                <div>
                    <div class="gse-top-name">{{ $sales->nama }}</div>
                    <div class="gse-top-meta">
                        <span class="gse-code">{{ $sales->kode_sales }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('gula.sales.update', $sales->id) }}">
            @csrf
            @method('PUT')

            {{-- ─── CARD 1: Informasi Pribadi (Amber) ─── --}}
            <div class="gse-card amber">
                <div class="gse-card-hdr">
                    <div class="gse-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="gse-card-title">Informasi Pribadi</div>
                </div>
                <div class="gse-card-body">
                    <div class="gse-grid2">
                        <div class="gse-fg">
                            <label class="gse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Nama Lengkap <span class="gse-req">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama', $sales->nama) }}" required
                                class="gse-inp @error('nama') is-invalid @enderror" placeholder="Nama lengkap sales">
                            @error('nama')<div class="gse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="gse-fg">
                            <label class="gse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                No HP <span class="gse-opt">(Opsional)</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $sales->no_hp) }}"
                                class="gse-inp mono @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                            @error('no_hp')<div class="gse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="gse-fg">
                            <label class="gse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                Email <span class="gse-opt">(Opsional)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $sales->email) }}"
                                class="gse-inp @error('email') is-invalid @enderror" placeholder="email@example.com">
                            @error('email')<div class="gse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="gse-fg">
                            <label class="gse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                Target Harian <span class="gse-opt">(Opsional)</span>
                            </label>
                            <input type="number" name="target_harian" value="{{ old('target_harian', $sales->target_harian) }}"
                                class="gse-inp mono @error('target_harian') is-invalid @enderror" placeholder="0" min="0" step="0.01">
                            @error('target_harian')<div class="gse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="gse-fg gse-full">
                            <label class="gse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Alamat <span class="gse-opt">(Opsional)</span>
                            </label>
                            <textarea name="alamat" rows="2" class="gse-txt @error('alamat') is-invalid @enderror" placeholder="Alamat lengkap sales...">{{ old('alamat', $sales->alamat) }}</textarea>
                            @error('alamat')<div class="gse-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── CARD 2: Informasi Kendaraan (Green) ─── --}}
            <div class="gse-card green">
                <div class="gse-card-hdr">
                    <div class="gse-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div class="gse-card-title">Informasi Kendaraan</div>
                </div>
                <div class="gse-card-body">
                    <div class="gse-fg">
                        <label class="gse-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            Tugaskan Kendaraan <span class="gse-opt">(Opsional)</span>
                        </label>
                        <select name="vehicle_id" class="gse-inp @error('vehicle_id') is-invalid @enderror" style="cursor:pointer;">
                            <option value="">-- Pilih dari Data Kendaraan --</option>
                            @foreach($vehicles as $v)
                                @php
                                    $isCurrent = $sales->vehicle && $sales->vehicle->id === $v->id;
                                @endphp
                                <option value="{{ $v->id }}" {{ old('vehicle_id', $isCurrent ? $v->id : '') == $v->id ? 'selected' : '' }}>
                                    {{ strtoupper($v->license_plate) }}@if($v->type) · {{ $v->type }}@endif@if($isCurrent) (Saat ini)@endif
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')<div class="gse-err">{{ $message }}</div>@enderror
                        <div style="font-size:0.6875rem;color:#94a3b8;margin-top:4px;">Data diambil dari menu Operasional → Data Kendaraan</div>
                    </div>
                </div>
            </div>

            {{-- ─── CARD 3: Status & Keterangan (Purple) ─── --}}
            <div class="gse-card purple">
                <div class="gse-card-hdr">
                    <div class="gse-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="gse-card-title">Status & Keterangan</div>
                </div>
                <div class="gse-card-body">
                    <div style="display:flex;flex-direction:column;gap:1.125rem;">
                        <div class="gse-fg">
                            <label class="gse-lbl" style="margin-bottom:0.5rem;">Status Sales <span class="gse-req">*</span></label>
                            <div class="gse-radios">
                                <label class="gse-radio">
                                    <input type="radio" name="status" value="aktif" {{ old('status', $sales->status) === 'aktif' ? 'checked' : '' }} required>
                                    <div class="gse-radio-card">
                                        <div class="gse-radio-dot ok">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                        <div>
                                            <div class="gse-radio-text">Aktif</div>
                                            <div class="gse-radio-sub">Sales beroperasi normal</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="gse-radio">
                                    <input type="radio" name="status" value="cuti" {{ old('status', $sales->status) === 'cuti' ? 'checked' : '' }}>
                                    <div class="gse-radio-card">
                                        <div class="gse-radio-dot" style="background:#fef3c7;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        </div>
                                        <div>
                                            <div class="gse-radio-text">Cuti</div>
                                            <div class="gse-radio-sub">Sementara libur</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="gse-radio">
                                    <input type="radio" name="status" value="nonaktif" {{ old('status', $sales->status) === 'nonaktif' ? 'checked' : '' }}>
                                    <div class="gse-radio-card">
                                        <div class="gse-radio-dot off">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        </div>
                                        <div>
                                            <div class="gse-radio-text">Nonaktif</div>
                                            <div class="gse-radio-sub">Sales tidak beroperasi</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('status')<div class="gse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="gse-fg">
                            <label class="gse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                                Keterangan <span class="gse-opt">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" rows="2" class="gse-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan...">{{ old('keterangan', $sales->keterangan) }}</textarea>
                            @error('keterangan')<div class="gse-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── ACTIONS ─── --}}
            <div class="gse-actions">
                <a href="{{ route('gula.sales.index') }}" class="gse-btn gse-btn-ghost">Batal</a>
                <button type="submit" class="gse-btn gse-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
@endsection
