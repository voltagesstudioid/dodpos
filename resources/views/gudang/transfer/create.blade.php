<x-app-layout>
    <x-slot name="header">Buat Transfer Stok</x-slot>

    <div class="page-container" style="max-width:1100px; width:100%;">

        {{-- Header --}}
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.75rem; margin-bottom:1.25rem; flex-wrap:wrap;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:800; color:#0f172a; margin:0; display:flex; align-items:center; gap:0.5rem;">
                    <span style="background:#dbeafe; padding:0.35rem 0.5rem; border-radius:8px;">🔄</span> Form Transfer Stok Antar Gudang
                </h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.3rem 0 0;">Pindahkan stok dari satu gudang ke gudang lain. Stok global tidak berubah.</p>
            </div>
            <a href="{{ route('gudang.transfer') }}" class="btn-secondary" style="font-size:0.875rem;">← Riwayat</a>
        </div>

        @if(session('error')) <div class="alert alert-danger" style="margin-bottom:1rem;">❌ {{ session('error') }}</div> @endif
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1rem;">
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin:0.5rem 0 0; padding-left:1.25rem;">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        @endif

        <style>
            .transfer-grid {
                display: grid;
                grid-template-columns: 1fr auto 1fr;
                gap: 1rem;
                margin-bottom: 1rem;
                align-items: stretch;
            }
            .transfer-arrow-container {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 0.5rem;
            }
            .transfer-arrow-icon {
                transition: transform 0.3s;
            }

            .items-head {
                display: grid;
                grid-template-columns: 36px minmax(0, 1fr) 120px 120px 90px;
                gap: 0.625rem;
                align-items: center;
                font-size: 0.72rem;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                margin-bottom: 0.5rem;
                padding: 0 0.25rem;
            }

            .transfer-item-row {
                display: grid;
                grid-template-columns: 36px minmax(0, 1fr) 120px 120px 90px;
                gap: 0.625rem;
                align-items: center;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                padding: 0.625rem;
            }

            .transfer-item-index {
                width: 26px;
                height: 26px;
                border-radius: 50%;
                background: #eef2ff;
                color: #4f46e5;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                font-weight: 700;
            }

            .btn-remove-item {
                border: 1px solid #fecaca;
                background: #fff1f2;
                color: #be123c;
                border-radius: 8px;
                height: 40px;
                font-weight: 600;
                cursor: pointer;
            }

            .btn-remove-item:hover {
                background: #ffe4e6;
            }

            .items-footer {
                margin-top: 0.625rem;
                display: flex;
                justify-content: flex-start;
            }

            @media (max-width: 1024px) {
                .transfer-item-row,
                .items-head {
                    grid-template-columns: 30px minmax(0, 1fr) 100px 100px 84px;
                }
            }

            @media (max-width: 900px) {
                .page-container {
                    max-width: 100% !important;
                }
            }

            @media (max-width: 768px) {
                .transfer-grid {
                    grid-template-columns: 1fr;
                }
                .transfer-arrow-container {
                    padding: 1rem 0;
                }
                .transfer-arrow-icon {
                    transform: rotate(90deg);
                }

                .items-head {
                    display: none;
                }

                .transfer-item-row {
                    grid-template-columns: 1fr;
                    gap: 0.5rem;
                    padding: 0.75rem;
                }

                .card {
                    padding: 1rem !important;
                }

                .transfer-item-row .item-product,
                .transfer-item-row .item-unit,
                .transfer-item-row .item-qty,
                .transfer-item-row .item-action {
                    width: 100%;
                }

                .transfer-item-index {
                    margin-bottom: 0.125rem;
                }

                .btn-remove-item {
                    width: 100%;
                }

                .items-footer .btn-secondary {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>

        <form method="POST" action="{{ route('gudang.transfer.store') }}">
            @csrf

            {{-- Referensi & Barang --}}
            <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <span style="width:24px; height:24px; background:#6366f1; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0;">1</span>
                    <span style="font-weight:700; color:#334155; font-size:1.05rem;">Data Referensi & Barang Ditarik</span>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:1rem;">
                    <div class="form-group" style="flex: 1 1 250px;">
                        <label class="form-label">No. Dokumen Transfer <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="reference_number" value="{{ old('reference_number', 'TRF-'.date('Ymd-His')) }}" class="form-input @error('reference_number') input-error @enderror" required style="font-family:monospace; background-color:#f8fafc;" readonly>
                        @error('reference_number') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" style="flex: 2 1 300px;">
                        <label class="form-label">Daftar Barang yang Ditransfer <span style="color:#ef4444;">*</span></label>
                        <div class="items-head">
                            <div>#</div>
                            <div>Produk</div>
                            <div>Satuan</div>
                            <div>Qty</div>
                            <div>Aksi</div>
                        </div>
                        <div id="transfer-items-wrap" style="display:flex; flex-direction:column; gap:0.625rem;">
                            @php
                                $oldItems = old('items', [['product_id' => '', 'quantity' => '']]);
                            @endphp
                            @foreach($oldItems as $idx => $oldItem)
                                <div class="transfer-item-row">
                                    <div class="transfer-item-index">{{ $idx + 1 }}</div>
                                    <div class="item-product">
                                        <select name="items[{{ $idx }}][product_id]" class="form-input" required>
                                            <option value="">-- Pilih Barang (Stok > 0) --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}" {{ (string)($oldItem['product_id'] ?? '') === (string)$p->id ? 'selected' : '' }}>
                                                    {{ $p->sku }} – {{ $p->name }} (Total Stok: {{ ($maskStock ?? false) ? 'Terkunci' : $p->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="item-unit">
                                        <select name="items[{{ $idx }}][unit_factor]" class="form-input transfer-unit-select" required>
                                            <option value="1" data-label="Satuan Dasar" {{ (string)($oldItem['unit_factor'] ?? '1') === '1' ? 'selected' : '' }}>Satuan Dasar</option>
                                        </select>
                                        <input type="hidden" name="items[{{ $idx }}][unit_label]" value="{{ $oldItem['unit_label'] ?? 'Satuan Dasar' }}" class="transfer-unit-label">
                                    </div>
                                    <div class="item-qty">
                                        <input type="number" min="0.0001" step="any" name="items[{{ $idx }}][quantity]" value="{{ $oldItem['quantity'] ?? '' }}" class="form-input transfer-qty-input" placeholder="Qty" required>
                                        <small class="qty-preview" style="display:block; margin-top:4px; color:#64748b;">= 0 satuan dasar</small>
                                    </div>
                                    <button type="button" class="item-action btn-remove-item remove-item-btn" onclick="removeTransferItem(this)">Hapus</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="items-footer">
                            <button type="button" class="btn-secondary btn-sm" onclick="addTransferItem()">+ Tambah Barang</button>
                        </div>
                        @error('items') <div class="form-error">{{ $message }}</div> @enderror
                        @error('items.*.product_id') <div class="form-error">{{ $message }}</div> @enderror
                        @error('items.*.unit_factor') <div class="form-error">{{ $message }}</div> @enderror
                        @error('items.*.quantity') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Dari → Ke --}}
            <div class="transfer-grid">

                {{-- Asal --}}
                <div class="card shadow-sm" style="padding:1.5rem; border-top:4px solid #f43f5e; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem;">
                        <span style="font-size:1.2rem; background:#ffe4e6; padding:8px; border-radius:8px;">📤</span>
                        <span style="font-weight:700; color:#be123c; font-size:1.1rem;">Gudang Asal (Pengirim)</span>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Dari Gudang <span style="color:#ef4444;">*</span></label>
                        <select name="from_warehouse_id" id="from_warehouse_id" class="form-input @error('from_warehouse_id') input-error @enderror" required>
                            <option value="">-- Pilih Gudang Asal --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('from_warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('from_warehouse_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Arrow --}}
                <div class="transfer-arrow-container">
                    <div style="background:#e0e7ff; border-radius:50%; width:48px; height:48px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 6px -1px rgba(99,102,241,0.2);">
                        <svg class="transfer-arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    </div>
                </div>

                {{-- Tujuan --}}
                <div class="card shadow-sm" style="padding:1.5rem; border-top:4px solid #10b981; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem;">
                        <span style="font-size:1.2rem; background:#dcfce7; padding:8px; border-radius:8px;">📥</span>
                        <span style="font-weight:700; color:#065f46; font-size:1.1rem;">Gudang Tujuan (Penerima)</span>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Ke Gudang <span style="color:#ef4444;">*</span></label>
                        <select name="to_warehouse_id" id="to_warehouse_id" class="form-input @error('to_warehouse_id') input-error @enderror" required>
                            <option value="">-- Pilih Gudang Tujuan --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('to_warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('to_warehouse_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Qty & Notes --}}
            <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid #f1f5f9;">
                    <span style="width:24px; height:24px; background:#6366f1; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; flex-shrink:0;">3</span>
                    <span style="font-weight:700; color:#334155; font-size:1.05rem;">Rincian Transfer & Finalisasi</span>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:1.5rem;">
                    <div class="form-group" style="flex: 1 1 200px;">
                        <label class="form-label">Jumlah per Item <span style="color:#ef4444;">*</span></label>
                        <div class="form-input" style="background:#f8fafc; color:#475569; display:flex; align-items:center; min-height:46px;">
                            Diisi pada masing-masing baris barang
                        </div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:0.35rem; display:flex; align-items:center; gap:0.25rem;">
                            <span style="color:#eab308;">⚡</span> Stok ditarik FIFO per barang
                        </div>
                    </div>
                    <div class="form-group" style="flex: 2 1 300px;">
                        <label class="form-label">Catatan Tambahan (Alasan/Keterangan)</label>
                        <textarea name="notes" rows="2" class="form-input" placeholder="Opsional, misal: Permintaan darurat toko B..." style="resize:vertical;">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <a href="{{ route('gudang.transfer') }}" class="btn-secondary">Batalkan</a>
                <button type="submit" class="btn-primary">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.35rem;"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    Proses Transfer
                </button>
            </div>
        </form>
    </div>

    <script>
        function addTransferItem() {
            const wrap = document.getElementById('transfer-items-wrap');
            const index = wrap.querySelectorAll('.transfer-item-row').length;
            const row = document.createElement('div');
            row.className = 'transfer-item-row';

            const productsHtml = `@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->sku }} – {{ $p->name }} (Total Stok: {{ $p->stock }})</option>@endforeach`;

            row.innerHTML = `
                <div class="transfer-item-index">${index + 1}</div>
                <div class="item-product">
                    <select name="items[${index}][product_id]" class="form-input transfer-product-select" required>
                        <option value="">-- Pilih Barang (Stok > 0) --</option>
                        ${productsHtml}
                    </select>
                </div>
                <div class="item-unit">
                    <select name="items[${index}][unit_factor]" class="form-input transfer-unit-select" required>
                        <option value="1" data-label="Satuan Dasar">Satuan Dasar</option>
                    </select>
                    <input type="hidden" name="items[${index}][unit_label]" value="Satuan Dasar" class="transfer-unit-label">
                </div>
                <div class="item-qty">
                    <input type="number" min="0.0001" step="any" name="items[${index}][quantity]" class="form-input transfer-qty-input" placeholder="Qty" required>
                    <small class="qty-preview" style="display:block; margin-top:4px; color:#64748b;">= 0 satuan dasar</small>
                </div>
                <button type="button" class="item-action btn-remove-item remove-item-btn" onclick="removeTransferItem(this)">Hapus</button>
            `;
            wrap.appendChild(row);
        }

        function removeTransferItem(btn) {
            const wrap = document.getElementById('transfer-items-wrap');
            const rows = wrap.querySelectorAll('.transfer-item-row');
            if (rows.length <= 1) {
                alert('Minimal 1 barang wajib diisi.');
                return;
            }
            btn.closest('.transfer-item-row').remove();
            reindexTransferItems();
        }

        function reindexTransferItems() {
            const rows = document.querySelectorAll('#transfer-items-wrap .transfer-item-row');
            rows.forEach((row, idx) => {
                const productSelect = row.querySelector('select');
                const qtyInput = row.querySelector('.transfer-qty-input');
                const unitSelect = row.querySelector('.transfer-unit-select');
                const unitLabelInput = row.querySelector('.transfer-unit-label');
                const indexBadge = row.querySelector('.transfer-item-index');
                if (productSelect) productSelect.name = `items[${idx}][product_id]`;
                if (unitSelect) unitSelect.name = `items[${idx}][unit_factor]`;
                if (unitLabelInput) unitLabelInput.name = `items[${idx}][unit_label]`;
                if (qtyInput) qtyInput.name = `items[${idx}][quantity]`;
                if (indexBadge) indexBadge.textContent = idx + 1;
            });
        }

        </script>
        <script id="transfer-stocks-json" type="application/json">{!! json_encode(
            $products->mapWithKeys(function($p){
                $grouped = $p->productStocks->groupBy('warehouse_id')->map->sum('stock');
                return [$p->id => $grouped];
            })->toArray()
        , JSON_UNESCAPED_UNICODE) !!}</script>
        <script id="transfer-units-json" type="application/json">{!! json_encode(
            $products->mapWithKeys(function($p){
                $units = $p->unitConversions->map(function($conv){
                    return ['factor' => (int) $conv->conversion_factor, 'label' => $conv->unit?->name ?? 'Satuan'];
                })->values()->toArray();
                $units[] = ['factor' => 1, 'label' => $p->unit?->name ?? 'Satuan Dasar'];
                return [$p->id => $units];
            })->toArray()
        , JSON_UNESCAPED_UNICODE) !!}</script>
        <script>
        const productStocksData = (function(){
            const el = document.getElementById('transfer-stocks-json');
            try { return JSON.parse(el?.textContent || '{}'); } catch(_) { return {}; }
        })();
        const productUnitsData = (function(){
            const el = document.getElementById('transfer-units-json');
            try { return JSON.parse(el?.textContent || '{}'); } catch(_) { return {}; }
        })();

        function uniqueUnits(units) {
            const map = new Map();
            (units || []).forEach(u => {
                const key = `${u.factor}-${u.label}`;
                if (!map.has(key)) map.set(key, u);
            });
            return Array.from(map.values()).sort((a, b) => b.factor - a.factor);
        }

        function refreshUnitOptionsForRow(row) {
            const productSelect = row.querySelector('.transfer-product-select, select[name*="[product_id]"]');
            const unitSelect = row.querySelector('.transfer-unit-select');
            const unitLabelInput = row.querySelector('.transfer-unit-label');
            if (!productSelect || !unitSelect) return;

            const productId = productSelect.value;
            const units = uniqueUnits(productUnitsData[productId] || [{ factor: 1, label: 'Satuan Dasar' }]);

            unitSelect.innerHTML = '';
            units.forEach((u, idx) => {
                const opt = document.createElement('option');
                opt.value = String(u.factor);
                opt.textContent = `${u.label} (x${u.factor})`;
                opt.dataset.label = u.label;
                if (idx === 0) opt.selected = true;
                unitSelect.appendChild(opt);
            });

            if (unitLabelInput) {
                const selected = unitSelect.options[unitSelect.selectedIndex];
                unitLabelInput.value = selected ? (selected.dataset.label || selected.textContent) : 'Satuan Dasar';
            }

            updateQtyPreview(row);
        }

        function updateQtyPreview(row) {
            const qtyInput = row.querySelector('.transfer-qty-input');
            const unitSelect = row.querySelector('.transfer-unit-select');
            const preview = row.querySelector('.qty-preview');
            const unitLabelInput = row.querySelector('.transfer-unit-label');

            if (!qtyInput || !unitSelect || !preview) return;

            const qty = Number(qtyInput.value || 0);
            const factor = Number(unitSelect.value || 1);
            const baseQty = qty * factor;

            const selected = unitSelect.options[unitSelect.selectedIndex];
            if (unitLabelInput && selected) {
                unitLabelInput.value = selected.dataset.label || selected.textContent || 'Satuan Dasar';
            }

            preview.textContent = `= ${Number.isFinite(baseQty) ? baseQty : 0} satuan dasar`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fromWarehouseSelect = document.querySelector('select[name="from_warehouse_id"]');
            const itemWrap = document.getElementById('transfer-items-wrap');

            const originalFromOptions = Array.from(fromWarehouseSelect.options).map(opt => ({
                value: opt.value,
                originalText: opt.text,
                selected: opt.selected
            }));

            function getAvailableByWarehouse(warehouseId) {
                const selects = itemWrap.querySelectorAll('select[name*="[product_id]"]');
                let total = 0;
                selects.forEach(sel => {
                    const pId = sel.value;
                    if (!pId || !productStocksData[pId]) return;
                    total += Number(productStocksData[pId][warehouseId] || 0);
                });
                return total;
            }

            function refreshFromWarehouseOptions() {
                const currentValue = fromWarehouseSelect.value;
                fromWarehouseSelect.innerHTML = '';
                originalFromOptions.forEach(opt => {
                    const newOpt = document.createElement('option');
                    newOpt.value = opt.value;

                    if (opt.value === '') {
                        newOpt.text = opt.originalText;
                    } else {
                        const available = getAvailableByWarehouse(opt.value);
                        newOpt.text = `${opt.originalText} (Total tersedia: ${available})`;
                        if (available <= 0) {
                            newOpt.disabled = true;
                        }
                    }

                    if (opt.value === currentValue && !newOpt.disabled) {
                        newOpt.selected = true;
                    } else if (!currentValue && opt.selected && !newOpt.disabled) {
                        newOpt.selected = true;
                    }

                    fromWarehouseSelect.appendChild(newOpt);
                });
            }

            itemWrap.addEventListener('change', function(e) {
                if (!e.target) return;

                if (e.target.matches('select[name*="[product_id]"], .transfer-product-select')) {
                    const row = e.target.closest('.transfer-item-row');
                    if (row) refreshUnitOptionsForRow(row);
                    refreshFromWarehouseOptions();
                }

                if (e.target.matches('.transfer-unit-select')) {
                    const row = e.target.closest('.transfer-item-row');
                    if (row) updateQtyPreview(row);
                }
            });

            itemWrap.addEventListener('input', function(e) {
                if (e.target && e.target.matches('.transfer-qty-input')) {
                    const row = e.target.closest('.transfer-item-row');
                    if (row) updateQtyPreview(row);
                }
            });

            document.querySelectorAll('#transfer-items-wrap .transfer-item-row').forEach(row => {
                refreshUnitOptionsForRow(row);
            });

            refreshFromWarehouseOptions();
        });

        (function(){
            var sp = new URLSearchParams(window.location.search);
            var fromId = sp.get('from_warehouse_id');
            var toId = sp.get('to_warehouse_id');
            var notes = sp.get('notes');
            function setVal(sel, val){
                var el = document.querySelector(sel);
                if(el && val){ el.value = val; }
            }
            setVal('select[name="from_warehouse_id"]', fromId);
            setVal('select[name="to_warehouse_id"]', toId);
            setVal('textarea[name="notes"]', notes);
            if(!toId){
                var toSel = document.querySelector('select[name="to_warehouse_id"]');
                if(toSel){
                    var options = Array.from(toSel.options);
                    var q = options.find(function(opt){ return /karantina/i.test(opt.textContent || ''); });
                    if(q && !toSel.value){ toSel.value = q.value; }
                }
            }
        })();
    </script>
</x-app-layout>
