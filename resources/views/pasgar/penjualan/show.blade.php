<x-app-layout>
    <x-slot name="header">Detail SO: {{ $order->so_number }}</x-slot>

    <div class="page-container" style="max-width:800px;">
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pasgar.penjualan.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">🛒 {{ $order->so_number }}</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">
                        {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                    </p>
                </div>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    @if($order->order_type === 'canvas')
                        <span class="badge-success">Kanvas</span>
                    @else
                        <span class="badge-indigo">Pre-Order</span>
                    @endif
                    @if($order->status === 'completed')
                        <span class="badge-success">✅ Selesai</span>
                    @elseif($order->status === 'pending')
                        <span class="badge-indigo">⏳ Pending</span>
                    @else
                        <span class="badge-danger">{{ $order->status }}</span>
                    @endif
                </div>
            </div>

            {{-- Info Grid --}}
            <div style="padding:1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; border-bottom:1px solid #e2e8f0;">
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Anggota Pasgar</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $order->user?->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Pelanggan</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $order->customer?->name ?? '—' }}</div>
                    <div style="font-size:0.8rem; color:#64748b;">{{ $order->customer?->phone ?? '' }}</div>
                    <div style="font-size:0.8rem; color:#64748b;">{{ $order->customer?->address ?? '' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Tanggal Order</div>
                    <div style="font-weight:500; color:#1e293b;">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Tanggal Kirim</div>
                    <div style="font-weight:500; color:#1e293b;">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : '—' }}</div>
                </div>
                @if($order->notes)
                <div style="grid-column:1/-1;">
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Catatan</div>
                    <div style="color:#475569;">{{ $order->notes }}</div>
                </div>
                @endif
            </div>

            {{-- Items --}}
            <div>
                <div style="padding:1rem 1.5rem; font-weight:700; font-size:0.9rem; color:#1e293b; border-bottom:1px solid #f1f5f9;">📦 Detail Produk</div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th>Satuan</th>
                                <th style="text-align:center;">Qty</th>
                                <th style="text-align:right;">Harga</th>
                                <th style="text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $i => $item)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div style="font-weight:600;">{{ $item->product?->name ?? '—' }}</div>
                                    <div style="font-size:0.75rem; color:#94a3b8;">SKU: {{ $item->product?->sku ?? '—' }}</div>
                                </td>
                                <td class="text-muted">{{ $item->product?->unit?->name ?? 'pcs' }}</td>
                                <td style="text-align:center; font-weight:600;">{{ $item->quantity }}</td>
                                <td style="text-align:right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td style="text-align:right; font-weight:700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f8fafc;">
                                <td colspan="5" style="padding:1rem 1.25rem; font-weight:700; color:#475569; text-align:right;">TOTAL</td>
                                <td style="padding:1rem 1.25rem; font-weight:800; color:#1e293b; text-align:right; font-size:1.05rem;">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
