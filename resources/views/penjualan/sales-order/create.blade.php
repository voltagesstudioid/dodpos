<x-app-layout>
    <x-slot name="header">Buat Sales Order Baru</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --so-bg: #f8fafc;
            --so-surface: #ffffff;
            --so-border: #e2e8f0;
            --so-border-light: #f1f5f9;
            --so-text-main: #0f172a;
            --so-text-muted: #64748b;
            --so-primary: #3b82f6;
            --so-primary-hover: #2563eb;
            --so-danger: #ef4444;
            --so-success: #10b981;
            --so-radius-lg: 16px;
            --so-radius-md: 8px;
            --so-shadow-sm: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .so-page-wrapper { background-color: var(--so-bg); min-height: calc(100vh - 64px); padding: 2rem 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif; }
        .so-container { max-width: 1000px; margin: 0 auto; color: var(--so-text-main); }

        .so-back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.85rem; font-weight: 600; color: var(--so-text-muted);
            text-decoration: none; margin-bottom: 1.25rem; transition: color 0.2s;
        }
        .so-back-link:hover { color: var(--so-text-main); }

        .so-paper {
            background: var(--so-surface); border-radius: var(--so-radius-lg);
            border: 1px solid var(--so-border); box-shadow: var(--so-shadow-sm);
            overflow: hidden; margin-bottom: 1.5rem;
        }

        .so-header {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1.5rem 2rem; border-bottom: 1px solid var(--so-border-light); background: #fff;
        }
        .so-header-left { display: flex; align-items: center; gap: 1rem; }
        .so-header-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: #eff6ff; color: var(--so-primary);
            display: flex; align-items: center; justify-content: center;
        }
        .so-title { font-size: 1.25rem; font-weight: 800; margin: 0; }
        .so-subtitle { font-size: 0.85rem; color: var(--so-text-muted); margin-top: 0.25rem; }
        
        .so-body { padding: 1.5rem 2rem; }
        
        .so-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 1.25rem; }
        .col-4 { grid-column: span 4; }
        .col-6 { grid-column: span 6; }
        .col-12 { grid-column: span 12; }

        .so-label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; }
        .so-req { color: var(--so-danger); }
        .so-input {
            width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--so-border);
            border-radius: var(--so-radius-md); font-family: inherit; font-size: 0.85rem; color: var(--so-text-main);
            background: #f8fafc; outline: none; transition: all 0.2s;
        }
        .so-input:focus { border-color: var(--so-primary); background: #ffffff; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        select.so-input { cursor: pointer; }

        .so-alert { padding: 1rem 1.25rem; border-radius: var(--so-radius-md); margin-bottom: 1rem; font-size: 0.85rem; fill-opacity: 0.1; display: flex; gap: 0.75rem; }
        .so-alert-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .so-table-wrapper { border: 1px solid var(--so-border); border-radius: var(--so-radius-md); overflow: hidden; margin-top: 1rem; }
        .so-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        .so-table th { background: #f8fafc; padding: 0.75rem 1rem; text-align: left; font-weight: 700; color: var(--so-text-muted); border-bottom: 1px solid var(--so-border); }
        .so-table td { padding: 0.75rem 1rem; border-bottom: 1px solid var(--so-border-light); vertical-align: middle; }
        .so-table tr:last-child td { border-bottom: none; }
        
        .so-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 0.65rem 1.25rem; border-radius: var(--so-radius-md); font-size: 0.85rem; 
            font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; font-family: inherit;
        }
        .so-btn-primary { background: var(--so-primary); color: #fff; }
        .so-btn-primary:hover { background: var(--so-primary-hover); transform: translateY(-1px); }
        .so-btn-outline { background: #fff; border: 1px solid var(--so-border); color: var(--so-text-main); }
        .so-btn-outline:hover { background: #f8fafc; }
        .so-btn-danger { background: #fef2f2; color: var(--so-danger); border: 1px solid #fecaca; padding: 0.4rem 0.75rem; }
        .so-btn-danger:hover { background: var(--so-danger); color: #fff; }
        
        .empty-state { text-align: center; padding: 3rem 1rem; color: var(--so-text-muted); }
        .empty-icon { font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.5; }

        /* Modal Styles */
        .so-modal { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); display: none; align-items: center; justify-content: center; padding: 1rem; z-index: 1200; backdrop-filter: blur(4px); }
        .so-modal.open { display: flex; }
        .so-modal-card { width: min(700px, 100%); background: #fff; border-radius: 16px; border: 1px solid var(--so-border); overflow: hidden; box-shadow: 0 24px 80px rgba(15, 23, 42, 0.35); display: flex; flex-direction: column; max-height: 85vh; }
        .so-modal-head { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--so-border-light); display: flex; align-items: center; justify-content: space-between; background: #f8fafc; }
        .so-modal-body { padding: 1.5rem; overflow-y: auto; }
        
        .search-results { margin-top: 1rem; border: 1px solid var(--so-border); border-radius: 8px; max-height: 300px; overflow-y: auto; }
        .search-item { padding: 0.85rem 1rem; border-bottom: 1px solid var(--so-border-light); cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s; }
        .search-item:last-child { border-bottom: none; }
        .search-item:hover { background: #f8fafc; }
        .search-item-title { font-weight: 700; color: var(--so-text-main); margin-bottom: 2px; }
        .search-item-sub { font-size: 0.75rem; color: var(--so-text-muted); }

        @media (max-width: 768px) {
            .col-4, .col-6 { grid-column: span 12; }
            .so-header { flex-direction: column; align-items: flex-start; }
            .so-body { padding: 1rem; }
        }
    </style>
    @endpush

    <div class="so-page-wrapper">
        <div class="so-container">
            <a href="{{ route('sales-order.index') }}" class="so-back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar Sales Order
            </a>

            @if($errors->any())
                <div class="so-alert so-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <div>
                        <strong>Terdapat kesalahan input:</strong>
                        <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem;">
                            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('sales-order.store') }}" method="POST" id="soForm">
                @csrf
                
                {{-- Info Panel --}}
                <div class="so-paper">
                    <div class="so-header">
                        <div class="so-header-left">
                            <div class="so-header-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            <div>
                                <h2 class="so-title">Informasi Order</h2>
                                <p class="so-subtitle">Detail pelanggan, tanggal, dan status order.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="so-body">
                        <div class="so-grid">
                            <div class="col-6">
                                <label class="so-label" for="customer_id">Pelanggan <span class="so-req">*</span></label>
                                <select name="customer_id" id="customer_id" class="so-input" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }} {{ $c->phone ? '('.$c->phone.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label class="so-label" for="order_date">Tanggal Order <span class="so-req">*</span></label>
                                <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="so-input" required>
                            </div>
                            <div class="col-3">
                                <label class="so-label" for="delivery_date">Tanggal Kirim</label>
                                <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', date('Y-m-d', strtotime('+1 day'))) }}" class="so-input">
                            </div>

                            <div class="col-4">
                                <label class="so-label" for="status">Status Awal <span class="so-req">*</span></label>
                                <select name="status" id="status" class="so-input" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Baru)</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed (Disetujui)</option>
                                    <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing (Diproses)</option>
                                </select>
                            </div>
                            <div class="col-8">
                                <label class="so-label" for="notes">Catatan Tambahan</label>
                                <input type="text" name="notes" id="notes" value="{{ old('notes') }}" class="so-input" placeholder="Contoh: Kirim secepatnya...">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Items Panel --}}
                <div class="so-paper">
                    <div class="so-header">
                        <div class="so-header-left">
                            <div class="so-header-icon" style="background:#f0fdf4; color:var(--so-success);">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            </div>
                            <div>
                                <h2 class="so-title">Daftar Barang</h2>
                                <p class="so-subtitle">Tambahkan barang minimal 1 ke dalam pesanan.</p>
                            </div>
                        </div>
                        <button type="button" class="so-btn so-btn-outline" onclick="openProductModal()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah Barang ( / )
                        </button>
                    </div>
                    
                    <div class="so-body">
                        <div class="so-table-wrapper">
                            <table class="so-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width:50px; text-align:center;">#</th>
                                        <th>Nama Barang</th>
                                        <th style="width:150px;">Harga (Rp)</th>
                                        <th style="width:200px;">Qty & Satuan</th>
                                        <th style="width:150px; text-align:right;">Subtotal</th>
                                        <th style="width:80px; text-align:center;">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="emptyRow">
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <div class="empty-icon">🛒</div>
                                                <div style="font-weight:700; color:var(--so-text-main); margin-bottom:0.25rem;">Belum ada barang</div>
                                                <div>Klik tombol "Tambah Barang" untuk memulai</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr style="background:#f8fafc;">
                                        <td colspan="4" style="text-align:right; font-weight:800;">TOTAL KESELURUHAN</td>
                                        <td style="text-align:right; font-weight:800; color:var(--so-primary); font-size:1.1rem;">
                                            Rp <span id="grandTotalLabel">0</span>
                                            <input type="hidden" name="total_amount" id="total_amount" value="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:2rem;">
                    <a href="{{ route('sales-order.index') }}" class="so-btn so-btn-outline" style="padding:0.75rem 1.5rem;">Batalkan</a>
                    <button type="submit" class="so-btn so-btn-primary" style="padding:0.75rem 1.5rem; font-size:0.95rem;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan Sales Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Product Modal --}}
    <div id="productModal" class="so-modal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="so-modal-card">
            <div class="so-modal-head">
                <div>
                    <h3 style="font-size:1.1rem; font-weight:800; margin:0; color:var(--so-text-main);">Cari & Tambah Barang</h3>
                    <p style="font-size:0.8rem; color:var(--so-text-muted); margin-top:2px;">Ketik minimal 2 karakter (Nama / SKU)</p>
                </div>
                <button type="button" class="so-btn so-btn-outline" onclick="closeProductModal()" style="padding:0.4rem 0.75rem;">✕ Tutup</button>
            </div>
            <div class="so-modal-body">
                <input type="text" id="searchInput" class="so-input" placeholder="Cari barang..." autocomplete="off">
                <div class="search-results" id="searchResults">
                    <div style="padding: 2rem; text-align:center; color:var(--so-text-muted); font-size:0.85rem;">
                        Mulai ketik untuk mencari...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="application/json" id="oldItemsJson">{!! json_encode($oldItemsForJs ?? []) !!}</script>

    @push('scripts')
    <script>
        let orderItems = [];
        const oldItemsEl = document.getElementById('oldItemsJson');
        let oldItems = [];
        try { oldItems = JSON.parse(oldItemsEl ? oldItemsEl.textContent : '[]'); } catch (e) {}

        if (Array.isArray(oldItems) && oldItems.length) {
            oldItems.forEach(it => {
                orderItems.push({
                    id: Number(it.product_id),
                    name: String(it.name || `Barang (ID: ${it.product_id})`),
                    price: Number(it.price || 0),
                    qty: Number(it.quantity || 1),
                    conversions: Array.isArray(it.conversions) ? it.conversions : [],
                    subtotal: Number(it.price || 0) * Number(it.quantity || 1),
                });
            });
            renderTable();
        }

        function formatCurrency(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num));
        }

        function openProductModal() {
            const modal = document.getElementById('productModal');
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            setTimeout(() => document.getElementById('searchInput').focus(), 100);
        }
        window.openProductModal = openProductModal;

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }

        let searchTimeout = null;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.trim();
            const resultsContainer = document.getElementById('searchResults');
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                resultsContainer.innerHTML = '<div style="padding: 2rem; text-align:center; color:#64748b;">Ketik minimal 2 karakter...</div>';
                return;
            }

            resultsContainer.innerHTML = '<div style="padding: 2rem; text-align:center; color:#3b82f6; font-weight:600;">Mencari data...</div>';
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales-order.products.search') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        window.latestSoSearchResults = Array.isArray(data) ? data : [];
                        if(!Array.isArray(data) || data.length === 0) {
                            resultsContainer.innerHTML = '<div style="padding: 2rem; text-align:center; color:#ef4444;">Barang tidak ditemukan.</div>';
                            return;
                        }

                        let html = '';
                        data.forEach(item => {
                            const safeName = String(item.name || '').replace(/'/g, "\\'");
                            const stock = item.stock || 0;
                            let badge = stock > 0 ? `<span style="background:#dcfce7; color:#166534; padding:2px 8px; border-radius:99px; font-size:0.7rem; font-weight:700;">Stok: ${stock}</span>` : `<span style="background:#fee2e2; color:#b91c1c; padding:2px 8px; border-radius:99px; font-size:0.7rem; font-weight:700;">Habis</span>`;
                            
                            html += `
                                <div class="search-item" onclick="selectProduct(${item.id}, '${safeName}', ${item.price || 0})">
                                    <div>
                                        <div class="search-item-title">${item.name || '-'}</div>
                                        <div class="search-item-sub">${item.sku || item.barcode || '-'}</div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-weight:700; color:var(--so-primary); font-size:0.9rem;">Rp ${formatCurrency(item.price || 0)}</div>
                                        ${badge}
                                    </div>
                                </div>
                            `;
                        });
                        resultsContainer.innerHTML = html;
                    })
                    .catch(err => {
                        resultsContainer.innerHTML = '<div style="padding: 2rem; text-align:center; color:#ef4444;">Gagal mengambil data.</div>';
                    });
            }, 300);
        });

        window.selectProduct = function(id, name, defaultPrice) {
            const existing = orderItems.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
                existing.subtotal = existing.qty * existing.price;
            } else {
                let convs = [];
                try {
                    const found = (window.latestSoSearchResults || []).find(x => x.id === id);
                    convs = Array.isArray(found?.conversions) ? found.conversions : [];
                } catch (e) {}
                
                orderItems.push({
                    id: id, name: name, price: defaultPrice, qty: 1, conversions: convs, subtotal: defaultPrice
                });
            }
            closeProductModal();
            renderTable();
        };

        function updateQty(index, newQty) {
            const val = parseInt(newQty) || 1;
            orderItems[index].qty = val < 1 ? 1 : val;
            orderItems[index].subtotal = orderItems[index].qty * orderItems[index].price;
            renderTable();
        }

        function updatePrice(index, newPrice) {
            let val = parseFloat(newPrice) || 0;
            orderItems[index].price = val < 0 ? 0 : val;
            orderItems[index].subtotal = orderItems[index].qty * orderItems[index].price;
            renderTable();
        }

        function onUnitChange(index) {
            const sel = document.getElementById('unit-'+index);
            const factor = parseInt(sel.value) || 1;
            const item = orderItems[index];
            updateQty(index, factor); 
        }

        window.removeItem = function(index) {
            orderItems.splice(index, 1);
            renderTable();
        }

        function renderTable() {
            const tbody = document.querySelector('#itemsTable tbody');
            const emptyRow = document.getElementById('emptyRow');
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
                
                let unitOptions = '';
                if(item.conversions && item.conversions.length > 0) {
                     unitOptions = `<select id="unit-${index}" class="so-input" onchange="onUnitChange(${index})" style="width:100%; margin-top:4px; padding:0.4rem;">
                        ${item.conversions.slice(0,5).map(c => `<option value="${c.factor}">${c.label} (x${c.factor})</option>`).join('')}
                     </select>`;
                }

                html += `
                    <tr class="item-row">
                        <td style="text-align:center; font-weight:700; color:var(--so-text-muted);">${index + 1}</td>
                        <td style="font-weight:700;">
                            ${item.name}
                            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        </td>
                        <td>
                            <input type="number" name="items[${index}][price]" value="${item.price}" onchange="updatePrice(${index}, this.value)" class="so-input" min="0" step="1">
                        </td>
                        <td>
                            <input type="number" name="items[${index}][quantity]" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)" class="so-input">
                            ${unitOptions}
                        </td>
                        <td style="text-align:right; font-weight:800; color:var(--so-primary);">Rp ${formatCurrency(item.subtotal)}</td>
                        <td style="text-align:center;">
                            <button type="button" onclick="removeItem(${index})" class="so-btn so-btn-danger" title="Hapus">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            emptyRow.insertAdjacentHTML('beforebegin', html);
            document.getElementById('grandTotalLabel').innerText = formatCurrency(grandTotal);
            document.getElementById('total_amount').value = grandTotal;
        }

        document.addEventListener('keydown', function(e){
            if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                var tag = (e.target && e.target.tagName || '').toLowerCase();
                if (['input', 'textarea', 'select'].indexOf(tag) === -1) {
                    e.preventDefault();
                    openProductModal();
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
    @endpush
</x-app-layout>
