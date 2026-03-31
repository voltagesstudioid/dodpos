<x-app-layout>
    <x-slot name="header">Penerimaan Barang Gudang (Non-PO)</x-slot>

    <div class="page-container" style="max-width: 1100px; margin: 0 auto;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Terima Barang Masuk</div>
                <div class="page-header-subtitle">Penerimaan barang yang tidak terkait Purchase Order</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('gudang.terimapo.index') }}" class="btn-secondary">🛒 Terima dari PO</a>
                <a href="{{ route('gudang.penerimaan') }}" class="btn-secondary">← Riwayat</a>
            </div>
        </div>

        <div class="panel" style="background:#f0fdf4;border-color:#bbf7d0;margin-bottom:1rem;">
            <div class="panel-body" style="padding: 1rem 1.25rem;">
                <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                    <div style="font-size:1.25rem;line-height:1;flex-shrink:0;">📦</div>
                    <div>
                        <div style="font-weight:800;color:#166534;margin-bottom:0.25rem;">Penerimaan Barang Gudang (Non-PO)</div>
                        <div style="font-size:0.8125rem;color:#15803d;line-height:1.6;">
                            Gunakan form ini untuk mencatat barang masuk yang <strong>tidak berasal dari Purchase Order</strong>:
                            retur pelanggan, stok awal, koreksi temuan fisik, konsinyasi, atau transfer antar gudang.
                            Jika barang datang dari PO, gunakan menu
                            <a href="{{ route('pembelian.order') }}" style="color:#166534;font-weight:800;text-decoration:underline;">Pembelian → Purchase Order → Terima Barang</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div style="font-weight:900;margin-bottom:0.25rem;">Terdapat kesalahan</div>
                <ul style="margin:0;padding-left:1.1rem;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('gudang.penerimaan.store') }}" method="POST">
            @csrf

            <div class="panel" style="margin-bottom:1rem;">
                <div class="panel-header">
                    <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                        <span class="badge badge-indigo">1</span>
                        <div>
                            <div class="panel-title">Sumber & Referensi Barang</div>
                            <div class="panel-subtitle">Alasan penerimaan dan nomor dokumen</div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="padding:1.25rem;">
                    <div class="form-row">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Sumber / Alasan Penerimaan <span class="required">*</span></label>
                            <select name="source_type" class="form-input @error('source_type') input-error @enderror" required>
                                <option value="">-- Pilih Sumber --</option>
                                @foreach($sourceTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('source_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('source_type') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">No. Referensi / Surat Jalan <span class="required">*</span></label>
                            <input
                                type="text"
                                name="reference_number"
                                value="{{ old('reference_number', 'IN-'.date('Ymd-His')) }}"
                                class="form-input @error('reference_number') input-error @enderror"
                                required
                            >
                            @error('reference_number') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-bottom:1rem;">
                <div class="panel-header">
                    <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                        <span class="badge badge-indigo">2</span>
                        <div>
                            <div class="panel-title">Detail Produk & Stok</div>
                            <div class="panel-subtitle">Produk, jumlah diterima, dan tujuan gudang</div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="padding:1.25rem;">
                    <div class="form-row">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Produk <span class="required">*</span></label>
                            <select name="product_id" id="product_id" class="form-input @error('product_id') input-error @enderror" required onchange="updateUnitOptions()">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $p)
                                    @php
                                        $units = $p->unitConversions->map(function($uc) {
                                            return ['id' => $uc->unit_id, 'name' => $uc->unit ? $uc->unit->name : null, 'factor' => $uc->conversion_factor, 'is_base' => $uc->is_base_unit];
                                        });
                                        if ($p->unit) {
                                            $units->prepend(['id' => $p->unit_id, 'name' => $p->unit->name, 'factor' => 1, 'is_base' => true]);
                                        }
                                        $unitsData = $units->filter()->values()->all();
                                    @endphp
                                    <option value="{{ $p->id }}" data-units="{{ json_encode($unitsData) }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->sku }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Satuan <span class="required">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-input @error('unit_id') input-error @enderror" required onchange="updateBaseQtyHint()">
                                <option value="">-- Pilih Satuan --</option>
                            </select>
                            <input type="hidden" name="conversion_factor" id="conversion_factor" value="1">
                            @error('unit_id') <div class="form-error">{{ $message }}</div> @enderror
                            <div id="base-qty-hint" style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;"></div>
                        </div>
                    </div>
                    <div class="form-row" style="margin-top:1rem;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Jumlah Diterima (Qty) <span class="required">*</span></label>
                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                value="{{ old('quantity') }}"
                                min="1"
                                class="form-input @error('quantity') input-error @enderror"
                                required
                                placeholder="contoh: 50"
                                oninput="updateBaseQtyHint()"
                            >
                            @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Gudang Tujuan <span class="required">*</span></label>
                            <select name="warehouse_id" class="form-input @error('warehouse_id') input-error @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-bottom:1rem;">
                <div class="panel-header">
                    <div style="display:flex;gap:0.75rem;align-items:flex-start;">
                        <span class="badge badge-indigo">3</span>
                        <div>
                            <div class="panel-title">Informasi Tambahan</div>
                            <div class="panel-subtitle">Batch, expired date, dan catatan</div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="padding:1.25rem;">
                    <div class="form-row">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">No. Batch</label>
                            <input type="text" name="batch_number" value="{{ old('batch_number') }}" class="form-input" placeholder="Nomor batch/lot">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="expired_date" value="{{ old('expired_date') }}" class="form-input">
                        </div>
                    </div>
                    <div class="form-group" style="margin:1rem 0 0;">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" rows="2" class="form-input" placeholder="Keterangan tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-body" style="padding: 1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <div style="color:#64748b;font-size:0.8125rem;">
                        Pastikan sumber, qty, dan gudang tujuan sudah benar.
                    </div>
                    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                        <a href="{{ route('gudang.penerimaan') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">📥 Simpan &amp; Tambah Stok</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentUnits = [];

        function updateUnitOptions() {
            const productSelect = document.getElementById('product_id');
            const unitSelect = document.getElementById('unit_id');
            const selectedOption = productSelect.options[productSelect.selectedIndex];

            unitSelect.innerHTML = '<option value="">-- Pilih Satuan --</option>';
            currentUnits = [];

            if (!selectedOption.value) {
                updateBaseQtyHint();
                return;
            }

            try {
                const unitsData = selectedOption.getAttribute('data-units');
                if (unitsData) {
                    currentUnits = JSON.parse(unitsData).filter(u => u !== null);
                }
            } catch (e) {
                console.error('Error parsing units:', e);
            }

            // Add base unit first
            const baseUnit = currentUnits.find(u => u.is_base) || currentUnits[0];
            if (baseUnit) {
                const option = document.createElement('option');
                option.value = baseUnit.id;
                option.text = baseUnit.name + ' (base)';
                option.setAttribute('data-factor', baseUnit.factor);
                unitSelect.appendChild(option);

                // Add other units
                currentUnits.filter(u => !u.is_base).forEach(u => {
                    const opt = document.createElement('option');
                    opt.value = u.id;
                    opt.text = u.name + ' (x' + u.factor + ')';
                    opt.setAttribute('data-factor', u.factor);
                    unitSelect.appendChild(opt);
                });

                // Select base unit by default
                unitSelect.value = baseUnit.id;
                document.getElementById('conversion_factor').value = baseUnit.factor;
            }

            updateBaseQtyHint();
        }

        function updateBaseQtyHint() {
            const unitSelect = document.getElementById('unit_id');
            const quantityInput = document.getElementById('quantity');
            const conversionFactorInput = document.getElementById('conversion_factor');
            const hintDiv = document.getElementById('base-qty-hint');

            const selectedOption = unitSelect.options[unitSelect.selectedIndex];
            const quantity = parseFloat(quantityInput.value) || 0;
            const factor = parseFloat(selectedOption?.getAttribute('data-factor')) || 1;

            conversionFactorInput.value = factor;

            if (quantity > 0 && factor > 0) {
                const baseQty = Math.round(quantity * factor);
                const unitName = selectedOption?.text?.split(' (')[0] || 'satuan';
                hintDiv.innerHTML = `<strong>${quantity} ${unitName}</strong> = <strong>${baseQty} satuan dasar</strong>`;
            } else {
                hintDiv.textContent = '';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateUnitOptions();
        });
    </script>
</x-app-layout>
