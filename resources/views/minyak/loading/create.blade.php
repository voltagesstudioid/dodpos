<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .lc-page { max-width:44rem; margin:0 auto; padding:1.5rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Back link */
        .lc-back { display:inline-flex; align-items:center; gap:6px; font-size:0.8125rem; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1.25rem; transition:all .2s; }
        .lc-back:hover { color:#334155; }
        .lc-back:hover svg { transform:translateX(-3px); }
        .lc-back svg { transition:transform .2s; }

        /* Header */
        .lc-hdr {
            background:linear-gradient(135deg,#92400e 0%,#d97706 40%,#f59e0b 100%);
            border-radius:20px; padding:1.75rem; margin-bottom:1.5rem;
            box-shadow:0 12px 40px rgba(217,119,6,0.25); position:relative; overflow:hidden;
        }
        .lc-hdr::after { content:''; position:absolute; top:-40px; right:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,0.07); }
        .lc-hdr::before { content:''; position:absolute; bottom:-60px; left:-20px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.04); }
        .lc-hdr-icon { font-size:2rem; margin-bottom:0.5rem; position:relative; z-index:1; }
        .lc-hdr-title { font-size:1.375rem; font-weight:800; color:#fff; letter-spacing:-0.03em; position:relative; z-index:1; }
        .lc-hdr-sub { font-size:0.8125rem; color:rgba(255,255,255,0.75); margin-top:0.25rem; position:relative; z-index:1; }

        /* Card */
        .lc-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px;
            margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lc-card-hdr {
            padding:1rem 1.25rem; border-bottom:1px solid #fef9e7;
            display:flex; align-items:center; gap:0.625rem;
            background:linear-gradient(180deg,#fffbeb,#fffdf8);
        }
        .lc-card-ico {
            width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center;
            font-size:0.9rem; flex-shrink:0;
        }
        .lc-card-ico.amber { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; }
        .lc-card-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#065f46; }
        .lc-card-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1e40af; }
        .lc-card-lbl { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .lc-card-body { padding:1.25rem; }

        /* Fields */
        .lc-fld { margin-bottom:1.125rem; }
        .lc-fld:last-child { margin-bottom:0; }
        .lc-lbl { display:block; font-size:0.6875rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.5rem; }
        .lc-lbl .req { color:#ef4444; margin-left:2px; }

        .lc-input {
            width:100%; padding:0.75rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit;
        }
        .lc-input:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .lc-input::placeholder { color:#94a3b8; }

        .lc-sel {
            width:100%; padding:0.75rem 2.5rem 0.75rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.625rem center; background-size:16px;
        }
        .lc-sel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }

        .lc-textarea {
            width:100%; padding:0.75rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; resize:vertical; min-height:4rem;
        }
        .lc-textarea:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
        .lc-textarea::placeholder { color:#94a3b8; }

        /* Number input with suffix */
        .lc-num-wrap { position:relative; }
        .lc-num-wrap .lc-input { padding-right:3.5rem; }
        .lc-num-suffix {
            position:absolute; right:0; top:0; bottom:0; width:3.5rem;
            display:flex; align-items:center; justify-content:center;
            font-size:0.75rem; font-weight:700; color:#92400e;
            background:linear-gradient(180deg,#fffbeb,#fef3c7); border-left:1.5px solid #fde68a;
            border-radius:0 10px 10px 0; pointer-events:none;
        }

        /* Product info row */
        .lc-prod-info {
            display:flex; align-items:center; gap:0.5rem; padding:0.625rem 0.875rem;
            border-radius:8px; margin-top:0.5rem; font-size:0.75rem; font-weight:600;
            background:#f8fafc; border:1px solid #e2e8f0; color:#64748b;
            transition:all 0.2s;
        }
        .lc-prod-info.active { background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
        .lc-prod-info svg { flex-shrink:0; }

        /* Grid */
        .lc-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        @media(max-width:640px) { .lc-grid { grid-template-columns:1fr; } }

        /* Error */
        .lc-err { font-size:0.75rem; color:#ef4444; margin-top:0.375rem; font-weight:500; display:flex; align-items:center; gap:4px; }

        /* Submit Bar */
        .lc-bar {
            display:flex; gap:0.75rem; margin-top:1.5rem; align-items:center;
        }
        .lc-btn-submit {
            flex:1; display:inline-flex; align-items:center; justify-content:center; gap:0.5rem;
            padding:0.9375rem 1.5rem; border-radius:14px; font-size:0.9375rem; font-weight:700;
            border:none; cursor:pointer; transition:all 0.25s; font-family:inherit;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 8px 24px rgba(217,119,6,0.3);
        }
        .lc-btn-submit:hover { transform:translateY(-2px); box-shadow:0 12px 36px rgba(217,119,6,0.4); }
        .lc-btn-submit:disabled { opacity:0.5; cursor:not-allowed; transform:none; box-shadow:none; }
        .lc-btn-cancel {
            display:inline-flex; align-items:center; justify-content:center; gap:0.375rem;
            padding:0.9375rem 1.5rem; border-radius:14px; font-size:0.9375rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#fff; color:#64748b; text-decoration:none;
        }
        .lc-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }

        /* Summary preview */
        .lc-summary {
            background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1px solid #fde68a; border-radius:12px;
            padding:1rem 1.125rem; margin-top:1rem;
        }
        .lc-summary-title { font-size:0.6875rem; font-weight:700; color:#92400e; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.5rem; }
        .lc-summary-row { display:flex; justify-content:space-between; align-items:center; padding:4px 0; font-size:0.8125rem; }
        .lc-summary-key { color:#92400e; font-weight:500; }
        .lc-summary-val { color:#78350f; font-weight:700; }

        @media(max-width:640px) { .lc-bar { flex-direction:column; } }
    </style>
    @endpush

    <div class="lc-page">
        {{-- Back --}}
        <a href="{{ route('minyak.loading.index') }}" class="lc-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Loading
        </a>

        {{-- Header --}}
        <div class="lc-hdr">
            <div class="lc-hdr-icon">🚛</div>
            <div class="lc-hdr-title">Tambah Loading Harian</div>
            <div class="lc-hdr-sub">Input muatan BBM untuk distribusi sales</div>
        </div>

        <form method="POST" action="{{ route('minyak.loading.store') }}" id="form-loading">
            @csrf

            {{-- Info Utama --}}
            <div class="lc-card">
                <div class="lc-card-hdr">
                    <div class="lc-card-ico amber">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="lc-card-lbl">Informasi Loading</div>
                </div>
                <div class="lc-card-body">
                    <div class="lc-grid">
                        <div class="lc-fld">
                            <label class="lc-lbl">Tanggal <span class="req">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="lc-input" id="fld-tanggal">
                            @error('tanggal')<div class="lc-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                        </div>
                        <div class="lc-fld">
                            <label class="lc-lbl">Sales <span class="req">*</span></label>
                            <select name="sales_id" required class="lc-sel" id="fld-sales">
                                <option value="">— Pilih Sales —</option>
                                @foreach($sales as $s)
                                    <option value="{{ $s->id }}" data-plat="{{ $s->plat_nomor ?? '-' }}" {{ old('sales_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_id')<div class="lc-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Selected Sales Info --}}
                    <div class="lc-prod-info" id="sales-info" style="display:none;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span id="sales-info-text"></span>
                    </div>
                </div>
            </div>

            {{-- Produk & Jumlah --}}
            <div class="lc-card">
                <div class="lc-card-hdr">
                    <div class="lc-card-ico green">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="lc-card-lbl">Produk & Volume</div>
                </div>
                <div class="lc-card-body">
                    <div class="lc-fld">
                        <label class="lc-lbl">Produk BBM <span class="req">*</span></label>
                        <select name="produk_id" required class="lc-sel" id="fld-produk">
                            <option value="">— Pilih Produk —</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id }}" data-stok="{{ $p->stok_gudang }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('produk_id')<div class="lc-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                    </div>

                    {{-- Stok Info --}}
                    <div class="lc-prod-info" id="stok-info" style="display:none;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span id="stok-info-text"></span>
                    </div>

                    <div class="lc-fld" style="margin-top:1rem;">
                        <label class="lc-lbl">Jumlah Loading <span class="req">*</span></label>
                        <div class="lc-num-wrap">
                            <input type="number" name="jumlah_loading" value="{{ old('jumlah_loading') }}" required min="1"
                                placeholder="Masukkan volume BBM"
                                class="lc-input" id="fld-jumlah">
                            <div class="lc-num-suffix">Liter</div>
                        </div>
                        @error('jumlah_loading')<div class="lc-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                    </div>

                    {{-- Summary --}}
                    <div class="lc-summary" id="summary-box" style="display:none;">
                        <div class="lc-summary-title">Ringkasan Loading</div>
                        <div class="lc-summary-row">
                            <span class="lc-summary-key">Produk</span>
                            <span class="lc-summary-val" id="sum-produk">-</span>
                        </div>
                        <div class="lc-summary-row">
                            <span class="lc-summary-key">Stok Gudang</span>
                            <span class="lc-summary-val" id="sum-stok">-</span>
                        </div>
                        <div class="lc-summary-row">
                            <span class="lc-summary-key">Volume Loading</span>
                            <span class="lc-summary-val" id="sum-vol">-</span>
                        </div>
                        <div class="lc-summary-row" style="border-top:1px dashed #fde68a; padding-top:6px; margin-top:2px;">
                            <span class="lc-summary-key">Sisa Stok Gudang</span>
                            <span class="lc-summary-val" id="sum-sisa">-</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="lc-card">
                <div class="lc-card-hdr">
                    <div class="lc-card-ico blue">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div class="lc-card-lbl">Catatan</div>
                </div>
                <div class="lc-card-body">
                    <textarea name="keterangan" class="lc-textarea" placeholder="Catatan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="lc-err"><svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="lc-bar">
                <a href="{{ route('minyak.loading.index') }}" class="lc-btn-cancel">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <button type="submit" class="lc-btn-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Loading
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fldSales = document.getElementById('fld-sales');
        const fldProduk = document.getElementById('fld-produk');
        const fldJumlah = document.getElementById('fld-jumlah');
        const salesInfo = document.getElementById('sales-info');
        const salesInfoText = document.getElementById('sales-info-text');
        const stokInfo = document.getElementById('stok-info');
        const stokInfoText = document.getElementById('stok-info-text');
        const summaryBox = document.getElementById('summary-box');

        let currentStok = 0;

        function updateSalesInfo() {
            const opt = fldSales.options[fldSales.selectedIndex];
            if (opt && opt.value) {
                const plat = opt.dataset.plat || '-';
                salesInfoText.textContent = 'Plat: ' + plat;
                salesInfo.style.display = 'flex';
                salesInfo.className = 'lc-prod-info active';
            } else {
                salesInfo.style.display = 'none';
            }
        }

        function updateProdukInfo() {
            const opt = fldProduk.options[fldProduk.selectedIndex];
            if (opt && opt.value) {
                currentStok = parseInt(opt.dataset.stok) || 0;
                const satuan = opt.dataset.satuan || 'Liter';
                stokInfoText.textContent = 'Stok gudang: ' + currentStok.toLocaleString('id-ID') + ' ' + satuan;
                stokInfo.style.display = 'flex';
                stokInfo.className = 'lc-prod-info active';
            } else {
                stokInfo.style.display = 'none';
                currentStok = 0;
            }
            updateSummary();
        }

        function updateSummary() {
            const jumlah = parseInt(fldJumlah.value) || 0;
            const produkOpt = fldProduk.options[fldProduk.selectedIndex];

            if (produkOpt && produkOpt.value && jumlah > 0) {
                const satuan = produkOpt.dataset.satuan || 'Liter';
                const sisa = currentStok - jumlah;
                document.getElementById('sum-produk').textContent = produkOpt.text.trim();
                document.getElementById('sum-stok').textContent = currentStok.toLocaleString('id-ID') + ' ' + satuan;
                document.getElementById('sum-vol').textContent = jumlah.toLocaleString('id-ID') + ' ' + satuan;

                const sumSisa = document.getElementById('sum-sisa');
                sumSisa.textContent = sisa.toLocaleString('id-ID') + ' ' + satuan;
                sumSisa.style.color = sisa < 0 ? '#dc2626' : '#78350f';

                summaryBox.style.display = 'block';
            } else {
                summaryBox.style.display = 'none';
            }
        }

        fldSales.addEventListener('change', updateSalesInfo);
        fldProduk.addEventListener('change', updateProdukInfo);
        fldJumlah.addEventListener('input', updateSummary);

        // Initialize on page load
        updateSalesInfo();
        updateProdukInfo();

        // Prevent double submit
        document.getElementById('form-loading').addEventListener('submit', function(e) {
            const btn = this.querySelector('.lc-btn-submit');
            btn.style.opacity = '0.7';
            btn.style.cursor = 'wait';
        });
    });
    </script>
    @endpush
</x-app-layout>
