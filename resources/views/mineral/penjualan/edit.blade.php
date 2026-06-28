<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .pj-page { max-width:44rem; margin:0 auto; padding:1.25rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .pj-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .pj-back:hover { color:#334155; }
        .pj-hdr { margin-bottom:1.5rem; }
        .pj-hdr-tag { display:inline-block; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#2563eb; background:#eff6ff; padding:3px 10px; border-radius:20px; margin-bottom:6px; }
        .pj-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-.03em; line-height:1.2; }
        .pj-hdr-sub { font-size:13px; color:#64748b; margin-top:2px; }
        .pj-sec { background:#fff; border:1px solid #f1f5f9; border-radius:14px; margin-bottom:14px; box-shadow:0 1px 2px rgba(0,0,0,.03); }
        .pj-sec-hdr { padding:.875rem 1.25rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f8fafc; }
        .pj-sec-ico { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .pj-sec-ico.blue { background:#eff6ff; color:#2563eb; }
        .pj-sec-ico.sky { background:#f0f9ff; color:#0284c7; }
        .pj-sec-ico.amber { background:#fffbeb; color:#d97706; }
        .pj-sec-ico.rose { background:#fff1f2; color:#e11d48; }
        .pj-sec-title { font-size:14px; font-weight:700; color:#0f172a; }
        .pj-sec-body { padding:1.125rem 1.25rem; }
        .pj-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .pj-fld { margin-bottom:12px; }
        .pj-lbl { display:flex; align-items:center; gap:4px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
        .pj-req { color:#ef4444; }
        .pj-inp { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; }
        .pj-inp:focus { border-color:#2563eb; background:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
        .pj-sel { width:100%; padding:10px 36px 10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:14px; }
        .pj-sel:focus { border-color:#2563eb; background-color:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
        .pj-money { position:relative; }
        .pj-money-pfx { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:700; color:#94a3b8; pointer-events:none; }
        .pj-money .pj-inp { padding-left:36px; }
        .pj-radio-grp { display:flex; gap:8px; }
        .pj-radio-pill { position:relative; flex:1; cursor:pointer; }
        .pj-radio-pill input { position:absolute; opacity:0; width:0; height:0; }
        .pj-radio-face { display:flex; flex-direction:column; align-items:center; gap:4px; padding:12px 8px; border-radius:10px; border:2px solid #e2e8f0; background:#fafbfc; transition:all .2s; text-align:center; }
        .pj-radio-face .ico { font-size:20px; line-height:1; }
        .pj-radio-face .lbl { font-size:11px; font-weight:700; color:#64748b; }
        .pj-radio-pill input:checked + .pj-radio-face { border-color:#2563eb; background:linear-gradient(180deg,#eff6ff,#f0f7ff); box-shadow:0 0 0 3px rgba(37,99,235,.1); }
        .pj-radio-pill input:checked + .pj-radio-face .lbl { color:#1e40af; }
        .pj-sum { background:linear-gradient(135deg,#eff6ff,#f0f7ff); border:1.5px solid #93c5fd; border-radius:12px; padding:14px 16px; margin-top:12px; }
        .pj-sum-row { display:flex; justify-content:space-between; align-items:center; padding:4px 0; }
        .pj-sum-row.total { border-top:2px dashed #93c5fd; margin-top:6px; padding-top:10px; }
        .pj-sum-k { font-size:12px; color:#64748b; }
        .pj-sum-v { font-size:13px; font-weight:700; color:#0f172a; }
        .pj-sum-v.hi { color:#2563eb; font-size:15px; }
        .pj-btn-bar { display:flex; gap:10px; margin-top:8px; }
        .pj-btn-cancel { display:inline-flex; align-items:center; gap:6px; padding:12px 20px; border-radius:12px; font-size:14px; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all .2s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; }
        .pj-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; }
        .pj-btn-submit { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 24px; border-radius:12px; font-size:14px; font-weight:700; border:none; cursor:pointer; transition:all .25s; font-family:inherit; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; box-shadow:0 4px 16px rgba(37,99,235,.25); }
        .pj-btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,99,235,.35); }
        .pj-err { font-size:11px; color:#ef4444; margin-top:4px; font-weight:500; }
        @media(max-width:640px) { .pj-grid { grid-template-columns:1fr; } .pj-btn-bar { flex-direction:column; } }
    </style>
    @endpush

    <div class="pj-page">
        <a href="{{ route('mineral.penjualan.show', $penjualan) }}" class="pj-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <div class="pj-hdr">
            <div class="pj-hdr-tag">Edit Transaksi</div>
            <div class="pj-hdr-title">Edit Penjualan</div>
            <div class="pj-hdr-sub">No. Faktur: <strong>{{ $penjualan->no_faktur }}</strong></div>
        </div>

        <form method="POST" action="{{ route('mineral.penjualan.update', $penjualan) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="pj-sec">
                <div class="pj-sec-hdr">
                    <div class="pj-sec-ico blue"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div class="pj-sec-title">Informasi Transaksi</div>
                </div>
                <div class="pj-sec-body">
                    <div class="pj-grid">
                        <div class="pj-fld">
                            <label class="pj-lbl">Tanggal Jual <span class="pj-req">*</span></label>
                            <input type="date" name="tanggal_jual" value="{{ old('tanggal_jual', $penjualan->tanggal_jual->format('Y-m-d')) }}" class="pj-inp" required>
                            @error('tanggal_jual')<div class="pj-err">{{ $message }}</div>@enderror
                        </div>
                        @if(! $isSalesRole)
                        <div class="pj-fld">
                            <label class="pj-lbl">Sales <span class="pj-req">*</span></label>
                            <select name="sales_id" class="pj-sel" required>
                                <option value="">Pilih Sales</option>
                                @foreach($sales as $s)
                                    <option value="{{ $s->id }}" {{ old('sales_id', $penjualan->sales_id) == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                                @endforeach
                            </select>
                            @error('sales_id')<div class="pj-err">{{ $message }}</div>@enderror
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pj-sec">
                <div class="pj-sec-hdr">
                    <div class="pj-sec-ico sky"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg></div>
                    <div class="pj-sec-title">Detail Penjualan</div>
                </div>
                <div class="pj-sec-body">
                    <div class="pj-fld">
                        <label class="pj-lbl">Pelanggan <span class="pj-req">*</span></label>
                        <select name="pelanggan_id" class="pj-sel" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" {{ old('pelanggan_id', $penjualan->pelanggan_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }} — {{ $p->nama_pemilik }}</option>
                            @endforeach
                        </select>
                        @error('pelanggan_id')<div class="pj-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="pj-fld">
                        <label class="pj-lbl">Produk <span class="pj-req">*</span></label>
                        <select name="produk_id" class="pj-sel" id="sel-produk" required>
                            <option value="">Pilih Produk</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id }}" data-harga="{{ $p->harga_jual }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id', $penjualan->produk_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->satuan }})</option>
                            @endforeach
                        </select>
                        @error('produk_id')<div class="pj-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="pj-grid">
                        <div class="pj-fld">
                            <label class="pj-lbl">Jumlah <span class="pj-req">*</span></label>
                            <input type="number" name="jumlah" id="inp-jumlah" value="{{ old('jumlah', $penjualan->jumlah) }}" min="0.01" step="any" class="pj-inp" required>
                        </div>
                        <div class="pj-fld">
                            <label class="pj-lbl">Harga Satuan <span class="pj-req">*</span></label>
                            <div class="pj-money"><span class="pj-money-pfx">Rp</span><input type="text" inputmode="numeric" data-currency name="harga_satuan" id="inp-harga" value="{{ old('harga_satuan', number_format($penjualan->harga_satuan, 0, ',', '')) }}" class="pj-inp" required></div>
                        </div>
                    </div>

                    <div class="pj-sum">
                        <div class="pj-sum-row"><span class="pj-sum-k">Jumlah</span><span class="pj-sum-v" id="sum-qty">{{ $penjualan->jumlah }}</span></div>
                        <div class="pj-sum-row"><span class="pj-sum-k">Harga Satuan</span><span class="pj-sum-v" id="sum-price">Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</span></div>
                        <div class="pj-sum-row total"><span class="pj-sum-k" style="font-weight:700;">Total</span><span class="pj-sum-v hi" id="sum-total">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span></div>
                    </div>
                </div>
            </div>

            <div class="pj-sec">
                <div class="pj-sec-hdr">
                    <div class="pj-sec-ico amber"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg></div>
                    <div class="pj-sec-title">Pembayaran</div>
                </div>
                <div class="pj-sec-body">
                    <div class="pj-fld">
                        <label class="pj-lbl">Tipe Pembayaran <span class="pj-req">*</span></label>
                        <div class="pj-radio-grp">
                            <label class="pj-radio-pill">
                                <input type="radio" name="tipe_bayar" value="tunai" {{ old('tipe_bayar', $penjualan->tipe_bayar) == 'tunai' ? 'checked' : '' }} onchange="toggleBayar()">
                                <div class="pj-radio-face"><div class="ico">💵</div><div class="lbl">Tunai</div></div>
                            </label>
                            <label class="pj-radio-pill">
                                <input type="radio" name="tipe_bayar" value="transfer" {{ old('tipe_bayar', $penjualan->tipe_bayar) == 'transfer' ? 'checked' : '' }} onchange="toggleBayar()">
                                <div class="pj-radio-face"><div class="ico">🏦</div><div class="lbl">Transfer</div></div>
                            </label>
                            <label class="pj-radio-pill">
                                <input type="radio" name="tipe_bayar" value="hutang" {{ old('tipe_bayar', $penjualan->tipe_bayar) == 'hutang' ? 'checked' : '' }} onchange="toggleBayar()">
                                <div class="pj-radio-face"><div class="ico">📋</div><div class="lbl">Hutang</div></div>
                            </label>
                        </div>
                        @error('tipe_bayar')<div class="pj-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="pj-fld" id="field-bayar" style="display:{{ in_array(old('tipe_bayar', $penjualan->tipe_bayar), ['hutang']) ? 'block' : 'none' }};">
                        <label class="pj-lbl">Bayar</label>
                        <div class="pj-money"><span class="pj-money-pfx">Rp</span><input type="text" inputmode="numeric" data-currency name="bayar" id="inp-bayar" value="{{ old('bayar', $penjualan->bayar) }}" class="pj-inp"></div>
                    </div>

                    <div class="pj-fld" id="field-no-bukti" style="display:{{ old('tipe_bayar', $penjualan->tipe_bayar) == 'transfer' ? 'block' : 'none' }};">
                        <label class="pj-lbl">No. Bukti Transfer</label>
                        <input type="text" name="no_bukti_transfer" value="{{ old('no_bukti_transfer', $penjualan->no_bukti_transfer) }}" class="pj-inp" placeholder="Masukkan nomor bukti transfer">
                        @error('no_bukti_transfer')<div class="pj-err">{{ $message }}</div>@enderror
                    </div>

                    <div class="pj-fld">
                        <label class="pj-lbl">Keterangan</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan', $penjualan->keterangan) }}" class="pj-inp" placeholder="Catatan (opsional)">
                    </div>
                </div>
            </div>

            <div class="pj-btn-bar">
                <a href="{{ route('mineral.penjualan.show', $penjualan) }}" class="pj-btn-cancel">Batal</a>
                <button type="submit" class="pj-btn-submit">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function toggleBayar() {
            var t = document.querySelector('input[name="tipe_bayar"]:checked');
            if (!t) return;
            var v = t.value;
            document.getElementById('field-bayar').style.display = v === 'hutang' ? 'block' : 'none';
            document.getElementById('field-no-bukti').style.display = v === 'transfer' ? 'block' : 'none';
        }

        document.querySelectorAll('input[name="tipe_bayar"]').forEach(function(r) {
            r.addEventListener('change', toggleBayar);
        });

        // Auto-calculate total
        function calcTotal() {
            var qty = parseFloat(document.getElementById('inp-jumlah').value) || 0;
            var price = parseFloat(document.getElementById('inp-harga').value.replace(/[^0-9]/g, '')) || 0;
            document.getElementById('sum-qty').textContent = qty;
            document.getElementById('sum-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            document.getElementById('sum-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(qty * price);
        }

        document.getElementById('inp-jumlah').addEventListener('input', calcTotal);
        document.getElementById('inp-harga').addEventListener('input', calcTotal);

        // Currency input formatting
        document.querySelectorAll('[data-currency]').forEach(function(el) {
            el.addEventListener('input', function(e) {
                var v = this.value.replace(/[^0-9]/g, '');
                if (v) this.value = new Intl.NumberFormat('id-ID').format(parseInt(v));
                calcTotal();
            });
        });

        // Produk select sets harga
        document.getElementById('sel-produk').addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.harga) {
                var price = parseInt(opt.dataset.harga);
                document.getElementById('inp-harga').value = new Intl.NumberFormat('id-ID').format(price);
                calcTotal();
            }
        });
    </script>
    @endpush
</x-app-layout>
