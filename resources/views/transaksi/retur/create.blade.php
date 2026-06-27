<x-app-layout>
    <x-slot name="header">Retur Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</x-slot>

    @push('styles')
    <style>
        .ti-box { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 1.5rem; overflow: hidden; border: 1px solid #e2e8f0; }
        .ti-box-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: space-between; }
        .ti-box-title { font-size: 1.125rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.5rem; }
        .ti-box-body { padding: 1.5rem; }
        .ti-form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
        
        .ti-label { display: block; font-size: 0.8125rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .ti-input, .ti-select { width: 100%; padding: 0.6rem 1rem; border: 1.5px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 0.95rem; color: #1e293b; background: #fff; transition: all 0.2s; outline: none; box-sizing: border-box; }
        .ti-input:focus, .ti-select:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
        
        .ti-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .ti-table th { padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; background: #f8fafc; }
        .ti-table td { padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; }
        .ti-table tr:hover td { background: #f8fafc; }
        
        .ti-item-name { font-weight: 700; color: #0f172a; margin-bottom: 0.2rem; }
        .ti-item-sku { font-size: 0.75rem; color: #64748b; background: #f1f5f9; padding: 0.15rem 0.4rem; border-radius: 4px; display: inline-block; }
        
        .ti-qty-input { width: 90px; padding: 0.5rem; text-align: center; border: 1.5px solid #cbd5e1; border-radius: 6px; font-weight: 700; color: #0f172a; outline: none; transition: 0.2s; }
        .ti-qty-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .ti-qty-input:read-only { background: #f1f5f9; cursor: not-allowed; }
        
        .ti-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; }
        .ti-badge-blue { background: #eff6ff; color: #2563eb; }
        .ti-badge-gray { background: #f1f5f9; color: #64748b; }
        
        .ti-summary { display: flex; justify-content: flex-end; padding: 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; align-items: center; gap: 2rem; }
        .ti-total-label { font-size: 0.875rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .ti-total-value { font-size: 1.5rem; font-weight: 900; color: #ef4444; }
        
        .ti-btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
        .ti-btn-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
        .ti-btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(37,99,235,0.4); color: #fff; }
        .ti-btn-primary:disabled { background: #94a3b8; box-shadow: none; cursor: not-allowed; opacity: 0.7; transform: none; }
        .ti-btn-secondary { background: #fff; color: #475569; border: 1.5px solid #cbd5e1; }
        .ti-btn-secondary:hover { background: #f8fafc; border-color: #94a3b8; color: #1e293b; }
    </style>
    @endpush

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success')) <div class="mb-4 p-4 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 font-bold">❌ {{ session('error') }}</div> @endif

        <div class="ti-box">
            <div class="ti-box-header">
                <div class="ti-box-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    Pilih Item Retur
                </div>
                <a href="{{ route('transaksi.show', $transaksi) }}" class="ti-btn ti-btn-secondary">Kembali</a>
            </div>
            <div class="ti-box-body" style="padding-top: 1rem; padding-bottom: 1rem; background: #fafafa;">
                <div style="color:#64748b;font-size:0.9rem;">
                    Pelanggan: <strong style="color:#0f172a;">{{ $transaksi->customer?->name ?? 'Umum' }}</strong> &nbsp;&bull;&nbsp; 
                    Tanggal Transaksi: <strong style="color:#0f172a;">{{ $transaksi->created_at->format('d/m/Y H:i') }}</strong>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('transaksi.retur.store', $transaksi) }}" id="returForm">
            @csrf

            <div class="ti-box">
                <div class="ti-box-header">
                    <div class="ti-box-title" style="font-size: 1rem;">Opsi Pengembalian</div>
                </div>
                <div class="ti-box-body">
                    <div class="ti-form-grid">
                        <div>
                            <label class="ti-label">Metode Refund</label>
                            <select name="refund_method" id="refundMethod" class="ti-select" required>
                                <option value="tunai" @selected(old('refund_method')==='tunai')>Tunai (Cash)</option>
                                <option value="transfer" @selected(old('refund_method')==='transfer')>Transfer Bank</option>
                                <option value="tanpa_refund" @selected(old('refund_method')==='tanpa_refund')>Potong Hutang / Tanpa Refund</option>
                            </select>
                        </div>
                        <div id="transferField" style="display: {{ old('refund_method') === 'transfer' ? 'block' : 'none' }};">
                            <label class="ti-label">ID Transfer Bank</label>
                            <input type="text" name="refund_reference" id="refundReference" class="ti-input" placeholder="Misal: TRF-BCA-123" value="{{ old('refund_reference') }}" maxlength="100">
                        </div>
                        <div>
                            <label class="ti-label">Catatan Retur</label>
                            <input type="text" name="notes" class="ti-input" placeholder="Alasan retur..." value="{{ old('notes') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="ti-box">
                <div class="ti-box-header">
                    <div class="ti-box-title" style="font-size: 1rem;">Daftar Barang Transaksi</div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="ti-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th style="text-align:center;">Harga</th>
                                <th style="text-align:center;">Terjual</th>
                                <th style="text-align:center;">Sisa Bisa Retur</th>
                                <th style="text-align:center;">Gudang Asal</th>
                                <th style="text-align:center; width:140px;">Qty Diretur</th>
                                <th style="text-align:right;">Subtotal Refund</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $i => $r)
                                @php 
                                    $oldItems = old('items', []); 
                                    $available = $r['qty_available'];
                                @endphp
                                <tr style="{{ $available <= 0 ? 'opacity:0.5;' : '' }}">
                                    <td>
                                        <div class="ti-item-name">{{ $r['product_name'] }}</div>
                                        <div class="ti-item-sku">{{ $r['sku'] ?: 'No SKU' }}</div>
                                    </td>
                                    <td style="text-align:center; font-weight: 700; color:#334155;">
                                        Rp <span class="item-price" data-price="{{ $r['price'] }}">{{ number_format((float) $r['price'], 0, ',', '.') }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        {{ $r['qty_sold'] }} <span style="font-size:0.75rem;color:#64748b;">{{ $r['unit_name'] }}</span>
                                        @if($r['qty_returned'] > 0)
                                            <div style="font-size:0.7rem;color:#ef4444;margin-top:2px;font-weight:700;">(-{{ $r['qty_returned'] }} sdh retur)</div>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="ti-badge {{ $available > 0 ? 'ti-badge-blue' : 'ti-badge-gray' }}">{{ $available }} {{ $r['unit_name'] }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        @if($r['warehouse_id'])
                                            <div style="font-size:0.85rem;font-weight:700;color:#475569;">{{ $r['warehouse_name'] ?? 'Gudang Utama' }}</div>
                                            <input type="hidden" name="items[{{ $i }}][warehouse_id]" value="{{ $r['warehouse_id'] }}">
                                        @else
                                            <select name="items[{{ $i }}][warehouse_id]" class="ti-select" style="padding:0.4rem;font-size:0.85rem;min-width:140px;">
                                                <option value="">Pilih Gudang...</option>
                                                @foreach($warehouses as $wh)
                                                    <option value="{{ $wh->id }}" @selected(($oldItems[$i]['warehouse_id'] ?? '') == $wh->id)>{{ $wh->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $r['detail_id'] }}">
                                        <input type="number"
                                               name="items[{{ $i }}][quantity]"
                                               class="ti-qty-input row-qty"
                                               value="{{ $oldItems[$i]['quantity'] ?? 0 }}"
                                               min="0"
                                               max="{{ $available }}"
                                               {{ $available <= 0 ? 'readonly' : '' }}>
                                    </td>
                                    <td style="text-align:right; font-weight:800; color:#0f172a;">
                                        Rp <span class="row-subtotal">0</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="ti-summary">
                    <div style="text-align:right;">
                        <div class="ti-total-label">Total Estimasi Refund</div>
                        <div class="ti-total-value">Rp <span id="grandTotal">0</span></div>
                    </div>
                    <button type="submit" class="ti-btn ti-btn-primary" id="btnSubmit" disabled>
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Proses Retur
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodSelect = document.getElementById('refundMethod');
            const transferField = document.getElementById('transferField');
            const refInput = document.getElementById('refundReference');
            const qtyInputs = document.querySelectorAll('.row-qty');
            const grandTotalEl = document.getElementById('grandTotal');
            const btnSubmit = document.getElementById('btnSubmit');

            // Handle refund method change
            methodSelect.addEventListener('change', function() {
                if (this.value === 'transfer') {
                    transferField.style.display = 'block';
                    refInput.required = true;
                } else {
                    transferField.style.display = 'none';
                    refInput.required = false;
                }
            });

            // Handle quantity calculations
            function calculateTotals() {
                let grandTotal = 0;
                let totalQty = 0;

                qtyInputs.forEach(input => {
                    let qty = parseInt(input.value) || 0;
                    let max = parseInt(input.getAttribute('max')) || 0;
                    
                    if (qty < 0) { qty = 0; input.value = 0; }
                    if (qty > max) { qty = max; input.value = max; }

                    const row = input.closest('tr');
                    const priceEl = row.querySelector('.item-price');
                    const subtotalEl = row.querySelector('.row-subtotal');
                    
                    const price = parseFloat(priceEl.getAttribute('data-price')) || 0;
                    const subtotal = qty * price;
                    
                    subtotalEl.textContent = subtotal.toLocaleString('id-ID');
                    
                    grandTotal += subtotal;
                    totalQty += qty;
                });

                grandTotalEl.textContent = grandTotal.toLocaleString('id-ID');
                
                // Disable button if no items selected
                btnSubmit.disabled = (totalQty <= 0);
            }

            qtyInputs.forEach(input => {
                input.addEventListener('input', calculateTotals);
                input.addEventListener('change', calculateTotals);
            });

            // Initial calculation (in case of old input)
            calculateTotals();
        });
    </script>
    @endpush
</x-app-layout>

