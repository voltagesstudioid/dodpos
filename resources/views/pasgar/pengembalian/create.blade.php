<x-app-layout>
    <x-slot name="header">Buat Pengembalian Sisa Barang</x-slot>

    <div class="page-container" style="max-width: 980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">Pengembalian Sisa Barang</div>
                <div class="page-header-subtitle">Kembalikan sisa stok dari kendaraan ke gudang utama</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('pasgar.pengembalian.index') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="returnForm" class="btn-primary" id="topSubmitBtn" disabled>↩ Proses</button>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div>❌ Periksa input Anda:</div>
                <div style="margin-top:0.35rem;">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('pasgar.pengembalian.store') }}" id="returnForm">
            @csrf

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Informasi Pengembalian</div>
                        <div class="panel-subtitle">Tanggal, kendaraan sumber, gudang tujuan, dan catatan</div>
                    </div>
                    <span class="badge badge-gray">Status: Draft</span>
                </div>
                <div class="panel-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tanggal Pengembalian <span class="required">*</span></label>
                            <input type="date" name="date" value="{{ old('date', today()->format('Y-m-d')) }}" class="form-input {{ $errors->has('date') ? 'input-error' : '' }}" required>
                            @error('date')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kendaraan Sumber <span class="required">*</span></label>
                            <select name="vehicle_id" id="vehicleSelect" class="form-input {{ $errors->has('vehicle_id') ? 'input-error' : '' }}" required>
                                <option value="">Pilih Kendaraan</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}" {{ (string) old('vehicle_id') === (string) $v->id ? 'selected' : '' }}>
                                        {{ $v->license_plate }} — {{ $v->warehouse?->name ?? 'Tanpa Gudang' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')<div class="form-error">{{ $message }}</div>@enderror
                            <div class="form-hint">Pilih kendaraan, lalu klik “Muat Stok Kendaraan” untuk mengisi item.</div>
                        </div>
                    </div>

                    <div class="form-row" style="margin-top: 0.25rem;">
                        <div class="form-group">
                            <label class="form-label">Gudang Tujuan <span class="required">*</span></label>
                            <select name="to_warehouse_id" class="form-input {{ $errors->has('to_warehouse_id') ? 'input-error' : '' }}" required>
                                <option value="">Pilih Gudang Tujuan</option>
                                @foreach($mainWarehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ (string) old('to_warehouse_id') === (string) $wh->id ? 'selected' : '' }}>
                                        {{ $wh->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_warehouse_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" rows="2" placeholder="Contoh: pengembalian akhir shift" class="form-input" style="resize:vertical;">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top: 1rem;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Daftar Barang yang Dikembalikan</div>
                        <div class="panel-subtitle">Masukkan qty &gt; 0 untuk minimal satu produk</div>
                    </div>
                    <button type="button" id="loadStockBtn" class="btn-secondary" onclick="loadVehicleStock()">🔄 Muat Stok Kendaraan</button>
                </div>
                <div class="panel-body">
                    <div id="stockLoading" style="display:none;" class="alert alert-info" role="alert">⏳ Memuat stok kendaraan...</div>

                    <div class="table-wrapper">
                        <table class="data-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th style="text-align:center;">Stok Tersedia</th>
                                    <th style="text-align:center;">Qty Dikembalikan</th>
                                    <th style="text-align:right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr id="emptyRow">
                                    <td colspan="4" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">↩</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada item</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                                Pilih kendaraan, lalu klik “Muat Stok Kendaraan” untuk menampilkan stok yang bisa dikembalikan.
                                            </div>
                                            <button type="button" class="btn-primary" onclick="loadVehicleStock()">🔄 Muat Stok Kendaraan</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;flex-wrap:wrap;margin-top:1rem;">
                        <a href="{{ route('pasgar.pengembalian.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary" id="submitBtn" disabled>↩ Proses Pengembalian</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let stockData = [];

        async function loadVehicleStock() {
            const vehicleId = document.getElementById('vehicleSelect').value;
            if (!vehicleId) {
                alert('Pilih kendaraan terlebih dahulu.');
                return;
            }

            document.getElementById('stockLoading').style.display = 'block';
            document.getElementById('itemsBody').innerHTML = '';

            try {
                const res = await fetch(`{{ route('pasgar.pengembalian.vehicle-stock') }}?vehicle_id=${vehicleId}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                stockData = await res.json();

                const tbody = document.getElementById('itemsBody');
                if (stockData.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding:2rem; color:#94a3b8;">Tidak ada stok di kendaraan ini.</td></tr>';
                    document.getElementById('submitBtn').disabled = true;
                    document.getElementById('topSubmitBtn').disabled = true;
                } else {
                    tbody.innerHTML = '';
                    stockData.forEach((item, idx) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <input type="hidden" name="items[${idx}][product_id]" value="${item.product_id}">
                                <div style="font-weight:600;">${item.product_name}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">${item.unit}</div>
                            </td>
                            <td style="text-align:center; font-weight:700; color:#10b981;">${item.stock}</td>
                            <td style="text-align:center;">
                                <input type="number" name="items[${idx}][quantity]" min="0" max="${item.stock}" value="0"
                                    class="form-input" style="width:90px; text-align:center;"
                                    onchange="validateQty(this, ${item.stock})">
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="btn-secondary btn-sm" onclick="setMax(this, ${item.stock}, ${idx})">Max</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                    document.getElementById('submitBtn').disabled = false;
                    document.getElementById('topSubmitBtn').disabled = false;
                }
            } catch(e) {
                document.getElementById('itemsBody').innerHTML = '<tr><td colspan="4" style="text-align:center; padding:2rem; color:#ef4444;">Gagal memuat stok. Coba lagi.</td></tr>';
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('topSubmitBtn').disabled = true;
            }

            document.getElementById('stockLoading').style.display = 'none';
        }

        function validateQty(input, max) {
            if (parseInt(input.value) > max) input.value = max;
            if (parseInt(input.value) < 0) input.value = 0;
        }

        function setMax(btn, max, idx) {
            document.querySelector(`input[name="items[${idx}][quantity]"]`).value = max;
        }

        document.getElementById('returnForm').addEventListener('submit', function(e) {
            let hasItem = false;
            document.querySelectorAll('#itemsBody input[name^="items["][name$="[quantity]"]').forEach((qtyInput) => {
                if (parseInt(qtyInput.value) > 0) hasItem = true;
            });
            if (!hasItem) {
                e.preventDefault();
                alert('Masukkan qty > 0 untuk minimal satu produk.');
            }
        });
    </script>
</x-app-layout>
