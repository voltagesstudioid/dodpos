@extends('layouts.app', ['title' => 'Transaksi Penjualan - Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .pj-page { font-family:'Plus+Jakarta Sans',sans-serif; max-width:56rem; margin:0 auto; padding:1.25rem 1rem; }
    .pj-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; }
    .pj-hdr-icon { width:48px; height:48px; border-radius:13px; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(16,185,129,0.25); flex-shrink:0; }
    .pj-hdr-icon svg { width:24px; height:24px; stroke:#fff; fill:none; }
    .pj-hdr h1 { font-size:1.25rem; font-weight:800; color:#1e1b4b; margin:0; }
    .pj-hdr p { font-size:0.78rem; color:#10b981; margin:2px 0 0; font-weight:600; }
    .pj-back { font-size:0.78rem; color:#10b981; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; margin-bottom:1rem; }
    .pj-back:hover { text-decoration:underline; }
    .pj-card { background:#fff; border:1px solid #d1fae5; border-radius:16px; margin-bottom:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.04); position:relative; }
    .pj-card-hdr { padding:0.9rem 1.25rem; display:flex; align-items:center; gap:0.65rem; border-bottom:1px solid #ecfdf5; }
    .pj-card-dot { width:8px; height:8px; border-radius:50%; background:#10b981; flex-shrink:0; }
    .pj-card-title { font-size:0.82rem; font-weight:700; color:#1e1b4b; }
    .pj-card-body { padding:1.125rem 1.25rem; }
    .pj-row2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .pj-fg { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.85rem; }
    .pj-lbl { display:flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .pj-req { color:#ef4444; }
    .pj-inp, .pj-sel, .pj-txt { width:100%; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#fcfcfd; font-family:inherit; font-size:0.82rem; color:#0f172a; transition:all 0.2s; outline:none; box-sizing:border-box; }
    .pj-inp:focus, .pj-sel:focus, .pj-txt:focus { border-color:#10b981; background:#fff; box-shadow:0 0 0 3px rgba(16,185,129,0.1); }
    .pj-txt { resize:vertical; min-height:60px; }
    .pj-hint { font-size:0.68rem; color:#94a3b8; margin-top:0.2rem; }
    .pj-loading-info { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.75rem 1rem; display:flex; align-items:center; gap:0.75rem; margin-bottom:0.85rem; }
    .pj-loading-info .tag { font-family:'JetBrains Mono',monospace; font-size:0.72rem; font-weight:700; color:#059669; background:#d1fae5; padding:2px 8px; border-radius:6px; }
    .pj-loading-info .info { font-size:0.78rem; color:#475569; }

    /* Items */
    .pj-item-card { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.85rem 1rem; margin-bottom:0.65rem; transition:all 0.15s; }
    .pj-item-card:hover { border-color:#10b981; }
    .pj-item-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.5rem; }
    .pj-item-name { font-size:0.82rem; font-weight:700; color:#1e1b4b; }
    .pj-item-meta { font-size:0.68rem; color:#94a3b8; margin-top:1px; }
    .pj-item-stock { font-size:0.72rem; font-weight:700; color:#059669; background:#d1fae5; padding:2px 8px; border-radius:6px; white-space:nowrap; }
    .pj-item-row { display:grid; grid-template-columns:1fr auto 1fr auto; gap:0.5rem; align-items:end; }
    .pj-item-row label { font-size:0.65rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:2px; display:block; }
    .pj-qty-wrap { display:flex; align-items:center; gap:0; border:1.5px solid #e2e8f0; border-radius:8px; overflow:hidden; }
    .pj-qty-btn { width:30px; height:32px; border:none; background:#f1f5f9; color:#475569; font-size:1rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.15s; }
    .pj-qty-btn:hover { background:#10b981; color:#fff; }
    .pj-qty-inp { width:48px; height:32px; border:none; border-left:1px solid #e2e8f0; border-right:1px solid #e2e8f0; text-align:center; font-family:inherit; font-size:0.82rem; font-weight:700; color:#0f172a; outline:none; background:#fff; }
    .pj-price-inp { padding:0.4rem 0.6rem; border:1.5px solid #e2e8f0; border-radius:8px; font-family:inherit; font-size:0.78rem; font-weight:700; color:#0f172a; text-align:right; width:100%; outline:none; box-sizing:border-box; }
    .pj-price-inp:focus { border-color:#10b981; }
    .pj-item-sub { font-size:0.78rem; font-weight:800; color:#10b981; white-space:nowrap; min-width:90px; text-align:right; }
    .pj-item-check { width:18px; height:18px; accent-color:#10b981; cursor:pointer; }
    .pj-item-disabled { opacity:0.5; }

    /* Summary */
    .pj-summary { padding:1rem 1.25rem; border-top:1px solid #ecfdf5; display:flex; justify-content:space-between; align-items:center; }
    .pj-sum-label { font-size:0.72rem; color:#94a3b8; font-weight:600; }
    .pj-sum-value { font-size:1.1rem; font-weight:800; color:#059669; }

    /* Actions */
    .pj-actions { display:flex; gap:0.75rem; justify-content:flex-end; margin-top:0.5rem; }
    .pj-btn { display:inline-flex; align-items:center; gap:0.4rem; padding:0.65rem 1.25rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; }
    .pj-btn-ghost { background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; }
    .pj-btn-ghost:hover { background:#e2e8f0; }
    .pj-btn-primary { background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.25); }
    .pj-btn-primary:hover { box-shadow:0 4px 16px rgba(16,185,129,0.35); }
    .pj-btn:disabled { opacity:0.5; cursor:not-allowed; }

    /* Payment method pills */
    .pj-pay-pills { display:flex; gap:0.5rem; flex-wrap:wrap; }
    .pj-pay-pill { padding:0.5rem 1rem; border-radius:10px; border:1.5px solid #e2e8f0; font-size:0.78rem; font-weight:700; color:#64748b; cursor:pointer; background:#fff; transition:all 0.15s; }
    .pj-pay-pill.active { background:#d1fae5; border-color:#10b981; color:#059669; }
    .pj-pay-pill:hover { border-color:#10b981; }

    /* Transfer section */
    .pj-transfer-section { display:none; background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:1rem 1.125rem; margin-top:0.75rem; }
    .pj-transfer-title { font-size:0.72rem; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.65rem; display:flex; align-items:center; gap:0.4rem; }
    .pj-file-input { width:100%; padding:0.5rem; border:1.5px dashed #93c5fd; border-radius:8px; font-size:0.78rem; font-family:inherit; color:#1e40af; background:#f8faff; cursor:pointer; }
    .pj-file-input:hover { border-color:#3b82f6; background:#eff6ff; }
    .pj-file-input::-webkit-file-upload-button { background:#dbeafe; color:#1d4ed8; border:none; padding:0.35rem 0.75rem; border-radius:6px; font-size:0.72rem; font-weight:600; cursor:pointer; margin-right:0.5rem; }

    /* Searchable select */
    .pj-ss-wrap { position:relative; }
    .pj-ss-trigger { display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#fcfcfd; cursor:pointer; transition:all 0.2s; min-height:42px; }
    .pj-ss-trigger:hover, .pj-ss-wrap.open .pj-ss-trigger { border-color:#10b981; background:#fff; }
    .pj-ss-wrap.open .pj-ss-trigger { box-shadow:0 0 0 3px rgba(16,185,129,0.1); }
    .pj-ss-placeholder { font-size:0.82rem; color:#94a3b8; }
    .pj-ss-value { font-size:0.82rem; color:#0f172a; font-weight:600; }
    .pj-ss-value small { font-weight:400; color:#64748b; font-size:0.72rem; }
    .pj-ss-chevron { width:16px; height:16px; stroke:#94a3b8; flex-shrink:0; transition:transform 0.2s; }
    .pj-ss-wrap.open .pj-ss-chevron { transform:rotate(180deg); }
    .pj-ss-dropdown { position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff; border:1.5px solid #e2e8f0; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.1); z-index:100; display:none; overflow:hidden; }
    .pj-ss-wrap.open .pj-ss-dropdown { display:block; }
    .pj-ss-search { padding:0.5rem 0.75rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.5rem; }
    .pj-ss-search svg { width:16px; height:16px; stroke:#94a3b8; flex-shrink:0; }
    .pj-ss-search input { flex:1; border:none; outline:none; font-size:0.8rem; font-family:inherit; color:#0f172a; }
    .pj-ss-search input::placeholder { color:#94a3b8; }
    .pj-ss-list { max-height:200px; overflow-y:auto; }
    .pj-ss-opt { padding:0.55rem 0.75rem; cursor:pointer; transition:background 0.1s; display:flex; justify-content:space-between; align-items:center; }
    .pj-ss-opt:hover, .pj-ss-opt.highlighted { background:#f0fdf4; }
    .pj-ss-opt.selected { background:#d1fae5; }
    .pj-ss-opt-main { font-size:0.8rem; font-weight:600; color:#1e1b4b; }
    .pj-ss-opt-sub { font-size:0.68rem; color:#94a3b8; margin-top:1px; }
    .pj-ss-opt-meta { font-size:0.65rem; color:#64748b; text-align:right; white-space:nowrap; }
    .pj-ss-empty { padding:1rem; text-align:center; font-size:0.78rem; color:#94a3b8; }
    .pj-ss-clear { padding:0.4rem 0.75rem; border-top:1px solid #f1f5f9; text-align:center; font-size:0.72rem; font-weight:700; color:#ef4444; cursor:pointer; }
    .pj-ss-clear:hover { background:#fef2f2; }

    @media (max-width:640px) { .pj-item-row { grid-template-columns:1fr 1fr; } .pj-row2 { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="pj-page">
    <div class="pj-hdr">
        <div class="pj-hdr-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div>
            <h1>Transaksi Penjualan</h1>
            <p>Catat penjualan barang dari stok kendaraan</p>
        </div>
    </div>

    <a href="{{ route('pasgar.loading.index') }}" class="pj-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>

    <form action="{{ route('pasgar.penjualan.store') }}" method="POST" id="penjualanForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="loading_id" value="{{ $loading->id }}">

        {{-- Server-side validation errors --}}
        @if($errors->any())
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;">
            @foreach($errors->all() as $error)
            <div style="color:#991b1b;font-size:0.8rem;font-weight:600;">{{ $error }}</div>
            @endforeach
        </div>
        @endif
        @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;">
            <div style="color:#991b1b;font-size:0.8rem;font-weight:600;">{{ session('error') }}</div>
        </div>
        @endif

        {{-- Loading Info --}}
        <div class="pj-card">
            <div class="pj-card-hdr">
                <div class="pj-card-dot"></div>
                <div class="pj-card-title">Stok Kendaraan</div>
            </div>
            <div class="pj-card-body">
                <div class="pj-loading-info">
                    <span class="tag">{{ $loading->nomor_loading }}</span>
                    <span class="info">{{ $salesProfile->nama }} &middot; {{ $loading->loaded_at?->format('d/m/Y') ?? '' }}</span>
                </div>
                <div class="pj-hint">Pilih barang yang akan dijual, masukkan jumlah dan harga.</div>
            </div>
        </div>

        {{-- Customer --}}
        <div class="pj-card">
            <div class="pj-card-hdr">
                <div class="pj-card-dot"></div>
                <div class="pj-card-title">Pelanggan</div>
            </div>
            <div class="pj-card-body">
                <div class="pj-fg" style="margin-bottom:0.5rem;">
                    <label class="pj-lbl">Tipe Pelanggan</label>
                    <div class="pj-pay-pills" id="custTypePills">
@php $showTerdaftar = !empty(old('pelanggan_id')); @endphp
                        <div class="pj-pay-pill{{ !$showTerdaftar ? ' active' : '' }}" data-type="umum" onclick="setCustType('umum')">Umum (Tanpa Pelanggan)</div>
                        <div class="pj-pay-pill{{ $showTerdaftar ? ' active' : '' }}" data-type="terdaftar" onclick="setCustType('terdaftar')">Pelanggan Terdaftar</div>
                    </div>
                </div>
                <div id="custTerdaftar" style="display:{{ $showTerdaftar ? 'block' : 'none' }};">
                    <div class="pj-fg">
                        <label class="pj-lbl">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Pilih Pelanggan
                        </label>
                        <input type="hidden" name="pelanggan_id" id="pelangganSelect" value="{{ old('pelanggan_id', '') }}">
                        <div class="pj-ss-wrap" id="custSearchWrap">
                            <div class="pj-ss-trigger" onclick="toggleCustDropdown()">
                                <span id="custTriggerText" class="pj-ss-placeholder">Ketik nama pelanggan...</span>
                                <svg class="pj-ss-chevron" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                            <div class="pj-ss-dropdown">
                                <div class="pj-ss-search">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <input type="text" id="custSearchInput" placeholder="Cari nama toko atau pemilik..." oninput="filterCustomers(this.value)" onkeydown="custKeyNav(event)">
                                </div>
                                <div class="pj-ss-list" id="custList"></div>
                                <div class="pj-ss-clear" id="custClearBtn" onclick="clearCustSelection()" style="display:none;">✕ Hapus Pilihan</div>
                            </div>
                        </div>
                        <div id="custSelectedInfo" style="display:none;margin-top:0.4rem;">
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:0.5rem 0.75rem;font-size:0.75rem;color:#065f46;display:flex;align-items:center;gap:0.5rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span id="custInfoText"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="custUmum" style="display:{{ $showTerdaftar ? 'none' : 'block' }};">
                    <div class="pj-row2">
                        <div class="pj-fg">
                            <label class="pj-lbl">Nama Pembeli</label>
                            <input type="text" name="nama_pelanggan" class="pj-inp" placeholder="Nama pembeli (opsional)...">
                        </div>
                        <div class="pj-fg">
                            <label class="pj-lbl">No HP</label>
                            <input type="text" name="telepon_pelanggan" class="pj-inp" placeholder="No HP (opsional)...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="pj-card">
            <div class="pj-card-hdr">
                <div class="pj-card-dot"></div>
                <div class="pj-card-title">Barang yang Dijual</div>
                <span id="itemCountBadge" style="margin-left:auto;font-size:0.7rem;font-weight:700;background:#d1fae5;color:#059669;padding:2px 8px;border-radius:6px;display:none;">0 barang</span>
            </div>
            <div class="pj-card-body" id="itemsBody">
                {{-- Rendered by JS --}}
            </div>
            <div class="pj-summary">
                <div>
                    <div class="pj-sum-label"><span id="totalItems">0</span> barang dijual</div>
                </div>
                <div style="text-align:right;">
                    <div class="pj-sum-label">Total</div>
                    <div class="pj-sum-value" id="grandTotal">Rp 0</div>
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="pj-card">
            <div class="pj-card-hdr">
                <div class="pj-card-dot"></div>
                <div class="pj-card-title">Pembayaran</div>
            </div>
            <div class="pj-card-body">
                <div class="pj-fg" style="margin-bottom:0.5rem;">
                    <label class="pj-lbl">Metode Bayar <span class="pj-req">*</span></label>
                    <div class="pj-pay-pills" id="paymentPills">
                        <div class="pj-pay-pill{{ old('metode_bayar', 'tunai') === 'tunai' ? ' active' : '' }}" data-method="tunai" onclick="setPayment('tunai')">💵 Tunai</div>
                        <div class="pj-pay-pill{{ old('metode_bayar') === 'transfer' ? ' active' : '' }}" data-method="transfer" onclick="setPayment('transfer')">🏦 Transfer</div>
                    </div>
                    <input type="hidden" name="metode_bayar" id="metodeBayar" value="{{ old('metode_bayar', 'tunai') }}">
                </div>

                {{-- Transfer section --}}
                <div class="pj-transfer-section" id="transferSection" style="display:{{ old('metode_bayar') === 'transfer' ? 'block' : 'none' }};">
                    <div class="pj-transfer-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        Detail Transfer
                    </div>
                    <div class="pj-row2">
                        <div class="pj-fg">
                            <label class="pj-lbl">ID Transaksi / No Referensi</label>
                            <input type="text" name="id_transaksi_transfer" class="pj-inp" id="idTransaksiTransfer" value="{{ old('id_transaksi_transfer', '') }}" placeholder="Contoh: TRX123456789...">
                            <span class="pj-hint">Nomor referensi transaksi bank</span>
                        </div>
                        <div class="pj-fg">
                            <label class="pj-lbl">Foto Bukti Transfer</label>
                            <input type="file" name="foto_bukti_transfer" class="pj-file-input" id="fotoBuktiTransfer" accept="image/jpeg,image/jpg,image/png,image/webp">
                            <span class="pj-hint">Format: JPG, PNG, WebP (maks 4MB)</span>
                        </div>
                    </div>
                </div>

                <div class="pj-fg" style="margin-bottom:0;margin-top:0.75rem;">
                    <label class="pj-lbl">Catatan</label>
                    <textarea name="catatan" class="pj-txt" placeholder="Catatan tambahan (opsional)...">{{ old('catatan', '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pj-actions">
            <a href="{{ route('pasgar.loading.index') }}" class="pj-btn pj-btn-ghost">Batal</a>
            <button type="submit" class="pj-btn pj-btn-primary" id="submitBtn" disabled>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Available items from loaded stock
const AVAILABLE_ITEMS = {!! $itemsJson !!};
const PELANGGANS = {!! $pelanggansJson !!};
const IS_SALES = {{ $isSalesRole ? 'true' : 'false' }};

// State: {loading_item_id, product_id, product_name, sku, category, qty_sisa, unit_conversion_id, conversions, current_conversion, sumber, qty, harga, selected}
let items = [];

function init() {
    items = AVAILABLE_ITEMS.map(ai => ({
        loading_item_id: ai.loading_item_id,
        product_id: ai.product_id,
        product_name: ai.product_name,
        sku: ai.sku,
        category: ai.category,
        qty_sisa: ai.qty_sisa,
        unit_conversion_id: ai.unit_conversion_id,
        conversions: ai.conversions || [],
        current_conversion: ai.current_conversion,
        sumber: ai.sumber,
        qty: 0,
        harga: ai.current_conversion ? ai.current_conversion.price : 0,
        selected: false,
    }));
    renderItems();

    // Restore customer selection if old input present (after validation error)
    const selVal = document.getElementById('pelangganSelect').value;
    if (selVal) {
        const pgId = parseInt(selVal);
        const pg = PELANGGANS.find(p => p.id === pgId);
        if (pg) {
            document.getElementById('custTriggerText').className = 'pj-ss-value';
            document.getElementById('custTriggerText').innerHTML = escHtml(pg.nama_toko) + ' <small>(' + escHtml(pg.nama_pemilik) + ')</small>';
            document.getElementById('custSelectedInfo').style.display = '';
            document.getElementById('custInfoText').innerHTML = '<strong>' + escHtml(pg.nama_toko) + '</strong> — ' + escHtml(pg.alamat || '-') + ' — ' + escHtml(pg.no_hp || '-');
        }
    }

    // Restore hutang info - no longer needed
}

function formatRp(n) { return 'Rp ' + n.toLocaleString('id-ID'); }
function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

function toggleItem(idx) {
    items[idx].selected = !items[idx].selected;
    if (items[idx].selected && items[idx].qty === 0) items[idx].qty = 1;
    if (!items[idx].selected) items[idx].qty = 0;
    renderItems();
}

function changeQty(idx, delta) {
    if (!items[idx].selected) return;
    const nq = items[idx].qty + delta;
    if (nq < 0) return;
    if (nq > items[idx].qty_sisa) return;
    items[idx].qty = nq;
    if (nq === 0) items[idx].selected = false;
    renderItems();
}

function setQty(idx, val) {
    if (!items[idx].selected) return;
    let n = parseInt(val) || 0;
    n = Math.max(0, Math.min(items[idx].qty_sisa, n));
    items[idx].qty = n;
    if (n === 0) items[idx].selected = false;
    renderItems();
}

function setHarga(idx, val) {
    const n = parseInt(val.replace(/[^0-9]/g, '')) || 0;
    items[idx].harga = n;
    renderItems();
}

function renderItems() {
    const body = document.getElementById('itemsBody');
    const badge = document.getElementById('itemCountBadge');
    const submitBtn = document.getElementById('submitBtn');
    let selectedCount = 0;
    let grandTotal = 0;
    let html = '';
    let hiddenHtml = '';
    let hIdx = 0;

    items.forEach((item, idx) => {
        const subtotal = item.qty * item.harga;
        if (item.selected && item.qty > 0) {
            selectedCount++;
            grandTotal += subtotal;
        }

        const unitLabel = item.current_conversion ? item.current_conversion.unit_name : '';
        const checked = item.selected ? 'checked' : '';
        const cardClass = item.selected ? '' : 'pj-item-disabled';

        html += '<div class="pj-item-card ' + cardClass + '">';
        html += '<div class="pj-item-top">';
        html += '<div style="display:flex;align-items:center;gap:0.5rem;">';
        html += '<input type="checkbox" class="pj-item-check" ' + checked + ' onchange="toggleItem(' + idx + ')">';
        html += '<div><div class="pj-item-name">' + escHtml(item.product_name) + '</div>';
        html += '<div class="pj-item-meta">' + (item.sku ? escHtml(item.sku) + ' · ' : '') + escHtml(item.category) + (unitLabel ? ' · /' + escHtml(unitLabel) : '') + '</div>';
        html += '</div></div>';
        html += '<span class="pj-item-stock">Sisa: ' + item.qty_sisa + '</span>';
        html += '</div>';

        html += '<div class="pj-item-row">';
        html += '<div><label>Qty</label>';
        html += '<div class="pj-qty-wrap">';
        html += '<button type="button" class="pj-qty-btn" onclick="changeQty(' + idx + ',-1)">−</button>';
        html += '<input type="text" class="pj-qty-inp" value="' + item.qty + '" onchange="setQty(' + idx + ',this.value)">';
        html += '<button type="button" class="pj-qty-btn" onclick="changeQty(' + idx + ',1)">+</button>';
        html += '</div></div>';

        html += '<div><label>Harga</label>';
        if (IS_SALES) {
            html += '<div style="padding:0.4rem 0.6rem;background:#f1f5f9;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:0.78rem;font-weight:700;color:#64748b;text-align:right;">' + item.harga.toLocaleString('id-ID') + '</div>';
        } else {
            html += '<input type="text" class="pj-price-inp" value="' + item.harga + '" onchange="setHarga(' + idx + ',this.value)">';
        }
        html += '</div>';

        html += '<div style="display:flex;align-items:end;justify-content:flex-end;">';
        html += '<div class="pj-item-sub">' + formatRp(subtotal) + '</div>';
        html += '</div>';

        html += '<div></div>';
        html += '</div></div>';

        // Hidden inputs for selected items
        if (item.selected && item.qty > 0) {
            hiddenHtml += '<input type="hidden" name="items[' + hIdx + '][loading_item_id]" value="' + item.loading_item_id + '">';
            hiddenHtml += '<input type="hidden" name="items[' + hIdx + '][product_id]" value="' + item.product_id + '">';
            hiddenHtml += '<input type="hidden" name="items[' + hIdx + '][unit_conversion_id]" value="' + (item.unit_conversion_id || '') + '">';
            hiddenHtml += '<input type="hidden" name="items[' + hIdx + '][qty]" value="' + item.qty + '">';
            hiddenHtml += '<input type="hidden" name="items[' + hIdx + '][harga]" value="' + item.harga + '">';
            hIdx++;
        }
    });

    body.innerHTML = html;

    // Hidden inputs container
    let container = document.getElementById('pjHiddenInputs');
    if (!container) {
        container = document.createElement('div');
        container.id = 'pjHiddenInputs';
        document.getElementById('penjualanForm').appendChild(container);
    }
    container.innerHTML = hiddenHtml;

    // Update summary
    document.getElementById('totalItems').textContent = selectedCount;
    document.getElementById('grandTotal').textContent = formatRp(grandTotal);

    if (selectedCount > 0) {
        badge.style.display = '';
        badge.textContent = selectedCount + ' barang';
        submitBtn.disabled = false;
    } else {
        badge.style.display = 'none';
        submitBtn.disabled = true;
    }
}

// Customer type toggle
function setCustType(type) {
    document.querySelectorAll('#custTypePills .pj-pay-pill').forEach(p => {
        p.classList.toggle('active', p.dataset.type === type);
    });
    document.getElementById('custTerdaftar').style.display = type === 'terdaftar' ? 'block' : 'none';
    document.getElementById('custUmum').style.display = type === 'umum' ? 'block' : 'none';
    if (type === 'terdaftar') {
        setTimeout(() => document.getElementById('custSearchInput')?.focus(), 100);
    }
}

// --- Searchable Customer Dropdown ---
let custHighlightIdx = -1;

function toggleCustDropdown() {
    const wrap = document.getElementById('custSearchWrap');
    const isOpen = wrap.classList.contains('open');
    if (isOpen) {
        closeCustDropdown();
    } else {
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
    const list = document.getElementById('custList');
    const clearBtn = document.getElementById('custClearBtn');
    const currentVal = document.getElementById('pelangganSelect').value;
    clearBtn.style.display = currentVal ? '' : 'none';

    let filtered = PELANGGANS;
    if (q) {
        filtered = PELANGGANS.filter(p =>
            p.nama_toko.toLowerCase().includes(q) ||
            p.nama_pemilik.toLowerCase().includes(q) ||
            (p.no_hp && p.no_hp.includes(q)) ||
            (p.alamat && p.alamat.toLowerCase().includes(q))
        );
    }

    if (filtered.length === 0) {
        list.innerHTML = '<div class="pj-ss-empty">Tidak ada pelanggan ditemukan</div>';
        custHighlightIdx = -1;
        return;
    }

    let html = '';
    const isHutang = false;
    filtered.forEach((p, i) => {
        const selClass = String(p.id) === String(currentVal) ? ' selected' : '';
        const hlClass = i === custHighlightIdx ? ' highlighted' : '';
        const hpLabel = p.no_hp ? p.no_hp : '-';
        html += '<div class="pj-ss-opt' + selClass + hlClass + '" data-id="' + p.id + '" onclick="selectCustomer(' + p.id + ')">';
        html += '<div><div class="pj-ss-opt-main">' + escHtml(p.nama_toko) + '</div>';
        html += '<div class="pj-ss-opt-sub">' + escHtml(p.nama_pemilik) + '</div>';
        html += '<div class="pj-ss-opt-meta">' + escHtml(hpLabel) + '</div>';
        html += '</div>';
    });
    list.innerHTML = html;
    custHighlightIdx = -1;
}

function selectCustomer(id) {
    const pg = PELANGGANS.find(p => p.id === id);
    if (!pg) return;
    document.getElementById('pelangganSelect').value = id;
    document.getElementById('custTriggerText').className = 'pj-ss-value';
    document.getElementById('custTriggerText').innerHTML = escHtml(pg.nama_toko) + ' <small>(' + escHtml(pg.nama_pemilik) + ')</small>';
    document.getElementById('custSelectedInfo').style.display = '';
    let infoHtml = '<strong>' + escHtml(pg.nama_toko) + '</strong> — ' + escHtml(pg.alamat || '-') + ' — ' + escHtml(pg.no_hp || '-');
    document.getElementById('custInfoText').innerHTML = infoHtml;
    closeCustDropdown();
}

function clearCustSelection() {
    document.getElementById('pelangganSelect').value = '';
    document.getElementById('custTriggerText').className = 'pj-ss-placeholder';
    document.getElementById('custTriggerText').textContent = 'Ketik nama pelanggan...';
    document.getElementById('custSelectedInfo').style.display = 'none';
    closeCustDropdown();
}

function custKeyNav(e) {
    const opts = document.querySelectorAll('#custList .pj-ss-opt');
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
    opts.forEach((o, i) => o.classList.toggle('highlighted', i === custHighlightIdx));
    if (opts[custHighlightIdx]) opts[custHighlightIdx].scrollIntoView({ block: 'nearest' });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('custSearchWrap');
    if (wrap && !wrap.contains(e.target)) closeCustDropdown();
});

// Payment method toggle
function setPayment(method) {
    document.querySelectorAll('#paymentPills .pj-pay-pill').forEach(p => {
        p.classList.remove('active');
        if (p.dataset.method === method) p.classList.add('active');
    });
    document.getElementById('metodeBayar').value = method;
    document.getElementById('transferSection').style.display = method === 'transfer' ? 'block' : 'none';
}

// Hutang-related functions removed

// Form validation
document.getElementById('penjualanForm').addEventListener('submit', function(e) {
    const selected = items.filter(i => i.selected && i.qty > 0);
    if (selected.length === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 barang untuk dijual.');
        return false;
    }
    const method = document.getElementById('metodeBayar').value;
});

// Initialize
init();
</script>
@endpush
@endsection
