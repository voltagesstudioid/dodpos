<x-app-layout>
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --tc-primary: #4f46e5; --tc-primary-hover: #4338ca; --tc-primary-light: #eef2ff;
            --tc-success: #10b981; --tc-success-bg: #ecfdf5; --tc-success-text: #065f46;
            --tc-danger: #ef4444; --tc-danger-bg: #fef2f2; --tc-danger-text: #b91c1c; --tc-danger-border: #fecaca;
            --tc-warning: #f59e0b; --tc-warning-bg: #fffbeb;
            --tc-rose: #f43f5e; --tc-rose-bg: #fff1f2; --tc-rose-text: #be123c;
            --tc-green: #10b981; --tc-green-bg: #dcfce7; --tc-green-text: #065f46;
            --tc-bg: #f8fafc; --tc-card: #ffffff; --tc-border: #e2e8f0; --tc-border-light: #f1f5f9;
            --tc-text: #0f172a; --tc-text-secondary: #64748b; --tc-text-muted: #94a3b8;
            --tc-radius-sm: 6px; --tc-radius: 10px; --tc-radius-md: 12px; --tc-radius-lg: 14px;
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .tc-wrapper { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem; }

        /* Header */
        .tc-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .tc-title-group { display: flex; flex-direction: column; gap: 0.25rem; }
        .tc-title { font-size: 1.375rem; font-weight: 800; color: var(--tc-text); margin: 0; display: flex; align-items: center; gap: 0.625rem; }
        .tc-title-icon { width: 36px; height: 36px; background: var(--tc-primary-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tc-subtitle { color: var(--tc-text-secondary); font-size: 0.85rem; margin: 0; font-weight: 500; }
        .tc-back-link { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.5rem 1rem; border-radius: var(--tc-radius-sm); font-size: 0.85rem; font-weight: 600; color: var(--tc-text-secondary); background: var(--tc-card); border: 1px solid var(--tc-border); text-decoration: none; transition: all 0.2s; }
        .tc-back-link:hover { background: var(--tc-bg); color: var(--tc-text); }

        /* Alerts */
        .tc-alert { display: flex; align-items: flex-start; gap: 0.625rem; padding: 0.875rem 1.125rem; border-radius: var(--tc-radius); margin-bottom: 1rem; font-size: 0.85rem; font-weight: 500; border: 1px solid; }
        .tc-alert-danger { background: var(--tc-danger-bg); color: var(--tc-danger-text); border-color: var(--tc-danger-border); }
        .tc-alert-danger ul { margin: 0.35rem 0 0; padding-left: 1.25rem; }

        /* Steps */
        .tc-step { background: var(--tc-card); border: 1px solid var(--tc-border); border-radius: var(--tc-radius-lg); padding: 1.5rem; margin-bottom: 1rem; }
        .tc-step-header { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 1.25rem; padding-bottom: 0.875rem; border-bottom: 1px solid var(--tc-border-light); }
        .tc-step-badge { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; flex-shrink: 0; color: #fff; }
        .tc-step-badge.indigo { background: var(--tc-primary); }
        .tc-step-badge.rose { background: var(--tc-rose); }
        .tc-step-badge.green { background: var(--tc-success); }
        .tc-step-title { font-weight: 700; color: var(--tc-text); font-size: 1rem; }

        /* Form elements */
        .tc-form-group { display: flex; flex-direction: column; gap: 0.375rem; margin-bottom: 0.875rem; }
        .tc-formgroup:last-child { margin-bottom: 0; }
        .tc-label { font-size: 0.8rem; font-weight: 600; color: var(--tc-text); }
        .tc-label .required { color: var(--tc-danger); }
        .tc-input, .tc-select { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--tc-border); border-radius: var(--tc-radius-sm); font-size: 0.85rem; font-family: inherit; background: var(--tc-card); color: var(--tc-text); transition: border-color 0.2s, box-shadow 0.2s; }
        .tc-input:focus, .tc-select:focus { outline: none; border-color: var(--tc-primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        .tc-input.mono { font-family: 'Cascadia Code', 'Fira Code', monospace; background: var(--tc-bg); }
        .tc-input.error { border-color: var(--tc-danger); }
        .tc-textarea { resize: vertical; }
        .tc-error-msg { font-size: 0.75rem; color: var(--tc-danger-text); }
        .tc-hint { font-size: 0.75rem; color: var(--tc-text-muted); margin-top: 0.25rem; display: flex; align-items: center; gap: 0.25rem; }

        /* Reference row */
        .tc-ref-row { display: flex; flex-wrap: wrap; gap: 1rem; }
        .tc-ref-doc { flex: 1 1 260px; }
        .tc-ref-items { flex: 2 1 320px; }

        /* Item list */
        .tc-items-header { display: grid; grid-template-columns: 32px minmax(0,1fr) 110px 110px 80px; gap: 0.5rem; align-items: center; font-size: 0.7rem; font-weight: 700; color: var(--tc-text-muted); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.5rem; padding: 0 0.25rem; }
        .tc-item-row { display: grid; grid-template-columns: 32px minmax(0,1fr) 110px 110px 80px; gap: 0.5rem; align-items: center; background: var(--tc-bg); border: 1px solid var(--tc-border); border-radius: var(--tc-radius); padding: 0.625rem; }
        .tc-item-index { width: 26px; height: 26px; border-radius: 50%; background: var(--tc-primary-light); color: var(--tc-primary); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; }
        .tc-qty-preview { display: block; margin-top: 3px; font-size: 0.72rem; color: var(--tc-text-muted); }
        .tc-btn-remove { border: 1px solid var(--tc-danger-border); background: var(--tc-danger-bg); color: var(--tc-danger-text); border-radius: var(--tc-radius-sm); height: 38px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: background 0.2s; font-family: inherit; }
        .tc-btn-remove:hover { background: #ffe4e6; }
        .tc-items-footer { margin-top: 0.625rem; }
        .tc-btn-add { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.5rem 1rem; border: 1px dashed var(--tc-primary); background: var(--tc-primary-light); color: var(--tc-primary); border-radius: var(--tc-radius-sm); font-size: 0.8rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; }
        .tc-btn-add:hover { background: #e0e7ff; }

        /* Warehouse Grid */
        .tc-wh-grid { display: grid; grid-template-columns: 1fr auto 1fr; gap: 1rem; align-items: stretch; }
        .tc-wh-card { background: var(--tc-card); border: 1px solid var(--tc-border); border-radius: var(--tc-radius-lg); padding: 1.5rem; display: flex; flex-direction: column; }
        .tc-wh-card.from { border-top: 4px solid var(--tc-rose); }
        .tc-wh-card.to { border-top: 4px solid var(--tc-green); }
        .tc-wh-label { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; }
        .tc-wh-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tc-wh-icon.from { background: var(--tc-rose-bg); }
        .tc-wh-icon.to { background: var(--tc-green-bg); }
        .tc-wh-name { font-weight: 700; font-size: 0.95rem; }
        .tc-wh-name.from { color: var(--tc-rose-text); }
        .tc-wh-name.to { color: var(--tc-green-text); }
        .tc-wh-arrow { display: flex; align-items: center; justify-content: center; padding: 0 0.25rem; }
        .tc-wh-arrow-circle { width: 48px; height: 48px; background: var(--tc-primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(79,70,229,0.15); }

        /* Notes section */
        .tc-notes-row { display: flex; flex-wrap: wrap; gap: 1.5rem; }
        .tc-notes-info { flex: 1 1 200px; }
        .tc-notes-text { flex: 2 1 320px; }
        .tc-fifo-box { display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; background: var(--tc-warning-bg); border: 1px solid #fde68a; border-radius: var(--tc-radius-sm); font-size: 0.8rem; color: #92400e; }

        /* Sticky footer */
        .tc-footer { position: sticky; bottom: 0; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); border-top: 1px solid var(--tc-border); padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; margin: 0 -1rem -1.5rem; border-radius: 0 0 var(--tc-radius-lg) var(--tc-radius-lg); }
        .tc-btn { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.625rem 1.25rem; border-radius: var(--tc-radius-sm); font-size: 0.85rem; font-weight: 600; border: 1px solid transparent; cursor: pointer; text-decoration: none; transition: all 0.2s; font-family: inherit; }
        .tc-btn-secondary { background: var(--tc-card); color: var(--tc-text-secondary); border-color: var(--tc-border); }
        .tc-btn-secondary:hover { background: var(--tc-bg); }
        .tc-btn-primary { background: var(--tc-primary); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,0.25); }
        .tc-btn-primary:hover { background: var(--tc-primary-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,70,229,0.3); }

        /* Responsive */
        @media (max-width: 1024px) {
            .tc-items-header, .tc-item-row { grid-template-columns: 28px minmax(0,1fr) 95px 95px 72px; }
        }
        @media (max-width: 768px) {
            .tc-wh-grid { grid-template-columns: 1fr; }
            .tc-wh-arrow { padding: 0.5rem 0; }
            .tc-wh-arrow-circle { transform: rotate(90deg); }
            .tc-items-header { display: none; }
            .tc-item-row { grid-template-columns: 1fr; gap: 0.5rem; padding: 0.75rem; }
            .tc-btn-remove { width: 100%; }
            .tc-footer { flex-direction: column-reverse; }
            .tc-btn { width: 100%; justify-content: center; }
        }
    </style>
    @endpush

    <div class="tc-wrapper">
        {{-- Header --}}
        <div class="tc-header">
            <div class="tc-title-group">
                <h1 class="tc-title">
                    <span class="tc-title-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M4 20L21 3"/><path d="M21 16v5h-5"/><path d="M15 15l6 6"/><path d="M4 4l5 5"/></svg>
                    </span>
                    Form Transfer Stok Antar Gudang
                </h1>
                <p class="tc-subtitle">Pindahkan stok dari satu gudang ke gudang lain. Stok global tidak berubah.</p>
            </div>
            <a href="{{ route('gudang.transfer') }}" class="tc-back-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                Riwayat Transfer
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
        <div class="tc-alert tc-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="tc-alert tc-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('gudang.transfer.store') }}">
            @csrf

            {{-- Step 1: Reference & Items --}}
            <div class="tc-step">
                <div class="tc-step-header">
                    <span class="tc-step-badge indigo">1</span>
                    <span class="tc-step-title">Data Referensi & Barang</span>
                </div>
                <div class="tc-ref-row">
                    <div class="tc-ref-doc">
                        <div class="tc-formgroup">
                            <label class="tc-label">No. Dokumen Transfer <span class="required">*</span></label>
                            <input type="text" name="reference_number" value="{{ old('reference_number', 'TRF-'.date('Ymd-His')) }}" class="tc-input mono" readonly required>
                            @error('reference_number') <div class="tc-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="tc-ref-items">
                        <div class="tc-formgroup" style="margin-bottom:0;">
                            <label class="tc-label">Daftar Barang yang Ditransfer <span class="required">*</span></label>
                            <div class="tc-items-header">
                                <div>#</div>
                                <div>Produk</div>
                                <div>Satuan</div>
                                <div>Qty</div>
                                <div>Aksi</div>
                            </div>
                            <div id="transfer-items-wrap" style="display:flex; flex-direction:column; gap:0.5rem;">
                                @php $oldItems = old('items', [['product_id' => '', 'quantity' => '']]); @endphp
                                @foreach($oldItems as $idx => $oldItem)
                                <div class="tc-item-row">
                                    <div class="tc-item-index">{{ $idx + 1 }}</div>
                                    <div>
                                        <select name="items[{{ $idx }}][product_id]" class="tc-select" required>
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ (string)($oldItem['product_id'] ?? '') === (string)$p->id ? 'selected' : '' }}>
                                                {{ $p->sku }} – {{ $p->name }} (Stok: {{ ($maskStock ?? false) ? 'Terkunci' : $p->stock }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <select name="items[{{ $idx }}][unit_factor]" class="tc-select transfer-unit-select" required>
                                            <option value="1" data-label="Satuan Dasar" {{ (string)($oldItem['unit_factor'] ?? '1') === '1' ? 'selected' : '' }}>Satuan Dasar</option>
                                        </select>
                                        <input type="hidden" name="items[{{ $idx }}][unit_label]" value="{{ $oldItem['unit_label'] ?? 'Satuan Dasar' }}" class="transfer-unit-label">
                                    </div>
                                    <div>
                                        <input type="number" min="0.0001" step="any" name="items[{{ $idx }}][quantity]" value="{{ $oldItem['quantity'] ?? '' }}" class="tc-input transfer-qty-input" placeholder="Qty" required>
                                        <small class="tc-qty-preview">= 0 satuan dasar</small>
                                    </div>
                                    <button type="button" class="tc-btn-remove remove-item-btn" onclick="removeTransferItem(this)">Hapus</button>
                                </div>
                                @endforeach
                            </div>
                            <div class="tc-items-footer">
                                <button type="button" class="tc-btn-add" onclick="addTransferItem()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    Tambah Barang
                                </button>
                            </div>
                            @error('items') <div class="tc-error-msg">{{ $message }}</div> @enderror
                            @error('items.*.product_id') <div class="tc-error-msg">{{ $message }}</div> @enderror
                            @error('items.*.unit_factor') <div class="tc-error-msg">{{ $message }}</div> @enderror
                            @error('items.*.quantity') <div class="tc-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2: From / To Warehouse --}}
            <div class="tc-wh-grid" style="margin-bottom:1rem;">
                <div class="tc-wh-card from">
                    <div class="tc-wh-label">
                        <span class="tc-wh-icon from">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#be123c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 7l-10 10"/><path d="M8 7h9v9"/></svg>
                        </span>
                        <span class="tc-wh-name from">Gudang Asal (Pengirim)</span>
                    </div>
                    <div class="tc-formgroup" style="margin-bottom:0;">
                        <label class="tc-label">Dari Gudang <span class="required">*</span></label>
                        <select name="from_warehouse_id" id="from_warehouse_id" class="tc-select {{ $errors->has('from_warehouse_id') ? 'error' : '' }}" required>
                            <option value="">-- Pilih Gudang Asal --</option>
                            @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ old('from_warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('from_warehouse_id') <div class="tc-error-msg">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="tc-wh-arrow">
                    <div class="tc-wh-arrow-circle">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    </div>
                </div>

                <div class="tc-wh-card to">
                    <div class="tc-wh-label">
                        <span class="tc-wh-icon to">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17l10-10"/><path d="M16 17H7V8"/></svg>
                        </span>
                        <span class="tc-wh-name to">Gudang Tujuan (Penerima)</span>
                    </div>
                    <div class="tc-formgroup" style="margin-bottom:0;">
                        <label class="tc-label">Ke Gudang <span class="required">*</span></label>
                        <select name="to_warehouse_id" id="to_warehouse_id" class="tc-select {{ $errors->has('to_warehouse_id') ? 'error' : '' }}" required>
                            <option value="">-- Pilih Gudang Tujuan --</option>
                            @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ old('to_warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('to_warehouse_id') <div class="tc-error-msg">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Step 3: Notes & Finalize --}}
            <div class="tc-step">
                <div class="tc-step-header">
                    <span class="tc-step-badge green">3</span>
                    <span class="tc-step-title">Rincian Transfer & Finalisasi</span>
                </div>
                <div class="tc-notes-row">
                    <div class="tc-notes-info">
                        <div class="tc-formgroup">
                            <label class="tc-label">Jumlah per Item</label>
                            <div class="tc-fifo-box">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Diisi per baris barang. Stok ditarik FIFO.
                            </div>
                        </div>
                    </div>
                    <div class="tc-notes-text">
                        <div class="tc-formgroup">
                            <label class="tc-label">Catatan Tambahan (Alasan/Keterangan)</label>
                            <textarea name="notes" rows="2" class="tc-input tc-textarea" placeholder="Opsional, misal: Permintaan darurat toko B...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="tc-footer">
                    <a href="{{ route('gudang.transfer') }}" class="tc-btn tc-btn-secondary">Batalkan</a>
                    <button type="submit" class="tc-btn tc-btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        Proses Transfer
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- JSON data for JS --}}
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

    @push('scripts')
    <script>
    (function() {
        const stocksData = (function(){ const el = document.getElementById('transfer-stocks-json'); try { return JSON.parse(el?.textContent || '{}'); } catch(_) { return {}; } })();
        const unitsData = (function(){ const el = document.getElementById('transfer-units-json'); try { return JSON.parse(el?.textContent || '{}'); } catch(_) { return {}; } })();

        function uniqueUnits(units) {
            const map = new Map();
            (units || []).forEach(u => { const key = u.factor + '-' + u.label; if (!map.has(key)) map.set(key, u); });
            return Array.from(map.values()).sort((a, b) => b.factor - a.factor);
        }

        function buildProductOptions() {
            const selects = document.querySelectorAll('#transfer-items-wrap select[name*="[product_id]"]');
            if (!selects.length) return '';
            const opts = selects[0].innerHTML;
            return opts;
        }

        window.addTransferItem = function() {
            const wrap = document.getElementById('transfer-items-wrap');
            const index = wrap.querySelectorAll('.tc-item-row').length;
            const row = document.createElement('div');
            row.className = 'tc-item-row';
            const optsHtml = buildProductOptions();
            row.innerHTML = `
                <div class="tc-item-index">${index + 1}</div>
                <div><select name="items[${index}][product_id]" class="tc-select" required>${optsHtml}</select></div>
                <div><select name="items[${index}][unit_factor]" class="tc-select transfer-unit-select" required><option value="1" data-label="Satuan Dasar">Satuan Dasar</option></select><input type="hidden" name="items[${index}][unit_label]" value="Satuan Dasar" class="transfer-unit-label"></div>
                <div><input type="number" min="0.0001" step="any" name="items[${index}][quantity]" class="tc-input transfer-qty-input" placeholder="Qty" required><small class="tc-qty-preview">= 0 satuan dasar</small></div>
                <button type="button" class="tc-btn-remove remove-item-btn" onclick="removeTransferItem(this)">Hapus</button>
            `;
            wrap.appendChild(row);
            attachRowListeners(row);
        };

        window.removeTransferItem = function(btn) {
            const wrap = document.getElementById('transfer-items-wrap');
            const rows = wrap.querySelectorAll('.tc-item-row');
            if (rows.length <= 1) { alert('Minimal 1 barang wajib diisi.'); return; }
            btn.closest('.tc-item-row').remove();
            reindexItems();
        };

        function reindexItems() {
            document.querySelectorAll('#transfer-items-wrap .tc-item-row').forEach((row, idx) => {
                const ps = row.querySelector('select[name*="[product_id]"]');
                const us = row.querySelector('.transfer-unit-select');
                const ul = row.querySelector('.transfer-unit-label');
                const qi = row.querySelector('.transfer-qty-input');
                const badge = row.querySelector('.tc-item-index');
                if (ps) ps.name = 'items[' + idx + '][product_id]';
                if (us) us.name = 'items[' + idx + '][unit_factor]';
                if (ul) ul.name = 'items[' + idx + '][unit_label]';
                if (qi) qi.name = 'items[' + idx + '][quantity]';
                if (badge) badge.textContent = idx + 1;
            });
        }

        function refreshUnitOptions(row) {
            const ps = row.querySelector('select[name*="[product_id]"]');
            const us = row.querySelector('.transfer-unit-select');
            const ul = row.querySelector('.transfer-unit-label');
            if (!ps || !us) return;
            const units = uniqueUnits(unitsData[ps.value] || [{ factor: 1, label: 'Satuan Dasar' }]);
            us.innerHTML = '';
            units.forEach((u, i) => {
                const opt = document.createElement('option');
                opt.value = String(u.factor);
                opt.textContent = u.label + ' (x' + u.factor + ')';
                opt.dataset.label = u.label;
                if (i === 0) opt.selected = true;
                us.appendChild(opt);
            });
            if (ul) { const sel = us.options[us.selectedIndex]; ul.value = sel ? (sel.dataset.label || sel.textContent) : 'Satuan Dasar'; }
            updateQtyPreview(row);
        }

        function updateQtyPreview(row) {
            const qi = row.querySelector('.transfer-qty-input');
            const us = row.querySelector('.transfer-unit-select');
            const pv = row.querySelector('.tc-qty-preview');
            const ul = row.querySelector('.transfer-unit-label');
            if (!qi || !us || !pv) return;
            const qty = Number(qi.value || 0);
            const factor = Number(us.value || 1);
            pv.textContent = '= ' + (qty * factor) + ' satuan dasar';
            if (ul) { const sel = us.options[us.selectedIndex]; ul.value = sel ? (sel.dataset.label || sel.textContent) : 'Satuan Dasar'; }
        }

        function refreshFromWarehouseOptions() {
            const fromSel = document.getElementById('from_warehouse_id');
            if (!fromSel) return;
            const currentValue = fromSel.value;
            Array.from(fromSel.options).forEach(opt => {
                if (opt.value === '') return;
                let total = 0;
                document.querySelectorAll('#transfer-items-wrap select[name*="[product_id]"]').forEach(sel => {
                    const pId = sel.value;
                    if (pId && stocksData[pId]) total += Number(stocksData[pId][opt.value] || 0);
                });
                const baseName = opt.text.replace(/ \(Total tersedia:.*\)/, '');
                opt.text = baseName + ' (Total tersedia: ' + total + ')';
                opt.disabled = total <= 0;
            });
            if (currentValue) fromSel.value = currentValue;
        }

        function attachRowListeners(row) {
            row.addEventListener('change', function(e) {
                if (e.target.matches('select[name*="[product_id]"]')) { refreshUnitOptions(row); refreshFromWarehouseOptions(); }
                if (e.target.matches('.transfer-unit-select')) updateQtyPreview(row);
            });
            row.addEventListener('input', function(e) {
                if (e.target.matches('.transfer-qty-input')) updateQtyPreview(row);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const wrap = document.getElementById('transfer-items-wrap');
            wrap.querySelectorAll('.tc-item-row').forEach(function(row) {
                refreshUnitOptions(row);
                attachRowListeners(row);
            });
            refreshFromWarehouseOptions();

            // URL params auto-fill
            var sp = new URLSearchParams(window.location.search);
            var fromId = sp.get('from_warehouse_id');
            var toId = sp.get('to_warehouse_id');
            var notes = sp.get('notes');
            if (fromId) { var f = document.getElementById('from_warehouse_id'); if (f) f.value = fromId; }
            if (toId) { var t = document.getElementById('to_warehouse_id'); if (t) t.value = toId; }
            if (notes) { var n = document.querySelector('textarea[name="notes"]'); if (n) n.value = notes; }
            if (!toId) {
                var toSel = document.getElementById('to_warehouse_id');
                if (toSel) {
                    var options = Array.from(toSel.options);
                    var q = options.find(function(opt) { return /karantina/i.test(opt.textContent || ''); });
                    if (q) toSel.value = q.value;
                }
            }
        });
    })();
    </script>
    @endpush
</x-app-layout>
