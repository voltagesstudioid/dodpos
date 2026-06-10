@extends('layouts.app', ['title' => 'Edit Sales Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .pse-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    /* Breadcrumb */
    .pse-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
    .pse-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
    .pse-back:hover { color:#4f46e5; }
    .pse-sep { color:#cbd5e1; font-size:0.8125rem; }
    .pse-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    /* Kode Badge */
    .pse-kode { display:inline-flex; align-items:center; gap:6px; padding:0.375rem 0.875rem; background:linear-gradient(135deg,#eef2ff,#e0e7ff); border:1px solid #c7d2fe; border-radius:8px; font-family:'JetBrains Mono',monospace; font-size:0.75rem; font-weight:700; color:#4338ca; margin-left:auto; }

    /* Form Card */
    .pse-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
    .pse-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
    .pse-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.1rem; }
    .pse-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
    .pse-card-body { padding:1.375rem; }

    .pse-card.indigo .pse-card-hdr { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .pse-card.indigo .pse-card-ico { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }
    .pse-card.green .pse-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .pse-card.green .pse-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .pse-card.purple .pse-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
    .pse-card.purple .pse-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }
    .pse-card.amber .pse-card-hdr { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .pse-card.amber .pse-card-ico { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .pse-card.rose .pse-card-hdr { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
    .pse-card.rose .pse-card-ico { background:linear-gradient(135deg,#f43f5e,#e11d48); color:#fff; }

    /* Form fields */
    .pse-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
    .pse-full { grid-column:1 / -1; }
    .pse-fg { display:flex; flex-direction:column; gap:0.375rem; }
    .pse-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .pse-req { color:#ef4444; }
    .pse-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
    .pse-inp, .pse-txt, .pse-sel {
        width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
        background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
        transition:all 0.2s; outline:none;
    }
    .pse-inp:focus, .pse-txt:focus, .pse-sel:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .pse-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
    .pse-txt { resize:vertical; min-height:80px; line-height:1.5; }
    .pse-inp::placeholder, .pse-txt::placeholder { color:#cbd5e1; }
    .pse-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
    .pse-hint { font-size:0.72rem; color:#94a3b8; margin-top:0.25rem; }

    /* Prefix */
    .pse-prefix { display:flex; align-items:stretch; }
    .pse-prefix-lbl {
        display:flex; align-items:center; padding:0 0.875rem; background:#f1f5f9; border:1.5px solid #e2e8f0;
        border-right:none; border-radius:10px 0 0 10px; font-size:0.8125rem; font-weight:700; color:#64748b;
    }
    .pse-prefix .pse-inp { border-radius:0 10px 10px 0; }

    /* Status toggle */
    .pse-status-row { display:flex; gap:0.75rem; }
    .pse-status-opt { flex:1; }
    .pse-status-opt input[type="radio"] { display:none; }
    .pse-status-opt label {
        display:flex; align-items:center; justify-content:center; gap:8px; padding:0.75rem 1rem;
        border:1.5px solid #e2e8f0; border-radius:10px; cursor:pointer;
        font-size:0.8125rem; font-weight:700; transition:all 0.2s; background:#fcfcfd;
    }
    .pse-status-opt input[type="radio"]:checked + label.aktif {
        background:linear-gradient(135deg,#ecfdf5,#d1fae5); border-color:#10b981; color:#047857;
    }
    .pse-status-opt input[type="radio"]:checked + label.nonaktif {
        background:linear-gradient(135deg,#fef2f2,#fee2e2); border-color:#ef4444; color:#b91c1c;
    }

    /* Actions */
    .pse-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
    .pse-btn {
        display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
        font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
        border:1px solid transparent; text-decoration:none; font-family:inherit;
    }
    .pse-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
    .pse-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
    .pse-btn-primary {
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 4px 14px rgba(79,70,229,0.3);
    }
    .pse-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(79,70,229,0.4); }

    @media(max-width:640px) { .pse-grid2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="pse-page">

    {{-- Breadcrumb --}}
    <nav class="pse-nav">
        <a href="{{ route('pasgar.sales.index') }}" class="pse-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Sales
        </a>
        <span class="pse-sep">/</span>
        <span class="pse-crumb">Edit Sales</span>
        <span class="pse-kode">{{ $sales->kode_sales }}</span>
    </nav>

    <form method="POST" action="{{ route('pasgar.sales.update', $sales) }}">
        @csrf
        @method('PUT')

        {{-- Card 1: Informasi Pribadi (Indigo) --}}
        <div class="pse-card indigo">
            <div class="pse-card-hdr">
                <div class="pse-card-ico">👤</div>
                <div class="pse-card-title">Informasi Pribadi</div>
            </div>
            <div class="pse-card-body">
                <div class="pse-grid2">
                    <div class="pse-fg pse-full">
                        <label class="pse-lbl">Nama Lengkap <span class="pse-req">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $sales->nama) }}" required
                            class="pse-inp @error('nama') is-invalid @enderror" placeholder="Nama lengkap sales">
                        @error('nama')<div class="pse-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="pse-fg">
                        <label class="pse-lbl">No HP <span class="pse-opt">(Opsional)</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $sales->no_hp) }}"
                            class="pse-inp mono @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                        @error('no_hp')<div class="pse-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="pse-fg">
                        <label class="pse-lbl">Target Harian <span class="pse-opt">(Opsional)</span></label>
                        <div class="pse-prefix">
                            <span class="pse-prefix-lbl">Rp</span>
                            <input type="number" name="target_harian" value="{{ old('target_harian', $sales->target_harian) }}"
                                class="pse-inp mono" placeholder="0" min="0" step="1">
                        </div>
                        @error('target_harian')<div class="pse-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="pse-fg pse-full">
                        <label class="pse-lbl">Alamat <span class="pse-opt">(Opsional)</span></label>
                        <textarea name="alamat" rows="2" class="pse-txt @error('alamat') is-invalid @enderror" placeholder="Alamat lengkap sales...">{{ old('alamat', $sales->alamat) }}</textarea>
                        @error('alamat')<div class="pse-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Kendaraan (Green) --}}
        <div class="pse-card green">
            <div class="pse-card-hdr">
                <div class="pse-card-ico">🚗</div>
                <div class="pse-card-title">Informasi Kendaraan</div>
            </div>
            <div class="pse-card-body">
                <div class="pse-fg">
                    <label class="pse-lbl">Tugaskan Kendaraan <span class="pse-opt">(Opsional)</span></label>
                    <select name="vehicle_id" class="pse-sel @error('vehicle_id') is-invalid @enderror">
                        <option value="">-- Pilih dari Data Kendaraan --</option>
                        @php $currentVehicleId = $sales->vehicle?->id; @endphp
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ old('vehicle_id', $currentVehicleId) == $v->id ? 'selected' : '' }}>
                                {{ strtoupper($v->license_plate) }}@if($v->type) · {{ $v->type }}@endif
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<div class="pse-err">{{ $message }}</div>@enderror
                    <div class="pse-hint">Data diambil dari menu Operasional → Data Kendaraan</div>
                </div>
            </div>
        </div>

        {{-- Card 3: Status (Rose) --}}
        <div class="pse-card rose">
            <div class="pse-card-hdr">
                <div class="pse-card-ico">⚡</div>
                <div class="pse-card-title">Status Akun</div>
            </div>
            <div class="pse-card-body">
                <div class="pse-status-row">
                    <div class="pse-status-opt">
                        <input type="radio" name="status" value="aktif" id="status_aktif" {{ old('status', $sales->status) === 'aktif' ? 'checked' : '' }}>
                        <label for="status_aktif" class="aktif">✅ Aktif</label>
                    </div>
                    <div class="pse-status-opt">
                        <input type="radio" name="status" value="nonaktif" id="status_nonaktif" {{ old('status', $sales->status) === 'nonaktif' ? 'checked' : '' }}>
                        <label for="status_nonaktif" class="nonaktif">🚫 Nonaktif</label>
                    </div>
                </div>
                @error('status')<div class="pse-err">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Card 5: Keterangan (Purple) --}}
        <div class="pse-card purple">
            <div class="pse-card-hdr">
                <div class="pse-card-ico">📝</div>
                <div class="pse-card-title">Keterangan</div>
            </div>
            <div class="pse-card-body">
                <div class="pse-fg">
                    <label class="pse-lbl">Catatan Tambahan <span class="pse-opt">(Opsional)</span></label>
                    <textarea name="keterangan" rows="2" class="pse-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan...">{{ old('keterangan', $sales->keterangan) }}</textarea>
                    @error('keterangan')<div class="pse-err">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pse-actions">
            <a href="{{ route('pasgar.sales.index') }}" class="pse-btn pse-btn-ghost">Batal</a>
            <button type="submit" class="pse-btn pse-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
