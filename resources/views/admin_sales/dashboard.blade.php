<x-app-layout>
    <x-slot name="header">Admin Sales Dashboard</x-slot>

    <div class="page-container">
        {{-- Header --}}
        <div class="panel" style="margin-bottom:1.25rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">📈 Admin Sales Dashboard</div>
                    <div class="panel-subtitle">Pusat kontrol area penjualan dan faktur.</div>
                </div>
                <span class="badge badge-blue">Role: Admin Sales</span>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stat-grid" style="margin-bottom:1.25rem;">
            <div class="stat-card">
                <div class="stat-icon indigo">📄</div>
                <div>
                    <div class="stat-label">Total Sales Order</div>
                    <div class="stat-value indigo">{{ $totalSalesOrders }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Semua SO</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber">⏳</div>
                <div>
                    <div class="stat-label">SO Pending</div>
                    <div class="stat-value amber">{{ $pendingSalesOrders }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Belum diproses</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon emerald">✅</div>
                <div>
                    <div class="stat-label">SO Selesai</div>
                    <div class="stat-value emerald">{{ $completedSalesOrders }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Telah diselesaikan</div>
                </div>
            </div>
        </div>

        {{-- Quick Access --}}
        <div class="grid-4" style="margin-bottom:1.25rem;">
            @can('view_pos_kasir')
                <a href="{{ route('kasir.index') }}" style="display:flex;flex-direction:column;justify-content:space-between;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:14px;padding:1.25rem;text-decoration:none;color:white;min-height:110px;transition:all .2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                    <div style="font-size:1.5rem;">🖥️</div>
                    <div>
                        <div style="font-weight:700;font-size:0.9375rem;">Buka Kasir (POS)</div>
                        <div style="font-size:0.75rem;opacity:.85;margin-top:2px;">Kasir eceran / grosir</div>
                    </div>
                </a>
            @endcan
            <a href="{{ route('sales-order.index') }}" style="display:flex;flex-direction:column;justify-content:space-between;background:linear-gradient(135deg,#2563eb,#0ea5e9);border-radius:14px;padding:1.25rem;text-decoration:none;color:white;min-height:110px;transition:all .2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="font-size:1.5rem;">📋</div>
                <div>
                    <div style="font-weight:700;font-size:0.9375rem;">Kelola Sales Order</div>
                    <div style="font-size:0.75rem;opacity:.85;margin-top:2px;">Catat & proses pesanan</div>
                </div>
            </a>
            <a href="{{ route('transaksi.index') }}" style="display:flex;flex-direction:column;justify-content:space-between;background:linear-gradient(135deg,#059669,#10b981);border-radius:14px;padding:1.25rem;text-decoration:none;color:white;min-height:110px;transition:all .2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="font-size:1.5rem;">🧾</div>
                <div>
                    <div style="font-weight:700;font-size:0.9375rem;">Riwayat Transaksi</div>
                    <div style="font-size:0.75rem;opacity:.85;margin-top:2px;">Lihat & cetak struk</div>
                </div>
            </a>
            <a href="{{ route('laporan.penjualan') }}" style="display:flex;flex-direction:column;justify-content:space-between;background:linear-gradient(135deg,#475569,#0f172a);border-radius:14px;padding:1.25rem;text-decoration:none;color:white;min-height:110px;transition:all .2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
                <div style="font-size:1.5rem;">📊</div>
                <div>
                    <div style="font-weight:700;font-size:0.9375rem;">Laporan Penjualan</div>
                    <div style="font-size:0.75rem;opacity:.85;margin-top:2px;">Analisa performa toko</div>
                </div>
            </a>
        </div>

        {{-- Recent Orders --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">⏱️ Sales Order Terbaru</div>
                    <div class="panel-subtitle">10 SO terakhir</div>
                </div>
                <a href="{{ route('sales-order.index') }}" class="panel-action">Lihat Semua →</a>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. SO</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th style="text-align:right;">Total</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $so)
                        <tr>
                            <td>
                                <a href="{{ route('sales-order.show', $so->id) }}" style="font-weight:700;color:#4f46e5;text-decoration:none;">{{ $so->so_number }}</a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($so->order_date)->format('d M Y') }}</td>
                            <td>{{ $so->customer ? $so->customer->name : 'Umum' }}</td>
                            <td style="text-align:right;font-weight:700;">Rp {{ number_format($so->total_amount, 0, ',', '.') }}</td>
                            <td style="text-align:center;">
                                @php
                                    $statusMap = ['pending'=>'badge-warning','draft'=>'badge-gray','confirmed'=>'badge-blue','processing'=>'badge-indigo','completed'=>'badge-success','cancelled'=>'badge-danger'];
                                    $statusLabel = ucfirst($so->status ?? '-');
                                    $statusClass = $statusMap[$so->status ?? ''] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-state-icon">📋</div>
                                <div class="empty-state-title">Belum ada Sales Order</div>
                                <a href="{{ route('sales-order.create') }}" class="btn-primary btn-sm">Buat SO pertama →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
