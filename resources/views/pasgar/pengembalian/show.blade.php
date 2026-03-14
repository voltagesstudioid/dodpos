<x-app-layout>
    <x-slot name="header">Detail Pengembalian: {{ $pengembalian->transfer_number }}</x-slot>

    <div class="page-container" style="max-width:800px;">
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pasgar.pengembalian.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">↩ {{ $pengembalian->transfer_number }}</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">
                        {{ \Carbon\Carbon::parse($pengembalian->date)->format('d M Y') }}
                    </p>
                </div>
                @if($pengembalian->status === 'completed')
                    <span class="badge-success" style="font-size:0.8rem; padding:0.375rem 0.875rem;">✅ Selesai</span>
                @else
                    <span class="badge-indigo" style="font-size:0.8rem; padding:0.375rem 0.875rem;">⏳ Pending</span>
                @endif
            </div>

            <div style="padding:1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Dari (Kendaraan)</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $pengembalian->fromWarehouse?->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Ke (Gudang Tujuan)</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $pengembalian->toWarehouse?->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Dibuat Oleh</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $pengembalian->creator?->name ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;">Catatan</div>
                    <div style="color:#475569;">
                        {{ str_replace('[Pengembalian Pasgar]', '', $pengembalian->notes) ?: '—' }}
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div style="border-top:1px solid #e2e8f0;">
                <div style="padding:1rem 1.5rem; font-weight:700; font-size:0.9rem; color:#1e293b;">📦 Daftar Barang Dikembalikan</div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th>Satuan</th>
                                <th style="text-align:right;">Qty Dikembalikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengembalian->items as $i => $item)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div style="font-weight:600;">{{ $item->product->name }}</div>
                                    <div style="font-size:0.75rem; color:#94a3b8;">SKU: {{ $item->product->sku ?? '—' }}</div>
                                </td>
                                <td class="text-muted">{{ $item->product->unit?->name ?? 'pcs' }}</td>
                                <td style="text-align:right; font-weight:700; color:#4f46e5; font-size:1.05rem;">{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f8fafc;">
                                <td colspan="3" style="padding:0.875rem 1.25rem; font-weight:700; color:#475569;">Total Item</td>
                                <td style="text-align:right; padding:0.875rem 1.25rem; font-weight:700; color:#1e293b;">
                                    {{ $pengembalian->items->sum('quantity') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
