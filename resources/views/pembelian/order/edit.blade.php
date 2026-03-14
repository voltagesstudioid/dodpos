<x-app-layout>
    <x-slot name="header">Edit Purchase Order</x-slot>

    <div class="page-container" style="max-width:1150px;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:800; color:#0f172a; margin:0; display:flex; align-items:center; gap:0.5rem;">
                    <span style="background:#fef3c7; padding:0.35rem 0.5rem; border-radius:8px;">✏️</span>
                    Edit Purchase Order
                </h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.3rem 0 0;">Edit PO <strong style="font-family:monospace; color:#4f46e5;">{{ $order->po_number }}</strong> (hanya bisa diedit selama status Draft)</p>
            </div>
            <a href="{{ route('pembelian.order.show', $order) }}" class="btn-secondary" style="font-size:0.875rem;">← Detail PO</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1rem;">
                <strong>Kesalahan:</strong>
                <ul style="margin:0.5rem 0 0; padding-left:1.25rem;">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('pembelian.order.update', $order) }}" method="POST" id="po-form">
            @csrf
            @method('PUT')

            {{-- Header PO --}}
            <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <span style="width:24px; height:24px; background:#f59e0b; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;">1</span>
                    <span style="font-weight:700; color:#334155;">Info Pesanan</span>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">No. PO</label>
                        <input type="text" value="{{ $order->po_number }}" class="form-input" disabled style="font-family:monospace; font-weight:700; background:#f1f5f9; color:#94a3b8;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supplier <span style="color:#ef4444;">*</span></label>
                        <select name="supplier_id" class="form-input @error('supplier_id') input-error @enderror" required>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id', $order->supplier_id)==$s->id ? 'selected':'' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pesanan <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="order_date" value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estimasi Tiba</label>
                        <input type="date" name="expected_date" value="{{ old('expected_date', $order->expected_date?->format('Y-m-d')) }}" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran <span class="required">*</span></label>
                        <select name="payment_term" class="form-input" required>
                            <option value="cash" {{ old('payment_term', $order->payment_term)=='cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="credit" {{ old('payment_term', $order->payment_term ?? 'credit')=='credit' ? 'selected' : '' }}>Kredit / Hutang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jatuh Tempo <span style="color:#64748b; font-weight:400;">(Opsional, untuk Kredit)</span></label>
                        <input type="date" name="due_date" value="{{ old('due_date', $order->due_date?->format('Y-m-d')) }}" class="form-input">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="2" class="form-input">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>

            {{-- Items --}}
            <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <span style="width:24px; height:24px; background:#f59e0b; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;">2</span>
                        <span style="font-weight:700; color:#334155;">Daftar Barang</span>
                    </div>
                    <button type="button" onclick="addItem()" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.4rem 0.9rem; background:#4f46e5; color:white; border:none; border-radius:7px; font-size:0.8rem; font-weight:600; cursor:pointer;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah Item
                    </button>
                </div>

                <div style="display:grid; grid-template-columns:3fr 130px 180px 160px 45px; gap:1.25rem; padding:0 1rem 0.75rem; border-bottom:2px solid #e2e8f0; margin-bottom:1.25rem; align-items:center;">
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Produk</span>
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; text-align:center;">Qty & Satuan</span>
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; text-align:right;">Harga Beli (Rp)</span>
                    <span style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; text-align:right;">Subtotal</span>
                    <span></span>
                </div>

                <div id="items-container"></div>

                <div style="display:flex; justify-content:flex-end; margin-top:1rem; padding-top:1rem; border-top:1px solid #f1f5f9;">
                    <div style="text-align:right;">
                        <div style="font-size:0.8rem; color:#64748b; margin-bottom:0.2rem;">Total Pembelian</div>
                        <div style="font-size:1.5rem; font-weight:800; color:#0f172a; font-family:monospace;" id="grand-total">Rp 0</div>
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <a href="{{ route('pembelian.order.show', $order) }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary" style="background:#f59e0b; border-color:#f59e0b;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.35rem;"><polyline points="20 6 9 17 4 12"/></svg>
                    Perbarui Purchase Order
                </button>
            </div>
        </form>
    </div>

    <script id="po-edit-items" type="application/json">{!! json_encode($order->items->map(function($i) { return ['product_id' => $i->product_id, 'qty_ordered' => $i->qty_ordered, 'unit_price' => $i->unit_price, 'unit_id' => $i->unit_id, 'conversion_factor' => $i->conversion_factor]; })->values(), JSON_UNESCAPED_UNICODE) !!}</script>
    <script>
    const searchUrl = "{{ route('pembelian.order.products.search') }}";
    const searchTimers = {};
    const searchCache = new Map();
    const productByIdCache = new Map();
    const searchControllers = {};
    const existingItems = (function(){
        const el = document.getElementById('po-edit-items');
        if (!el) return [];
        try { return JSON.parse(el.textContent || '[]'); } catch (_) { return []; }
    })();

    // Toggle due_date by payment term
    (function(){
        const term = document.querySelector('select[name="payment_term"]');
        const due  = document.querySelector('input[name="due_date"]');
        const order= document.querySelector('input[name="order_date"]');
        function toggleDue(){
            if (!term || !due) return;
            const isCredit = term.value === 'credit';
            due.disabled = !isCredit;
            due.parentElement.style.opacity = isCredit ? '1' : '.6';
        }
        if (term && due) {
            term.addEventListener('change', toggleDue);
            if (order) order.addEventListener('change', toggleDue);
            toggleDue();
        }
    })();
    let itemIndex = 0;

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

    function setSelectOptions(select, products, keepSelectedId = 0) {
        if (!select) return;
        const selectedId = keepSelectedId || parseInt(select.value || '0', 10) || 0;
        select.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = '-- Cari & Pilih Produk --';
        select.appendChild(placeholder);

        products.forEach((p) => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = `${p.name} (${p.sku || '-'})`;
            opt.dataset.product = JSON.stringify(p);
            if (selectedId && parseInt(p.id || '0', 10) === selectedId) {
                opt.selected = true;
            }
            select.appendChild(opt);
        });

        if (products.length === 1) {
            select.value = String(products[0].id);
        }
    }

    async function ensureSelectedProductData(select) {
        if (!select) return null;
        const productId = parseInt(select.value || '0', 10);
        if (!productId) return null;
        const opt = select.options[select.selectedIndex];
        if (opt && opt.dataset.product) {
            try { return JSON.parse(opt.dataset.product); } catch (_) { /* ignore */ }
        }
        const found = await fetchProducts({ id: productId });
        const product = found && found[0] ? found[0] : null;
        if (!product) return null;
        setSelectOptions(select, [product], productId);
        return product;
    }

    function onProductSearch(input, idx) {
        if (searchTimers[idx]) {
            clearTimeout(searchTimers[idx]);
        }
        searchTimers[idx] = setTimeout(async () => {
            const q = (input?.value || '').trim();
            const select = document.querySelector(`select[name="items[${idx}][product_id]"]`);
            const status = document.getElementById(`search-status-${idx}`);
            if (!select) return;
            if (q.length < 2) {
                if (status) status.textContent = q.length ? 'Minimal 2 karakter.' : '';
                return;
            }
            if (searchControllers[idx]) {
                try { searchControllers[idx].abort(); } catch (_) { /* ignore */ }
            }
            const controller = new AbortController();
            searchControllers[idx] = controller;
            if (status) status.textContent = 'Memuat...';
            select.disabled = true;
            const selectedId = parseInt(select.value || '0', 10) || 0;
            try {
                const results = await fetchProducts({ q }, controller.signal);
                setSelectOptions(select, results, selectedId);
                if (results.length === 1) {
                    onProductChange(select, idx);
                }
                if (status) status.textContent = results.length ? `${results.length} hasil.` : 'Tidak ada hasil.';
            } catch (_) {
                if (status) status.textContent = 'Gagal memuat hasil.';
            } finally {
                select.disabled = false;
            }
        }, 250);
    }

    function onProductSearchKeydown(e, idx) {
        if (!e || e.key !== 'Enter') return;
        e.preventDefault();
        const select = document.querySelector(`select[name="items[${idx}][product_id]"]`);
        if (!select) return;
        if (!select.value) {
            const first = select.options && select.options.length > 1 ? select.options[1] : null;
            if (first) {
                select.value = first.value;
            }
        }
        if (select.value) {
            onProductChange(select, idx);
        }
    }

    function addItem(item = null) {
        const container = document.getElementById('items-container');
        const idx = itemIndex++;
        const productId = item?.product_id ? parseInt(item.product_id, 10) : 0;
        const qty = item?.qty_ordered ? parseInt(item.qty_ordered, 10) : 1;
        const price = item?.unit_price ? parseFloat(item.unit_price) : 0;
        const unitId = item?.unit_id ? parseInt(item.unit_id, 10) : 0;
        const conversionFactor = item?.conversion_factor ? parseInt(item.conversion_factor, 10) : 1;

        const row = document.createElement('div');
        row.id = `item-row-${idx}`;
        row.style.cssText = 'display:grid; grid-template-columns:3fr 130px 180px 160px 45px; gap:1.25rem; align-items:center; margin-bottom:1rem; padding:1.25rem 1rem; border-radius:10px; background:#f8fafc; border:1px solid #e2e8f0; box-shadow:0 1px 2px rgba(0,0,0,0.02); transition:all 0.2s;';
        row.onmouseover = function() { this.style.borderColor = '#cbd5e1'; this.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.05)'; };
        row.onmouseout = function() { this.style.borderColor = '#e2e8f0'; this.style.boxShadow = '0 1px 2px rgba(0,0,0,0.02)'; };
        row.innerHTML = `
            <div>
                <input type="text" class="form-input" placeholder="Ketik nama / SKU..." oninput="onProductSearch(this, ${idx})" onkeydown="onProductSearchKeydown(event, ${idx})" style="font-size:0.85rem;padding:0.5rem;border-radius:6px;margin-bottom:0.4rem;">
                <div id="search-status-${idx}" style="font-size:0.72rem;color:#94a3b8;margin:-0.25rem 0 0.35rem 0;"></div>
                <select name="items[${idx}][product_id]" class="form-input" onchange="onProductChange(this, ${idx})" required style="font-size:0.9rem; padding:0.6rem; border-radius:6px; cursor:pointer;">
                    <option value="">-- Cari & Pilih Produk --</option>
                </select>
            </div>
            <div style="display:flex; flex-direction:column; gap:0.4rem;">
                <input type="number" name="items[${idx}][qty_ordered]" class="form-input qty-input" min="1" value="${qty}" oninput="calcSubtotal(${idx})" required style="text-align:center; font-size:1rem; font-weight:700; padding:0.5rem; border-radius:6px;">
                <select name="items[${idx}][unit_id]" id="unit-select-${idx}" class="form-input" onchange="onUnitChange(this, ${idx}, false)" style="font-size:0.75rem; color:#475569; padding:0.25rem 0.5rem; border-radius:4px; border:1px solid #cbd5e1; background:#f1f5f9; display:none; cursor:pointer; text-align:center;"></select>
                <input type="hidden" name="items[${idx}][conversion_factor]" id="conversion-factor-${idx}" value="1">
            </div>
            <div>
                <div style="position:relative; display:flex; align-items:center;">
                    <span style="position:absolute; left:0.75rem; font-size:0.85rem; font-weight:700; color:#94a3b8;">Rp</span>
                    <input type="number" name="items[${idx}][unit_price]" id="price-${idx}" class="form-input price-input" min="0" value="${price}" oninput="calcSubtotal(${idx})" required style="width:100%; padding:0.6rem 0.6rem 0.6rem 2.25rem; text-align:right; font-size:0.95rem; font-weight:600; border-radius:6px;">
                </div>
            </div>
            <div style="text-align:right; font-weight:800; color:#0f172a; font-family:'Menlo', 'Consolas', monospace; font-size:1.1rem; letter-spacing:-0.5px;" id="subtotal-${idx}">Rp ${(qty*price).toLocaleString('id-ID')}</div>
            <div style="display:flex; justify-content:flex-end;">
                <button type="button" onclick="removeItem(${idx})" title="Hapus" style="width:38px; height:38px; background:#fef2f2; color:#ef4444; border:1px solid #fecaca; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        `;
        container.appendChild(row);

        const select = document.querySelector(`select[name="items[${idx}][product_id]"]`);
        if (select && productId) {
            fetchProducts({ id: productId }).then((found) => {
                const product = found && found[0] ? found[0] : null;
                if (!product) return;
                setSelectOptions(select, [product], productId);
                select.value = String(productId);
                populateUnitSelectFromProduct(idx, product, unitId, conversionFactor, price);
            });
        }

        calcTotal();
    }

    function populateUnitSelectFromProduct(idx, product, selectedUnitId = 0, overrideFactor = 1, overridePrice = null) {
        const unitSelect = document.getElementById(`unit-select-${idx}`);
        const factorInput = document.getElementById(`conversion-factor-${idx}`);
        const priceInput = document.getElementById(`price-${idx}`);
        if (!unitSelect || !factorInput || !priceInput) return;

        const conversions = Array.isArray(product?.conversions) ? product.conversions : [];
        unitSelect.style.display = 'block';
        unitSelect.innerHTML = '';

        if (conversions.length) {
            conversions.forEach((c) => {
                const opt = document.createElement('option');
                opt.value = c.unit_id ?? '';
                opt.textContent = `${c.name}${c.factor > 1 ? ' (x' + c.factor + ')' : ''}`;
                opt.dataset.factor = String(c.factor || 1);
                opt.dataset.price = String(c.price || 0);
                if (selectedUnitId && parseInt(c.unit_id || '0', 10) === selectedUnitId) {
                    opt.selected = true;
                }
                unitSelect.appendChild(opt);
            });
        } else {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = product?.unit_name || 'Pcs';
            opt.dataset.factor = '1';
            opt.dataset.price = String(product?.purchase_price || 0);
            unitSelect.appendChild(opt);
        }

        onUnitChange(unitSelect, idx, true);
        factorInput.value = overrideFactor || factorInput.value || 1;
        if (overridePrice !== null) {
            priceInput.value = overridePrice;
        }
        calcSubtotal(idx);
    }

    function isDuplicateProduct(idx, productId) {
        const selects = document.querySelectorAll('select[name^="items["][name$="[product_id]"]');
        for (const el of selects) {
            const name = el.getAttribute('name') || '';
            if (name.includes(`items[${idx}]`)) continue;
            if (String(el.value) === String(productId)) return true;
        }
        return false;
    }

    function onProductChange(select, idx) {
        const productId = select.value;
        const unitSelect = document.getElementById(`unit-select-${idx}`);
        const priceInput = document.getElementById(`price-${idx}`);
        const factorInput = document.getElementById(`conversion-factor-${idx}`);
        const status = document.getElementById(`search-status-${idx}`);
        
        if (!productId) {
            unitSelect.style.display = 'none';
            unitSelect.innerHTML = '';
            priceInput.value = 0;
            factorInput.value = 1;
            if (status) status.textContent = '';
            calcSubtotal(idx);
            return;
        }
        if (isDuplicateProduct(idx, productId)) {
            if (status) status.textContent = 'Produk sudah dipilih di baris lain.';
            select.value = '';
            unitSelect.style.display = 'none';
            unitSelect.innerHTML = '';
            priceInput.value = 0;
            factorInput.value = 1;
            calcSubtotal(idx);
            return;
        }

        ensureSelectedProductData(select).then((product) => {
            if (!product) return;
            populateUnitSelectFromProduct(idx, product);
        });
    }

    function onUnitChange(select, idx, skipPriceUpdate = false) {
        const opt = select.options[select.selectedIndex];
        if (opt) {
            const price = parseFloat(opt.dataset.price) || 0;
            const factor = parseInt(opt.dataset.factor) || 1;
            if(!skipPriceUpdate) {
                document.getElementById(`price-${idx}`).value = price;
            }
            document.getElementById(`conversion-factor-${idx}`).value = factor;
        }
        calcSubtotal(idx);
    }

    function calcSubtotal(idx) {
        const row = document.getElementById(`item-row-${idx}`);
        if (!row) return;
        const qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        document.getElementById(`subtotal-${idx}`).textContent = 'Rp ' + (qty*price).toLocaleString('id-ID');
        calcTotal();
    }

    function calcTotal() {
        let total = 0;
        document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
            total += parseInt(el.textContent.replace(/[^\d]/g, '')) || 0;
        });
        document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function removeItem(idx) {
        document.getElementById(`item-row-${idx}`)?.remove();
        calcTotal();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (existingItems && existingItems.length) {
            existingItems.forEach((it) => addItem(it));
        } else {
            addItem();
        }
    });
    </script>
</x-app-layout>
