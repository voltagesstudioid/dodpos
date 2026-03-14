<x-app-layout>
    <x-slot name="header">Tambah Produk</x-slot>
    <style>
    .product-page{max-width:min(1200px,100%);margin:0 auto;padding:0 1rem 5rem;}
    .unit-table-wrap{width:100%;overflow:auto;}
    .unit-header{min-width:1100px;display:grid;gap:0.5rem;padding:0 0.25rem 0.5rem;border-bottom:1px solid #f1f5f9;margin-bottom:0.5rem;grid-template-columns:minmax(150px,1.2fr) 90px 120px 120px 120px 120px 120px 120px 140px 80px 60px;}
    .unit-header span{font-size:0.68rem;font-weight:900;color:#94a3b8;text-transform:uppercase;}
    .unit-header .t-center{text-align:center;}
    .unit-header .t-right{text-align:right;}
    .unit-row{min-width:1100px;display:grid;gap:0.5rem;align-items:end;margin-bottom:0.5rem;padding:0.6rem 0.5rem;border-radius:10px;border:1px solid #e2e8f0;background:#ffffff;grid-template-columns:minmax(150px,1.2fr) 90px 120px 120px 120px 120px 120px 120px 140px 80px 60px;}
    .unit-cell{min-width:0;}
    .unit-label{display:none;font-size:0.68rem;font-weight:900;color:#94a3b8;text-transform:uppercase;margin-bottom:0.25rem;}
    .unit-center{display:flex;justify-content:center;align-items:center;height:38px;}
    .unit-actions{display:flex;justify-content:center;align-items:center;height:38px;}
    @media (max-width: 1100px){
        .product-page{padding:0 0.75rem 5rem;}
        .unit-table-wrap{overflow:visible;}
        .unit-header{display:none;}
        .unit-row{min-width:0;grid-template-columns:repeat(2,minmax(0,1fr));padding:0.9rem;}
        .unit-label{display:block;}
        .unit-cell--basis,.unit-cell--actions{grid-column:1/-1;}
        .unit-center,.unit-actions{justify-content:flex-start;height:auto;}
    }
    @media (max-width: 520px){
        .unit-row{grid-template-columns:1fr;}
    }
    </style>

    <div class="page-container product-page">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;flex-wrap:wrap;margin-bottom:1rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;min-width:0;">
                <a href="{{ route('products.index') }}" style="color:#64748b;text-decoration:none;flex-shrink:0;">← Kembali</a>
                <div style="min-width:0;">
                    <div style="font-size:0.75rem;color:#94a3b8;">Produk</div>
                    <div style="font-size:1.1rem;font-weight:800;color:#0f172a;">Tambah Produk</div>
                </div>
            </div>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" form="product-form" class="btn-primary">💾 Simpan</button>
            </div>
        </div>

        @if(session('error')) <div class="alert alert-danger" style="margin-bottom:1rem;">❌ {{ session('error') }}</div> @endif
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1rem;">
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin:0.5rem 0 0; padding-left:1.25rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.store') }}" id="product-form">
            @csrf
            <input type="hidden" id="priceHidden" name="price" value="{{ old('price', 0) }}">
            <input type="hidden" id="purchasePriceHidden" name="purchase_price" value="{{ old('purchase_price', 0) }}">
            <div class="card" style="padding:1.5rem;margin-bottom:1rem;">
                <div style="font-size:0.875rem;font-weight:900;color:#0f172a;margin-bottom:0.25rem;">Utama</div>
                <div style="font-size:0.75rem;color:#94a3b8;margin-bottom:0.75rem;">Isi yang wajib dulu. Sisanya ada di bagian “Lainnya”.</div>

                <div class="form-group">
                    <label class="form-label">Nama Produk <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') input-error @enderror" placeholder="Contoh: Indomie Goreng Spesial">
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#ef4444;">*</span></label>
                        <select name="category_id" class="form-input @error('category_id') input-error @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kode SKU <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $nextSku ?? '') }}" class="form-input" style="background-color: #f1f5f9; cursor: not-allowed; color: #64748b; font-weight: 600;" readonly>
                        <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 0.35rem;">Dibuat otomatis oleh sistem</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Stok Awal <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="form-input @error('stock') input-error @enderror">
                        @error('stock') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Minimum Stok <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" min="0" class="form-input @error('min_stock') input-error @enderror">
                        @error('min_stock') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <details style="margin-top:0.75rem;">
                    <summary style="cursor:pointer;font-weight:900;color:#334155;list-style:none;user-select:none;">
                        Lainnya
                        <span style="font-size:0.75rem;color:#94a3b8;font-weight:700;margin-left:0.35rem;">(opsional)</span>
                    </summary>
                    <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid #e2e8f0;">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Barcode / EAN</label>
                                <input type="text" name="barcode" value="{{ old('barcode') }}" class="form-input @error('barcode') input-error @enderror" placeholder="Scan barcode disini...">
                                @error('barcode') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Satuan Dasar (terkecil)</label>
                                <select name="unit_id" class="form-input">
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" rows="2" class="form-input">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </details>
            </div>

            <div class="card" style="padding:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.75rem;">
                    <div>
                        <div style="font-size:0.875rem;font-weight:900;color:#0f172a;">Satuan & Harga</div>
                        <div style="font-size:0.75rem;color:#94a3b8;">1 baris = 1 satuan</div>
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                        <button type="button" class="btn-primary btn-sm" onclick="addUnitRow()">＋ Tambah Satuan</button>
                    </div>
                </div>

                <div style="display:flex; gap:0.5rem; align-items:flex-end; flex-wrap:wrap; padding:0.75rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; margin-bottom:0.75rem;">
                    <div style="font-weight:900; font-size:0.8rem; color:#0f172a; margin-right:0.25rem;">Markup (%) dari Modal</div>
                    <div style="display:flex; gap:0.35rem; align-items:center; flex-wrap:wrap;">
                        <input id="pctEcer" type="number" step="0.01" min="0" class="form-input" placeholder="Eceran%" style="width:92px; font-size:0.8rem;">
                        <input id="pctGrosir" type="number" step="0.01" min="0" class="form-input" placeholder="Grosir%" style="width:92px; font-size:0.8rem;">
                        <input id="pctJ1" type="number" step="0.01" min="0" class="form-input" placeholder="Jual1%" style="width:92px; font-size:0.8rem;">
                        <input id="pctJ2" type="number" step="0.01" min="0" class="form-input" placeholder="Jual2%" style="width:92px; font-size:0.8rem;">
                        <input id="pctJ3" type="number" step="0.01" min="0" class="form-input" placeholder="Jual3%" style="width:92px; font-size:0.8rem;">
                        <input id="pctMin" type="number" step="0.01" min="0" class="form-input" placeholder="Minimal%" style="width:92px; font-size:0.8rem;">
                        <button id="btnApplyMarkup" type="button" class="btn-secondary" style="height:38px; padding:0 0.9rem;">Hitung</button>
                    </div>
                    <div style="font-size:0.75rem; color:#64748b;">Angka otomatis pakai titik ribuan.</div>
                </div>

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

                <div id="no-units-msg" style="text-align:center;padding:1.25rem;color:#94a3b8;font-size:0.8rem;display:none;border:1px dashed #e2e8f0;border-radius:12px;">
                    Belum ada satuan. Klik “Tambah Satuan”.
                </div>

                <div style="margin-top:0.75rem;padding:0.6rem 0.75rem;background:#fef3c7;border-radius:8px;font-size:0.75rem;color:#92400e;border-left:3px solid #f59e0b;">
                    Konversi = berapa satuan terkecil per unit ini. Contoh: 1 Karton = 40 Pcs → isi 40.
                </div>
            </div>
        </form>
    </div>

    <template id="unitsJson">@json($unitsData)</template>

    <div style="position:sticky;bottom:0;margin-top:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:0.75rem;flex-wrap:wrap;padding:0.75rem 1rem;border:1px solid #e2e8f0;background:rgba(255,255,255,0.92);backdrop-filter:blur(8px);border-radius:14px;">
            <div style="font-size:0.75rem;color:#64748b;">Pastikan satuan “Basis” adalah satuan terkecil.</div>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" form="product-form" class="btn-primary">💾 Simpan</button>
            </div>
        </div>
    </div>

    <script>
    const allUnits = JSON.parse(document.getElementById('unitsJson')?.innerHTML || '[]');
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
            row.style.background = isBase ? '#f0fdf4' : '#ffffff';
            row.style.borderColor = isBase ? '#bbf7d0' : '#e2e8f0';
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
            document.getElementById('no-units-msg').style.display = 'block';
        }
        syncMasterFromBase();
    }

    function baseDefaults() {
        const base = getBaseRowEl();
        if (!base) {
            const p = num(document.getElementById('priceHidden')?.value || 0);
            return {
                purchase_price: num(document.getElementById('purchasePriceHidden')?.value || 0),
                sell_price_ecer: p,
                sell_price_grosir: p,
                sell_price_jual1: p,
                sell_price_jual2: p,
                sell_price_jual3: p,
                sell_price_minimal: p,
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
        vis.addEventListener('input', () => {
            el.value = String(parseMoney(vis.value));
        });
        vis.addEventListener('blur', () => {
            vis.value = formatMoney(el.value);
        });
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
        const rows = document.querySelectorAll('[id^="unit-row-"]');
        rows.forEach(row => {
            const modal = getHidden(row, 'purchase_price');
            keys.forEach(k => {
                const p = num(pct[k]);
                if (!pct[k] && pct[k] !== 0) return;
                if (!Number.isFinite(p) || p < 0) return;
                const price = modal * (1 + (p / 100));
                setHidden(row, k, Math.round(price));
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
                <select name="units[${idx}][unit_id]" class="form-input" style="font-size:0.8rem; padding:0.45rem 0.6rem;" required>
                    <option value="">-- Satuan --</option>${unitOptions}
                </select>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Konversi</div>
                <input type="number" name="units[${idx}][conversion_factor]" value="${factor}" min="1" class="form-input" style="font-size:0.85rem; font-weight:900; text-align:center;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Modal</div>
                <input type="hidden" name="units[${idx}][purchase_price]" data-hidden="purchase_price" value="${val('purchase_price', isBase ? base.purchase_price : base.purchase_price * factor)}">
                <input type="text" inputmode="numeric" data-visible="purchase_price" class="form-input" style="text-align:right; font-size:0.8rem;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Eceran</div>
                <input type="hidden" name="units[${idx}][sell_price_ecer]" data-hidden="sell_price_ecer" value="${val('sell_price_ecer', isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_ecer" class="form-input" style="text-align:right; font-size:0.8rem;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Grosir</div>
                <input type="hidden" name="units[${idx}][sell_price_grosir]" data-hidden="sell_price_grosir" value="${val('sell_price_grosir', isBase ? base.sell_price_grosir : base.sell_price_grosir * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_grosir" class="form-input" style="text-align:right; font-size:0.8rem;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual 1</div>
                <input type="hidden" name="units[${idx}][sell_price_jual1]" data-hidden="sell_price_jual1" value="${val('sell_price_jual1', isBase ? base.sell_price_jual1 : base.sell_price_jual1 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual1" class="form-input" style="text-align:right; font-size:0.8rem;">
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual 2</div>
                <input type="hidden" name="units[${idx}][sell_price_jual2]" data-hidden="sell_price_jual2" value="${val('sell_price_jual2', isBase ? base.sell_price_jual2 : base.sell_price_jual2 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual2" class="form-input" style="text-align:right; font-size:0.8rem;">
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual 3</div>
                <input type="hidden" name="units[${idx}][sell_price_jual3]" data-hidden="sell_price_jual3" value="${val('sell_price_jual3', isBase ? base.sell_price_jual3 : base.sell_price_jual3 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual3" class="form-input" style="text-align:right; font-size:0.8rem;">
            </div>
            <div class="unit-cell">
                <div class="unit-label">Jual Minimal</div>
                <input type="hidden" name="units[${idx}][sell_price_minimal]" data-hidden="sell_price_minimal" value="${val('sell_price_minimal', (d.sell_price_minimal ?? (isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)))}">
                <input type="text" inputmode="numeric" data-visible="sell_price_minimal" class="form-input" style="text-align:right; font-size:0.8rem;">
            </div>
            <div class="unit-cell unit-cell--basis">
                <div class="unit-label">Basis</div>
                <div class="unit-center">
                    <input type="checkbox" name="units[${idx}][is_base_unit]" value="1" ${isBase ? 'checked' : ''} onchange="onBaseChange(${idx})" style="width:16px; height:16px; accent-color:#10b981; cursor:pointer;">
                </div>
            </div>
            <div class="unit-cell unit-cell--actions">
                <div class="unit-label">Aksi</div>
                <div class="unit-actions">
                    <button type="button" onclick="removeUnitRow(${idx})" style="width:30px; height:30px; background:#fee2e2; color:#991b1b; border:none; border-radius:8px; cursor:pointer; font-weight:900;">✕</button>
                </div>
            </div>
        `;

        container.appendChild(row);
        wireMoneyInput(row, 'purchase_price');
        wireMoneyInput(row, 'sell_price_ecer');
        wireMoneyInput(row, 'sell_price_grosir');
        wireMoneyInput(row, 'sell_price_jual1');
        wireMoneyInput(row, 'sell_price_jual2');
        wireMoneyInput(row, 'sell_price_jual3');
        wireMoneyInput(row, 'sell_price_minimal');
        updateAllRowStyles();
        syncMasterFromBase();
    }

    document.addEventListener('DOMContentLoaded', () => {
        addUnitRow({ is_base_unit: true, conversion_factor: 1 });

        document.getElementById('unit-rows-container')?.addEventListener('input', (e) => {
            const base = getBaseRowEl();
            if (!base) return;
            if (base.contains(e.target)) syncMasterFromBase();
        });

        document.getElementById('btnApplyMarkup')?.addEventListener('click', applyMarkup);

        document.getElementById('product-form')?.addEventListener('submit', () => {
            syncMasterFromBase();
        });

        syncMasterFromBase();
    });
    </script>
</x-app-layout>
