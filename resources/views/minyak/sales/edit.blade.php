<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap');

        .mse-page { max-width:48rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .mse-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
        .mse-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .mse-back:hover { color:#ea580c; }
        .mse-sep { color:#cbd5e1; font-size:0.8125rem; }
        .mse-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }
        .mse-code { margin-left:auto; font-size:0.75rem; font-weight:700; font-family:'JetBrains Mono',monospace; color:#64748b; background:#f1f5f9; padding:0.375rem 0.75rem; border-radius:8px; border:1px solid #e2e8f0; }

        .mse-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .mse-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .mse-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .mse-card-ico svg { width:17px; height:17px; }
        .mse-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .mse-card-body { padding:1.375rem; }

        .mse-card.blue .mse-card-hdr { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .mse-card.blue .mse-card-ico { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .mse-card.green .mse-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .mse-card.green .mse-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .mse-card.purple .mse-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .mse-card.purple .mse-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        .mse-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
        .mse-full { grid-column:1 / -1; }
        .mse-fg { display:flex; flex-direction:column; gap:0.375rem; }
        .mse-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
        .mse-lbl svg { width:13px; height:13px; color:#94a3b8; }
        .mse-req { color:#ef4444; }
        .mse-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
        .mse-inp, .mse-sel, .mse-txt {
            width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
            background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
            transition:all 0.2s; outline:none;
        }
        .mse-inp:focus, .mse-sel:focus, .mse-txt:focus { border-color:#f97316; background:#fff; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .mse-txt { resize:vertical; min-height:80px; line-height:1.5; }
        .mse-inp::placeholder, .mse-txt::placeholder { color:#cbd5e1; }
        .mse-sel { appearance:none; cursor:pointer; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; background-size:16px; padding-right:2.5rem; }
        .mse-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
        .mse-inp.is-invalid, .mse-sel.is-invalid, .mse-txt.is-invalid { border-color:#fecaca; background:#fef2f2; }
        .mse-hint { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }

        .mse-vehicle-link {
            display:inline-flex; align-items:center; gap:4px; font-size:0.6875rem; font-weight:600;
            color:#2563eb; text-decoration:none; margin-top:4px;
        }
        .mse-vehicle-link:hover { color:#1d4ed8; text-decoration:underline; }

        .mse-radios { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
        .mse-radio { position:relative; }
        .mse-radio input { position:absolute; opacity:0; pointer-events:none; }
        .mse-radio-card {
            display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px;
            border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff;
        }
        .mse-radio-card:hover { border-color:#fdba74; background:#fffaf5; }
        .mse-radio input:checked ~ .mse-radio-card { border-color:#f97316; background:linear-gradient(135deg,#fff7ed,#ffedd5); box-shadow:0 2px 8px rgba(249,115,22,0.12); }
        .mse-radio-dot { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .mse-radio-dot.ok { background:#ecfdf5; }
        .mse-radio-dot.off { background:#f1f5f9; }
        .mse-radio-text { font-size:0.8125rem; font-weight:600; color:#0f172a; }
        .mse-radio-sub { font-size:0.6875rem; color:#94a3b8; font-weight:500; }

        .mse-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
        .mse-btn {
            display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
            border:1px solid transparent; text-decoration:none; font-family:inherit;
        }
        .mse-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
        .mse-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
        .mse-btn-primary { background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; box-shadow:0 4px 14px rgba(234,88,12,0.3); }
        .mse-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(234,88,12,0.4); }

        .mse-alert { padding:0.875rem 1.125rem; border-radius:12px; font-size:0.8125rem; font-weight:500; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; }
        .mse-alert-error { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }

        @media(max-width:640px) { .mse-grid2 { grid-template-columns:1fr; } .mse-radios { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="mse-page">

        {{-- Breadcrumb --}}
        <nav class="mse-nav">
            <a href="{{ route('minyak.sales.index') }}" class="mse-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Sales
            </a>
            <span class="mse-sep">/</span>
            <span class="mse-crumb">Edit Sales</span>
            <span class="mse-code">{{ $sales->kode_sales }}</span>
        </nav>

        @if($errors->any())
            <div class="mse-alert mse-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mohon periksa kembali input Anda.
            </div>
        @endif

        <form method="POST" action="{{ route('minyak.sales.update', $sales->id) }}">
            @csrf @method('PUT')

            {{-- CARD 1: Informasi Pribadi --}}
            <div class="mse-card blue">
                <div class="mse-card-hdr">
                    <div class="mse-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="mse-card-title">Informasi Pribadi</div>
                </div>
                <div class="mse-card-body">
                    <div class="mse-grid2">
                        <div class="mse-fg">
                            <label class="mse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Nama Lengkap <span class="mse-req">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama', $sales->nama) }}" required
                                class="mse-inp @error('nama') is-invalid @enderror" placeholder="Nama lengkap sales">
                            @error('nama')<div class="mse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mse-fg">
                            <label class="mse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                                No HP <span class="mse-opt">(Opsional)</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $sales->no_hp) }}"
                                class="mse-inp @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
                            @error('no_hp')<div class="mse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mse-fg">
                            <label class="mse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                Email <span class="mse-opt">(Opsional)</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $sales->email) }}"
                                class="mse-inp @error('email') is-invalid @enderror" placeholder="sales@example.com">
                            @error('email')<div class="mse-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="mse-fg mse-full">
                            <label class="mse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Alamat <span class="mse-opt">(Opsional)</span>
                            </label>
                            <textarea name="alamat" rows="2" class="mse-txt @error('alamat') is-invalid @enderror" placeholder="Alamat lengkap sales">{{ old('alamat', $sales->alamat) }}</textarea>
                            @error('alamat')<div class="mse-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: Kendaraan & Regional --}}
            <div class="mse-card green">
                <div class="mse-card-hdr">
                    <div class="mse-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div class="mse-card-title">Kendaraan & Regional</div>
                </div>
                <div class="mse-card-body">
                    <div class="mse-grid2">
                        <div class="mse-fg">
                            <label class="mse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                Tugaskan Kendaraan <span class="mse-opt">(Opsional)</span>
                            </label>
                            <select name="vehicle_id" class="mse-sel @error('vehicle_id') is-invalid @enderror">
                                <option value="">-- Pilih kendaraan --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}" {{ old('vehicle_id', $sales->vehicle_id) == $v->id ? 'selected' : '' }}>
                                        {{ strtoupper($v->license_plate) }}@if($v->type) · {{ $v->type }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')<div class="mse-err">{{ $message }}</div>@enderror
                            <a href="{{ route('operasional.kendaraan.index') }}" class="mse-vehicle-link" target="_blank">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                                Tambah kendaraan baru di Data Kendaraan
                            </a>
                        </div>

                        <div class="mse-fg">
                            <label class="mse-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Regional Kerja <span class="mse-opt">(Opsional)</span>
                            </label>
                            <select name="regional_id" class="mse-sel @error('regional_id') is-invalid @enderror">
                                <option value="">-- Pilih regional --</option>
                                @foreach($regionals as $r)
                                    <option value="{{ $r->id }}" {{ old('regional_id', $sales->regional_id) == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                                @endforeach
                            </select>
                            @error('regional_id')<div class="mse-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 3: Status --}}
            <div class="mse-card purple">
                <div class="mse-card-hdr">
                    <div class="mse-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="mse-card-title">Status Sales</div>
                </div>
                <div class="mse-card-body">
                    <div class="mse-fg">
                        <label class="mse-lbl" style="margin-bottom:0.5rem;">Status <span class="mse-req">*</span></label>
                        <div class="mse-radios">
                            <label class="mse-radio">
                                <input type="radio" name="status" value="aktif" {{ old('status', $sales->status) === 'aktif' ? 'checked' : '' }} required>
                                <div class="mse-radio-card">
                                    <div class="mse-radio-dot ok">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    </div>
                                    <div>
                                        <div class="mse-radio-text">Aktif</div>
                                        <div class="mse-radio-sub">Sales aktif bekerja</div>
                                    </div>
                                </div>
                            </label>
                            <label class="mse-radio">
                                <input type="radio" name="status" value="nonaktif" {{ old('status', $sales->status) === 'nonaktif' ? 'checked' : '' }}>
                                <div class="mse-radio-card">
                                    <div class="mse-radio-dot off">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    </div>
                                    <div>
                                        <div class="mse-radio-text">Nonaktif</div>
                                        <div class="mse-radio-sub">Cuti / berhenti</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('status')<div class="mse-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="mse-fg" style="margin-top:1.25rem;">
                        <label class="mse-lbl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Keterangan <span class="mse-opt">(Opsional)</span>
                        </label>
                        <textarea name="keterangan" rows="2" class="mse-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan...">{{ old('keterangan', $sales->keterangan) }}</textarea>
                        @error('keterangan')<div class="mse-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mse-actions">
                <a href="{{ route('minyak.sales.index') }}" class="mse-btn mse-btn-ghost">Batal</a>
                <button type="submit" class="mse-btn mse-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
