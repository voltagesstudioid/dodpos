<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .pj-page { max-width:44rem; margin:0 auto; padding:1.25rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .pj-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#94a3b8; text-decoration:none; margin-bottom:1rem; transition:all .2s; }
        .pj-back:hover { color:#334155; }
        .pj-back:hover svg { transform:translateX(-3px); }
        .pj-back svg { transition:transform .2s; }
        .pj-hdr { margin-bottom:1.5rem; }
        .pj-hdr-tag { display:inline-block; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#2563eb; background:#eff6ff; padding:3px 10px; border-radius:20px; margin-bottom:6px; }
        .pj-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-.03em; line-height:1.2; }
        .pj-hdr-sub { font-size:13px; color:#64748b; margin-top:2px; }
        .pj-sec { background:#fff; border:1px solid #f1f5f9; border-radius:14px; margin-bottom:14px; box-shadow:0 1px 2px rgba(0,0,0,.03), 0 4px 12px rgba(0,0,0,.02); transition:box-shadow .2s; }
        .pj-sec:hover { box-shadow:0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04); }
        .pj-sec-hdr { padding:.875rem 1.25rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f8fafc; }
        .pj-sec-ico { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0; }
        .pj-sec-ico.blue { background:#eff6ff; color:#2563eb; }
        .pj-sec-ico.sky { background:#f0f9ff; color:#0284c7; }
        .pj-sec-ico.amber { background:#fffbeb; color:#d97706; }
        .pj-sec-ico.rose { background:#fff1f2; color:#e11d48; }
        .pj-sec-title { font-size:14px; font-weight:700; color:#0f172a; }
        .pj-sec-body { padding:1.125rem 1.25rem; }
        .pj-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .pj-fld { margin-bottom:12px; }
        .pj-fld:last-child { margin-bottom:0; }
        .pj-lbl { display:flex; align-items:center; gap:4px; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
        .pj-req { color:#ef4444; }
        .pj-inp { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; }
        .pj-inp:focus { border-color:#2563eb; background:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
        .pj-inp::placeholder { color:#94a3b8; }
        .pj-sel { width:100%; padding:10px 36px 10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:14px; }
        .pj-sel:focus { border-color:#2563eb; background-color:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
        .pj-ta { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid #e2e8f0; background:#fafbfc; font-size:14px; color:#0f172a; outline:none; transition:all .2s; font-family:inherit; resize:vertical; min-height:60px; }
        .pj-ta:focus { border-color:#2563eb; background:#fff; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
        .pj-money { position:relative; }
        .pj-money-pfx { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:700; color:#94a3b8; pointer-events:none; }
        .pj-money .pj-inp { padding-left:36px; }
        .pj-radio-grp { display:flex; gap:8px; }
        .pj-radio-pill { position:relative; flex:1; cursor:pointer; }
        .pj-radio-pill input { position:absolute; opacity:0; width:0; height:0; }
        .pj-radio-face { display:flex; flex-direction:column; align-items:center; gap:4px; padding:12px 8px; border-radius:10px; border:2px solid #e2e8f0; background:#fafbfc; transition:all .2s; text-align:center; }
        .pj-radio-face .ico { font-size:20px; line-height:1; }
        .pj-radio-face .lbl { font-size:11px; font-weight:700; color:#64748b; transition:color .2s; }
        .pj-radio-pill input:checked + .pj-radio-face { border-color:#2563eb; background:linear-gradient(180deg,#eff6ff,#f0f7ff); box-shadow:0 0 0 3px rgba(37,99,235,.1); }
        .pj-radio-pill input:checked + .pj-radio-face .lbl { color:#1e40af; }
        .pj-radio-pill:hover .pj-radio-face { border-color:#93c5fd; }
        .pj-sum { background:linear-gradient(135deg,#eff6ff,#f0f7ff); border:1.5px solid #93c5fd; border-radius:12px; padding:14px 16px; margin-top:12px; }
        .pj-sum-row { display:flex; justify-content:space-between; align-items:center; padding:4px 0; }
        .pj-sum-row.total { border-top:2px dashed #93c5fd; margin-top:6px; padding-top:10px; }
        .pj-sum-k { font-size:12px; color:#64748b; font-weight:500; }
        .pj-sum-v { font-size:13px; font-weight:700; color:#0f172a; }
        .pj-sum-v.hi { color:#2563eb; font-size:15px; }
        .pj-photo-box { position:relative; width:100%; aspect-ratio:16/9; background:linear-gradient(135deg,#f8fafc,#f1f5f9); border:2px dashed #d1d5db; border-radius:12px; overflow:hidden; display:flex; align-items:center; justify-content:center; transition:border-color .2s; }
        .pj-photo-box:hover { border-color:#93c5fd; }
        .pj-photo-box img, .pj-photo-box video { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
        .pj-photo-empty { text-align:center; }
        .pj-photo-empty-ico { width:44px; height:44px; background:#e2e8f0; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 8px; }
        .pj-photo-empty p { font-size:12px; color:#94a3b8; margin:0; line-height:1.6; }
        .pj-photo-empty strong { color:#64748b; }
        .pj-cam-btn { display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:10px; border-radius:10px; font-size:13px; font-weight:700; border:1.5px solid #93c5fd; cursor:pointer; transition:all .2s; font-family:inherit; background:#eff6ff; color:#1e40af; margin-top:10px; }
        .pj-cam-btn:hover { background:#dbeafe; border-color:#60a5fa; }
        .pj-cap-btn { display:none; width:100%; padding:10px; border-radius:10px; font-size:13px; font-weight:700; border:1.5px solid #60a5fa; cursor:pointer; font-family:inherit; background:#dbeafe; color:#1e3a8a; margin-top:8px; text-align:center; }
        .pj-submit-bar { position:sticky; bottom:0; background:linear-gradient(to top,#fff 60%,transparent); padding:16px 0 4px; display:flex; gap:10px; margin-top:8px; z-index:10; }
        .pj-btn-cancel { display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:12px 20px; border-radius:12px; font-size:14px; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all .2s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; white-space:nowrap; }
        .pj-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }
        .pj-btn-submit { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 24px; border-radius:12px; font-size:14px; font-weight:700; border:none; cursor:pointer; transition:all .25s; font-family:inherit; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; box-shadow:0 4px 16px rgba(37,99,235,.25); }
        .pj-btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(37,99,235,.35); }
        .pj-btn-submit:active { transform:translateY(0); }
        .pj-err { font-size:11px; color:#ef4444; margin-top:4px; font-weight:500; }
        @media(max-width:640px) { .pj-grid { grid-template-columns:1fr; } .pj-submit-bar { flex-direction:column; } }
    </style>
    @endpush

    <div class="pj-page">
        <a href="{{ route('mineral.penjualan.index') }}" class="pj-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Penjualan
        </a>
        <div class="pj-hdr">
            <div class="pj-hdr-tag">Transaksi Baru</div>
            <div class="pj-hdr-title">Tambah Penjualan</div>
            <div class="pj-hdr-sub">Catat transaksi penjualan mineral baru</div>
        </div>

        <form method="POST" action="{{ route('mineral.penjualan.store') }}" enctype="multipart/form-data" id="form-penjualan">
            @csrf
            <div class="pj-sec">
                <div class="pj-sec-hdr">
                    <div class="pj-sec-ico blue"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div class="pj-sec-title">Informasi Transaksi</div>
                </div>
                <div class="pj-sec-body">
                    <div class="pj-grid">
                        <div class="pj-fld">
                            <label class="pj-lbl">Tanggal Jual <span class="pj-req">*</span></label>
                            <input type="date" name="tanggal_jual" value="{{ old('tanggal_jual', date('Y-m-d')) }}" class="pj-inp" required>
                            @error('tanggal_jual')<div class="pj-err">{{ $message }}</div>@enderror
                        </div>
                        @if(! $isSalesRole)
                        <div class="pj-fld">
                            <label class="pj-lbl">Sales <span class="pj-req">*</span></label>
                            <select name="sales_id" class="pj-sel" id="sel-sales" required>
                                <option value="">Pilih Sales</option>
                                @foreach($sales as $s)<option value="{{ $s->id }}" data-regional-id="{{ $s->regional_id ?? '' }}" {{ old('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>@endforeach
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
                            @foreach($pelanggans as $p)<option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_toko }} — {{ $p->nama_pemilik }}</option>@endforeach
                        </select>
                        @error('pelanggan_id')<div class="pj-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="pj-fld">
                        <label class="pj-lbl">Produk <span class="pj-req">*</span></label>
                        <select name="produk_id" class="pj-sel" id="sel-produk" required>
                            <option value="">Pilih Produk</option>
                            @foreach($produks as $p)<option value="{{ $p->id }}" data-harga="{{ $p->harga_jual }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->satuan }})</option>@endforeach
                        </select>
                        @error('produk_id')<div class="pj-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="pj-grid">
                        <div class="pj-fld">
                            <label class="pj-lbl">Jumlah <span class="pj-req">*</span></label>
                            <input type="number" name="jumlah" id="inp-jumlah" value="{{ old('jumlah', 1) }}" min="1" class="pj-inp" required>
                        </div>
                        <div class="pj-fld">
                            <label class="pj-lbl">Harga Satuan <span class="pj-req">*</span></label>
                            <div class="pj-money"><span class="pj-money-pfx">Rp</span><input type="number" name="harga_satuan" id="inp-harga" value="{{ old('harga_satuan') }}" min="0" step="100" class="pj-inp" required></div>
                            <div id="harga-source" style="font-size:11px;font-weight:600;margin-top:4px;display:none;"></div>
                        </div>
                    </div>
                    <div class="pj-sum" id="summary-box" style="display:none;">
                        <div class="pj-sum-row"><span class="pj-sum-k">Jumlah</span><span class="pj-sum-v" id="sum-qty">0</span></div>
                        <div class="pj-sum-row"><span class="pj-sum-k">Harga Satuan</span><span class="pj-sum-v" id="sum-price">Rp 0</span></div>
                        <div class="pj-sum-row total"><span class="pj-sum-k" style="font-weight:700;">Total</span><span class="pj-sum-v hi" id="sum-total">Rp 0</span></div>
                    </div>
                </div>
            </div>

            <div class="pj-sec">
                <div class="pj-sec-hdr">
                    <div class="pj-sec-ico amber"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
                    <div class="pj-sec-title">Pembayaran</div>
                </div>
                <div class="pj-sec-body">
                    <div class="pj-fld">
                        <label class="pj-lbl">Tipe Pembayaran <span class="pj-req">*</span></label>
                        <div class="pj-radio-grp">
                            <label class="pj-radio-pill"><input type="radio" name="tipe_bayar" value="tunai" {{ old('tipe_bayar', 'tunai') === 'tunai' ? 'checked' : '' }}><div class="pj-radio-face"><span class="ico">💵</span><span class="lbl">Tunai</span></div></label>
                            <label class="pj-radio-pill"><input type="radio" name="tipe_bayar" value="hutang" {{ old('tipe_bayar') === 'hutang' ? 'checked' : '' }}><div class="pj-radio-face"><span class="ico">📄</span><span class="lbl">Hutang</span></div></label>
                            <label class="pj-radio-pill"><input type="radio" name="tipe_bayar" value="transfer" {{ old('tipe_bayar') === 'transfer' ? 'checked' : '' }}><div class="pj-radio-face"><span class="ico">🏦</span><span class="lbl">Transfer</span></div></label>
                        </div>
                    </div>
                    <div class="pj-fld" id="fld-bayar" style="display:none;">
                        <label class="pj-lbl">Jumlah Bayar (DP)</label>
                        <div class="pj-money"><span class="pj-money-pfx">Rp</span><input type="number" name="bayar" id="inp-bayar" value="{{ old('bayar', 0) }}" min="0" step="100" class="pj-inp"></div>
                    </div>

                    {{-- Transfer Fields --}}
                    <div id="fld-transfer" style="display:none;">
                        <div class="pj-fld">
                            <label class="pj-lbl">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                No. Bukti Transfer <span class="pj-req">*</span>
                            </label>
                            <input type="text" name="no_bukti_transfer" id="inp-transfer" value="{{ old('no_bukti_transfer') }}" class="pj-inp" placeholder="Masukkan ID / No. referensi transfer">
                            @error('no_bukti_transfer')<div class="pj-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="pj-fld">
                            <label class="pj-lbl">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Foto Bukti Transfer <span class="pj-req">*</span>
                            </label>
                            <div style="font-size:11px;color:#64748b;margin-bottom:8px;">Upload screenshot/foto bukti transfer dari bank/e-wallet. Supervisor akan memverifikasi data ini.</div>
                            <input type="file" name="bukti_transfer" id="transfer-input" accept="image/*" capture="user" style="display:none;">
                            <input type="hidden" name="bukti_transfer_base64" id="transfer-base64">
                            <div class="pj-photo-box" id="transfer-preview-box" style="aspect-ratio:4/3;border-color:#93c5fd;">
                                <img id="transfer-preview" src="" alt="" style="display:none;">
                                <div class="pj-photo-empty" id="transfer-placeholder">
                                    <div class="pj-photo-empty-ico" style="background:#dbeafe;">
                                        <svg width="22" height="22" fill="none" stroke="#3b82f6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p>Upload foto bukti transfer<br><strong>screenshot dari bank/e-wallet</strong></p>
                                </div>
                            </div>
                            <button type="button" class="pj-cam-btn" id="transfer-btn-camera">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Upload Bukti Transfer
                            </button>
                            @error('bukti_transfer')<div class="pj-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="pj-fld">
                        <label class="pj-lbl">Keterangan</label>
                        <textarea name="keterangan" class="pj-ta" placeholder="Catatan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <input type="hidden" name="latitude" id="gps-lat">
            <input type="hidden" name="longitude" id="gps-lng">

            <div class="pj-sec">
                <div class="pj-sec-hdr">
                    <div class="pj-sec-ico rose"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <div class="pj-sec-title">Bukti Penjualan</div>
                </div>
                <div class="pj-sec-body">
                    <input type="file" name="foto_nota" id="nota-input" accept="image/*" capture="user" style="display:none;">
                    <input type="hidden" name="foto_nota_base64" id="nota-base64">
                    <div class="pj-photo-box" id="nota-preview-box">
                        <video id="nota-webcam-video" autoplay playsinline style="display:none;"></video>
                        <canvas id="nota-webcam-canvas" style="display:none;"></canvas>
                        <img id="nota-preview" src="" alt="" style="display:none;">
                        <div class="pj-photo-empty" id="nota-placeholder">
                            <div class="pj-photo-empty-ico"><svg width="22" height="22" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                            <p>Foto nota / struk penjualan<br><strong>sebagai bukti transaksi</strong></p>
                        </div>
                    </div>
                    <button type="button" class="pj-cam-btn" id="nota-btn-camera">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Buka Kamera / Upload Foto
                    </button>
                    <button type="button" class="pj-cap-btn" id="nota-btn-capture">Ambil Foto</button>
                </div>
            </div>

            <div class="pj-submit-bar">
                <a href="{{ route('mineral.penjualan.index') }}" class="pj-btn-cancel"><svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Batal</a>
                <button type="submit" class="pj-btn-submit"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Penjualan</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Regional price map from controller: { salesId: { produkId: harga } }
        var regionalPriceMap = @json($regionalPriceMap);

        var selSales = document.getElementById('sel-sales');
        var selProduk = document.getElementById('sel-produk');
        var inpJumlah = document.getElementById('inp-jumlah');
        var inpHarga = document.getElementById('inp-harga');
        var hargaSource = document.getElementById('harga-source');
        var sumBox = document.getElementById('summary-box');
        var sumQty = document.getElementById('sum-qty');
        var sumPrice = document.getElementById('sum-price');
        var sumTotal = document.getElementById('sum-total');
        var fldBayar = document.getElementById('fld-bayar');
        var isSalesRole = @json($isSalesRole);

        function fmt(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

        function getSalesId() {
            if (isSalesRole && selSales === null) {
                // For sales role, there's only one sales in the map
                var keys = Object.keys(regionalPriceMap);
                return keys.length > 0 ? keys[0] : null;
            }
            return selSales ? selSales.value : null;
        }

        function applyHarga() {
            var salesId = getSalesId();
            var opt = selProduk.options[selProduk.selectedIndex];
            if (!opt || !opt.value) {
                inpHarga.value = '';
                hargaSource.style.display = 'none';
                updateSummary();
                return;
            }
            var produkId = opt.value;
            var defaultHarga = parseFloat(opt.dataset.harga) || 0;
            var regionalHarga = null;

            if (salesId && regionalPriceMap[salesId] && regionalPriceMap[salesId][produkId] !== undefined) {
                regionalHarga = parseFloat(regionalPriceMap[salesId][produkId]);
            }

            if (regionalHarga !== null && regionalHarga > 0) {
                inpHarga.value = regionalHarga;
                hargaSource.style.display = 'block';
                hargaSource.style.color = '#059669';
                hargaSource.textContent = '\u2713 Harga Regional (' + fmt(regionalHarga) + ')';
            } else {
                inpHarga.value = defaultHarga;
                hargaSource.style.display = 'block';
                hargaSource.style.color = '#94a3b8';
                hargaSource.textContent = '\u2022 Harga Default produk';
            }
            updateSummary();
        }

        function updateSummary() {
            var qty = parseInt(inpJumlah.value) || 0, price = parseFloat(inpHarga.value) || 0, total = qty * price;
            if (qty > 0 && price > 0) { sumBox.style.display = 'block'; sumQty.textContent = qty + ' unit'; sumPrice.textContent = fmt(price); sumTotal.textContent = fmt(total); }
            else { sumBox.style.display = 'none'; }
        }

        if (selSales) {
            selSales.addEventListener('change', function() { applyHarga(); });
        }
        selProduk.addEventListener('change', function() { applyHarga(); });
        inpJumlah.addEventListener('input', updateSummary);
        inpHarga.addEventListener('input', function() {
            hargaSource.style.display = 'none';
            updateSummary();
        });
        document.querySelectorAll('input[name="tipe_bayar"]').forEach(function(r) { r.addEventListener('change', function() { fldBayar.style.display = this.value === 'hutang' ? 'block' : 'none'; document.getElementById('fld-transfer').style.display = this.value === 'transfer' ? 'block' : 'none'; }); });
        var checked = document.querySelector('input[name="tipe_bayar"]:checked');
        if (checked) { fldBayar.style.display = checked.value === 'hutang' ? 'block' : 'none'; document.getElementById('fld-transfer').style.display = checked.value === 'transfer' ? 'block' : 'none'; }
        applyHarga();
        if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(function(p) { document.getElementById('gps-lat').value = p.coords.latitude; document.getElementById('gps-lng').value = p.coords.longitude; }, function(){}, {timeout:5000}); }
        document.getElementById('form-penjualan').addEventListener('submit', function() { var b = this.querySelector('.pj-btn-submit'); b.style.opacity = '0.7'; b.style.cursor = 'wait'; });

        // Transfer photo upload
        (function() {
            var inp = document.getElementById('transfer-input');
            var preview = document.getElementById('transfer-preview');
            var ph = document.getElementById('transfer-placeholder');
            var btnCam = document.getElementById('transfer-btn-camera');
            function show(src) { preview.src = src; preview.style.display = 'block'; ph.style.display = 'none'; btnCam.innerHTML = 'Ulangi Foto'; }
            btnCam.addEventListener('click', function() { inp.click(); });
            inp.addEventListener('change', function() { var f = this.files && this.files[0]; if (!f) return; show(URL.createObjectURL(f)); });
        })();
        (function() {
            var isMob = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            var inp = document.getElementById('nota-input'), preview = document.getElementById('nota-preview'), ph = document.getElementById('nota-placeholder');
            var b64 = document.getElementById('nota-base64'), btnCam = document.getElementById('nota-btn-camera'), btnCap = document.getElementById('nota-btn-capture');
            var vid = document.getElementById('nota-webcam-video'), cvs = document.getElementById('nota-webcam-canvas'), stream = null;
            function show(src) { preview.src = src; preview.style.display = 'block'; ph.style.display = 'none'; vid.style.display = 'none'; btnCap.style.display = 'none'; btnCam.innerHTML = 'Ulangi Foto'; }
            function stop() { if(stream){stream.getTracks().forEach(function(t){t.stop();});stream=null;} }
            async function start() { try { stream = await navigator.mediaDevices.getUserMedia({video:{facingMode:'user',width:{ideal:640},height:{ideal:480}}}); vid.srcObject=stream; vid.style.display='block'; preview.style.display='none'; ph.style.display='none'; btnCam.style.display='none'; btnCap.style.display='block'; } catch(e) { alert('Tidak dapat mengakses kamera.'); } }
            btnCam.addEventListener('click', function(){ isMob ? inp.click() : start(); });
            btnCap.addEventListener('click', function(){ cvs.width=vid.videoWidth; cvs.height=vid.videoHeight; cvs.getContext('2d').drawImage(vid,0,0); var d=cvs.toDataURL('image/jpeg',.8); b64.value=d; stop(); show(d); });
            inp.addEventListener('change', function(){ var f=this.files&&this.files[0]; if(!f)return; show(URL.createObjectURL(f)); });
        })();
    });
    </script>
    @endpush
</x-app-layout>
