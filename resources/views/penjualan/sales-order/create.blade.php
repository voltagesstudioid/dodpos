<x-app-layout>
    <x-slot name="header">Buat Sales Order Baru</x-slot>

    <style>
        .so-modal { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); display: none; align-items: center; justify-content: center; padding: 1rem; z-index: 1200; }
        .so-modal.open { display: flex; }
        .so-modal-card { width: min(840px, 100%); background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.35); }
        .so-modal-head { padding: 1rem 1.1rem; border-bottom: 1px solid #f1f5f9; background: linear-gradient(180deg, #fdfdfe, #f8fafc); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .so-modal-title { font-weight: 900; color: #0f172a; }
        .so-modal-body { padding: 1rem 1.1rem; }
        .so-results { margin-top: 0.75rem; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; max-height: 340px; overflow-y: auto; background: #fff; }
        .so-result { width: 100%; text-align: left; padding: 0.85rem 0.95rem; border: 0; background: #fff; display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; cursor: pointer; }
        .so-result + .so-result { border-top: 1px solid #f1f5f9; }
        .so-result:hover { background: #f8fafc; }
        .so-result-name { font-weight: 900; color: #0f172a; font-size: 0.9rem; line-height: 1.25; }
        .so-result-sub { color: #64748b; font-size: 0.8rem; margin-top: 0.2rem; }
        .so-result-price { font-weight: 900; color: #4338ca; font-size: 0.85rem; }
        .so-inline-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
    </style>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Buat Sales Order Baru</div>
                <div class="page-header-subtitle">Pilih pelanggan, tambahkan barang, lalu simpan</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('sales-order.index') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="soForm" class="btn-primary">💾 Simpan</button>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div>❌ Periksa input Anda:</div>
                <div style="margin-top:0.35rem;">
                    <ul style="margin:0;padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div>
        @endif

        <form action="{{ route('sales-order.store') }}" method="POST" id="soForm">
            @csrf

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Informasi Order</div>
                        <div class="panel-subtitle">Pelanggan, tanggal, status, dan catatan</div>
                    </div>
                    <span class="badge badge-gray">Status: Baru</span>
                </div>
                <div class="panel-body">
                    <div class="form-row-3">
                        <div>
                            <label for="customer_id" class="form-label">Pelanggan <span class="required">*</span></label>
                            <select name="customer_id" id="customer_id" class="form-input" required>
                                <option value="">Pilih Pelanggan...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} {{ $customer->phone ? ' - '.$customer->phone : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id') <div class="form-error">⚠ {{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="order_date" class="form-label">Tanggal Order <span class="required">*</span></label>
                            <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="form-input" required>
                            @error('order_date') <div class="form-error">⚠ {{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="delivery_date" class="form-label">Tanggal Kirim</label>
                            <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date') }}" class="form-input">
                            @error('delivery_date') <div class="form-error">⚠ {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-row" style="margin-top: 1rem;">
                        <div class="form-group">
                            <label for="status" class="form-label">Status Awal <span class="required">*</span></label>
                            @php $st = old('status', 'draft'); @endphp
                            <select name="status" id="status" class="form-input" required>
                                <option value="draft" {{ $st == 'draft' ? 'selected' : '' }}>Draft (Disimpan sementara)</option>
                                <option value="confirmed" {{ $st == 'confirmed' ? 'selected' : '' }}>Confirmed (Order disetujui)</option>
                                <option value="processing" {{ $st == 'processing' ? 'selected' : '' }}>Processing (Sedang diproses/disiapkan)</option>
                            </select>
                            @error('status') <div class="form-error">⚠ {{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" id="notes" rows="2" class="form-input" placeholder="Contoh: Kirim sebelum jam 10 pagi">{{ old('notes') }}</textarea>
                            @error('notes') <div class="form-error">⚠ {{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top: 1rem;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Daftar Barang</div>
                        <div class="panel-subtitle">Tambahkan minimal 1 barang</div>
                    </div>
                    <div class="so-inline-actions">
                        <button type="button" class="btn-secondary" onclick="window.openProductModal()">➕ Tambah Barang</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-wrapper">
                        <table class="data-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:48px;">#</th>
                                    <th>Nama Barang</th>
                                    <th style="width:160px;">Harga (Rp)</th>
                                    <th style="width:110px;">Qty</th>
                                    <th style="width:160px;">Subtotal</th>
                                    <th style="width:110px;text-align:right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="emptyRow">
                                    <td colspan="6" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">🧾</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada barang</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                                Klik tombol “Tambah Barang” untuk memasukkan item Sales Order.
                                            </div>
                                            <button type="button" class="btn-primary" onclick="window.openProductModal()">➕ Tambah Barang</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align:right;font-weight:900;">TOTAL KESELURUHAN:</td>
                                    <td style="font-weight:900;color:#4338ca;">
                                        Rp <span id="grandTotalLabel">0</span>
                                        <div style="font-size:0.8rem;color:#64748b;font-weight:600;margin-top:2px;" id="itemsSummaryLabel">0 item • Qty 0</div>
                                        <input type="hidden" name="total_amount" id="total_amount" value="0">
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @error('items') <div class="form-error" style="margin-top:0.5rem;">⚠ {{ $message }}</div> @enderror
                    

                    <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;margin-top:1rem;">
                        <a href="{{ route('sales-order.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary" id="btnSubmit">💾 Simpan Sales Order</button>
                    </div>
            </div>
        </form>
    </div>

    <div id="productModal" class="so-modal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="so-modal-card">
            <div class="so-modal-head">
                <div>
                    <div class="so-modal-title">Cari Barang</div>
                    <div style="color:#64748b;font-size:0.85rem;line-height:1.5;">Ketik minimal 2 karakter (nama / SKU / barcode)</div>
                </div>
                <button type="button" class="btn-secondary" onclick="closeProductModal()">✕ Tutup</button>
            </div>
            <div class="so-modal-body">
                <div class="form-row" style="grid-template-columns: 1fr auto;">
                    <div>
                        <label class="form-label">Kata Kunci</label>
                        <input type="text" id="searchInput" class="form-input" placeholder="Contoh: Indomie / SKU / barcode" autocomplete="off">
                    </div>
                    <div style="display:flex;align-items:flex-end;">
                        <button type="button" class="btn-secondary" onclick="clearSearch()">↺ Reset</button>
                    </div>
                </div>

                <div class="so-results" id="searchResults">
                    <div style="padding: 1.25rem; color:#64748b; font-size:0.875rem; text-align:center;">
                        Mulai ketik untuk mencari barang...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="application/json" id="oldItemsJson">{{ json_encode($oldItemsForJs ?? []) }}</script>

    <script>
        let orderItems = [];
        let itemsCounter = 0;
        const oldItemsEl = document.getElementById('oldItemsJson');
        let oldItems = [];
        try {
            oldItems = JSON.parse(oldItemsEl ? oldItemsEl.textContent : '[]');
        } catch (e) {
            oldItems = [];
        }

        if (Array.isArray(oldItems) && oldItems.length) {
            oldItems.forEach((it) => {
                const id = Number(it.product_id);
                const price = Number(it.price || 0);
                const qty = Number(it.quantity || 1);
                orderItems.push({
                    id: id,
                    name: String(it.name || `Barang (ID: ${id})`),
                    price: price,
                    qty: qty,
                    conversions: Array.isArray(it.conversions) ? it.conversions : [],
                    subtotal: price * qty,
                });
            });
            renderTable();
        }

        function formatCurrency(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num));
        }

        function window_openProductModal() {
            const modal = document.getElementById('productModal');
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            setTimeout(() => document.getElementById('searchInput').focus(), 50);
        }
        window.openProductModal = window_openProductModal;

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            clearSearch();
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchResults').innerHTML = '<div style="padding: 1.25rem; color:#64748b; font-size:0.875rem; text-align:center;">Mulai ketik untuk mencari barang...</div>';
        }

        let searchTimeout = null;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.trim();
            const resultsContainer = document.getElementById('searchResults');
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                resultsContainer.innerHTML = '<div style="padding: 1.25rem; color:#64748b; font-size:0.875rem; text-align:center;">Ketik minimal 2 karakter...</div>';
                return;
            }

            resultsContainer.innerHTML = '<div style="padding: 1.25rem; color:#4338ca; font-size:0.875rem; font-weight:800; text-align:center;">Mencari data...</div>';
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales-order.products.search') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        window.latestSoSearchResults = Array.isArray(data) ? data : [];
                        if(!Array.isArray(data) || data.length === 0) {
                            resultsContainer.innerHTML = '<div style="padding: 1.25rem; color:#ef4444; font-size:0.875rem; font-weight:800; text-align:center;">Barang tidak ditemukan.</div>';
                            return;
                        }

                        let html = '';
                        data.forEach(item => {
                            const safeName = String(item.name || '').replace(/'/g, "\\'");
                            const byWh = Array.isArray(item.stocks_by_warehouse) ? item.stocks_by_warehouse.slice(0,3) : [];
                            const whNote = byWh.length ? ' • ' + byWh.map(r => `${r.warehouse}: ${r.stock}`).join(' | ') : '';
                            const sub = `${item.sku || item.barcode || '-'} • Stok: ${item.stock || 0}${whNote}`;
                            let badgeText = 'Aman', badgeStyle = 'background:#dcfce7;color:#166534;', badgeTitle = 'Stok Aman';
                            if ((item.stock||0) <= 0) { badgeText = 'Habis'; badgeStyle = 'background:#fee2e2;color:#b91c1c;'; badgeTitle = 'Stok Habis'; }
                            else if ((item.min_stock||0) > 0 && (item.stock||0) <= (item.min_stock||0)) { badgeText = 'Rendah'; badgeStyle = 'background:#fef3c7;color:#b45309;'; badgeTitle = 'Stok Rendah'; }
                            html += `
                                <button type="button" class="so-result" onclick="selectProduct(${item.id}, '${safeName}', ${item.price || 0})">
                                    <div>
                                        <div class="so-result-name">${item.name || '-'}</div>
                                        <div class="so-result-sub">${sub}</div>
                                    </div>
                                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                                        <div class="so-result-price">Rp ${formatCurrency(item.price || 0)}</div>
                                        <span style="font-size:0.7rem;padding:2px 6px;border-radius:999px;${badgeStyle}" title="${badgeTitle}">${badgeText}</span>
                                    </div>
                                </button>
                            `;
                        });
                        resultsContainer.innerHTML = html;
                    })
                    .catch(err => {
                        resultsContainer.innerHTML = '<div style="padding: 1.25rem; color:#ef4444; font-size:0.875rem; font-weight:800; text-align:center;">Gagal mengambil data.</div>';
                    });
            }, 300);
        });

        window.selectProduct = function(id, name, defaultPrice) {
            // Check if already in list
            const existing = orderItems.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
                existing.subtotal = existing.qty * existing.price;
            } else {
                // get conversions from latest search data
                let convs = [];
                try {
                    const arr = window.latestSoSearchResults || [];
                    const found = arr.find(x => x.id === id);
                    convs = Array.isArray(found?.conversions) ? found.conversions : [];
                    try {
                        const pref = JSON.parse(localStorage.getItem('so_pref_unit_' + id) || 'null');
                        if (pref && Array.isArray(convs) && convs.length) {
                            convs.sort(function(a,b){
                                const af = (a.factor===pref.factor && a.label===pref.label)?-1:0;
                                const bf = (b.factor===pref.factor && b.label===pref.label)?-1:0;
                                return af - bf;
                            });
                        }
                    } catch(_) {}
                } catch (e) {}
                orderItems.push({
                    id: id,
                    name: name,
                    price: defaultPrice,
                    qty: 1,
                    conversions: convs,
                    subtotal: defaultPrice * 1
                });
            }
            
            closeProductModal();
            renderTable();
        };

        function rememberPreferredUnit(productId, factor, label){
            try { localStorage.setItem('so_pref_unit_'+productId, JSON.stringify({factor: Number(factor)||1, label: String(label||'')})); } catch(_) {}
        }
        function updateQtyWithUnit(index, factor, label){
            var multEl = document.getElementById('mult-'+index);
            var k = parseInt(multEl ? multEl.value : '1');
            if (!Number.isFinite(k) || k < 1) k = 1;
            updateQty(index, Number(factor||1) * k);
            var item = orderItems[index];
            if (item && item.id) rememberPreferredUnit(item.id, factor, label);
        }
        function onUnitChange(index){
            var unitSel = document.getElementById('unit-'+index);
            if(!unitSel) return;
            var opt = unitSel.options[unitSel.selectedIndex];
            var factor = parseInt(unitSel.value || '1');
            var label = opt ? (opt.getAttribute('data-label') || opt.textContent || '') : '';
            updateQtyWithUnit(index, factor, label);
        }

        function updateQty(index, newQty) {
            const val = parseInt(newQty);
            if (isNaN(val) || val < 1) {
                orderItems[index].qty = 1;
            } else {
                orderItems[index].qty = val;
            }
            orderItems[index].subtotal = orderItems[index].qty * orderItems[index].price;
            renderTable();
        }

        function updatePrice(index, newPrice) {
            let val = parseFloat(newPrice);
            if (isNaN(val) || val < 0) val = 0;
            orderItems[index].price = val;
            orderItems[index].subtotal = orderItems[index].qty * orderItems[index].price;
            renderTable();
        }

        window.removeItem = function(index) {
            orderItems.splice(index, 1);
            renderTable();
        }

        function renderTable() {
            const tbody = document.querySelector('#itemsTable tbody');
            const emptyRow = document.getElementById('emptyRow');
            
            // Remove all current item rows
            document.querySelectorAll('.item-row').forEach(row => row.remove());
            
            if (orderItems.length === 0) {
                emptyRow.style.display = 'table-row';
                document.getElementById('grandTotalLabel').innerText = '0';
                document.getElementById('total_amount').value = 0;
                return;
            }
            
            emptyRow.style.display = 'none';
            let grandTotal = 0;
            
            let html = '';
            orderItems.forEach((item, index) => {
                grandTotal += item.subtotal;
                
                html += `
                    <tr class="item-row">
                        <td style="text-align:center;font-weight:800;">${index + 1}</td>
                        <td style="font-weight:800;color:#0f172a;">
                            ${item.name}
                            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        </td>
                        <td>
                            <input type="number" name="items[${index}][price]" value="${item.price}" onchange="updatePrice(${index}, this.value)" class="form-input" min="0" step="1" style="width:120px;">
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.35rem;flex-wrap:wrap;">
                                <input type="number" name="items[${index}][quantity]" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)" class="form-input" style="width:100px;">
                                <select id="mult-${index}" class="form-input" style="width:64px;height:30px;padding:0 6px;font-size:12px;">
                                    <option value="1">×1</option>
                                    <option value="2">×2</option>
                                    <option value="3">×3</option>
                                    <option value="5">×5</option>
                                </select>
                                ${Array.isArray(item.conversions) && item.conversions.length ? `
                                <select id="unit-${index}" class="form-input" onchange="onUnitChange(${index})" style="width:150px;height:30px;padding:0 6px;font-size:12px;">
                                    ${item.conversions.slice(0,6).map(c => `
                                        <option value="${c.factor}" data-label="${c.label.replace(/"/g, '&quot;')}">${c.label} (x${c.factor})</option>
                                    `).join('')}
                                </select>
                                ` : ``}
                            </div>
                        </td>
                        <td style="text-align:right;font-weight:900;color:#0f172a;">Rp ${formatCurrency(item.subtotal)}</td>
                        <td style="text-align:right;">
                            <button type="button" onclick="removeItem(${index})" class="btn-danger">Hapus</button>
                        </td>
                    </tr>
                `;
            });
            
            // Insert before empty row
            emptyRow.insertAdjacentHTML('beforebegin', html);
            let totalQty = 0;
            orderItems.forEach(it => totalQty += Number(it.qty||0));
            document.getElementById('grandTotalLabel').innerText = formatCurrency(grandTotal);
            if (document.getElementById('itemsSummaryLabel')) {
                document.getElementById('itemsSummaryLabel').innerText = `${orderItems.length} item • Qty ${totalQty}`;
            }
            document.getElementById('total_amount').value = grandTotal;
        }

        document.getElementById('searchInput').addEventListener('keydown', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                var arr = window.latestSoSearchResults || [];
                if (arr.length) {
                    var first = arr[0];
                    window.selectProduct(first.id, first.name || ('Barang '+first.id), first.price || 0);
                }
            }
        });
        document.addEventListener('keydown', function(e){
            if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                var tag = (e.target && e.target.tagName || '').toLowerCase();
                if (['input', 'textarea', 'select'].indexOf(tag) === -1) {
                    e.preventDefault();
                    window.openProductModal();
                }
            }
        });

        document.getElementById('soForm').addEventListener('submit', function(e) {
            if (orderItems.length === 0) {
                e.preventDefault();
                alert('Silakan tambahkan minimal satu barang ke dalam Sales Order.');
            }
        });
    </script>
</x-app-layout>
