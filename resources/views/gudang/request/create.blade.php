<x-app-layout>
    <x-slot name="header">Buat Permintaan Barang</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-form-container">
            
            {{-- Navigation Back --}}
            <a href="{{ route('gudang.request.index') }}" class="tr-back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar Permintaan
            </a>

            {{-- Main Paper / Document --}}
            <div class="tr-paper">
                
                {{-- Paper Header --}}
                <div class="tr-paper-header">
                    <div class="tr-header-icon bg-indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </div>
                    <div class="tr-header-text">
                        <h1 class="tr-title">Form Pengajuan Barang</h1>
                        <p class="tr-subtitle">Pilih produk yang menipis dan jenis permintaannya untuk ditinjau oleh Supervisor.</p>
                    </div>
                </div>

                {{-- Alerts --}}
                @if(session('error') || $errors->any())
                <div class="tr-paper-alerts">
                    @if(session('error')) 
                        <div class="tr-alert tr-alert-danger">
                            <svg class="tr-alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <span>{{ session('error') }}</span>
                        </div> 
                    @endif

                    @if($errors->any())
                        <div class="tr-alert tr-alert-danger tr-alert-block">
                            <div class="tr-alert-head">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <strong>Terdapat kesalahan input:</strong>
                            </div>
                            <ul>
                                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @endif

                {{-- The Form --}}
                <form action="{{ route('gudang.request.store') }}" method="POST" id="requestForm">
                    @csrf

                    <fieldset class="tr-fieldset tr-fieldset-last">
                        <div class="tr-form-stack">
                            
                            {{-- Pilih Produk --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Pilih Produk <span class="tr-req">*</span></label>
                                <div class="tr-select-wrapper">
                                    <select name="product_id" class="tr-select @error('product_id') is-invalid @enderror" required autofocus>
                                        <option value="">-- Cari atau Pilih Produk --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->sku ? "[$p->sku] " : "" }} {{ $p->name }} (Sisa Stok: {{ $p->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_id') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="tr-grid">
                                {{-- Jenis Permintaan --}}
                                <div class="tr-col-half">
                                    <label class="tr-label">Jenis Permintaan <span class="tr-req">*</span></label>
                                    <div class="tr-select-wrapper">
                                        <select name="type" id="typeSelect" class="tr-select @error('type') is-invalid @enderror" required>
                                            <option value="po" {{ old('type') == 'po' ? 'selected' : '' }}>Purchase Order (Beli Baru)</option>
                                            <option value="transfer" {{ old('type') == 'transfer' ? 'selected' : '' }}>Transfer (Minta dari Cabang)</option>
                                        </select>
                                    </div>
                                    @error('type') <div class="tr-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- Satuan --}}
                                <div class="tr-col-half">
                                    <label class="tr-label">Satuan <span class="tr-text-muted font-normal">(Opsional)</span></label>
                                    <div class="tr-select-wrapper">
                                        <select name="unit_id" id="unitSelect" class="tr-select @error('unit_id') is-invalid @enderror">
                                            <option value="">-- Satuan Dasar --</option>
                                            @foreach($units ?? [] as $u)
                                                <option value="{{ $u->id }}" data-factor="{{ $u->conversion_factor ?? 1 }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }} {{ $u->conversion_factor && $u->conversion_factor != 1 ? '(1 = '.$u->conversion_factor.' base)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="conversion_factor" id="conversionFactor" value="{{ old('conversion_factor', 1) }}">
                                    @error('unit_id') <div class="tr-error">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="tr-form-group">
                                <label class="tr-label">Jumlah (Qty) <span class="tr-req">*</span></label>
                                <input type="number" name="quantity" id="quantityInput" class="tr-input @error('quantity') is-invalid @enderror" required min="1" value="{{ old('quantity') }}" placeholder="Cth: 50">
                                <div class="tr-input-hint" id="qtyHint" style="margin-top:4px; display: none;">
                                    Setara dengan <strong id="baseQty">0</strong> satuan dasar
                                </div>
                                @error('quantity') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>

                            {{-- Gudang Tujuan (Hanya Muncul Jika Tipe: Transfer) --}}
                            <div class="tr-form-group" id="warehouseToGroup" style="display:none; padding: 1rem; background: var(--tr-bg); border-radius: var(--tr-radius-md); border: 1px solid var(--tr-border-light);">
                                <label class="tr-label">Gudang Tujuan <span class="tr-text-muted font-normal">(Barang akan dikirim ke mana?)</span></label>
                                <div class="tr-select-wrapper">
                                    <select name="to_warehouse_id" class="tr-select @error('to_warehouse_id') is-invalid @enderror">
                                        <option value="">-- Pilih Gudang Tujuan --</option>
                                        @foreach(($warehouses ?? []) as $wh)
                                            <option value="{{ $wh->id }}" {{ (string) old('to_warehouse_id') === (string) $wh->id ? 'selected' : '' }}>
                                                {{ $wh->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="tr-input-hint" style="margin-top:6px;">Karena ini adalah permintaan transfer, mohon tentukan gudang mana yang akan menerima barang.</div>
                                @error('to_warehouse_id') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>

                            {{-- Catatan --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Catatan / Alasan Mendesak</label>
                                <textarea name="notes" rows="3" class="tr-input tr-textarea @error('notes') is-invalid @enderror" placeholder="Contoh: Stok display toko sudah habis total, butuh segera dikirim...">{{ old('notes') }}</textarea>
                                @error('notes') <div class="tr-error">{{ $message }}</div> @enderror
                            </div>

                        </div>
                    </fieldset>

                    {{-- Footer Actions --}}
                    <div class="tr-form-footer">
                        <a href="{{ route('gudang.request.index') }}" class="tr-btn tr-btn-light">Batalkan</a>
                        <button type="submit" class="tr-btn tr-btn-primary" id="submitBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('requestForm');
            const submitBtn = document.getElementById('submitBtn');
            const typeSelect = document.getElementById('typeSelect');
            const warehouseToGroup = document.getElementById('warehouseToGroup');
            const toWarehouseSelect = document.querySelector('select[name="to_warehouse_id"]');
            const unitSelect = document.getElementById('unitSelect');
            const conversionFactorInput = document.getElementById('conversionFactor');
            const quantityInput = document.getElementById('quantityInput');
            const qtyHint = document.getElementById('qtyHint');
            const baseQtyDisplay = document.getElementById('baseQty');

            // Update conversion factor when unit changes
            if (unitSelect) {
                unitSelect.addEventListener('change', function() {
                    const selected = unitSelect.options[unitSelect.selectedIndex];
                    const factor = parseFloat(selected.dataset.factor) || 1;
                    conversionFactorInput.value = factor;
                    updateQtyHint();
                });
            }

            // Update hint when quantity changes
            if (quantityInput) {
                quantityInput.addEventListener('input', updateQtyHint);
            }

            function updateQtyHint() {
                if (!qtyHint || !baseQtyDisplay || !quantityInput) return;
                const qty = parseFloat(quantityInput.value) || 0;
                const factor = parseFloat(conversionFactorInput.value) || 1;
                const baseQty = Math.round(qty * factor);
                
                if (factor !== 1 && qty > 0) {
                    baseQtyDisplay.textContent = baseQty;
                    qtyHint.style.display = 'block';
                } else {
                    qtyHint.style.display = 'none';
                }
            }

            if(form && submitBtn) {
                form.addEventListener('submit', function () {
                    // Beri jeda sedikit agar HTML5 Validation tetap berjalan
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="tr-spinner"></span> Mengirim...';
                    }, 10);
                });
            }

            function refreshType() {
                if (!typeSelect || !warehouseToGroup) return;
                const isTransfer = (typeSelect.value || '') === 'transfer';
                
                if (isTransfer) {
                    warehouseToGroup.style.display = 'block';
                    // Optional: Make it required via JS when visible
                    if(toWarehouseSelect) toWarehouseSelect.required = true;
                } else {
                    warehouseToGroup.style.display = 'none';
                    if(toWarehouseSelect) {
                        toWarehouseSelect.required = false;
                        toWarehouseSelect.value = ''; // Reset value if hidden
                    }
                }
            }

            if (typeSelect) {
                typeSelect.addEventListener('change', refreshType);
                refreshType(); // Initialize on page load
            }

            // Initialize qty hint on page load
            updateQtyHint();
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #4f46e5;
            --tr-primary-hover: #4338ca;
            --tr-primary-bg: #e0e7ff;
            --tr-danger: #ef4444;
            --tr-danger-light: #fef2f2;
            --tr-radius-lg: 16px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 4rem; }
        .tr-form-container {
            max-width: 680px; /* Ukuran compact yang pas untuk baca */
            margin: 0 auto;
            padding: 3rem 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--tr-text-main);
        }

        /* ── BACK LINK ── */
        .tr-back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.85rem; font-weight: 600; color: var(--tr-text-muted);
            text-decoration: none; margin-bottom: 1.25rem; transition: color 0.2s;
        }
        .tr-back-link:hover { color: var(--tr-text-main); }

        /* ── PAPER (MAIN CARD) ── */
        .tr-paper {
            background: var(--tr-surface);
            border-radius: var(--tr-radius-lg);
            border: 1px solid var(--tr-border);
            box-shadow: var(--tr-shadow-sm);
            overflow: hidden;
        }

        /* HEADER */
        .tr-paper-header {
            display: flex; align-items: flex-start; gap: 1.25rem;
            padding: 2rem;
            border-bottom: 1px solid var(--tr-border-light);
            background: #ffffff;
        }
        .tr-header-icon {
            width: 52px; height: 52px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .tr-header-icon.bg-indigo { background: var(--tr-primary-bg); color: var(--tr-primary); }
        
        .tr-title { font-size: 1.35rem; font-weight: 800; margin: 0 0 0.4rem 0; letter-spacing: -0.01em; line-height: 1.2; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; font-weight: 500; line-height: 1.5; }

        /* ALERTS */
        .tr-paper-alerts { padding: 1.5rem 2rem 0 2rem; display: flex; flex-direction: column; gap: 1rem; }
        .tr-alert { 
            display: flex; align-items: flex-start; gap: 12px; 
            padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); 
            font-size: 0.85rem; line-height: 1.5; border: 1px solid transparent; 
        }
        .tr-alert-danger { background: var(--tr-danger-light); color: #b91c1c; border-color: #fecaca; }
        
        .tr-alert-block { flex-direction: column; gap: 8px; }
        .tr-alert-head { display: flex; align-items: center; gap: 8px; font-weight: 700; }
        .tr-alert ul { margin: 0; padding-left: 2rem; }
        .tr-alert-icon { flex-shrink: 0; margin-top: 1px; }

        /* ── FIELDSETS & FORM STACK ── */
        .tr-fieldset { padding: 2rem; margin: 0; border: none; border-bottom: 1px dashed var(--tr-border); }
        .tr-fieldset-last { border-bottom: none; }
        .tr-form-stack { display: flex; flex-direction: column; gap: 1.5rem; }
        
        .tr-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .tr-col-half { grid-column: span 1; }
        
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        
        /* ── INPUTS ── */
        .tr-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--tr-text-main); margin-bottom: 2px; }
        .tr-req { color: var(--tr-danger); }
        .font-normal { font-weight: 400; }
        
        .tr-input, .tr-select, .tr-textarea {
            width: 100%; padding: 0.7rem 0.85rem;
            border: 1px solid var(--tr-border);
            border-radius: var(--tr-radius-md);
            font-family: inherit; font-size: 0.9rem; color: var(--tr-text-main);
            background: #f8fafc; outline: none; transition: all 0.2s;
            appearance: none;
        }
        select.tr-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: 16px; background-position: right 12px center; background-repeat: no-repeat;
            padding-right: 2.5rem; cursor: pointer;
        }
        .tr-input:focus, .tr-select:focus, .tr-textarea:focus { border-color: var(--tr-primary); background: #ffffff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15); }
        
        .tr-textarea { resize: vertical; min-height: 90px; line-height: 1.5; }
        .tr-input-hint { font-size: 0.75rem; color: var(--tr-text-muted); line-height: 1.4; }
        
        .is-invalid { border-color: var(--tr-danger) !important; background: var(--tr-danger-light) !important; }
        .tr-error { font-size: 0.75rem; color: var(--tr-danger); font-weight: 600; margin-top: 4px; }

        /* Custom Select Wrapper for native dropdown styling */
        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after {
            content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            width: 10px; height: 10px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: contain; background-repeat: no-space; pointer-events: none;
        }

        /* ── FOOTER ACTIONS ── */
        .tr-form-footer {
            display: flex; justify-content: flex-end; align-items: center; gap: 1rem;
            padding: 1.5rem 2rem; background: #f8fafc; border-top: 1px solid var(--tr-border);
        }
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 0.7rem 1.4rem; border-radius: var(--tr-radius-md); font-size: 0.9rem; 
            font-family: inherit; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s;
        }
        .tr-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
        
        .tr-btn-light { background: transparent; color: var(--tr-text-muted); }
        .tr-btn-light:hover { color: var(--tr-text-main); background: #e2e8f0; }
        .tr-btn-primary { background: var(--tr-primary); color: #ffffff; box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover:not(:disabled) { background: var(--tr-primary-hover); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(79, 70, 229, 0.3); }

        /* Spinner */
        .tr-spinner { width: 16px; height: 16px; border: 2.5px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: tr-spin 0.8s linear infinite; }
        @keyframes tr-spin { to { transform: rotate(360deg); } }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .tr-form-container { padding: 1.5rem 1rem; }
            .tr-paper-header { padding: 1.5rem; flex-direction: column; align-items: flex-start; gap: 1rem; }
            .tr-paper-alerts { padding: 1.5rem 1.5rem 0 1.5rem; }
            .tr-fieldset { padding: 1.5rem; }
            .tr-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .tr-form-footer { flex-direction: column-reverse; padding: 1.5rem; }
            .tr-form-footer .tr-btn { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>