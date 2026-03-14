<x-app-layout>
    <x-slot name="header">Edit Produk</x-slot>

    <style>
        .prod-page { max-width: min(1280px, 100%); margin: 0 auto; padding-bottom: 6rem; }

        /* Page Header */
        .prod-header {
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap; margin-bottom: 1.75rem;
        }
        .prod-header-left { display: flex; align-items: center; gap: 1rem; min-width: 0; }
        .prod-back-btn {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5rem 0.875rem; background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 9px; color: #475569; text-decoration: none;
            font-size: 0.8125rem; font-weight: 600; transition: all 0.2s; white-space: nowrap;
        }
        .prod-back-btn:hover { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }
        .prod-title-wrap { min-width: 0; }
        .prod-breadcrumb { font-size: 0.75rem; color: #94a3b8; font-weight: 500; margin-bottom: 2px; }
        .prod-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Section Cards */
        .section-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 1.25rem; overflow: hidden;
        }
        .section-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.125rem 1.5rem; border-bottom: 1px solid #f1f5f9;
        }
        .section-card-header-left { display: flex; align-items: center; gap: 0.75rem; }
        .section-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .icon-indigo { background: #eef2ff; }
        .icon-emerald { background: #ecfdf5; }
        .section-card-title { font-size: 0.9375rem; font-weight: 800; color: #0f172a; }
        .section-card-subtitle { font-size: 0.75rem; color: #94a3b8; margin-top: 1px; }
        .section-card-body { padding: 1.5rem; }

        /* Optional details block */
        .optional-block { margin-top: 0.5rem; }
        .optional-toggle {
            display: flex; align-items: center; gap: 0.5rem;
            cursor: pointer; user-select: none; padding: 0.6rem 0;
            font-size: 0.8125rem; font-weight: 700; color: #475569;
            border-top: 1px dashed #e2e8f0; list-style: none;
        }
        .optional-toggle::-webkit-details-marker { display: none; }
        .optional-toggle-arrow { width: 16px; height: 16px; transition: transform 0.2s; }
        details[open] .optional-toggle .optional-toggle-arrow { transform: rotate(90deg); }
        .optional-body { padding-top: 1rem; }

        /* Markup bar */
        .markup-bar {
            display: flex; align-items: flex-end; gap: 0.5rem; flex-wrap: wrap;
            padding: 1rem 1.25rem; background: #f8fafc;
            border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 1rem;
        }
        .markup-label { font-size: 0.8rem; font-weight: 800; color: #334155; white-space: nowrap; margin-bottom: 0.25rem; }
        .markup-inputs { display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; }
        .markup-input { width: 90px; font-size: 0.8rem !important; padding: 0.45rem 0.625rem !important; }

        /* Unit Table */
        .unit-table-wrap { width: 100%; overflow-x: auto; }
        .unit-header { min-width: 1100px; display: grid; gap: 0.5rem; padding: 0.5rem 0.75rem; background: #f8fafc; border-radius: 9px; margin-bottom: 0.5rem; grid-template-columns: minmax(150px,1.2fr) 90px 120px 120px 120px 120px 120px 120px 140px 70px 56px; }
        .unit-header span { font-size: 0.67rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; }
        .unit-header .t-center { text-align: center; }
        .unit-header .t-right { text-align: right; }
        .unit-row { min-width: 1100px; display: grid; gap: 0.5rem; align-items: end; margin-bottom: 0.5rem; padding: 0.75rem; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; transition: all 0.15s; grid-template-columns: minmax(150px,1.2fr) 90px 120px 120px 120px 120px 120px 120px 140px 70px 56px; }
        .unit-row:hover { border-color: #c7d2fe; box-shadow: 0 2px 8px rgba(99,102,241,0.06); }
        .unit-cell { min-width: 0; }
        .unit-label { display: none; font-size: 0.67rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
        .unit-center { display: flex; justify-content: center; align-items: center; height: 38px; }
        .unit-actions { display: flex; justify-content: center; align-items: center; height: 38px; }
        .no-units-box {
            text-align: center; padding: 2rem 1rem; color: #94a3b8; font-size: 0.85rem;
            border: 2px dashed #e2e8f0; border-radius: 12px; background: #fafbff;
        }
        .no-units-box span { font-size: 2rem; display: block; margin-bottom: 0.5rem; }

        /* Sticky footer */
        .sticky-footer {
            position: sticky; bottom: 1rem; margin-top: 1.25rem;
        }
        .sticky-footer-inner {
            display: flex; justify-content: space-between; align-items: center;
            gap: 1rem; flex-wrap: wrap; padding: 0.875rem 1.25rem;
            background: rgba(255,255,255,0.95); backdrop-filter: blur(8px);
            border: 1px solid #e2e8f0; border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .footer-hint { font-size: 0.78rem; color: #64748b; }

        @media (max-width: 1100px) {
            .unit-table-wrap { overflow: visible; }
            .unit-header { display: none; }
            .unit-row { min-width: 0; grid-template-columns: repeat(2, minmax(0,1fr)); padding: 0.9rem; }
            .unit-label { display: block; }
            .unit-cell--basis, .unit-cell--actions { grid-column: 1 / -1; }
            .unit-center, .unit-actions { justify-content: flex-start; height: auto; }
        }
        @media (max-width: 520px) { .unit-row { grid-template-columns: 1fr; } }
    </style>

    <div class="prod-page">

        {{-- Page Header --}}
        <div class="prod-header">
            <div class="prod-header-left">
                <a href="{{ route('products.index') }}" class="prod-back-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    Kembali
                </a>
                <div class="prod-title-wrap">
                    <div class="prod-breadcrumb">Master Data / Produk</div>
                    <div class="prod-title">Edit: {{ $product->name }}</div>
                </div>
            </div>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" form="product-form" class="btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan
                </button>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:1.25rem;">❌ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1.25rem;">
                <div style="font-weight:800;margin-bottom:0.3rem;">⚠️ Terdapat kesalahan:</div>
                <ul style="margin:0;padding-left:1.25rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.update', $product) }}" id="product-form">
            @csrf
            @method('PUT')
            <input type="hidden" id="priceHidden" name="price" value="{{ old('price', floor($product->price)) }}">
            <input type="hidden" id="purchasePriceHidden" name="purchase_price" value="{{ old('purchase_price', floor($product->purchase_price)) }}">

            {{-- ─── Card 1: Informasi Utama ─── --}}
            <div class="section-card">
                <div class="section-card-header">
                    <div class="section-card-header-left">
                        <div class="section-icon icon-indigo">🏷️</div>
                        <div>
                            <div class="section-card-title">Informasi Produk</div>
                            <div class="section-card-subtitle">Data utama produk yang diperlukan sistem</div>
                        </div>
                    </div>
                </div>
                <div class="section-card-body">

                    <div class="form-group">
                        <label class="form-label">Nama Produk <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input @error('name') input-error @enderror" placeholder="Contoh: Gula Kristal Putih 50kg">
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Kategori <span class="required">*</span></label>
                            <select name="category_id" class="form-input @error('category_id') input-error @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">SKU <span class="required">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-input @error('sku') input-error @enderror" placeholder="Contoh: GKP-50KG">
                            @error('sku') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Stok Saat Ini</label>
                            @if(($maskStock ?? false) === true)
                                <input type="text" value="Terkunci" class="form-input" disabled style="background:#f8fafc;cursor:not-allowed;" title="Wajib opname untuk melihat stok">
                            @else
                                <input type="number" value="{{ $product->stock }}" class="form-input" disabled style="background:#f8fafc;cursor:not-allowed;" title="Hanya bisa diubah lewat Opname">
                            @endif
                            <span class="form-hint">ℹ️ Gunakan menu <strong>Stok Opname</strong> untuk penyesuaian.</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Minimum Stok <span class="required">*</span></label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" class="form-input @error('min_stock') input-error @enderror" placeholder="0">
                            @error('min_stock') <span class="form-error">{{ $message }}</span> @enderror
                            <span class="form-hint">Sistem akan memperingatkan saat stok di bawah nilai ini.</span>
                        </div>
                    </div>

                    {{-- Optional Section --}}
                    <details class="optional-block">
                        <summary class="optional-toggle">
                            <svg class="optional-toggle-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            <span>Informasi Tambahan</span>
                            <span class="badge badge-gray" style="font-size:0.65rem;font-weight:700;">Opsional</span>
                        </summary>
                        <div class="optional-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Barcode / EAN</label>
                                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="form-input @error('barcode') input-error @enderror" placeholder="Scan atau ketik barcode">
                                    @error('barcode') <span class="form-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Satuan Dasar (terkecil)</label>
                                    <select name="unit_id" class="form-input">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi Produk</label>
                                <textarea name="description" rows="3" class="form-input" placeholder="Keterangan tambahan tentang produk...">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </details>
                </div>
            </div>

            {{-- ─── Card 2: Satuan & Harga ─── --}}
            <div class="section-card">
                <div class="section-card-header">
                    <div class="section-card-header-left">
                        <div class="section-icon icon-emerald">💰</div>
                        <div>
                            <div class="section-card-title">Satuan &amp; Harga</div>
                            <div class="section-card-subtitle">Atur konversi satuan dan semua level harga jual</div>
                        </div>
                    </div>
                    <button type="button" class="btn-primary btn-sm" onclick="addUnitRow()">＋ Tambah Satuan</button>
                </div>
                <div class="section-card-body">

                    {{-- Markup Tool --}}
                    <div class="markup-bar">
                        <div>
                            <div class="markup-label">⚡ Hitung Otomatis Markup dari Modal (%)</div>
                            <div style="font-size:0.72rem;color:#64748b;">Isi persentase markup lalu klik Hitung.</div>
                        </div>
                        <div class="markup-inputs">
                            <input id="pctEcer" type="number" step="0.01" min="0" class="form-input markup-input" placeholder="Eceran %">
                            <input id="pctGrosir" type="number" step="0.01" min="0" class="form-input markup-input" placeholder="Grosir %">
                            <input id="pctJ1" type="number" step="0.01" min="0" class="form-input markup-input" placeholder="Jual1 %">
                            <input id="pctJ2" type="number" step="0.01" min="0" class="form-input markup-input" placeholder="Jual2 %">
                            <input id="pctJ3" type="number" step="0.01" min="0" class="form-input markup-input" placeholder="Jual3 %">
                            <input id="pctMin" type="number" step="0.01" min="0" class="form-input markup-input" placeholder="Minimal %">
                            <button id="btnApplyMarkup" type="button" class="btn-secondary" style="height:38px;padding:0 1rem;font-size:0.8rem;white-space:nowrap;">
                                Hitung
                            </button>
                        </div>
                    </div>

                    {{-- Unit Table --}}
                    <div class="unit-table-wrap">
                        <div class="unit-header">
                            <span>Satuan</span>
                            <span class="t-center">Konversi</span>
                            <span class="t-right">Modal</span>
                            <span class="t-right">Eceran</span>
                            <span class="t-right">Grosir</span>
                            <span class="t-right">Jual 1</span>
                            <span class="t-right">Jual 2</span>
                            <span class="t-right">Jual 3</span>
                            <span class="t-right">Jual Minimal</span>
                            <span class="t-center">Basis</span>
                            <span class="t-center">Aksi</span>
                        </div>
                        <div id="unit-rows-container"></div>
                    </div>

                    <div id="no-units-msg" class="no-units-box" style="display:none;">
                        <span>📦</span>
                        Belum ada satuan. Klik <strong>"+ Tambah Satuan"</strong> untuk mulai menambahkan.
                    </div>

                    <div style="margin-top:0.75rem;padding:0.6rem 0.875rem;background:#fef3c7;border-radius:9px;font-size:0.75rem;color:#92400e;border-left:3px solid #f59e0b;display:flex;align-items:center;gap:0.5rem;">
                        <span>💡</span>
                        <span><strong>Konversi</strong> = jumlah satuan terkecil dalam unit ini. Contoh: 1 Karton = 40 Pcs → isi 40. Centang <strong>Basis</strong> untuk satuan terkecil.</span>
                    </div>
                </div>
            </div>
        </form>

        {{-- Sticky Footer --}}
        <div class="sticky-footer">
            <div class="sticky-footer-inner">
                <div class="footer-hint">
                    💾 Perubahan belum tersimpan. Klik <strong>Simpan</strong> untuk menyimpan semua data.
                </div>
                <div style="display:flex;gap:0.5rem;align-items:center;">
                    <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" form="product-form" class="btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <template id="unitsJson">@json($unitsData)</template>
    <template id="existingConversionsJson">@json($existingConversionsData)</template>

    <script>
    const allUnits = JSON.parse(document.getElementById('unitsJson')?.innerHTML || '[]');
    const existingConversions = JSON.parse(document.getElementById('existingConversionsJson')?.innerHTML || '[]');
    let unitRowIdx = 0;

    function num(v) {
        const n = Number(v);
        return Number.isFinite(n) ? n : 0;
    }
    function parseMoney(v) {
        const s = String(v ?? '');
        const digits = s.replace(/[^\d]/g, '');
        return digits ? Number(digits) : 0;
    }
    function formatMoney(v) {
        const n = Math.max(0, Math.floor(num(v)));
        return n.toLocaleString('id-ID');
    }
    function getBaseRowEl() {
        const rows = document.querySelectorAll('[id^="unit-row-"]');
        for (const row of rows) {
            const cb = row.querySelector('input[name$="[is_base_unit]"]');
            if (cb && cb.checked) return row;
        }
        return rows.length ? rows[0] : null;
    }
    function syncMasterFromBase() {
        const base = getBaseRowEl();
        if (!base) return;
        const ecer = base.querySelector('input[name$="[sell_price_ecer]"]');
        const modal = base.querySelector('input[name$="[purchase_price]"]');
        const priceHidden = document.getElementById('priceHidden');
        const purchaseHidden = document.getElementById('purchasePriceHidden');
        if (priceHidden && ecer) priceHidden.value = String(num(ecer.value));
        if (purchaseHidden && modal) purchaseHidden.value = String(num(modal.value));
    }
    function updateAllRowStyles() {
        document.querySelectorAll('[id^="unit-row-"]').forEach(row => {
            const cb = row.querySelector('input[name$="[is_base_unit]"]');
            const isBase = !!cb?.checked;
            row.style.background = isBase ? '#f0fdf4' : '#fff';
            row.style.borderColor = isBase ? '#86efac' : '#e2e8f0';
        });
    }
    function onBaseChange(idx) {
        const row = document.getElementById(`unit-row-${idx}`);
        const checked = row?.querySelector('input[name$="[is_base_unit]"]')?.checked;
        if (checked) {
            document.querySelectorAll('input[name$="[is_base_unit]"]').forEach(cb => {
                if (cb !== row.querySelector('input[name$="[is_base_unit]"]')) cb.checked = false;
            });
        }
        updateAllRowStyles();
        syncMasterFromBase();
    }
    function removeUnitRow(idx) {
        document.getElementById(`unit-row-${idx}`)?.remove();
        if (!document.querySelectorAll('[id^="unit-row-"]').length) {
            document.getElementById('no-units-msg').style.display = '';
        }
        syncMasterFromBase();
    }
    function baseDefaults() {
        const base = getBaseRowEl();
        if (!base) {
            return {
                purchase_price: num(document.getElementById('purchasePriceHidden')?.value || 0),
                sell_price_ecer: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_grosir: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_jual1: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_jual2: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_jual3: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_minimal: num(document.getElementById('priceHidden')?.value || 0),
            };
        }
        const getVal = (name) => num(base.querySelector(`input[name$="[${name}]"]`)?.value || 0);
        return {
            purchase_price: getVal('purchase_price'),
            sell_price_ecer: getVal('sell_price_ecer'),
            sell_price_grosir: getVal('sell_price_grosir'),
            sell_price_jual1: getVal('sell_price_jual1'),
            sell_price_jual2: getVal('sell_price_jual2'),
            sell_price_jual3: getVal('sell_price_jual3'),
            sell_price_minimal: getVal('sell_price_minimal'),
        };
    }
    function getHidden(row, key) {
        const el = row.querySelector(`input[type="hidden"][data-hidden="${key}"]`);
        return el ? num(el.value) : 0;
    }
    function setHidden(row, key, value) {
        const el = row.querySelector(`input[type="hidden"][data-hidden="${key}"]`);
        const vis = row.querySelector(`input[type="text"][data-visible="${key}"]`);
        if (el) el.value = String(Math.max(0, Math.floor(num(value))));
        if (vis) vis.value = formatMoney(el?.value ?? value);
    }
    function wireMoneyInput(row, key) {
        const el = row.querySelector(`input[type="hidden"][data-hidden="${key}"]`);
        const vis = row.querySelector(`input[type="text"][data-visible="${key}"]`);
        if (!el || !vis) return;
        vis.value = formatMoney(el.value);
        vis.addEventListener('input', () => { el.value = String(parseMoney(vis.value)); });
        vis.addEventListener('blur', () => { vis.value = formatMoney(el.value); });
    }
    function applyMarkup() {
        const pct = {
            sell_price_ecer: document.getElementById('pctEcer')?.value,
            sell_price_grosir: document.getElementById('pctGrosir')?.value,
            sell_price_jual1: document.getElementById('pctJ1')?.value,
            sell_price_jual2: document.getElementById('pctJ2')?.value,
            sell_price_jual3: document.getElementById('pctJ3')?.value,
            sell_price_minimal: document.getElementById('pctMin')?.value,
        };
        const keys = Object.keys(pct);
        document.querySelectorAll('[id^="unit-row-"]').forEach(row => {
            const modal = getHidden(row, 'purchase_price');
            keys.forEach(k => {
                const p = num(pct[k]);
                if (!pct[k] && pct[k] !== 0) return;
                if (!Number.isFinite(p) || p < 0) return;
                setHidden(row, k, Math.round(modal * (1 + p / 100)));
            });
        });
        syncMasterFromBase();
    }
    function addUnitRow(data = null) {
        const container = document.getElementById('unit-rows-container');
        document.getElementById('no-units-msg').style.display = 'none';
        const idx = unitRowIdx++;
        const d = data || {};
        const isBase = !!d.is_base_unit;
        const factor = Math.max(1, Math.floor(num(d.conversion_factor || 1)));
        const base = baseDefaults();

        const unitOptions = allUnits.map(u =>
            `<option value="${u.id}" ${u.id == (d.unit_id || '') ? 'selected' : ''}>${u.name}</option>`
        ).join('');

        const val = (k, fallback) => String(Math.max(0, num(d[k] ?? fallback ?? 0)));
        const row = document.createElement('div');
        row.id = `unit-row-${idx}`;
        row.className = 'unit-row';
        row.innerHTML = `
            <div class="unit-cell">
                <div class="unit-label">Satuan</div>
                <select name="units[${idx}][unit_id]" class="form-input" style="font-size:0.82rem;padding:0.45rem 0.6rem;" required>
                    <option value="">-- Satuan --</option>${unitOptions}
                </select>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Konversi</div>
                <input type="number" name="units[${idx}][conversion_factor]" value="${factor}" min="1" class="form-input" style="font-size:0.85rem;font-weight:800;text-align:center;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Modal</div>
                <input type="hidden" name="units[${idx}][purchase_price]" data-hidden="purchase_price" value="${val('purchase_price', isBase ? base.purchase_price : base.purchase_price * factor)}">
                <input type="text" inputmode="numeric" data-visible="purchase_price" class="form-input" style="text-align:right;font-size:0.8rem;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Eceran</div>
                <input type="hidden" name="units[${idx}][sell_price_ecer]" data-hidden="sell_price_ecer" value="${val('sell_price_ecer', isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_ecer" class="form-input" style="text-align:right;font-size:0.8rem;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Grosir</div>
                <input type="hidden" name="units[${idx}][sell_price_grosir]" data-hidden="sell_price_grosir" value="${val('sell_price_grosir', isBase ? base.sell_price_grosir : base.sell_price_grosir * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_grosir" class="form-input" style="text-align:right;font-size:0.8rem;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual 1</div>
                <input type="hidden" name="units[${idx}][sell_price_jual1]" data-hidden="sell_price_jual1" value="${val('sell_price_jual1', isBase ? base.sell_price_jual1 : base.sell_price_jual1 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual1" class="form-input" style="text-align:right;font-size:0.8rem;">
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual 2</div>
                <input type="hidden" name="units[${idx}][sell_price_jual2]" data-hidden="sell_price_jual2" value="${val('sell_price_jual2', isBase ? base.sell_price_jual2 : base.sell_price_jual2 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual2" class="form-input" style="text-align:right;font-size:0.8rem;">
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual 3</div>
                <input type="hidden" name="units[${idx}][sell_price_jual3]" data-hidden="sell_price_jual3" value="${val('sell_price_jual3', isBase ? base.sell_price_jual3 : base.sell_price_jual3 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual3" class="form-input" style="text-align:right;font-size:0.8rem;">
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual Minimal</div>
                <input type="hidden" name="units[${idx}][sell_price_minimal]" data-hidden="sell_price_minimal" value="${val('sell_price_minimal', (d.sell_price_minimal ?? (isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)))}">
                <input type="text" inputmode="numeric" data-visible="sell_price_minimal" class="form-input" style="text-align:right;font-size:0.8rem;">
            </div>
            <div class="unit-cell unit-cell--basis">
                <div class="unit-label">Basis</div>
                <div class="unit-center">
                    <input type="checkbox" name="units[${idx}][is_base_unit]" value="1" ${isBase ? 'checked' : ''} onchange="onBaseChange(${idx})" style="width:18px;height:18px;accent-color:#10b981;cursor:pointer;">
                </div>
            </div>
            <div class="unit-cell unit-cell--actions">
                <div class="unit-label">Aksi</div>
                <div class="unit-actions">
                    <button type="button" onclick="removeUnitRow(${idx})" style="width:32px;height:32px;background:#fee2e2;color:#991b1b;border:none;border-radius:8px;cursor:pointer;font-size:0.9rem;font-weight:900;display:flex;align-items:center;justify-content:center;" title="Hapus baris">✕</button>
                </div>
            </div>
        `;
        container.appendChild(row);
        ['purchase_price','sell_price_ecer','sell_price_grosir','sell_price_jual1','sell_price_jual2','sell_price_jual3','sell_price_minimal'].forEach(k => wireMoneyInput(row, k));
        updateAllRowStyles();
        syncMasterFromBase();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (existingConversions.length > 0) {
            existingConversions.forEach(data => addUnitRow(data));
        } else {
            document.getElementById('no-units-msg').style.display = '';
        }
        document.getElementById('unit-rows-container')?.addEventListener('input', (e) => {
            const base = getBaseRowEl();
            if (!base) return;
            if (base.contains(e.target)) syncMasterFromBase();
        });
        document.getElementById('btnApplyMarkup')?.addEventListener('click', applyMarkup);
        document.getElementById('product-form')?.addEventListener('submit', () => { syncMasterFromBase(); });
        syncMasterFromBase();
    });
    </script>
</x-app-layout>
