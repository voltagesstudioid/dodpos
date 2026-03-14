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
                            <select name="product_id" class="form-input @error('product_id') input-error @enderror" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->sku }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Jumlah Diterima (Qty) <span class="required">*</span></label>
                            <input
                                type="number"
                                name="quantity"
                                value="{{ old('quantity') }}"
                                min="1"
                                class="form-input @error('quantity') input-error @enderror"
                                required
                                placeholder="contoh: 50"
                            >
                            @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-row" style="margin-top:1rem;">
                        <div class="form-group" style="margin:0; grid-column: span 2;">
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
</x-app-layout>
