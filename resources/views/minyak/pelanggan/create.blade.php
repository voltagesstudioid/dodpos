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
                                <label class="pcf-lbl">Alamat <span class="pcf-req">*</span></label>
                                <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" class="pcf-input" required>{{ old('alamat') }}</textarea>
                                @error('alamat')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="pcf-lbl">Kecamatan <span class="pcf-req">*</span></label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" placeholder="Nama kecamatan" class="pcf-input" required list="list-kecamatan">
                                <datalist id="list-kecamatan">
                                    @forelse($kecamatanList as $kec)
                                        <option value="{{ $kec }}">
                                    @empty
                                        <option value="Cileungsi">
                                        <option value="Gunung Putri">
                                        <option value="Citeureup">
                                        <option value="Bojong Gede">
                                        <option value="Sukmajaya">
                                        <option value="Cimanggis">
                                        <option value="Beji">
                                        <option value="Pancoran Mas">
                                        <option value="Sawangan">
                                        <option value="Limo">
                                        <option value="Cinere">
                                        <option value="Kebayoran Baru">
                                        <option value="Kebayoran Lama">
                                        <option value="Cilandak">
                                        <option value="Pasar Minggu">
                                        <option value="Jagakarsa">
                                        <option value="Tebet">
                                        <option value="Setiabudi">
                                        <option value="Menteng">
                                        <option value="Tanah Abang">
                                        <option value="Grogol Petamburan">
                                        <option value="Kembangan">
                                        <option value="Kebon Jeruk">
                                        <option value="Palmerah">
                                        <option value="Tambora">
                                        <option value="Taman Sari">
                                        <option value="Cengkareng">
                                        <option value="Kalideres">
                                    @endforelse
                                </datalist>
                            </div>
                            <div>
                                <label class="pcf-lbl">Kota <span class="pcf-req">*</span></label>
                                <input type="text" name="kota" value="{{ old('kota') }}" placeholder="Nama kota" class="pcf-input" required list="list-kota">
                                <datalist id="list-kota">
                                    @forelse($kotaList as $kota)
                                        <option value="{{ $kota }}">
                                    @empty
                                        <option value="Jakarta Pusat">
                                        <option value="Jakarta Utara">
                                        <option value="Jakarta Barat">
                                        <option value="Jakarta Selatan">
                                        <option value="Jakarta Timur">
                                        <option value="Bogor">
                                        <option value="Depok">
                                        <option value="Tangerang">
                                        <option value="Tangerang Selatan">
                                        <option value="Bekasi">
                                        <option value="Bandung">
                                        <option value="Cimahi">
                                        <option value="Cirebon">
                                        <option value="Semarang">
                                        <option value="Surakarta">
                                        <option value="Yogyakarta">
                                        <option value="Surabaya">
                                        <option value="Malang">
                                        <option value="Sidoarjo">
                                        <option value="Gresik">
                                        <option value="Denpasar">
                                        <option value="Medan">
                                        <option value="Palembang">
                                        <option value="Makassar">
                                        <option value="Manado">
                                        <option value="Batam">
                                        <option value="Pekanbaru">
                                        <option value="Padang">
                                        <option value="Bandar Lampung">
                                        <option value="Pontianak">
                                        <option value="Banjarmasin">
                                        <option value="Balikpapan">
                                        <option value="Samarinda">
                                        <option value="Mataram">
                                        <option value="Kupang">
                                        <option value="Ambon">
                                        <option value="Jayapura">
                                    @endforelse
                                </datalist>
                            </div>
                            <div>
                                <label class="pcf-lbl">Provinsi</label>
                                <select name="provinsi" class="pcf-input">
                                    <option value="">-- Pilih Provinsi --</option>
                                    <option value="Aceh" {{ old('provinsi') == 'Aceh' ? 'selected' : '' }}>Aceh</option>
                                    <option value="Sumatera Utara" {{ old('provinsi') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                                    <option value="Sumatera Barat" {{ old('provinsi') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                                    <option value="Riau" {{ old('provinsi') == 'Riau' ? 'selected' : '' }}>Riau</option>
                                    <option value="Kepulauan Riau" {{ old('provinsi') == 'Kepulauan Riau' ? 'selected' : '' }}>Kepulauan Riau</option>
                                    <option value="Jambi" {{ old('provinsi') == 'Jambi' ? 'selected' : '' }}>Jambi</option>
                                    <option value="Sumatera Selatan" {{ old('provinsi') == 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                                    <option value="Bangka Belitung" {{ old('provinsi') == 'Bangka Belitung' ? 'selected' : '' }}>Bangka Belitung</option>
                                    <option value="Bengkulu" {{ old('provinsi') == 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                                    <option value="Lampung" {{ old('provinsi') == 'Lampung' ? 'selected' : '' }}>Lampung</option>
                                    <option value="Banten" {{ old('provinsi') == 'Banten' ? 'selected' : '' }}>Banten</option>
                                    <option value="DKI Jakarta" {{ old('provinsi') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                    <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                    <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                    <option value="DI Yogyakarta" {{ old('provinsi') == 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                                    <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                    <option value="Bali" {{ old('provinsi') == 'Bali' ? 'selected' : '' }}>Bali</option>
                                    <option value="Nusa Tenggara Barat" {{ old('provinsi') == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                                    <option value="Nusa Tenggara Timur" {{ old('provinsi') == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                                    <option value="Kalimantan Barat" {{ old('provinsi') == 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                                    <option value="Kalimantan Tengah" {{ old('provinsi') == 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                                    <option value="Kalimantan Selatan" {{ old('provinsi') == 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                                    <option value="Kalimantan Timur" {{ old('provinsi') == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                    <option value="Kalimantan Utara" {{ old('provinsi') == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                                    <option value="Sulawesi Utara" {{ old('provinsi') == 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                                    <option value="Sulawesi Tengah" {{ old('provinsi') == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                                    <option value="Sulawesi Selatan" {{ old('provinsi') == 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                                    <option value="Sulawesi Tenggara" {{ old('provinsi') == 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                                    <option value="Gorontalo" {{ old('provinsi') == 'Gorontalo' ? 'selected' : '' }}>Gorontalo</option>
                                    <option value="Sulawesi Barat" {{ old('provinsi') == 'Sulawesi Barat' ? 'selected' : '' }}>Sulawesi Barat</option>
                                    <option value="Maluku" {{ old('provinsi') == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                                    <option value="Maluku Utara" {{ old('provinsi') == 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>
                                    <option value="Papua" {{ old('provinsi') == 'Papua' ? 'selected' : '' }}>Papua</option>
                                    <option value="Papua Barat" {{ old('provinsi') == 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                                    <option value="Papua Selatan" {{ old('provinsi') == 'Papua Selatan' ? 'selected' : '' }}>Papua Selatan</option>
                                    <option value="Papua Tengah" {{ old('provinsi') == 'Papua Tengah' ? 'selected' : '' }}>Papua Tengah</option>
                                    <option value="Papua Pegunungan" {{ old('provinsi') == 'Papua Pegunungan' ? 'selected' : '' }}>Papua Pegunungan</option>
                                    <option value="Papua Barat Daya" {{ old('provinsi') == 'Papua Barat Daya' ? 'selected' : '' }}>Papua Barat Daya</option>
                                </select>
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
                            <span id="gps-text" style="font-size:0.8125rem; font-weight:600; color:#64748b;">Klik tombol "Deteksi Lokasi Saya" untuk mengambil koordinat</span>
                        </div>
                        <div class="pcf-grid">
                            <div>
                                <label class="pcf-lbl">Latitude</label>
                                <input type="text" name="latitude" id="inp-lat" value="{{ old('latitude') }}" placeholder="-6.20880000" class="pcf-input" readonly>
                            </div>
                            <div>
                                <label class="pcf-lbl">Longitude</label>
                                <input type="text" name="longitude" id="inp-lng" value="{{ old('longitude') }}" placeholder="106.84560000" class="pcf-input" readonly>
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
                            📱 Pastikan GPS/lokasi di HP Anda aktif. Koordinat ini akan digunakan untuk memverifikasi sales berada di lokasi toko saat melakukan penjualan.<br>
                            ⚠️ Jika akses dari HP tidak berfungsi, akses website menggunakan <strong>HTTPS</strong> (bukan HTTP) atau aktifkan lokasi di pengaturan browser.
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
                            <div class="pcf-card-hdr-desc">@if($isSalesRole)Wajib: untuk penilaian limit oleh supervisor @else Opsional @endif</div>
                        </div>
                    </div>
                    <div class="pcf-card-body">
                        <input type="file" name="foto_toko" id="inp-foto-depan" accept="image/*" style="display:none;">
                        <input type="file" name="foto_toko_dalam" id="inp-foto-dalam" accept="image/*" style="display:none;">
                        <div class="pcf-grid" style="gap:1rem;">
                            <div>
                                <label class="pcf-lbl">Tampak Depan</label>
                                <div id="preview-depan-wrap" style="text-align:center; margin-bottom:0.5rem;">
                                    <img id="preview-depan" src="" alt="Preview Depan" style="display:none; max-width:100%; max-height:200px; border-radius:10px; border:2px solid #e2e8f0;">
                                </div>
                                <button type="button" class="pcf-btn btn-camera" data-target="inp-foto-depan" data-preview="preview-depan" style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.75rem 1rem; border-radius:10px; border:2px dashed #f9a8d4; background:#fdf2f8; cursor:pointer; width:100%; font-size:0.75rem; font-weight:600; color:#be185d; font-family:inherit;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    📷 Buka Kamera
                                </button>
                                @error('foto_toko')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="pcf-lbl">Tampak Dalam</label>
                                <div id="preview-dalam-wrap" style="text-align:center; margin-bottom:0.5rem;">
                                    <img id="preview-dalam" src="" alt="Preview Dalam" style="display:none; max-width:100%; max-height:200px; border-radius:10px; border:2px solid #e2e8f0;">
                                </div>
                                <button type="button" class="pcf-btn btn-camera" data-target="inp-foto-dalam" data-preview="preview-dalam" style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.75rem 1rem; border-radius:10px; border:2px dashed #f9a8d4; background:#fdf2f8; cursor:pointer; width:100%; font-size:0.75rem; font-weight:600; color:#be185d; font-family:inherit;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    📷 Buka Kamera
                                </button>
                                @error('foto_toko_dalam')<div class="pcf-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                        @if($isSalesRole)
                        <div style="font-size:0.75rem; color:#be185d; font-weight:600; margin-top:0.75rem;">⚠️ Kedua foto wajib diisi untuk penilaian limit oleh supervisor</div>
                        @endif
                    </div>
                </div>

                {{-- Camera Modal --}}
                <div id="camera-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.95);">
                    <div style="position:relative; width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:1rem;">
                        <video id="camera-video" autoplay playsinline style="max-width:100%; max-height:75vh; border-radius:16px; object-fit:contain;"></video>
                        <div style="display:flex; gap:1rem; margin-top:1.25rem; flex-wrap:wrap; justify-content:center;">
                            <button type="button" id="btn-camera-capture" style="padding:0.875rem 2.5rem; border-radius:50px; border:none; background:linear-gradient(135deg,#f59e0b,#ea580c); color:#fff; font-weight:700; font-size:0.9375rem; cursor:pointer; box-shadow:0 4px 20px rgba(234,88,12,0.4);">📸 Ambil Foto</button>
                            <button type="button" id="btn-camera-close" style="padding:0.875rem 2rem; border-radius:50px; border:none; background:rgba(255,255,255,0.15); color:#fff; font-weight:600; font-size:0.9375rem; cursor:pointer;">✕ Tutup</button>
                        </div>
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
                            <input type="text" inputmode="numeric" name="limit_hutang" value="{{ old('limit_hutang') }}" placeholder="0" class="pcf-input" data-currency>
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
    // Camera capture via MediaDevices API
    (function() {
        var modal = document.getElementById('camera-modal');
        var video = document.getElementById('camera-video');
        var btnCapture = document.getElementById('btn-camera-capture');
        var btnClose = document.getElementById('btn-camera-close');
        var canvas = document.createElement('canvas');
        var stream = null;
        var currentInput = null;
        var currentPreview = null;

        document.querySelectorAll('.btn-camera').forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentInput = document.getElementById(this.dataset.target);
                currentPreview = document.getElementById(this.dataset.preview);
                openCamera();
            });
        });

        function openCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Browser tidak mendukung kamera. Gunakan HP dengan browser terbaru.');
                return;
            }
            modal.style.display = 'block';
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            })
            .then(function(s) {
                stream = s;
                video.srcObject = s;
                video.play();
            })
            .catch(function(err) {
                alert('Tidak dapat mengakses kamera: ' + err.message + '\nPastikan akses HTTPS dan izinkan kamera.');
                modal.style.display = 'none';
            });
        }

        function closeCamera() {
            if (stream) {
                stream.getTracks().forEach(function(t) { t.stop(); });
                stream = null;
            }
            video.srcObject = null;
            modal.style.display = 'none';
        }

        btnCapture.addEventListener('click', function() {
            if (!stream || !video.videoWidth) return;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            canvas.toBlob(function(blob) {
                var file = new File([blob], 'foto_' + Date.now() + '.jpg', { type: 'image/jpeg' });
                var dt = new DataTransfer();
                dt.items.add(file);
                currentInput.files = dt.files;
                var reader = new FileReader();
                reader.onload = function(ev) {
                    currentPreview.src = ev.target.result;
                    currentPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
                closeCamera();
            }, 'image/jpeg', 0.85);
        });

        btnClose.addEventListener('click', closeCamera);

        // Close on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeCamera();
        });
    })();

    // GPS Detection
    (function() {
        var inpLat = document.getElementById('inp-lat');
        var inpLng = document.getElementById('inp-lng');
        var gpsDot = document.getElementById('gps-dot');
        var gpsText = document.getElementById('gps-text');
        var btnDetect = document.getElementById('btn-detect-gps');
        var btnClear = document.getElementById('btn-clear-gps');
        var watchId = null;

        // Show initial info (no auto-detect)
        gpsDot.style.background = '#94a3b8';
        gpsText.textContent = 'Klik "Deteksi Lokasi Saya" untuk mengambil koordinat.';
        gpsText.style.color = '#64748b';

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

        function getErrorMessage(err) {
            switch(err.code) {
                case err.PERMISSION_DENIED:
                    return 'Akses lokasi ditolak. Izinkan akses lokasi di pengaturan browser Anda.';
                case err.POSITION_UNAVAILABLE:
                    return 'Informasi lokasi tidak tersedia. Pastikan GPS aktif.';
                case err.TIMEOUT:
                    return 'Waktu deteksi lokasi habis. Coba lagi.';
                default:
                    return 'Gagal mendeteksi lokasi (' + err.message + ').';
            }
        }

        function detectGPS() {
            if (!navigator.geolocation) {
                setStatus('error', 'Browser tidak mendukung GPS.');
                return;
            }

            // Clear any existing watch
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }

            setStatus('loading', 'Mendeteksi lokasi... Izinkan akses lokasi jika diminta.');

            // Use watchPosition for more reliable detection
            watchId = navigator.geolocation.watchPosition(
                function(pos) {
                    inpLat.value = pos.coords.latitude.toFixed(8);
                    inpLng.value = pos.coords.longitude.toFixed(8);
                    setStatus('success', 'Lokasi terdeteksi: ' + pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6));
                    // Stop watching after getting a fix
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                },
                function(err) {
                    setStatus('error', getErrorMessage(err));
                    watchId = null;
                },
                { enableHighAccuracy: true, timeout: 30000, maximumAge: 60000 }
            );

            // Fallback timeout: if watchPosition doesn't respond, try getCurrentPosition
            setTimeout(function() {
                if (watchId !== null && !inpLat.value) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                    navigator.geolocation.getCurrentPosition(
                        function(pos) {
                            inpLat.value = pos.coords.latitude.toFixed(8);
                            inpLng.value = pos.coords.longitude.toFixed(8);
                            setStatus('success', 'Lokasi terdeteksi: ' + pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6));
                        },
                        function(err) {
                            setStatus('error', getErrorMessage(err) + ' Tips: Pastikan GPS HP aktif & browser diizinkan akses lokasi.');
                        },
                        { enableHighAccuracy: false, timeout: 15000, maximumAge: 300000 }
                    );
                }
            }, 10000);
        }

        btnDetect.addEventListener('click', detectGPS);
        btnClear.addEventListener('click', function() {
            inpLat.value = '';
            inpLng.value = '';
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }
            setStatus('', 'Koordinat direset. Klik "Deteksi Lokasi Saya" untuk mengambil ulang.');
        });

        // No auto-detect — user must tap button
    })();
    </script>
    @endpush
</x-app-layout>
