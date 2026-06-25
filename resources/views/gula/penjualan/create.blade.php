@extends('layouts.app', ['title' => 'Tambah Penjualan - Gula'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .pg-page { font-family:'Plus Jakarta Sans',sans-serif; max-width:56rem; margin:0 auto; padding:1.25rem 1rem; }
    .pg-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; }
    .pg-hdr-icon { width:48px; height:48px; border-radius:13px; background:linear-gradient(135deg,#f59e0b,#d97706); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(245,158,11,0.25); flex-shrink:0; }
    .pg-hdr-icon svg { width:24px; height:24px; stroke:#fff; fill:none; }
    .pg-hdr h1 { font-size:1.25rem; font-weight:800; color:#1e1b4b; margin:0; }
    .pg-hdr p { font-size:0.78rem; color:#d97706; margin:2px 0 0; font-weight:600; }
    .pg-back { font-size:0.78rem; color:#d97706; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; margin-bottom:1rem; }
    .pg-back:hover { text-decoration:underline; }
    .pg-card { background:#fff; border:1px solid #fde68a; border-radius:16px; margin-bottom:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.04); position:relative; }
    .pg-card-hdr { padding:0.9rem 1.25rem; display:flex; align-items:center; gap:0.65rem; border-bottom:1px solid #fef3c7; }
    .pg-card-dot { width:8px; height:8px; border-radius:50%; background:#f59e0b; flex-shrink:0; }
    .pg-card-title { font-size:0.82rem; font-weight:700; color:#1e1b4b; }
    .pg-card-body { padding:1.125rem 1.25rem; }
    .pg-row2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .pg-fg { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.85rem; }
    .pg-lbl { display:flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .pg-req { color:#ef4444; }
    .pg-inp, .pg-sel, .pg-txt { width:100%; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#fcfcfd; font-family:inherit; font-size:0.82rem; color:#0f172a; transition:all 0.2s; outline:none; box-sizing:border-box; }
    .pg-inp:focus, .pg-sel:focus, .pg-txt:focus { border-color:#f59e0b; background:#fff; box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
    .pg-sel { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; padding-right:32px; cursor:pointer; }
    .pg-txt { resize:vertical; min-height:60px; }
    .pg-hint { font-size:0.68rem; color:#94a3b8; margin-top:0.2rem; }
    .pg-err { font-size:0.72rem; color:#dc2626; font-weight:600; margin-top:0.2rem; }
    .pg-stock-info { background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:0.5rem 0.75rem; font-size:0.75rem; color:#92400e; margin-top:0.35rem; display:none; }
    .pg-stock-info strong { font-weight:700; }
    .pg-qty-wrap { position:relative; display:inline-flex; align-items:center; }
    .pg-qty-unit { position:absolute; right:10px; font-size:0.72rem; font-weight:700; color:#94a3b8; background:#f1f5f9; padding:2px 8px; border-radius:6px; pointer-events:none; }
    .pg-qty-wrap .pg-inp { padding-right:56px; }

    /* Summary */
    .pg-summary { padding:1rem 1.25rem; border-top:1px solid #fef3c7; display:flex; justify-content:space-between; align-items:center; background:#fffbeb; border-radius:0 0 16px 16px; }
    .pg-sum-label { font-size:0.72rem; color:#94a3b8; font-weight:600; }
    .pg-sum-value { font-size:1.1rem; font-weight:800; color:#d97706; }

    /* Actions */
    .pg-actions { display:flex; gap:0.75rem; justify-content:flex-end; margin-top:0.5rem; }
    .pg-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.25rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .pg-btn-ghost { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
    .pg-btn-ghost:hover { background:#e2e8f0; }
    .pg-btn-primary { background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; box-shadow:0 2px 8px rgba(245,158,11,0.25); }
    .pg-btn-primary:hover { box-shadow:0 4px 16px rgba(245,158,11,0.35); }

    /* Payment pills */
    .pg-pay-pills { display:flex; gap:0.5rem; flex-wrap:wrap; }
    .pg-pay-pill { padding:0.5rem 1rem; border-radius:10px; border:1.5px solid #e2e8f0; font-size:0.78rem; font-weight:700; color:#64748b; cursor:pointer; background:#fff; transition:all 0.15s; }
    .pg-pay-pill.active { background:#dcfce7; border-color:#22c55e; color:#15803d; }
    .pg-pay-pill.hutang-active { background:#fef3c7; border-color:#f59e0b; color:#92400e; }
    .pg-pay-pill.transfer-active { background:#dbeafe; border-color:#3b82f6; color:#1d4ed8; }
    .pg-pay-pill:hover { border-color:#f59e0b; }

    /* Hutang section */
    .pg-hutang-section { display:none; background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:1rem 1.125rem; margin-top:0.75rem; }
    .pg-hutang-info { background:#fef3c7; border:1px solid #fde68a; border-radius:8px; padding:0.6rem 0.85rem; margin-bottom:0.75rem; font-size:0.75rem; color:#92400e; }
    .pg-hutang-info strong { font-weight:800; }
    .pg-hutang-limit-ok { color:#059669; }
    .pg-hutang-limit-warn { color:#dc2626; }

    /* Tunai section */
    .pg-tunai-section { display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:1rem 1.125rem; margin-top:0.75rem; }
    .pg-kembalian-box { background:#dcfce7; border:1px solid #86efac; border-radius:8px; padding:0.6rem 0.85rem; margin-top:0.5rem; display:flex; justify-content:space-between; align-items:center; }
    .pg-kembalian-lbl { font-size:0.75rem; font-weight:700; color:#166534; }
    .pg-kembalian-val { font-size:1rem; font-weight:800; color:#15803d; }

    /* Transfer section */
    .pg-transfer-section { display:none; background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:1rem 1.125rem; margin-top:0.75rem; }
    .pg-transfer-title { font-size:0.72rem; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.65rem; display:flex; align-items:center; gap:0.4rem; }
    .pg-file-input { width:100%; padding:0.5rem; border:1.5px dashed #93c5fd; border-radius:8px; font-size:0.78rem; font-family:inherit; color:#1e40af; background:#f8faff; cursor:pointer; }
    .pg-file-input:hover { border-color:#3b82f6; background:#eff6ff; }
    .pg-file-input::-webkit-file-upload-button { background:#dbeafe; color:#1d4ed8; border:none; padding:0.35rem 0.75rem; border-radius:6px; font-size:0.72rem; font-weight:600; cursor:pointer; margin-right:0.5rem; }

    /* Searchable select */
    .pg-ss-wrap { position:relative; }
    .pg-ss-trigger { display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#fcfcfd; cursor:pointer; transition:all 0.2s; min-height:42px; }
    .pg-ss-trigger:hover, .pg-ss-wrap.open .pg-ss-trigger { border-color:#f59e0b; background:#fff; }
    .pg-ss-wrap.open .pg-ss-trigger { box-shadow:0 0 0 3px rgba(245,158,11,0.1); }
    .pg-ss-placeholder { font-size:0.82rem; color:#94a3b8; }
    .pg-ss-value { font-size:0.82rem; color:#0f172a; font-weight:600; }
    .pg-ss-value small { font-weight:400; color:#64748b; font-size:0.72rem; }
    .pg-ss-chevron { width:16px; height:16px; stroke:#94a3b8; flex-shrink:0; transition:transform 0.2s; }
    .pg-ss-wrap.open .pg-ss-chevron { transform:rotate(180deg); }
    .pg-ss-dropdown { position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff; border:1.5px solid #e2e8f0; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.1); z-index:100; display:none; overflow:hidden; }
    .pg-ss-wrap.open .pg-ss-dropdown { display:block; }
    .pg-ss-search { padding:0.5rem 0.75rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.5rem; }
    .pg-ss-search svg { width:16px; height:16px; stroke:#94a3b8; flex-shrink:0; }
    .pg-ss-search input { flex:1; border:none; outline:none; font-size:0.8rem; font-family:inherit; color:#0f172a; }
    .pg-ss-search input::placeholder { color:#94a3b8; }
    .pg-ss-list { max-height:200px; overflow-y:auto; }
    .pg-ss-opt { padding:0.55rem 0.75rem; cursor:pointer; transition:background 0.1s; display:flex; justify-content:space-between; align-items:center; }
    .pg-ss-opt:hover, .pg-ss-opt.highlighted { background:#fffbeb; }
    .pg-ss-opt.selected { background:#fef3c7; }
    .pg-ss-opt-main { font-size:0.8rem; font-weight:600; color:#1e1b4b; }
    .pg-ss-opt-sub { font-size:0.68rem; color:#94a3b8; margin-top:1px; }
    .pg-ss-opt-meta { font-size:0.65rem; color:#64748b; text-align:right; white-space:nowrap; }
    .pg-ss-empty { padding:1rem; text-align:center; font-size:0.78rem; color:#94a3b8; }
    .pg-ss-clear { padding:0.4rem 0.75rem; border-top:1px solid #f1f5f9; text-align:center; font-size:0.72rem; font-weight:700; color:#ef4444; cursor:pointer; }
    .pg-ss-clear:hover { background:#fef2f2; }

    /* Alert */
    .pg-alert { background:#fee2e2; border:1px solid #fca5a5; border-radius:10px; padding:0.75rem 1rem; margin-bottom:1rem; }
    .pg-alert-msg { color:#991b1b; font-size:0.8rem; font-weight:600; }
    .pg-alert-ok { background:#dcfce7; border-color:#bbf7d0; }
    .pg-alert-ok .pg-alert-msg { color:#166534; }

    @media (max-width:640px) { .pg-row2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="pg-page">
    <div class="pg-hdr">
        <div class="pg-hdr-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <h1>Transaksi Penjualan</h1>
            <p>Catat penjualan gula dari stok kendaraan</p>
        </div>
    </div>

    <a href="{{ route('gula.penjualan.index') }}" class="pg-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>

    <form action="{{ route('gula.penjualan.store') }}" method="POST" id="penjualanForm" enctype="multipart/form-data">
        @csrf

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="pg-alert">
            @foreach($errors->all() as $error)
            <div class="pg-alert-msg">{{ $error }}</div>
            @endforeach
        </div>
        @endif
        @if(session('error'))
        <div class="pg-alert">
            <div class="pg-alert-msg">{{ session('error') }}</div>
        </div>
        @endif
        @if(session('success'))
        <div class="pg-alert pg-alert-ok">
            <div class="pg-alert-msg">{{ session('success') }}</div>
        </div>
        @endif

        {{-- Card 1: Informasi Transaksi --}}
        <div class="pg-card">
            <div class="pg-card-hdr">
                <div class="pg-card-dot"></div>
                <div class="pg-card-title">Informasi Transaksi</div>
            </div>
            <div class="pg-card-body">
                <div class="pg-row2">
                    <div class="pg-fg">
                        <label class="pg-lbl">Tanggal Jual <span class="pg-req">*</span></label>
                        <input type="date" name="tanggal_jual" class="pg-inp" value="{{ old('tanggal_jual', date('Y-m-d')) }}" required>
                        @error('tanggal_jual')<div class="pg-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="pg-fg">
                        <label class="pg-lbl">Sales <span class="pg-req">*</span></label>
                        <select name="sales_id" id="sales_id" class="pg-sel" required>
                            <option value="">Pilih Sales</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ old('sales_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }}{{ $s->no_kendaraan ? ' ('.$s->no_kendaraan.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('sales_id')<div class="pg-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Pelanggan --}}
        <div class="pg-card">
            <div class="pg-card-hdr">
                <div class="pg-card-dot"></div>
                <div class="pg-card-title">Pelanggan</div>
            </div>
            <div class="pg-card-body">
                <div class="pg-fg" style="margin-bottom:0.5rem;">
                    <label class="pg-lbl">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Pilih Pelanggan <span class="pg-req">*</span>
                    </label>
                    <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id', '') }}">
                    <div class="pg-ss-wrap" id="custSearchWrap">
                        <div class="pg-ss-trigger" onclick="toggleCustDropdown()">
                            <span id="custTriggerText" class="pg-ss-placeholder">Ketik nama pelanggan...</span>
                            <svg class="pg-ss-chevron" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="pg-ss-dropdown">
                            <div class="pg-ss-search">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="custSearchInput" placeholder="Cari nama toko atau pemilik..." oninput="filterCustomers(this.value)" onkeydown="custKeyNav(event)">
                            </div>
                            <div class="pg-ss-list" id="custList"></div>
                            <div class="pg-ss-clear" id="custClearBtn" onclick="clearCustSelection()" style="display:none;">&#10005; Hapus Pilihan</div>
                        </div>
                    </div>
                    <div id="custSelectedInfo" style="display:none;margin-top:0.4rem;">
                        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:0.5rem 0.75rem;font-size:0.75rem;color:#92400e;display:flex;align-items:center;gap:0.5rem;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span id="custInfoText"></span>
                        </div>
                    </div>
                    <div class="pg-err" id="pelanggan_err" style="display:none;">Pelanggan wajib dipilih</div>
                    @error('pelanggan_id')<div class="pg-err">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Card 3: Detail Produk --}}
        <div class="pg-card">
            <div class="pg-card-hdr">
                <div class="pg-card-dot"></div>
                <div class="pg-card-title">Detail Produk</div>
            </div>
            <div class="pg-card-body">
                <div class="pg-fg">
                    <label class="pg-lbl">Produk <span class="pg-req">*</span></label>
                    <select name="produk_id" id="produk_id" class="pg-sel" required>
                        <option value="">Pilih Sales terlebih dahulu</option>
                    </select>
                    @error('produk_id')<div class="pg-err">{{ $message }}</div>@enderror
                    <div class="pg-stock-info" id="stokInfo">
                        <strong id="stokInfoText"></strong>
                    </div>
                </div>
                <div class="pg-row2">
                    <div class="pg-fg">
                        <label class="pg-lbl">Jumlah <span class="pg-req">*</span></label>
                        <div class="pg-qty-wrap">
                            <input type="number" name="jumlah" id="jumlah" class="pg-inp" value="{{ old('jumlah') }}" required min="1" placeholder="0" oninput="calculateTotal()">
                            <span class="pg-qty-unit" id="qtyUnit">—</span>
                        </div>
                        @error('jumlah')<div class="pg-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="pg-fg">
                        <label class="pg-lbl">Harga Satuan <span class="pg-req">*</span></label>
                        <input type="text" inputmode="numeric" data-currency name="harga_satuan" id="harga_satuan" class="pg-inp" value="{{ old('harga_satuan') }}" required placeholder="0" oninput="calculateTotal()">
                        @error('harga_satuan')<div class="pg-err">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="pg-summary">
                <div>
                    <div class="pg-sum-label">Total</div>
                </div>
                <div class="pg-sum-value" id="grandTotal">Rp 0</div>
            </div>
        </div>

        {{-- Card 4: Pembayaran --}}
        <div class="pg-card">
            <div class="pg-card-hdr">
                <div class="pg-card-dot"></div>
                <div class="pg-card-title">Pembayaran</div>
            </div>
            <div class="pg-card-body">
                <div class="pg-fg" style="margin-bottom:0.5rem;">
                    <label class="pg-lbl">Metode Bayar <span class="pg-req">*</span></label>
                    <div class="pg-pay-pills" id="paymentPills">
                        <div class="pg-pay-pill{{ old('tipe_bayar', 'tunai') === 'tunai' ? ' active' : '' }}" data-method="tunai" onclick="setPayment('tunai')">Tunai</div>
                        <div class="pg-pay-pill{{ old('tipe_bayar') === 'transfer' ? ' transfer-active' : '' }}" data-method="transfer" onclick="setPayment('transfer')">Transfer</div>
                        <div class="pg-pay-pill{{ old('tipe_bayar') === 'hutang' ? ' hutang-active' : '' }}" data-method="hutang" onclick="setPayment('hutang')">Hutang</div>
                    </div>
                    <input type="hidden" name="tipe_bayar" id="tipeBayar" value="{{ old('tipe_bayar', 'tunai') }}">
                </div>

                {{-- Tunai section --}}
                <div class="pg-tunai-section" id="tunaiSection" style="display:{{ old('tipe_bayar', 'tunai') === 'tunai' ? 'block' : 'none' }};">
                    <div class="pg-fg" style="margin-bottom:0;">
                        <label class="pg-lbl">Uang Diterima</label>
                        <input type="text" inputmode="numeric" data-currency name="bayar_tunai" id="bayarTunaiInput" class="pg-inp" value="{{ old('bayar', '') }}" placeholder="Masukkan uang tunai..." oninput="calculateKembalian()">
                        <span class="pg-hint">Kosongkan jika pas</span>
                    </div>
                    <div class="pg-kembalian-box" id="kembalianBox" style="display:none;">
                        <span class="pg-kembalian-lbl">Kembalian</span>
                        <span class="pg-kembalian-val" id="kembalianVal">Rp 0</span>
                    </div>
                </div>

                {{-- Hutang section --}}
                <div class="pg-hutang-section" id="hutangSection" style="display:{{ old('tipe_bayar') === 'hutang' ? 'block' : 'none' }};">
                    <div class="pg-hutang-info" id="hutangInfoBox" style="display:none;"></div>
                    <div class="pg-fg" style="margin-bottom:0;">
                        <label class="pg-lbl">Uang Muka (DP)</label>
                        <input type="text" inputmode="numeric" data-currency name="bayar" id="bayarInput" class="pg-inp" value="{{ old('bayar', '0') }}" placeholder="0" oninput="calculateTotal()">
                        <span class="pg-hint">Sisa akan menjadi hutang pelanggan</span>
                    </div>
                </div>

                {{-- Transfer section --}}
                <div class="pg-transfer-section" id="transferSection" style="display:{{ old('tipe_bayar') === 'transfer' ? 'block' : 'none' }};">
                    <div class="pg-transfer-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Detail Transfer
                    </div>
                    <div class="pg-row2">
                        <div class="pg-fg">
                            <label class="pg-lbl">ID Transaksi / No Referensi <span class="pg-req">*</span></label>
                            <input type="text" name="transfer_ref" class="pg-inp" value="{{ old('transfer_ref', '') }}" placeholder="Contoh: TRX123456789...">
                            <span class="pg-hint">Nomor referensi transaksi bank</span>
                            @error('transfer_ref')<div class="pg-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="pg-fg">
                            <label class="pg-lbl">Foto Bukti Transfer <span class="pg-req">*</span></label>
                            <input type="file" name="foto_bukti_transfer" class="pg-file-input" accept="image/jpeg,image/jpg,image/png,image/webp">
                            <span class="pg-hint">Format: JPG, PNG, WebP (maks 4MB)</span>
                            @error('foto_bukti_transfer')<div class="pg-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="pg-fg" style="margin-bottom:0;margin-top:0.75rem;">
                    <label class="pg-lbl">Catatan</label>
                    <textarea name="keterangan" class="pg-txt" placeholder="Catatan tambahan (opsional)...">{{ old('keterangan', '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Card 5: Lokasi (opsional) --}}
        <div class="pg-card">
            <div class="pg-card-hdr">
                <div class="pg-card-dot" style="background:#94a3b8;"></div>
                <div class="pg-card-title">Lokasi (Opsional)</div>
            </div>
            <div class="pg-card-body">
                <div class="pg-row2">
                    <div class="pg-fg">
                        <label class="pg-lbl">Latitude</label>
                        <input type="number" step="any" name="latitude" class="pg-inp" value="{{ old('latitude') }}" placeholder="-6.123456">
                    </div>
                    <div class="pg-fg">
                        <label class="pg-lbl">Longitude</label>
                        <input type="number" step="any" name="longitude" class="pg-inp" value="{{ old('longitude') }}" placeholder="106.123456">
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pg-actions">
            <a href="{{ route('gula.penjualan.index') }}" class="pg-btn pg-btn-ghost">Batal</a>
            <button type="submit" class="pg-btn pg-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Data from controller
var vehicleStock = @json($vehicleStock);
var produks = {!! $produkJson !!};
var PELANGGANS = {!! $pelangganJson !!};

function formatRp(n) { return 'Rp ' + n.toLocaleString('id-ID'); }
function escHtml(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

// ===== Searchable Customer Dropdown =====
var custHighlightIdx = -1;

function toggleCustDropdown() {
    var wrap = document.getElementById('custSearchWrap');
    var isOpen = wrap.classList.contains('open');
    if (isOpen) { closeCustDropdown(); }
    else {
        wrap.classList.add('open');
        document.getElementById('custSearchInput').value = '';
        document.getElementById('custSearchInput').focus();
        filterCustomers('');
    }
}

function closeCustDropdown() {
    document.getElementById('custSearchWrap').classList.remove('open');
    custHighlightIdx = -1;
}

function filterCustomers(q) {
    q = q.toLowerCase().trim();
    var list = document.getElementById('custList');
    var clearBtn = document.getElementById('custClearBtn');
    var currentVal = document.getElementById('pelanggan_id').value;
    clearBtn.style.display = currentVal ? '' : 'none';

    var filtered = PELANGGANS;
    if (q) {
        filtered = PELANGGANS.filter(function(p) {
            return (p.nama_toko && p.nama_toko.toLowerCase().indexOf(q) !== -1) ||
                   (p.nama_pemilik && p.nama_pemilik.toLowerCase().indexOf(q) !== -1) ||
                   (p.no_hp && p.no_hp.indexOf(q) !== -1) ||
                   (p.alamat && p.alamat.toLowerCase().indexOf(q) !== -1);
        });
    }

    if (filtered.length === 0) {
        list.innerHTML = '<div class="pg-ss-empty">Tidak ada pelanggan ditemukan</div>';
        custHighlightIdx = -1;
        return;
    }

    var html = '';
    filtered.forEach(function(p, i) {
        var selClass = String(p.id) === String(currentVal) ? ' selected' : '';
        var hlClass = i === custHighlightIdx ? ' highlighted' : '';
        var hpLabel = p.no_hp ? p.no_hp : '-';
        html += '<div class="pg-ss-opt' + selClass + hlClass + '" data-id="' + p.id + '" onclick="selectCustomer(' + p.id + ')">';
        html += '<div><div class="pg-ss-opt-main">' + escHtml(p.nama_toko) + '</div>';
        html += '<div class="pg-ss-opt-sub">' + escHtml(p.nama_pemilik) + '</div></div>';
        html += '<div class="pg-ss-opt-meta">' + escHtml(hpLabel) + '</div>';
        html += '</div>';
    });
    list.innerHTML = html;
    custHighlightIdx = -1;
}

function selectCustomer(id) {
    var pg = PELANGGANS.find(function(p) { return p.id == id; });
    if (!pg) return;
    document.getElementById('pelanggan_id').value = pg.id;
    document.getElementById('custTriggerText').className = 'pg-ss-value';
    document.getElementById('custTriggerText').innerHTML = escHtml(pg.nama_toko) + ' <small>(' + escHtml(pg.nama_pemilik) + ')</small>';
    document.getElementById('custSelectedInfo').style.display = '';
    document.getElementById('custInfoText').innerHTML = '<strong>' + escHtml(pg.nama_toko) + '</strong> — ' + escHtml(pg.alamat || '-') + ' — ' + escHtml(pg.no_hp || '-');
    document.getElementById('pelanggan_err').style.display = 'none';
    closeCustDropdown();
    updateHutangInfo();
}

function clearCustSelection() {
    document.getElementById('pelanggan_id').value = '';
    document.getElementById('custTriggerText').className = 'pg-ss-placeholder';
    document.getElementById('custTriggerText').textContent = 'Ketik nama pelanggan...';
    document.getElementById('custSelectedInfo').style.display = 'none';
    closeCustDropdown();
    updateHutangInfo();
}

function custKeyNav(e) {
    var opts = document.querySelectorAll('#custList .pg-ss-opt');
    if (!opts.length) return;
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        custHighlightIdx = Math.min(custHighlightIdx + 1, opts.length - 1);
        updateCustHighlight(opts);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        custHighlightIdx = Math.max(custHighlightIdx - 1, 0);
        updateCustHighlight(opts);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (custHighlightIdx >= 0 && opts[custHighlightIdx]) {
            selectCustomer(parseInt(opts[custHighlightIdx].dataset.id));
        }
    } else if (e.key === 'Escape') {
        closeCustDropdown();
    }
}

function updateCustHighlight(opts) {
    opts.forEach(function(o, i) { o.classList.toggle('highlighted', i === custHighlightIdx); });
    if (opts[custHighlightIdx]) opts[custHighlightIdx].scrollIntoView({ block: 'nearest' });
}

document.addEventListener('click', function(e) {
    var wrap = document.getElementById('custSearchWrap');
    if (wrap && !wrap.contains(e.target)) closeCustDropdown();
});

// ===== Sales -> Product dropdown with vehicle stock =====
document.getElementById('sales_id').addEventListener('change', onSalesChange);

function onSalesChange() {
    var salesId = document.getElementById('sales_id').value;
    var produkSelect = document.getElementById('produk_id');
    var stokInfo = document.getElementById('stokInfo');
    stokInfo.style.display = 'none';

    produkSelect.innerHTML = '<option value="">Pilih Produk</option>';

    if (!salesId) {
        produkSelect.innerHTML = '<option value="">Pilih Sales terlebih dahulu</option>';
        document.getElementById('qtyUnit').textContent = '—';
        return;
    }

    var stockMap = vehicleStock[salesId] || {};
    var hasProduct = false;

    produks.forEach(function(p) {
        var vStock = stockMap[p.id] || 0;
        if (vStock > 0) {
            var opt = document.createElement('option');
            opt.value = p.id;
            opt.dataset.harga = p.harga_jual;
            opt.dataset.satuan = p.satuan;
            opt.dataset.vstock = vStock;
            opt.textContent = p.nama + ' — Stok: ' + vStock.toLocaleString('id-ID') + ' ' + p.satuan;
            produkSelect.appendChild(opt);
            hasProduct = true;
        }
    });

    if (!hasProduct) {
        produkSelect.innerHTML = '<option value="">Tidak ada stok di kendaraan sales ini</option>';
    }
}

document.getElementById('produk_id').addEventListener('change', onProdukChange);

function onProdukChange() {
    var select = document.getElementById('produk_id');
    var opt = select.options[select.selectedIndex];
    if (!opt || !opt.value) return;

    if (opt.dataset.harga) {
        document.getElementById('harga_satuan').value = formatCurrency(opt.dataset.harga);
    }
    document.getElementById('qtyUnit').textContent = opt.dataset.satuan || '—';

    var stokInfo = document.getElementById('stokInfo');
    var stokText = document.getElementById('stokInfoText');
    var vStock = parseInt(opt.dataset.vstock) || 0;
    stokInfo.style.display = 'block';
    stokText.textContent = 'Stok kendaraan: ' + vStock.toLocaleString('id-ID') + ' ' + (opt.dataset.satuan || '');

    document.getElementById('jumlah').max = vStock;
    calculateTotal();
}

function calculateTotal() {
    var jumlah = parseInt(document.getElementById('jumlah').value) || 0;
    var harga = parseInt(parseCurrency(document.getElementById('harga_satuan').value)) || 0;
    var total = jumlah * harga;
    document.getElementById('grandTotal').textContent = formatRp(total);
    calculateKembalian();
    updateHutangInfo();
}

function calculateKembalian() {
    var method = document.getElementById('tipeBayar').value;
    if (method !== 'tunai') return;
    var jumlah = parseInt(document.getElementById('jumlah').value) || 0;
    var harga = parseInt(parseCurrency(document.getElementById('harga_satuan').value)) || 0;
    var total = jumlah * harga;
    var bayar = parseInt(parseCurrency(document.getElementById('bayarTunaiInput').value)) || 0;
    var kembalianBox = document.getElementById('kembalianBox');
    var kembalianVal = document.getElementById('kembalianVal');
    if (bayar > 0 && bayar >= total) {
        kembalianBox.style.display = 'flex';
        kembalianVal.textContent = formatRp(bayar - total);
    } else {
        kembalianBox.style.display = 'none';
    }
}

// ===== Payment method toggle =====
function setPayment(method) {
    document.querySelectorAll('#paymentPills .pg-pay-pill').forEach(function(p) {
        p.classList.remove('active', 'hutang-active', 'transfer-active');
        if (p.dataset.method === method) {
            if (method === 'tunai') p.classList.add('active');
            else if (method === 'hutang') p.classList.add('hutang-active');
            else if (method === 'transfer') p.classList.add('transfer-active');
        }
    });
    document.getElementById('tipeBayar').value = method;
    document.getElementById('tunaiSection').style.display = method === 'tunai' ? 'block' : 'none';
    document.getElementById('hutangSection').style.display = method === 'hutang' ? 'block' : 'none';
    document.getElementById('transferSection').style.display = method === 'transfer' ? 'block' : 'none';
    updateHutangInfo();
}

function updateHutangInfo() {
    var method = document.getElementById('tipeBayar').value;
    var sel = document.getElementById('pelanggan_id');
    var infoBox = document.getElementById('hutangInfoBox');
    if (method !== 'hutang' || !sel || !sel.value) {
        infoBox.style.display = 'none';
        return;
    }
    // Note: hutang info is optional for gula — only shown if data available
    infoBox.style.display = 'none';
}

// ===== Form validation =====
document.getElementById('penjualanForm').addEventListener('submit', function(e) {
    var pelId = document.getElementById('pelanggan_id').value;
    if (!pelId) {
        e.preventDefault();
        document.getElementById('pelanggan_err').style.display = 'block';
        toggleCustDropdown();
        return false;
    }
});

// ===== Init: restore state from old input =====
(function() {
    // Restore customer
    var oldId = document.getElementById('pelanggan_id').value;
    if (oldId) {
        var pg = PELANGGANS.find(function(p) { return p.id == parseInt(oldId); });
        if (pg) {
            document.getElementById('custTriggerText').className = 'pg-ss-value';
            document.getElementById('custTriggerText').innerHTML = escHtml(pg.nama_toko) + ' <small>(' + escHtml(pg.nama_pemilik) + ')</small>';
            document.getElementById('custSelectedInfo').style.display = '';
            document.getElementById('custInfoText').innerHTML = '<strong>' + escHtml(pg.nama_toko) + '</strong> — ' + escHtml(pg.alamat || '-') + ' — ' + escHtml(pg.no_hp || '-');
        }
    }

    // Restore products from selected sales
    var salesId = document.getElementById('sales_id').value;
    if (salesId) {
        onSalesChange();
        var oldProdukId = '{{ old('produk_id', '') }}';
        if (oldProdukId) {
            document.getElementById('produk_id').value = oldProdukId;
            onProdukChange();
        }
    }

    calculateTotal();
})();
</script>
@endpush
@endsection
