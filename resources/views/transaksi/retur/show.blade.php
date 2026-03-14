<x-app-layout>
    <x-slot name="header">Detail Retur {{ $retur->return_number }}</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">❌ {{ session('error') }}</div> @endif

        <div style="display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start;">
            <div>
                <div class="card" style="padding:1.5rem; margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem;gap:1rem;flex-wrap:wrap;">
                        <div>
                            <div style="font-size:1.35rem;font-weight:900;color:#0f172a;">{{ $retur->return_number }}</div>
                            <div style="font-size:0.85rem;color:#64748b;margin-top:0.25rem;">
                                Transaksi: <a href="{{ route('transaksi.show', $retur->transaction) }}" style="color:#4f46e5;text-decoration:none;">#{{ str_pad($retur->transaction_id, 5, '0', STR_PAD_LEFT) }}</a>
                                • {{ optional($retur->created_at)->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <span class="badge badge-success">Selesai</span>
                    </div>

                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th style="text-align:center;">Gudang</th>
                                    <th style="text-align:center;">Qty</th>
                                    <th style="text-align:right;">Harga</th>
                                    <th style="text-align:right;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($retur->items as $it)
                                    <tr>
                                        <td class="td-main">{{ $it->product?->name ?? 'Produk dihapus' }}</td>
                                        <td style="text-align:center;">{{ $it->warehouse?->name ?? '-' }}</td>
                                        <td style="text-align:center;font-weight:900;">{{ (int) $it->quantity }}</td>
                                        <td style="text-align:right;">Rp {{ number_format((float) $it->price, 0, ',', '.') }}</td>
                                        <td style="text-align:right;font-weight:900;color:#ef4444;">Rp {{ number_format((float) $it->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align:right;font-weight:900;">Total Refund</td>
                                    <td style="text-align:right;font-weight:900;color:#ef4444;">Rp {{ number_format((float) $retur->refund_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($retur->notes)
                        <div style="margin-top:1rem;color:#64748b;">Catatan: {{ $retur->notes }}</div>
                    @endif
                </div>
            </div>

            <div>
                <div class="card" style="padding:1.25rem; margin-bottom:0.75rem;">
                    <div style="font-size:.75rem;font-weight:700;color:#94a3b8;margin-bottom:1rem;text-transform:uppercase;">Ringkasan</div>

                    <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;">
                        <span style="color:#64748b;">Pelanggan</span>
                        <span style="font-weight:600;">{{ $retur->customer?->name ?? 'Umum' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;">
                        <span style="color:#64748b;">Kasir</span>
                        <span style="font-weight:600;">{{ $retur->user?->name ?? '-' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;">
                        <span style="color:#64748b;">Metode Refund</span>
                        <span style="font-weight:600;">{{ strtoupper($retur->refund_method) }}</span>
                    </div>
                    @if($retur->refund_method === 'transfer' && $retur->refund_reference)
                        <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;">
                            <span style="color:#64748b;">ID Transfer</span>
                            <span style="font-weight:600;">{{ $retur->refund_reference }}</span>
                        </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;padding:.75rem 0 0;font-size:1rem;font-weight:900;">
                        <span>Refund</span>
                        <span style="color:#ef4444;">Rp {{ number_format((float) $retur->refund_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="card" style="padding:1.25rem; margin-bottom:0.75rem;">
                    <a href="{{ route('transaksi.show', $retur->transaction) }}" class="btn-secondary" style="width:100%;justify-content:center;">← Kembali ke Transaksi</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

