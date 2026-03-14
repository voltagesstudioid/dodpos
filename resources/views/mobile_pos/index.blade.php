<x-mobile-layout>
    <x-slot name="header">Pasgar POS</x-slot>

    <!-- Data Initialization from Server -->
    <script>
        // Server injects the initial catalog if online, otherwise JS reads from IndexedDB
        window.serverProducts = @json(\App\Models\Product::with('unit')->get(['id', 'barcode', 'kode_produk', 'name', 'unit_id', 'harga_umum', 'harga_grosir', 'photo']));
        window.serverCustomers = @json(\App\Models\Customer::get(['id', 'name', 'phone', 'address', 'type']));
    </script>

    <style>
        .pos-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-bottom: 80px; /* space for cart button */
        }

        .search-bar {
            position: sticky;
            top: 0;
            background: #f8fafc;
            padding-bottom: 0.5rem;
            z-index: 5;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            font-size: 1rem;
            outline: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }

        .search-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .product-img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .product-info {
            padding: 0.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 0.875rem;
            font-weight: 800;
            color: #4f46e5;
            margin-top: auto;
        }

        .add-btn {
            background: #e0e7ff;
            color: #4338ca;
            border: none;
            width: 100%;
            padding: 0.5rem;
            font-weight: 700;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .add-btn:active {
            background: #c7d2fe;
        }

        /* Floating Cart Summary */
        .cart-summary {
            position: fixed;
            bottom: calc(env(safe-area-inset-bottom, 0.75rem) + 60px); 
            left: 1rem;
            right: 1rem;
            background: #1e293b;
            color: white;
            border-radius: 16px;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2);
            z-index: 20;
            cursor: pointer;
            transition: bottom 0.3s;
        }

        .cart-summary.hidden {
            bottom: -100px;
        }

        /* Cart Modal */
        .cart-modal {
            position: fixed;
            top: 100%;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 30;
            display: flex;
            flex-direction: column;
            transition: top 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cart-modal.open {
            top: 0;
        }

        .cart-header {
            padding: 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 0;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #f1f5f9;
            border-radius: 999px;
            padding: 0.25rem;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            border: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .checkout-bar {
            padding: 1rem;
            background: white;
            border-top: 1px solid #e2e8f0;
            padding-bottom: calc(1rem + env(safe-area-inset-bottom, 0));
        }

        .checkout-btn {
            width: 100%;
            background: #10b981;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.125rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4);
        }
    </style>

    <div class="pos-container" id="pos-app">
        
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="🔍 Cari Barang..." id="searchInput">
        </div>

        <div class="product-grid" id="productGrid">
            <!-- Rendered via JS -->
        </div>

    </div>

    <!-- Floating Cart Summary -->
    <div class="cart-summary hidden" id="cartSummary" onclick="toggleCartModal()">
        <div>
            <div style="font-size: 0.75rem; color: #94a3b8;">Item di Keranjang</div>
            <div style="font-weight: 700; font-size: 1.125rem;" id="cartItemCount">0 Item</div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 0.75rem; color: #94a3b8;">Total Belanja</div>
            <div style="font-weight: 800; font-size: 1.125rem;" id="cartTotalSum">Rp 0</div>
        </div>
    </div>

    <!-- Cart Full Screen Modal -->
    <div class="cart-modal" id="cartModal">
        <div class="cart-header">
            <h2 style="margin: 0; font-weight: 700; font-size: 1.125rem;">🛒 Keranjang</h2>
            <button onclick="toggleCartModal()" style="border:none; background:#e2e8f0; width:36px; height:36px; border-radius:50%; font-weight:bold; cursor:pointer;">✕</button>
        </div>
        
        <div style="padding: 1rem 1rem 0;">
            <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:0.25rem;">Pilih Pelanggan <span style="color:#ef4444;">*</span></label>
            <select id="customerSelect" style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid #cbd5e1; outline:none;">
                <option value="">-- Pelanggan Umum --</option>
                <!-- customers rendered via js -->
            </select>
        </div>

        <div class="cart-items" id="cartItemsContainer">
            <!-- Items rendered via JS -->
            <div style="text-align:center; padding: 2rem; color: #94a3b8;">Keranjang Masih Kosong</div>
        </div>
        
        <div class="checkout-bar">
            <div style="display:flex; justify-content:space-between; margin-bottom:1rem; font-size: 1.125rem; font-weight:700;">
                <span>Total Tagihan:</span>
                <span id="modalTotalSum">Rp 0</span>
            </div>
            <button class="checkout-btn" onclick="saveOrderOffline()">
                <span>SIMPAN PESANAN</span>
                <span>✅</span>
            </button>
        </div>
    </div>


    <script>
        // PWA Offline-First Core Logic
        const AppState = {
            products: [],
            customers: [],
            cart: [],
            vanStock: {}, // Map of product_id => remaining_qty
            priceMode: 'harga_umum' // can toggle based on customer type
        };

        const Elements = {
            grid: document.getElementById('productGrid'),
            searchInput: document.getElementById('searchInput'),
            summary: document.getElementById('cartSummary'),
            cartItemCount: document.getElementById('cartItemCount'),
            cartTotalSum: document.getElementById('cartTotalSum'),
            cartModal: document.getElementById('cartModal'),
            cartItemsContainer: document.getElementById('cartItemsContainer'),
            modalTotalSum: document.getElementById('modalTotalSum'),
            customerSelect: document.getElementById('customerSelect')
        };

        function formatRp(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        function getDynamicVanStock(productId) {
            let stock = AppState.vanStock[productId] || 0;
            
            // Deduct pending offline orders
            let pendingOrders = JSON.parse(localStorage.getItem('pwa_pending_orders') || '[]');
            pendingOrders.forEach(o => {
                o.items.forEach(i => {
                    if (i.id === productId) stock -= i.qty;
                });
            });

            // Deduct current active cart
            let inCart = AppState.cart.find(c => c.id === productId)?.qty || 0;
            stock -= inCart;

            return Math.max(0, stock);
        }

        // 1. Storage & Boot
        async function initApp() {
            if(navigator.onLine && window.serverProducts && window.serverProducts.length > 0) {
                // Online sync: save to local payload
                localStorage.setItem('pwa_products', JSON.stringify(window.serverProducts));
                localStorage.setItem('pwa_customers', JSON.stringify(window.serverCustomers));
                
                AppState.products = window.serverProducts;
                AppState.customers = window.serverCustomers;

                try {
                    const res = await fetch('{{ route("mobile.stock") }}');
                    const data = await res.json();
                    if(data.success) {
                        AppState.vanStock = data.van_stock;
                        localStorage.setItem('pwa_van_stock', JSON.stringify(data.van_stock));
                    }
                } catch(e) {
                    console.error('Gagal fetch stok mobil', e);
                    AppState.vanStock = JSON.parse(localStorage.getItem('pwa_van_stock') || '{}');
                }
            } else {
                // Offline load
                const pCache = localStorage.getItem('pwa_products');
                const cCache = localStorage.getItem('pwa_customers');
                
                AppState.products = pCache ? JSON.parse(pCache) : [];
                AppState.customers = cCache ? JSON.parse(cCache) : [];
                AppState.vanStock = JSON.parse(localStorage.getItem('pwa_van_stock') || '{}');
            }

            renderProducts(AppState.products);
            renderCustomers();
        }

        // 2. Rendering
        function renderProducts(list) {
            Elements.grid.innerHTML = '';
            
            if(list.length === 0) {
                Elements.grid.innerHTML = '<div style="grid-column:1/-1; padding:2rem; text-align:center; color:#94a3b8;">Sedang OFFLINE dan Belum Ada Data tersimpan. Silakan online dulu.</div>';
                return;
            }

            list.forEach(p => {
                const img = p.photo ? `/storage/${p.photo}` : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%23cbd5e1" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>';
                const price = AppState.priceMode === 'harga_grosir' ? p.harga_grosir : p.harga_umum;
                
                const qtyInCart = AppState.cart.find(c => c.id === p.id)?.qty || 0;
                const dynamicSisa = getDynamicVanStock(p.id);
                
                let btnHtml = '';
                if(qtyInCart > 0) {
                    btnHtml = `<div style="display:flex; justify-content:space-between; align-items:center; background:#4f46e5; color:white; padding:0.5rem; font-size:0.875rem; font-weight:700;">
                        <button onclick="removeFromCart(${p.id})" style="border:none;background:rgba(255,255,255,0.2);color:white;width:24px;height:24px;border-radius:4px;cursor:pointer;">-</button>
                        <span>${qtyInCart}</span>
                        <button onclick="addToCart(${p.id})" style="border:none;background:rgba(255,255,255,0.2);color:white;width:24px;height:24px;border-radius:4px;cursor:pointer;${dynamicSisa <= 0 ? 'opacity:0.5' : ''}">+</button>
                    </div>`;
                } else {
                    if (dynamicSisa > 0) {
                        btnHtml = `<button class="add-btn" onclick="addToCart(${p.id})">+ TAMBAH</button>`;
                    } else {
                        btnHtml = `<button class="add-btn" disabled style="background:#f1f5f9;color:#94a3b8;cursor:not-allowed;">KOSONG</button>`;
                    }
                }

                const card = `
                <div class="product-card">
                    <img src="${img}" class="product-img" alt="${p.name}">
                    <div class="product-info">
                        <div class="product-name">${p.name}</div>
                        <div style="font-size:0.75rem; color:#ef4444; font-weight:700; margin-bottom:0.25rem;">Sisa Mbl: ${dynamicSisa + qtyInCart}</div>
                        <div class="product-price">${formatRp(price)}</div>
                    </div>
                    ${btnHtml}
                </div>`;
                Elements.grid.insertAdjacentHTML('beforeend', card);
            });
        }

        function renderCustomers() {
            let html = '<option value="">-- Pelanggan Umum --</option>';
            AppState.customers.forEach(c => {
                html += `<option value="${c.id}" data-type="${c.type}">${c.name}</option>`;
            });
            Elements.customerSelect.innerHTML = html;
        }

        // 3. Cart Logic
        function addToCart(productId) {
            const prod = AppState.products.find(p => p.id === productId);
            if(!prod) return;

            const existing = AppState.cart.find(c => c.id === productId);
            const dynamicSisa = getDynamicVanStock(productId);

            if (dynamicSisa <= 0) {
                alert('Stok di mobil tidak cukup!');
                return;
            }

            if(existing) {
                existing.qty++;
            } else {
                AppState.cart.push({ ...prod, qty: 1 });
            }
            
            updateUI();
        }

        function removeFromCart(productId) {
            const index = AppState.cart.findIndex(c => c.id === productId);
            if(index > -1) {
                if(AppState.cart[index].qty > 1) {
                    AppState.cart[index].qty--;
                } else {
                    AppState.cart.splice(index, 1);
                }
            }
            updateUI();
        }

        function updateUI() {
            // Re-render grid to show qtys
            renderProducts(AppState.products);

            // Calculate totals
            let totalQty = 0;
            let totalSum = 0;

            AppState.cart.forEach(item => {
                const price = AppState.priceMode === 'harga_grosir' ? item.harga_grosir : item.harga_umum;
                totalQty += item.qty;
                totalSum += item.qty * price;
            });

            // Update Summary
            if(totalQty > 0) {
                Elements.summary.classList.remove('hidden');
                Elements.cartItemCount.innerText = `${totalQty} Item`;
                Elements.cartTotalSum.innerText = formatRp(totalSum);
                Elements.modalTotalSum.innerText = formatRp(totalSum);
            } else {
                Elements.summary.classList.add('hidden');
                Elements.modalTotalSum.innerText = 'Rp 0';
            }

            renderCartItems();
        }

        function toggleCartModal() {
            Elements.cartModal.classList.toggle('open');
        }

        function renderCartItems() {
            if(AppState.cart.length === 0) {
                Elements.cartItemsContainer.innerHTML = '<div style="text-align:center; padding: 2rem; color: #94a3b8;">🛒 Keranjang Masih Kosong</div>';
                return;
            }

            let html = '';
            AppState.cart.forEach(item => {
                const price = AppState.priceMode === 'harga_grosir' ? item.harga_grosir : item.harga_umum;
                html += `
                <div class="cart-item">
                    <div>
                        <div style="font-weight:600; font-size:0.875rem;">${item.name}</div>
                        <div style="font-size:0.8125rem; color:#4f46e5; font-weight:700;">${formatRp(price)}</div>
                    </div>
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="removeFromCart(${item.id})">-</button>
                        <span style="font-weight:700; width:20px; text-align:center;">${item.qty}</span>
                        <button class="qty-btn" onclick="addToCart(${item.id})">+</button>
                    </div>
                </div>`;
            });

            Elements.cartItemsContainer.innerHTML = html;
        }

        // 4. Save Order Offline (Background Sync PWA approach)
        function saveOrderOffline() {
            if(AppState.cart.length === 0) return alert('Keranjang Kosong!');

            const orderId = 'OFF-' + Date.now();
            const orderPayload = {
                id: orderId,
                customer_id: Elements.customerSelect.value,
                items: AppState.cart,
                total: AppState.cart.reduce((s, i) => s + (i.qty * (AppState.priceMode === 'harga_grosir' ? i.harga_grosir : i.harga_umum)), 0),
                created_at: new Date().toISOString(),
                synced: false
            };

            // Save to LocalStorage queue
            let pendingOrders = JSON.parse(localStorage.getItem('pwa_pending_orders') || '[]');
            pendingOrders.push(orderPayload);
            localStorage.setItem('pwa_pending_orders', JSON.stringify(pendingOrders));

            // Clear Cart
            AppState.cart = [];
            toggleCartModal();
            updateUI();

            // Notify
            const t = document.getElementById('sync-toast');
            t.textContent = "✅ Pesanan Tersimpan di HP (Draft)";
            t.style.background = "#0ea5e9";
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3000);

            // Attempt bg sync if online
            if(navigator.onLine) {
                forceSync();
            }
        }

        // Search logic
        Elements.searchInput.addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase();
            const filtered = AppState.products.filter(p => p.name.toLowerCase().includes(q) || p.kode_produk?.toLowerCase().includes(q));
            renderProducts(filtered);
        });

        // Customer Type logic
        Elements.customerSelect.addEventListener('change', (e) => {
            const selectedOpt = e.target.options[e.target.selectedIndex];
            const type = selectedOpt.getAttribute('data-type');
            AppState.priceMode = (type === 'grosir') ? 'harga_grosir' : 'harga_umum';
            updateUI(); // recompute prices
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', initApp);

    </script>
</x-mobile-layout>
