<x-app-layout>
    <x-slot name="header">Kasir / Tambah Item - {{ $transaction->invoice_number }}</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-container">
            {{-- ─── HEADER ─── --}}
            <div class="tr-header-bar">
                <a href="{{ route('transaksi.show', $transaction) }}" class="btn-back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali ke Transaksi
                </a>
                <div class="header-title">
                    <h1>Tambah Item</h1>
                    <span class="subtitle">{{ $transaction->invoice_number }}</span>
                </div>
            </div>

            {{-- ─── ORIGINAL TRANSACTION SUMMARY ─── --}}
            <div class="tr-card summary-card">
                <h3>Transaksi Awal</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <span class="label">Customer</span>
                        <span class="value">{{ $transaction->customer?->name ?? 'Umum' }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Total Awal</span>
                        <span class="value">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Metode Bayar</span>
                        <span class="value">{{ ucfirst($transaction->payment_method) }}</span>
                    </div>
                    @if($transaction->hasAdditionalItems())
                        <div class="summary-item additional">
                            <span class="label">Sudah Ada Tambahan</span>
                            <span class="value">{{ $transaction->additionalTransactions->count() }} kali</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─── ADD ITEMS FORM ─── --}}
            <div class="tr-card form-card">
                <h3>Tambah Item Baru</h3>

                {{-- Product Search --}}
                <div class="product-section">
                    <label>Cari Produk</label>
                    <div class="search-box">
                        <input type="text" id="productSearch" placeholder="Ketik nama atau SKU produk..." autocomplete="off">
                        <div id="productResults" class="product-dropdown"></div>
                    </div>
                </div>

                {{-- Cart Items --}}
                <div class="cart-section">
                    <h4>Item yang Ditambahkan</h4>
                    <div id="cartItems" class="cart-list">
                        <div class="empty-cart">Belum ada item. Cari dan tambahkan produk.</div>
                    </div>
                </div>

                {{-- Totals --}}
                <div class="totals-section">
                    <div class="total-row">
                        <span>Total Item:</span>
                        <span id="itemCount">0 item</span>
                    </div>
                    <div class="total-row grand">
                        <span>Total Tambahan:</span>
                        <span id="totalAmount">Rp 0</span>
                    </div>
                </div>

                {{-- Payment Section --}}
                <div class="payment-section">
                    <div class="form-group">
                        <label>Pembayaran Tambahan (Rp)</label>
                        <input type="number" id="additionalPayment" min="0" step="0.01" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select id="paymentMethod" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <option value="cash" {{ $transaction->payment_method === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="transfer" {{ $transaction->payment_method === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="qris" {{ $transaction->payment_method === 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                        <small style="color: #64748b; font-size: 0.75rem;">Default: {{ ucfirst($transaction->payment_method) }}</small>
                    </div>
                    <div class="form-group" id="refField" style="display: {{ $transaction->payment_method === 'transfer' ? 'block' : 'none' }};">
                        <label>No. Referensi (untuk Transfer/QRIS)</label>
                        <input type="text" id="paymentReference" placeholder="Contoh: TRX-123456">
                    </div>
                    <div class="form-group">
                        <label>Catatan (opsional)</label>
                        <textarea id="notes" rows="2" placeholder="Contoh: Customer minta nambah..."></textarea>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="actions">
                    <button type="button" id="btnSave" class="btn-primary" disabled>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        </svg>
                        Simpan Tambahan Item
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .tr-page-wrapper { background: #f8fafc; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 3rem; }
        .tr-container { max-width: 800px; margin: 0 auto; padding: 2rem 1.5rem; }

        .tr-header-bar { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .btn-back { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b; text-decoration: none; font-weight: 600; }
        .btn-back:hover { background: #f1f5f9; }
        .header-title h1 { font-size: 1.5rem; font-weight: 800; margin: 0; }
        .header-title .subtitle { color: #64748b; font-size: 0.9rem; }

        .tr-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem; }
        .tr-card h3 { font-size: 1rem; font-weight: 700; margin: 0 0 1.25rem; color: #0f172a; }
        .tr-card h4 { font-size: 0.9rem; font-weight: 700; margin: 0 0 1rem; color: #374151; }

        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .summary-item { display: flex; flex-direction: column; gap: 0.25rem; }
        .summary-item .label { font-size: 0.8rem; color: #64748b; }
        .summary-item .value { font-size: 1rem; font-weight: 600; color: #0f172a; }
        .summary-item.additional .value { color: #f59e0b; }

        .product-section { margin-bottom: 1.5rem; }
        .product-section label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; color: #374151; }
        .search-box { position: relative; }
        .search-box input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; }
        .search-box input:focus { outline: none; border-color: #4f46e5; }
        .product-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; max-height: 250px; overflow-y: auto; z-index: 100; display: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .product-dropdown.active { display: block; }
        .product-item { padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; }
        .product-item:hover { background: #f8fafc; }
        .product-item:last-child { border-bottom: none; }
        .product-name { font-weight: 600; color: #0f172a; }
        .product-info { font-size: 0.8rem; color: #64748b; }

        .cart-section { margin-bottom: 1.5rem; }
        .cart-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .cart-item { display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 10px; }
        .cart-item-info { flex: 1; }
        .cart-item-name { font-weight: 600; color: #0f172a; }
        .cart-item-price { font-size: 0.85rem; color: #64748b; }
        .cart-item-qty { display: flex; align-items: center; gap: 0.5rem; }
        .cart-item-qty input { width: 60px; text-align: center; padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; }
        .cart-item-total { font-weight: 700; color: #4f46e5; min-width: 100px; text-align: right; }
        .btn-remove { width: 32px; height: 32px; border: none; background: #fee2e2; color: #ef4444; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .btn-remove:hover { background: #fecaca; }
        .empty-cart { text-align: center; padding: 2rem; color: #94a3b8; }

        .totals-section { border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-bottom: 1.5rem; }
        .total-row { display: flex; justify-content: space-between; padding: 0.5rem 0; }
        .total-row.grand { font-size: 1.1rem; font-weight: 700; color: #0f172a; }
        .total-row.grand span:last-child { color: #4f46e5; }

        .payment-section { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1.5rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group label { font-size: 0.875rem; font-weight: 600; color: #374151; }
        .form-group input, .form-group textarea { padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #4f46e5; }

        .actions { display: flex; justify-content: flex-end; }
        .btn-primary { display: flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.5rem; background: #4f46e5; color: #fff; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .btn-primary:hover:not(:disabled) { background: #4338ca; }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

        @media (max-width: 640px) {
            .payment-section { grid-template-columns: 1fr; }
            .cart-item { flex-wrap: wrap; }
            .cart-item-total { width: 100%; text-align: left; margin-top: 0.5rem; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
    const PRODUCTS = @json($products);
    const WAREHOUSES = @json(\App\Models\Warehouse::where('active', true)->get(['id', 'name']));
    const ORIGINAL_TRANSACTION_ID = {{ $transaction->id }};
    const CSRF_TOKEN = '{{ csrf_token() }}';

    let cart = [];

    // Product search
    const productSearch = document.getElementById('productSearch');
    const productResults = document.getElementById('productResults');

    productSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        if (query.length < 2) {
            productResults.classList.remove('active');
            return;
        }

        const filtered = PRODUCTS.filter(p =>
            p.name.toLowerCase().includes(query) ||
            (p.sku && p.sku.toLowerCase().includes(query))
        ).slice(0, 10);

        if (filtered.length > 0) {
            productResults.innerHTML = filtered.map(p => {
                const unitsList = p.units && p.units.length 
                    ? p.units.map(u => `${u.name}: Rp ${(u.prices?.eceran || 0).toLocaleString('id-ID')}`).join(' | ')
                    : `Harga: Rp ${(p.prices?.eceran || 0).toLocaleString('id-ID')}`;
                return `
                <div class="product-item" onclick="showUnitSelection(${p.id})">
                    <div class="product-name">${p.name}</div>
                    <div class="product-info">SKU: ${p.sku || '-'} | Stok: ${p.stock || 0}</div>
                    <div class="product-units" style="font-size:0.75rem;color:#4f46e5;margin-top:4px;">${unitsList}</div>
                </div>`;
            }).join('');
            productResults.classList.add('active');
        } else {
            productResults.innerHTML = '<div class="product-item" style="color:#94a3b8;">Tidak ada produk ditemukan</div>';
            productResults.classList.add('active');
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-box')) {
            productResults.classList.remove('active');
        }
    });

    function showUnitSelection(productId) {
        const product = PRODUCTS.find(p => p.id === productId);
        if (!product) return;

        // If product has units, show selection dialog
        if (product.units && product.units.length > 0) {
            let unitOptions = product.units.map((u, idx) => 
                `<option value="${idx}">${u.name} - Rp ${(u.prices?.eceran || 0).toLocaleString('id-ID')}</option>`
            ).join('');
            
            const unitIndex = prompt(`Pilih satuan untuk ${product.name}:\n${product.units.map((u,i) => `${i+1}. ${u.name} - Rp ${(u.prices?.eceran || 0).toLocaleString('id-ID')}`).join('\n')}\n\nMasukkan nomor (1-${product.units.length}):`, '1');
            
            if (unitIndex === null) return; // Cancelled
            
            const selectedIdx = parseInt(unitIndex) - 1;
            if (selectedIdx >= 0 && selectedIdx < product.units.length) {
                addToCartWithUnit(productId, selectedIdx);
            } else {
                alert('Pilihan tidak valid');
            }
        } else {
            // No units, add directly
            addToCartWithUnit(productId, null);
        }
    }

    function addToCartWithUnit(productId, unitIndex) {
        const product = PRODUCTS.find(p => p.id === productId);
        if (!product) return;

        // Default to first warehouse
        const defaultWarehouse = WAREHOUSES[0]?.id || 1;
        
        // Get unit data if selected
        let unitData = null;
        let unitPrice = product.prices?.eceran || 0;
        let unitName = 'pcs';
        let unitId = null;
        
        if (unitIndex !== null && product.units && product.units[unitIndex]) {
            unitData = product.units[unitIndex];
            unitPrice = unitData.prices?.eceran || 0;
            unitName = unitData.name;
            unitId = unitData.id;
        }

        const existing = cart.find(item => item.product_id === productId && item.unit_id === unitId);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({
                product_id: productId,
                unit_id: unitId,
                unit_name: unitName,
                name: product.name,
                sku: product.sku,
                price: unitPrice,
                qty: 1,
                warehouse_id: defaultWarehouse,
                stock: product.stock || 0,
                units: product.units || [], // Store for unit switching
            });
        }

        productSearch.value = '';
        productResults.classList.remove('active');
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateQty(index, newQty) {
        newQty = parseInt(newQty) || 1;
        if (newQty < 1) newQty = 1;
        if (newQty > cart[index].stock) {
            alert('Stok tidak mencukupi!');
            newQty = cart[index].stock;
        }
        cart[index].qty = newQty;
        renderCart();
    }

    function updatePrice(index, newPrice) {
        newPrice = parseFloat(newPrice) || 0;
        cart[index].price = newPrice;
        renderCart();
    }

    function changeUnit(index, unitId) {
        const item = cart[index];
        const unit = item.units.find(u => u.id == unitId);
        if (unit) {
            item.unit_id = unit.id;
            item.unit_name = unit.name;
            item.price = unit.prices?.eceran || 0;
            renderCart();
        }
    }

    function renderCart() {
        const container = document.getElementById('cartItems');

        if (cart.length === 0) {
            container.innerHTML = '<div class="empty-cart">Belum ada item. Cari dan tambahkan produk.</div>';
            document.getElementById('btnSave').disabled = true;
            updateTotals();
            return;
        }

        container.innerHTML = cart.map((item, index) => `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">
                        SKU: ${item.sku || '-'} | Stok: ${item.stock}
                        <select onchange="changeUnit(${index}, this.value)" style="margin-left: 10px; font-size: 0.8rem;">
                            ${item.units && item.units.length > 0 
                                ? item.units.map(u => `<option value="${u.id}" ${u.id == item.unit_id ? 'selected' : ''}>${u.name}</option>`).join('')
                                : `<option value="">pcs</option>`
                            }
                        </select>
                        <select onchange="cart[${index}].warehouse_id = this.value; renderCart();" style="margin-left: 10px; font-size: 0.8rem;">
                            ${WAREHOUSES.map(w => `<option value="${w.id}" ${w.id == item.warehouse_id ? 'selected' : ''}>${w.name}</option>`).join('')}
                        </select>
                    </div>
                </div>
                <div class="cart-item-qty">
                    <input type="number" min="1" max="${item.stock}" value="${item.qty}" onchange="updateQty(${index}, this.value)">
                </div>
                <div class="cart-item-qty">
                    <input type="number" min="0" value="${item.price}" onchange="updatePrice(${index}, this.value)" style="width: 100px;">
                </div>
                <div class="cart-item-total">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</div>
                <button class="btn-remove" onclick="removeFromCart(${index})">×</button>
            </div>
        `).join('');

        document.getElementById('btnSave').disabled = false;
        updateTotals();
    }

    function updateTotals() {
        const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
        const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

        document.getElementById('itemCount').textContent = `${totalQty} item`;
        document.getElementById('totalAmount').textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
    }

    // Handle payment method change
    document.getElementById('paymentMethod').addEventListener('change', function() {
        const refField = document.getElementById('refField');
        if (this.value === 'transfer' || this.value === 'qris') {
            refField.style.display = 'block';
        } else {
            refField.style.display = 'none';
            document.getElementById('paymentReference').value = '';
        }
    });

    // Save additional items
    document.getElementById('btnSave').addEventListener('click', async function() {
        if (cart.length === 0) {
            alert('Keranjang masih kosong. Silakan tambahkan item terlebih dahulu.');
            return;
        }

        const items = cart.map(item => ({
            product_id: item.product_id,
            quantity: item.qty,
            price: item.price,
            warehouse_id: item.warehouse_id,
        }));

        // Calculate total
        const totalAmount = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const additionalPayment = parseFloat(document.getElementById('additionalPayment').value) || 0;

        // Validate payment
        if (additionalPayment <= 0) {
            alert('Pembayaran tambahan wajib diisi dan harus lebih dari 0.');
            document.getElementById('additionalPayment').focus();
            return;
        }

        if (additionalPayment < totalAmount) {
            alert(`Pembayaran kurang! Total: Rp ${totalAmount.toLocaleString('id-ID')}, Dibayar: Rp ${additionalPayment.toLocaleString('id-ID')}`);
            document.getElementById('additionalPayment').focus();
            return;
        }

        const paymentMethod = document.getElementById('paymentMethod').value;
        const paymentReference = document.getElementById('paymentReference').value;
        const notes = document.getElementById('notes').value;

        // Validate payment reference for transfer/qris
        if ((paymentMethod === 'transfer' || paymentMethod === 'qris') && !paymentReference.trim()) {
            alert('No. Referensi wajib diisi untuk metode pembayaran Transfer/QRIS.');
            document.getElementById('paymentReference').focus();
            return;
        }

        this.disabled = true;
        this.innerHTML = 'Menyimpan...';

        try {
            const response = await fetch(`/kasir/transactions/${ORIGINAL_TRANSACTION_ID}/add-items`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({
                    items: items,
                    additional_payment: additionalPayment,
                    payment_method: paymentMethod,
                    payment_reference: paymentReference,
                    notes: notes,
                }),
            });

            const result = await response.json();
            console.log('Server response:', result);

            if (result.success) {
                alert(`Item tambahan berhasil disimpan!\nPick Order: ${result.pick_order || '-'}\n\nAnda akan diarahkan ke detail transaksi.`);
                window.location.href = '{{ route('transaksi.show', $transaction) }}';
            } else {
                alert('Gagal: ' + (result.message || 'Unknown error'));
                this.disabled = false;
                this.innerHTML = `
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    </svg>
                    Simpan Tambahan Item
                `;
            }
        } catch (error) {
            alert('Error: ' + error.message);
            this.disabled = false;
            this.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                </svg>
                Simpan Tambahan Item
            `;
        }
    });
    </script>
    @endpush
</x-app-layout>
