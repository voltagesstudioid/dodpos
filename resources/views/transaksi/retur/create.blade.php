<x-app-layout>
    <x-slot name="header">Retur Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">❌ {{ session('error') }}</div> @endif

        <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                <div>
                    <div style="font-size:1.25rem;font-weight:900;color:#0f172a;">Pilih Item Retur</div>
                    <div style="color:#64748b;font-size:0.875rem;margin-top:0.25rem;">
                        Pelanggan: {{ $transaksi->customer?->name ?? 'Umum' }} • Tanggal: {{ $transaksi->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                <a href="{{ route('transaksi.show', $transaksi) }}" class="btn-secondary">← Kembali</a>
            </div>
        </div>

        <form method="POST" action="{{ route('transaksi.retur.store', $transaksi) }}">
            @csrf

            <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;">
                    <div>
                        <label class="form-label">Metode Refund</label>
                        <select name="refund_method" class="form-input" required>
                            <option value="tunai" @selected(old('refund_method')==='tunai')>Tunai</option>
                            <option value="transfer" @selected(old('refund_method')==='transfer')>Transfer</option>
                            <option value="tanpa_refund" @selected(old('refund_method')==='tanpa_refund')>Tanpa Refund</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">ID Transfer (opsional)</label>
                        <input type="text" name="refund_reference" class="form-input" value="{{ old('refund_reference') }}" maxlength="100">
                    </div>
                    <div>
                        <label class="form-label">Catatan (opsional)</label>
                        <input type="text" name="notes" class="form-input" value="{{ old('notes') }}">
                    </div>
                </div>
            </div>

            <div class="card" style="padding:1.5rem;">
                <div style="font-size:0.85rem;font-weight:900;color:#0f172a;margin-bottom:0.75rem;">Detail Barang</div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th style="text-align:center;">Terjual</th>
                                <th style="text-align:center;">Sudah Retur</th>
                                <th style="text-align:center;">Sisa</th>
                                <th style="text-align:right;">Harga</th>
                                <th style="text-align:center;">Gudang</th>
                                <th style="text-align:center;">Qty Retur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $i => $r)
                                @php $oldItems = old('items', []); @endphp
                                <tr>
                                    <td class="td-main">
                                        {{ $r['product_name'] }}
                                        <div style="font-size:0.75rem;color:#64748b;">{{ $r['sku'] ?: '' }}</div>
                                    </td>
                                    <td style="text-align:center;">{{ $r['qty_sold'] }}</td>
                                    <td style="text-align:center;">{{ $r['qty_returned'] }}</td>
                                    <td style="text-align:center;font-weight:900;">{{ $r['qty_available'] }}</td>
                                    <td style="text-align:right;">Rp {{ number_format((float) $r['price'], 0, ',', '.') }}</td>
                                    <td style="text-align:center;">
                                        @if($r['warehouse_id'])
                                            <span class="badge badge-gray">{{ $r['warehouse_name'] ?? 'Gudang' }}</span>
                                        @else
                                            <select name="items[{{ $i }}][warehouse_id]" class="form-input" style="min-width:180px;">
                                                <option value="">-- Pilih Gudang --</option>
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
                                               class="form-input"
                                               value="{{ $oldItems[$i]['quantity'] ?? 0 }}"
                                               min="0"
                                               max="{{ $r['qty_available'] }}"
                                               style="max-width:120px;text-align:center;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:0.5rem;margin-top:1rem;">
                    <button type="submit" class="btn-primary">↩️ Proses Retur</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

