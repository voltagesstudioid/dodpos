<x-app-layout>
    <x-slot name="header">Kasir / Tambah Item - {{ $transaction->invoice_number }}</x-slot>

    @push('styles')
    <style>
        .ti { max-width:56rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans','Segoe UI',sans-serif; }

        /* Back link */
        .ti-back { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#64748b; text-decoration:none; padding:6px 10px; border-radius:8px; margin-bottom:16px; transition:.15s; }
        .ti-back:hover { background:#f1f5f9; color:#334155; }

        /* Header */
        .ti-head { display:flex; align-items:center; gap:14px; margin-bottom:20px; }
        .ti-head-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; background:linear-gradient(135deg,#10b981,#059669); box-shadow:0 6px 20px rgba(16,185,129,.3); flex-shrink:0; }
        .ti-head-title { font-size:20px; font-weight:800; color:#0f172a; }
        .ti-head-sub { font-size:12px; color:#64748b; font-family:'JetBrains Mono',monospace; margin-top:2px; }

        /* Info cards */
        .ti-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:20px; }
        .ti-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:14px 16px; position:relative; overflow:hidden; }
        .ti-card::before { content:''; position:absolute; top:0; left:0; bottom:0; width:3px; }
        .ti-card.blue::before { background:#3b82f6; }
        .ti-card.green::before { background:#10b981; }
        .ti-card.amber::before { background:#f59e0b; }
        .ti-card-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; }
        .ti-card-val { font-size:16px; font-weight:800; margin-top:4px; }
        .ti-card.blue .ti-card-val { color:#2563eb; }
        .ti-card.green .ti-card-val { color:#059669; }
        .ti-card.amber .ti-card-val { color:#d97706; }
        .ti-card-meta { font-size:11px; color:#94a3b8; margin-top:3px; }

        /* Search box */
        .ti-search-box { background:#fff; border:1px solid #e2e8f0; border-radius:14px; margin-bottom:14px; box-shadow:0 1px 3px rgba(0,0,0,.04); }
        .ti-search-head { padding:12px 16px; border-bottom:1px solid #f1f5f9; }
        .ti-search-title { font-size:13px; font-weight:700; color:#0f172a; }
        .ti-search-desc { font-size:11px; color:#94a3b8; margin-top:1px; }
        .ti-search-body { padding:14px 16px; position:relative; }
        .ti-search-wrap { position:relative; }
        .ti-search-ico { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
        .ti-search { width:100%; padding:10px 12px 10px 38px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#1e293b; outline:none; transition:.15s; font-family:inherit; }
        .ti-search:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.1); }
        .ti-search-clear { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; cursor:pointer; font-size:16px; padding:4px; display:none; }
        .ti-search-clear.show { display:block; }

        /* Search results dropdown */
        .ti-results { position:absolute; top:calc(100% + 6px); left:0; right:0; background:#fff; border:1px solid #e2e8f0; border-radius:12px; max-height:280px; overflow-y:auto; z-index:200; display:none; box-shadow:0 12px 32px rgba(0,0,0,.1); }
        .ti-results.open { display:block; }
        .ti-result { padding:10px 14px; cursor:pointer; border-bottom:1px solid #f8fafc; display:flex; justify-content:space-between; align-items:center; transition:.1s; }
        .ti-result:hover { background:#f0fdf4; }
        .ti-result:last-child { border-bottom:none; }
        .ti-result-name { font-size:13px; font-weight:600; color:#0f172a; }
        .ti-result-meta { font-size:11px; color:#94a3b8; margin-top:2px; }
        .ti-result-price { font-size:12px; font-weight:700; color:#059669; text-align:right; white-space:nowrap; }
        .ti-result-empty { padding:24px; text-align:center; color:#94a3b8; font-size:12px; }

        /* Cart section */
        .ti-cart-box { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; margin-bottom:14px; box-shadow:0 1px 3px rgba(0,0,0,.04); }
        .ti-cart-head { padding:12px 16px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; }
        .ti-cart-title { font-size:13px; font-weight:700; color:#0f172a; }
        .ti-cart-count { font-size:11px; font-weight:600; color:#10b981; background:#ecfdf5; padding:2px 8px; border-radius:20px; }
        .ti-cart-empty { text-align:center; padding:32px 16px; color:#94a3b8; font-size:12px; }
        .ti-cart-empty-icon { font-size:32px; margin-bottom:8px; opacity:.5; }

        /* Cart item row */
        .ti-item { display:grid; grid-template-columns:1fr auto; gap:8px; padding:12px 16px; border-bottom:1px solid #f8fafc; transition:.15s; }
        .ti-item:hover { background:#fafafa; }
        .ti-item:last-child { border-bottom:none; }
        .ti-item-info { min-width:0; }
        .ti-item-name { font-size:13px; font-weight:600; color:#0f172a; display:flex; align-items:center; gap:6px; }
        .ti-item-badge { font-size:9px; font-weight:700; color:#10b981; background:#ecfdf5; padding:1px 6px; border-radius:4px; }
        .ti-item-sub { font-size:11px; color:#94a3b8; margin-top:3px; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .ti-item-sub select { font-size:11px; padding:2px 6px; border:1px solid #e2e8f0; border-radius:6px; background:#fff; color:#475569; cursor:pointer; }
        .ti-item-ctrl { display:flex; align-items:center; gap:8px; flex-shrink:0; }
        .ti-qty { width:52px; text-align:center; padding:6px 4px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#1e293b; outline:none; }
        .ti-qty:focus { border-color:#10b981; }
        .ti-price { width:100px; text-align:right; padding:6px 8px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:12px; font-weight:600; color:#1e293b; outline:none; }
        .ti-price:focus { border-color:#10b981; }
        .ti-subtotal { font-size:13px; font-weight:700; color:#0f172a; min-width:90px; text-align:right; }
        .ti-remove { width:28px; height:28px; border:none; background:#fee2e2; color:#ef4444; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:.15s; flex-shrink:0; }
        .ti-remove:hover { background:#fecaca; }

        /* Cart footer totals */
        .ti-cart-foot { padding:12px 16px; border-top:1.5px solid #e2e8f0; background:#fafbfc; }
        .ti-total-row { display:flex; justify-content:space-between; align-items:center; padding:4px 0; font-size:12px; color:#64748b; }
        .ti-total-row.grand { font-size:16px; font-weight:800; color:#0f172a; padding-top:8px; margin-top:4px; border-top:1px dashed #e2e8f0; }
        .ti-total-row.grand .ti-total-val { color:#059669; }

        /* Payment section */
        .ti-pay-box { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; margin-bottom:14px; box-shadow:0 1px 3px rgba(0,0,0,.04); }
        .ti-pay-head { padding:12px 16px; border-bottom:1px solid #f1f5f9; }
        .ti-pay-title { font-size:13px; font-weight:700; color:#0f172a; }
        .ti-pay-desc { font-size:11px; color:#94a3b8; margin-top:1px; }
        .ti-pay-body { padding:14px 16px; display:flex; flex-direction:column; gap:14px; }
        .ti-pay-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#475569; margin-bottom:6px; }
        .ti-pay-wrap { position:relative; }
        .ti-pay-prefix { position:absolute; left:12px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:600; color:#94a3b8; pointer-events:none; }
        .ti-pay-input { width:100%; padding:9px 12px 9px 32px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#1e293b; outline:none; font-family:inherit; }
        .ti-pay-input:focus { border-color:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.1); }
        .ti-pay-auto { font-size:11px; color:#10b981; font-weight:600; cursor:pointer; margin-top:4px; display:inline-flex; align-items:center; gap:4px; }
        .ti-pay-auto:hover { text-decoration:underline; }
        .ti-methods { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
        .ti-method { display:flex; flex-direction:column; align-items:center; gap:4px; padding:10px 8px; border-radius:10px; border:1.5px solid #e2e8f0; cursor:pointer; transition:.15s; background:#fff; text-align:center; }
        .ti-method:hover { border-color:#cbd5e1; background:#f8fafc; }
        .ti-method.sel { border-color:#10b981; background:#f0fdf4; }
        .ti-method input { display:none; }
        .ti-method-ico { font-size:18px; }
        .ti-method-lbl { font-size:12px; font-weight:700; color:#374151; }
        .ti-method-desc { font-size:10px; color:#94a3b8; }
        .ti-ref-input { width:100%; padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#1e293b; outline:none; font-family:inherit; }
        .ti-ref-input:focus { border-color:#10b981; }
        .ti-notes { width:100%; padding:9px 12px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:13px; color:#1e293b; outline:none; font-family:inherit; resize:none; min-height:50px; }
        .ti-notes:focus { border-color:#10b981; }

        /* Actions */
        .ti-actions { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:8px 0; }
        .ti-btn-cancel { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:.15s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; }
        .ti-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; }
        .ti-btn-save { display:inline-flex; align-items:center; gap:6px; padding:10px 24px; border-radius:10px; font-size:13px; font-weight:700; border:none; cursor:pointer; transition:.2s; font-family:inherit; background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 16px rgba(16,185,129,.35); }
        .ti-btn-save:hover:not(:disabled) { transform:translateY(-1px); box-shadow:0 8px 24px rgba(16,185,129,.4); }
        .ti-btn-save:disabled { opacity:.5; cursor:not-allowed; transform:none; }

        /* Unit modal */
        .ti-modal-bg { position:fixed; inset:0; background:rgba(15,23,42,.5); z-index:1000; display:none; align-items:center; justify-content:center; padding:16px; backdrop-filter:blur(2px); }
        .ti-modal-bg.open { display:flex; }
        .ti-modal { background:#fff; border-radius:14px; padding:20px; width:100%; max-width:380px; border:1px solid #e2e8f0; box-shadow:0 20px 60px rgba(0,0,0,.15); }
        .ti-modal-hdr { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
        .ti-modal-title { font-size:14px; font-weight:800; color:#0f172a; }
        .ti-modal-close { background:none; border:none; font-size:20px; color:#94a3b8; cursor:pointer; padding:0; }
        .ti-modal-close:hover { color:#ef4444; }
        .ti-unit-opt { display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; cursor:pointer; transition:.1s; margin-bottom:6px; }
        .ti-unit-opt:hover { border-color:#10b981; background:#f0fdf4; }
        .ti-unit-opt:last-child { margin-bottom:0; }
        .ti-unit-name { font-size:13px; font-weight:700; color:#0f172a; }
        .ti-unit-sub { font-size:10px; color:#94a3b8; margin-top:2px; }
        .ti-unit-price { font-size:13px; font-weight:800; color:#059669; }

        @media(max-width:640px) {
            .ti-cards { grid-template-columns:1fr; }
            .ti-methods { grid-template-columns:1fr; }
            .ti-item { grid-template-columns:1fr; }
            .ti-item-ctrl { flex-wrap:wrap; }
            .ti-actions { flex-direction:column-reverse; }
            .ti-btn-cancel, .ti-btn-save { width:100%; justify-content:center; }
        }

        @keyframes ti-spin { from{transform:rotate(0)} to{transform:rotate(360deg)} }
    </style>
    @endpush

    <div class="ti">
        {{-- Back --}}
        <a href="{{ route('transaksi.show', $transaction) }}" class="ti-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Transaksi
        </a>

        {{-- Header --}}
        <div class="ti-head">
            <div class="ti-head-icon">+</div>
            <div>
                <div class="ti-head-title">Tambah Item</div>
                <div class="ti-head-sub">{{ $transaction->invoice_number }}</div>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="ti-cards">
            <div class="ti-card blue">
                <div class="ti-card-lbl">Customer</div>
                <div class="ti-card-val">{{ $transaction->customer?->name ?? 'Umum' }}</div>
                <div class="ti-card-meta">{{ $transaction->customer ? 'Terdaftar' : 'Customer umum' }}</div>
            </div>
            <div class="ti-card green">
                <div class="ti-card-lbl">Total Awal</div>
                <div class="ti-card-val">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                <div class="ti-card-meta">{{ $transaction->details->count() }} item</div>
            </div>
            <div class="ti-card amber">
                <div class="ti-card-lbl">Metode Bayar</div>
                <div class="ti-card-val">{{ strtoupper($transaction->payment_method) }}</div>
                @if($transaction->hasAdditionalItems())
                    <div class="ti-card-meta">+{{ $transaction->additionalTransactions->count() }} tambahan</div>
                @else
                    <div class="ti-card-meta">Belum ada tambahan</div>
                @endif
            </div>
        </div>

        {{-- Search --}}
        <div class="ti-search-box">
            <div class="ti-search-head">
                <div class="ti-search-title">Cari & Tambah Produk</div>
                <div class="ti-search-desc">Ketik nama atau SKU produk untuk mencari</div>
            </div>
            <div class="ti-search-body">
                <div class="ti-search-wrap">
                    <svg class="ti-search-ico" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="tiSearch" class="ti-search" placeholder="Ketik nama atau SKU produk..." autocomplete="off">
                    <button type="button" id="tiClear" class="ti-search-clear" onclick="clearSearch()">&times;</button>
                    <div id="tiResults" class="ti-results"></div>
                </div>
            </div>
        </div>

        {{-- Cart --}}
        <div class="ti-cart-box">
            <div class="ti-cart-head">
                <div class="ti-cart-title">Item yang Ditambahkan</div>
                <span class="ti-cart-count" id="tiCount">0 item</span>
            </div>
            <div id="tiCart">
                <div class="ti-cart-empty">
                    <div class="ti-cart-empty-icon">📋</div>
                    <div>Belum ada item. Cari produk di atas untuk menambahkan.</div>
                </div>
            </div>
            <div class="ti-cart-foot" id="tiFoot" style="display:none;">
                <div class="ti-total-row">
                    <span>Total Item</span>
                    <span id="tiItemCount">0</span>
                </div>
                <div class="ti-total-row grand">
                    <span>Total Tambahan</span>
                    <span class="ti-total-val" id="tiTotal">Rp 0</span>
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="ti-pay-box">
            <div class="ti-pay-head">
                <div class="ti-pay-title">Pembayaran Tambahan</div>
                <div class="ti-pay-desc">Detail pembayaran untuk item tambahan</div>
            </div>
            <div class="ti-pay-body">
                <div>
                    <div class="ti-pay-label">Jumlah Pembayaran <span style="color:#ef4444">*</span></div>
                    <div class="ti-pay-wrap">
                        <span class="ti-pay-prefix">Rp</span>
                        <input type="text" id="tiPayAmount" inputmode="numeric" placeholder="0" class="ti-pay-input">
                    </div>
                    <span class="ti-pay-auto" onclick="autoFillPay()">
                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Isi otomatis dari total
                    </span>
                </div>

                <div>
                    <div class="ti-pay-label">Metode Pembayaran</div>
                    <div class="ti-methods" id="tiMethods">
                        <label class="ti-method {{ $transaction->payment_method === 'cash' ? 'sel' : '' }}" onclick="selMethod(this,'cash')">
                            <input type="radio" name="payMethod" value="cash" {{ $transaction->payment_method === 'cash' ? 'checked' : '' }}>
                            <div class="ti-method-ico">💵</div>
                            <div class="ti-method-lbl">Tunai</div>
                            <div class="ti-method-desc">Cash</div>
                        </label>
                        <label class="ti-method {{ $transaction->payment_method === 'transfer' ? 'sel' : '' }}" onclick="selMethod(this,'transfer')">
                            <input type="radio" name="payMethod" value="transfer" {{ $transaction->payment_method === 'transfer' ? 'checked' : '' }}>
                            <div class="ti-method-ico">🏦</div>
                            <div class="ti-method-lbl">Transfer</div>
                            <div class="ti-method-desc">Bank</div>
                        </label>
                        <label class="ti-method {{ $transaction->payment_method === 'qris' ? 'sel' : '' }}" onclick="selMethod(this,'qris')">
                            <input type="radio" name="payMethod" value="qris" {{ $transaction->payment_method === 'qris' ? 'checked' : '' }}>
                            <div class="ti-method-ico">📱</div>
                            <div class="ti-method-lbl">QRIS</div>
                            <div class="ti-method-desc">Scan QR</div>
                        </label>
                    </div>
                </div>

                <div id="tiRefField" style="display:{{ in_array($transaction->payment_method, ['transfer','qris']) ? 'block' : 'none' }};">
                    <div class="ti-pay-label">No. Referensi</div>
                    <input type="text" id="tiPayRef" class="ti-ref-input" placeholder="Contoh: TRX-123456">
                </div>

                <div>
                    <div class="ti-pay-label">Catatan</div>
                    <textarea id="tiNotes" class="ti-notes" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="ti-actions">
            <a href="{{ route('transaksi.show', $transaction) }}" class="ti-btn-cancel">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Batal
            </a>
            <button type="button" id="btnSave" class="ti-btn-save" disabled>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Tambahan Item
            </button>
        </div>
    </div>

    {{-- Unit Selection Modal --}}
    <div id="unitModal" class="ti-modal-bg">
        <div class="ti-modal">
            <div class="ti-modal-hdr">
                <div class="ti-modal-title" id="unitModalTitle">Pilih Satuan</div>
                <button onclick="closeUnitModal()" class="ti-modal-close">&times;</button>
            </div>
            <div id="unitModalBody"></div>
        </div>
    </div>

    @push('scripts')
    <script>
    /* ── DATA ── */
    let PRODUCTS = [];
    try {
        PRODUCTS = @json($products);
        console.log('[TI] Products loaded:', PRODUCTS.length);
    } catch(e) {
        console.error('[TI] Failed to load products:', e);
    }
    const WAREHOUSES = @json(\App\Models\Warehouse::where('active', true)->get(['id','name']));
    const TRX_ID = {{ $transaction->id }};
    const CSRF = '{{ csrf_token() }}';
    const IS_GROSIR = '{{ $transaction->sale_type }}' === 'grosir';

    let cart = [];
    let method = '{{ $transaction->payment_method }}';

    /* ── SEARCH ── */
    const searchEl = document.getElementById('tiSearch');
    const resultsEl = document.getElementById('tiResults');
    const clearEl = document.getElementById('tiClear');

    searchEl.addEventListener('input', function() {
        const q = this.value.trim();
        clearEl.classList.toggle('show', q.length > 0);
        if (q.length < 1) { closeResults(); return; }

        const ql = q.toLowerCase();
        const found = PRODUCTS.filter(p =>
            (p.name && p.name.toLowerCase().includes(ql)) ||
            (p.sku && p.sku.toLowerCase().includes(ql)) ||
            (p.category && p.category.toLowerCase().includes(ql))
        ).slice(0, 12);

        console.log('[TI] Search:', q, '→ found', found.length, 'of', PRODUCTS.length);

        if (found.length === 0) {
            resultsEl.innerHTML = '<div class="ti-result-empty">Tidak ada produk ditemukan untuk "<b>' + escapeHtml(q) + '</b>"</div>';
        } else {
            resultsEl.innerHTML = found.map(p => {
                const price = getPrice(p);
                const stock = p.stock || 0;
                const stockLabel = stock > 0
                    ? '<span style="color:#10b981">Stok: ' + stock + '</span>'
                    : '<span style="color:#ef4444">Stok habis</span>';
                const unitInfo = (p.units && p.units.length)
                    ? p.units.map(u => u.name).join(', ')
                    : (p.unit || 'pcs');
                return '<div class="ti-result" onclick="addProduct(' + p.id + ')">' +
                    '<div>' +
                        '<div class="ti-result-name">' + escapeHtml(p.name) + '</div>' +
                        '<div class="ti-result-meta">' + escapeHtml(p.sku||'-') + ' · ' + stockLabel + ' · ' + unitInfo + '</div>' +
                    '</div>' +
                    '<div class="ti-result-price">Rp ' + price.toLocaleString('id-ID') + '</div>' +
                '</div>';
            }).join('');
        }
        resultsEl.classList.add('open');
    });

    searchEl.addEventListener('focus', function() {
        if (this.value.trim().length >= 1) resultsEl.classList.add('open');
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.ti-search-wrap')) closeResults();
    });

    function closeResults() { resultsEl.classList.remove('open'); }
    function clearSearch() { searchEl.value = ''; clearEl.classList.remove('show'); closeResults(); searchEl.focus(); }

    /* ── PRICING ── */
    function getPrice(product) {
        if (IS_GROSIR && product.prices?.grosir) return product.prices.grosir;
        if (product.prices?.eceran) return product.prices.eceran;
        return 0;
    }

    function getUnitPrice(unit) {
        if (IS_GROSIR && unit.prices?.grosir) return unit.prices.grosir;
        if (unit.prices?.eceran) return unit.prices.eceran;
        return 0;
    }

    /* ── ADD PRODUCT ── */
    function addProduct(productId) {
        const p = PRODUCTS.find(x => x.id === productId);
        if (!p) return;

        if (p.units && p.units.length > 1) {
            showUnitModal(p);
        } else {
            addToCart(p, null);
        }
        closeResults();
        searchEl.value = '';
        clearEl.classList.remove('show');
    }

    function addToCart(product, unitIdx) {
        const wh = WAREHOUSES[0]?.id || 1;
        let price = getPrice(product);
        let unitName = product.unit || 'pcs';
        let unitId = null;

        if (unitIdx !== null && product.units && product.units[unitIdx]) {
            const u = product.units[unitIdx];
            price = getUnitPrice(u);
            unitName = u.name;
            unitId = u.id;
        }

        const existing = cart.find(i => i.product_id === product.id && i.unit_id === unitId);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({
                product_id: product.id,
                unit_id: unitId,
                unit_name: unitName,
                name: product.name,
                sku: product.sku || '-',
                price: price,
                qty: 1,
                warehouse_id: wh,
                stock: product.stock || 0,
                units: product.units || [],
            });
        }
        renderCart();
    }

    /* ── UNIT MODAL ── */
    function showUnitModal(product) {
        document.getElementById('unitModalTitle').textContent = 'Pilih Satuan — ' + product.name;
        const body = product.units.map((u, idx) => {
            const price = getUnitPrice(u);
            const factor = u.factor ? ' (x' + u.factor + ')' : '';
            return '<div class="ti-unit-opt" onclick="pickUnit(' + product.id + ',' + idx + ')">' +
                '<div>' +
                    '<div class="ti-unit-name">' + escapeHtml(u.name) + factor + '</div>' +
                    '<div class="ti-unit-sub">Stok tersedia</div>' +
                '</div>' +
                '<div class="ti-unit-price">Rp ' + price.toLocaleString('id-ID') + '</div>' +
            '</div>';
        }).join('');
        document.getElementById('unitModalBody').innerHTML = body;
        document.getElementById('unitModal').classList.add('open');
    }

    function pickUnit(productId, idx) {
        const p = PRODUCTS.find(x => x.id === productId);
        if (p) addToCart(p, idx);
        closeUnitModal();
    }

    function closeUnitModal() { document.getElementById('unitModal').classList.remove('open'); }
    document.getElementById('unitModal').addEventListener('click', function(e) { if (e.target === this) closeUnitModal(); });

    /* ── CART RENDERING ── */
    function renderCart() {
        const container = document.getElementById('tiCart');
        const foot = document.getElementById('tiFoot');
        const saveBtn = document.getElementById('btnSave');

        if (cart.length === 0) {
            container.innerHTML = '<div class="ti-cart-empty"><div class="ti-cart-empty-icon">📋</div><div>Belum ada item. Cari produk di atas untuk menambahkan.</div></div>';
            foot.style.display = 'none';
            saveBtn.disabled = true;
            updateTotals();
            return;
        }

        let html = '';
        cart.forEach((item, i) => {
            const subtotal = item.price * item.qty;
            html += '<div class="ti-item">' +
                '<div class="ti-item-info">' +
                    '<div class="ti-item-name">' +
                        escapeHtml(item.name) +
                        '<span class="ti-item-badge">' + escapeHtml(item.unit_name) + '</span>' +
                    '</div>' +
                    '<div class="ti-item-sub">' +
                        '<span>SKU: ' + escapeHtml(item.sku) + '</span>' +
                        '<span>Stok: ' + item.stock + '</span>' +
                        (item.units && item.units.length > 1
                            ? '<select onchange="changeUnit(' + i + ',this.value)">' +
                                item.units.map(u => '<option value="' + u.id + '"' + (u.id == item.unit_id ? ' selected' : '') + '>' + escapeHtml(u.name) + '</option>').join('') +
                              '</select>'
                            : '') +
                        (WAREHOUSES.length > 1
                            ? '<select onchange="cart[' + i + '].warehouse_id=+this.value;renderCart()">' +
                                WAREHOUSES.map(w => '<option value="' + w.id + '"' + (w.id == item.warehouse_id ? ' selected' : '') + '>' + escapeHtml(w.name) + '</option>').join('') +
                              '</select>'
                            : '') +
                    '</div>' +
                '</div>' +
                '<div class="ti-item-ctrl">' +
                    '<input type="number" class="ti-qty" min="1" max="' + item.stock + '" value="' + item.qty + '" onchange="setQty(' + i + ',this.value)" title="Qty">' +
                    '<input type="text" class="ti-price" value="' + item.price.toLocaleString('id-ID') + '" onchange="setPrice(' + i + ',this.value)" title="Harga">' +
                    '<div class="ti-subtotal">Rp ' + subtotal.toLocaleString('id-ID') + '</div>' +
                    '<button class="ti-remove" onclick="removeItem(' + i + ')" title="Hapus">' +
                        '<svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>' +
                    '</button>' +
                '</div>' +
            '</div>';
        });

        container.innerHTML = html;
        foot.style.display = 'block';
        saveBtn.disabled = false;
        updateTotals();
    }

    function removeItem(i) { cart.splice(i, 1); renderCart(); }

    function setQty(i, val) {
        let n = parseInt(val) || 1;
        if (n < 1) n = 1;
        if (n > cart[i].stock) { alert('Stok tidak mencukupi!'); n = cart[i].stock; }
        cart[i].qty = n;
        renderCart();
    }

    function setPrice(i, val) {
        const n = parseInt(String(val).replace(/[^\d]/g, '')) || 0;
        cart[i].price = n;
        renderCart();
    }

    function changeUnit(i, unitId) {
        const item = cart[i];
        const u = item.units.find(x => x.id == unitId);
        if (u) {
            item.unit_id = u.id;
            item.unit_name = u.name;
            item.price = getUnitPrice(u);
            renderCart();
        }
    }

    function updateTotals() {
        const totalQty = cart.reduce((s, i) => s + i.qty, 0);
        const totalAmt = cart.reduce((s, i) => s + (i.price * i.qty), 0);
        document.getElementById('tiCount').textContent = totalQty + ' item';
        document.getElementById('tiItemCount').textContent = totalQty + ' item';
        document.getElementById('tiTotal').textContent = 'Rp ' + totalAmt.toLocaleString('id-ID');
    }

    /* ── PAYMENT ── */
    function selMethod(el, m) {
        method = m;
        document.querySelectorAll('.ti-method').forEach(x => x.classList.remove('sel'));
        el.classList.add('sel');
        el.querySelector('input').checked = true;
        document.getElementById('tiRefField').style.display = (m === 'transfer' || m === 'qris') ? 'block' : 'none';
        if (m === 'cash') document.getElementById('tiPayRef').value = '';
    }

    const payInput = document.getElementById('tiPayAmount');
    payInput.addEventListener('input', function() {
        const raw = this.value.replace(/[^\d]/g, '');
        this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    });

    function autoFillPay() {
        const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
        if (total > 0) payInput.value = total.toLocaleString('id-ID');
    }

    function getPayValue() {
        return parseInt(payInput.value.replace(/[^\d]/g, '')) || 0;
    }

    /* ── SAVE ── */
    document.getElementById('btnSave').addEventListener('click', async function() {
        if (cart.length === 0) { alert('Keranjang masih kosong.'); return; }

        const items = cart.map(i => ({
            product_id: i.product_id,
            quantity: i.qty,
            price: i.price,
            warehouse_id: i.warehouse_id,
            unit_name: i.unit_name,
            unit_id: i.unit_id,
        }));
        const totalAmount = items.reduce((s, i) => s + (i.price * i.quantity), 0);
        const payment = getPayValue();

        if (payment <= 0) { alert('Pembayaran tambahan wajib diisi.'); payInput.focus(); return; }
        if (payment < totalAmount) {
            alert('Pembayaran kurang!\nTotal: Rp ' + totalAmount.toLocaleString('id-ID') + '\nDibayar: Rp ' + payment.toLocaleString('id-ID'));
            payInput.focus();
            return;
        }

        const ref = document.getElementById('tiPayRef').value;
        if ((method === 'transfer' || method === 'qris') && !ref.trim()) {
            alert('No. Referensi wajib diisi untuk Transfer/QRIS.');
            document.getElementById('tiPayRef').focus();
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:ti-spin 1s linear infinite"><path d="M12 2v4m0 12v4m-7.07-3.93l2.83-2.83m8.48-8.48l2.83-2.83M2 12h4m12 0h4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83"/></svg> Menyimpan...';

        try {
            const res = await fetch('/kasir/transactions/' + TRX_ID + '/add-items', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({
                    items,
                    additional_payment: payment,
                    payment_method: method,
                    payment_reference: ref,
                    notes: document.getElementById('tiNotes').value,
                }),
            });

            const result = await res.json();
            if (result.success) {
                alert('Item tambahan berhasil disimpan!\nPick Order: ' + (result.pick_order || '-') + '\n\nAnda akan diarahkan ke detail transaksi.');
                window.location.href = '{{ route('transaksi.show', $transaction) }}';
            } else {
                alert('Gagal: ' + (result.message || 'Unknown error'));
                resetBtn();
            }
        } catch (err) {
            alert('Error: ' + err.message);
            resetBtn();
        }
    });

    function resetBtn() {
        const btn = document.getElementById('btnSave');
        btn.disabled = false;
        btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Tambahan Item';
    }

    /* ── UTILS ── */
    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
    </script>
    @endpush
</x-app-layout>
