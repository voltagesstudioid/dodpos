<x-app-layout>
    <x-slot name="header">Buat Purchase Order</x-slot>

    <style>
        .autocomplete-results {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .autocomplete-results::-webkit-scrollbar {
            width: 6px;
        }
        .autocomplete-results::-webkit-scrollbar-track {
            background: transparent;
        }
        .autocomplete-results::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .autocomplete-item:last-child {
            border-bottom: none !important;
        }
        .autocomplete-item.active {
            background: #f1f5f9 !important;
        }
        .product-search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>

    <div class="page-container" style="max-width:1150px;">

        {{-- Header --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:800; color:#0f172a; margin:0; display:flex; align-items:center; gap:0.5rem;">
                    <span style="background:#dbeafe; padding:0.35rem 0.5rem; border-radius:8px;">🛒</span> Buat Purchase Order Baru
                </h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.3rem 0 0;">Isi detail PO dan tambahkan produk yang akan dipesan</p>
            </div>
            <a href="{{ route('pembelian.order') }}" class="btn-secondary" style="font-size:0.875rem;">← Kembali</a>
        </div>

        @if(session('error')) <div class="alert alert-danger" style="margin-bottom:1rem;">❌ {{ session('error') }}</div> @endif
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1rem;">
                <strong>Kesalahan:</strong>
                <ul style="margin:0.5rem 0 0; padding-left:1.25rem;">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('pembelian.order.store') }}" method="POST" id="po-form">
            @csrf

            {{-- Header PO --}}
            <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <span style="width:24px; height:24px; background:#3b82f6; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;">1</span>
                    <span style="font-weight:700; color:#334155;">Info Pesanan</span>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. PO <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="po_number" value="{{ old('po_number', $poNumber) }}" class="form-input @error('po_number') input-error @enderror" required style="font-family:monospace; font-weight:700;">
                        @error('po_number') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier <span style="color:#ef4444;">*</span></label>
                        <select name="supplier_id" class="form-input @error('supplier_id') input-error @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id')==$s->id ? 'selected':'' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pesanan <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="form-input @error('order_date') input-error @enderror" required>
                        @error('order_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estimasi Tiba <span style="color:#64748b; font-weight:400;">(Opsional)</span></label>
                        <input type="date" name="expected_date" value="{{ old('expected_date') }}" class="form-input @error('expected_date') input-error @enderror">
                        @error('expected_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran <span class="required">*</span></label>
                        <select name="payment_term" class="form-input @error('payment_term') input-error @enderror" required>
                            <option value="cash" {{ old('payment_term')==='cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="credit" {{ old('payment_term','credit')==='credit' ? 'selected' : '' }}>Kredit / Hutang</option>
                        </select>
                        @error('payment_term') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jatuh Tempo <span style="color:#64748b; font-weight:400;">(Opsional, untuk Kredit)</span></label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-input @error('due_date') input-error @enderror">
                        @error('due_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Catatan <span style="color:#94a3b8; font-weight:400;">(Opsional)</span></label>
                    <textarea name="notes" rows="2" class="form-input">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Items --}}
            <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span style="width:24px; height:24px; background:#3b82f6; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;">2</span>
                        <span style="font-weight:700; color:#334155;">Daftar Barang yang Dipesan</span>
                    </div>
                    <button type="button" onclick="addItem()" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.4rem 0.9rem; background:#4f46e5; color:white; border:none; border-radius:7px; font-size:0.8rem; font-weight:600; cursor:pointer;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah Item
                    </button>
                </div>

                {{-- Header Row --}}
                <div style="display:grid; grid-template-columns:3fr 130px 180px 160px 45px; gap:1.25rem; padding:0 1rem 0.75rem; border-bottom:2px solid #e2e8f0; margin-bottom:1.25rem; align-items:center;">
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Produk</span>
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; text-align:center;">Qty & Satuan</span>
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; text-align:right;">Harga Beli (Rp)</span>
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; text-align:right;">Subtotal</span>
                    <span></span>
                </div>

                <div id="items-container"></div>

                {{-- Total --}}
                <div style="display:flex; justify-content:flex-end; margin-top:1rem; padding-top:1rem; border-top:1px solid #f1f5f9;">
                    <div style="text-align:right;">
                        <div style="font-size:0.8rem; color:#64748b; margin-bottom:0.2rem;">Total Pembelian</div>
                        <div style="font-size:1.5rem; font-weight:800; color:#0f172a; font-family:monospace;" id="grand-total">Rp 0</div>
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <a href="{{ route('pembelian.order') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.35rem;"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan sebagai Draft
                </button>
            </div>
        </form>
    </div>

    <script>
    const searchUrl = "{{ route('pembelian.order.products.search') }}";
    let itemIndex = 0;
    const searchTimers = {};
    const searchCache = new Map();
    const productByIdCache = new Map();
    const searchControllers = {};

    // Toggle due_date by payment term and suggest +30 days from order_date
    (function(){
        const term = document.querySelector('select[name="payment_term"]');
        const due  = document.querySelector('input[name="due_date"]');
        const order= document.querySelector('input[name="order_date"]');
        function recomputeDefaultDue(){
            if (!order || !due) return;
            const val = order.value;
            if (!val) return;
            const d = new Date(val);
            d.setDate(d.getDate() + 30);
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth()+1).padStart(2,'0');
            const dd = String(d.getDate()).padStart(2,'0');
            if (!due.value) due.value = `${yyyy}-${mm}-${dd}`;
        }
        function toggleDue(){
            if (!term || !due) return;
            const isCredit = term.value === 'credit';
            due.disabled = !isCredit;
            due.parentElement.style.opacity = isCredit ? '1' : '.6';
            if (isCredit) recomputeDefaultDue(); else due.value = '';
        }
        if (term && due) {
            term.addEventListener('change', toggleDue);
            if (order) order.addEventListener('change', function(){ if(term.value==='credit') { due.value=''; toggleDue(); } });
            toggleDue();
        }
    })();

    // Prefill items from query params: ?add[]=ID&qty[]=N
    document.addEventListener('DOMContentLoaded', () => {
        const sp = new URLSearchParams(window.location.search);
        const adds = sp.getAll('add[]').length ? sp.getAll('add[]') : (sp.getAll('add') || []);
        const qtys = sp.getAll('qty[]').length ? sp.getAll('qty[]') : (sp.getAll('qty') || []);

        // Prefill payment_term & due_date if provided
        const term = sp.get('payment_term');
        const due  = sp.get('due_date');
        if (term) { const sel = document.querySelector('select[name="payment_term"]'); if (sel) { sel.value = term; sel.dispatchEvent(new Event('change')); } }
        if (due)  { const inp = document.querySelector('input[name="due_date"]'); if (inp) { inp.value = due; } }

        if (adds.length) {
            adds.forEach(async (pid, i) => {
                const productId = parseInt(pid, 10);
                const qty = parseInt(qtys[i] || '1', 10) || 1;
                addItem();
                const idx = itemIndex - 1;
                const select = document.querySelector(`select[name="items[${idx}][product_id]"]`);
                if (select) {
                    const found = await fetchProducts({ id: productId });
                    const product = found && found[0] ? found[0] : null;
                    if (product) {
                        setSelectOptions(select, [product], productId);
                        select.value = String(productId);
                        onProductChange(select, idx);
                    }
                }
                const qtyInput = document.querySelector(`#item-row-${idx} .qty-input`);
                if (qtyInput) {
                    qtyInput.value = qty;
                    calcSubtotal(idx);
                }
            });
            calcTotal();
        } else {
            addItem();
        }
    });

    async function fetchProducts(params, signal) {
        const id = params && params.id ? parseInt(params.id, 10) : 0;
        const q = params && params.q ? String(params.q) : '';
        if (id > 0 && productByIdCache.has(id)) {
            return [productByIdCache.get(id)];
        }
        if (!id && q && searchCache.has(q)) {
            return searchCache.get(q);
        }
        const url = new URL(searchUrl, window.location.origin);
        Object.entries(params).forEach(([k, v]) => {
            if (v === undefined || v === null || v === '') return;
            url.searchParams.set(k, String(v));
        });
        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' }, signal });
        if (!res.ok) return [];
        try {
            const data = await res.json();
            const arr = Array.isArray(data) ? data : [];
            if (id > 0 && arr[0]) {
                productByIdCache.set(id, arr[0]);
            } else if (!id && q) {
                searchCache.set(q, arr);
                if (searchCache.size > 50) searchCache.clear();
            }
            return arr;
        } catch (_) {
            return [];
        }
    }



    function addItem() {
        const container = document.getElementById('items-container');
        const idx = itemIndex++;

        const row = document.createElement('div');
        row.className = 'item-row';
        row.id = `item-row-${idx}`;
        row.style.cssText = 'display:grid; grid-template-columns:3fr 130px 180px 160px 45px; gap:1.25rem; align-items:center; margin-bottom:1rem; padding:1.25rem 1rem; border-radius:10px; background:#f8fafc; border:1px solid #e2e8f0; box-shadow:0 1px 2px rgba(0,0,0,0.02); transition:all 0.2s;';
        row.onmouseover = function() { this.style.borderColor = '#cbd5e1'; this.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.05)'; };
        row.onmouseout = function() { this.style.borderColor = '#e2e8f0'; this.style.boxShadow = '0 1px 2px rgba(0,0,0,0.02)'; };
        row.innerHTML = `
            <div style="position:relative;">
                <div class="product-autocomplete" id="product-autocomplete-${idx}" style="position:relative;">
                    <input type="text" class="form-input product-search-input" placeholder="Ketik nama / SKU produk..." oninput="onProductSearch(this, ${idx})" onkeydown="onProductSearchKeydown(event, ${idx})" onblur="onProductSearchBlur(${idx})" style="font-size:0.9rem;padding:0.6rem;border-radius:6px;" autocomplete="off">
                    <input type="hidden" name="items[${idx}][product_id]" id="product-id-${idx}">
                    <div id="search-status-${idx}" style="font-size:0.72rem;color:#94a3b8;margin:0.25rem 0 0 0;"></div>
                    <div id="search-results-${idx}" class="autocomplete-results" style="display:none;position:absolute;top:100%;left:0;right:0;background:white;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.1);z-index:100;max-height:250px;overflow-y:auto;margin-top:4px;"></div>
                </div>
                <div id="selected-product-${idx}" class="selected-product-display" style="display:none;margin-top:0.5rem;padding:0.5rem;background:#f0fdf4;border:1px solid #86efac;border-radius:6px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="selected-product-name-${idx}" style="font-weight:600;color:#166534;font-size:0.85rem;"></span>
                        <button type="button" onclick="clearProductSelection(${idx})" style="background:none;border:none;color:#22c55e;cursor:pointer;font-size:0.75rem;padding:0.2rem 0.5rem;">✕ Ganti</button>
                    </div>
                </div>
            </div>
            <div style="display:flex; flex-direction:column; gap:0.4rem;">
                <input type="number" name="items[${idx}][qty_ordered]" class="form-input qty-input" min="1" value="1" oninput="calcSubtotal(${idx})" required style="text-align:center; font-size:1rem; font-weight:700; padding:0.5rem; border-radius:6px;">
                <select name="items[${idx}][unit_id]" id="unit-select-${idx}" class="form-input" onchange="onUnitChange(this, ${idx})" style="font-size:0.75rem; color:#475569; padding:0.25rem 0.5rem; border-radius:4px; border:1px solid #cbd5e1; background:#f1f5f9; display:none; cursor:pointer; text-align:center;"></select>
                <input type="hidden" name="items[${idx}][conversion_factor]" id="conversion-factor-${idx}" value="1">
            </div>
            <div>
                <div style="position:relative; display:flex; align-items:center;">
                    <span style="position:absolute; left:0.75rem; font-size:0.85rem; font-weight:700; color:#94a3b8;">Rp</span>
                    <input type="number" name="items[${idx}][unit_price]" id="price-${idx}" class="form-input price-input" min="0" value="0" oninput="calcSubtotal(${idx})" required style="width:100%; padding:0.6rem 0.6rem 0.6rem 2.25rem; text-align:right; font-size:0.95rem; font-weight:600; border-radius:6px;">
                </div>
            </div>
            <div style="text-align:right; font-weight:800; color:#0f172a; font-family:'Menlo', 'Consolas', monospace; font-size:1.1rem; letter-spacing:-0.5px;" id="subtotal-${idx}">Rp 0</div>
            <div style="display:flex; justify-content:flex-end;">
                <button type="button" onclick="removeItem(${idx})" title="Hapus" style="width:38px; height:38px; background:#fef2f2; color:#ef4444; border:1px solid #fecaca; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        `;
        container.appendChild(row);
    }

    function onProductSearchKeydown(e, idx) {
        if (!e) return;
        const resultsContainer = document.getElementById(`search-results-${idx}`);
        if (!resultsContainer) return;
        const items = resultsContainer.querySelectorAll('.autocomplete-item');
        let activeIdx = -1;
        items.forEach((item, i) => { if (item.classList.contains('active')) activeIdx = i; });

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (activeIdx < items.length - 1) {
                if (activeIdx >= 0) items[activeIdx].classList.remove('active');
                items[activeIdx + 1].classList.add('active');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (activeIdx > 0) {
                items[activeIdx].classList.remove('active');
                items[activeIdx - 1].classList.add('active');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIdx >= 0 && items[activeIdx]) {
                items[activeIdx].click();
            } else if (items.length === 1) {
                items[0].click();
            }
        } else if (e.key === 'Escape') {
            hideSearchResults(idx);
        }
    }

    function onProductSearchBlur(idx) {
        setTimeout(() => hideSearchResults(idx), 200);
    }

    function hideSearchResults(idx) {
        const resultsContainer = document.getElementById(`search-results-${idx}`);
        if (resultsContainer) resultsContainer.style.display = 'none';
    }

    function showSearchResults(idx, products) {
        const resultsContainer = document.getElementById(`search-results-${idx}`);
        const status = document.getElementById(`search-status-${idx}`);
        if (!resultsContainer) return;

        resultsContainer.innerHTML = '';

        if (products.length === 0) {
            resultsContainer.innerHTML = '<div style="padding:1rem;text-align:center;color:#94a3b8;font-size:0.85rem;">Tidak ada hasil</div>';
            resultsContainer.style.display = 'block';
            if (status) status.textContent = '';
            return;
        }

        products.forEach((p, i) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.style.cssText = 'padding:0.75rem 1rem;cursor:pointer;border-bottom:1px solid #f1f5f9;transition:all 0.15s;';
            item.innerHTML = `
                <div style="font-weight:600;color:#1e293b;font-size:0.9rem;">${p.name}</div>
                <div style="font-size:0.75rem;color:#64748b;">SKU: ${p.sku || '-'} | Stok: ${p.stock || 0}</div>
            `;
            item.onmouseenter = () => {
                resultsContainer.querySelectorAll('.autocomplete-item').forEach(el => el.classList.remove('active'));
                item.classList.add('active');
                item.style.background = '#f8fafc';
            };
            item.onmouseleave = () => {
                item.classList.remove('active');
                item.style.background = 'transparent';
            };
            item.onclick = () => selectProduct(idx, p);
            resultsContainer.appendChild(item);
        });

        resultsContainer.style.display = 'block';
        if (status) status.textContent = `${products.length} hasil ditemukan`;
    }

    function selectProduct(idx, product) {
        const productIdInput = document.getElementById(`product-id-${idx}`);
        const searchInput = document.querySelector(`#product-autocomplete-${idx} .product-search-input`);
        const selectedDisplay = document.getElementById(`selected-product-${idx}`);
        const selectedName = document.getElementById(`selected-product-name-${idx}`);
        const status = document.getElementById(`search-status-${idx}`);

        if (isDuplicateProduct(idx, product.id)) {
            if (status) status.textContent = 'Produk sudah dipilih di baris lain.';
            return;
        }

        productIdInput.value = product.id;
        if (searchInput) searchInput.value = '';
        if (selectedName) selectedName.textContent = `${product.name} (${product.sku || '-'})`;
        if (selectedDisplay) selectedDisplay.style.display = 'block';
        hideSearchResults(idx);
        if (status) status.textContent = '';

        onProductChange(product, idx);
    }

    function clearProductSelection(idx) {
        const productIdInput = document.getElementById(`product-id-${idx}`);
        const searchInput = document.querySelector(`#product-autocomplete-${idx} .product-search-input`);
        const selectedDisplay = document.getElementById(`selected-product-${idx}`);
        const unitSelect = document.getElementById(`unit-select-${idx}`);
        const priceInput = document.getElementById(`price-${idx}`);
        const factorInput = document.getElementById(`conversion-factor-${idx}`);

        productIdInput.value = '';
        if (searchInput) searchInput.value = '';
        if (selectedDisplay) selectedDisplay.style.display = 'none';
        if (unitSelect) { unitSelect.style.display = 'none'; unitSelect.innerHTML = ''; }
        if (priceInput) priceInput.value = 0;
        if (factorInput) factorInput.value = 1;
        calcSubtotal(idx);
    }

    function onProductSearch(input, idx) {
        if (searchTimers[idx]) {
            clearTimeout(searchTimers[idx]);
        }
        searchTimers[idx] = setTimeout(async () => {
            const q = (input?.value || '').trim();
            const status = document.getElementById(`search-status-${idx}`);

            if (q.length < 2) {
                hideSearchResults(idx);
                if (status) status.textContent = q.length ? 'Minimal 2 karakter.' : '';
                return;
            }

            if (searchControllers[idx]) {
                try { searchControllers[idx].abort(); } catch (_) { /* ignore */ }
            }

            const controller = new AbortController();
            searchControllers[idx] = controller;
            if (status) status.textContent = 'Mencari...';

            try {
                const results = await fetchProducts({ q }, controller.signal);
                showSearchResults(idx, results);
            } catch (_) {
                if (status) status.textContent = 'Gagal memuat hasil.';
            }
        }, 250);
    }

    function isDuplicateProduct(idx, productId) {
        const inputs = document.querySelectorAll('input[name^="items["][name$="[product_id]"]');
        for (const el of inputs) {
            const name = el.getAttribute('name') || '';
            if (name.includes(`items[${idx}]`)) continue;
            if (String(el.value) === String(productId)) return true;
        }
        return false;
    }

    function onProductChange(product, idx) {
        const unitSelect = document.getElementById(`unit-select-${idx}`);
        const priceInput = document.getElementById(`price-${idx}`);
        const factorInput = document.getElementById(`conversion-factor-${idx}`);

        if (!product) return;

        const conversions = Array.isArray(product.conversions) ? product.conversions : [];
        unitSelect.style.display = 'block';
        unitSelect.innerHTML = '';

        if (conversions.length) {
            conversions.forEach((c) => {
                const opt = document.createElement('option');
                opt.value = c.unit_id ?? '';
                opt.textContent = `${c.name}${c.factor > 1 ? ' (x' + c.factor + ')' : ''}`;
                opt.dataset.factor = String(c.factor || 1);
                opt.dataset.price = String(c.price || 0);
                unitSelect.appendChild(opt);
            });
        } else {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = product.unit_name || 'Pcs';
            opt.dataset.factor = '1';
            opt.dataset.price = String(product.purchase_price || 0);
            unitSelect.appendChild(opt);
        }

        onUnitChange(unitSelect, idx);
    }

    function onUnitChange(select, idx) {
        const opt = select.options[select.selectedIndex];
        if (opt) {
            const price = parseFloat(opt.dataset.price) || 0;
            const factor = parseInt(opt.dataset.factor) || 1;
            document.getElementById(`price-${idx}`).value = price;
            document.getElementById(`conversion-factor-${idx}`).value = factor;
        }
        calcSubtotal(idx);
    }

    function calcSubtotal(idx) {
        const row = document.getElementById(`item-row-${idx}`);
        if (!row) return;
        const qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const sub   = qty * price;
        document.getElementById(`subtotal-${idx}`).textContent = 'Rp ' + sub.toLocaleString('id-ID');
        calcTotal();
    }

    function calcTotal() {
        let total = 0;
        document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
            const val = el.textContent.replace(/[^\d]/g, '');
            total += parseInt(val) || 0;
        });
        document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function removeItem(idx) {
        document.getElementById(`item-row-${idx}`)?.remove();
        calcTotal();
    }

    // Add first item on load handled in prefill block
    </script>
</x-app-layout>
