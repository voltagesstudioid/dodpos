<x-app-layout>
    <x-slot name="header">Penjualan Kanvas Pasgar</x-slot>

    <div class="page-container">

        {{-- Summary Cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem;">
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#4f46e5;">{{ $totalOrders }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Order</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.25rem; font-weight:800; color:#10b981;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Nilai</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#f59e0b;">{{ $totalCanvas }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Order Kanvas</div>
            </div>
            <div class="card" style="padding:1.25rem; text-align:center;">
                <div style="font-size:1.5rem; font-weight:800; color:#6366f1;">{{ $totalPreorder }}</div>
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Pre-Order</div>
            </div>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">🛒 Riwayat Penjualan Kanvas</h2>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Semua transaksi penjualan oleh tim pasgar di lapangan</p>
            </div>

            {{-- Filter --}}
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input" style="width:160px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-input" style="width:160px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Anggota</label>
                        <select name="user_id" class="form-input" style="width:180px;">
                            <option value="">Semua Anggota</option>
                            @foreach($pasgarUsers as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Tipe</label>
                        <select name="order_type" class="form-input" style="width:140px;">
                            <option value="">Semua Tipe</option>
                            <option value="canvas" {{ request('order_type') === 'canvas' ? 'selected' : '' }}>Kanvas</option>
                            <option value="preorder" {{ request('order_type') === 'preorder' ? 'selected' : '' }}>Pre-Order</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Status</label>
                        <select name="status" class="form-input" style="width:140px;">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('pasgar.penjualan.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. SO</th>
                            <th>Anggota</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th style="text-align:right;">Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td style="font-weight:600; color:#4f46e5;">{{ $order->so_number }}</td>
                            <td>
                                <div style="font-weight:500;">{{ $order->user?->name ?? '—' }}</div>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $order->customer?->name ?? '—' }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $order->customer?->phone ?? '' }}</div>
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                            <td>
                                @if($order->order_type === 'canvas')
                                    <span class="badge-success">Kanvas</span>
                                @else
                                    <span class="badge-indigo">Pre-Order</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-weight:700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status === 'completed')
                                    <span class="badge-success">Selesai</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge-indigo">Pending</span>
                                @else
                                    <span class="badge-danger">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pasgar.penjualan.show', $order) }}" class="btn-secondary btn-sm">👁 Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:3rem; color:#94a3b8;">
                                <div style="font-size:2rem; margin-bottom:0.5rem;">🛒</div>
                                <div>Tidak ada data penjualan untuk filter ini.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
