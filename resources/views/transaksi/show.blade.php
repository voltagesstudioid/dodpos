<x-app-layout>
    <x-slot name="header">Detail Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif

        <div style="display:grid; grid-template-columns:1fr 280px; gap:1.5rem; align-items:start;">

            {{-- LEFT: Transaction Detail --}}
            <div>
                <div class="card" style="padding:1.5rem; margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1.5rem;">
                        <div>
                            <div style="font-size:1.5rem;font-weight:800;color:#1e293b;">
                                #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                            <div style="font-size:0.8rem;color:#64748b;margin-top:0.25rem;">
                                {{ $transaksi->created_at->format('l, d F Y — H:i:s') }}
                            </div>
                        </div>
                        @if($transaksi->status === 'completed')
                            <span style="background:#dcfce7;color:#166534;padding:.3rem .875rem;border-radius:999px;font-size:.8rem;font-weight:700;">✅ Selesai</span>
                        @elseif($transaksi->status === 'voided')
                            <span style="background:#fee2e2;color:#991b1b;padding:.3rem .875rem;border-radius:999px;font-size:.8rem;font-weight:700;">❌ Void</span>
                        @endif
                    </div>

                    @if($transaksi->payment_method === 'transfer' && $transaksi->payment_reference)
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;padding:1rem;background:#f8fafc;border-radius:10px;margin-bottom:1.5rem;">
                    @else
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;padding:1rem;background:#f8fafc;border-radius:10px;margin-bottom:1.5rem;">
                    @endif
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;font-weight:600;margin-bottom:0.25rem;">KASIR</div>
                            <div style="font-weight:600;">{{ $transaksi->user?->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;font-weight:600;margin-bottom:0.25rem;">METODE BAYAR</div>
                            <div style="font-weight:600;">
                                {{ match($transaksi->payment_method) { 'cash' => '💵 Tunai', 'transfer' => '🏦 Transfer', 'qris' => '📱 QRIS', default => $transaksi->payment_method } }}
                            </div>
                        </div>
                        @if($transaksi->payment_method === 'transfer' && $transaksi->payment_reference)
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;font-weight:600;margin-bottom:0.25rem;">ID TRANSFER</div>
                            <div style="font-weight:600;">{{ $transaksi->payment_reference }}</div>
                        </div>
                        @endif
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;font-weight:600;margin-bottom:0.25rem;">JUMLAH ITEM</div>
                            <div style="font-weight:600;">{{ $transaksi->details->count() }} produk</div>
                        </div>
                    </div>

                    {{-- Items table --}}
                    <div style="font-size:0.75rem;font-weight:700;color:#94a3b8;margin-bottom:0.75rem;text-transform:uppercase;">Detail Barang</div>
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                                <th style="text-align:left;padding:.5rem .75rem;font-size:.72rem;color:#64748b;font-weight:600;">#</th>
                                <th style="text-align:left;padding:.5rem .75rem;font-size:.72rem;color:#64748b;font-weight:600;">Produk</th>
                                <th style="text-align:left;padding:.5rem .75rem;font-size:.72rem;color:#64748b;font-weight:600;">Kategori</th>
                                <th style="text-align:right;padding:.5rem .75rem;font-size:.72rem;color:#64748b;font-weight:600;">Qty</th>
                                <th style="text-align:right;padding:.5rem .75rem;font-size:.72rem;color:#64748b;font-weight:600;">Harga</th>
                                <th style="text-align:right;padding:.5rem .75rem;font-size:.72rem;color:#64748b;font-weight:600;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->details as $i => $d)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:.625rem .75rem;color:#94a3b8;font-size:.8rem;">{{ $i + 1 }}</td>
                                <td style="padding:.625rem .75rem;">
                                    <div style="font-weight:600;font-size:.85rem;">{{ $d->product?->name ?? 'Produk dihapus' }}</div>
                                    <div style="font-size:.65rem;color:#94a3b8;">{{ $d->product?->sku ?? '' }}</div>
                                </td>
                                <td style="padding:.625rem .75rem;font-size:.8rem;color:#64748b;">{{ $d->product?->category?->name ?? '-' }}</td>
                                <td style="padding:.625rem .75rem;text-align:right;font-weight:600;">{{ $d->quantity }}</td>
                                <td style="padding:.625rem .75rem;text-align:right;font-size:.85rem;">Rp {{ number_format($d->price, 0, ',', '.') }}</td>
                                <td style="padding:.625rem .75rem;text-align:right;font-weight:700;color:#4f46e5;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f8fafc;border-top:2px solid #e2e8f0;">
                                <td colspan="5" style="padding:.75rem;text-align:right;font-weight:700;font-size:.875rem;">TOTAL</td>
                                <td style="padding:.75rem;text-align:right;font-weight:800;font-size:1rem;color:#1e293b;">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- RIGHT: Payment Summary & Actions --}}
            <div>
                <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                    <div style="font-size:.75rem;font-weight:700;color:#94a3b8;margin-bottom:1rem;text-transform:uppercase;">Ringkasan Pembayaran</div>

                    <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;">
                        <span style="color:#64748b;">Subtotal</span>
                        <span style="font-weight:600;">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;">
                        <span style="color:#64748b;">Dibayar</span>
                        <span style="font-weight:600;color:#16a34a;">Rp {{ number_format($transaksi->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.75rem 0 0;font-size:1rem;font-weight:800;">
                        <span>Kembali</span>
                        <span style="color:#f59e0b;">Rp {{ number_format($transaksi->change_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="card" style="padding:1.25rem; margin-bottom:0.75rem;">
                    <a href="{{ route('transaksi.index') }}" class="btn-secondary" style="width:100%;justify-content:center;margin-bottom:.5rem">← Kembali ke Daftar</a>

                    @if($transaksi->status === 'completed')
                    @can('edit_transaksi')
                    <a href="{{ route('transaksi.retur.create', $transaksi) }}" class="btn-secondary" style="width:100%;justify-content:center;margin-top:.25rem;">
                        ↩️ Retur Transaksi
                    </a>
                    @endcan
                    <form action="{{ route('transaksi.void', $transaksi) }}" method="POST"
                          onsubmit="return confirm('Void transaksi ini? Stok akan dikembalikan dan transaksi ditandai void.')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-danger" style="width:100%;justify-content:center;margin-top:.25rem;">
                            ❌ Void Transaksi
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
