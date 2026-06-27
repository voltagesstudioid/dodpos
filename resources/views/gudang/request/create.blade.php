<x-app-layout>
    <x-slot name="header">Buat Permintaan Barang</x-slot>

    @push('styles')
    <style>
        .gc-page { max-width: 720px; margin: 0 auto; padding: 0 0 3rem; animation: fadeSlideIn 0.35s ease both; }

        .gc-back {
            display: inline-flex; align-items: center; gap: 0.5rem;
            color: #64748b; text-decoration: none; font-weight: 600;
            font-size: 0.875rem; margin-bottom: 1.25rem; transition: all 0.2s;
        }
        .gc-back:hover { color: #0f172a; transform: translateX(-2px); }

        .gc-alert {
            padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.25rem;
            font-size: 0.875rem; font-weight: 500; animation: fadeSlideIn 0.3s ease;
        }
        .gc-alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .gc-alert ul { margin: 0.5rem 0 0 1.25rem; padding: 0; }
        .gc-alert li { margin-bottom: 0.25rem; }

        .gc-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .gc-card-head {
            padding: 1.5rem 1.75rem; border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(180deg, #f8fafc, #fff);
            display: flex; align-items: center; gap: 1rem;
        }
        .gc-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            color: #fff; flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(99,102,241,0.35);
        }
        .gc-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
        .gc-subtitle { font-size: 0.8125rem; color: #64748b; margin: 0.25rem 0 0; }

        .gc-card-body { padding: 1.75rem; }

        .gc-group { margin-bottom: 1.5rem; }
        .gc-label {
            display: block; font-size: 0.8125rem; font-weight: 700;
            color: #0f172a; margin-bottom: 0.5rem;
        }
        .gc-req { color: #ef4444; margin-left: 2px; }
        .gc-input, .gc-select {
            width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0;
            border-radius: 10px; font-size: 0.875rem; background: #fff;
            color: #0f172a; outline: none; font-family: inherit;
            transition: all 0.2s;
        }
        .gc-input:focus, .gc-select:focus {
            border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }
        .gc-input:hover:not(:focus), .gc-select:hover:not(:focus) { border-color: #c7d2fe; }
        .gc-input::placeholder { color: #94a3b8; }
        .gc-select {
            cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 1rem center;
            padding-right: 2.5rem;
        }
        .gc-textarea { resize: vertical; min-height: 100px; }

        .gc-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .gc-wh-info {
            display: flex; align-items: flex-start; gap: 0.75rem;
            padding: 1rem 1.25rem; border-radius: 12px;
            margin-bottom: 1.5rem; font-size: 0.8125rem;
        }
        .gc-wh-info svg { flex-shrink: 0; margin-top: 2px; }
        .gc-wh-info strong { font-weight: 700; }
        .gc-wh-info-sub { font-size: 0.75rem; margin-top: 0.25rem; }

        .gc-wh-transfer {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            border: 1px solid #c7d2fe; color: #3730a3;
        }
        .gc-wh-transfer .gc-wh-info-sub { color: #6366f1; }

        .gc-wh-po {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #a7f3d0; color: #065f46;
        }
        .gc-wh-po .gc-wh-info-sub { color: #10b981; }

        .gc-card-foot {
            padding: 1.25rem 1.75rem; background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex; justify-content: flex-end; gap: 0.75rem;
        }
        .gc-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.375rem; border-radius: 10px;
            font-size: 0.875rem; font-weight: 700; border: none;
            cursor: pointer; font-family: inherit; transition: all 0.2s;
            text-decoration: none;
        }
        .gc-btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
            box-shadow: 0 4px 14px rgba(99,102,241,0.35);
        }
        .gc-btn-primary:hover:not(:disabled) {
            transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,0.45);
        }
        .gc-btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .gc-btn-ghost {
            background: #fff; color: #64748b; border: 1.5px solid #e2e8f0;
        }
        .gc-btn-ghost:hover { background: #f1f5f9; color: #0f172a; border-color: #cbd5e1; }

        @media (max-width: 640px) {
            .gc-row { grid-template-columns: 1fr; }
            .gc-card-foot { flex-direction: column-reverse; }
            .gc-btn { width: 100%; justify-content: center; }
            .gc-card-body { padding: 1.25rem; }
            .gc-card-head { padding: 1.25rem; }
        }
    </style>
    @endpush

    <div class="gc-page">

        <a href="{{ route('gudang.request.index') }}" class="gc-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar
        </a>

        @if(session('error') || $errors->any())
            <div class="gc-alert gc-alert-danger">
                @if(session('error'))<strong>{{ session('error') }}</strong>@endif
                @if($errors->any())<ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>@endif
            </div>
        @endif

        <div class="gc-card">
            <div class="gc-card-head">
                <div class="gc-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <div>
                    <h1 class="gc-title">Form Permintaan Barang</h1>
                    <p class="gc-subtitle">Ajukan permintaan Purchase Order atau Transfer antar gudang.</p>
                </div>
            </div>

            <form action="{{ route('gudang.request.store') }}" method="POST" id="requestForm">
                @csrf
                <div class="gc-card-body">

                    <div class="gc-group">
                        <label class="gc-label">Produk <span class="gc-req">*</span></label>
                        <input type="text" id="productSearch" class="gc-input" placeholder="Ketik untuk mencari produk..." style="margin-bottom: 0.5rem;">
                        <select name="product_id" id="productSelect" class="gc-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                @php
                                    $unitData = $p->unitConversions->map(fn($c) => [
                                        'id' => $c->unit_id, 'name' => $c->unit->name ?? '?', 'factor' => (float)$c->conversion_factor,
                                    ]);
                                    if ($unitData->isEmpty() && $p->unit) {
                                        $unitData->push(['id' => $p->unit_id, 'name' => $p->unit->name, 'factor' => 1]);
                                    }
                                @endphp
                                <option value="{{ $p->id }}" data-name="{{ strtolower($p->name) }}" data-sku="{{ strtolower($p->sku ?? '') }}" data-units="{{ json_encode($unitData) }}">
                                    {{ $p->sku ? "[$p->sku] " : '' }}{{ $p->name }} (Stok: {{ $p->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="gc-row">
                        <div class="gc-group">
                            <label class="gc-label">Jenis Permintaan <span class="gc-req">*</span></label>
                            <select name="type" id="typeSelect" class="gc-select" required>
                                <option value="po">Purchase Order (Beli Baru)</option>
                                <option value="transfer">Transfer (Dari Gudang Lain)</option>
                            </select>
                        </div>
                        <div class="gc-group">
                            <label class="gc-label">Satuan</label>
                            <select name="unit_id" id="unitSelect" class="gc-select">
                                <option value="">-- Satuan Dasar --</option>
                            </select>
                        </div>
                    </div>

                    @php
                        $role = strtolower(auth()->user()->role);
                        $ownWh = \App\Support\WarehouseConfig::getAllowedId($role);
                        $ownWhName = $ownWh ? \App\Models\Warehouse::find($ownWh)?->name : 'Gudang Anda';
                        $otherWhName = $role === 'admin3'
                            ? (\App\Models\Warehouse::find(\App\Support\WarehouseConfig::getBranchId())?->name ?? 'Gudang Cabang')
                            : (\App\Models\Warehouse::find(\App\Support\WarehouseConfig::getMainId())?->name ?? 'Gudang Utama');
                    @endphp

                    <div class="gc-wh-info gc-wh-transfer" id="warehouseInfo" style="display:none">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            <strong>Transfer otomatis:</strong>
                            {{ $otherWhName }} → <strong>{{ $ownWhName }}</strong>
                            <div class="gc-wh-info-sub">Gudang ditentukan otomatis berdasarkan akun Anda.</div>
                        </div>
                    </div>

                    <div class="gc-wh-info gc-wh-po" id="warehousePo">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            <strong>Stok masuk ke:</strong> {{ $ownWhName }}
                            <div class="gc-wh-info-sub">Barang yang dibeli akan masuk ke gudang Anda.</div>
                        </div>
                    </div>

                    <div class="gc-group">
                        <label class="gc-label">Jumlah <span class="gc-req">*</span></label>
                        <input type="number" name="quantity" class="gc-input" required min="1" placeholder="Contoh: 50">
                    </div>

                    <div class="gc-group" style="margin-bottom: 0;">
                        <label class="gc-label">Catatan</label>
                        <textarea name="notes" rows="3" class="gc-input gc-textarea" placeholder="Alasan atau informasi tambahan..."></textarea>
                    </div>
                </div>

                <div class="gc-card-foot">
                    <a href="{{ route('gudang.request.index') }}" class="gc-btn gc-btn-ghost">Batal</a>
                    <button type="submit" class="gc-btn gc-btn-primary" id="submitBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('requestForm');
        const submitBtn = document.getElementById('submitBtn');
        const searchInput = document.getElementById('productSearch');
        const productSelect = document.getElementById('productSelect');
        const typeSelect = document.getElementById('typeSelect');
        const unitSelect = document.getElementById('unitSelect');
        const whInfo = document.getElementById('warehouseInfo');
        const whPo = document.getElementById('warehousePo');

        function updateWhInfo() {
            const isTransfer = typeSelect.value === 'transfer';
            whInfo.style.display = isTransfer ? 'flex' : 'none';
            whPo.style.display = isTransfer ? 'none' : 'flex';
        }
        typeSelect.addEventListener('change', updateWhInfo);
        updateWhInfo();

        const allProducts = Array.from(productSelect.options).map(o => ({
            value: o.value, text: o.textContent,
            name: o.dataset.name || '', sku: o.dataset.sku || '',
            units: o.dataset.units || ''
        }));

        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            productSelect.innerHTML = '<option value="">-- Pilih Produk --</option>';
            allProducts.filter(p => !p.value || p.name.includes(term) || p.sku.includes(term))
                .forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.value; opt.textContent = p.text;
                    if (p.units) opt.dataset.units = p.units;
                    productSelect.appendChild(opt);
                });
        });

        productSelect.addEventListener('change', function() {
            unitSelect.innerHTML = '<option value="">-- Satuan Dasar --</option>';
            const sel = this.options[this.selectedIndex];
            if (sel && sel.dataset.units) {
                try {
                    JSON.parse(sel.dataset.units).forEach(u => {
                        const opt = document.createElement('option');
                        opt.value = u.id;
                        opt.textContent = `${u.name} (1 = ${u.factor})`;
                        unitSelect.appendChild(opt);
                    });
                } catch(e) {}
            }
        });

        form.addEventListener('submit', () => {
            setTimeout(() => { submitBtn.disabled = true; submitBtn.textContent = 'Mengirim...'; }, 10);
        });
    });
    </script>
</x-app-layout>
