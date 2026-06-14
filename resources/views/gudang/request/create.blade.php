<x-app-layout>
    <x-slot name="header">Buat Permintaan Barang</x-slot>

    <div class="rc-page">
        <a href="{{ route('gudang.request.index') }}" class="rc-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
        </a>

        {{-- Alerts --}}
        @if(session('error') || $errors->any())
            <div class="rc-alert rc-alert-danger">
                @if(session('error'))<strong>{{ session('error') }}</strong>@endif
                @if($errors->any())<ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>@endif
            </div>
        @endif

        {{-- Main Card --}}
        <div class="rc-card">
            <div class="rc-card-head">
                <div class="rc-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <div>
                    <h1 class="rc-title">Form Permintaan Barang</h1>
                    <p class="rc-subtitle">Ajukan permintaan Purchase Order atau Transfer antar gudang.</p>
                </div>
            </div>

            <form action="{{ route('gudang.request.store') }}" method="POST" id="requestForm">
                @csrf
                <div class="rc-card-body">

                    {{-- Product --}}
                    <div class="rc-group">
                        <label class="rc-label">Produk <span class="rc-req">*</span></label>
                        <input type="text" id="productSearch" class="rc-input" placeholder="Ketik untuk mencari produk...">
                        <select name="product_id" id="productSelect" class="rc-select" required>
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

                    {{-- Type + Unit --}}
                    <div class="rc-row">
                        <div class="rc-group">
                            <label class="rc-label">Jenis Permintaan <span class="rc-req">*</span></label>
                            <select name="type" id="typeSelect" class="rc-select" required>
                                <option value="po">Purchase Order (Beli Baru)</option>
                                <option value="transfer">Transfer (Dari Gudang Lain)</option>
                            </select>
                        </div>
                        <div class="rc-group">
                            <label class="rc-label">Satuan</label>
                            <select name="unit_id" id="unitSelect" class="rc-select">
                                <option value="">-- Satuan Dasar --</option>
                            </select>
                        </div>
                    </div>

                    {{-- Auto warehouse info --}}
                    @php
                        $role = strtolower(auth()->user()->role);
                        $ownWh = \App\Support\WarehouseConfig::getAllowedId($role);
                        $ownWhName = $ownWh ? \App\Models\Warehouse::find($ownWh)?->name : 'Gudang Anda';
                        $otherWhName = $role === 'admin3'
                            ? (\App\Models\Warehouse::find(\App\Support\WarehouseConfig::getBranchId())?->name ?? 'Gudang Cabang')
                            : (\App\Models\Warehouse::find(\App\Support\WarehouseConfig::getMainId())?->name ?? 'Gudang Utama');
                    @endphp
                    <div class="rc-wh-info" id="warehouseInfo" style="display:none">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            <strong>Transfer otomatis:</strong>
                            {{ $otherWhName }} → <strong>{{ $ownWhName }}</strong>
                            <div class="rc-wh-sub">Gudang ditentukan otomatis berdasarkan akun Anda.</div>
                        </div>
                    </div>

                    <div class="rc-wh-info rc-wh-po" id="warehousePo">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            <strong>Stok masuk ke:</strong> {{ $ownWhName }}
                            <div class="rc-wh-sub">Barang yang dibeli akan masuk ke gudang Anda.</div>
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="rc-group">
                        <label class="rc-label">Jumlah <span class="rc-req">*</span></label>
                        <input type="number" name="quantity" class="rc-input" required min="1" placeholder="Contoh: 50">
                    </div>

                    {{-- Notes --}}
                    <div class="rc-group">
                        <label class="rc-label">Catatan</label>
                        <textarea name="notes" rows="3" class="rc-input rc-textarea" placeholder="Alasan atau informasi tambahan..."></textarea>
                    </div>
                </div>

                <div class="rc-card-foot">
                    <a href="{{ route('gudang.request.index') }}" class="rc-btn rc-btn-ghost">Batal</a>
                    <button type="submit" class="rc-btn rc-btn-primary" id="submitBtn">
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

        // Toggle warehouse info based on type
        function updateWhInfo() {
            const isTransfer = typeSelect.value === 'transfer';
            whInfo.style.display = isTransfer ? 'flex' : 'none';
            whPo.style.display = isTransfer ? 'none' : 'flex';
        }
        typeSelect.addEventListener('change', updateWhInfo);
        updateWhInfo();

        // Product search
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

        // Unit conversion
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

        // Submit
        form.addEventListener('submit', () => {
            setTimeout(() => { submitBtn.disabled = true; submitBtn.textContent = 'Mengirim...'; }, 10);
        });
    });
    </script>

    @push('styles')
    <style>
        .rc-page{font-family:'Plus Jakarta Sans',system-ui,sans-serif;max-width:720px;margin:0 auto;padding:1.5rem 1rem}
        .rc-back{display:inline-flex;align-items:center;gap:.4rem;color:#64748b;text-decoration:none;font-weight:600;font-size:.85rem;margin-bottom:1.25rem}
        .rc-back:hover{color:#0f172a}
        .rc-alert{padding:.85rem 1.1rem;border-radius:10px;margin-bottom:1.25rem;font-size:.84rem}
        .rc-alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .rc-alert ul{margin:.4rem 0 0 1.2rem;padding:0}
        .rc-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden}
        .rc-card-head{display:flex;align-items:center;gap:1rem;padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9}
        .rc-icon{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0}
        .rc-title{font-size:1.15rem;font-weight:800;color:#0f172a;margin:0}
        .rc-subtitle{font-size:.82rem;color:#64748b;margin:.2rem 0 0}
        .rc-card-body{padding:1.5rem}
        .rc-card-foot{padding:1rem 1.5rem;background:#f8fafc;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:.65rem}
        .rc-group{margin-bottom:1.25rem}
        .rc-label{display:block;font-size:.84rem;font-weight:700;color:#0f172a;margin-bottom:.4rem}
        .rc-req{color:#ef4444}
        .rc-input,.rc-select{width:100%;padding:.7rem .85rem;border:1px solid #e2e8f0;border-radius:8px;font-size:.88rem;background:#f8fafc;color:#0f172a;outline:none;font-family:inherit;transition:all .15s}
        .rc-input:focus,.rc-select:focus{border-color:#6366f1;background:#fff;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .rc-textarea{resize:vertical;min-height:80px}
        .rc-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        .rc-wh-info{display:flex;align-items:flex-start;gap:.65rem;padding:.85rem 1rem;background:#eef2ff;border:1px solid #c7d2fe;border-radius:10px;margin-bottom:1.25rem;font-size:.82rem;color:#3730a3}
        .rc-wh-info svg{flex-shrink:0;margin-top:2px}
        .rc-wh-sub{font-size:.75rem;color:#6366f1;margin-top:.15rem}
        .rc-wh-po{background:#ecfdf5;border-color:#a7f3d0;color:#065f46}
        .rc-wh-po .rc-wh-sub{color:#10b981}
        .rc-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.7rem 1.2rem;border-radius:8px;font-size:.88rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;transition:all .15s;text-decoration:none}
        .rc-btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;box-shadow:0 2px 8px rgba(99,102,241,.25)}
        .rc-btn-primary:hover:not(:disabled){transform:translateY(-1px);box-shadow:0 4px 14px rgba(99,102,241,.35)}
        .rc-btn-primary:disabled{opacity:.6;cursor:not-allowed}
        .rc-btn-ghost{background:#fff;color:#64748b;border:1px solid #e2e8f0}
        .rc-btn-ghost:hover{background:#f1f5f9;color:#0f172a}
        @media(max-width:640px){
            .rc-row{grid-template-columns:1fr}
            .rc-card-foot{flex-direction:column-reverse}
            .rc-btn{width:100%;justify-content:center}
        }
    </style>
    @endpush
</x-app-layout>
