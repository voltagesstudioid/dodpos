<x-app-layout>
    <x-slot name="header">Penyesuaian Stok Gudang</x-slot>

    @push('styles')
    <style>
        .sa-page{max-width:780px;margin:0 auto;padding:1.5rem 1rem 3rem;}
        .sa-hdr{display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;}
        .sa-hdr-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;}
        .sa-hdr h1{font-size:1.35rem;font-weight:800;color:#0f172a;margin:0;}
        .sa-hdr p{font-size:0.8rem;color:#64748b;margin:0.2rem 0 0;}

        .sa-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:1.25rem;overflow:hidden;}
        .sa-card-hdr{padding:0.875rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:0.65rem;}
        .sa-step{background:#eef2ff;color:#4338ca;font-size:0.65rem;font-weight:700;padding:0.2rem 0.55rem;border-radius:999px;text-transform:uppercase;letter-spacing:0.04em;}
        .sa-card-hdr h2{font-size:0.9rem;font-weight:700;color:#0f172a;margin:0;}
        .sa-card-body{padding:1.25rem;}

        /* Type Selector */
        .sa-types{display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;}
        .sa-type{cursor:pointer;}
        .sa-type input{position:absolute;opacity:0;pointer-events:none;}
        .sa-type-box{padding:1rem;border:2px solid #e5e7eb;border-radius:12px;background:#fff;transition:all .2s;display:flex;flex-direction:column;gap:0.5rem;}
        .sa-type input:checked+.sa-type-box{border-color:#6366f1;background:#eef2ff;}
        .sa-type input[value="koreksi"]:checked+.sa-type-box{border-color:#f59e0b;background:#fffbeb;}
        .sa-type-icon{font-size:1.5rem;line-height:1;}
        .sa-type-box h3{font-size:0.85rem;font-weight:700;color:#0f172a;margin:0;}
        .sa-type-box p{font-size:0.75rem;color:#64748b;margin:0;line-height:1.45;}

        /* Info Box */
        .sa-info{margin-top:1rem;padding:0.75rem 1rem;border-radius:10px;font-size:0.78rem;font-weight:500;line-height:1.5;display:none;}
        .sa-info.visible{display:flex;gap:0.65rem;align-items:flex-start;}
        .sa-info.masuk{background:#eef2ff;color:#3730a3;border:1px solid rgba(99,102,241,.15);}
        .sa-info.koreksi{background:#fffbeb;color:#92400e;border:1px solid rgba(245,158,11,.15);}

        /* Form */
        .sa-fg{display:flex;flex-direction:column;gap:0.35rem;margin-bottom:1rem;}
        .sa-fg:last-child{margin-bottom:0;}
        .sa-label{font-size:0.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:0.04em;}
        .sa-label .req{color:#ef4444;}
        .sa-ctrl{width:100%;padding:0.7rem 0.875rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.85rem;font-family:inherit;background:#f8fafc;color:#0f172a;transition:all .2s;box-sizing:border-box;}
        .sa-ctrl:focus{outline:none;border-color:#6366f1;background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,.08);}
        select.sa-ctrl{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.75rem center;padding-right:2rem;cursor:pointer;}
        .sa-hint{font-size:0.7rem;color:#94a3b8;}
        .sa-grid2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}

        /* Searchable Select */
        .sa-search-wrap{position:relative;}
        .sa-search-input{width:100%;padding:0.7rem 2.5rem 0.7rem 0.875rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.85rem;font-family:inherit;background:#f8fafc;color:#0f172a;transition:all .2s;box-sizing:border-box;}
        .sa-search-input:focus{outline:none;border-color:#6366f1;background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,.08);}
        .sa-search-icon{position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;}
        .sa-search-clear{position:absolute;right:2.2rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;font-size:1rem;padding:2px;display:none;}
        .sa-search-clear.visible{display:block;}
        .sa-dropdown{position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1.5px solid #e5e7eb;border-radius:10px;max-height:220px;overflow-y:auto;z-index:50;display:none;box-shadow:0 8px 24px rgba(0,0,0,.08);}
        .sa-dropdown.open{display:block;}
        .sa-dropdown-item{padding:0.6rem 0.875rem;cursor:pointer;font-size:0.82rem;border-bottom:1px solid #f1f5f9;transition:background .1s;}
        .sa-dropdown-item:last-child{border-bottom:none;}
        .sa-dropdown-item:hover{background:#eef2ff;}
        .sa-dropdown-item.selected{background:#eef2ff;font-weight:600;}
        .sa-dropdown-item .sku{font-size:0.7rem;color:#94a3b8;margin-left:4px;}
        .sa-dropdown-empty{padding:1rem;text-align:center;color:#94a3b8;font-size:0.8rem;}
        .sa-selected-tag{display:inline-flex;align-items:center;gap:4px;background:#eef2ff;color:#4338ca;padding:2px 8px;border-radius:6px;font-size:0.75rem;font-weight:600;margin-top:4px;}
        .sa-selected-tag button{background:none;border:none;color:#4338ca;cursor:pointer;font-size:0.85rem;padding:0 2px;}

        /* Unit Selector */
        .sa-unit-row{display:flex;gap:0.75rem;align-items:flex-end;}
        .sa-unit-row .sa-fg{flex:1;}
        .sa-unit-info{padding:0.5rem 0.75rem;background:#f0fdf4;border:1px solid rgba(16,185,129,.15);border-radius:8px;font-size:0.75rem;color:#059669;font-weight:600;display:none;margin-top:0.35rem;}
        .sa-unit-info.visible{display:block;}

        /* Stock Display */
        .sa-stock-box{margin-top:0.5rem;display:none;}
        .sa-stock-box.visible{display:block;}
        .sa-stock-row{display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0.875rem;background:#f0fdf4;border:1px solid rgba(16,185,129,.15);border-radius:10px;margin-bottom:0.35rem;}
        .sa-stock-row.warn{background:#fffbeb;border-color:rgba(245,158,11,.15);}
        .sa-stock-lbl{font-size:0.72rem;color:#64748b;font-weight:600;}
        .sa-stock-val{font-family:'Cascadia Code','Fira Code',monospace;font-size:0.85rem;font-weight:700;color:#059669;}
        .sa-stock-val.warn{color:#d97706;}
        .sa-stock-val.danger{color:#dc2626;}

        /* Preview */
        .sa-preview{margin-top:0.75rem;padding:0.75rem 1rem;background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;display:none;}
        .sa-preview.visible{display:block;}
        .sa-preview-title{font-size:0.7rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.5rem;}
        .sa-preview-flow{display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;}
        .sa-preview-box{text-align:center;padding:0.4rem 0.75rem;border-radius:8px;}
        .sa-preview-box.before{background:#fee2e2;color:#991b1b;}
        .sa-preview-box.after{background:#dcfce7;color:#065f46;}
        .sa-preview-box .lbl{font-size:0.6rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;opacity:0.7;}
        .sa-preview-box .val{font-family:'Cascadia Code','Fira Code',monospace;font-size:1rem;font-weight:800;display:block;margin-top:0.15rem;}
        .sa-preview-arrow{font-size:1.25rem;color:#94a3b8;}
        .sa-preview-diff{font-size:0.78rem;font-weight:700;margin-left:auto;}
        .sa-preview-diff.up{color:#059669;}
        .sa-preview-diff.down{color:#dc2626;}
        .sa-preview-diff.same{color:#64748b;}

        /* Actions */
        .sa-actions{display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1.5rem;}
        .sa-btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.65rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:700;cursor:pointer;transition:all .15s;border:none;text-decoration:none;}
        .sa-btn-cancel{background:#f1f5f9;color:#374151;border:1px solid #e5e7eb;}
        .sa-btn-cancel:hover{background:#e2e8f0;}
        .sa-btn-save{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;box-shadow:0 4px 12px rgba(99,102,241,.3);}
        .sa-btn-save:hover{box-shadow:0 6px 20px rgba(99,102,241,.4);transform:translateY(-1px);}
        .sa-btn-save.amber{background:linear-gradient(135deg,#f59e0b,#d97706);box-shadow:0 4px 12px rgba(245,158,11,.3);}
        .sa-btn-save.amber:hover{box-shadow:0 6px 20px rgba(245,158,11,.4);}

        .sa-err{color:#dc2626;font-size:0.72rem;font-weight:600;margin-top:0.25rem;}
        .sa-alert{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:0.875rem 1.25rem;border-radius:12px;margin-bottom:1.25rem;font-size:0.8rem;font-weight:600;}

        @media(max-width:768px){
            .sa-types{grid-template-columns:1fr;}
            .sa-grid2{grid-template-columns:1fr;}
            .sa-preview-flow{flex-direction:column;align-items:stretch;}
            .sa-preview-diff{margin-left:0;text-align:center;}
        }
    </style>
    @endpush

    <div class="sa-page">
        {{-- Header --}}
        <div class="sa-hdr">
            <div class="sa-hdr-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <div>
                <h1>Penyesuaian Stok Gudang</h1>
                <p>Tambah stok manual atau koreksi stok fisik tanpa Purchase Order.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="sa-alert">
            <ul style="margin:0;padding-left:1.25rem;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('gudang.stock-adjustment.store') }}" id="adjForm" onsubmit="return confirmSubmit()">
            @csrf

            {{-- Card 1: Jenis --}}
            <div class="sa-card">
                <div class="sa-card-hdr">
                    <span class="sa-step">Langkah 1</span>
                    <h2>Jenis Penyesuaian</h2>
                </div>
                <div class="sa-card-body">
                    <div class="sa-types">
                        <label class="sa-type">
                            <input type="radio" name="tipe" value="masuk" {{ old('tipe','masuk')==='masuk'?'checked':'' }} onchange="updateUI()">
                            <div class="sa-type-box">
                                <span class="sa-type-icon">📥</span>
                                <h3>Stok Masuk</h3>
                                <p>Jumlah input <strong>ditambahkan</strong> ke stok saat ini. Untuk sisa event, bonus supplier, dll.</p>
                            </div>
                        </label>
                        <label class="sa-type">
                            <input type="radio" name="tipe" value="koreksi" {{ old('tipe')==='koreksi'?'checked':'' }} onchange="updateUI()">
                            <div class="sa-type-box">
                                <span class="sa-type-icon">⚖️</span>
                                <h3>Koreksi Stok</h3>
                                <p>Jumlah input <strong>menggantikan</strong> stok sistem. Untuk hasil opname / perhitungan fisik.</p>
                            </div>
                        </label>
                    </div>

                    <div class="sa-info masuk" id="infoMasuk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>Contoh: Stok gudang <strong>50</strong>, input <strong>20</strong> → stok akhir = <strong>70</strong>.</div>
                    </div>
                    <div class="sa-info koreksi" id="infoKoreksi">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>Contoh: Stok sistem <strong>50</strong>, hitungan fisik <strong>35</strong> → input <strong>35</strong> agar tersinkron.</div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Produk & Gudang --}}
            <div class="sa-card">
                <div class="sa-card-hdr">
                    <span class="sa-step">Langkah 2</span>
                    <h2>Produk & Gudang</h2>
                </div>
                <div class="sa-card-body">
                    {{-- Merged Search + Select Product --}}
                    <div class="sa-fg">
                        <label class="sa-label">Produk <span class="req">*</span></label>
                        <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}" required>
                        <div class="sa-search-wrap" id="searchWrap">
                            <input type="text" id="productSearch" class="sa-search-input" placeholder="Ketik nama / SKU produk..." autocomplete="off">
                            <button type="button" class="sa-search-clear" id="clearBtn" onclick="clearProduct()">×</button>
                            <span class="sa-search-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </span>
                            <div class="sa-dropdown" id="productDropdown"></div>
                        </div>
                        <div id="selectedTag" style="display:none;"></div>
                        @error('product_id')<div class="sa-err">{{ $message }}</div>@enderror
                    </div>

                    {{-- Unit + Gudang --}}
                    <div class="sa-grid2">
                        <div class="sa-fg">
                            <label class="sa-label">Satuan Barang</label>
                            <select class="sa-ctrl" name="unit_id" id="unit_id" onchange="updateUnitInfo()">
                                <option value="">— Satuan Dasar —</option>
                            </select>
                            <div class="sa-unit-info" id="unitInfo"></div>
                            <span class="sa-hint">Pilih satuan untuk input dalam unit tersebut</span>
                        </div>
                        <div class="sa-fg">
                            <label class="sa-label">Pilih Gudang <span class="req">*</span></label>
                            <select class="sa-ctrl {{ $errors->has('warehouse_id')?'has-error':'' }}" name="warehouse_id" id="warehouse_id" required onchange="updateStockDisplay()">
                                <option value="">— Pilih Gudang —</option>
                                @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Stock Display --}}
                    <div class="sa-stock-box" id="stockBox">
                        <div class="sa-stock-row" id="stockRow">
                            <span class="sa-stock-lbl">Stok di gudang ini</span>
                            <span class="sa-stock-val" id="stockVal">-</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Jumlah & Preview --}}
            <div class="sa-card">
                <div class="sa-card-hdr">
                    <span class="sa-step">Langkah 3</span>
                    <h2>Jumlah & Konfirmasi</h2>
                </div>
                <div class="sa-card-body">
                    <div class="sa-grid2">
                        <div class="sa-fg">
                            <label class="sa-label" id="jumlahLabel">Jumlah Penambahan <span class="req">*</span></label>
                            <input type="number" class="sa-ctrl {{ $errors->has('jumlah')?'has-error':'' }}"
                                name="jumlah" id="jumlah" step="0.001" min="0.001"
                                value="{{ old('jumlah') }}" placeholder="0" required oninput="updatePreview()">
                            @error('jumlah')<div class="sa-err">{{ $message }}</div>@enderror
                            <span class="sa-hint" id="jumlahHint">Berapa jumlah yang ingin ditambahkan?</span>
                        </div>
                        <div class="sa-fg">
                            <label class="sa-label">Keterangan</label>
                            <input type="text" class="sa-ctrl" name="keterangan" id="keterangan"
                                value="{{ old('keterangan') }}" placeholder="Alasan penyesuaian...">
                            <span class="sa-hint">Opsional — untuk audit trail</span>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="sa-preview" id="previewBox">
                        <div class="sa-preview-title">Preview Perubahan Stok</div>
                        <div class="sa-preview-flow">
                            <div class="sa-preview-box before">
                                <span class="lbl">Sebelum</span>
                                <span class="val" id="prevBefore">0</span>
                            </div>
                            <span class="sa-preview-arrow">→</span>
                            <div class="sa-preview-box after">
                                <span class="lbl">Sesudah</span>
                                <span class="val" id="prevAfter">0</span>
                            </div>
                            <span class="sa-preview-diff" id="prevDiff">-</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="sa-actions">
                <a href="{{ route('gudang.stock-adjustment.index') }}" class="sa-btn sa-btn-cancel">Batal</a>
                <button type="submit" class="sa-btn sa-btn-save" id="submitBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan Penyesuaian
                </button>
            </div>
        </form>
    </div>

    @php
    $productData = $products->map(function($p) {
        return [
            'id'    => $p->id,
            'name'  => $p->name,
            'sku'   => $p->sku ?? '',
            'unit'  => $p->unit ? $p->unit->abbreviation : 'pcs',
            'units' => $p->unitConversions->map(function($uc) {
                return [
                    'unit_id' => $uc->unit_id,
                    'name'    => $uc->unit ? $uc->unit->name : '',
                    'factor'  => $uc->conversion_factor,
                    'is_base' => $uc->is_base_unit,
                ];
            })->values()->toArray(),
        ];
    });
    @endphp

    <script>
        // Product data from server (with unit conversions)
        const PRODUCTS = @json($productData);

        const warehouseStock = @json($warehouseStock);

        // ── Searchable Product Select ──
        const searchInput = document.getElementById('productSearch');
        const dropdown = document.getElementById('productDropdown');
        const clearBtn = document.getElementById('clearBtn');
        const selectedTag = document.getElementById('selectedTag');
        const hiddenInput = document.getElementById('product_id');
        let selectedProduct = null;

        function renderDropdown(items) {
            if (items.length === 0) {
                dropdown.innerHTML = '<div class="sa-dropdown-empty">Tidak ada produk ditemukan</div>';
                dropdown.classList.add('open');
                return;
            }
            dropdown.innerHTML = items.map(p => {
                const sel = selectedProduct && selectedProduct.id === p.id ? ' selected' : '';
                const sku = p.sku ? '<span class="sku">' + p.sku + '</span>' : '';
                return '<div class="sa-dropdown-item' + sel + '" onclick="selectProduct(' + p.id + ')">' +
                    escapeHtml(p.name) + sku + ' <span class="sku">(' + p.unit + ')</span></div>';
            }).join('');
            dropdown.classList.add('open');
        }

        function selectProduct(id) {
            selectedProduct = PRODUCTS.find(p => p.id === id);
            if (!selectedProduct) return;
            hiddenInput.value = id;
            searchInput.value = selectedProduct.name + (selectedProduct.sku ? ' (' + selectedProduct.sku + ')' : '');
            dropdown.classList.remove('open');
            clearBtn.classList.add('visible');
            selectedTag.style.display = 'inline-flex';
            selectedTag.innerHTML = '<span class="sa-selected-tag">✓ ' + escapeHtml(selectedProduct.name) +
                ' <button onclick="clearProduct()" type="button">×</button></span>';
            populateUnits();
            updateStockDisplay();
        }

        function clearProduct() {
            selectedProduct = null;
            hiddenInput.value = '';
            searchInput.value = '';
            clearBtn.classList.remove('visible');
            selectedTag.style.display = 'none';
            dropdown.classList.remove('open');
            document.getElementById('unit_id').innerHTML = '<option value="">— Satuan Dasar —</option>';
            document.getElementById('unitInfo').classList.remove('visible');
            updateStockDisplay();
            searchInput.focus();
        }

        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            clearBtn.classList.toggle('visible', term.length > 0);
            if (term.length === 0) {
                renderDropdown(PRODUCTS);
            } else {
                const filtered = PRODUCTS.filter(p =>
                    p.name.toLowerCase().includes(term) ||
                    p.sku.toLowerCase().includes(term)
                );
                renderDropdown(filtered);
            }
        });

        searchInput.addEventListener('focus', function() {
            if (!selectedProduct) renderDropdown(PRODUCTS);
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('searchWrap').contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // ── Unit Conversion ──
        function populateUnits() {
            const select = document.getElementById('unit_id');
            select.innerHTML = '<option value="">— Satuan Dasar (' + (selectedProduct ? selectedProduct.unit : 'pcs') + ') —</option>';
            if (!selectedProduct || !selectedProduct.units) return;
            selectedProduct.units.forEach(u => {
                if (!u.is_base) {
                    const opt = document.createElement('option');
                    opt.value = u.unit_id;
                    opt.textContent = u.name + ' (×' + u.factor + ')';
                    opt.dataset.factor = u.factor;
                    opt.dataset.unitName = u.name;
                    select.appendChild(opt);
                }
            });
            document.getElementById('unitInfo').classList.remove('visible');
        }

        function updateUnitInfo() {
            const select = document.getElementById('unit_id');
            const info = document.getElementById('unitInfo');
            const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
            const opt = select.options[select.selectedIndex];

            if (opt.value && opt.dataset.factor) {
                const factor = parseInt(opt.dataset.factor);
                const unitName = opt.dataset.unitName;
                const baseName = selectedProduct ? selectedProduct.unit : 'pcs';
                const baseQty = jumlah * factor;
                info.textContent = jumlah + ' ' + unitName + ' = ' + baseQty.toLocaleString('id-ID') + ' ' + baseName;
                info.classList.add('visible');
            } else {
                info.classList.remove('visible');
            }
            updatePreview();
        }

        // ── Type & Preview ──
        function getType() {
            const r = document.querySelector('input[name="tipe"]:checked');
            return r ? r.value : 'masuk';
        }

        function updateUI() {
            const t = getType();
            const btn = document.getElementById('submitBtn');
            const lbl = document.getElementById('jumlahLabel');
            const hnt = document.getElementById('jumlahHint');
            const iM = document.getElementById('infoMasuk');
            const iK = document.getElementById('infoKoreksi');

            iM.classList.toggle('visible', t === 'masuk');
            iK.classList.toggle('visible', t === 'koreksi');

            if (t === 'masuk') {
                lbl.innerHTML = 'Jumlah Penambahan <span class="req">*</span>';
                hnt.textContent = 'Berapa jumlah yang ingin ditambahkan?';
                btn.className = 'sa-btn sa-btn-save';
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Tambah Stok Masuk';
            } else {
                lbl.innerHTML = 'Stok Fisik Aktual <span class="req">*</span>';
                hnt.textContent = 'Berapa stok fisik sesungguhnya di gudang ini?';
                btn.className = 'sa-btn sa-btn-save amber';
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Koreksi Stok';
            }
            updatePreview();
        }

        function getCurrentStock() {
            const pid = hiddenInput.value;
            const wid = document.getElementById('warehouse_id').value;
            if (!pid || !wid) return null;
            return (warehouseStock[pid] && warehouseStock[pid][wid] !== undefined)
                ? warehouseStock[pid][wid] : 0;
        }

        function getConversionFactor() {
            const select = document.getElementById('unit_id');
            const opt = select.options[select.selectedIndex];
            return opt && opt.dataset.factor ? parseInt(opt.dataset.factor) : 1;
        }

        function getDisplayUnit() {
            const select = document.getElementById('unit_id');
            const opt = select.options[select.selectedIndex];
            if (opt && opt.value && opt.dataset.unitName) return opt.dataset.unitName;
            return selectedProduct ? selectedProduct.unit : 'pcs';
        }

        function fmt(n) {
            return Number(n).toLocaleString('id-ID', { maximumFractionDigits: 3 });
        }

        function updateStockDisplay() {
            const box = document.getElementById('stockBox');
            const val = document.getElementById('stockVal');
            const row = document.getElementById('stockRow');
            const stock = getCurrentStock();
            const baseUnit = selectedProduct ? selectedProduct.unit : '';

            if (stock !== null) {
                box.classList.add('visible');
                val.textContent = fmt(stock) + (baseUnit ? ' ' + baseUnit : '');
                row.className = 'sa-stock-row';
            } else {
                box.classList.remove('visible');
            }
            updatePreview();
        }

        function updatePreview() {
            const stock = getCurrentStock();
            const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
            const t = getType();
            const factor = getConversionFactor();
            const displayUnit = getDisplayUnit();
            const baseUnit = selectedProduct ? selectedProduct.unit : '';
            const box = document.getElementById('previewBox');

            if (stock === null || jumlah <= 0) {
                box.classList.remove('visible');
                return;
            }

            box.classList.add('visible');
            const inputBase = jumlah * factor;
            let before = stock, after, diff;

            if (t === 'masuk') {
                after = before + inputBase;
                diff = inputBase;
            } else {
                after = inputBase;
                diff = after - before;
            }

            const unitSuffix = baseUnit ? ' ' + baseUnit : '';
            document.getElementById('prevBefore').textContent = fmt(before) + unitSuffix;
            document.getElementById('prevAfter').textContent = fmt(after) + unitSuffix;

            const diffEl = document.getElementById('prevDiff');
            if (diff > 0) {
                diffEl.textContent = '+' + fmt(diff);
                diffEl.className = 'sa-preview-diff up';
            } else if (diff < 0) {
                diffEl.textContent = fmt(diff);
                diffEl.className = 'sa-preview-diff down';
            } else {
                diffEl.textContent = 'Tidak berubah';
                diffEl.className = 'sa-preview-diff same';
            }
        }

        function confirmSubmit() {
            const stock = getCurrentStock();
            const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
            const t = getType();
            const pid = hiddenInput.value;
            const wid = document.getElementById('warehouse_id').value;
            const prodName = selectedProduct ? selectedProduct.name : '';
            const factor = getConversionFactor();
            const displayUnit = getDisplayUnit();
            const baseUnit = selectedProduct ? selectedProduct.unit : '';
            const baseQty = jumlah * factor;

            if (!pid || !wid) {
                alert('Pilih produk dan gudang terlebih dahulu.');
                return false;
            }

            const unitLine = factor > 1
                ? '\nInput: ' + fmt(jumlah) + ' ' + displayUnit + ' = ' + fmt(baseQty) + ' ' + baseUnit
                : '';

            let msg;
            if (t === 'masuk') {
                msg = 'Tambah stok masuk:\n\n' + prodName + unitLine +
                    '\nStok saat ini: ' + fmt(stock ?? 0) + ' ' + baseUnit +
                    '\nDitambahkan: +' + fmt(baseQty) + ' ' + baseUnit +
                    '\nStok akhir: ' + fmt((stock ?? 0) + baseQty) + ' ' + baseUnit +
                    '\n\nLanjutkan?';
            } else {
                const diff = baseQty - (stock ?? 0);
                const sign = diff >= 0 ? '+' : '';
                msg = 'Koreksi stok:\n\n' + prodName + unitLine +
                    '\nStok sistem: ' + fmt(stock ?? 0) + ' ' + baseUnit +
                    '\nStok fisik: ' + fmt(baseQty) + ' ' + baseUnit +
                    '\nSelisih: ' + sign + fmt(diff) + ' ' + baseUnit +
                    '\n\nLanjutkan?';
            }
            return confirm(msg);
        }

        function escapeHtml(str) {
            if (!str) return '';
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // Jumlah input also updates unit info
        document.getElementById('jumlah').addEventListener('input', updateUnitInfo);

        // Init
        updateUI();
        updateStockDisplay();
    </script>
</x-app-layout>
