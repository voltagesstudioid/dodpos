<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .pc-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Breadcrumb */
        .pc-nav { display:flex; align-items:center; gap:8px; margin-bottom:1.75rem; }
        .pc-back { display:flex; align-items:center; gap:5px; text-decoration:none; color:#64748b; font-size:0.8125rem; font-weight:600; transition:color 0.2s; }
        .pc-back:hover { color:#ea580c; }
        .pc-sep { color:#cbd5e1; font-size:0.8125rem; }
        .pc-crumb { font-size:0.8125rem; font-weight:700; color:#0f172a; }

        /* Card */
        .pc-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .pc-card-hdr { padding:1.125rem 1.375rem; display:flex; align-items:center; gap:0.75rem; border-bottom:1px solid #f1f5f9; }
        .pc-card-ico { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .pc-card-ico svg { width:17px; height:17px; }
        .pc-card-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .pc-card-body { padding:1.375rem; }

        .pc-card.orange .pc-card-hdr { background:linear-gradient(135deg,#fff7ed,#ffedd5); }
        .pc-card.orange .pc-card-ico { background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; }
        .pc-card.green .pc-card-hdr { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); }
        .pc-card.green .pc-card-ico { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .pc-card.purple .pc-card-hdr { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .pc-card.purple .pc-card-ico { background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; }

        /* Form Fields */
        .pc-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.125rem; }
        .pc-full { grid-column:1 / -1; }
        .pc-fg { display:flex; flex-direction:column; gap:0.375rem; }
        .pc-lbl { display:flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
        .pc-lbl svg { width:13px; height:13px; color:#94a3b8; }
        .pc-req { color:#ef4444; }
        .pc-opt { color:#94a3b8; font-weight:500; text-transform:none; letter-spacing:0; font-size:0.6875rem; }
        .pc-inp, .pc-sel, .pc-txt {
            width:100%; padding:0.6875rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px;
            background:#fcfcfd; font-family:inherit; font-size:0.875rem; color:#0f172a;
            transition:all 0.2s; outline:none;
        }
        .pc-inp:focus, .pc-sel:focus, .pc-txt:focus { border-color:#f97316; background:#fff; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .pc-inp.mono { font-family:'JetBrains Mono',monospace; font-weight:600; letter-spacing:0.04em; }
        .pc-txt { resize:vertical; min-height:80px; line-height:1.5; }
        .pc-inp::placeholder, .pc-txt::placeholder { color:#cbd5e1; }
        .pc-sel { appearance:none; cursor:pointer; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; background-size:16px; padding-right:2.5rem; }
        .pc-err { color:#ef4444; font-size:0.75rem; font-weight:600; margin-top:2px; }
        .pc-inp.is-invalid, .pc-sel.is-invalid { border-color:#fecaca; background:#fef2f2; }
        .pc-hint { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }

        /* Money Input */
        .pc-money-wrap { position:relative; }
        .pc-money-prefix { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); font-size:0.8125rem; font-weight:600; color:#94a3b8; pointer-events:none; }
        .pc-money-inp { padding-left:2.75rem !important; }
        .pc-money-suffix { position:absolute; right:0.875rem; top:50%; transform:translateY(-50%); font-size:0.75rem; color:#94a3b8; pointer-events:none; }

        /* Margin Preview */
        .pc-margin-box {
            margin-top:1rem; padding:0.875rem 1rem; border-radius:12px; border:1.5px dashed #e2e8f0;
            background:#f8fafc; display:flex; align-items:center; justify-content:space-between; gap:1rem;
            transition:all 0.3s;
        }
        .pc-margin-box.positive { border-color:#a7f3d0; background:#ecfdf5; }
        .pc-margin-box.negative { border-color:#fecaca; background:#fef2f2; }
        .pc-margin-lbl { font-size:0.75rem; font-weight:600; color:#64748b; }
        .pc-margin-val { font-size:1rem; font-weight:800; }
        .pc-margin-val.positive { color:#059669; }
        .pc-margin-val.negative { color:#dc2626; }
        .pc-margin-val.neutral { color:#94a3b8; }
        .pc-margin-pct { font-size:0.6875rem; font-weight:700; padding:0.2rem 0.5rem; border-radius:6px; }
        .pc-margin-pct.positive { background:#d1fae5; color:#065f46; }
        .pc-margin-pct.negative { background:#fee2e2; color:#991b1b; }
        .pc-margin-pct.neutral { background:#f1f5f9; color:#94a3b8; }

        /* Radio Cards */
        .pc-radios { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
        .pc-radio { position:relative; }
        .pc-radio input { position:absolute; opacity:0; pointer-events:none; }
        .pc-radio-card {
            display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px;
            border:2px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff;
        }
        .pc-radio-card:hover { border-color:#fdba74; background:#fffaf5; }
        .pc-radio input:checked ~ .pc-radio-card { border-color:#f97316; background:linear-gradient(135deg,#fff7ed,#ffedd5); box-shadow:0 2px 8px rgba(249,115,22,0.12); }
        .pc-radio-dot { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .pc-radio-dot.ok { background:#ecfdf5; }
        .pc-radio-dot.off { background:#f1f5f9; }
        .pc-radio-text { font-size:0.8125rem; font-weight:600; color:#0f172a; }
        .pc-radio-sub { font-size:0.6875rem; color:#94a3b8; font-weight:500; }

        /* Actions */
        .pc-actions { display:flex; align-items:center; justify-content:flex-end; gap:0.75rem; padding-top:0.5rem; }
        .pc-btn {
            display:inline-flex; align-items:center; gap:8px; padding:0.6875rem 1.5rem; border-radius:12px;
            font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s;
            border:1px solid transparent; text-decoration:none; font-family:inherit;
        }
        .pc-btn-ghost { background:transparent; border-color:#e2e8f0; color:#64748b; }
        .pc-btn-ghost:hover { background:#f8fafc; color:#0f172a; }
        .pc-btn-primary {
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff;
            box-shadow:0 4px 14px rgba(234,88,12,0.3);
        }
        .pc-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(234,88,12,0.4); }

        /* Alert */
        .pc-alert {
            padding:0.875rem 1.125rem; border-radius:12px; font-size:0.8125rem; font-weight:500; margin-bottom:1.25rem;
            display:flex; align-items:center; gap:0.5rem;
        }
        .pc-alert-error { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }
        .pc-alert-info { background:#fffbeb; border:1px solid #fde68a; color:#92400e; }

        @media(max-width:640px) {
            .pc-grid2 { grid-template-columns:1fr; }
            .pc-radios { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="pc-page">

        {{-- Breadcrumb --}}
        <nav class="pc-nav">
            <a href="{{ route('minyak.produk.index') }}" class="pc-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Produk
            </a>
            <span class="pc-sep">/</span>
            <span class="pc-crumb">Tambah Produk Baru</span>
        </nav>

        @if($errors->any())
            <div class="pc-alert pc-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mohon periksa kembali input Anda. Ada {{ $errors->count() }} field yang perlu diperbaiki.
            </div>
        @endif

        <form method="POST" action="{{ route('minyak.produk.store') }}">
            @csrf

            {{-- CARD 1: Informasi Dasar --}}
            <div class="pc-card orange">
                <div class="pc-card-hdr">
                    <div class="pc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <div class="pc-card-title">Informasi Dasar Produk</div>
                </div>
                <div class="pc-card-body">
                    <div class="pc-grid2">
                        <div class="pc-fg pc-full">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Nama Produk <span class="pc-req">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                class="pc-inp @error('nama') is-invalid @enderror"
                                placeholder="Contoh: Pertalite, Pertamax, Solar B30, Oli Pelumas">
                            @error('nama')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                Jenis Produk <span class="pc-opt">(Opsional)</span>
                            </label>
                            <select name="jenis" class="pc-sel @error('jenis') is-invalid @enderror">
                                <option value="">Pilih jenis produk</option>
                                @foreach($jenisList as $j)
                                    <option value="{{ $j->nama }}" {{ old('jenis') == $j->nama ? 'selected' : '' }}>{{ $j->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                Satuan <span class="pc-req">*</span>
                            </label>
                            <select name="satuan" required class="pc-sel @error('satuan') is-invalid @enderror" id="satuan-select">
                                <option value="">Pilih satuan</option>
                                @foreach($satuanList as $s)
                                    <option value="{{ $s->nama }}" {{ old('satuan') == $s->nama ? 'selected' : '' }}>{{ $s->nama }}@if($s->singkatan) ({{ $s->singkatan }})@endif</option>
                                @endforeach
                            </select>
                            @error('satuan')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: Harga & Stok --}}
            <div class="pc-card green">
                <div class="pc-card-hdr">
                    <div class="pc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div class="pc-card-title">Harga & Stok</div>
                </div>
                <div class="pc-card-body">
                    <div class="pc-grid2">
                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 10h20"/></svg>
                                Harga Modal (HPP) <span class="pc-opt">(Opsional)</span>
                            </label>
                            <div class="pc-money-wrap">
                                <span class="pc-money-prefix">Rp</span>
                                <input type="number" name="harga_modal" id="harga_modal" value="{{ old('harga_modal') }}"
                                    class="pc-inp pc-money-inp @error('harga_modal') is-invalid @enderror"
                                    placeholder="0" min="0" step="100">
                            </div>
                            <div class="pc-hint">Harga beli dari supplier per <span class="satuan-text">liter</span></div>
                            @error('harga_modal')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8l-4 4-4-4M12 16V8"/></svg>
                                Harga Jual <span class="pc-req">*</span>
                            </label>
                            <div class="pc-money-wrap">
                                <span class="pc-money-prefix">Rp</span>
                                <input type="number" name="harga_jual" id="harga_jual" value="{{ old('harga_jual') }}" required
                                    class="pc-inp pc-money-inp @error('harga_jual') is-invalid @enderror"
                                    placeholder="0" min="0" step="100">
                            </div>
                            <div class="pc-hint">Harga jual ke pelanggan per <span class="satuan-text">liter</span></div>
                            @error('harga_jual')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Margin Preview --}}
                    <div class="pc-margin-box" id="margin-box">
                        <div>
                            <div class="pc-margin-lbl">Margin / Keuntungan</div>
                            <div class="pc-margin-val neutral" id="margin-val">Rp 0</div>
                        </div>
                        <div class="pc-margin-pct neutral" id="margin-pct">0%</div>
                    </div>

                    <div class="pc-grid2" style="margin-top:1.25rem;">
                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                Stok Awal Gudang <span class="pc-opt">(Opsional)</span>
                            </label>
                            <div class="pc-money-wrap">
                                <input type="number" name="stok_gudang" value="{{ old('stok_gudang', 0) }}" min="0"
                                    class="pc-inp @error('stok_gudang') is-invalid @enderror" placeholder="0">
                                <span class="pc-money-suffix satuan-text">liter</span>
                            </div>
                            <div class="pc-hint">Stok fisik yang ada di gudang saat ini</div>
                            @error('stok_gudang')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Stok Minimum
                            </label>
                            <div class="pc-money-wrap">
                                <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 100) }}" min="0"
                                    class="pc-inp @error('stok_minimum') is-invalid @enderror" placeholder="100">
                                <span class="pc-money-suffix satuan-text">liter</span>
                            </div>
                            <div class="pc-hint">Peringatan stok rendah akan muncul jika stok ≤ angka ini</div>
                            @error('stok_minimum')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 3: Status & Keterangan --}}
            <div class="pc-card purple">
                <div class="pc-card-hdr">
                    <div class="pc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="pc-card-title">Status & Keterangan</div>
                </div>
                <div class="pc-card-body">
                    <div style="display:flex;flex-direction:column;gap:1.125rem;">
                        <div class="pc-fg">
                            <label class="pc-lbl" style="margin-bottom:0.5rem;">Status Produk <span class="pc-req">*</span></label>
                            <div class="pc-radios">
                                <label class="pc-radio">
                                    <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }} required>
                                    <div class="pc-radio-card">
                                        <div class="pc-radio-dot ok">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                        <div>
                                            <div class="pc-radio-text">Aktif</div>
                                            <div class="pc-radio-sub">Produk bisa dijual</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="pc-radio">
                                    <input type="radio" name="status" value="nonaktif" {{ old('status') === 'nonaktif' ? 'checked' : '' }}>
                                    <div class="pc-radio-card">
                                        <div class="pc-radio-dot off">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        </div>
                                        <div>
                                            <div class="pc-radio-text">Nonaktif</div>
                                            <div class="pc-radio-sub">Sementara tidak dijual</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('status')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Keterangan <span class="pc-opt">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" rows="2" class="pc-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan tentang produk...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="pc-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pc-actions">
                <a href="{{ route('minyak.produk.index') }}" class="pc-btn pc-btn-ghost">Batal</a>
                <button type="submit" class="pc-btn pc-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Produk
                </button>
            </div>
        </form>

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hargaModal = document.getElementById('harga_modal');
        const hargaJual = document.getElementById('harga_jual');
        const marginBox = document.getElementById('margin-box');
        const marginVal = document.getElementById('margin-val');
        const marginPct = document.getElementById('margin-pct');
        const satuanSelect = document.getElementById('satuan-select');
        const satuanTexts = document.querySelectorAll('.satuan-text');

        function formatRp(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function updateMargin() {
            const modal = parseFloat(hargaModal.value) || 0;
            const jual = parseFloat(hargaJual.value) || 0;

            if (modal === 0 && jual === 0) {
                marginBox.className = 'pc-margin-box';
                marginVal.className = 'pc-margin-val neutral';
                marginVal.textContent = 'Rp 0';
                marginPct.className = 'pc-margin-pct neutral';
                marginPct.textContent = '0%';
                return;
            }

            const margin = jual - modal;
            const pct = modal > 0 ? ((margin / modal) * 100).toFixed(1) : 0;

            if (margin >= 0) {
                marginBox.className = 'pc-margin-box positive';
                marginVal.className = 'pc-margin-val positive';
                marginVal.textContent = '+' + formatRp(margin);
                marginPct.className = 'pc-margin-pct positive';
                marginPct.textContent = '+' + pct + '%';
            } else {
                marginBox.className = 'pc-margin-box negative';
                marginVal.className = 'pc-margin-val negative';
                marginVal.textContent = formatRp(margin);
                marginPct.className = 'pc-margin-pct negative';
                marginPct.textContent = pct + '%';
            }
        }

        function updateSatuan() {
            const s = satuanSelect.value || 'liter';
            satuanTexts.forEach(el => el.textContent = s.toLowerCase());
        }

        hargaModal.addEventListener('input', updateMargin);
        hargaJual.addEventListener('input', updateMargin);
        satuanSelect.addEventListener('change', updateSatuan);

        updateMargin();
        updateSatuan();
    });
    </script>
    @endpush
</x-app-layout>
