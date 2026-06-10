@extends('layouts.app', ['title' => 'Tambah Pelanggan Pasgar'])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .plc-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    /* Breadcrumb */
    .plc-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
    .plc-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
    .plc-back:hover { color:#4f46e5; }
    .plc-sep { color:#cbd5e1; font-size:0.8125rem; }
    .plc-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    /* Form Card */
    .plc-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
    .plc-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
    .plc-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.1rem; }
    .plc-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
    .plc-card-body { padding:1.375rem; }

    .plc-card.indigo .plc-card-hdr  { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .plc-card.indigo .plc-card-ico  { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }
    .plc-card.green .plc-card-hdr   { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .plc-card.green .plc-card-ico   { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .plc-card.blue .plc-card-hdr    { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .plc-card.blue .plc-card-ico    { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
    .plc-card.pink .plc-card-hdr    { background:linear-gradient(135deg,#fdf2f8,#fce7f3); }
    .plc-card.pink .plc-card-ico    { background:linear-gradient(135deg,#ec4899,#db2777); color:#fff; }
    .plc-card.amber .plc-card-hdr   { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .plc-card.amber .plc-card-ico   { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }

    /* Form fields */
    .plc-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
    .plc-full { grid-column:1 / -1; }
    .plc-fg { display:flex; flex-direction:column; gap:0.375rem; }
    .plc-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .plc-req { color:#ef4444; }
    .plc-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
    .plc-inp, .plc-txt, .plc-sel {
        width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
        background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
        transition:all 0.2s; outline:none;
    }
    .plc-inp:focus, .plc-txt:focus, .plc-sel:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .plc-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
    .plc-txt { resize:vertical; min-height:80px; line-height:1.5; }
    .plc-inp::placeholder, .plc-txt::placeholder { color:#cbd5e1; }
    .plc-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
    .plc-hint { font-size:0.72rem; color:#94a3b8; margin-top:0.25rem; }

    /* Actions */
    .plc-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
    .plc-btn {
        display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
        font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
        border:1px solid transparent; text-decoration:none; font-family:inherit;
    }
    .plc-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
    .plc-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
    .plc-btn-primary {
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 4px 14px rgba(79,70,229,0.3);
    }
    .plc-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(79,70,229,0.4); }

    @media(max-width:640px) { .plc-grid2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="plc-page">

    {{-- Breadcrumb --}}
    <nav class="plc-nav">
        <a href="{{ route('pasgar.pelanggan.index') }}" class="plc-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Daftar Pelanggan
        </a>
        <span class="plc-sep">/</span>
        <span class="plc-crumb">Tambah Baru</span>
    </nav>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pasgar.pelanggan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Card 1: Info Toko (Indigo) --}}
        <div class="plc-card indigo">
            <div class="plc-card-hdr">
                <div class="plc-card-ico">🏪</div>
                <div class="plc-card-title">Informasi Toko</div>
            </div>
            <div class="plc-card-body">
                <div class="plc-grid2">
                    <div class="plc-fg">
                        <label class="plc-lbl">Nama Toko <span class="plc-req">*</span></label>
                        <input type="text" name="nama_toko" class="plc-inp" value="{{ old('nama_toko') }}" required placeholder="Nama toko / warung">
                        @error('nama_toko') <span class="plc-err">{{ $message }}</span> @enderror
                    </div>
                    <div class="plc-fg">
                        <label class="plc-lbl">Nama Pemilik <span class="plc-req">*</span></label>
                        <input type="text" name="nama_pemilik" class="plc-inp" value="{{ old('nama_pemilik') }}" required placeholder="Nama pemilik toko">
                        @error('nama_pemilik') <span class="plc-err">{{ $message }}</span> @enderror
                    </div>
                    <div class="plc-fg">
                        <label class="plc-lbl">Tipe Pelanggan <span class="plc-req">*</span></label>
                        <select name="tipe" class="plc-sel" required>
                            <option value="warung" {{ old('tipe') === 'warung' ? 'selected' : '' }}>Warung</option>
                            <option value="toko" {{ old('tipe') === 'toko' ? 'selected' : '' }}>Toko</option>
                            <option value="kios" {{ old('tipe') === 'kios' ? 'selected' : '' }}>Kios</option>
                        </select>
                        @error('tipe') <span class="plc-err">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Card 2: Kontak (Green) --}}
        <div class="plc-card green">
            <div class="plc-card-hdr">
                <div class="plc-card-ico">📞</div>
                <div class="plc-card-title">Kontak</div>
            </div>
            <div class="plc-card-body">
                <div class="plc-grid2">
                    <div class="plc-fg">
                        <label class="plc-lbl">No WhatsApp / HP <span class="plc-opt">(Opsional)</span></label>
                        <input type="text" name="no_hp" class="plc-inp mono" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="plc-fg">
                        <label class="plc-lbl">Email <span class="plc-opt">(Opsional)</span></label>
                        <input type="email" name="email" class="plc-inp" value="{{ old('email') }}" placeholder="email@example.com">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Alamat (Blue) --}}
        <div class="plc-card blue">
            <div class="plc-card-hdr">
                <div class="plc-card-ico">📍</div>
                <div class="plc-card-title">Alamat</div>
            </div>
            <div class="plc-card-body">
                <div class="plc-grid2">
                    <div class="plc-fg plc-full">
                        <label class="plc-lbl">Alamat Lengkap <span class="plc-opt">(Opsional)</span></label>
                        <textarea name="alamat" class="plc-txt" rows="2" placeholder="Alamat lengkap toko...">{{ old('alamat') }}</textarea>
                    </div>
                    <div class="plc-fg">
                        <label class="plc-lbl">Kecamatan <span class="plc-opt">(Opsional)</span></label>
                        <input type="text" name="kecamatan" class="plc-inp" value="{{ old('kecamatan') }}" placeholder="Nama kecamatan">
                    </div>
                    <div class="plc-fg">
                        <label class="plc-lbl">Kota <span class="plc-opt">(Opsional)</span></label>
                        <input type="text" name="kota" class="plc-inp" value="{{ old('kota') }}" placeholder="Nama kota">
                    </div>
                    <div class="plc-fg">
                        <label class="plc-lbl">Latitude <span class="plc-opt">(Opsional)</span></label>
                        <input type="text" name="latitude" class="plc-inp mono" value="{{ old('latitude') }}" placeholder="-6.2088">
                    </div>
                    <div class="plc-fg">
                        <label class="plc-lbl">Longitude <span class="plc-opt">(Opsional)</span></label>
                        <input type="text" name="longitude" class="plc-inp mono" value="{{ old('longitude') }}" placeholder="106.8456">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Foto Toko (Pink) --}}
        <div class="plc-card pink">
            <div class="plc-card-hdr">
                <div class="plc-card-ico">📷</div>
                <div class="plc-card-title">Foto Toko</div>
            </div>
            <div class="plc-card-body">
                <div class="plc-fg">
                    <label class="plc-lbl">Upload Foto <span class="plc-opt">(Opsional)</span></label>
                    <input type="file" name="foto_toko" class="plc-inp" accept="image/jpeg,image/jpg,image/png,image/webp">
                    <div class="plc-hint">Format: JPG, PNG, WebP. Maksimal 4MB.</div>
                    @error('foto_toko') <span class="plc-err">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Card 5: Status (Amber, supervisor only) --}}
        @if(!$isSalesRole)
        <div class="plc-card amber">
            <div class="plc-card-hdr">
                <div class="plc-card-ico">⚙️</div>
                <div class="plc-card-title">Status</div>
            </div>
            <div class="plc-card-body">
                <div class="plc-fg">
                    <label class="plc-lbl">Status Pelanggan</label>
                    <select name="status" class="plc-sel">
                        <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="blacklist" {{ old('status') === 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                    </select>
                </div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="plc-actions">
            <a href="{{ route('pasgar.pelanggan.index') }}" class="plc-btn plc-btn-ghost">Batal</a>
            <button type="submit" class="plc-btn plc-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Pelanggan
            </button>
        </div>
    </form>

</div>
@endsection
