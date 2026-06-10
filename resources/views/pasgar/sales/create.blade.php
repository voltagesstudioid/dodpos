@extends('layouts.app', ['title' => 'Tambah Sales Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .psc-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    /* Breadcrumb */
    .psc-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
    .psc-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
    .psc-back:hover { color:#4f46e5; }
    .psc-sep { color:#cbd5e1; font-size:0.8125rem; }
    .psc-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    /* Form Card */
    .psc-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
    .psc-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
    .psc-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.1rem; }
    .psc-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
    .psc-card-body { padding:1.375rem; }

    .psc-card.indigo .psc-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .psc-card.indigo .psc-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }
    .psc-card.green .psc-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .psc-card.green .psc-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .psc-card.purple .psc-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
    .psc-card.purple .psc-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

    .psc-card.amber .psc-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .psc-card.amber .psc-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }

    /* Form fields */
    .psc-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
    .psc-full { grid-column:1 / -1; }
    .psc-fg { display:flex; flex-direction:column; gap:0.375rem; }
    .psc-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .psc-req { color:#ef4444; }
    .psc-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
    .psc-inp, .psc-txt, .psc-sel {
        width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
        background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
        transition:all 0.2s; outline:none;
    }
    .psc-inp:focus, .psc-txt:focus, .psc-sel:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .psc-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
    .psc-txt { resize:vertical; min-height:80px; line-height:1.5; }
    .psc-inp::placeholder, .psc-txt::placeholder { color:#cbd5e1; }
    .psc-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
    .psc-hint { font-size:0.72rem; color:#94a3b8; margin-top:0.25rem; }

    /* Prefix */
    .psc-prefix { display:flex; align-items:stretch; }
    .psc-prefix-lbl {
        display:flex; align-items:center; padding:0 0.875rem; background:#f1f5f9; border:1.5px solid #e2e8f0;
        border-right:none; border-radius:10px 0 0 10px; font-size:0.8125rem; font-weight:700; color:#64748b;
    }
    .psc-prefix .psc-inp { border-radius:0 10px 10px 0; }

    /* Actions */
    .psc-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
    .psc-btn {
        display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
        font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
        border:1px solid transparent; text-decoration:none; font-family:inherit;
    }
    .psc-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
    .psc-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
    .psc-btn-primary {
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 4px 14px rgba(79,70,229,0.3);
    }
    .psc-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(79,70,229,0.4); }

    @media(max-width:640px) { .psc-grid2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="psc-page">

    {{-- Breadcrumb --}}
    <nav class="psc-nav">
        <a href="{{ route('pasgar.sales.index') }}" class="psc-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Sales
        </a>
        <span class="psc-sep">/</span>
        <span class="psc-crumb">Tambah Baru</span>
    </nav>

    <form method="POST" action="{{ route('pasgar.sales.store') }}">
        @csrf

        {{-- Card 1: Informasi Pribadi (Indigo) --}}
        <div class="psc-card indigo">
            <div class="psc-card-hdr">
                <div class="psc-card-ico">👤</div>
                <div class="psc-card-title">Informasi Pribadi</div>
            </div>
            <div class="psc-card-body">
                <div class="psc-grid2">
                    <div class="psc-fg psc-full">
                        <label class="psc-lbl">Nama Lengkap <span class="psc-req">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                            class="psc-inp @error('nama') is-invalid @enderror" placeholder="Nama lengkap sales">
                        @error('nama')<div class="psc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="psc-fg">
                        <label class="psc-lbl">No HP <span class="psc-opt">(Opsional)</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            class="psc-inp mono @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                        @error('no_hp')<div class="psc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="psc-fg">
                        <label class="psc-lbl">Target Harian <span class="psc-opt">(Opsional)</span></label>
                        <div class="psc-prefix">
                            <span class="psc-prefix-lbl">Rp</span>
                            <input type="number" name="target_harian" value="{{ old('target_harian', 0) }}"
                                class="psc-inp mono" placeholder="0" min="0" step="1">
                        </div>
                        @error('target_harian')<div class="psc-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="psc-fg psc-full">
                        <label class="psc-lbl">Alamat <span class="psc-opt">(Opsional)</span></label>
                        <textarea name="alamat" rows="2" class="psc-txt @error('alamat') is-invalid @enderror" placeholder="Alamat lengkap sales...">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="psc-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Kendaraan (Green) --}}
        <div class="psc-card green">
            <div class="psc-card-hdr">
                <div class="psc-card-ico">🚗</div>
                <div class="psc-card-title">Informasi Kendaraan</div>
            </div>
            <div class="psc-card-body">
                <div class="psc-fg">
                    <label class="psc-lbl">Tugaskan Kendaraan <span class="psc-opt">(Opsional)</span></label>
                    <select name="vehicle_id" class="psc-sel @error('vehicle_id') is-invalid @enderror">
                        <option value="">-- Pilih dari Data Kendaraan --</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                                {{ strtoupper($v->license_plate) }}@if($v->type) · {{ $v->type }}@endif
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<div class="psc-err">{{ $message }}</div>@enderror
                    <div class="psc-hint">Data diambil dari menu Operasional → Data Kendaraan</div>
                </div>
            </div>
        </div>

        {{-- Card 3: Keterangan (Purple) --}}
        <div class="psc-card purple">
            <div class="psc-card-hdr">
                <div class="psc-card-ico">📝</div>
                <div class="psc-card-title">Keterangan</div>
            </div>
            <div class="psc-card-body">
                <div class="psc-fg">
                    <label class="psc-lbl">Catatan Tambahan <span class="psc-opt">(Opsional)</span></label>
                    <textarea name="keterangan" rows="2" class="psc-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="psc-err">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="psc-actions">
            <a href="{{ route('pasgar.sales.index') }}" class="psc-btn psc-btn-ghost">Batal</a>
            <button type="submit" class="psc-btn psc-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Data
            </button>
        </div>
    </form>

</div>
@endsection
