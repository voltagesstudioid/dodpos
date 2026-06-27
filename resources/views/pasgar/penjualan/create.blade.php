<x-app-layout>
    <x-slot name="header">Transaksi Penjualan - Pasgar</x-slot>

    @push('styles')
    <style>
        .ti-box { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 1.5rem; overflow: hidden; border: 1px solid #e2e8f0; }
        .ti-box-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: space-between; }
        .ti-box-title { font-size: 1.125rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; }
        .ti-box-body { padding: 1.5rem; }
        .ti-form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
        
        .ti-label { display: block; font-size: 0.8125rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .ti-input, .ti-select, .ti-textarea { width: 100%; padding: 0.6rem 1rem; border: 1.5px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 0.95rem; color: #1e293b; background: #fff; transition: all 0.2s; outline: none; box-sizing: border-box; }
        .ti-input:focus, .ti-select:focus, .ti-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
        .ti-textarea { min-height: 80px; resize: vertical; }

        .ti-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
        .ti-btn-primary { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
        .ti-btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16,185,129,0.4); color: #fff; }
        .ti-btn-primary:disabled { background: #94a3b8; box-shadow: none; cursor: not-allowed; opacity: 0.7; transform: none; }
        .ti-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #cbd5e1; }
        .ti-btn-secondary:hover { background: #f8fafc; border-color: #94a3b8; color: #1e293b; }

        .ti-pill-group { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .ti-pill { padding: 0.5rem 1.25rem; border-radius: 99px; border: 1.5px solid #cbd5e1; font-weight: 700; color: #64748b; cursor: pointer; background: #fff; transition: all 0.2s; font-size: 0.85rem; }
        .ti-pill:hover { border-color: #10b981; }
        .ti-pill.active { background: #d1fae5; border-color: #10b981; color: #059669; }
        .ti-pill.disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none; }

        /* Items */
        .item-card { background: #fff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; transition: all 0.2s; display: grid; grid-template-columns: 1fr auto auto auto; gap: 1rem; align-items: center; }
        .item-card:hover { border-color: #10b981; box-shadow: 0 4px 15px rgba(16,185,129,0.05); }
        .item-card.selected { border-color: #10b981; background: #f0fdf4; }
        
        .item-info { display: flex; align-items: flex-start; gap: 0.75rem; }
        .item-checkbox { width: 20px; height: 20px; accent-color: #10b981; cursor: pointer; margin-top: 2px; }
        .item-name { font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem; }
        .item-sku { font-size: 0.75rem; color: #64748b; }
        .item-stock { font-size: 0.75rem; font-weight: 700; color: #059669; background: #d1fae5; padding: 2px 8px; border-radius: 99px; display: inline-block; margin-left: 0.5rem; }

        .qty-control { display: flex; align-items: center; border: 1.5px solid #cbd5e1; border-radius: 8px; overflow: hidden; height: 38px; }
        .qty-btn { width: 32px; height: 100%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #475569; font-weight: 800; cursor: pointer; border: none; transition: 0.2s; }
        .qty-btn:hover { background: #e2e8f0; color: #0f172a; }
        .qty-input { width: 50px; height: 100%; border: none; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1; text-align: center; font-weight: 800; color: #0f172a; font-size: 0.95rem; outline: none; }

        .price-input { width: 120px; padding: 0.5rem; border: 1.5px solid #cbd5e1; border-radius: 8px; font-weight: 800; color: #0f172a; text-align: right; outline: none; transition: 0.2s; font-size: 0.95rem; }
        .price-input:focus { border-color: #10b981; }
        .price-input.is-invalid { border-color: #ef4444; background: #fef2f2; }

        .price-helper { font-size: 0.7rem; color: #64748b; margin-top: 4px; display: flex; gap: 4px; flex-wrap: wrap; justify-content: flex-end; max-width: 120px; }
        .price-helper span { background: #f1f5f9; padding: 2px 4px; border-radius: 4px; font-weight: 600; }
        .price-helper.is-invalid { color: #ef4444; font-weight: 700; }

        .item-subtotal { font-size: 1.1rem; font-weight: 900; color: #10b981; text-align: right; min-width: 110px; }

        /* Searchable Select Native Wrapper */
        .ti-search-select { position: relative; }
        .ti-search-select-input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; box-sizing: border-box; }
        .ti-search-select-input:focus { border-color: #10b981; }
        .ti-search-select-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .ti-search-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1.5px solid #cbd5e1; border-radius: 8px; margin-top: 4px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 50; max-height: 250px; overflow-y: auto; display: none; }
        .ti-search-dropdown.show { display: block; }
        .ti-search-option { padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: 0.2s; }
        .ti-search-option:hover { background: #f0fdf4; }
        .ti-search-option-title { font-weight: 700; color: #0f172a; }
        .ti-search-option-sub { font-size: 0.75rem; color: #64748b; }

        .ti-summary { display: flex; justify-content: flex-end; padding: 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; align-items: center; gap: 2rem; }
        .ti-total-label { font-size: 0.875rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .ti-total-value { font-size: 1.75rem; font-weight: 900; color: #10b981; }

        @media (max-width: 768px) {
            .item-card { grid-template-columns: 1fr; gap: 0.75rem; }
            .item-subtotal { text-align: left; }
            .price-helper { justify-content: flex-start; max-width: none; }
        }
    </style>
    @endpush

    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 font-bold">
            @foreach($errors->all() as $error) <div>❌ {{ $error }}</div> @endforeach
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 font-bold">
            ❌ {{ session('error') }}
        </div>
        @endif

        <div class="ti-box">
            <div class="ti-box-header">
                <div class="ti-box-title">
                    <svg width="24" height="24" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Penjualan Baru Pasgar
                </div>
                <a href="{{ route('pasgar.loading.index') }}" class="ti-btn ti-btn-secondary">Kembali</a>
            </div>
            <div class="ti-box-body" style="padding-top: 1rem; padding-bottom: 1rem; background: #fafafa;">
                <div style="color:#64748b;font-size:0.9rem;">
                    Loading: <strong style="color:#0f172a;">{{ $loading->nomor_loading }}</strong> &nbsp;&bull;&nbsp; 
                    Sales: <strong style="color:#0f172a;">{{ $salesProfile->nama }}</strong> &nbsp;&bull;&nbsp; 
                    Tanggal: <strong style="color:#0f172a;">{{ $loading->loaded_at?->format('d/m/Y') ?? '' }}</strong>
                </div>
            </div>
        </div>

        <form action="{{ route('pasgar.penjualan.store') }}" method="POST" id="penjualanForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="loading_id" value="{{ $loading->id }}">

            {{-- Customer --}}
            <div class="ti-box">
                <div class="ti-box-header">
                    <div class="ti-box-title" style="font-size: 1rem;">1. Data Pelanggan</div>
                </div>
                <div class="ti-box-body">
                    <div style="margin-bottom: 1.25rem;">
                        <label class="ti-label">Tipe Pelanggan</label>
                        <div class="ti-pill-group">
                            <div class="ti-pill active" id="btnCustUmum" onclick="setCustType('umum')">Umum (Tanpa Akun)</div>
                            <div class="ti-pill" id="btnCustTerdaftar" onclick="setCustType('terdaftar')">Pelanggan Terdaftar</div>
                        </div>
                    </div>

                    <div id="sectionCustUmum">
                        <div class="ti-form-grid">
                            <div>
                                <label class="ti-label">Nama Pembeli (Opsional)</label>
                                <input type="text" name="nama_pelanggan" class="ti-input" placeholder="Masukkan nama pembeli...">
                            </div>
                            <div>
                                <label class="ti-label">No HP (Opsional)</label>
                                <input type="text" name="telepon_pelanggan" class="ti-input" placeholder="Masukkan nomor HP...">
                            </div>
                        </div>
                    </div>

                    <div id="sectionCustTerdaftar" style="display: none;">
                        <label class="ti-label">Pilih Pelanggan <span style="color:#ef4444">*</span></label>
                        <div class="ti-search-select" id="custSearchContainer">
                            <svg class="ti-search-select-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" class="ti-search-select-input" id="custSearchInput" placeholder="Cari nama toko atau pemilik..." autocomplete="off">
                            <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id') }}">
                            <div class="ti-search-dropdown" id="custDropdown">
                                <!-- Populated by JS -->
                            </div>
                        </div>
                        <div id="custSelectedInfo" style="margin-top:0.75rem; display:none; background:#f0fdf4; border:1px solid #bbf7d0; padding:0.75rem; border-radius:8px; color:#065f46; font-size:0.85rem; align-items:center; gap:0.5rem; font-weight:600;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="custInfoText"></span>
                            <button type="button" onclick="clearCust()" style="margin-left:auto; background:none; border:none; color:#ef4444; font-weight:700; cursor:pointer; font-size:0.75rem;">Batal Pilih</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="ti-box">
                <div class="ti-box-header">
                    <div class="ti-box-title" style="font-size: 1rem;">2. Barang yang Dijual</div>
                </div>
                
                <div style="padding: 1rem 1.5rem 0 1.5rem;">
                    <div class="ti-search-select">
                        <svg class="ti-search-select-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" class="ti-search-select-input" id="itemSearchInput" placeholder="Ketik nama barang atau SKU untuk menyaring list di bawah..." oninput="filterItems(this.value)">
                    </div>
                </div>

                <div class="ti-box-body" id="itemsContainer" style="max-height: 500px; overflow-y: auto;">
                    <!-- Rendered by JS -->
                </div>
                
                <div class="ti-summary">
                    <div>
                        <div class="ti-total-label"><span id="totalItemsCount">0</span> Item Terpilih</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="ti-total-label">Total Tagihan</div>
                        <div class="ti-total-value">Rp <span id="grandTotal">0</span></div>
                    </div>
                </div>
            </div>

            {{-- Payment --}}
            <div class="ti-box">
                <div class="ti-box-header">
                    <div class="ti-box-title" style="font-size: 1rem;">3. Pembayaran</div>
                </div>
                <div class="ti-box-body">
                    <div style="margin-bottom: 1.25rem;">
                        <label class="ti-label">Metode Bayar <span style="color:#ef4444">*</span></label>
                        <div class="ti-pill-group">
                            <div class="ti-pill active" id="btnPayTunai" onclick="setPayment('tunai')">💵 Tunai</div>
                            <div class="ti-pill" id="btnPayTransfer" onclick="setPayment('transfer')">🏦 Transfer</div>
                        </div>
                        <input type="hidden" name="metode_bayar" id="metode_bayar" value="tunai">
                    </div>

                    <div id="transferSection" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; padding: 1.25rem; border-radius: 8px; margin-bottom: 1.25rem;">
                        <div class="ti-form-grid">
                            <div>
                                <label class="ti-label">ID Transaksi / Referensi <span style="color:#ef4444">*</span></label>
                                <input type="text" name="id_transaksi_transfer" id="id_transaksi_transfer" class="ti-input" placeholder="Contoh: TRX-1234...">
                            </div>
                            <div>
                                <label class="ti-label">Bukti Transfer <span style="color:#ef4444">*</span></label>
                                <input type="file" name="foto_bukti_transfer" id="foto_bukti_transfer" class="ti-input" style="padding: 0.4rem 1rem;" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="ti-label">Catatan Transaksi</label>
                        <textarea name="catatan" class="ti-textarea" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="ti-box-header" style="background:#f8fafc; border-top:1px solid #e2e8f0; justify-content:flex-end; gap:1rem;">
                    <a href="{{ route('pasgar.loading.index') }}" class="ti-btn ti-btn-secondary">Batal</a>
                    <button type="button" class="ti-btn ti-btn-primary" id="btnSubmit" onclick="submitForm()">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const PELANGGANS = {!! $pelanggansJson !!};
        const AVAILABLE_ITEMS = {!! $itemsJson !!};
        const IS_SALES = {{ $isSalesRole ? 'true' : 'false' }};
        
        let custType = 'umum';
        let paymentMethod = 'tunai';
        let items = [];

        // Formatting utilities
        function formatRp(n) { return (n || 0).toLocaleString('id-ID'); }
        function parseRp(s) { return parseInt(String(s).replace(/[^0-9]/g, '')) || 0; }
        
        function formatRupiahInput(el) {
            let val = el.value.replace(/[^0-9]/g, '');
            if(val) {
                // Save cursor position roughly
                let pos = el.selectionStart;
                let oldLen = el.value.length;
                
                el.value = parseInt(val).toLocaleString('id-ID');
                
                // Adjust cursor
                let newLen = el.value.length;
                pos = pos + (newLen - oldLen);
                el.setSelectionRange(pos, pos);
            } else {
                el.value = '';
            }
        }

        function init() {
            // Setup initial items array
            items = AVAILABLE_ITEMS.map((ai, idx) => ({
                idx: idx,
                loading_item_id: ai.loading_item_id,
                product_id: ai.product_id,
                product_name: ai.product_name,
                sku: ai.sku,
                qty_sisa: ai.qty_sisa,
                conversions: ai.conversions || [],
                current_conv: ai.current_conversion,
                selected: false,
                qty: 0,
                harga: ai.current_conversion ? ai.current_conversion.price : 0,
                min_price: ai.current_conversion ? ai.current_conversion.min_price : 0,
                visible: true,
                priceError: false
            }));
            
            renderItems();

            // Setup Customer Search Dropdown
            const searchInput = document.getElementById('custSearchInput');
            const dropdown = document.getElementById('custDropdown');
            
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                dropdown.innerHTML = '';
                
                if (q.length === 0) {
                    dropdown.classList.remove('show');
                    return;
                }
                
                const filtered = PELANGGANS.filter(p => p.nama_toko.toLowerCase().includes(q) || (p.nama_pemilik && p.nama_pemilik.toLowerCase().includes(q)));
                
                if (filtered.length === 0) {
                    dropdown.innerHTML = '<div class="ti-search-option"><div class="ti-search-option-sub">Tidak ditemukan</div></div>';
                } else {
                    filtered.forEach(p => {
                        const div = document.createElement('div');
                        div.className = 'ti-search-option';
                        div.innerHTML = `<div class="ti-search-option-title">${p.nama_toko}</div><div class="ti-search-option-sub">${p.nama_pemilik} &bull; Sisa Limit: Rp ${formatRp(p.sisa_limit)}</div>`;
                        div.onclick = () => selectCust(p);
                        dropdown.appendChild(div);
                    });
                }
                dropdown.classList.add('show');
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!document.getElementById('custSearchContainer').contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }

        // Customer Logic
        function setCustType(type) {
            custType = type;
            document.getElementById('btnCustUmum').classList.toggle('active', type === 'umum');
            document.getElementById('btnCustTerdaftar').classList.toggle('active', type === 'terdaftar');
            document.getElementById('sectionCustUmum').style.display = (type === 'umum') ? 'block' : 'none';
            document.getElementById('sectionCustTerdaftar').style.display = (type === 'terdaftar') ? 'block' : 'none';
            
            if (type === 'umum') {
                clearCust();
            }
            updateKreditPill();
        }

        function selectCust(p) {
            document.getElementById('pelanggan_id').value = p.id;
            document.getElementById('custSearchInput').value = '';
            document.getElementById('custDropdown').classList.remove('show');
            document.getElementById('custSearchInput').style.display = 'none';
            document.getElementById('custSelectedInfo').style.display = 'flex';
            document.getElementById('custInfoText').textContent = `${p.nama_toko} (${p.nama_pemilik})`;
            
            updateKreditPill();
        }

        function clearCust() {
            document.getElementById('pelanggan_id').value = '';
            document.getElementById('custSearchInput').style.display = 'block';
            document.getElementById('custSearchInput').value = '';
            document.getElementById('custSelectedInfo').style.display = 'none';
            
            if (paymentMethod === 'limit') {
                setPayment('tunai');
            }
            updateKreditPill();
        }

        function updateKreditPill() {
            // No longer needed
        }

        // Payment Logic
        function setPayment(method) {
            paymentMethod = method;
            document.getElementById('btnPayTunai').classList.toggle('active', method === 'tunai');
            document.getElementById('btnPayTransfer').classList.toggle('active', method === 'transfer');
            document.getElementById('metode_bayar').value = method;
            
            document.getElementById('transferSection').style.display = (method === 'transfer') ? 'block' : 'none';
        }

        // Items Logic
        function filterItems(q) {
            const query = q.toLowerCase();
            items.forEach(item => {
                item.visible = item.product_name.toLowerCase().includes(query) || (item.sku && item.sku.toLowerCase().includes(query));
            });
            renderItems();
        }

        function toggleItem(idx) {
            const item = items[idx];
            item.selected = !item.selected;
            if (item.selected && item.qty === 0) item.qty = 1;
            if (!item.selected) item.qty = 0;
            checkPriceError(idx);
            renderItems();
        }

        function changeQty(idx, delta) {
            const item = items[idx];
            if (!item.selected) item.selected = true;
            
            let nq = item.qty + delta;
            if (nq < 0) nq = 0;
            
            item.qty = nq;
            if (item.qty === 0) item.selected = false;
            
            renderItems();
        }

        function inputQty(idx, val) {
            const item = items[idx];
            if (!item.selected && val > 0) item.selected = true;
            
            let nq = parseInt(val) || 0;
            if (nq < 0) nq = 0;
            
            item.qty = nq;
            if (item.qty === 0) item.selected = false;
            
            renderItems();
        }

        function changeUnit(idx, sel) {
            const item = items[idx];
            const convId = parseInt(sel.value);
            const conv = item.conversions.find(c => c.id === convId);
            if (conv) {
                item.current_conv = conv;
                item.harga = conv.price || 0;
                item.min_price = conv.min_price || 0;
            }
            checkPriceError(idx);
            renderItems();
        }

        function changePrice(idx, val) {
            const item = items[idx];
            item.harga = parseRp(val);
            checkPriceError(idx);
            renderItems();
        }

        function checkPriceError(idx) {
            const item = items[idx];
            if (IS_SALES && item.selected && item.harga < item.min_price) {
                item.priceError = true;
            } else {
                item.priceError = false;
            }
        }

        function renderItems() {
            const container = document.getElementById('itemsContainer');
            let html = '';
            let selectedCount = 0;
            let grandTotal = 0;
            let hasPriceError = false;

            items.forEach(item => {
                if (!item.visible) return;

                let subtotal = item.qty * item.harga;
                if (item.selected && item.qty > 0) {
                    selectedCount++;
                    grandTotal += subtotal;
                    if (item.priceError) hasPriceError = true;
                }
                
                let unitsHtml = '';
                if (item.conversions.length > 1) {
                    unitsHtml = `<select class="ti-select" style="padding: 2px 6px; height: 26px; font-size: 0.75rem; width: auto; display: inline-block; border-color: #cbd5e1;" onchange="changeUnit(${item.idx}, this)">`;
                    item.conversions.forEach(c => {
                        unitsHtml += `<option value="${c.id}" ${c.id === item.current_conv?.id ? 'selected' : ''}>${c.unit_name}</option>`;
                    });
                    unitsHtml += `</select>`;
                } else if (item.current_conv) {
                    unitsHtml = `<span style="font-size:0.75rem; color:#64748b; background:#f1f5f9; padding:2px 6px; border-radius:4px;">${item.current_conv.unit_name}</span>`;
                }

                html += `
                    <div class="item-card ${item.selected ? 'selected' : ''}">
                        <div class="item-info">
                            <input type="checkbox" class="item-checkbox" ${item.selected ? 'checked' : ''} onchange="toggleItem(${item.idx})">
                            <div>
                                <div class="item-name">${item.product_name} ${unitsHtml} <span class="item-stock" title="Stok asli: ${item.qty_sisa}">Sisa: ${item.qty_sisa}</span></div>
                                <div class="item-sku">${item.sku || '-'} &bull; ${item.category || '-'}</div>
                            </div>
                        </div>
                        
                        <div>
                            <div style="font-size:0.7rem; font-weight:700; color:#64748b; margin-bottom:4px; text-transform:uppercase;">Kuantitas</div>
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="changeQty(${item.idx}, -1)">−</button>
                                <input type="text" class="qty-input" value="${item.qty}" onchange="inputQty(${item.idx}, this.value)" onfocus="this.select()">
                                <button type="button" class="qty-btn" onclick="changeQty(${item.idx}, 1)">+</button>
                            </div>
                        </div>

                        <div>
                            <div style="font-size:0.7rem; font-weight:700; color:#64748b; margin-bottom:4px; text-transform:uppercase;">Harga Jual (Rp)</div>
                            <input type="text" class="price-input ${item.priceError ? 'is-invalid' : ''}" value="${item.harga > 0 ? formatRp(item.harga) : ''}" oninput="formatRupiahInput(this)" onchange="changePrice(${item.idx}, this.value)" onfocus="this.select()">
                            <div class="price-helper ${item.priceError ? 'is-invalid' : ''}">
                                ${IS_SALES && item.min_price > 0 ? `Batas Min: Rp ${formatRp(item.min_price)}` : ''}
                            </div>
                        </div>

                        <div class="item-subtotal">Rp ${formatRp(subtotal)}</div>
                    </div>
                `;
            });

            container.innerHTML = html;
            document.getElementById('totalItemsCount').textContent = selectedCount;
            document.getElementById('grandTotal').textContent = formatRp(grandTotal);

            const btnSubmit = document.getElementById('btnSubmit');
            // We no longer disable natively so user can click and see error.
            if (selectedCount === 0 || hasPriceError) {
                // btnSubmit.disabled = true;
            } else {
                // btnSubmit.disabled = false;
            }
        }

        function submitForm() {
            let selectedCount = 0;
            let hasPriceError = false;
            items.forEach(item => {
                if (item.selected && item.qty > 0) {
                    selectedCount++;
                    if (item.priceError) hasPriceError = true;
                }
            });

            if (selectedCount === 0) {
                alert('Silakan pilih minimal 1 barang terlebih dahulu.');
                return;
            }

            if (hasPriceError) {
                alert('Ada harga barang yang berada di bawah batas minimal. Silakan periksa kotak berwarna merah pada daftar barang.');
                return;
            }

            const btnSubmit = document.getElementById('btnSubmit');
            if (btnSubmit.disabled) return;

            const form = document.getElementById('penjualanForm');
            // Remove previous hidden inputs
            document.querySelectorAll('.hidden-item-input').forEach(e => e.remove());
            
            let hIdx = 0;
            items.forEach(item => {
                if (item.selected && item.qty > 0 && !item.priceError) {
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" class="hidden-item-input" name="items[${hIdx}][loading_item_id]" value="${item.loading_item_id}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" class="hidden-item-input" name="items[${hIdx}][product_id]" value="${item.product_id}">`);
                    if (item.current_conv) {
                        form.insertAdjacentHTML('beforeend', `<input type="hidden" class="hidden-item-input" name="items[${hIdx}][unit_conversion_id]" value="${item.current_conv.id}">`);
                    }
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" class="hidden-item-input" name="items[${hIdx}][qty]" value="${item.qty}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" class="hidden-item-input" name="items[${hIdx}][harga]" value="${item.harga}">`);
                    hIdx++;
                }
            });

            // Validate Transfer Method
            if (paymentMethod === 'transfer') {
                const idTx = document.getElementById('id_transaksi_transfer').value.trim();
                const file = document.getElementById('foto_bukti_transfer').files.length;
                if (!idTx || file === 0) {
                    alert('ID Transaksi dan Bukti Transfer wajib diisi!');
                    return;
                }
            }

            btnSubmit.innerHTML = 'Memproses...';
            btnSubmit.disabled = true;
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', init);
    </script>
    @endpush
</x-app-layout>
