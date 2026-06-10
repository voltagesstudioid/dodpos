<x-app-layout>
    <x-slot name="header">Kasir / Tambah Item - {{ $transaction->invoice_number }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .ai-page { max-width:52rem; margin:0 auto; padding:1.5rem 1rem 4rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Back + Header */
        .ai-back { display:inline-flex; align-items:center; gap:0.5rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; padding:0.5rem 0.75rem; border-radius:10px; transition:all 0.2s; margin-bottom:1.25rem; }
        .ai-back:hover { background:#f1f5f9; color:#334155; }
        .ai-hdr { display:flex; align-items:center; gap:1rem; margin-bottom:1.75rem; }
        .ai-hdr-ico { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; background:linear-gradient(135deg,#6366f1,#4f46e5); box-shadow:0 8px 24px rgba(79,70,229,0.3); }
        .ai-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .ai-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; font-family:'JetBrains Mono',monospace; }

        /* KPI Summary */
        .ai-kpis { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:0.75rem; margin-bottom:1.5rem; }
        .ai-kpi { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1rem 1.125rem; position:relative; overflow:hidden; transition:all 0.3s; }
        .ai-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .ai-kpi.blue::before { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .ai-kpi.green::before { background:linear-gradient(180deg,#10b981,#059669); }
        .ai-kpi.amber::before { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .ai-kpi:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.06); border-color:transparent; }
        .ai-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .ai-kpi-val { font-size:1.25rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; }
        .ai-kpi-val.blue { color:#2563eb; }
        .ai-kpi-val.green { color:#059669; }
        .ai-kpi-val.amber { color:#d97706; }
        .ai-kpi-meta { font-size:0.6875rem; color:#94a3b8; margin-top:0.25rem; }

        /* Sections */
        .ai-section { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .ai-section-hdr { display:flex; align-items:center; gap:0.75rem; padding:1rem 1.25rem; border-bottom:1px solid #e2e8f0; }
        .ai-section-ico { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:0.9375rem; flex-shrink:0; }
        .ai-section-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .ai-section-ico.green { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .ai-section-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .ai-section-title { font-size:0.875rem; font-weight:700; color:#0f172a; }
        .ai-section-desc { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .ai-section-body { padding:1.25rem; }

        /* Search */
        .ai-search-wrap { position:relative; }
        .ai-search-ico { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
        .ai-search { width:100%; padding:0.75rem 1rem 0.75rem 2.75rem; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.875rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; background:#fff; }
        .ai-search:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
        .ai-search::placeholder { color:#cbd5e1; }

        /* Dropdown */
        .ai-dropdown { position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff; border:1px solid #e2e8f0; border-radius:12px; max-height:260px; overflow-y:auto; z-index:100; display:none; box-shadow:0 12px 32px rgba(0,0,0,0.08); }
        .ai-dropdown.active { display:block; }
        .ai-dropdown-item { padding:0.75rem 1rem; cursor:pointer; border-bottom:1px solid #f1f5f9; transition:background 0.15s; }
        .ai-dropdown-item:hover { background:#f8fafc; }
        .ai-dropdown-item:last-child { border-bottom:none; }
        .ai-dropdown-name { font-size:0.8125rem; font-weight:600; color:#0f172a; }
        .ai-dropdown-meta { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }
        .ai-dropdown-price { font-size:0.75rem; color:#4f46e5; font-weight:600; margin-top:3px; }
        .ai-dropdown-empty { padding:1.5rem; text-align:center; color:#94a3b8; font-size:0.8125rem; }

        /* Cart */
        .ai-cart-empty { text-align:center; padding:2.5rem 1.5rem; }
        .ai-cart-empty-ico { width:56px; height:56px; margin:0 auto 0.75rem; border-radius:50%; background:linear-gradient(135deg,#f1f5f9,#e2e8f0); display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .ai-cart-empty-text { font-size:0.8125rem; color:#94a3b8; }

        .ai-cart-item { display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; transition:all 0.2s; }
        .ai-cart-item:hover { border-color:#cbd5e1; box-shadow:0 2px 8px rgba(0,0,0,0.04); }
        .ai-cart-info { flex:1; min-width:0; }
        .ai-cart-name { font-size:0.8125rem; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ai-cart-meta { font-size:0.6875rem; color:#94a3b8; margin-top:2px; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; }
        .ai-cart-meta select { font-size:0.6875rem; padding:0.2rem 0.4rem; border:1px solid #e2e8f0; border-radius:6px; background:#fff; color:#475569; cursor:pointer; outline:none; font-family:inherit; }
        .ai-cart-meta select:focus { border-color:#6366f1; }
        .ai-cart-controls { display:flex; align-items:center; gap:0.5rem; flex-shrink:0; }
        .ai-qty-input { width:52px; text-align:center; padding:0.375rem 0.25rem; border:1.5px solid #e2e8f0; border-radius:8px; font-size:0.8125rem; font-weight:600; color:#1e293b; outline:none; font-family:inherit; }
        .ai-qty-input:focus { border-color:#6366f1; box-shadow:0 0 0 2px rgba(99,102,241,0.1); }
        .ai-price-input { width:90px; text-align:right; padding:0.375rem 0.5rem; border:1.5px solid #e2e8f0; border-radius:8px; font-size:0.75rem; font-weight:600; color:#1e293b; outline:none; font-family:inherit; }
        .ai-price-input:focus { border-color:#6366f1; box-shadow:0 0 0 2px rgba(99,102,241,0.1); }
        .ai-cart-total { font-size:0.8125rem; font-weight:800; color:#4f46e5; min-width:80px; text-align:right; white-space:nowrap; }
        .ai-cart-remove { width:30px; height:30px; border:none; background:#fee2e2; color:#ef4444; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s; flex-shrink:0; }
        .ai-cart-remove:hover { background:#fecaca; transform:scale(1.05); }

        /* Totals */
        .ai-totals { border-top:1.5px solid #e2e8f0; padding-top:1rem; margin-top:1rem; }
        .ai-total-row { display:flex; justify-content:space-between; align-items:center; padding:0.375rem 0; font-size:0.8125rem; color:#64748b; }
        .ai-total-row.grand { font-size:1.125rem; font-weight:800; color:#0f172a; padding-top:0.625rem; border-top:1px dashed #e2e8f0; margin-top:0.375rem; }
        .ai-total-row.grand span:last-child { color:#4f46e5; }

        /* Payment Section */
        .ai-pay-grid { display:grid; gap:1rem; }
        .ai-pay-label { display:flex; align-items:center; gap:0.375rem; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#475569; margin-bottom:0.5rem; }
        .ai-pay-input { width:100%; padding:0.6875rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0; background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; }
        .ai-pay-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
        .ai-pay-input::placeholder { color:#cbd5e1; }
        textarea.ai-pay-input { resize:none; min-height:60px; }

        .ai-pay-wrap { position:relative; }
        .ai-pay-prefix { position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); font-size:0.8125rem; font-weight:600; color:#94a3b8; pointer-events:none; }
        .ai-pay-input.has-prefix { padding-left:2.75rem; }

        .ai-pay-hint { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; }
        .ai-pay-auto { font-size:0.6875rem; color:#4f46e5; font-weight:600; cursor:pointer; margin-top:0.375rem; display:inline-flex; align-items:center; gap:0.25rem; }
        .ai-pay-auto:hover { text-decoration:underline; }

        /* Payment Method Radio Cards */
        .ai-methods { display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; }
        .ai-method { display:flex; flex-direction:column; align-items:center; gap:0.375rem; padding:0.75rem 0.5rem; border-radius:10px; border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; background:#fff; text-align:center; }
        .ai-method:hover { border-color:#cbd5e1; background:#f8fafc; }
        .ai-method.selected { border-color:#4f46e5; background:#eef2ff; }
        .ai-method input { display:none; }
        .ai-method-ico { font-size:1.25rem; }
        .ai-method-lbl { font-size:0.75rem; font-weight:700; color:#374151; }
        .ai-method-desc { font-size:0.625rem; color:#94a3b8; }

        /* Actions */
        .ai-actions { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 0; margin-top:0.5rem; }
        .ai-btn-save { display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem; border-radius:12px; font-size:0.8125rem; font-weight:700; border:none; cursor:pointer; transition:all 0.25s; font-family:inherit; background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; box-shadow:0 6px 20px rgba(79,70,229,0.35); }
        .ai-btn-save:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 10px 32px rgba(79,70,229,0.45); }
        .ai-btn-save:disabled { opacity:0.5; cursor:not-allowed; transform:none; }
        .ai-btn-cancel { display:inline-flex; align-items:center; gap:0.375rem; padding:0.75rem 1.25rem; border-radius:12px; font-size:0.8125rem; font-weight:600; border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit; background:#fff; color:#64748b; text-decoration:none; }
        .ai-btn-cancel:hover { background:#f8fafc; border-color:#cbd5e1; color:#475569; }

        /* Modal */
        .ai-modal-bg { position:fixed; inset:0; background:rgba(15,23,42,0.5); z-index:1000; display:none; align-items:center; justify-content:center; padding:1rem; backdrop-filter:blur(2px); }
        .ai-modal-bg.active { display:flex; }
        .ai-modal { background:#fff; border-radius:16px; padding:1.5rem; width:100%; max-width:400px; border:1px solid #e2e8f0; box-shadow:0 20px 60px rgba(0,0,0,0.15); }
        .ai-modal-hdr { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
        .ai-modal-title { font-size:0.9375rem; font-weight:800; color:#0f172a; }
        .ai-modal-close { background:none; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer; padding:0; line-height:1; transition:color 0.2s; }
        .ai-modal-close:hover { color:#ef4444; }
        .ai-modal-body { display:flex; flex-direction:column; gap:0.5rem; }
        .ai-unit-opt { display:flex; justify-content:space-between; align-items:center; padding:0.75rem 1rem; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:10px; cursor:pointer; transition:all 0.15s; }
        .ai-unit-opt:hover { border-color:#6366f1; background:#eef2ff; }
        .ai-unit-name { font-size:0.8125rem; font-weight:700; color:#0f172a; }
        .ai-unit-price { font-size:0.8125rem; font-weight:800; color:#4f46e5; }
        .ai-unit-stock { font-size:0.6875rem; color:#94a3b8; }

        @media(max-width:640px) {
            .ai-kpis { grid-template-columns:1fr 1fr; }
            .ai-methods { grid-template-columns:1fr; }
            .ai-cart-item { flex-wrap:wrap; }
            .ai-cart-controls { width:100%; justify-content:space-between; margin-top:0.5rem; }
            .ai-cart-total { min-width:auto; }
            .ai-actions { flex-direction:column-reverse; }
            .ai-btn-save, .ai-btn-cancel { width:100%; justify-content:center; }
        }
    </style>
    @endpush

    <div class="ai-page">

        {{-- Back --}}
        <a href="{{ route('transaksi.show', $transaction) }}" class="ai-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Transaksi
        </a>

        {{-- Header --}}
        <div class="ai-hdr">
            <div class="ai-hdr-ico">🛒</div>
            <div>
                <div class="ai-hdr-title">Tambah Item</div>
                <div class="ai-hdr-sub">{{ $transaction->invoice_number }}</div>
            </div>
        </div>

        {{-- KPI Summary --}}
        <div class="ai-kpis">
            <div class="ai-kpi blue">
                <div class="ai-kpi-lbl">Customer</div>
                <div class="ai-kpi-val blue">{{ $transaction->customer?->name ?? 'Umum' }}</div>
                <div class="ai-kpi-meta">{{ $transaction->customer ? 'Terdaftar' : 'Customer umum' }}</div>
            </div>
            <div class="ai-kpi green">
                <div class="ai-kpi-lbl">Total Awal</div>
                <div class="ai-kpi-val green">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                <div class="ai-kpi-meta">{{ $transaction->details->count() }} item</div>
            </div>
            <div class="ai-kpi amber">
                <div class="ai-kpi-lbl">Metode Bayar</div>
                <div class="ai-kpi-val amber">{{ strtoupper($transaction->payment_method) }}</div>
                @if($transaction->hasAdditionalItems())
                    <div class="ai-kpi-meta">+{{ $transaction->additionalTransactions->count() }} tambahan</div>
                @else
                    <div class="ai-kpi-meta">Belum ada tambahan</div>
                @endif
            </div>
        </div>

        {{-- Search Product --}}
        <div class="ai-section">
            <div class="ai-section-hdr">
                <div class="ai-section-ico blue">🔍</div>
                <div>
                    <div class="ai-section-title">Cari & Tambah Produk</div>
                    <div class="ai-section-desc">Ketik nama atau SKU untuk mencari produk</div>
                </div>
            </div>
            <div class="ai-section-body">
                <div class="ai-search-wrap">
                    <svg class="ai-search-ico" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="productSearch" class="ai-search" placeholder="Ketik nama atau SKU produk..." autocomplete="off">
                    <div id="productResults" class="ai-dropdown"></div>
                </div>
            </div>
        </div>

        {{-- Cart --}}
        <div class="ai-section">
            <div class="ai-section-hdr">
                <div class="ai-section-ico green">📦</div>
                <div>
                    <div class="ai-section-title">Item yang Ditambahkan</div>
                    <div class="ai-section-desc">Daftar produk tambahan untuk transaksi ini</div>
                </div>
            </div>
            <div class="ai-section-body">
                <div id="cartItems">
                    <div class="ai-cart-empty">
                        <div class="ai-cart-empty-ico">📋</div>
                        <div class="ai-cart-empty-text">Belum ada item. Cari dan tambahkan produk di atas.</div>
                    </div>
                </div>

                <div class="ai-totals">
                    <div class="ai-total-row">
                        <span>Total Item</span>
                        <span id="itemCount">0 item</span>
                    </div>
                    <div class="ai-total-row grand">
                        <span>Total Tambahan</span>
                        <span id="totalAmount">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="ai-section">
            <div class="ai-section-hdr">
                <div class="ai-section-ico purple">💳</div>
                <div>
                    <div class="ai-section-title">Pembayaran Tambahan</div>
                    <div class="ai-section-desc">Detail pembayaran untuk item tambahan</div>
                </div>
            </div>
            <div class="ai-section-body">
                <div class="ai-pay-grid">
                    <div>
                        <div class="ai-pay-label">Jumlah Pembayaran <span style="color:#ef4444;font-weight:800;">*</span></div>
                        <div class="ai-pay-wrap">
                            <span class="ai-pay-prefix">Rp</span>
                            <input type="text" id="additionalPayment" inputmode="numeric" placeholder="0" class="ai-pay-input has-prefix">
                        </div>
                        <span class="ai-pay-auto" id="autoFillPay" onclick="autoFillPayment()">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Isi otomatis dari total
                        </span>
                    </div>

                    <div>
                        <div class="ai-pay-label">Metode Pembayaran</div>
                        <div class="ai-methods" id="methodGroup">
                            <label class="ai-method {{ $transaction->payment_method === 'cash' ? 'selected' : '' }}" onclick="selectMethod(this, 'cash')">
                                <input type="radio" name="payMethod" value="cash" {{ $transaction->payment_method === 'cash' ? 'checked' : '' }}>
                                <div class="ai-method-ico">💵</div>
                                <div class="ai-method-lbl">Tunai</div>
                                <div class="ai-method-desc">Cash</div>
                            </label>
                            <label class="ai-method {{ $transaction->payment_method === 'transfer' ? 'selected' : '' }}" onclick="selectMethod(this, 'transfer')">
                                <input type="radio" name="payMethod" value="transfer" {{ $transaction->payment_method === 'transfer' ? 'checked' : '' }}>
                                <div class="ai-method-ico">🏦</div>
                                <div class="ai-method-lbl">Transfer</div>
                                <div class="ai-method-desc">Bank</div>
                            </label>
                            <label class="ai-method {{ $transaction->payment_method === 'qris' ? 'selected' : '' }}" onclick="selectMethod(this, 'qris')">
                                <input type="radio" name="payMethod" value="qris" {{ $transaction->payment_method === 'qris' ? 'checked' : '' }}>
                                <div class="ai-method-ico">📱</div>
                                <div class="ai-method-lbl">QRIS</div>
                                <div class="ai-method-desc">Scan QR</div>
                            </label>
                        </div>
                    </div>

                    <div id="refField" style="display:{{ in_array($transaction->payment_method, ['transfer','qris']) ? 'block' : 'none' }};">
                        <div class="ai-pay-label">No. Referensi</div>
                        <input type="text" id="paymentReference" class="ai-pay-input" placeholder="Contoh: TRX-123456">
                    </div>

                    <div>
                        <div class="ai-pay-label">Catatan</div>
                        <textarea id="notes" class="ai-pay-input" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="ai-actions">
            <a href="{{ route('transaksi.show', $transaction) }}" class="ai-btn-cancel">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Batal
            </a>
            <button type="button" id="btnSave" class="ai-btn-save" disabled>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Tambahan Item
            </button>
        </div>

    </div>

    {{-- Unit Selection Modal --}}
    <div id="unitModal" class="ai-modal-bg">
        <div class="ai-modal">
            <div class="ai-modal-hdr">
                <div class="ai-modal-title" id="unitModalTitle">Pilih Satuan</div>
                <button onclick="closeUnitModal()" class="ai-modal-close">&times;</button>
            </div>
            <div id="unitModalBody" class="ai-modal-body"></div>
        </div>
    </div>

    @push('scripts')
    <script>
    const PRODUCTS = @json($products);
    const WAREHOUSES = @json(\App\Models\Warehouse::where('active', true)->get(['id', 'name']));
    const ORIGINAL_TRANSACTION_ID = {{ $transaction->id }};
    const CSRF_TOKEN = '{{ csrf_token() }}';

    let cart = [];
    let selectedMethod = '{{ $transaction->payment_method }}';

    // ─── PRODUCT SEARCH ───
    const productSearch = document.getElementById('productSearch');
    const productResults = document.getElementById('productResults');

    productSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        if (query.length < 2) { productResults.classList.remove('active'); return; }

        const filtered = PRODUCTS.filter(p =>
            p.name.toLowerCase().includes(query) || (p.sku && p.sku.toLowerCase().includes(query))
        ).slice(0, 10);

        if (filtered.length > 0) {
            productResults.innerHTML = filtered.map(p => {
                const unitsList = p.units && p.units.length
                    ? p.units.map(u => `${u.name}: Rp ${(u.prices?.eceran||0).toLocaleString('id-ID')}`).join(' · ')
                    : `Rp ${(p.prices?.eceran||0).toLocaleString('id-ID')}`;
                return `
                <div class="ai-dropdown-item" onclick="showUnitSelection(${p.id})">
                    <div class="ai-dropdown-name">${p.name}</div>
                    <div class="ai-dropdown-meta">SKU: ${p.sku||'-'} · Stok: ${p.stock||0}</div>
                    <div class="ai-dropdown-price">${unitsList}</div>
                </div>`;
            }).join('');
            productResults.classList.add('active');
        } else {
            productResults.innerHTML = '<div class="ai-dropdown-empty">Tidak ada produk ditemukan</div>';
            productResults.classList.add('active');
        }
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.ai-search-wrap')) productResults.classList.remove('active');
    });

    // ─── UNIT SELECTION ───
    function showUnitSelection(productId) {
        const product = PRODUCTS.find(p => p.id === productId);
        if (!product) return;

        if (product.units && product.units.length > 0) {
            document.getElementById('unitModalTitle').textContent = 'Pilih Satuan — ' + product.name;
            document.getElementById('unitModalBody').innerHTML = product.units.map((u, idx) =>
                `<div class="ai-unit-opt" onclick="selectUnit(${productId}, ${idx})">
                    <div>
                        <div class="ai-unit-name">${u.name}</div>
                        <div class="ai-unit-stock">Stok tersedia</div>
                    </div>
                    <div class="ai-unit-price">Rp ${(u.prices?.eceran||0).toLocaleString('id-ID')}</div>
                </div>`
            ).join('');
            document.getElementById('unitModal').classList.add('active');
        } else {
            addToCartWithUnit(productId, null);
        }
    }

    function selectUnit(productId, unitIndex) { closeUnitModal(); addToCartWithUnit(productId, unitIndex); }
    function closeUnitModal() { document.getElementById('unitModal').classList.remove('active'); }
    document.getElementById('unitModal').addEventListener('click', function(e) { if (e.target === this) closeUnitModal(); });

    // ─── CART MANAGEMENT ───
    function addToCartWithUnit(productId, unitIndex) {
        const product = PRODUCTS.find(p => p.id === productId);
        if (!product) return;

        const defaultWarehouse = WAREHOUSES[0]?.id || 1;
        let unitPrice = product.prices?.eceran || 0;
        let unitName = 'pcs';
        let unitId = null;

        if (unitIndex !== null && product.units && product.units[unitIndex]) {
            const unitData = product.units[unitIndex];
            unitPrice = unitData.prices?.eceran || 0;
            unitName = unitData.name;
            unitId = unitData.id;
        }

        const existing = cart.find(item => item.product_id === productId && item.unit_id === unitId);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({
                product_id: productId, unit_id: unitId, unit_name: unitName,
                name: product.name, sku: product.sku, price: unitPrice,
                qty: 1, warehouse_id: defaultWarehouse, stock: product.stock || 0,
                units: product.units || [],
            });
        }

        productSearch.value = '';
        productResults.classList.remove('active');
        renderCart();
    }

    function removeFromCart(index) { cart.splice(index, 1); renderCart(); }

    function updateQty(index, newQty) {
        newQty = parseInt(newQty) || 1;
        if (newQty < 1) newQty = 1;
        if (newQty > cart[index].stock) { alert('Stok tidak mencukupi!'); newQty = cart[index].stock; }
        cart[index].qty = newQty;
        renderCart();
    }

    function updatePrice(index, newPrice) {
        newPrice = parseInt(String(newPrice).replace(/[^\d]/g,'')) || 0;
        cart[index].price = newPrice;
        renderCart();
    }

    function changeUnit(index, unitId) {
        const item = cart[index];
        const unit = item.units.find(u => u.id == unitId);
        if (unit) { item.unit_id = unit.id; item.unit_name = unit.name; item.price = unit.prices?.eceran || 0; renderCart(); }
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        if (cart.length === 0) {
            container.innerHTML = '<div class="ai-cart-empty"><div class="ai-cart-empty-ico">📋</div><div class="ai-cart-empty-text">Belum ada item. Cari dan tambahkan produk di atas.</div></div>';
            document.getElementById('btnSave').disabled = true;
            updateTotals(); return;
        }

        container.innerHTML = '<div style="display:flex;flex-direction:column;gap:0.625rem;">' + cart.map((item, i) => `
            <div class="ai-cart-item">
                <div class="ai-cart-info">
                    <div class="ai-cart-name">${item.name}</div>
                    <div class="ai-cart-meta">
                        <span>SKU: ${item.sku||'-'}</span>
                        <span>·</span>
                        <span>Stok: ${item.stock}</span>
                        ${item.units && item.units.length > 0
                            ? `<select onchange="changeUnit(${i}, this.value)">${item.units.map(u => `<option value="${u.id}" ${u.id==item.unit_id?'selected':''}>${u.name}</option>`).join('')}</select>`
                            : ''}
                        ${WAREHOUSES.length > 1
                            ? `<select onchange="cart[${i}].warehouse_id=this.value;">${WAREHOUSES.map(w => `<option value="${w.id}" ${w.id==item.warehouse_id?'selected':''}>${w.name}</option>`).join('')}</select>`
                            : ''}
                    </div>
                </div>
                <div class="ai-cart-controls">
                    <input type="number" class="ai-qty-input" min="1" max="${item.stock}" value="${item.qty}" onchange="updateQty(${i}, this.value)" title="Qty">
                    <input type="text" class="ai-price-input" value="${item.price.toLocaleString('id-ID')}" onchange="updatePrice(${i}, this.value)" title="Harga">
                    <div class="ai-cart-total">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</div>
                    <button class="ai-cart-remove" onclick="removeFromCart(${i})" title="Hapus">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        `).join('') + '</div>';

        document.getElementById('btnSave').disabled = false;
        updateTotals();
    }

    function updateTotals() {
        const totalQty = cart.reduce((s, i) => s + i.qty, 0);
        const totalAmount = cart.reduce((s, i) => s + (i.price * i.qty), 0);
        document.getElementById('itemCount').textContent = totalQty + ' item';
        document.getElementById('totalAmount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
    }

    // ─── PAYMENT ───
    function selectMethod(el, method) {
        selectedMethod = method;
        document.querySelectorAll('.ai-method').forEach(m => m.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;
        document.getElementById('refField').style.display = (method === 'transfer' || method === 'qris') ? 'block' : 'none';
        if (method === 'cash') document.getElementById('paymentReference').value = '';
    }

    // Auto-format payment input
    const payInput = document.getElementById('additionalPayment');
    payInput.addEventListener('input', function() {
        let raw = this.value.replace(/[^\d]/g, '');
        this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    });

    function autoFillPayment() {
        const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
        if (total > 0) {
            payInput.value = total.toLocaleString('id-ID');
        }
    }

    function getPaymentValue() {
        return parseInt(payInput.value.replace(/[^\d]/g, '')) || 0;
    }

    // ─── SAVE ───
    document.getElementById('btnSave').addEventListener('click', async function() {
        if (cart.length === 0) { alert('Keranjang masih kosong.'); return; }

        const items = cart.map(i => ({ product_id: i.product_id, quantity: i.qty, price: i.price, warehouse_id: i.warehouse_id }));
        const totalAmount = items.reduce((s, i) => s + (i.price * i.quantity), 0);
        const additionalPayment = getPaymentValue();

        if (additionalPayment <= 0) { alert('Pembayaran tambahan wajib diisi.'); payInput.focus(); return; }
        if (additionalPayment < totalAmount) {
            alert('Pembayaran kurang! Total: Rp ' + totalAmount.toLocaleString('id-ID') + ', Dibayar: Rp ' + additionalPayment.toLocaleString('id-ID'));
            payInput.focus(); return;
        }

        const paymentReference = document.getElementById('paymentReference').value;
        if ((selectedMethod === 'transfer' || selectedMethod === 'qris') && !paymentReference.trim()) {
            alert('No. Referensi wajib diisi untuk Transfer/QRIS.'); document.getElementById('paymentReference').focus(); return;
        }

        this.disabled = true;
        this.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;"><path d="M12 2v4m0 12v4m-7.07-3.93l2.83-2.83m8.48-8.48l2.83-2.83M2 12h4m12 0h4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83"/></svg> Menyimpan...';

        try {
            const response = await fetch(`/kasir/transactions/${ORIGINAL_TRANSACTION_ID}/add-items`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({
                    items, additional_payment: additionalPayment, payment_method: selectedMethod,
                    payment_reference: paymentReference, notes: document.getElementById('notes').value,
                }),
            });

            const result = await response.json();
            if (result.success) {
                alert('Item tambahan berhasil disimpan!\nPick Order: ' + (result.pick_order || '-') + '\n\nAnda akan diarahkan ke detail transaksi.');
                window.location.href = '{{ route('transaksi.show', $transaction) }}';
            } else {
                alert('Gagal: ' + (result.message || 'Unknown error'));
                resetSaveBtn();
            }
        } catch (error) {
            alert('Error: ' + error.message);
            resetSaveBtn();
        }
    });

    function resetSaveBtn() {
        const btn = document.getElementById('btnSave');
        btn.disabled = false;
        btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Tambahan Item';
    }
    </script>
    <style>@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>
    @endpush
</x-app-layout>
