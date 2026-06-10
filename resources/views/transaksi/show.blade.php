<x-app-layout>
    <x-slot name="header">Detail Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</x-slot>

    <div class="tr-page">

        {{-- ─── BREADCRUMB ─── --}}
        <nav class="tr-breadcrumb">
            <a href="{{ route('transaksi.index') }}" class="tr-back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Riwayat Transaksi
            </a>
            <span class="tr-sep">/</span>
            <span class="tr-current">Detail #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span>
        </nav>

        {{-- ─── HEADER ─── --}}
        <div class="tr-header-row">
            <div class="tr-header-title">
                <div class="tr-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div>
                    <h1 class="tr-title">Invoice <span class="tr-num">#{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span></h1>
                    <p class="tr-date">{{ $transaksi->created_at->format('l, d F Y — H:i:s') }} WIB</p>
                </div>
            </div>
            <div>
                @if($transaksi->status === 'completed')
                    <span class="tr-badge tr-badge-success">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Selesai
                    </span>
                @elseif($transaksi->status === 'voided')
                    <span class="tr-badge tr-badge-danger">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        Dibatalkan
                    </span>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="tr-alert tr-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="tr-alert tr-alert-danger">{{ session('error') }}</div>
        @endif

        {{-- ─── MAIN GRID ─── --}}
        <div class="tr-grid">

            {{-- ═══ LEFT COLUMN ═══ --}}
            <div class="tr-left">

                {{-- Metadata Card --}}
                <div class="tr-meta-card">
                    <div class="tr-meta-item">
                        <span class="tr-meta-label">Kasir</span>
                        <span class="tr-meta-val">{{ $transaksi->user?->name ?? 'Sistem' }}</span>
                    </div>
                    <div class="tr-meta-item">
                        <span class="tr-meta-label">Pelanggan</span>
                        <span class="tr-meta-val">{{ $transaksi->customer?->name ?? 'Umum' }}</span>
                    </div>
                    <div class="tr-meta-item">
                        <span class="tr-meta-label">Pembayaran</span>
                        <span class="tr-meta-val tr-flex">
                            @if($transaksi->payment_method === 'cash')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle></svg>
                                Tunai
                            @elseif($transaksi->payment_method === 'transfer')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                Transfer
                            @elseif($transaksi->payment_method === 'qris')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect></svg>
                                QRIS
                            @elseif($transaksi->payment_method === 'kredit')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                Kredit
                            @else
                                {{ strtoupper($transaksi->payment_method) }}
                            @endif
                        </span>
                    </div>
                    <div class="tr-meta-item">
                        <span class="tr-meta-label">Tipe Penjualan</span>
                        <span class="tr-meta-val">{{ ucfirst($transaksi->sale_type ?? 'eceran') }}</span>
                    </div>
                    @if($transaksi->payment_method === 'transfer' && $transaksi->payment_reference)
                        <div class="tr-meta-item">
                            <span class="tr-meta-label">No. Referensi</span>
                            <span class="tr-meta-val tr-mono">{{ $transaksi->payment_reference }}</span>
                        </div>
                    @endif
                    @if($transaksi->vehicle_id || $transaksi->driver_name)
                        <div class="tr-meta-item">
                            <span class="tr-meta-label">Kendaraan / Supir</span>
                            <span class="tr-meta-val">
                                @if($transaksi->vehicle)
                                    {{ $transaksi->vehicle->license_plate }}
                                    @if($transaksi->vehicle->type)({{ $transaksi->vehicle->type }})@endif
                                @endif
                                @if($transaksi->driver_name)
                                    {{ $transaksi->driver_name }}
                                @endif
                            </span>
                        </div>
                    @endif
                    @if($transaksi->sourceWarehouse)
                        <div class="tr-meta-item">
                            <span class="tr-meta-label">Gudang Asal</span>
                            <span class="tr-meta-val">{{ $transaksi->sourceWarehouse->name }}</span>
                        </div>
                    @endif
                </div>

                {{-- Item Table --}}
                <div class="tr-card">
                    <div class="tr-card-head">
                        <h2 class="tr-card-title">Detail Pembelian Barang</h2>
                        @php
                            $totalItems = $transaksi->details->count();
                            foreach ($transaksi->additionalTransactions as $addT) {
                                $totalItems += $addT->details->count();
                            }
                        @endphp
                        <span class="tr-count-badge">{{ $totalItems }} Item</span>
                    </div>
                    <div class="tr-table-wrap">
                        <table class="tr-table">
                            <thead>
                                <tr>
                                    <th class="c" style="width:36px;">#</th>
                                    <th>Item Produk</th>
                                    <th class="r">Harga Satuan</th>
                                    <th class="c">Qty</th>
                                    <th class="r">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->details as $i => $d)
                                    @php
                                        $isReturned = in_array($d->id, $returnedDetailIds);
                                        $displayQty = ($d->unit_qty !== null && $d->unit_qty > 0) ? $d->unit_qty : $d->quantity;
                                        $displayUnit = $d->unit_name ?? 'pcs';
                                        $displayPrice = ($displayQty > 0) ? ($d->subtotal / $displayQty) : ($d->price ?? $d->subtotal);
                                    @endphp
                                    <tr class="{{ $transaksi->status === 'voided' ? 'is-voided' : '' }} {{ $isReturned ? 'is-returned' : '' }}">
                                        <td class="c muted">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="item-name">
                                                {{ $d->product?->name ?? 'Produk Dihapus' }}
                                                @if($isReturned)
                                                    <span class="tr-retur-tag">RETUR</span>
                                                @endif
                                            </div>
                                            <div class="item-sub">
                                                {{ $d->product?->category?->name ?? 'Tanpa Kategori' }}
                                                @if($d->product?->sku) &middot; {{ $d->product->sku }} @endif
                                                @if($d->warehouse) &middot; {{ $d->warehouse->name }} @endif
                                            </div>
                                        </td>
                                        <td class="r tr-mono">{{ number_format($displayPrice, 0, ',', '.') }}</td>
                                        <td class="c bold">{{ $displayQty }} {{ $displayUnit }}</td>
                                        <td class="r tr-mono bold">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach

                                {{-- Additional Items --}}
                                @if($transaksi->hasAdditionalItems())
                                    <tr class="tr-section-row">
                                        <td colspan="5">+ Item Tambahan</td>
                                    </tr>
                                    @php $addIdx = $transaksi->details->count(); @endphp
                                    @foreach($transaksi->additionalTransactions as $addTrans)
                                        @foreach($addTrans->details as $d)
                                            @php
                                                $addIdx++;
                                                $isReturned = in_array($d->id, $returnedDetailIds);
                                                $displayQty = ($d->unit_qty !== null && $d->unit_qty > 0) ? $d->unit_qty : $d->quantity;
                                                $displayUnit = $d->unit_name ?? 'pcs';
                                                $displayPrice = ($displayQty > 0) ? ($d->subtotal / $displayQty) : ($d->price ?? $d->subtotal);
                                            @endphp
                                            <tr class="tr-additional-row {{ $isReturned ? 'is-returned' : '' }}">
                                                <td class="c muted">{{ $addIdx }}</td>
                                                <td>
                                                    <div class="item-name">
                                                        {{ $d->product?->name ?? 'Produk Dihapus' }}
                                                        @if($isReturned)
                                                            <span class="tr-retur-tag">RETUR</span>
                                                        @endif
                                                    </div>
                                                    <div class="item-sub">
                                                        {{ $d->product?->category?->name ?? 'Tanpa Kategori' }}
                                                        @if($d->product?->sku) &middot; {{ $d->product->sku }} @endif
                                                    </div>
                                                </td>
                                                <td class="r tr-mono">{{ number_format($displayPrice, 0, ',', '.') }}</td>
                                                <td class="c bold">{{ $displayQty }} {{ $displayUnit }}</td>
                                                <td class="r tr-mono bold">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="r bold">Total</td>
                                    <td class="r tr-mono bold tr-total-cell">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Additional Transactions Timeline --}}
                @if($transaksi->hasAdditionalItems())
                    <div class="tr-card tr-additional-card">
                        <div class="tr-card-head">
                            <h2 class="tr-card-title">Riwayat Penambahan Item</h2>
                            <span class="tr-count-badge">{{ $transaksi->additionalTransactions->count() }}x</span>
                        </div>
                        <div class="tr-timeline">
                            @foreach($transaksi->additionalTransactions as $addTrans)
                                @php
                                    $addMethod = match($addTrans->payment_method) {
                                        'cash' => 'Tunai',
                                        'transfer' => 'Transfer',
                                        'qris' => 'QRIS',
                                        'kredit' => 'Kredit',
                                        default => ucfirst($addTrans->payment_method)
                                    };
                                @endphp
                                <div class="tr-timeline-item {{ !$loop->last ? 'tr-timeline-border' : '' }}">
                                    <div>
                                        <div class="tr-tl-date">{{ $addTrans->created_at->format('d M Y — H:i') }} WIB</div>
                                        <div class="tr-tl-meta">
                                            {{ $addMethod }}
                                            @if($addTrans->payment_reference)
                                                &middot; Ref: {{ $addTrans->payment_reference }}
                                            @endif
                                            &middot; {{ $addTrans->details->count() }} item
                                        </div>
                                        @if($addTrans->additional_notes)
                                            <div class="tr-tl-notes">{{ $addTrans->additional_notes }}</div>
                                        @endif
                                    </div>
                                    <div class="tr-tl-amount">
                                        Rp {{ number_format($addTrans->total_amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Return History --}}
                @if($hasReturns)
                    <div class="tr-card tr-return-card">
                        <div class="tr-card-head">
                            <h2 class="tr-card-title">Riwayat Retur Barang</h2>
                            <span class="tr-count-badge tr-badge-warn">{{ $transaksi->returns->where('status','completed')->count() }}x Retur</span>
                        </div>
                        <div class="tr-timeline">
                            @foreach($transaksi->returns->where('status', 'completed') as $ret)
                                <div class="tr-timeline-item {{ !$loop->last ? 'tr-timeline-border' : '' }}">
                                    <div>
                                        <div class="tr-tl-date">{{ $ret->return_date->format('d M Y') }} &middot; {{ $ret->return_number }}</div>
                                        <div class="tr-tl-meta">
                                            Oleh: {{ $ret->user?->name ?? 'Sistem' }}
                                            &middot; {{ $ret->items->count() }} item
                                            @if($ret->refund_method)
                                                &middot; Refund: {{ ucfirst($ret->refund_method) }}
                                            @endif
                                        </div>
                                        @if($ret->notes)
                                            <div class="tr-tl-notes">{{ $ret->notes }}</div>
                                        @endif
                                        <div class="tr-return-items">
                                            @foreach($ret->items as $ri)
                                                <span class="tr-ri-item">{{ $ri->product?->name ?? '?' }} ({{ $ri->quantity }}x)</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="tr-tl-amount tr-amount-return">
                                        -Rp {{ number_format($ret->refund_amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- ═══ RIGHT COLUMN (SIDEBAR) ═══ --}}
            <div class="tr-right">

                {{-- Receipt Summary --}}
                @php
                    $grandTotal = $transaksi->grand_total;
                    $totalPaid = $transaksi->total_paid;
                    $changeAmount = $totalPaid - $grandTotal;
                @endphp
                <div class="tr-receipt">
                    <div class="tr-receipt-head">Ringkasan Transaksi</div>
                    <div class="tr-receipt-body">
                        <div class="tr-receipt-row">
                            <span>Subtotal Item</span>
                            <span class="tr-mono">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="tr-receipt-div"></div>
                        <div class="tr-receipt-row tr-total-row">
                            <span>Total Tagihan</span>
                            <span class="tr-mono">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="tr-receipt-row tr-paid-row">
                            <span>Jumlah Dibayar</span>
                            <span class="tr-mono tr-text-green">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                        </div>
                        <div class="tr-receipt-change">
                            <span>Uang Kembali</span>
                            <span class="tr-mono tr-text-amber">Rp {{ number_format(max(0, $changeAmount), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="tr-actions">
                    @if($transaksi->status === 'completed')
                        <a href="{{ route('print.receipt', $transaksi->id) }}" target="_blank" class="tr-btn tr-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            Cetak Ulang Struk
                        </a>

                        @can('view_pos_kasir')
                            <a href="{{ route('kasir.transactions.add_items_form', $transaksi) }}" class="tr-btn tr-btn-success">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                                Tambah Item
                            </a>
                        @endcan

                        @can('edit_transaksi')
                            <a href="{{ route('transaksi.retur.create', $transaksi) }}" class="tr-btn tr-btn-outline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                                Proses Retur Barang
                            </a>
                        @endcan

                        @if(!$hasReturns)
                            <form action="{{ route('transaksi.void', $transaksi) }}" method="POST" class="tr-form-block"
                                  onsubmit="return confirm('PERINGATAN!\n\nVoid transaksi akan membatalkan struk ini secara permanen dan mengembalikan stok barang ke sistem.\n\nLanjutkan?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="tr-btn tr-btn-danger-outline">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    Void (Batalkan Transaksi)
                                </button>
                            </form>
                        @else
                            <div class="tr-void-blocked">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span>Void tidak tersedia — transaksi ini memiliki retur.</span>
                            </div>
                        @endif

                    @elseif($transaksi->status === 'voided')
                        <div class="tr-void-notice">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <p>Transaksi ini telah dibatalkan (VOID). Stok barang telah dikembalikan ke sistem inventori.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .tr-page { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem 4rem; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #0f172a; }

        /* ── Breadcrumb ── */
        .tr-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 1.25rem; }
        .tr-back-link { display: flex; align-items: center; gap: 4px; text-decoration: none; color: #64748b; font-size: 0.85rem; font-weight: 700; transition: color 0.2s; }
        .tr-back-link:hover { color: #4f46e5; }
        .tr-sep { color: #cbd5e1; font-size: 0.85rem; }
        .tr-current { font-size: 0.85rem; font-weight: 600; color: #0f172a; }

        /* ── Header ── */
        .tr-header-row { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem; }
        .tr-header-title { display: flex; align-items: center; gap: 1rem; }
        .tr-icon-box { width: 48px; height: 48px; border-radius: 12px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tr-title { font-size: 1.5rem; font-weight: 900; margin: 0 0 2px; letter-spacing: -0.02em; }
        .tr-num { color: #4f46e5; font-family: ui-monospace, 'Cascadia Code', 'Fira Code', monospace; }
        .tr-date { font-size: 0.85rem; color: #64748b; margin: 0; font-weight: 500; }

        .tr-badge { display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; border-radius: 99px; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; }
        .tr-badge-success { background: #dcfce7; color: #15803d; }
        .tr-badge-danger { background: #fee2e2; color: #991b1b; }
        .tr-badge-warn { background: #fef3c7; color: #92400e; }

        /* ── Alerts ── */
        .tr-alert { padding: 0.85rem 1.25rem; border-radius: 10px; margin-bottom: 1.25rem; font-weight: 600; font-size: 0.88rem; }
        .tr-alert-success { background: #dcfce7; color: #15803d; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Main Grid ── */
        .tr-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }

        /* ── Meta Card ── */
        .tr-meta-card { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.85rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.15rem; margin-bottom: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .tr-meta-item { display: flex; flex-direction: column; gap: 3px; border-left: 3px solid #e0e7ff; padding-left: 10px; }
        .tr-meta-label { font-size: 0.68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; }
        .tr-meta-val { font-size: 0.9rem; font-weight: 800; color: #0f172a; }
        .tr-flex { display: inline-flex; align-items: center; gap: 5px; }

        /* ── Card ── */
        .tr-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 1.25rem; }
        .tr-card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; }
        .tr-card-title { font-size: 0.88rem; font-weight: 800; margin: 0; color: #0f172a; text-transform: uppercase; letter-spacing: 0.02em; }
        .tr-count-badge { font-size: 0.75rem; font-weight: 700; color: #4f46e5; background: #e0e7ff; padding: 3px 10px; border-radius: 99px; }

        /* ── Table ── */
        .tr-table-wrap { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 520px; }
        .tr-table thead th { background: #f8fafc; padding: 0.75rem 1rem; text-align: left; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        .tr-table tbody td { padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; vertical-align: middle; }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table tfoot td { padding: 0.85rem 1rem; border-top: 2px solid #e2e8f0; font-size: 0.88rem; }
        .tr-table .c { text-align: center; }
        .tr-table .r { text-align: right; }

        .is-voided td { opacity: 0.5; text-decoration: line-through; background: #f8fafc; }
        .is-returned td { background: #fffbeb; }

        .item-name { font-weight: 700; font-size: 0.9rem; color: #0f172a; margin-bottom: 2px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .item-sub { font-size: 0.75rem; color: #64748b; font-weight: 500; }
        .tr-retur-tag { font-size: 0.6rem; font-weight: 800; background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.04em; }

        .tr-section-row td { background: #fef3c7; padding: 6px 1rem !important; font-size: 0.72rem; font-weight: 700; color: #92400e; text-transform: uppercase; border-bottom: 1px solid #fcd34d !important; }
        .tr-additional-row td { background: #fffdf7; }
        .tr-total-cell { color: #4f46e5; font-size: 0.95rem; }

        /* ── Additional & Return Cards ── */
        .tr-additional-card { border-color: #fcd34d; background: #fffef5; }
        .tr-additional-card .tr-card-head { background: #fef9e7; }
        .tr-return-card { border-color: #fca5a5; background: #fff5f5; }
        .tr-return-card .tr-card-head { background: #fef2f2; }

        .tr-timeline { padding: 0.75rem 1.25rem; }
        .tr-timeline-item { display: flex; justify-content: space-between; align-items: flex-start; padding: 0.75rem 0; gap: 1rem; }
        .tr-timeline-border { border-bottom: 1px dashed #e2e8f0; }
        .tr-tl-date { font-weight: 700; font-size: 0.88rem; color: #0f172a; margin-bottom: 2px; }
        .tr-tl-meta { font-size: 0.78rem; color: #64748b; font-weight: 500; }
        .tr-tl-notes { font-size: 0.75rem; color: #94a3b8; margin-top: 3px; font-style: italic; }
        .tr-tl-amount { font-weight: 800; font-size: 0.95rem; color: #0f172a; white-space: nowrap; }
        .tr-amount-return { color: #dc2626; }
        .tr-return-items { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px; }
        .tr-ri-item { font-size: 0.72rem; font-weight: 600; background: #fee2e2; color: #991b1b; padding: 1px 8px; border-radius: 4px; }

        /* ── Right Column ── */
        .tr-right { position: sticky; top: 1.5rem; }

        .tr-receipt { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); margin-bottom: 1.25rem; overflow: hidden; }
        .tr-receipt-head { padding: 1.15rem 1.25rem 0.85rem; font-size: 0.78rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; text-align: center; border-bottom: 1px dashed #e2e8f0; }
        .tr-receipt-body { padding: 1.15rem 1.25rem; display: flex; flex-direction: column; gap: 0.65rem; }
        .tr-receipt-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem; }
        .tr-receipt-row > span:first-child { color: #64748b; font-weight: 600; }
        .tr-receipt-row > span:last-child { font-weight: 700; color: #0f172a; }
        .tr-receipt-div { border-top: 1px dashed #e2e8f0; margin: 0.25rem 0; }
        .tr-total-row > span:first-child { font-weight: 800; color: #0f172a; }
        .tr-total-row > span:last-child { font-weight: 900; font-size: 1.15rem; color: #4f46e5; }
        .tr-paid-row > span:last-child { color: #15803d; }
        .tr-receipt-change { display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 0.65rem 0.85rem; border-radius: 8px; margin-top: 0.25rem; }
        .tr-receipt-change > span:first-child { font-weight: 800; color: #0f172a; font-size: 0.88rem; }
        .tr-receipt-change > span:last-child { font-weight: 900; font-size: 1rem; }

        /* ── Actions ── */
        .tr-actions { display: flex; flex-direction: column; gap: 0.65rem; }
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 0.75rem 1.25rem; border-radius: 10px; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.15s; border: 1px solid transparent; text-decoration: none; width: 100%; }
        .tr-btn-primary { background: #4f46e5; color: #fff; box-shadow: 0 2px 6px rgba(79,70,229,0.15); }
        .tr-btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .tr-btn-success { background: #15803d; color: #fff; }
        .tr-btn-success:hover { background: #166534; transform: translateY(-1px); }
        .tr-btn-outline { border-color: #e2e8f0; background: #fff; color: #0f172a; }
        .tr-btn-outline:hover { background: #f1f5f9; border-color: #94a3b8; }
        .tr-btn-danger-outline { border-color: #fecaca; color: #dc2626; background: transparent; }
        .tr-btn-danger-outline:hover { background: #fee2e2; }
        .tr-form-block { width: 100%; margin: 0; }

        .tr-void-blocked { display: flex; align-items: center; gap: 8px; padding: 0.75rem 1rem; border-radius: 10px; background: #f1f5f9; color: #64748b; font-size: 0.82rem; font-weight: 600; }
        .tr-void-notice { background: #fee2e2; border: 1px solid #fecaca; padding: 1.15rem; border-radius: 10px; text-align: center; color: #991b1b; display: flex; flex-direction: column; align-items: center; gap: 6px; }
        .tr-void-notice p { margin: 0; font-size: 0.82rem; font-weight: 600; line-height: 1.5; }

        /* ── Utils ── */
        .tr-mono { font-family: ui-monospace, 'Cascadia Code', 'Fira Code', Consolas, monospace; }
        .bold { font-weight: 800; }
        .muted { color: #94a3b8; }
        .tr-text-green { color: #15803d; }
        .tr-text-amber { color: #d97706; }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .tr-grid { grid-template-columns: 1fr; }
            .tr-right { position: static; }
            .tr-meta-card { grid-template-columns: repeat(2, 1fr); }
            .tr-header-row { flex-direction: column; }
        }
        @media (max-width: 640px) {
            .tr-meta-card { grid-template-columns: 1fr; }
            .tr-title { font-size: 1.2rem; }
        }
    </style>
    @endpush
</x-app-layout>
