@php
    $isEdit = isset($loading) && $loading;
    $pageTitle = $isEdit ? 'Edit Permintaan Loading - Pasgar' : 'Permintaan Loading Baru - Pasgar';
@endphp
@extends('layouts.app', ['title' => $pageTitle])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .lc-page { font-family:'Plus Jakarta Sans',sans-serif; max-width:56rem; margin:0 auto; padding:1.25rem 1rem; }

    /* Header */
    .lc-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; }
    .lc-hdr-icon { width:48px; height:48px; border-radius:13px; background:linear-gradient(135deg,#6366f1,#4338ca); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 14px rgba(79,70,229,0.25); flex-shrink:0; }
    .lc-hdr-icon svg { width:24px; height:24px; stroke:#fff; fill:none; }
    .lc-hdr h1 { font-size:1.25rem; font-weight:800; color:#1e1b4b; margin:0; }
    .lc-hdr p { font-size:0.78rem; color:#6366f1; margin:2px 0 0; font-weight:600; }
    .lc-back { font-size:0.78rem; color:#6366f1; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; margin-bottom:1rem; }
    .lc-back:hover { text-decoration:underline; }

    /* Cards */
    .lc-card { background:#fff; border:1px solid #e0e7ff; border-radius:16px; margin-bottom:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.04); position:relative; }
    .lc-card-hdr { padding:0.9rem 1.25rem; display:flex; align-items:center; gap:0.65rem; border-bottom:1px solid #f1f5f9; }
    .lc-card-dot { width:8px; height:8px; border-radius:50%; background:#6366f1; flex-shrink:0; }
    .lc-card-title { font-size:0.82rem; font-weight:700; color:#1e1b4b; }
    .lc-card-body { padding:1.125rem 1.25rem; }

    /* Seller badge */
    .lc-seller { background:linear-gradient(135deg,#f5f3ff,#eef2ff); border:1px solid #e0e7ff; border-radius:12px; padding:0.85rem 1rem; display:flex; align-items:center; gap:0.75rem; }
    .lc-seller-av { width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,#6366f1,#4338ca); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.05rem; flex-shrink:0; }
    .lc-seller-name { font-weight:700; color:#1e1b4b; font-size:0.85rem; }
    .lc-seller-code { font-family:'JetBrains Mono',monospace; font-size:0.7rem; color:#6366f1; font-weight:600; }

    /* Form fields */
    .lc-row2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .lc-fg { display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.85rem; }
    .lc-lbl { display:flex; align-items:center; gap:5px; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; }
    .lc-req { color:#ef4444; }
    .lc-inp, .lc-sel, .lc-txt {
        width:100%; padding:0.6rem 0.8rem; border:1.5px solid #e2e8f0; border-radius:10px;
        background:#fcfcfd; font-family:inherit; font-size:0.82rem; color:#0f172a;
        transition:all 0.2s; outline:none; box-sizing:border-box;
    }
    .lc-inp:focus, .lc-sel:focus, .lc-txt:focus { border-color:#6366f1; background:#fff; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
    .lc-txt { resize:vertical; min-height:60px; }
    .lc-inp::placeholder, .lc-txt::placeholder { color:#cbd5e1; }
    .lc-err { color:#ef4444; font-size:0.72rem; font-weight:600; margin-top:2px; }
    .lc-hint { font-size:0.68rem; color:#94a3b8; margin-top:0.2rem; }

    /* Product Search Box */
    .lc-search-wrap { position:relative; margin-bottom:1rem; }
    .lc-search-box { position:relative; }
    .lc-search-ico { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
    .lc-search-input {
        width:100%; padding:0.7rem 0.85rem 0.7rem 2.5rem; border:1.5px solid #e2e8f0; border-radius:12px;
        background:#fff; font-family:inherit; font-size:0.85rem; color:#0f172a;
        transition:all 0.2s; outline:none; box-sizing:border-box;
    }
    .lc-search-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .lc-search-input::placeholder { color:#94a3b8; }

    /* Search Results Dropdown */
    .lc-search-results {
        position:absolute; left:0; right:0; top:100%; z-index:50;
        background:#fff; border:1.5px solid #e2e8f0; border-top:none; border-radius:0 0 12px 12px;
        max-height:260px; overflow-y:auto; display:none; box-shadow:0 10px 30px rgba(0,0,0,0.1);
    }
    .lc-search-results.active { display:block; }
    .lc-sr-item {
        padding:0.7rem 1rem; cursor:pointer; display:flex; align-items:center; gap:0.75rem;
        border-bottom:1px solid #f8fafc; transition:background 0.15s;
    }
    .lc-sr-item:hover { background:#f5f3ff; }
    .lc-sr-item:last-child { border-bottom:none; }
    .lc-sr-info { flex:1; min-width:0; }
    .lc-sr-name { font-size:0.82rem; font-weight:700; color:#1e1b4b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .lc-sr-meta { display:flex; align-items:center; gap:0.5rem; margin-top:2px; flex-wrap:wrap; }
    .lc-sr-tag { font-size:0.65rem; padding:1px 6px; border-radius:4px; font-weight:600; }
    .lc-sr-tag.cat { background:#ede9fe; color:#7c3aed; }
    .lc-sr-tag.sku { background:#f1f5f9; color:#64748b; font-family:'JetBrains Mono',monospace; }
    .lc-sr-tag.stock { background:#ecfdf5; color:#059669; }
    .lc-sr-tag.stock.low { background:#fef2f2; color:#dc2626; }
    .lc-sr-tag.gudang { background:#dbeafe; color:#1d4ed8; }
    .lc-sr-tag.grosir { background:#fef3c7; color:#92400e; }
    .lc-sr-tag.unit { background:#f0fdf4; color:#15803d; font-style:italic; }
    .lc-sr-price { font-size:0.75rem; font-weight:700; color:#4f46e5; white-space:nowrap; }
    .lc-sr-add { width:30px; height:30px; border-radius:8px; border:1.5px solid #c7d2fe; background:#eef2ff; color:#4f46e5; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:800; flex-shrink:0; transition:all 0.15s; }
    .lc-sr-add:hover { background:#6366f1; color:#fff; border-color:#6366f1; }
    .lc-sr-add.added { background:#10b981; color:#fff; border-color:#10b981; pointer-events:none; }
    .lc-sr-empty { padding:1.5rem; text-align:center; color:#94a3b8; font-size:0.82rem; }
    .lc-search-wrap .lc-search-results::-webkit-scrollbar { width:6px; }
    .lc-search-wrap .lc-search-results::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:3px; }

    /* Items Table */
    .lc-items-empty { text-align:center; padding:2rem 1rem; color:#94a3b8; }
    .lc-items-empty svg { width:48px; height:48px; stroke:#cbd5e1; margin-bottom:0.5rem; }
    .lc-items-empty p { font-size:0.82rem; margin:0; }

    .lc-items-table { width:100%; border-collapse:collapse; }
    .lc-items-table th { font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; padding:0.6rem 0.75rem; border-bottom:2px solid #e2e8f0; text-align:left; }
    .lc-items-table th.num { text-align:center; }
    .lc-items-table th.right { text-align:right; }
    .lc-items-table td { padding:0.65rem 0.75rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .lc-items-table td.num { text-align:center; }
    .lc-items-table td.right { text-align:right; }
    .lc-items-table tr:last-child td { border-bottom:none; }

    .lc-it-name { font-size:0.82rem; font-weight:600; color:#1e1b4b; }
    .lc-it-sub { font-size:0.68rem; color:#94a3b8; margin-top:1px; }
    .lc-unit-sel { padding:3px 6px; border:1.5px solid #e2e8f0; border-radius:6px; font-size:0.72rem; font-family:inherit; font-weight:600; color:#475569; background:#fff; cursor:pointer; outline:none; max-width:100px; }
    .lc-unit-sel:focus { border-color:#6366f1; }
    .lc-it-price { font-size:0.78rem; font-weight:700; color:#475569; }
    .lc-it-subtotal { font-size:0.78rem; font-weight:700; color:#4f46e5; }

    .lc-qty-wrap { display:flex; align-items:center; gap:0; }
    .lc-qty-btn { width:30px; height:30px; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:800; transition:all 0.15s; }
    .lc-qty-btn:first-child { border-radius:8px 0 0 8px; }
    .lc-qty-btn:last-child { border-radius:0 8px 8px 0; }
    .lc-qty-btn:hover { background:#6366f1; color:#fff; border-color:#6366f1; }
    .lc-qty-inp {
        width:48px; height:30px; text-align:center; border:1.5px solid #e2e8f0; border-left:none; border-right:none;
        font-family:'JetBrains Mono',monospace; font-size:0.78rem; font-weight:700; color:#0f172a; outline:none;
        -moz-appearance:textfield;
    }
    .lc-qty-inp::-webkit-outer-spin-button, .lc-qty-inp::-webkit-inner-spin-button { -webkit-appearance:none; margin:0; }

    .lc-remove { width:30px; height:30px; border-radius:8px; border:1px solid #fecaca; background:#fff; color:#ef4444; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.85rem; transition:all 0.15s; }
    .lc-remove:hover { background:#fee2e2; }

    /* Summary */
    .lc-summary { display:flex; align-items:center; justify-content:space-between; padding:0.85rem 1.25rem; background:linear-gradient(135deg,#f5f3ff,#eef2ff); border-top:1px solid #e0e7ff; }
    .lc-sum-label { font-size:0.75rem; font-weight:700; color:#64748b; }
    .lc-sum-value { font-size:0.85rem; font-weight:800; color:#4f46e5; }
    .lc-sum-value.big { font-size:1rem; }

    /* Actions */
    .lc-actions { display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1rem; }
    .lc-btn { padding:0.7rem 1.5rem; border-radius:12px; font-size:0.82rem; font-weight:700; border:none; cursor:pointer; transition:all 0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-family:inherit; }
    .lc-btn-primary { background:linear-gradient(135deg,#6366f1,#4338ca); color:#fff; box-shadow:0 4px 14px rgba(79,70,229,0.25); }
    .lc-btn-primary:hover { box-shadow:0 6px 20px rgba(79,70,229,0.35); transform:translateY(-1px); }
    .lc-btn-primary:disabled { opacity:0.5; cursor:not-allowed; transform:none; box-shadow:none; }
    .lc-btn-ghost { background:transparent; border:1.5px solid #e2e8f0; color:#64748b; }
    .lc-btn-ghost:hover { background:#f8fafc; color:#0f172a; }

    @media(max-width:640px) {
        .lc-row2 { grid-template-columns:1fr; }
        .lc-items-table th:nth-child(2), .lc-items-table td:nth-child(2) { display:none; }
    }
</style>
@endpush

@section('content')
<div class="lc-page">
    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #86efac;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#166534;font-size:0.8rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;color:#991b1b;font-size:0.8rem;">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;">
        @foreach($errors->all() as $error)
        <div style="color:#991b1b;font-size:0.8rem;font-weight:600;">{{ $error }}</div>
        @endforeach
    </div>
    @endif
    <div class="lc-hdr">
        <div class="lc-hdr-icon">
            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        </div>
        <div>
            <h1>{{ $isEdit ? 'Edit Permintaan Loading' : 'Permintaan Loading Baru' }}</h1>
            <p>{{ $isEdit ? 'Ubah daftar barang atau tambahkan barang yang terlupakan' : 'Ajukan permintaan barang untuk dibawa berjualan' }}</p>
        </div>
    </div>

    <a href="{{ $isEdit ? route('pasgar.loading.show', $loading->id) : route('pasgar.loading.index') }}" class="lc-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        {{ $isEdit ? 'Kembali ke Detail Loading' : 'Kembali ke Daftar Loading' }}
    </a>

    <form action="{{ $isEdit ? route('pasgar.loading.update', $loading->id) : route('pasgar.loading.store') }}" method="POST" id="loadingForm">
        @csrf
        @if($isEdit) @method('PUT') @endif

        {{-- Card 1: Informasi Sales --}}
        <div class="lc-card">
            <div class="lc-card-hdr">
                <div class="lc-card-dot"></div>
                <div class="lc-card-title">Informasi Sales</div>
            </div>
            <div class="lc-card-body">
                <div class="lc-seller">
                    <div class="lc-seller-av">{{ strtoupper(substr($salesProfile->nama, 0, 1)) }}</div>
                    <div>
                        <div class="lc-seller-name">{{ $salesProfile->nama }}</div>
                        <div class="lc-seller-code">{{ $salesProfile->kode_sales }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Detail Permintaan --}}
        <div class="lc-card">
            <div class="lc-card-hdr">
                <div class="lc-card-dot"></div>
                <div class="lc-card-title">Detail Permintaan</div>
            </div>
            <div class="lc-card-body">
                <div class="lc-row2">
                    <div class="lc-fg">
                        <label class="lc-lbl">Tanggal <span class="lc-req">*</span></label>
                        <input type="date" name="tanggal" class="lc-inp" value="{{ $isEdit ? $loading->tanggal->format('Y-m-d') : date('Y-m-d') }}" required>
                    </div>
                    <div class="lc-fg">
                        <label class="lc-lbl">Catatan</label>
                        <input type="text" name="catatan" class="lc-inp" placeholder="Catatan tambahan (opsional)..." value="{{ $isEdit ? ($loading->catatan ?? '') : '' }}">
                    </div>
                </div>
                <div class="lc-hint" style="margin-bottom:0">Sumber barang (Gudang/Grosir) ditentukan otomatis per produk berdasarkan ketersediaan stok.</div>
            </div>
        </div>

        {{-- Card 3: Daftar Barang --}}
        <div class="lc-card" id="itemsCard">
            <div class="lc-card-hdr">
                <div class="lc-card-dot"></div>
                <div class="lc-card-title">Daftar Barang</div>
                <span id="itemCountBadge" style="margin-left:auto;font-size:0.7rem;font-weight:700;background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:6px;display:none;">0 barang</span>
            </div>
            <div class="lc-card-body" style="padding-bottom:0.5rem;">
                {{-- Product Search --}}
                <div class="lc-search-wrap" id="searchWrap">
                    <div class="lc-search-box">
                        <div class="lc-search-ico">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                        <input type="text" class="lc-search-input" id="productSearch" placeholder="Ketik nama produk, SKU, atau barcode..." autocomplete="off">
                    </div>
                    <div class="lc-search-results" id="searchResults"></div>
                </div>
                <div class="lc-hint" style="margin-bottom:0.75rem;">Cari dan klik + untuk menambahkan produk ke daftar permintaan</div>
            </div>

            {{-- Items Table --}}
            <div id="itemsTableWrap" style="display:none;">
                <table class="lc-items-table">
                    <thead>
                        <tr>
                            <th style="width:30%">Produk</th>
                            <th class="num">Sumber</th>
                            <th class="num">Qty</th>
                            <th>Satuan</th>
                            <th class="right">Subtotal</th>
                            <th style="width:40px"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
                <div class="lc-summary">
                    <div>
                        <div class="lc-sum-label"><span id="totalItems">0</span> jenis barang</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="lc-sum-label">Estimasi Total</div>
                        <div class="lc-sum-value big" id="grandTotal">Rp 0</div>
                    </div>
                </div>
            </div>

            <div id="emptyState" class="lc-items-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                <p>Belum ada barang. Gunakan pencarian di atas untuk menambah produk.</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="lc-actions">
            <a href="{{ $isEdit ? route('pasgar.loading.show', $loading->id) : route('pasgar.loading.index') }}" class="lc-btn lc-btn-ghost">Batal</a>
            <button type="submit" class="lc-btn lc-btn-primary" id="submitBtn" disabled>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Ajukan Permintaan' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Product data from server
const ALL_PRODUCTS = {!! $productsJson !!};

// Existing items for edit mode
const EXISTING_ITEMS = {!! $existingItemsJson !!};

// State
let selectedItems = []; // [{id, name, sku, price, stock_gudang, stock_grosir, sumber, category, unit, conversions, selectedConvId, qty}]

// DOM
const searchInput = document.getElementById('productSearch');
const searchResults = document.getElementById('searchResults');
const itemsBody = document.getElementById('itemsBody');
const itemsTableWrap = document.getElementById('itemsTableWrap');
const emptyState = document.getElementById('emptyState');
const itemCountBadge = document.getElementById('itemCountBadge');
const submitBtn = document.getElementById('submitBtn');

// Format currency
function formatRp(n) {
    return 'Rp ' + n.toLocaleString('id-ID');
}

// Search handler
searchInput.addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    if (q.length < 1) {
        searchResults.classList.remove('active');
        return;
    }

    const selectedIds = new Set(selectedItems.map(i => i.id));
    const results = ALL_PRODUCTS.filter(p => {
        return (p.name.toLowerCase().includes(q) ||
                p.sku.toLowerCase().includes(q) ||
                p.barcode.toLowerCase().includes(q)) &&
               !selectedIds.has(p.id);
    }).slice(0, 15);

    if (results.length === 0) {
        searchResults.innerHTML = '<div class="lc-sr-empty">Tidak ada produk yang cocok</div>';
    } else {
        searchResults.innerHTML = results.map(p => {
            const gClass = p.stock_gudang <= 5 ? 'low' : '';
            const rClass = p.stock_grosir <= 5 ? 'low' : '';
            const uLabel = p.unit ? ' '+escHtml(p.unit) : '';
            return `
                <div class="lc-sr-item" onclick="addProduct(${p.id})">
                    <div class="lc-sr-info">
                        <div class="lc-sr-name">${escHtml(p.name)}</div>
                        <div class="lc-sr-meta">
                            ${p.category ? `<span class="lc-sr-tag cat">${escHtml(p.category)}</span>` : ''}
                            ${p.sku ? `<span class="lc-sr-tag sku">${escHtml(p.sku)}</span>` : ''}
                            <span class="lc-sr-tag stock ${gClass}">Gudang: ${p.stock_gudang}${uLabel}</span>
                            <span class="lc-sr-tag stock ${rClass}">Grosir: ${p.stock_grosir}${uLabel}</span>
                        </div>
                    </div>
                    <div class="lc-sr-price">${formatRp(p.price)}</div>
                    <button type="button" class="lc-sr-add" title="Tambah">+</button>
                </div>
            `;
        }).join('');
    }
    searchResults.classList.add('active');
});

// Close dropdown on click outside
document.addEventListener('click', function(e) {
    if (!document.getElementById('searchWrap').contains(e.target)) {
        searchResults.classList.remove('active');
    }
});

// Focus opens results
searchInput.addEventListener('focus', function() {
    if (this.value.trim().length >= 1) {
        searchResults.classList.add('active');
    }
});

// Add product
function addProduct(productId) {
    const product = ALL_PRODUCTS.find(p => p.id === productId);
    if (!product || selectedItems.some(i => i.id === productId)) return;

    // Auto-determine sumber: gudang if stock available, else grosir
    const sumber = product.stock_gudang >= 1 ? 'gudang' : 'grosir';
    // Default to base unit conversion
    const baseConv = (product.conversions || []).find(c => c.is_base) || (product.conversions || [])[0] || null;
    const initialPrice = baseConv ? (baseConv.price || product.price) : product.price;
    selectedItems.push({
        id: product.id,
        name: product.name,
        sku: product.sku,
        price: initialPrice,
        stock_gudang: product.stock_gudang,
        stock_grosir: product.stock_grosir,
        sumber: sumber,
        category: product.category,
        unit: product.unit,
        conversions: product.conversions || [],
        selectedConvId: baseConv ? baseConv.id : null,
        qty: 1
    });

    searchInput.value = '';
    searchResults.classList.remove('active');
    renderItems();
    searchInput.focus();
}

// Remove product
function removeProduct(productId) {
    selectedItems = selectedItems.filter(i => i.id !== productId);
    renderItems();
}

// Change sumber
function setSumber(productId, sumber) {
    const item = selectedItems.find(i => i.id === productId);
    if (!item) return;
    item.sumber = sumber;
    renderItems();
}

// Change unit - update price based on selected conversion
function setUnit(productId, convId) {
    const item = selectedItems.find(i => i.id === productId);
    if (!item) return;
    const parsedId = parseInt(convId) || null;
    item.selectedConvId = parsedId;
    // Find the selected conversion and update price
    const conv = (item.conversions || []).find(c => c.id === parsedId);
    if (conv && conv.price) {
        item.price = conv.price;
    }
    renderItems();
}

// Change qty
function changeQty(productId, delta) {
    const item = selectedItems.find(i => i.id === productId);
    if (!item) return;
    const newQty = item.qty + delta;
    if (newQty < 1) return;
    if (newQty > 9999) return;
    item.qty = newQty;
    renderItems();
}

// Direct qty input
function setQty(productId, val) {
    const item = selectedItems.find(i => i.id === productId);
    if (!item) return;
    const n = parseInt(val) || 1;
    item.qty = Math.max(1, Math.min(9999, n));
    renderItems();
}

// Render items table
function renderItems() {
    // Build hidden inputs + table rows
    const count = selectedItems.length;
    let totalValue = 0;

    if (count === 0) {
        itemsTableWrap.style.display = 'none';
        emptyState.style.display = '';
        itemCountBadge.style.display = 'none';
        submitBtn.disabled = true;
        // Clear hidden inputs
        document.getElementById('hiddenInputs').innerHTML = '';
        return;
    }

    itemsTableWrap.style.display = '';
    emptyState.style.display = 'none';
    itemCountBadge.style.display = '';
    itemCountBadge.textContent = count + ' barang';
    submitBtn.disabled = false;

    let html = '';
    let hiddenHtml = '';

    selectedItems.forEach((item, idx) => {
        const subtotal = item.price * item.qty;
        totalValue += subtotal;
        const gudangActive = item.sumber === 'gudang' ? 'background:#dbeafe;border-color:#3b82f6;color:#1d4ed8;' : 'background:#fff;border-color:#e2e8f0;color:#94a3b8;';
        const grosirActive = item.sumber === 'grosir' ? 'background:#fef3c7;border-color:#f59e0b;color:#92400e;' : 'background:#fff;border-color:#e2e8f0;color:#94a3b8;';

        // Build unit options
        let unitOptions = '';
        if (item.conversions && item.conversions.length > 0) {
            item.conversions.forEach(c => {
                const sel = c.id === item.selectedConvId ? ' selected' : '';
                unitOptions += `<option value="${c.id}"${sel}>${escHtml(c.unit_name)}</option>`;
            });
        } else {
            unitOptions = `<option>${item.unit || '-'}</option>`;
        }

        html += `
            <tr>
                <td>
                    <div class="lc-it-name">${escHtml(item.name)}</div>
                    <div class="lc-it-sub">${item.sku ? escHtml(item.sku) + ' · ' : ''}${item.category ? escHtml(item.category) : ''}</div>
                </td>
                <td class="num">
                    <div style="display:inline-flex;border-radius:8px;overflow:hidden;border:1.5px solid #e2e8f0;">
                        <button type="button" onclick="setSumber(${item.id},'gudang')" style="padding:4px 10px;font-size:0.68rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;${gudangActive}">Gudang</button>
                        <button type="button" onclick="setSumber(${item.id},'grosir')" style="padding:4px 10px;font-size:0.68rem;font-weight:700;border:none;border-left:1.5px solid #e2e8f0;cursor:pointer;font-family:inherit;${grosirActive}">Grosir</button>
                    </div>
                </td>
                <td class="num">
                    <div class="lc-qty-wrap" style="justify-content:center;">
                        <button type="button" class="lc-qty-btn" onclick="changeQty(${item.id},-1)">−</button>
                        <input type="text" class="lc-qty-inp" value="${item.qty}" onchange="setQty(${item.id},this.value)">
                        <button type="button" class="lc-qty-btn" onclick="changeQty(${item.id},1)">+</button>
                    </div>
                </td>
                <td><select class="lc-unit-sel" onchange="setUnit(${item.id},this.value)">${unitOptions}</select></td>
                <td class="right"><div style="font-size:0.68rem;color:#94a3b8;">@${formatRp(item.price)}</div><span class="lc-it-subtotal">${formatRp(subtotal)}</span></td>
                <td><button type="button" class="lc-remove" onclick="removeProduct(${item.id})" title="Hapus">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button></td>
            </tr>
        `;

        hiddenHtml += `<input type="hidden" name="items[${idx}][product_id]" value="${item.id}">`;
        hiddenHtml += `<input type="hidden" name="items[${idx}][qty_diminta]" value="${item.qty}">`;
        hiddenHtml += `<input type="hidden" name="items[${idx}][sumber]" value="${item.sumber}">`;
        hiddenHtml += `<input type="hidden" name="items[${idx}][unit_conversion_id]" value="${item.selectedConvId || ''}">`;
    });

    itemsBody.innerHTML = html;
    document.getElementById('totalItems').textContent = count;
    document.getElementById('grandTotal').textContent = formatRp(totalValue);

    // Render hidden inputs
    if (!document.getElementById('hiddenInputs')) {
        const div = document.createElement('div');
        div.id = 'hiddenInputs';
        document.getElementById('loadingForm').appendChild(div);
    }
    document.getElementById('hiddenInputs').innerHTML = hiddenHtml;
}

// Escape HTML
function escHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Load existing items in edit mode
if (EXISTING_ITEMS.length > 0) {
    EXISTING_ITEMS.forEach(ei => {
        const product = ALL_PRODUCTS.find(p => p.id === ei.product_id);
        if (!product) return;

        // Determine the conversion to use
        const convId = ei.unit_conversion_id;
        const conv = (product.conversions || []).find(c => c.id === convId);
        const baseConv = (product.conversions || []).find(c => c.is_base) || (product.conversions || [])[0] || null;
        const selectedConv = conv || baseConv;
        const price = selectedConv ? (selectedConv.price || product.price) : product.price;

        selectedItems.push({
            id: product.id,
            name: product.name,
            sku: product.sku,
            price: price,
            stock_gudang: product.stock_gudang,
            stock_grosir: product.stock_grosir,
            sumber: ei.sumber || 'gudang',
            category: product.category,
            unit: product.unit,
            conversions: product.conversions || [],
            selectedConvId: selectedConv ? selectedConv.id : null,
            qty: ei.qty || 1
        });
    });
    renderItems();
}

// Form validation
document.getElementById('loadingForm').addEventListener('submit', function(e) {
    if (selectedItems.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal 1 barang.');
        return false;
    }
});
</script>
@endpush
@endsection
