<x-app-layout>
    <x-slot name="header">Penerimaan Barang Gudang (Non-PO)</x-slot>
    <style>
        .rc-cr{max-width:900px;margin:0 auto;padding:1.5rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;}
        .rc-bc{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#94a3b8;margin-bottom:1.25rem;}
        .rc-bc a{color:#4f46e5;text-decoration:none;font-weight:600;} .rc-bc a:hover{text-decoration:underline;}
        .rc-top{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .rc-top-left h1{font-size:1.5rem;font-weight:800;color:#0f172a;margin:0;}
        .rc-top-left p{font-size:0.8125rem;color:#64748b;margin:0.25rem 0 0;}
        .rc-top-actions{display:flex;gap:0.5rem;}

        /* Info box */
        .rc-info{display:flex;align-items:flex-start;gap:0.75rem;padding:1rem 1.25rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;margin-bottom:1.25rem;}
        .rc-info-ico{width:36px;height:36px;border-radius:9px;background:#dcfce7;color:#16a34a;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .rc-info-title{font-size:0.8125rem;font-weight:800;color:#166534;margin-bottom:0.125rem;}
        .rc-info-text{font-size:0.75rem;color:#15803d;line-height:1.6;}
        .rc-info-text a{color:#166534;font-weight:800;text-decoration:underline;}

        /* Alert */
        .rc-alert{padding:0.875rem 1.125rem;border-radius:10px;display:flex;align-items:flex-start;gap:0.625rem;font-size:0.8125rem;font-weight:600;margin-bottom:1rem;}
        .rc-alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
        .rc-alert ul{margin:0.375rem 0 0;padding-left:1.25rem;font-weight:500;}

        /* Cards */
        .rc-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;margin-bottom:1.25rem;}
        .rc-card-head{display:flex;align-items:center;gap:0.875rem;padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;background:#fafbfc;}
        .rc-step{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8125rem;font-weight:800;background:#eef2ff;color:#4f46e5;flex-shrink:0;}
        .rc-card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .rc-card-icon.indigo{background:#eef2ff;color:#4f46e5;} .rc-card-icon.emerald{background:#ecfdf5;color:#059669;} .rc-card-icon.amber{background:#fffbeb;color:#d97706;}
        .rc-card-title{font-size:0.9375rem;font-weight:700;color:#1e293b;margin:0;}
        .rc-card-desc{font-size:0.75rem;color:#94a3b8;margin:0.125rem 0 0;}
        .rc-card-body{padding:1.5rem;}

        /* Form */
        .rc-field{margin-bottom:1.125rem;} .rc-field:last-child{margin-bottom:0;}
        .rc-grid{display:grid;gap:1.25rem;} .rc-grid-2{grid-template-columns:1fr 1fr;}
        .rc-label{display:block;font-size:0.75rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.375rem;}
        .rc-req{color:#e11d48;}
        .rc-input,.rc-select,.rc-textarea{width:100%;height:42px;border:1.5px solid #e2e8f0;border-radius:10px;padding:0 0.875rem;font-size:0.875rem;outline:none;transition:all .2s;box-sizing:border-box;font-family:inherit;background:#fff;color:#1e293b;font-weight:500;}
        .rc-input:focus,.rc-select:focus,.rc-textarea:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1);}
        .rc-input.invalid,.rc-select.invalid,.rc-textarea.invalid{border-color:#e11d48;background:#fef2f2;}
        .rc-input.valid{border-color:#10b981;background:#f0fdf4;}
        .rc-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:14px;padding-right:2.5rem;cursor:pointer;}
        .rc-textarea{height:auto;padding:0.625rem 0.875rem;resize:vertical;min-height:72px;}
        .rc-err{font-size:0.6875rem;color:#e11d48;margin-top:0.25rem;font-weight:600;}
        .rc-hint{font-size:0.6875rem;color:#94a3b8;margin-top:0.25rem;display:flex;align-items:center;gap:0.25rem;}

        /* Conversion badge */
        .rc-conv{display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 0.875rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;font-size:0.75rem;color:#1e40af;font-weight:600;margin-top:0.5rem;}
        .rc-conv strong{font-weight:800;}

        /* Footer */
        .rc-footer{position:sticky;bottom:1rem;margin-top:1.5rem;z-index:50;}
        .rc-footer-inner{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:0.875rem 1.25rem;background:rgba(255,255,255,.95);backdrop-filter:blur(12px);border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.08);}
        .rc-footer-info{font-size:0.8125rem;color:#64748b;display:flex;align-items:center;gap:0.5rem;}
        .rc-footer-actions{display:flex;gap:0.5rem;}

        /* Buttons */
        .rc-btn{display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.625rem 1.125rem;border-radius:10px;font-size:0.8125rem;font-weight:700;cursor:pointer;transition:all .2s;border:1px solid transparent;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .rc-btn-primary{background:#4f46e5;color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.25);}
        .rc-btn-primary:hover{background:#4338ca;transform:translateY(-1px);}
        .rc-btn-outline{border-color:#e2e8f0;background:#fff;color:#475569;}
        .rc-btn-outline:hover{background:#f8fafc;border-color:#cbd5e1;}
        .rc-btn-dark{background:#1e293b;color:#fff;} .rc-btn-dark:hover{background:#0f172a;}

        @media(max-width:640px){
            .rc-grid-2{grid-template-columns:1fr;}
            .rc-top{flex-direction:column;}
            .rc-footer-inner{flex-direction:column;text-align:center;}
        }
    </style>

    <div class="rc-cr">
        {{-- Breadcrumb --}}
        <div class="rc-bc">
            <a href="{{ route('gudang.penerimaan') }}">Penerimaan Barang</a>
            <span>›</span>
            <span>Terima Barang (Non-PO)</span>
        </div>

        {{-- Header --}}
        <div class="rc-top">
            <div class="rc-top-left">
                <h1>Terima Barang Masuk</h1>
                <p>Penerimaan barang yang tidak terkait Purchase Order</p>
            </div>
            <div class="rc-top-actions">
                <a href="{{ route('gudang.terimapo.index') }}" class="rc-btn rc-btn-dark">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Terima dari PO
                </a>
                <a href="{{ route('gudang.penerimaan') }}" class="rc-btn rc-btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>
                    Riwayat
                </a>
            </div>
        </div>

        {{-- Info box --}}
        <div class="rc-info">
            <div class="rc-info-ico">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </div>
            <div>
                <div class="rc-info-title">Penerimaan Barang Gudang (Non-PO)</div>
                <div class="rc-info-text">
                    Gunakan form ini untuk mencatat barang masuk yang <strong>tidak berasal dari Purchase Order</strong>:
                    retur pelanggan, stok awal, koreksi temuan fisik, konsinyasi, atau transfer antar gudang.
                    Jika barang datang dari PO, gunakan menu
                    <a href="{{ route('pembelian.order') }}">Pembelian → Purchase Order → Terima Barang</a>.
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
        <div class="rc-alert rc-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="rc-alert rc-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <div>
                <strong>Periksa kembali:</strong>
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        </div>
        @endif

        <form action="{{ route('gudang.penerimaan.store') }}" method="POST" novalidate>
            @csrf

            {{-- CARD 1: SUMBER & REFERENSI --}}
            <div class="rc-card">
                <div class="rc-card-head">
                    <div class="rc-step">1</div>
                    <div class="rc-card-icon indigo">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <div>
                        <div class="rc-card-title">Sumber & Referensi Barang</div>
                        <div class="rc-card-desc">Alasan penerimaan dan nomor dokumen</div>
                    </div>
                </div>
                <div class="rc-card-body">
                    <div class="rc-grid rc-grid-2">
                        <div class="rc-field">
                            <label class="rc-label">Sumber / Alasan Penerimaan <span class="rc-req">*</span></label>
                            <select name="source_type" class="rc-select @error('source_type') invalid @enderror" required>
                                <option value="">-- Pilih Sumber --</option>
                                @foreach($sourceTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('source_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('source_type') <div class="rc-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="rc-field">
                            <label class="rc-label">No. Referensi / Surat Jalan <span class="rc-req">*</span></label>
                            <input type="text" name="reference_number" value="{{ old('reference_number', 'IN-'.date('Ymd-His')) }}" class="rc-input @error('reference_number') invalid @enderror" required style="font-family:ui-monospace,SFMono-Regular,monospace;font-weight:700;">
                            <div class="rc-hint">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                Dibuat otomatis, bisa diubah
                            </div>
                            @error('reference_number') <div class="rc-err">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: DETAIL PRODUK & STOK --}}
            <div class="rc-card">
                <div class="rc-card-head">
                    <div class="rc-step">2</div>
                    <div class="rc-card-icon emerald">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    </div>
                    <div>
                        <div class="rc-card-title">Detail Produk & Stok</div>
                        <div class="rc-card-desc">Produk, jumlah diterima, dan tujuan gudang</div>
                    </div>
                </div>
                <div class="rc-card-body">
                    <div class="rc-grid rc-grid-2">
                        <div class="rc-field">
                            <label class="rc-label">Produk <span class="rc-req">*</span></label>
                            <select name="product_id" id="product_id" class="rc-select @error('product_id') invalid @enderror" required onchange="updateUnitOptions()">
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
                            @error('product_id') <div class="rc-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="rc-field">
                            <label class="rc-label">Satuan <span class="rc-req">*</span></label>
                            <select name="unit_id" id="unit_id" class="rc-select @error('unit_id') invalid @enderror" required onchange="updateBaseQtyHint()">
                                <option value="">-- Pilih Satuan --</option>
                            </select>
                            <input type="hidden" name="conversion_factor" id="conversion_factor" value="1">
                            @error('unit_id') <div class="rc-err">{{ $message }}</div> @enderror
                            <div id="base-qty-hint"></div>
                        </div>
                    </div>
                    <div class="rc-grid rc-grid-2">
                        <div class="rc-field">
                            <label class="rc-label">Jumlah Diterima (Qty) <span class="rc-req">*</span></label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="1" class="rc-input @error('quantity') invalid @enderror" required placeholder="Contoh: 50" oninput="updateBaseQtyHint()">
                            @error('quantity') <div class="rc-err">{{ $message }}</div> @enderror
                        </div>
                        <div class="rc-field">
                            <label class="rc-label">Gudang Tujuan <span class="rc-req">*</span></label>
                            <select name="warehouse_id" class="rc-select @error('warehouse_id') invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                                @endforeach
                            </select>
                            @error('warehouse_id') <div class="rc-err">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 3: INFORMASI TAMBAHAN --}}
            <div class="rc-card">
                <div class="rc-card-head">
                    <div class="rc-step">3</div>
                    <div class="rc-card-icon amber">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </div>
                    <div>
                        <div class="rc-card-title">Informasi Tambahan</div>
                        <div class="rc-card-desc">Batch, expired date, dan catatan (opsional)</div>
                    </div>
                </div>
                <div class="rc-card-body">
                    <div class="rc-grid rc-grid-2">
                        <div class="rc-field">
                            <label class="rc-label">No. Batch</label>
                            <input type="text" name="batch_number" value="{{ old('batch_number') }}" class="rc-input" placeholder="Nomor batch/lot">
                        </div>
                        <div class="rc-field">
                            <label class="rc-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="expired_date" value="{{ old('expired_date') }}" class="rc-input">
                        </div>
                    </div>
                    <div class="rc-field">
                        <label class="rc-label">Catatan</label>
                        <textarea name="notes" rows="2" class="rc-textarea" placeholder="Keterangan tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="rc-footer">
                <div class="rc-footer-inner">
                    <div class="rc-footer-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Pastikan sumber, qty, dan gudang tujuan sudah benar.
                    </div>
                    <div class="rc-footer-actions">
                        <a href="{{ route('gudang.penerimaan') }}" class="rc-btn rc-btn-outline">Batal</a>
                        <button type="submit" class="rc-btn rc-btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Simpan & Tambah Stok
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
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

        const baseUnit = currentUnits.find(u => u.is_base) || currentUnits[0];
        if (baseUnit) {
            const opt = document.createElement('option');
            opt.value = baseUnit.id;
            opt.text = baseUnit.name + ' (base)';
            opt.setAttribute('data-factor', baseUnit.factor);
            unitSelect.appendChild(opt);

            currentUnits.filter(u => !u.is_base).forEach(u => {
                const o = document.createElement('option');
                o.value = u.id;
                o.text = u.name + ' (×' + u.factor + ')';
                o.setAttribute('data-factor', u.factor);
                unitSelect.appendChild(o);
            });

            unitSelect.value = baseUnit.id;
            document.getElementById('conversion_factor').value = baseUnit.factor;
        }

        updateBaseQtyHint();
    }

    function updateBaseQtyHint() {
        const unitSelect = document.getElementById('unit_id');
        const quantityInput = document.getElementById('quantity');
        const convInput = document.getElementById('conversion_factor');
        const hintDiv = document.getElementById('base-qty-hint');

        const selectedOption = unitSelect.options[unitSelect.selectedIndex];
        const quantity = parseFloat(quantityInput.value) || 0;
        const factor = parseFloat(selectedOption?.getAttribute('data-factor')) || 1;

        convInput.value = factor;

        if (quantity > 0 && factor > 0) {
            const baseQty = Math.round(quantity * factor);
            const unitName = selectedOption?.text?.split(' (')[0] || 'satuan';
            hintDiv.innerHTML = `<div class="rc-conv"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg> <strong>${quantity.toLocaleString('id-ID')} ${unitName}</strong> = <strong>${baseQty.toLocaleString('id-ID')} satuan dasar</strong></div>`;
        } else {
            hintDiv.innerHTML = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateUnitOptions();
    });
    </script>
    @endpush
</x-app-layout>
