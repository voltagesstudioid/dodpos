<x-app-layout>
    <x-slot name="header">Detail Retur #{{ $retur->return_number }}</x-slot>

    <div class="page-container">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start;">

            {{-- LEFT: Main Detail --}}
            <div>
                {{-- Header Info --}}
                <div class="card" style="padding:1.5rem; margin-bottom:1.25rem;">
                    <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:1rem; margin-bottom:1.25rem;">
                        <div>
                            <div style="font-size:1.25rem;font-weight:800;color:#1e293b;">{{ $retur->return_number }}</div>
                            <div style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;">Dibuat {{ $retur->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        {!! $retur->status_badge !!}
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
                        <div>
                            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:600;">Supplier</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $retur->supplier->name }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:600;">Tanggal Retur</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $retur->return_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:600;">Referensi PO</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $retur->purchaseOrder ? $retur->purchaseOrder->po_number : '-' }}</div>
                        </div>
                    </div>
                    <div style="margin-top:1rem;">
                        <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;font-weight:600;">Gudang Sumber</div>
                        <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $retur->warehouse?->name ?? '-' }}</div>
                    </div>

                    @if($retur->reason)
                    <div style="margin-top:1rem;padding:0.75rem 1rem;background:#fef3c7;border-radius:8px;border-left:3px solid #f59e0b;">
                        <div style="font-size:0.7rem;font-weight:700;color:#92400e;margin-bottom:0.25rem;">ALASAN RETUR</div>
                        <div style="font-size:0.875rem;color:#78350f;">{{ $retur->reason }}</div>
                    </div>
                    @endif
                    @if($retur->notes)
                    <div style="margin-top:0.75rem;font-size:0.85rem;color:#64748b;">📝 {{ $retur->notes }}</div>
                    @endif
                </div>

                {{-- Items Table --}}
                <div class="card">
                    <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;font-weight:700;color:#1e293b;">📦 Item yang Diretur</div>
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th>Harga Beli</th>
                                    <th>Subtotal</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($retur->items as $item)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;">{{ $item->product->name }}</div>
                                        <div style="font-size:0.7rem;color:#94a3b8;">{{ $item->product->sku }}</div>
                                    </td>
                                    <td>{{ $item->unit->name }}</td>
                                    <td>{{ number_format($item->quantity) }}</td>
                                    <td>Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                                    <td style="font-weight:600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td style="font-size:0.8rem;color:#64748b;">{{ $item->reason ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align:right;font-weight:700;">TOTAL RETUR</td>
                                    <td style="font-weight:800;color:#ef4444;">Rp {{ number_format($retur->total_amount, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Actions --}}
            <div>
                <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.9rem;">⚡ Aksi</div>

                    <a href="{{ route('print.return', $retur->id) }}" target="_blank" class="btn-primary" style="width:100%;justify-content:center;padding:0.625rem;margin-bottom:0.75rem;background:#3b82f6;border-color:#3b82f6;">🖨️ Cetak Surat Retur</a>

                    @if($retur->status === 'draft')
                        @can('edit_retur_pembelian')
                        <form action="{{ route('pembelian.retur.approve', $retur) }}" method="POST" style="margin-bottom:0.75rem;">
                            @csrf
                            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:0.625rem;">✅ Setujui Retur</button>
                        </form>
                        @endcan
                        @can('delete_retur_pembelian')
                        <form action="{{ route('pembelian.retur.destroy', $retur) }}" method="POST" onsubmit="return confirm('Hapus retur ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger" style="width:100%;justify-content:center;padding:0.625rem;">🗑️ Hapus Retur</button>
                        </form>
                        @endcan
                    @elseif($retur->status === 'approved')
                        @can('edit_retur_pembelian')
                        <form action="{{ route('pembelian.retur.process', $retur) }}" method="POST" style="margin-bottom:0.75rem;"
                              onsubmit="return confirm('Proses retur? Stok akan dikurangi.')">
                            @csrf
                            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:0.625rem;">🔄 Proses & Kurangi Stok</button>
                        </form>
                        <form action="{{ route('pembelian.retur.cancel', $retur) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-secondary" style="width:100%;justify-content:center;padding:0.625rem;">↩️ Kembalikan ke Draft</button>
                        </form>
                        @endcan
                    @else
                        <div style="text-align:center;padding:1rem;background:#f0fdf4;border-radius:8px;color:#166534;font-weight:600;font-size:0.875rem;">
                            ✅ Retur Selesai
                        </div>
                    @endif
                </div>

                <a href="{{ route('pembelian.retur.index') }}" class="btn-secondary" style="width:100%;justify-content:center;padding:0.625rem;display:flex;">← Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</x-app-layout>
