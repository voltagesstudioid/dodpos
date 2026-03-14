<x-app-layout>
    <x-slot name="header">Purchase Order</x-slot>
    <div class="page-container">

        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon blue">🛒</div>
                <div>
                    <h1 class="ph-title">Purchase Order (PO)</h1>
                    <p class="ph-subtitle">Kelola semua pesanan pembelian ke supplier</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_purchase_order')
                <a href="{{ route('pembelian.order.create') }}" class="btn-primary">＋ Buat PO Baru</a>
                @endcan
            </div>
        </div>

        <div class="panel animate-in animate-in-delay-1">
            <div class="filter-bar">
                <form method="GET" action="{{ route('pembelian.order') }}" style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari no. PO atau supplier..." class="form-input" style="flex:1;min-width:220px;max-width:340px;">
                    <select name="status" class="form-input" style="width:175px;">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status')=='draft' ? 'selected':'' }}>📝 Draft</option>
                        <option value="ordered" {{ request('status')=='ordered' ? 'selected':'' }}>📦 Dipesan</option>
                        <option value="partial" {{ request('status')=='partial' ? 'selected':'' }}>🔄 Sebagian Diterima</option>
                        <option value="received" {{ request('status')=='received' ? 'selected':'' }}>✅ Diterima Penuh</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>❌ Dibatalkan</option>
                    </select>
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('pembelian.order') }}" class="btn-secondary btn-sm">× Reset</a>
                    @endif
                </form>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Supplier</th>
                            <th>Tgl Pesan</th>
                            <th>Tgl Estimasi</th>
                            <th style="text-align:center;">Item</th>
                            @can('view_laporan_pembelian')
                            <th style="text-align:right;">Total</th>
                            @endcan
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        @php
                            $sl = $order->statusLabel;
                            $isLate = $order->expected_date && $order->expected_date->isPast()
                                && !in_array($order->status, ['received', 'cancelled']);
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('pembelian.order.show', $order) }}"
                                    style="font-weight:700;color:#4f46e5;text-decoration:none;font-family:monospace;font-size:0.875rem;">
                                    {{ $order->po_number }}
                                </a>
                            </td>
                            <td>
                                <div class="td-main">{{ $order->supplier->name }}</div>
                                @if($order->supplier->phone)<div class="td-sub">{{ $order->supplier->phone }}</div>@endif
                            </td>
                            <td style="white-space:nowrap;font-size:0.8125rem;color:#64748b;">{{ $order->order_date->format('d M Y') }}</td>
                            <td style="white-space:nowrap;font-size:0.8125rem;">
                                @if($order->expected_date)
                                    @if($isLate)
                                        <span style="color:#ef4444;font-weight:700;">⚠ {{ $order->expected_date->format('d M Y') }}</span>
                                        <div style="font-size:0.68rem;color:#ef4444;">Terlambat!</div>
                                    @else
                                        <span style="color:#64748b;">{{ $order->expected_date->format('d M Y') }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-blue">{{ $order->items->count() }} item</span>
                            </td>
                            @can('view_laporan_pembelian')
                            <td style="text-align:right;font-weight:700;font-size:0.875rem;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            @endcan
                            <td style="text-align:center;">
                                <span style="display:inline-block;padding:0.2rem 0.65rem;border-radius:99px;background:{{ $sl['bg'] }};color:{{ $sl['color'] }};font-size:0.6875rem;font-weight:700;">
                                    {{ $sl['label'] }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div class="act-grp" style="justify-content:center;">
                                    <a href="{{ route('pembelian.order.show', $order) }}" class="act-btn act-btn-view">👁 Detail</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8">
                            <div class="empty-state">
                                <span class="empty-state-icon">🛒</span>
                                <div class="empty-state-title">Belum ada Purchase Order</div>
                                @can('create_purchase_order')
                                <a href="{{ route('pembelian.order.create') }}" class="btn-primary btn-sm">＋ Buat PO Pertama</a>
                                @endcan
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())<div>{{ $orders->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
