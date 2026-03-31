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
            <div class="ph-actions" style="display:flex; gap:0.5rem;">
                @can('view_laporan_pembelian')
                <a href="{{ route('pembelian.order', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn-secondary" title="Export Excel">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px;">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export
                </a>
                @endcan
                @can('create_purchase_order')
                <a href="{{ route('pembelian.order.create') }}" class="btn-primary">＋ Buat PO Baru</a>
                @endcan
            </div>
        </div>

        {{-- Stats Cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">📋</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#4f46e5;">{{ number_format($stats['total']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Total PO</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">📦</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#d97706;">{{ number_format($stats['ordered'] + $stats['partial']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Dalam Proses</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">✅</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#16a34a;">{{ number_format($stats['received']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Selesai</div>
                </div>
            </div>
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">⚠️</div>
                <div>
                    <div style="font-size:1.75rem;font-weight:800;color:#dc2626;">{{ number_format($stats['late']) }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Terlambat</div>
                </div>
            </div>
            @can('view_laporan_pembelian')
            <div class="card" style="padding:1.25rem; display:flex; align-items:center; gap:1rem;">
                <div style="width:48px;height:48px;border-radius:12px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">💰</div>
                <div>
                    <div style="font-size:1.25rem;font-weight:800;color:#15803d;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
                    <div style="font-size:0.8rem;color:#64748b;">Total Nilai</div>
                </div>
            </div>
            @endcan
        </div>

        <div class="panel animate-in animate-in-delay-1">
            <div class="filter-bar">
                <form method="GET" action="{{ route('pembelian.order') }}" style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari no. PO atau supplier..." class="form-input" style="flex:1;min-width:200px;max-width:280px;">
                    <select name="status" class="form-input" style="width:160px;">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status')=='draft' ? 'selected':'' }}>📝 Draft</option>
                        <option value="ordered" {{ request('status')=='ordered' ? 'selected':'' }}>📦 Dipesan</option>
                        <option value="partial" {{ request('status')=='partial' ? 'selected':'' }}>🔄 Sebagian</option>
                        <option value="received" {{ request('status')=='received' ? 'selected':'' }}>✅ Diterima</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>❌ Batal</option>
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" style="width:140px;" title="Dari Tanggal">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" style="width:140px;" title="Sampai Tanggal">
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('search') || request('status') || request('date_from') || request('date_to'))
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
