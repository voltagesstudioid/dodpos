<x-app-layout>
    <x-slot name="header">Buat Permintaan Barang</x-slot>

    <style>
        /* Global Styles for this page */
        .request-page {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
        }
        .back-link:hover { color: #0f172a; }
        .form-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .header-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .header-text h1 {
            font-size: 1.375rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.25rem 0;
        }
        .header-text p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }
        .card-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        .form-label .required {
            color: #ef4444;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem;
            background: #f8fafc;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .form-input:focus, .form-select:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .form-input.error, .form-select.error {
            border-color: #ef4444;
            background: #fef2f2;
        }
        .form-error {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 0.25rem;
            font-weight: 600;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        .card-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-secondary {
            background: #ffffff;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .btn-secondary:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
        }
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        }
        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .alert-success {
            background: #dcfce7;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        .alert-icon {
            flex-shrink: 0;
        }
        .alert-list {
            margin: 0.5rem 0 0 1.25rem;
            padding: 0;
        }
        .alert-list li {
            margin-bottom: 0.25rem;
        }
        .warehouse-group {
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-top: 0.5rem;
            display: none;
        }
        .warehouse-group.visible {
            display: block;
        }
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .card-footer {
                flex-direction: column-reverse;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="request-page">
        <a href="{{ route('gudang.request.index') }}" class="back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            Kembali ke Daftar Permintaan
        </a>

        <!-- Alerts -->
        @if(session('error') || $errors->any())
            <div class="alert alert-danger">
                <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    @if(session('error'))
                        <strong>{{ session('error') }}</strong>
                    @endif
                    @if($errors->any())
                        <strong>Terdapat kesalahan input:</strong>
                        <ul class="alert-list">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="form-card">
            <div class="card-header">
                <div class="header-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <div class="header-text">
                    <h1>Form Pengajuan Barang</h1>
                    <p>Pilih produk yang menipis dan jenis permintaannya untuk ditinjau oleh Supervisor.</p>
                </div>
            </div>

            <form action="{{ route('gudang.request.store') }}" method="POST" id="requestForm">
                @csrf
                <div class="card-body">
                    <!-- Product Selection -->
                    <div class="form-group">
                        <label class="form-label">Pilih Produk <span class="required">*</span></label>
                        <input type="text" id="productSearch" class="form-input" placeholder="Cari produk berdasarkan nama atau SKU...">
                        <select name="product_id" id="productSelect" class="form-select @error('product_id') error @enderror" required autofocus style="margin-top: 0.5rem;">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" data-name="{{ strtolower($p->name) }}" data-sku="{{ strtolower($p->sku ?? '') }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->sku ? "[$p->sku] " : '' }}{{ $p->name }} (Sisa Stok: {{ $p->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type & Unit -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Permintaan <span class="required">*</span></label>
                            <select name="type" id="typeSelect" class="form-select @error('type') error @enderror" required>
                                <option value="po" {{ old('type') == 'po' ? 'selected' : '' }}>Purchase Order (Beli Baru)</option>
                                <option value="transfer" {{ old('type') == 'transfer' ? 'selected' : '' }}>Transfer (Minta dari Cabang)</option>
                            </select>
                            @error('type')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Satuan <span class="text-muted font-normal">(Opsional)</span></label>
                            <select name="unit_id" id="unitSelect" class="form-select @error('unit_id') error @enderror">
                                <option value="">-- Satuan Dasar --</option>
                                @foreach($units ?? [] as $u)
                                    <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Warehouse Destination (only for transfer) -->
                    <div class="warehouse-group" id="warehouseGroup">
                        <label class="form-label">Gudang Tujuan <span class="required">*</span></label>
                        <select name="to_warehouse_id" id="toWarehouseSelect" class="form-select @error('to_warehouse_id') error @enderror">
                            <option value="">-- Pilih Gudang Tujuan --</option>
                            @foreach(($warehouses ?? []) as $wh)
                                <option value="{{ $wh->id }}" {{ (string) old('to_warehouse_id') === (string) $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('to_warehouse_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Jumlah (Qty) <span class="required">*</span></label>
                        <input type="number" name="quantity" id="quantityInput" class="form-input @error('quantity') error @enderror" required min="1" value="{{ old('quantity') }}" placeholder="Contoh: 50">
                        @error('quantity')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label class="form-label">Catatan / Alasan</label>
                        <textarea name="notes" id="notesInput" rows="3" class="form-input form-textarea @error('notes') error @enderror" placeholder="Contoh: Stok display toko sudah habis total, butuh segera dikirim...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('gudang.request.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elements
            const form = document.getElementById('requestForm');
            const submitBtn = document.getElementById('submitBtn');
            const searchInput = document.getElementById('productSearch');
            const productSelect = document.getElementById('productSelect');
            const typeSelect = document.getElementById('typeSelect');
            const warehouseGroup = document.getElementById('warehouseGroup');
            const toWarehouseSelect = document.getElementById('toWarehouseSelect');

            // 1. Product Search Logic
            const allProducts = Array.from(productSelect.options).map(option => ({
                id: option.value,
                name: option.getAttribute('data-name') || '',
                sku: option.getAttribute('data-sku') || '',
                text: option.textContent,
                selected: option.selected
            }));

            function renderProducts(searchTerm = '') {
                const filtered = allProducts.filter(product => {
                    if (product.id === '') return true;
                    const term = searchTerm.toLowerCase().trim();
                    return product.name.includes(term) || product.sku.includes(term);
                });
                productSelect.innerHTML = '';
                filtered.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.text;
                    option.selected = product.selected;
                    option.setAttribute('data-name', product.name);
                    option.setAttribute('data-sku', product.sku);
                    productSelect.appendChild(option);
                });
            }
            if (searchInput) {
                searchInput.addEventListener('input', function() { renderProducts(this.value); });
            }

            // 2. Type Select Logic
            function updateWarehouseGroup() {
                const isTransfer = typeSelect.value === 'transfer';
                warehouseGroup.classList.toggle('visible', isTransfer);
                if (toWarehouseSelect) {
                    toWarehouseSelect.required = isTransfer;
                    if (!isTransfer) toWarehouseSelect.value = '';
                }
            }
            typeSelect.addEventListener('change', updateWarehouseGroup);
            updateWarehouseGroup(); // Initial call

            // 3. Form Submit Logic
            if (form && submitBtn) {
                form.addEventListener('submit', function () {
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner"></span> Mengirim...';
                    }, 10);
                });
            }
        });
    </script>
</x-app-layout>
