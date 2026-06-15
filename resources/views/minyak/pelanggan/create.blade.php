<x-app-layout>
    @push('styles')
    <style>
        .pcf-page { max-width:56rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .pcf-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.75rem; }
        .pcf-back {
            width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            background:#fff; border:1.5px solid #e2e8f0; color:#64748b; text-decoration:none; transition:all 0.2s; flex-shrink:0;
        }
        .pcf-back:hover { background:#f8fafc; border-color:#cbd5e1; color:#334155; transform:translateX(-2px); }
        .pcf-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f59e0b,#ea580c);
            box-shadow:0 8px 24px rgba(234,88,12,0.3);
        }
        .pcf-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .pcf-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* Card */
        .pcf-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden;
            box-shadow:0 2px 8px rgba(0,0,0,0.04); margin-bottom:1.25rem; transition:all 0.3s;
        }
        .pcf-card:hover { box-shadow:0 8px 28px rgba(0,0,0,0.07); }
        .pcf-card-hdr {
            padding:1.125rem 1.5rem; display:flex; align-items:center; gap:0.75rem;
            border-bottom:1px solid #f1f5f9;
        }
        .pcf-card-hdr.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .pcf-card-hdr.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .pcf-card-hdr-ico {
            width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem;
        }
        .pcf-card-hdr-ico.amber { background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; }
        .pcf-card-hdr-ico.purple { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }
        .pcf-card-hdr-title { font-size:0.9375rem; font-weight:700; color:#0f172a; }
        .pcf-card-hdr-desc { font-size:0.75rem; color:#64748b; margin-top:1px; }
        .pcf-card-body { padding:1.5rem; }

        /* Form Grid */
        .pcf-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
        .pcf-full { grid-column:1 / -1; }

        /* Labels */
        .pcf-lbl { display:flex; align-items:center; gap:0.25rem; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; margin-bottom:0.5rem; }
        .pcf-req { color:#ef4444; font-size:0.875rem; }

        /* Inputs */
        .pcf-input {
            width:100%; padding:0.7rem 1rem; border-radius:12px; border:1.5px solid #e2e8f0;
            background:#f8fafc; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit;
        }
        .pcf-input:focus { border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
        .pcf-input::placeholder { color:#94a3b8; }
        textarea.pcf-input { resize:none; min-height:80px; }
        .pcf-err { font-size:0.75rem; color:#ef4444; margin-top:0.375rem; display:flex; align-items:center; gap:0.25rem; }

        /* Money Input */
        .pcf-money-wrap { position:relative; }
        .pcf-money-prefix {
            position:absolute; left:1rem; top:50%; transform:translateY(-50%);
            font-size:0.8125rem; font-weight:700; color:#94a3b8; pointer-events:none;
        }
        .pcf-money-wrap .pcf-input { padding-left:2.75rem; }

        /* Radio Cards */
        .pcf-radios { display:flex; gap:0.75rem; }
        .pcf-radio {
            flex:1; position:relative; cursor:pointer;
            display:flex; align-items:center; gap:0.75rem;
            padding:1rem 1.125rem; border-radius:14px; border:2px solid #e2e8f0;
            background:#fafbfc; transition:all 0.2s;
        }
        .pcf-radio:hover { border-color:#cbd5e1; background:#f1f5f9; }
        .pcf-radio input { position:absolute; opacity:0; pointer-events:none; }
        .pcf-radio-dot {
            width:20px; height:20px; border-radius:50%; border:2px solid #cbd5e1;
            display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.2s;
        }
        .pcf-radio-dot::after {
            content:''; width:10px; height:10px; border-radius:50%; background:transparent; transition:all 0.2s;
        }
        .pcf-radio input:checked ~ .pcf-radio-dot { border-color:var(--rc, #f59e0b); }
        .pcf-radio input:checked ~ .pcf-radio-dot::after { background:var(--rc, #f59e0b); }
        .pcf-radio input:checked ~ .pcf-radio-body { color:#0f172a; }
        .pcf-radio.amber { --rc:#f59e0b; }
        .pcf-radio.purple { --rc:#8b5cf6; }
        .pcf-radio.orange { --rc:#ea580c; }
        .pcf-radio.green { --rc:#10b981; }
        .pcf-radio.gray { --rc:#64748b; }
        .pcf-radio.red { --rc:#ef4444; }
        .pcf-radio.blue { --rc:#3b82f6; }
        .pcf-radio.amber input:checked ~ .pcf-radio-dot { border-color:#f59e0b; }
        .pcf-radio.purple input:checked ~ .pcf-radio-dot { border-color:#8b5cf6; }
        .pcf-radio.orange input:checked ~ .pcf-radio-dot { border-color:#ea580c; }
        .pcf-radio.green input:checked ~ .pcf-radio-dot { border-color:#10b981; }
        .pcf-radio.gray input:checked ~ .pcf-radio-dot { border-color:#64748b; }
        .pcf-radio.red input:checked ~ .pcf-radio-dot { border-color:#ef4444; }
        .pcf-radio.blue input:checked ~ .pcf-radio-dot { border-color:#3b82f6; }
        .pcf-radio-body { font-size:0.8125rem; font-weight:600; color:#475569; }
        .pcf-radio-desc { font-size:0.6875rem; font-weight:400; color:#94a3b8; margin-top:1px; }

        /* Section Divider */
        .pcf-divider { height:1px; background:#f1f5f9; margin:1.25rem 0; }

        /* Help Text */
        .pcf-help { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; display:flex; align-items:center; gap:0.25rem; }

        /* Actions */
        .pcf-actions { display:flex; align-items:center; gap:1rem; margin-top:1.75rem; }
        .pcf-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.75rem 1.5rem; border-radius:12px; font-size:0.8125rem; font-weight:700;
            text-decoration:none; transition:all 0.25s cubic-bezier(0.4,0,0.2,1); border:none; cursor:pointer;
            font-family:inherit;
        }
        .pcf-btn-primary {
            background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff;
            box-shadow:0 6px 20px rgba(234,88,12,0.35);
        }
        .pcf-btn-primary:hover { transform:translateY(-2px); box-shadow:0 10px 32px rgba(234,88,12,0.45); }
        .pcf-btn-ghost {
            background:#fff; color:#64748b; border:1.5px solid #e2e8f0;
        }
        .pcf-btn-ghost:hover { background:#f8fafc; border-color:#cbd5e1; color:#334155; }

        @media(max-width:640px) {
            .pcf-grid { grid-template-columns:1fr; }
            .pcf-radios { flex-direction:column; }
            .pcf-hdr-title { font-size:1.25rem; }
            .pcf-actions { flex-direction:column; }
            .pcf-btn { width:100%; justify-content:center; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="pcf-page">

            {{-- Header --}}
            <div class="pcf-hdr">
                <a href="{{ route('minyak.pelanggan.index') }}" class="pcf-back">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div class="pcf-hdr-ico">🏪</div>
                <div>
                    <div class="pcf-hdr-title">Tambah Pelanggan</div>
                    <div class="pcf-hdr-sub">Input data pelanggan baru untuk modul minyak</div>
                </div>
            </div>

            <form method="POST" action="{{ route('minyak.pelanggan.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Informasi Toko --}}
                <div class="pcf-card">
                    <div class="pcf-card-hdr amber">
                        <div class="pcf-card-hdr-ico amber">🏪</div>
                        <div>
                            <div class="pcf-card-hdr-title">Informasi Toko</div>
                            <div class="pcf-card-hdr-desc">Data identitas toko dan pemilik</div>
                        </div>
                    </div>
                    <div class="pcf-card-body">
                        <div class="pcf-grid">
                            <div>
                                <label class="pcf-lbl">Nama Toko <span class="pcf-req">*</span></label>
                                <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" required placeholder="Masukkan nama toko" class="pcf-input">
                                @error('nama_toko')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="pcf-lbl">Nama Pemilik <span class="pcf-req">*</span></label>
                                <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required placeholder="Masukkan nama pemilik" class="pcf-input">
                                @error('nama_pemilik')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="pcf-lbl">No HP</label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" class="pcf-input">
                                @error('no_hp')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="pcf-lbl">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: toko@email.com" class="pcf-input">
                                @error('email')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                            <div class="pcf-full">
                                <label class="pcf-lbl">Alamat</label>
                                <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" class="pcf-input">{{ old('alamat') }}</textarea>
                                @error('alamat')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="pcf-lbl">Kecamatan</label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" placeholder="Nama kecamatan" class="pcf-input">
                            </div>
                            <div>
                                <label class="pcf-lbl">Kota</label>
                                <input type="text" name="kota" value="{{ old('kota') }}" placeholder="Nama kota" class="pcf-input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Koordinat Lokasi Toko --}}
                <div class="pcf-card">
                    <div class="pcf-card-hdr" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">
                        <div class="pcf-card-hdr-ico" style="background:linear-gradient(135deg,#10b981,#059669); color:#fff;">📍</div>
                        <div>
                            <div class="pcf-card-hdr-title">Koordinat Lokasi Toko</div>
                            <div class="pcf-card-hdr-desc">Wajib diisi — digunakan untuk validasi jarak saat penjualan (maks 20 meter)</div>
                        </div>
                    </div>
                    <div class="pcf-card-body">
                        {{-- GPS status --}}
                        <div id="gps-status-box" style="display:flex; align-items:center; gap:0.5rem; padding:0.75rem 1rem; border-radius:12px; background:#f1f5f9; border:1.5px solid #e2e8f0; margin-bottom:1rem;">
                            <span id="gps-dot" style="width:10px; height:10px; border-radius:50%; background:#94a3b8; flex-shrink:0;"></span>
                            <span id="gps-text" style="font-size:0.8125rem; font-weight:600; color:#64748b;">Mendeteksi lokasi...</span>
                        </div>
                        <div class="pcf-grid">
                            <div>
                                <label class="pcf-lbl">Latitude <span class="pcf-req">*</span></label>
                                <input type="text" name="latitude" id="inp-lat" value="{{ old('latitude') }}" required placeholder="-6.20880000" class="pcf-input" readonly>
                            </div>
                            <div>
                                <label class="pcf-lbl">Longitude <span class="pcf-req">*</span></label>
                                <input type="text" name="longitude" id="inp-lng" value="{{ old('longitude') }}" required placeholder="106.84560000" class="pcf-input" readonly>
                            </div>
                        </div>
                        <div style="display:flex; gap:0.75rem; margin-top:1rem; flex-wrap:wrap;">
                            <button type="button" id="btn-detect-gps" class="pcf-btn pcf-btn-primary" style="padding:0.625rem 1.25rem; font-size:0.8125rem;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Deteksi Lokasi Saya
                            </button>
                            <button type="button" id="btn-clear-gps" class="pcf-btn pcf-btn-ghost" style="padding:0.625rem 1.25rem; font-size:0.8125rem;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Reset
                            </button>
                        </div>
                        <div style="font-size:0.6875rem; color:#94a3b8; margin-top:0.75rem; line-height:1.6;">
                            📱 Pastikan GPS/lokasi di HP Anda aktif. Koordinat ini akan digunakan untuk memverifikasi sales berada di lokasi toko saat melakukan penjualan.
                        </div>
                        @error('latitude')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                        @error('longitude')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Foto Toko --}}
                <div class="pcf-card">
                    <div class="pcf-card-hdr" style="background:linear-gradient(135deg,#fce7f3,#fdf2f8);">
                        <div class="pcf-card-hdr-ico" style="background:linear-gradient(135deg,#ec4899,#db2777); color:#fff;">📸</div>
                        <div>
                            <div class="pcf-card-hdr-title">Foto Toko</div>
                            <div class="pcf-card-hdr-desc">@if($isSalesRole)Wajib: untuk penilaian limit kredit oleh supervisor @else Opsional @endif</div>
                        </div>
                    </div>
                    <div class="pcf-card-body">
                        <div id="foto-preview-wrap" style="text-align:center;">
                            <img id="foto-preview" src="" alt="Preview" style="display:none; max-width:100%; max-height:300px; border-radius:12px; border:2px solid #e2e8f0; margin-bottom:1rem;">
                        </div>
                        <div class="pcf-grid" style="gap:0.75rem;">
                            <div>
                                <label class="pcf-btn" style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.875rem 1rem; border-radius:12px; border:2px dashed #f9a8d4; background:#fdf2f8; cursor:pointer; width:100%; font-size:0.8125rem; font-weight:600; color:#be185d; transition:all 0.2s;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    📱 Kamera / Upload Foto
                                    <input type="file" name="foto_toko" id="inp-foto-toko" accept="image/*" capture="environment" style="display:none;" @if($isSalesRole) required @endif>
                                </label>
                            </div>
                            <div style="display:flex; align-items:center;">
                                <div style="font-size:0.75rem; color:#64748b; line-height:1.5;">
                                    @if($isSalesRole)
                                    <strong style="color:#be185d;">⚠️ Wajib upload foto toko</strong><br>
                                    Supervisor akan menentukan limit kredit berdasarkan kondisi toko.
                                    @else
                                    Foto toko membantu menilai kelayakan kredit pelanggan.
                                    @endif
                                </div>
                            </div>
                        </div>
                        @error('foto_toko')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Tipe & Status --}}
                <div class="pcf-card">
                    <div class="pcf-card-hdr purple">
                        <div class="pcf-card-hdr-ico purple">⚙️</div>
                        <div>
                            <div class="pcf-card-hdr-title">Tipe & Status</div>
                            <div class="pcf-card-hdr-desc">Klasifikasi dan status pelanggan</div>
                        </div>
                    </div>
                    <div class="pcf-card-body">
                        {{-- Regional --}}
                        <label class="pcf-lbl">🗺️ Regional Kerja</label>
                        <select name="regional_id" class="pcf-input" style="margin-bottom:1.25rem;">
                            <option value="">-- Pilih Regional --</option>
                            @foreach($regionals as $regional)
                                <option value="{{ $regional->id }}" {{ old('regional_id') == $regional->id ? 'selected' : '' }}>{{ $regional->nama }}</option>
                            @endforeach
                        </select>
                        @error('regional_id')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror

                        {{-- Tipe --}}
                        <label class="pcf-lbl">Tipe Pelanggan <span class="pcf-req">*</span></label>
                        <div class="pcf-radios">
                            <label class="pcf-radio blue">
                                <input type="radio" name="tipe" value="eceran" {{ old('tipe', 'eceran') == 'eceran' ? 'checked' : '' }} required>
                                <span class="pcf-radio-dot"></span>
                                <div>
                                    <div class="pcf-radio-body">Eceran</div>
                                    <div class="pcf-radio-desc">Pembelian satuan</div>
                                </div>
                            </label>
                            <label class="pcf-radio purple">
                                <input type="radio" name="tipe" value="grosir" {{ old('tipe') == 'grosir' ? 'checked' : '' }}>
                                <span class="pcf-radio-dot"></span>
                                <div>
                                    <div class="pcf-radio-body">Grosir</div>
                                    <div class="pcf-radio-desc">Partai besar</div>
                                </div>
                            </label>
                            <label class="pcf-radio orange">
                                <input type="radio" name="tipe" value="agen" {{ old('tipe') == 'agen' ? 'checked' : '' }}>
                                <span class="pcf-radio-dot"></span>
                                <div>
                                    <div class="pcf-radio-body">Agen</div>
                                    <div class="pcf-radio-desc">Agen resmi</div>
                                </div>
                            </label>
                        </div>
                        @error('tipe')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror

                        @if(! $isSalesRole)
                        <div class="pcf-divider"></div>

                        {{-- Limit Hutang --}}
                        <label class="pcf-lbl">💰 Limit Hutang (Rp)</label>
                        <div class="pcf-money-wrap">
                            <span class="pcf-money-prefix">Rp</span>
                            <input type="number" name="limit_hutang" value="{{ old('limit_hutang') }}" placeholder="0" class="pcf-input">
                        </div>
                        <div class="pcf-help">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Batas maksimal hutang yang diperbolehkan
                        </div>
                        @error('limit_hutang')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror

                        <div class="pcf-divider"></div>

                        {{-- Status --}}
                        <label class="pcf-lbl">Status <span class="pcf-req">*</span></label>
                        <div class="pcf-radios">
                            <label class="pcf-radio green">
                                <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }} required>
                                <span class="pcf-radio-dot"></span>
                                <div>
                                    <div class="pcf-radio-body">Aktif</div>
                                    <div class="pcf-radio-desc">Pelanggan aktif</div>
                                </div>
                            </label>
                            <label class="pcf-radio gray">
                                <input type="radio" name="status" value="nonaktif" {{ old('status') == 'nonaktif' ? 'checked' : '' }}>
                                <span class="pcf-radio-dot"></span>
                                <div>
                                    <div class="pcf-radio-body">Nonaktif</div>
                                    <div class="pcf-radio-desc">Sementara nonaktif</div>
                                </div>
                            </label>
                            <label class="pcf-radio red">
                                <input type="radio" name="status" value="blacklist" {{ old('status') == 'blacklist' ? 'checked' : '' }}>
                                <span class="pcf-radio-dot"></span>
                                <div>
                                    <div class="pcf-radio-body">Blacklist</div>
                                    <div class="pcf-radio-desc">Diblokir</div>
                                </div>
                            </label>
                        </div>
                        @error('status')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pcf-actions">
                    <button type="submit" class="pcf-btn pcf-btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Pelanggan
                    </button>
                    <a href="{{ route('minyak.pelanggan.index') }}" class="pcf-btn pcf-btn-ghost">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

    @push('scripts')
    <script>
    // Foto preview
    document.getElementById('inp-foto-toko').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(ev) {
            var preview = document.getElementById('foto-preview');
            preview.src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // GPS Detection
    (function() {
        var inpLat = document.getElementById('inp-lat');
        var inpLng = document.getElementById('inp-lng');
        var gpsDot = document.getElementById('gps-dot');
        var gpsText = document.getElementById('gps-text');
        var btnDetect = document.getElementById('btn-detect-gps');
        var btnClear = document.getElementById('btn-clear-gps');

        function setStatus(state, text) {
            gpsText.textContent = text;
            if (state === 'success') {
                gpsDot.style.background = '#10b981';
                gpsText.style.color = '#065f46';
            } else if (state === 'error') {
                gpsDot.style.background = '#ef4444';
                gpsText.style.color = '#991b1b';
            } else {
                gpsDot.style.background = '#f59e0b';
                gpsText.style.color = '#92400e';
            }
        }

        function detectGPS() {
            if (!navigator.geolocation) {
                setStatus('error', 'Browser tidak mendukung GPS.');
                return;
            }
            setStatus('loading', 'Mendeteksi lokasi...');
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    inpLat.value = pos.coords.latitude.toFixed(8);
                    inpLng.value = pos.coords.longitude.toFixed(8);
                    setStatus('success', 'Lokasi terdeteksi: ' + pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6));
                },
                function(err) {
                    setStatus('error', 'Gagal mendeteksi lokasi. Pastikan GPS aktif dan izinkan akses lokasi.');
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        btnDetect.addEventListener('click', detectGPS);
        btnClear.addEventListener('click', function() {
            inpLat.value = '';
            inpLng.value = '';
            setStatus('', 'Koordinat direset. Klik "Deteksi Lokasi Saya" untuk mengambil ulang.');
        });

        // Auto-detect on page load
        detectGPS();
    })();
    </script>
    @endpush
</x-app-layout>
