<x-app-layout>
    <x-slot name="header">Buat Retur Pembelian</x-slot>

    <div class="page-container">
        @if($errors->any())
            <div class="alert alert-danger">❌ {{ $errors->first() }}</div>
        @endif

        <form action="{{ route('pembelian.retur.store') }}" method="POST">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start;">

                {{-- LEFT: Main Form --}}
                <div>
                    <div class="card" style="padding:1.5rem; margin-bottom:1.25rem;">
                        <div style="font-size:0.9rem;font-weight:700;color:#1e293b;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #f1f5f9;">📋 Informasi Retur</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Supplier <span style="color:#ef4444">*</span></label>
                                <select name="supplier_id" class="form-input @error('supplier_id') input-error @enderror" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Referensi PO (opsional)</label>
                                <select name="purchase_order_id" class="form-input">
                                    <option value="">-- Tanpa referensi PO --</option>
                                    @foreach($purchaseOrders as $po)
                                        <option value="{{ $po->id }}" @selected(old('purchase_order_id') == $po->id)>
                                            {{ $po->po_number }} — {{ $po->supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Gudang Sumber Retur <span style="color:#ef4444">*</span></label>
                                <select name="warehouse_id" class="form-input @error('warehouse_id') input-error @enderror" required>
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" @selected(old('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Catatan Tambahan</label>
                                <textarea name="notes" class="form-input" rows="2" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Tanggal Retur <span style="color:#ef4444">*</span></label>
                                <input type="date" name="return_date" class="form-input @error('return_date') input-error @enderror"
                                       value="{{ old('return_date', date('Y-m-d')) }}" required>
                                @error('return_date')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alasan Retur <span style="color:#ef4444">*</span></label>
                                <select name="reason" class="form-input @error('reason') input-error @enderror" required>
                                    <option value="">-- Pilih Alasan --</option>
                                    <option value="Barang rusak / cacat" @selected(old('reason')=='Barang rusak / cacat')>Barang rusak / cacat</option>
                                    <option value="Barang tidak sesuai pesanan" @selected(old('reason')=='Barang tidak sesuai pesanan')>Barang tidak sesuai pesanan</option>
                                    <option value="Kadaluarsa (expired)" @selected(old('reason')=='Kadaluarsa (expired)')>Kadaluarsa (expired)</option>
                                    <option value="Kelebihan pengiriman" @selected(old('reason')=='Kelebihan pengiriman')>Kelebihan pengiriman</option>
                                    <option value="Lainnya" @selected(old('reason')=='Lainnya')>Lainnya</option>
                                </select>
                                @error('reason')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <div class="card" style="padding:1.5rem;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                            <div style="font-size:0.9rem;font-weight:700;color:#1e293b;">📦 Item Retur</div>
                            <button type="button" onclick="addItemRow()" class="btn-primary btn-sm">+ Tambah Item</button>
                        </div>

                        <div id="items-container">
                            <div style="text-align:center;padding:2rem;color:#94a3b8;" id="no-items-msg">
                                Klik <strong>+ Tambah Item</strong> untuk menambahkan barang yang diretur.
                            </div>
                        </div>

                        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;gap:1rem;align-items:center;">
                            <div style="font-size:0.875rem;color:#64748b;">Total Retur:</div>
                            <div style="font-size:1.25rem;font-weight:800;color:#ef4444;" id="grand-total">Rp 0</div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Actions --}}
                <div class="card" style="padding:1.5rem; position:sticky; top:1.5rem;">
                    <div style="font-size:0.9rem;font-weight:700;color:#1e293b;margin-bottom:1rem;">🚀 Simpan Retur</div>
                    <p style="font-size:0.8rem;color:#64748b;margin-bottom:1.25rem;">Retur akan disimpan dalam status <strong>Draft</strong>. Setelah diperiksa, bisa disetujui dan diproses.</p>
                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">💾 Simpan Retur</button>
                    <a href="{{ route('pembelian.retur.index') }}" class="btn-secondary" style="width:100%;justify-content:center;padding:0.75rem;margin-top:0.75rem;">Batal</a>
                </div>
            </div>
        </form>
    </div>

    <script id="retur-products-data" type="application/json">{!! json_encode($productsData, JSON_UNESCAPED_UNICODE) !!}</script>
    <script>
    const allProducts = (function () {
        const el = document.getElementById('retur-products-data');
        if (!el) return [];
        try {
            return JSON.parse(el.textContent || '[]');
        } catch (_) {
            return [];
        }
    })();
    let itemIdx = 0;

    function addItemRow() {
        document.getElementById('no-items-msg').style.display = 'none';
        const idx = itemIdx++;
        const div = document.createElement('div');
        div.id = 'item-row-' + idx;
        div.style.cssText = 'display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:0.5rem;margin-bottom:0.5rem;align-items:center;padding:0.75rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;';

        const prodOpts = allProducts.map(p => `<option value="${p.id}">${p.name} (${p.sku})</option>`).join('');

        div.innerHTML = `
            <div>
                <div style="font-size:0.7rem;color:#64748b;margin-bottom:2px;">Produk</div>
                <select name="items[${idx}][product_id]" class="form-input" style="font-size:0.8rem;padding:0.35rem 0.5rem;" onchange="onProductChange(this, ${idx})" required>
                    <option value="">-- Pilih --</option>${prodOpts}
                </select>
            </div>
            <div>
                <div style="font-size:0.7rem;color:#64748b;margin-bottom:2px;">Satuan</div>
                <select name="items[${idx}][unit_id]" class="form-input" style="font-size:0.8rem;padding:0.35rem 0.5rem;" onchange="onUnitChange(${idx})" required>
                    <option value="">-- Pilih --</option>
                </select>
            </div>
            <div>
                <div style="font-size:0.7rem;color:#64748b;margin-bottom:2px;">Qty</div>
                <input type="number" name="items[${idx}][quantity]" min="1" value="1" class="form-input" style="font-size:0.8rem;padding:0.35rem 0.5rem;" oninput="calcTotal()" required>
            </div>
            <div>
                <div style="font-size:0.7rem;color:#64748b;margin-bottom:2px;">Harga Beli</div>
                <input type="number" name="items[${idx}][price]" min="0" value="0" class="form-input" style="font-size:0.8rem;padding:0.35rem 0.5rem;" oninput="calcTotal()" required>
            </div>
            <button type="button" onclick="removeItem(${idx})" style="background:#fee2e2;border:none;color:#ef4444;border-radius:6px;width:28px;height:28px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">×</button>
        `;
        document.getElementById('items-container').appendChild(div);
    }

    function removeItem(idx) {
        document.getElementById('item-row-' + idx)?.remove();
        if (!document.querySelectorAll('[id^="item-row-"]').length) {
            document.getElementById('no-items-msg').style.display = 'block';
        }
        calcTotal();
    }

    function onProductChange(sel, idx) {
        const productId = parseInt(sel.value || '0', 10);
        const product = allProducts.find(p => p.id === productId);
        const unitSelect = document.querySelector(`select[name="items[${idx}][unit_id]"]`);
        const priceInput = document.querySelector(`input[name="items[${idx}][price]"]`);
        if (!unitSelect || !priceInput) return;

        if (!product) {
            unitSelect.innerHTML = '<option value="">-- Pilih --</option>';
            priceInput.value = 0;
            calcTotal();
            return;
        }

        const conversions = Array.isArray(product.conversions) ? product.conversions : [];
        unitSelect.innerHTML = ['<option value="">-- Pilih --</option>'].concat(
            conversions.map(c => `<option value="${c.unit_id}" data-price="${c.price}">${c.name}${c.factor > 1 ? ' (x'+c.factor+')' : ''}</option>`)
        ).join('');

        if (conversions.length) {
            unitSelect.value = conversions[0].unit_id;
            const opt = unitSelect.options[unitSelect.selectedIndex];
            priceInput.value = opt?.dataset?.price || product.price || 0;
        } else {
            priceInput.value = product.price || 0;
        }
        calcTotal();
    }

    function onUnitChange(idx) {
        const unitSelect = document.querySelector(`select[name="items[${idx}][unit_id]"]`);
        const priceInput = document.querySelector(`input[name="items[${idx}][price]"]`);
        if (!unitSelect || !priceInput) return;
        const opt = unitSelect.options[unitSelect.selectedIndex];
        priceInput.value = opt?.dataset?.price || priceInput.value || 0;
        calcTotal();
    }

    function calcTotal() {
        let total = 0;
        document.querySelectorAll('[id^="item-row-"]').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value || 0);
            const price = parseFloat(row.querySelector('input[name*="[price]"]')?.value || 0);
            total += qty * price;
        });
        document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    </script>
</x-app-layout>
