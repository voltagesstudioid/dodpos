@extends('layouts.app')

@section('title', 'Detail Pesanan #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' - DODPOS')

@push('styles')
<style>
/* Custom local styles for a premium look */
.items-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.items-table th { background: linear-gradient(180deg,#f8fafc,#f4f8fc); color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 0.8rem 1rem; border-bottom: 2px solid #e2e8f0; text-align: left; letter-spacing: 0.05em; }
.items-table td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; }
.items-table tr:hover td { background: #fafbff; }
.timeline-item { position: relative; padding-left: 1.5rem; margin-bottom: 1.5rem; }
.timeline-item:last-child { margin-bottom: 0; }
.timeline-item::before { content: ''; position: absolute; left: 6px; top: 22px; bottom: -18px; width: 2px; background: #e2e8f0; }
.timeline-item:last-child::before { display: none; }
.timeline-dot { position: absolute; left: 0; top: 4px; width: 14px; height: 14px; border-radius: 50%; border: 3px solid #fff; background: #e2e8f0; box-shadow: 0 0 0 1px #cbd5e1; z-index: 2;}
.timeline-dot.active { background: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,0.2); border-color: #fff; }
.timeline-dot.success { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.2); border-color: #fff; }
.timeline-date { font-size: 0.75rem; color: #94a3b8; margin-top: 2px; line-height: 1.3;}

.status-badge-lg { padding: 0.4rem 1rem; font-size: 0.85rem; border-radius: 99px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.status-badge-lg.pending { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
.status-badge-lg.packing { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
.status-badge-lg.packed { background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff; }
.status-badge-lg.intransit { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
.status-badge-lg.delivered { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }

.huge-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1rem; }
.bg-yellow-soft { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; box-shadow: 0 4px 10px rgba(245,158,11,0.1); }

.warehouse-group { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
.warehouse-header { background: #f8fafc; padding: 0.75rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: #334155; }
.check-item:hover { background: #fafbff; border-color: #cbd5e1; }
.check-item:has(input:checked) { background: #faf5ff; box-shadow: inset 3px 0 0 #9333ea;}
</style>
@endpush

@section('content')
<div class="page-container animate-in">
    {{-- Header --}}
    <div class="ph">
        <div class="ph-left">
            <a href="{{ route('warehouse.orders.index') }}" class="act-btn" style="border: 1px solid #e2e8f0; background: #fff; padding: 0.5rem; border-radius: 12px; margin-right: 0.5rem; text-decoration: none; color: #64748b; margin-top: 4px; box-shadow: var(--shadow-sm);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="ph-icon blue">📦</div>
            <div>
                <h1 class="ph-title">Pesanan #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <div class="ph-subtitle">
                    {{ $order->created_at->format('d M Y, H:i') }} • Kasir: <span style="font-weight:600; color:#1e293b;">{{ $order->user?->name ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="ph-actions">
            @php
                $statusClass = match($order->delivery_status) {
                    'pending' => 'pending',
                    'packing' => 'packing',
                    'packed' => 'packed',
                    'in_transit' => 'intransit',
                    'delivered' => 'delivered',
                    default => 'pending'
                };
                $statusLabels = [
                    'pending' => '🛑 Menunggu Di-pack',
                    'packing' => '📦 Sedang Dikemas',
                    'packed' => '✅ Selesai Kemas',
                    'in_transit' => '🚚 Dalam Perjalanan',
                    'delivered' => '🏁 Terdeliver'
                ];
            @endphp
            <div class="status-badge-lg {{ $statusClass }}">
                {{ $statusLabels[$order->delivery_status] ?? $order->delivery_status }}
            </div>
        </div>
    </div>

    <div class="two-col">
        {{-- Left: Items --}}
        <div>
            <div class="card-premium mb-3">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" fill="none" class="text-blue" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <h2 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0;">Daftar Item Pesanan</h2>
                </div>
                <div style="padding: 1.5rem; padding-bottom: 0.5rem;">
                    @foreach($itemsByWarehouse as $warehouseId => $items)
                        @php $warehouse = $items->first()?->warehouse; @endphp
                        <div class="warehouse-group">
                            <div class="warehouse-header">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $warehouse?->name ?? 'Gudang Utama' }}
                            </div>
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th style="text-align: center;">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        @php
                                            $displayQty = ($item->unit_qty !== null && $item->unit_qty > 0) ? $item->unit_qty : $item->quantity;
                                            $displayUnit = $item->unit_name ?? 'pcs';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="font-weight: 700; color: #1e293b;">{{ $item->product?->name ?? '-' }}</div>
                                                @if($item->product?->code)
                                                    <div style="font-size: 0.75rem; color: #94a3b8; font-family: monospace;">{{ $item->product->code }}</div>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <span style="display:inline-block; padding:0.2rem 0.6rem; background:#f1f5f9; border-radius:6px; font-weight:700;">{{ $displayQty }} {{ $displayUnit }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Cross-check section when packed --}}
            @if($order->delivery_status === 'packed')
                <div class="card-premium">
                    <div style="padding: 1.25rem 1.5rem; background:#faf5ff; border-bottom: 1px solid #e9d5ff; display: flex; align-items: center; gap: 0.5rem; color:#7e22ce;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <h2 style="font-size: 1rem; font-weight: 800; margin: 0;">Cross-Check Barang & Qty</h2>
                    </div>
                    <form method="POST" action="{{ route('warehouse.orders.cross_check', $order) }}" id="crossCheckForm">
                        @csrf
                        <div style="padding: 1.5rem;">
                            <p style="font-size:0.85rem; color:#64748b; margin-bottom:1rem;">Tandai setiap item di bawah ini jika barang fisik sudah dicek dan sesuai dengan pesanan sebelum diserahkan ke kurir/kasir.</p>
                            
                            <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem;">
                                @foreach($allDetails as $index => $item)
                                    @php
                                        $displayQty = ($item->unit_qty !== null && $item->unit_qty > 0) ? $item->unit_qty : $item->quantity;
                                        $displayUnit = $item->unit_name ?? 'pcs';
                                    @endphp
                                    <label class="check-item" style="display:flex; align-items:center; padding:1rem; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:all 0.2s;">
                                        <input type="checkbox" name="items_checked[{{ $item->id }}]" value="1" required
                                               style="width: 20px; height: 20px; border-radius: 6px; accent-color: #9333ea; cursor: pointer;"
                                               {{ old('items_checked.' . $item->id, true) ? 'checked' : '' }}>
                                        <div style="margin-left: 1rem; flex:1;">
                                            <div style="font-weight:700; color:#1e293b;">{{ $item->product?->name ?? '-' }}</div>
                                            <div style="font-size:0.75rem; color:#94a3b8;">{{ $item->product?->code }}</div>
                                        </div>
                                        <div style="text-align:right;">
                                            <div style="font-size:0.7rem; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">QTY</div>
                                            <div style="font-size:1.1rem; font-weight:800; color:#0f172a;">{{ $displayQty }} {{ $displayUnit }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="color:#7e22ce;">Catatan Cross-Check / Kondisi Barang (Opsional)</label>
                                <textarea name="notes" rows="2" class="form-input" style="border-color:#e9d5ff; background:#faf5ff;" placeholder="Misalnya: Barang A diganti dengan warna biru..."></textarea>
                            </div>
                        </div>

                        {{-- Fixed footer --}}
                        <div class="floating-bar">
                            <div class="floating-bar-info">
                                <div style="font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase;">Status Aktif</div>
                                <div style="font-weight:800; color:#9333ea;">Menunggu Cross Check</div>
                            </div>
                            <div class="floating-bar-actions">
                                <button type="submit" class="btn-primary" style="background:linear-gradient(135deg,#9333ea,#7e22ce); box-shadow:0 4px 14px rgba(147,51,234,0.3); font-size:0.9rem; padding:0.6rem 1.5rem;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Konfirmasi & Serahkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            
            {{-- Delivery Note --}}
            @if($order->delivery_status === 'in_transit')
                <div class="card-premium">
                    <form method="POST" action="{{ route('warehouse.orders.confirm_delivery', $order) }}">
                        @csrf
                        <div style="padding: 1.5rem;">
                            <div class="huge-icon bg-yellow-soft">🚚</div>
                            <h2 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 0.5rem; color:#0f172a;">Barang Menunggu Pengambilan / Diantar</h2>
                            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem;">Silakan isi nama penerima atau catatan konfirmasi jika barang sudah sukses diserah terima ke Kasir atau Pembeli.</p>

                            <div class="form-group m-0">
                                <label class="form-label">Tanda Terima (Opsional)</label>
                                <textarea name="notes" rows="2" class="form-input" placeholder="Diterima oleh Budi pada pukul 14:00, kondisi aman..."></textarea>
                            </div>
                        </div>
                        <div class="floating-bar">
                            <div class="floating-bar-info">
                                <div style="font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase;">Status Aktif</div>
                                <div style="font-weight:800; color:#ea580c;">Penyelesaian Pengiriman</div>
                            </div>
                            <button type="submit" class="btn-primary" style="background:linear-gradient(135deg,#ea580c,#c2410c); box-shadow:0 4px 14px rgba(234,88,12,0.3); font-size:0.9rem; padding:0.6rem 1.5rem;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Selesaikan Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- Right: Customer & Tracking --}}
        <div>
            <div class="form-card mb-3">
                <div class="form-card-header">
                    <div class="form-card-icon indigo">👤</div>
                    <div>
                        <div class="form-card-title">Informasi Tujuan</div>
                        <div class="form-card-subtitle">Data pemesan atau pelanggan</div>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="info-row">
                        <span class="info-key">Pihak Penerima</span>
                        <span class="info-val">{{ $order->customer?->name ?? 'Kasir / Umum' }}</span>
                    </div>
                    @if($order->customer?->phone)
                    <div class="info-row" style="border:none;">
                        <span class="info-key">Telepon</span>
                        <span class="info-val">{{ $order->customer->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="form-card mb-3">
                <div class="form-card-header">
                    <div class="form-card-icon emerald">⏱️</div>
                    <div>
                        <div class="form-card-title">Jejak Perjalanan</div>
                        <div class="form-card-subtitle">Timeline status proses pesanan</div>
                    </div>
                </div>
                <div class="form-card-body" style="padding: 1.5rem;">
                    
                    {{-- Created --}}
                    <div class="timeline-item">
                        <div class="timeline-dot success"></div>
                        <div style="font-weight:700; font-size:0.85rem; color:#0f172a;">Pesanan Dibuat</div>
                        <div class="timeline-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        <div style="font-size:0.75rem; color:#64748b;">Kasir: <span style="font-weight:600;">{{ $order->user?->name ?? '-' }}</span></div>
                    </div>

                    {{-- Packed --}}
                    @if($order->packed_at || $order->delivery_status === 'packing')
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->packed_at ? 'success' : 'active' }}"></div>
                        <div style="font-weight:700; font-size:0.85rem; color:#0f172a;">Gudang Packing</div>
                        @if($order->packed_at)
                            <div class="timeline-date">{{ $order->packed_at->format('d M Y, H:i') }}</div>
                            <div style="font-size:0.75rem; color:#64748b;">Oleh: <span style="font-weight:600;">{{ $order->packedBy?->name ?? '-' }}</span></div>
                        @else
                            <div class="timeline-date" style="color:#2563eb;">Proses pengepakan berlangsung...</div>
                        @endif
                    </div>
                    @endif

                    {{-- Checked --}}
                    @if($order->checked_at || $order->delivery_status === 'packed')
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->checked_at ? 'success' : 'active' }}"></div>
                        <div style="font-weight:700; font-size:0.85rem; color:#0f172a;">Cross Check Admin</div>
                        @if($order->checked_at)
                            <div class="timeline-date">{{ $order->checked_at->format('d M Y, H:i') }}</div>
                            <div style="font-size:0.75rem; color:#64748b;">Oleh: <span style="font-weight:600;">{{ $order->checkedBy?->name ?? '-' }}</span></div>
                        @else
                           <div class="timeline-date" style="color:#9333ea;">Menunggu konfirmasi admin...</div>
                        @endif
                    </div>
                    @endif

                    {{-- Delivered --}}
                    @if($order->delivered_at || $order->delivery_status === 'in_transit')
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->delivered_at ? 'success' : 'active' }}"></div>
                        <div style="font-weight:700; font-size:0.85rem; color:#0f172a;">Penyerahan Tujuan</div>
                        @if($order->delivered_at)
                            <div class="timeline-date">{{ $order->delivered_at->format('d M Y, H:i') }}</div>
                            <div style="font-size:0.75rem; color:#64748b;">Oleh: <span style="font-weight:600;">{{ $order->deliveredBy?->name ?? '-' }}</span></div>
                        @else
                             <div class="timeline-date" style="color:#ea580c;">Sedang menunggu serah terima...</div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

            @if($order->delivery_notes)
            <div class="card-premium mb-3" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border-color:#fde68a;">
                <div style="padding: 1.25rem;">
                    <div style="font-weight: 800; font-size: 0.85rem; color: #b45309; text-transform:uppercase; margin-bottom: 0.5rem; letter-spacing:0.05em;">Log Serah Terima</div>
                    <div style="font-size:0.85rem; color:#92400e; line-height: 1.6; white-space:pre-wrap;">{{ $order->delivery_notes }}</div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Default Actions for pending/packing (if they don't have their own forms taking over the floating bar) --}}
@if(in_array($order->delivery_status, ['pending', 'packing']))
<div class="floating-bar animate-in" style="animation-delay: 0.2s;">
    <div class="floating-bar-info">
        <div style="font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase;">Total Item</div>
        <div style="font-size:1.1rem; font-weight:800; color:#0f172a;">{{ $order->details->count() }} produk</div>
    </div>
    <div class="floating-bar-actions">
        
        @if($order->delivery_status === 'pending')
            <form method="POST" action="{{ route('warehouse.orders.start_packing', $order) }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-primary" style="background:linear-gradient(135deg,#2563eb,#1d4ed8); padding:0.6rem 1.5rem; font-size:0.9rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mulai Kerjakan (Packing)
                </button>
            </form>
        @elseif($order->delivery_status === 'packing')
            <form method="POST" action="{{ route('warehouse.orders.finish_packing', $order) }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-primary" style="background:linear-gradient(135deg,#9333ea,#7e22ce); box-shadow:0 4px 14px rgba(147,51,234,0.3); padding:0.6rem 1.5rem; font-size:0.9rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Tandai Selesai Packing
                </button>
            </form>
        @endif

    </div>
</div>
@endif

@endsection
