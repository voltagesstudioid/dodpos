<x-app-layout>
    <x-slot name="header">Buat Setoran Harian</x-slot>

    <div class="page-container" style="max-width:800px;">
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pasgar.setoran.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">💰 Buat Setoran Harian Baru</h2>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Rekap hasil penjualan dan penagihan harian anggota pasgar</p>
            </div>

            <form method="POST" action="{{ route('pasgar.setoran.store') }}" id="setoranForm" style="padding:1.5rem;">
                @csrf

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Anggota Pasgar <span style="color:#ef4444;">*</span></label>
                        <select name="pasgar_member_id" id="memberSelect" class="form-input {{ $errors->has('pasgar_member_id') ? 'input-error' : '' }}" required onchange="loadSummary()">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" {{ old('pasgar_member_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->user->name }} {{ $m->area ? '— '.$m->area : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('pasgar_member_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Setoran <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="deposit_date" id="depositDate" value="{{ old('deposit_date', today()->format('Y-m-d')) }}" class="form-input {{ $errors->has('deposit_date') ? 'input-error' : '' }}" required onchange="loadSummary()">
                        @error('deposit_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Auto-loaded Summary --}}
                <div id="summaryBox" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:1rem; margin-bottom:1rem;">
                    <div style="font-size:0.8rem; font-weight:700; color:#166534; margin-bottom:0.75rem;">📊 Ringkasan Otomatis (dari data sistem)</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                        <div>
                            <div style="font-size:0.7rem; color:#64748b;">Total Penjualan Kanvas</div>
                            <div id="summaryCanvas" style="font-weight:700; color:#1e293b;">Rp 0</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem; color:#64748b;">Total Penagihan Piutang</div>
                            <div id="summaryCollection" style="font-weight:700; color:#1e293b;">Rp 0</div>
                        </div>
                    </div>
                    <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">⚠️ Nilai di bawah dapat disesuaikan secara manual jika ada perbedaan.</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jumlah Penjualan (Rp) <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="sales_amount" id="salesAmount" value="{{ old('sales_amount', 0) }}" min="0" step="1000" class="form-input {{ $errors->has('sales_amount') ? 'input-error' : '' }}" required oninput="calcTotal()">
                        @error('sales_amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Penagihan (Rp)</label>
                        <input type="number" name="collection_amount" id="collectionAmount" value="{{ old('collection_amount', 0) }}" min="0" step="1000" class="form-input {{ $errors->has('collection_amount') ? 'input-error' : '' }}" oninput="calcTotal()">
                        @error('collection_amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pengeluaran Operasional (Rp)</label>
                        <input type="number" name="expense_amount" id="expenseAmount" value="{{ old('expense_amount', 0) }}" min="0" step="1000" class="form-input {{ $errors->has('expense_amount') ? 'input-error' : '' }}" oninput="calcTotal()">
                        @error('expense_amount')<div class="form-error">{{ $message }}</div>@enderror
                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.25rem;">BBM, parkir, dll. yang dikeluarkan dari uang hasil penjualan.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Disetor (Rp)</label>
                        <div style="position:relative;">
                            <input type="number" name="total_amount" id="totalAmount" value="{{ old('total_amount', 0) }}" min="0" step="1000" class="form-input {{ $errors->has('total_amount') ? 'input-error' : '' }}" required style="background:#f0fdf4; font-weight:700; color:#166534;">
                        </div>
                        @error('total_amount')<div class="form-error">{{ $message }}</div>@enderror
                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.25rem;">= Penjualan + Penagihan − Pengeluaran</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="3" placeholder="Catatan tambahan, kendala, dll..." class="form-input" style="resize:vertical;">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem; border-top:1px solid #f1f5f9; margin-top:0.5rem;">
                    <button type="submit" class="btn-primary">💾 Simpan Setoran</button>
                    <a href="{{ route('pasgar.setoran.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcTotal() {
            const sales = parseFloat(document.getElementById('salesAmount').value) || 0;
            const collection = parseFloat(document.getElementById('collectionAmount').value) || 0;
            const expense = parseFloat(document.getElementById('expenseAmount').value) || 0;
            document.getElementById('totalAmount').value = Math.max(0, sales + collection - expense);
        }

        async function loadSummary() {
            const memberId = document.getElementById('memberSelect').value;
            const date = document.getElementById('depositDate').value;
            if (!memberId || !date) return;

            try {
                const res = await fetch(`{{ route('pasgar.setoran.summary') }}?member_id=${memberId}&date=${date}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (data.status === 'success') {
                    document.getElementById('summaryBox').style.display = 'block';
                    document.getElementById('summaryCanvas').textContent = 'Rp ' + data.canvas_sales.toLocaleString('id-ID');
                    document.getElementById('summaryCollection').textContent = 'Rp ' + data.collection.toLocaleString('id-ID');

                    // Pre-fill fields
                    document.getElementById('salesAmount').value = data.canvas_sales;
                    document.getElementById('collectionAmount').value = data.collection;
                    calcTotal();
                }
            } catch(e) {
                // Silent fail — user can fill manually
            }
        }

        // Init calc
        calcTotal();
    </script>
</x-app-layout>
