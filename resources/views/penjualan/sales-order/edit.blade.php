<x-app-layout>
    <x-slot name="header">
        Edit Sales Order #{{ $salesOrder->so_number }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6">
                <!-- Header Component for styling -->
                <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Edit Sales Order: {{ $salesOrder->so_number }}</h2>
                    <p class="text-sm text-gray-500">Perbarui informasi pesanan atau daftar barang.</p>
                </div>

                <form action="{{ route('sales-order.update', $salesOrder->id) }}" method="POST" id="soForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Left Col: Customer & Dates -->
                        <div class="space-y-4">
                            <div>
                                <label for="customer_id" class="form-label">Pelanggan <span class="text-red-500">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-input mt-1" required>
                                    <option value="">Pilih Pelanggan...</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $salesOrder->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} {{ $customer->phone ? ' - '.$customer->phone : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('customer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="order_date" class="form-label">Tanggal Order <span class="text-red-500">*</span></label>
                                    <input type="date" name="order_date" id="order_date" value="{{ old('order_date', $salesOrder->order_date) }}" class="form-input mt-1" required>
                                    @error('order_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="delivery_date" class="form-label">Tanggal Kirim</label>
                                    <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $salesOrder->delivery_date) }}" class="form-input mt-1">
                                    @error('delivery_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Col: Status & Notes -->
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="form-input mt-1" required>
                                    <option value="draft" {{ old('status', $salesOrder->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="confirmed" {{ old('status', $salesOrder->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ old('status', $salesOrder->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ old('status', $salesOrder->status) == 'completed' ? 'selected' : '' }}>Completed (Selesai/Dikirim)</option>
                                    <option value="cancelled" {{ old('status', $salesOrder->status) == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" id="notes" rows="3" class="form-input mt-1">{{ old('notes', $salesOrder->notes) }}</textarea>
                                @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Daftar Barang</h3>
                            <button type="button" class="btn-sm btn-primary" onclick="window.openProductModal()">+ Tambah Barang</button>
                        </div>
                        
                        <div class="table-wrapper border border-gray-200 dark:border-gray-700 rounded-lg">
                            <table class="data-table" id="itemsTable">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="w-10">#</th>
                                        <th>Nama Barang</th>
                                        <th class="w-32">Harga (Rp)</th>
                                        <th class="w-24">QTY</th>
                                        <th class="w-32">Subtotal</th>
                                        <th class="w-16 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="emptyRow" style="display: none;">
                                        <td colspan="6" class="text-center py-6 text-gray-500 bg-white dark:bg-gray-900 border-b-0">
                                            Belum ada barang yang ditambahkan. Klik tombol "Tambah Barang".
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="border-t-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <td colspan="4" class="font-bold text-right py-3 pr-4">TOTAL KESELURUHAN:</td>
                                        <td class="font-bold text-lg text-indigo-600 dark:text-indigo-400">
                                            Rp <span id="grandTotalLabel">0</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold mt-1" id="itemsSummaryLabel">0 item • Qty 0</div>
                                            <input type="hidden" name="total_amount" id="total_amount" value="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @error('items') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                    

                    <!-- Submit Actions -->
                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('sales-order.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary" id="btnSubmit">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Search Modal (Reused) -->
    <div id="productModal" class="fixed inset-0 z-[100] hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden transition-all transform scale-95 opacity-0" id="productModalContent">
            
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Cari Barang</h3>
                <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="Ketik nama atau kode barang..." autocomplete="off">
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-2" id="searchResults">
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Mulai ketik di atas untuk mencari barang...
                </div>
            </div>
            
        </div>
    </div>

    <script type="application/json" id="so-existing-items-json">{{ json_encode($existingItemsForJs ?? [], JSON_UNESCAPED_UNICODE) }}</script>
    <script type="application/json" id="so-old-items-json">{{ json_encode($oldItemsForJs ?? [], JSON_UNESCAPED_UNICODE) }}</script>
    <!-- Scripts for SO Form -->
    <script>
        let orderItems = [];

        (function(){
            try {
                var exEl = document.getElementById('so-existing-items-json');
                var ex = JSON.parse(exEl ? (exEl.textContent || '[]') : '[]');
                orderItems = (ex || []).map(function(it){
                    var price = Number(it.price || 0);
                    var qty = Number(it.quantity || 1);
                    return {
                        id: Number(it.product_id),
                        name: String(it.name || ('Barang (ID: '+it.product_id+')')),
                        price: price,
                        qty: qty,
                        conversions: Array.isArray(it.conversions) ? it.conversions : [],
                        subtotal: price * qty
                    };
                });
                var oldEl = document.getElementById('so-old-items-json');
                var oldItems = JSON.parse(oldEl ? (oldEl.textContent || '[]') : '[]');
                if (Array.isArray(oldItems) && oldItems.length) {
                    orderItems = [];
                    oldItems.forEach(function(item){
                        var price = Number(item.price || 0);
                        var qty = Number(item.quantity || 1);
                        orderItems.push({
                            id: Number(item.product_id),
                            name: String(item.name || ("Barang (ID: "+item.product_id+")")),
                            price: price,
                            qty: qty,
                            conversions: Array.isArray(item.conversions) ? item.conversions : [],
                            subtotal: price * qty
                        });
                    });
                }
            } catch(e) {}
        })();

        function formatCurrency(num) {
            return new Intl.NumberFormat('id-ID').format(Math.round(num));
        }

        // --- Modal Logic ---
        function window_openProductModal() {
            const modal = document.getElementById('productModal');
            const content = document.getElementById('productModalContent');
            const input = document.getElementById('searchInput');
            
            modal.classList.remove('hidden');
            void modal.offsetWidth; // force reflow
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            
            setTimeout(() => input.focus(), 100);
        }
        window.openProductModal = window_openProductModal;

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            const content = document.getElementById('productModalContent');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('searchInput').value = '';
                document.getElementById('searchResults').innerHTML = '<div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">Mulai ketik di atas untuk mencari barang...</div>';
            }, 200);
        }

        // --- Search Logic ---
        let searchTimeout = null;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.trim();
            const resultsContainer = document.getElementById('searchResults');
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                resultsContainer.innerHTML = '<div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">Ketik minimal 2 karakter...</div>';
                return;
            }
            
            resultsContainer.innerHTML = '<div class="p-8 text-center text-indigo-500 text-sm font-medium">Mencari Data...</div>';
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales-order.products.search') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        window.latestSoSearchResults = Array.isArray(data) ? data : [];
                        if(data.length === 0) {
                            resultsContainer.innerHTML = '<div class="p-8 text-center text-red-500 text-sm">Barang tidak ditemukan.</div>';
                            return;
                        }
                        
                        let html = '<ul class="divide-y divide-gray-100 dark:divide-gray-700">';
                        data.forEach(item => {
                            const byWh = Array.isArray(item.stocks_by_warehouse) ? item.stocks_by_warehouse.slice(0,3) : [];
                            const whNote = byWh.length ? ' • ' + byWh.map(r => `${r.warehouse}: ${r.stock}`).join(' | ') : '';
                            html += `
                                <li>
                                    <button type="button" onclick="selectProduct(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price})" 
                                        class="w-full text-left p-4 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:bg-indigo-50 dark:focus:bg-indigo-900 transition-colors flex justify-between items-center group">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">${item.name}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${item.barcode || '-'} • Stok: ${item.stock || 0}${whNote}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                                Rp ${formatCurrency(item.price)}
                                            </span>
                                            <div class="mt-1 text-xs text-indigo-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">Pilih ➔</div>
                                        </div>
                                    </button>
                                </li>
                            `;
                        });
                        html += '</ul>';
                        resultsContainer.innerHTML = html;
                    })
                    .catch(err => {
                        resultsContainer.innerHTML = '<div class="p-8 text-center text-red-500 text-sm">Gagal mengambil data. Error: '+err.message+'</div>';
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
                } catch(e){}
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

        window.updateQty = function(index, newQty) {
            const val = parseInt(newQty);
            if (isNaN(val) || val < 1) {
                orderItems[index].qty = 1;
            } else {
                orderItems[index].qty = val;
            }
            orderItems[index].subtotal = orderItems[index].qty * orderItems[index].price;
            renderTable();
        }

        window.updatePrice = function(index, newPrice) {
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
                html += `
                    <tr class="item-row bg-white border-b dark:bg-gray-900 dark:border-gray-800">
                        <td class="text-center text-sm font-medium border-b-0">${index + 1}</td>
                        <td class="border-b-0 font-medium text-gray-800 dark:text-gray-200">
                            ${item.name}
                            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        </td>
                        <td class="border-b-0">
                            <input type="number" name="items[${index}][price]" value="${item.price}" onchange="updatePrice(${index}, this.value)" class="form-input py-1 text-sm bg-gray-50 dark:bg-gray-800" style="width:120px">
                        </td>
                        <td class="border-b-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <input type="number" name="items[${index}][quantity]" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)" class="w-24 form-input py-1 text-sm text-center">
                                <select id="mult-${index}" class="form-input w-18" style="width:64px;height:30px;padding:0 6px;font-size:12px;">
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
                        <td class="border-b-0 text-right font-medium">
                            Rp ${formatCurrency(item.subtotal)}
                        </td>
                        <td class="text-center border-b-0">
                            <button type="button" onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            emptyRow.insertAdjacentHTML('beforebegin', html);
            let totalQty = 0;
            orderItems.forEach(it => totalQty += Number(it.qty||0));
            document.getElementById('grandTotalLabel').innerText = formatCurrency(grandTotal);
            const sumEl = document.getElementById('itemsSummaryLabel');
            if (sumEl) sumEl.textContent = `${orderItems.length} item • Qty ${totalQty}`;
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

        // initial render
        renderTable();
    </script>
</x-app-layout>
