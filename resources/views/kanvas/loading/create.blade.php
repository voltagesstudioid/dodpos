<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Loading Armada Kanvas') }}
    </h2>
</x-slot>

<div class="content-body">
    <div class="header-section">
        <div class="breadcrumb">
            <a href="{{ route('kanvas.loading.index') }}">Loading</a> / Buat SJ Baru
        </div>
        <h1 class="page-title">Proses Loading Pagi</h1>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('kanvas.loading.store') }}" method="POST" id="loadingForm">
        @csrf
        <div class="row">
            <!-- Pilihan Sales -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white"><h5 class="mb-0">Pilih Sales Kanvas</h5></div>
                    <div class="card-body">
                        <label class="form-label">Tujuan Mobil / Sales</label>
                        <select name="sales_id" class="form-select" required>
                            <option value="">-- Pilih Tim Sales --</option>
                            @foreach($salesList as $sales)
                                <option value="{{ $sales->id }}">{{ $sales->name }}</option>
                            @endforeach
                        </select>
                        <hr>
                        <h6 class="text-primary mt-3"><i class="fas fa-box"></i> Keranjang Loading (<span id="cartCount">0</span>)</h6>
                        <div id="cartPreview" class="small mt-2 p-2 bg-light rounded" style="min-height: 100px; max-height: 250px; overflow-y: auto;">
                            <em class="text-muted">Belum ada barang dipilih...</em>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List Barang Gudang (AJAX Live Search) -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Scan / Cari Barang</h5>
                        <input type="text" id="barcodeScanner" class="form-control form-control-sm w-50" placeholder="Scan Barcode / Ketik Nama Item..." autofocus autocomplete="off">
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover mb-0" id="productTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Pilih</th>
                                    <th>Produk & Satuan</th>
                                    <th>Sisa Gudang</th>
                                    <th width="150">Qty Dimuat</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Ketik nama barang atau mulai scan barcode...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="button" onclick="submitForm()" class="btn btn-primary px-4"><i class="fas fa-truck-loading"></i> Proses Loading (Generate SJ)</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hidden Inputs for Submission -->
        <div id="hiddenInputsWrapper"></div>
    </form>
</div>

<script>
    let selectedItems = {};

    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('barcodeScanner');
        const tbody = document.getElementById('productTableBody');
        let typingTimer;
        
        // Fetch first 20 items on load
        fetchItems('');

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => fetchItems(this.value), 300); // 300ms debounce
        });

        function fetchItems(query) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> Mencari data...</td></tr>';
            
            fetch(`{{ route('kanvas.loading.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Barang tidak ditemukan di gudang.</td></tr>';
                        return;
                    }

                    data.forEach(stok => {
                        let prodId = stok.product_id;
                        let isChecked = selectedItems[prodId] ? 'checked' : '';
                        let currentQty = selectedItems[prodId] ? selectedItems[prodId].qty : '';
                        let name = stok.product ? stok.product.name : 'Unknown';
                        let barcode = stok.product ? (stok.product.barcode || '-') : '-';
                        let unit = stok.product ? stok.product.unit : '-';
                        let maxQty = stok.qty_tersedia;

                        let tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input chk-item" type="checkbox" value="${prodId}" id="chk_${prodId}" ${isChecked}>
                                </div>
                            </td>
                            <td>
                                <label for="chk_${prodId}" class="mb-0" style="cursor: pointer;">
                                    <strong>${name}</strong><br>
                                    <small class="text-muted">Barcode: <b>${barcode}</b> | Satuan: <b>${unit}</b></small>
                                </label>
                            </td>
                            <td><span class="badge ${maxQty > 50 ? 'bg-success' : 'bg-danger'}">${maxQty}</span></td>
                            <td>
                                <input type="number" class="form-control form-control-sm qty-input" id="qty_${prodId}" min="1" max="${maxQty}" placeholder="Misal: 10" value="${currentQty}" ${isChecked ? '' : 'disabled'}>
                            </td>
                        `;
                        tbody.appendChild(tr);

                        // Attach Event Listeners
                        const chk = tr.querySelector('.chk-item');
                        const input = tr.querySelector('.qty-input');

                        chk.addEventListener('change', function() {
                            if (this.checked) {
                                input.disabled = false;
                                input.focus();
                                // Initialize selection with qty 1 if empty
                                if(!input.value) input.value = 1;
                                updateCart(prodId, name, input.value);
                            } else {
                                input.disabled = true;
                                input.value = '';
                                removeFromCart(prodId);
                            }
                        });

                        input.addEventListener('input', function() {
                            if (chk.checked) {
                                if(parseInt(this.value) > maxQty) {
                                    alert('Qty melebihi sisa gudang (' + maxQty + ')');
                                    this.value = maxQty;
                                }
                                updateCart(prodId, name, this.value);
                            }
                        });
                        
                        // Fitur Submit Otomatis kalau menembak barcode Scanner (Enter)
                        // Barcode scanner rata-rata menembakkan "Enter" di akhir rentetan karakter
                        input.addEventListener('keypress', function(e) {
                             if(e.key === 'Enter') {
                                 e.preventDefault();
                                 searchInput.value = '';
                                 searchInput.focus();
                             }
                        });
                    });
                });
        }
    });

    function updateCart(id, name, qty) {
        if (!qty || qty <= 0) return;
        selectedItems[id] = { name: name, qty: qty };
        renderCart();
    }

    function removeFromCart(id) {
        delete selectedItems[id];
        renderCart();
    }

    function renderCart() {
        const preview = document.getElementById('cartPreview');
        const count = document.getElementById('cartCount');
        const keys = Object.keys(selectedItems);
        
        count.innerText = keys.length;
        
        if (keys.length === 0) {
            preview.innerHTML = '<em class="text-muted">Belum ada barang dipilih...</em>';
            return;
        }

        let html = '<ul class="ps-3 mb-0 text-indigo">';
        keys.forEach(k => {
            html += `<li><b>${selectedItems[k].qty}</b>x ${selectedItems[k].name}</li>`;
        });
        html += '</ul>';
        preview.innerHTML = html;
    }

    // Karena checkbox bisa hilang dari DOM karena AJAX (hilang saat di search),
    // kita build hidden input saat user klik tombol submit
    function submitForm() {
        if (Object.keys(selectedItems).length === 0) {
            alert('Belum ada barang yang diplih untuk dimuat ke Kanvas!');
            return;
        }

        const form = document.getElementById('loadingForm');
        const wrapper = document.getElementById('hiddenInputsWrapper');
        wrapper.innerHTML = '';
        
        let i = 0;
        for (const [prodId, data] of Object.entries(selectedItems)) {
            let inId = document.createElement('input');
            inId.type = 'hidden';
            inId.name = `items[${i}][product_id]`;
            inId.value = prodId;

            let inQty = document.createElement('input');
            inQty.type = 'hidden';
            inQty.name = `items[${i}][qty]`;
            inQty.value = data.qty;

            wrapper.appendChild(inId);
            wrapper.appendChild(inQty);
            i++;
        }

        form.submit();
    }
</script>
@endsection
