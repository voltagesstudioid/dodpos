<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');
        .ds-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .ds-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .ds-back:hover { color:#334155; }
        .ds-hdr { background:linear-gradient(135deg,#92400e 0%,#d97706 40%,#f59e0b 100%); border-radius:20px; padding:1.75rem; margin-bottom:1.5rem; box-shadow:0 12px 40px rgba(217,119,6,.25); position:relative; overflow:hidden; }
        .ds-hdr::after { content:''; position:absolute; top:-40px; right:-40px; width:160px; height:160px; border-radius:50%; background:rgba(255,255,255,.07); }
        .ds-hdr-ico { font-size:2rem; margin-bottom:.5rem; position:relative; z-index:1; }
        .ds-hdr-title { font-size:1.375rem; font-weight:800; color:#fff; letter-spacing:-.03em; position:relative; z-index:1; }
        .ds-hdr-sub { font-size:.8125rem; color:rgba(255,255,255,.75); margin-top:.25rem; position:relative; z-index:1; }
        .ds-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; margin-bottom:1.25rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.04); }
        .ds-card-hdr { padding:1rem 1.25rem; border-bottom:1px solid #fef3c7; display:flex; align-items:center; gap:.625rem; background:linear-gradient(180deg,#fffbeb,#fefce8); }
        .ds-card-ico { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.9rem; flex-shrink:0; }
        .ds-card-ico.amber { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; }
        .ds-card-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#065f46; }
        .ds-card-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#1e40af; }
        .ds-card-ico.red { background:linear-gradient(135deg,#fef2f2,#fecaca); color:#991b1b; }
        .ds-card-lbl { font-size:.875rem; font-weight:700; color:#0f172a; }
        .ds-card-body { padding:1.25rem; }
        .ds-fld { margin-bottom:1rem; } .ds-fld:last-child { margin-bottom:0; }
        .ds-lbl { display:flex; align-items:center; gap:4px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.5rem; }
        .ds-req { color:#ef4444; }
        .ds-inp { width:100%; padding:.75rem .875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:.875rem; color:#1e293b; outline:none; transition:all .2s; font-family:inherit; }
        .ds-inp:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,.12); }
        .ds-sel { width:100%; padding:.75rem 2.5rem .75rem .875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:.875rem; color:#1e293b; outline:none; transition:all .2s; font-family:inherit; cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right .625rem center; background-size:16px; }
        .ds-sel:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,.12); }
        .ds-ta { width:100%; padding:.75rem .875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:.875rem; color:#1e293b; outline:none; transition:all .2s; font-family:inherit; resize:vertical; min-height:3.5rem; }
        .ds-ta:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,.12); }
        .ds-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        @media(max-width:640px) { .ds-grid { grid-template-columns:1fr; } }
        .ds-stok { background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; }
        .ds-stok-lbl { font-size:11px; font-weight:700; color:#92400e; text-transform:uppercase; letter-spacing:.06em; }
        .ds-stok-val { font-size:1.5rem; font-weight:800; color:#d97706; font-family:'JetBrains Mono',monospace; }
        .ds-stok-unit { font-size:.75rem; font-weight:600; color:#fbbf24; }
        .ds-row { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1rem; margin-bottom:.75rem; position:relative; transition:all .2s; }
        .ds-row:hover { border-color:#cbd5e1; box-shadow:0 2px 8px rgba(0,0,0,.04); }
        .ds-row-num { position:absolute; top:-8px; left:12px; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; font-size:10px; font-weight:800; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(217,119,6,.3); }
        .ds-row-grid { display:grid; grid-template-columns:1fr 140px 32px; gap:.75rem; align-items:end; }
        @media(max-width:640px) { .ds-row-grid { grid-template-columns:1fr; } }
        .ds-row-remove { width:32px; height:42px; border-radius:8px; border:1.5px solid #fecaca; background:#fef2f2; color:#dc2626; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
        .ds-row-remove:hover { background:#fee2e2; border-color:#f87171; }
        .ds-row-info { display:flex; align-items:center; gap:6px; font-size:11px; color:#64748b; margin-top:6px; font-weight:500; }
        .ds-add { display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:.75rem; border-radius:12px; border:2px dashed #d97706; background:#fffbeb; color:#92400e; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; font-family:inherit; }
        .ds-add:hover { background:#fef3c7; border-color:#b45309; }
        .ds-sum { background:linear-gradient(135deg,#fffbeb,#fef3c7); border:1px solid #fde68a; border-radius:12px; padding:1.125rem 1.25rem; }
        .ds-sum-title { font-size:11px; font-weight:700; color:#92400e; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.75rem; }
        .ds-sum-row { display:flex; justify-content:space-between; align-items:center; padding:4px 0; font-size:13px; }
        .ds-sum-key { color:#92400e; font-weight:500; }
        .ds-sum-val { color:#78350f; font-weight:700; font-family:'JetBrains Mono',monospace; font-size:14px; }
        .ds-sum-val.danger { color:#dc2626; }
        .ds-sum-val.ok { color:#059669; }
        .ds-sum-divider { border-top:1px dashed #fde68a; margin:6px 0; }
        .ds-bar { display:flex; gap:.75rem; margin-top:1.5rem; }
        .ds-btn-cancel { display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:.9375rem 1.5rem; border-radius:14px; font-size:.9375rem; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all .2s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; }
        .ds-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }
        .ds-btn-submit { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:.9375rem 1.5rem; border-radius:14px; font-size:.9375rem; font-weight:700; border:none; cursor:pointer; transition:all .25s; font-family:inherit; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 8px 24px rgba(217,119,6,.3); }
        .ds-btn-submit:hover { transform:translateY(-2px); box-shadow:0 12px 36px rgba(217,119,6,.4); }
        .ds-btn-submit:disabled { opacity:.5; cursor:not-allowed; transform:none; box-shadow:none; }
        @media(max-width:640px) { .ds-bar { flex-direction:column; } }
        .ds-err { font-size:11px; color:#ef4444; margin-top:4px; font-weight:500; }
    </style>
    @endpush
    <div class="ds-page">
        <a href="{{ route('gula.loading.index') }}" class="ds-back"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Kembali</a>

        <div class="ds-hdr">
            <div class="ds-hdr-ico">🚛</div>
            <div class="ds-hdr-title">Distribusi Stok ke Sales</div>
            <div class="ds-hdr-sub">Pecah stok gudang ke beberapa sales sekaligus</div>
        </div>

        @if(session('error'))<div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:14px;font-weight:500;">{{ session('error') }}</div>@endif

        <form method="POST" action="{{ route('gula.loading.distribusi.store') }}" id="form-dist">
            @csrf

            {{-- Tanggal & Produk --}}
            <div class="ds-card">
                <div class="ds-card-hdr">
                    <div class="ds-card-ico amber"><svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                    <div class="ds-card-lbl">Pilih Produk & Tanggal</div>
                </div>
                <div class="ds-card-body">
                    <div class="ds-grid">
                        <div class="ds-fld">
                            <label class="ds-lbl">Tanggal <span class="ds-req">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="ds-inp" required>
                            @error('tanggal')<div class="ds-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="ds-fld">
                            <label class="ds-lbl">Produk Gula <span class="ds-req">*</span></label>
                            <select name="produk_id" id="sel-produk" class="ds-sel" required>
                                <option value="">— Pilih Produk —</option>
                                @foreach($produks as $p)
                                    <option value="{{ $p->id }}" data-stok="{{ $p->stok_gudang }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            @error('produk_id')<div class="ds-err">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="ds-stok" id="stok-box" style="display:none;">
                        <div><div class="ds-stok-lbl">Stok Gudang Tersedia</div><div style="font-size:11px;color:#fbbf24;margin-top:2px;" id="stok-produk-name"></div></div>
                        <div style="text-align:right;"><div class="ds-stok-val" id="stok-val">0</div><div class="ds-stok-unit" id="stok-unit">Dus</div></div>
                    </div>
                </div>
            </div>

            {{-- Sales Rows --}}
            <div class="ds-card">
                <div class="ds-card-hdr">
                    <div class="ds-card-ico green"><svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></div>
                    <div class="ds-card-lbl">Daftar Sales / Pengangkut</div>
                </div>
                <div class="ds-card-body">
                    <div id="tanker-list"></div>
                    <button type="button" class="ds-add" id="btn-add-tanker" onclick="addTankerRow()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Sales
                    </button>
                    @error('items')<div class="ds-err">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Summary --}}
            <div class="ds-card">
                <div class="ds-card-hdr">
                    <div class="ds-card-ico blue"><svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
                    <div class="ds-card-lbl">Ringkasan Distribusi</div>
                </div>
                <div class="ds-card-body">
                    <div class="ds-sum">
                        <div class="ds-sum-title">Perhitungan Stok</div>
                        <div class="ds-sum-row"><span class="ds-sum-key">Stok Gudang</span><span class="ds-sum-val" id="sum-stok">0 Dus</span></div>
                        <div class="ds-sum-row"><span class="ds-sum-key">Total Distribusi (<span id="sum-count">0</span> sales)</span><span class="ds-sum-val" id="sum-total">0 Dus</span></div>
                        <div class="ds-sum-divider"></div>
                        <div class="ds-sum-row"><span class="ds-sum-key" style="font-weight:700;">Sisa Stok Gudang</span><span class="ds-sum-val" id="sum-sisa">0 Dus</span></div>
                    </div>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="ds-card">
                <div class="ds-card-hdr">
                    <div class="ds-card-ico red"><svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
                    <div class="ds-card-lbl">Catatan (Opsional)</div>
                </div>
                <div class="ds-card-body">
                    <textarea name="keterangan" class="ds-ta" placeholder="Catatan distribusi...">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="ds-bar">
                <a href="{{ route('gula.loading.index') }}" class="ds-btn-cancel">Batal</a>
                <button type="submit" class="ds-btn-submit" id="btn-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Distribusikan Stok
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    var tankerCount = 0;
    var salesData = @json($sales->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama, 'plat' => $s->no_kendaraan ?? $s->jenis_kendaraan ?? '-']));
    var currentStok = 0;
    var currentSatuan = 'Dus';

    function fmt(n) { return Number(n).toLocaleString('id-ID'); }

    function addTankerRow() {
        tankerCount++;
        var idx = tankerCount;
        var opts = '<option value="">— Pilih Sales —</option>';
        salesData.forEach(function(s) {
            opts += '<option value="' + s.id + '">' + s.nama + (s.plat !== '-' ? ' (' + s.plat + ')' : '') + '</option>';
        });

        var html = '<div class="ds-row" id="row-' + idx + '">' +
            '<div class="ds-row-num">' + idx + '</div>' +
            '<div class="ds-row-grid">' +
                '<div class="ds-fld" style="margin-bottom:0"><label class="ds-lbl">Sales</label>' +
                '<select name="items[' + idx + '][sales_id]" class="ds-sel tanker-sel" required onchange="recalc()">' + opts + '</select></div>' +
                '<div class="ds-fld" style="margin-bottom:0"><label class="ds-lbl">Jumlah</label>' +
                '<input type="number" name="items[' + idx + '][jumlah]" class="ds-inp tanker-jumlah" min="1" step="1" placeholder="0" required oninput="recalc()"></div>' +
                '<button type="button" class="ds-row-remove" onclick="removeTankerRow(' + idx + ')" title="Hapus"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>' +
            '</div>' +
            '<div class="ds-row-info" id="info-' + idx + '"></div>' +
        '</div>';

        document.getElementById('tanker-list').insertAdjacentHTML('beforeend', html);
        renumber();
        recalc();
    }

    function removeTankerRow(idx) {
        var row = document.getElementById('row-' + idx);
        if (row) row.remove();
        renumber();
        recalc();
    }

    function renumber() {
        var rows = document.querySelectorAll('#tanker-list .ds-row');
        rows.forEach(function(r, i) {
            var num = r.querySelector('.ds-row-num');
            if (num) num.textContent = i + 1;
            var sel = r.querySelector('.tanker-sel');
            var inp = r.querySelector('.tanker-jumlah');
            if (sel) sel.name = 'items[' + (i+1) + '][sales_id]';
            if (inp) inp.name = 'items[' + (i+1) + '][jumlah]';
        });
    }

    function recalc() {
        var total = 0, count = 0;
        document.querySelectorAll('#tanker-list .ds-row').forEach(function(r) {
            var inp = r.querySelector('.tanker-jumlah');
            var sel = r.querySelector('.tanker-sel');
            var val = parseFloat(inp.value) || 0;
            if (val > 0 && sel.value) { total += val; count++; }
        });

        var sisa = currentStok - total;
        var satuan = currentSatuan;

        document.getElementById('sum-stok').textContent = fmt(currentStok) + ' ' + satuan;
        document.getElementById('sum-count').textContent = count;
        document.getElementById('sum-total').textContent = fmt(total) + ' ' + satuan;

        var sisaEl = document.getElementById('sum-sisa');
        sisaEl.textContent = fmt(sisa) + ' ' + satuan;
        sisaEl.className = 'ds-sum-val ' + (sisa < 0 ? 'danger' : 'ok');

        var btn = document.getElementById('btn-submit');
        if (sisa < 0) {
            btn.disabled = true;
            btn.title = 'Total distribusi melebihi stok gudang!';
        } else {
            btn.disabled = false;
            btn.title = '';
        }
    }

    document.getElementById('sel-produk').addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        var box = document.getElementById('stok-box');
        if (opt && opt.value) {
            currentStok = parseFloat(opt.dataset.stok) || 0;
            currentSatuan = opt.dataset.satuan || 'Dus';
            document.getElementById('stok-val').textContent = fmt(currentStok);
            document.getElementById('stok-unit').textContent = currentSatuan;
            document.getElementById('stok-produk-name').textContent = opt.text.trim();
            box.style.display = 'flex';
        } else {
            currentStok = 0;
            box.style.display = 'none';
        }
        recalc();
    });

    var initProduk = document.getElementById('sel-produk');
    if (initProduk.value) initProduk.dispatchEvent(new Event('change'));
    addTankerRow();
    addTankerRow();

    document.getElementById('form-dist').addEventListener('submit', function() {
        var btn = document.getElementById('btn-submit');
        btn.style.opacity = '0.7';
        btn.style.cursor = 'wait';
    });
    </script>
    @endpush
</x-app-layout>
