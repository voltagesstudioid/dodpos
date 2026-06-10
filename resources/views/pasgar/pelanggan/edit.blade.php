@extends('layouts.app', ['title' => 'Edit Pelanggan - ' . $pelanggan->nama_toko])

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .ple-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

    .ple-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
    .ple-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
    .ple-back:hover { color:#4f46e5; }
    .ple-sep { color:#cbd5e1; font-size:0.8125rem; }
    .ple-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

    .ple-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
    .ple-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
    .ple-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.1rem; }
    .ple-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
    .ple-card-body { padding:1.375rem; }

    .ple-card.indigo .ple-card-hdr  { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
    .ple-card.indigo .ple-card-ico  { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; }
    .ple-card.green .ple-card-hdr   { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
    .ple-card.green .ple-card-ico   { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .ple-card.blue .ple-card-hdr    { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .ple-card.blue .ple-card-ico    { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
    .ple-card.pink .ple-card-hdr    { background:linear-gradient(135deg,#fdf2f8,#fce7f3); }
    .ple-card.pink .ple-card-ico    { background:linear-gradient(135deg,#ec4899,#db2777); color:#fff; }
    .ple-card.amber .ple-card-hdr   { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .ple-card.amber .ple-card-ico   { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }

    .ple-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
    .ple-full { grid-column:1 / -1; }
    .ple-fg { display:flex; flex-direction:column; gap:0.375rem; }
    .ple-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .ple-req { color:#ef4444; }
    .ple-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
    .ple-inp, .ple-txt, .ple-sel {
        width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
        background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
        transition:all 0.2s; outline:none;
    }
    .ple-inp:focus, .ple-txt:focus, .ple-sel:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .ple-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
    .ple-txt { resize:vertical; min-height:80px; line-height:1.5; }
    .ple-inp::placeholder, .ple-txt::placeholder { color:#cbd5e1; }
    .ple-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
    .ple-hint { font-size:0.72rem; color:#94a3b8; margin-top:0.25rem; }

    .ple-foto-current { max-width:200px; border-radius:10px; border:1px solid #e2e8f0; margin-bottom:0.75rem; }

    .ple-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
    .ple-btn {
        display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
        font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
        border:1px solid transparent; text-decoration:none; font-family:inherit;
    }
    .ple-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
    .ple-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
    .ple-btn-primary {
        background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
        box-shadow:0 4px 14px rgba(79,70,229,0.3);
    }
    .ple-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(79,70,229,0.4); }

    @media(max-width:640px) { .ple-grid2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="ple-page">

    {{-- Breadcrumb --}}
    <nav class="ple-nav">
        <a href="{{ route('pasgar.pelanggan.show', $pelanggan->id) }}" class="ple-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Detail Pelanggan
        </a>
        <span class="ple-sep">/</span>
        <span class="ple-crumb">Edit</span>
    </nav>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pasgar.pelanggan.update', $pelanggan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Card 1: Info Toko (Indigo) --}}
        <div class="ple-card indigo">
            <div class="ple-card-hdr">
                <div class="ple-card-ico">🏪</div>
                <div class="ple-card-title">Informasi Toko</div>
            </div>
            <div class="ple-card-body">
                <div class="ple-grid2">
                    <div class="ple-fg">
                        <label class="ple-lbl">Nama Toko <span class="ple-req">*</span></label>
                        <input type="text" name="nama_toko" class="ple-inp" value="{{ old('nama_toko', $pelanggan->nama_toko) }}" required>
                        @error('nama_toko') <span class="ple-err">{{ $message }}</span> @enderror
                    </div>
                    <div class="ple-fg">
                        <label class="ple-lbl">Nama Pemilik <span class="ple-req">*</span></label>
                        <input type="text" name="nama_pemilik" class="ple-inp" value="{{ old('nama_pemilik', $pelanggan->nama_pemilik) }}" required>
                        @error('nama_pemilik') <span class="ple-err">{{ $message }}</span> @enderror
                    </div>
                    <div class="ple-fg">
                        <label class="ple-lbl">Tipe Pelanggan <span class="ple-req">*</span></label>
                        <select name="tipe" class="ple-sel" required>
                            <option value="warung" {{ old('tipe', $pelanggan->tipe) === 'warung' ? 'selected' : '' }}>Warung</option>
                            <option value="toko" {{ old('tipe', $pelanggan->tipe) === 'toko' ? 'selected' : '' }}>Toko</option>
                            <option value="kios" {{ old('tipe', $pelanggan->tipe) === 'kios' ? 'selected' : '' }}>Kios</option>
                        </select>
                        @error('tipe') <span class="ple-err">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Card 2: Kontak (Green) --}}
        <div class="ple-card green">
            <div class="ple-card-hdr">
                <div class="ple-card-ico">📞</div>
                <div class="ple-card-title">Kontak</div>
            </div>
            <div class="ple-card-body">
                <div class="ple-grid2">
                    <div class="ple-fg">
                        <label class="ple-lbl">No WhatsApp / HP <span class="ple-opt">(Opsional)</span></label>
                        <input type="text" name="no_hp" class="ple-inp mono" value="{{ old('no_hp', $pelanggan->no_hp) }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="ple-fg">
                        <label class="ple-lbl">Email <span class="ple-opt">(Opsional)</span></label>
                        <input type="email" name="email" class="ple-inp" value="{{ old('email', $pelanggan->email) }}" placeholder="email@example.com">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Alamat (Blue) --}}
        <div class="ple-card blue">
            <div class="ple-card-hdr">
                <div class="ple-card-ico">📍</div>
                <div class="ple-card-title">Alamat</div>
            </div>
            <div class="ple-card-body">
                <div class="ple-grid2">
                    <div class="ple-fg ple-full">
                        <label class="ple-lbl">Alamat Lengkap <span class="ple-opt">(Opsional)</span></label>
                        <textarea name="alamat" class="ple-txt" rows="2">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    </div>
                    <div class="ple-fg">
                        <label class="ple-lbl">Kecamatan <span class="ple-opt">(Opsional)</span></label>
                        <input type="text" name="kecamatan" class="ple-inp" value="{{ old('kecamatan', $pelanggan->kecamatan) }}">
                    </div>
                    <div class="ple-fg">
                        <label class="ple-lbl">Kota <span class="ple-opt">(Opsional)</span></label>
                        <input type="text" name="kota" class="ple-inp" value="{{ old('kota', $pelanggan->kota) }}">
                    </div>
                    <div class="ple-fg">
                        <label class="ple-lbl">Latitude <span class="ple-opt">(Opsional)</span></label>
                        <input type="text" name="latitude" class="ple-inp mono" value="{{ old('latitude', $pelanggan->latitude) }}" placeholder="-6.2088">
                    </div>
                    <div class="ple-fg">
                        <label class="ple-lbl">Longitude <span class="ple-opt">(Opsional)</span></label>
                        <input type="text" name="longitude" class="ple-inp mono" value="{{ old('longitude', $pelanggan->longitude) }}" placeholder="106.8456">
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Foto Toko (Pink) --}}
        <div class="ple-card pink">
            <div class="ple-card-hdr">
                <div class="ple-card-ico">📷</div>
                <div class="ple-card-title">Foto Toko</div>
            </div>
            <div class="ple-card-body">
                @if($pelanggan->foto_toko)
                <div>
                    <img src="{{ asset('storage/' . $pelanggan->foto_toko) }}" alt="Foto Toko" class="ple-foto-current">
                </div>
                @endif
                <div class="ple-fg">
                    <label class="ple-lbl">Upload Foto Baru <span class="ple-opt">(Opsional)</span></label>
                    <input type="file" name="foto_toko" class="ple-inp" accept="image/jpeg,image/jpg,image/png,image/webp">
                    <div class="ple-hint">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, WebP. Maksimal 4MB.</div>
                    @error('foto_toko') <span class="ple-err">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Card 5: Status (Amber, supervisor only) --}}
        @if(!$isSalesRole)
        <div class="ple-card amber">
            <div class="ple-card-hdr">
                <div class="ple-card-ico">⚙️</div>
                <div class="ple-card-title">Status</div>
            </div>
            <div class="ple-card-body">
                <div class="ple-fg">
                    <label class="ple-lbl">Status Pelanggan</label>
                    <select name="status" class="ple-sel">
                        <option value="aktif" {{ old('status', $pelanggan->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $pelanggan->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="blacklist" {{ old('status', $pelanggan->status) === 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                    </select>
                </div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="ple-actions">
            <a href="{{ route('pasgar.pelanggan.show', $pelanggan->id) }}" class="ple-btn ple-btn-ghost">Batal</a>
            <button type="submit" class="ple-btn ple-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
