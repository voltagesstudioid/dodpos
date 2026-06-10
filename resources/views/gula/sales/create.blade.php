@extends('layouts.app', ['title' => 'Tambah Sales Gula'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

    .gsc-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    /* ── BREADCRUMB ── */
    .gsc-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
    .gsc-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
    .gsc-back:hover { color:#d97706; }
    .gsc-sep { color:#cbd5e1; font-size:0.8125rem; }
    .gsc-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    /* ── FORM CARD ── */
    .gsc-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
    .gsc-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
    .gsc-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .gsc-card-ico svg { width:17px; height:17px; }
    .gsc-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
    .gsc-card-body { padding:1.375rem; }

    .gsc-card.amber .gsc-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef9ee); }
    .gsc-card.amber .gsc-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .gsc-card.green .gsc-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .gsc-card.green .gsc-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .gsc-card.purple .gsc-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
    .gsc-card.purple .gsc-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

    /* ── FORM FIELDS ── */
    .gsc-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
    .gsc-full { grid-column:1 / -1; }
    .gsc-fg { display:flex; flex-direction:column; gap:0.375rem; }
    .gsc-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .gsc-lbl svg { width:13px; height:13px; color:#94a3b8; }
    .gsc-req { color:#ef4444; }
    .gsc-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
    .gsc-inp, .gsc-txt {
        width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
        background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
        transition:all 0.2s; outline:none;
    }
    .gsc-inp:focus, .gsc-txt:focus { border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
    .gsc-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
    .gsc-txt { resize:vertical; min-height:80px; line-height:1.5; }
    .gsc-inp::placeholder, .gsc-txt::placeholder { color:#cbd5e1; }
    .gsc-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
    .gsc-inp.is-invalid, .gsc-txt.is-invalid { border-color:#fecaca; background:#fef2f2; }

    /* ── RADIO CARDS ── */
    .gsc-radios { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; }
    .gsc-radio { position:relative; }
    .gsc-radio input { position:absolute; opacity:0; pointer-events:none; }
    .gsc-radio-card {
        display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px;
        border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff;
    }
    .gsc-radio-card:hover { border-color:#fde68a; background:#fffaf5; }
    .gsc-radio input:checked ~ .gsc-radio-card { border-color:#f59e0b; background:linear-gradient(135deg,#fffbeb,#fef3c7); box-shadow:0 2px 8px rgba(245,158,11,0.12); }
    .gsc-radio-dot { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .gsc-radio-dot.ok { background:#ecfdf5; }
    .gsc-radio-dot.warn { background:#fef3c7; }
    .gsc-radio-dot.off { background:#f1f5f9; }
    .gsc-radio-text { font-size:0.8125rem; font-weight:600; color:#0f172a; }
    .gsc-radio-sub { font-size:0.6875rem; color:#94a3b8; font-weight:500; }

    /* ── ACTIONS ── */
    .gsc-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
    .gsc-btn {
        display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
        font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
        border:1px solid transparent; text-decoration:none; font-family:inherit;
    }
    .gsc-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
    .gsc-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
    .gsc-btn-primary {
        background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
        box-shadow:0 4px 14px rgba(245,158,11,0.3);
    }
    .gsc-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(245,158,11,0.4); }

    @media(max-width:640px) {
        .gsc-grid2 { grid-template-columns:1fr; }
        .gsc-radios { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="gsc-page">

    {{-- ─── BREADCRUMB ─── --}}
    <nav class="gsc-nav">
        <a href="{{ route('gula.sales.index') }}" class="gsc-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Sales
        </a>
        <span class="gsc-sep">/</span>
        <span class="gsc-crumb">Tambah Baru</span>
    </nav>

    <form method="POST" action="{{ route('gula.sales.store') }}">
        @csrf

        {{-- ─── CARD 1: Informasi Pribadi (Amber) ─── --}}
        <div class="gsc-card amber">
            <div class="gsc-card-hdr">
                <div class="gsc-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="gsc-card-title">Informasi Pribadi</div>
            </div>
            <div class="gsc-card-body">
                <div class="gsc-grid2">
                    <div class="gsc-fg">
                        <label class="gsc-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Nama Lengkap <span class="gsc-req">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                            class="gsc-inp @error('nama') is-invalid @enderror" placeholder="Nama lengkap sales">
                        @error('nama')<div class="gsc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="gsc-fg">
                        <label class="gsc-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            No HP <span class="gsc-opt">(Opsional)</span>
                        </label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            class="gsc-inp mono @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                        @error('no_hp')<div class="gsc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="gsc-fg">
                        <label class="gsc-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            Email <span class="gsc-opt">(Opsional)</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="gsc-inp @error('email') is-invalid @enderror" placeholder="email@example.com">
                        @error('email')<div class="gsc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="gsc-fg">
                        <label class="gsc-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            Target Harian <span class="gsc-opt">(Opsional)</span>
                        </label>
                        <input type="number" name="target_harian" value="{{ old('target_harian') }}"
                            class="gsc-inp mono @error('target_harian') is-invalid @enderror" placeholder="0" min="0" step="1">
                        @error('target_harian')<div class="gsc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="gsc-fg gsc-full">
                        <label class="gsc-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Alamat <span class="gsc-opt">(Opsional)</span>
                        </label>
                        <textarea name="alamat" rows="2" class="gsc-txt @error('alamat') is-invalid @enderror" placeholder="Alamat lengkap sales...">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="gsc-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── CARD 2: Informasi Kendaraan (Green) ─── --}}
        <div class="gsc-card green">
            <div class="gsc-card-hdr">
                <div class="gsc-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="gsc-card-title">Informasi Kendaraan</div>
            </div>
            <div class="gsc-card-body">
                <div class="gsc-fg">
                    <label class="gsc-lbl">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        Tugaskan Kendaraan <span class="gsc-opt">(Opsional)</span>
                    </label>
                    <select name="vehicle_id" class="gsc-inp @error('vehicle_id') is-invalid @enderror" style="cursor:pointer;">
                        <option value="">-- Pilih dari Data Kendaraan --</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                                {{ strtoupper($v->license_plate) }}@if($v->type) · {{ $v->type }}@endif
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<div class="gsc-err">{{ $message }}</div>@enderror
                    <div style="font-size:0.6875rem;color:#94a3b8;margin-top:4px;">Data diambil dari menu Operasional → Data Kendaraan</div>
                </div>
            </div>
        </div>

        {{-- ─── CARD 3: Status & Keterangan (Purple) ─── --}}
        <div class="gsc-card purple">
            <div class="gsc-card-hdr">
                <div class="gsc-card-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="gsc-card-title">Status & Keterangan</div>
            </div>
            <div class="gsc-card-body">
                <div style="display:flex;flex-direction:column;gap:1.125rem;">
                    <div class="gsc-fg">
                        <label class="gsc-lbl" style="margin-bottom:0.5rem;">Status Sales <span class="gsc-req">*</span></label>
                        <div class="gsc-radios">
                            <label class="gsc-radio">
                                <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }} required>
                                <div class="gsc-radio-card">
                                    <div class="gsc-radio-dot ok">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </div>
                                    <div>
                                        <div class="gsc-radio-text">Aktif</div>
                                        <div class="gsc-radio-sub">Beroperasi normal</div>
                                    </div>
                                </div>
                            </label>
                            <label class="gsc-radio">
                                <input type="radio" name="status" value="cuti" {{ old('status') === 'cuti' ? 'checked' : '' }}>
                                <div class="gsc-radio-card">
                                    <div class="gsc-radio-dot warn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    </div>
                                    <div>
                                        <div class="gsc-radio-text">Cuti</div>
                                        <div class="gsc-radio-sub">Sementara libur</div>
                                    </div>
                                </div>
                            </label>
                            <label class="gsc-radio">
                                <input type="radio" name="status" value="nonaktif" {{ old('status') === 'nonaktif' ? 'checked' : '' }}>
                                <div class="gsc-radio-card">
                                    <div class="gsc-radio-dot off">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    </div>
                                    <div>
                                        <div class="gsc-radio-text">Nonaktif</div>
                                        <div class="gsc-radio-sub">Tidak beroperasi</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('status')<div class="gsc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="gsc-fg">
                        <label class="gsc-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                            Keterangan <span class="gsc-opt">(Opsional)</span>
                        </label>
                        <textarea name="keterangan" rows="2" class="gsc-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<div class="gsc-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── ACTIONS ─── --}}
        <div class="gsc-actions">
            <a href="{{ route('gula.sales.index') }}" class="gsc-btn gsc-btn-ghost">Batal</a>
            <button type="submit" class="gsc-btn gsc-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Data
            </button>
        </div>
    </form>

</div>
@endsection
