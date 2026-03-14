<x-app-layout>
    <x-slot name="header">Sales Order</x-slot>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Daftar Sales Order</div>
                <div class="page-header-subtitle">Kelola dan pantau pesanan pelanggan dengan mudah</div>
            </div>
            <div class="page-header-actions">
                @can('create_sales_order')
                <a href="{{ route('sales-order.create') }}" class="btn-primary">➕ Buat SO Baru</a>
                @endcan
            </div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon indigo">📄</div>
                <div>
                    <div class="stat-label">Total</div>
                    <div class="stat-value indigo">{{ $totalCount ?? $salesOrders->total() }}</div>
                    <span class="badge badge-gray">Sesuai filter</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber">📝</div>
                <div>
                    <div class="stat-label">Draft</div>
                    <div class="stat-value amber">{{ $draftCount ?? 0 }}</div>
                    <span class="badge badge-warning">Draft</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">⚙️</div>
                <div>
                    <div class="stat-label">Proses</div>
                    <div class="stat-value blue">{{ ($confirmedCount ?? 0) + ($processingCount ?? 0) }}</div>
                    <span class="badge badge-blue">Confirmed/Processing</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon emerald">✅</div>
                <div>
                    <div class="stat-label">Selesai</div>
                    <div class="stat-value emerald">{{ $completedCount ?? 0 }}</div>
                    <span class="badge badge-success">Completed</span>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Pencarian</div>
                    <div class="panel-subtitle">Cari berdasarkan nomor SO, pelanggan, atau pembuat</div>
                </div>
            </div>
            <div class="panel-body">
                <form action="{{ route('sales-order.index') }}" method="GET" class="form-row">
                    <div>
                        <label class="form-label">Kata Kunci</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-input" placeholder="Contoh: SO-2026..., nama pelanggan, admin">
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="">Semua</option>
                            <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" value="{{ $date }}" class="form-input">
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                        <button type="submit" class="btn-primary">🔎 Terapkan</button>
                        @if($search || $status || $date)
                            <a href="{{ route('sales-order.index') }}" class="btn-secondary">↺ Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Data Sales Order</div>
                    <div class="panel-subtitle">Daftar SO terbaru</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No. SO</th>
                                <th>Tgl Order</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salesOrders as $so)
                                <tr>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:0.25rem;">
                                            <a href="{{ route('sales-order.show', $so->id) }}" style="text-decoration:none;">
                                                <span class="badge badge-indigo">{{ $so->so_number }}</span>
                                            </a>
                                            <span style="font-size:0.8rem;color:#64748b;">Oleh: {{ $so->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($so->order_date)->format('d M Y') }}</td>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:0.15rem;">
                                            <span style="font-weight:800;color:#0f172a;">{{ $so->customer->name ?? '-' }}</span>
                                            @if($so->customer && $so->customer->phone)
                                                <span style="font-size:0.8rem;color:#64748b;">{{ $so->customer->phone }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="font-weight:900;color:#0f172a;">Rp {{ number_format($so->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($so->status == 'draft')
                                            <span class="badge badge-gray">Draft</span>
                                        @elseif($so->status == 'confirmed')
                                            <span class="badge badge-blue">Confirmed</span>
                                        @elseif($so->status == 'processing')
                                            <span class="badge badge-indigo">Processing</span>
                                        @elseif($so->status == 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif($so->status == 'cancelled')
                                            <span class="badge badge-danger">Cancelled</span>
                                        @else
                                            <span class="badge badge-gray">{{ $so->status }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        <div style="display:inline-flex;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                                            <a href="{{ route('sales-order.show', $so->id) }}" class="btn-secondary">👁️ Detail</a>
                                            @can('edit_sales_order')
                                            @if(!in_array($so->status, ['completed', 'cancelled']))
                                                <a href="{{ route('sales-order.edit', $so->id) }}" class="btn-primary">✏️ Edit</a>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">📄</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada Sales Order</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                                Buat Sales Order baru untuk mulai mengelola pesanan pelanggan.
                                            </div>
                                            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:center;">
                                                @if($search || $status || $date)
                                                    <a href="{{ route('sales-order.index') }}" class="btn-secondary">↺ Hapus Filter</a>
                                                @else
                                                    @can('create_sales_order')
                                                    <a href="{{ route('sales-order.create') }}" class="btn-primary">➕ Buat SO Baru</a>
                                                    @endcan
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1rem;">
                    {{ $salesOrders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
