<x-app-layout>
    <x-slot name="header">Laporan Pelanggan</x-slot>

    <div class="page-container">

        <!-- Page Header -->
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#1e293b; margin:0;">👥 Laporan Pelanggan</h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.35rem 0 0;">Ringkasan data pelanggan aktif dan status piutang mereka.</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1.25rem; margin-bottom:1.5rem;">

            <div class="card" style="padding:1.5rem; border-top:4px solid #3b82f6;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">👤 Total Pelanggan Aktif</div>
                <div style="font-size:2rem; font-weight:900; color:#1d4ed8; line-height:1;">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Pelanggan terdaftar</div>
            </div>

            <div class="card" style="padding:1.5rem; border-top:4px solid #ef4444;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">💳 Total Piutang Berjalan</div>
                <div style="font-size:2rem; font-weight:900; color:#dc2626; line-height:1;">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Piutang yang belum lunas</div>
            </div>

            <div class="card" style="padding:1.5rem; border-top:4px solid #f59e0b;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">⚠️ Memiliki Piutang Aktif</div>
                <div style="font-size:2rem; font-weight:900; color:#d97706; line-height:1;">{{ $customersWithDebt }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Pelanggan masih berutang</div>
            </div>

        </div>

        <!-- Table Section -->
        <div class="card">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
                <h3 style="font-size:0.95rem; font-weight:700; color:#1e293b; margin:0;">📋 Daftar Pelanggan Berdasarkan Piutang</h3>
                <form action="{{ route('laporan.pelanggan') }}" method="GET" style="display:flex; gap:0.5rem; align-items:center;">
                    <input type="text" name="search" value="{{ $search }}" placeholder="🔍 Cari nama atau no. telepon..." class="form-input" style="width:280px;">
                    <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;">Cari</button>
                    @if($search) <a href="{{ route('laporan.pelanggan') }}" class="btn-secondary" style="padding:0.5rem 1rem;">Reset</a> @endif
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nama Pelanggan</th>
                            <th>Kontak</th>
                            <th style="text-align:right;">Limit Piutang (Rp)</th>
                            <th style="text-align:right;">Sisa Limit (Rp)</th>
                            <th style="text-align:right;">Total Piutang (Rp)</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $i => $customer)
                            <tr>
                                <td class="text-muted">{{ $customers->firstItem() + $i }}</td>
                                <td>
                                    <div style="font-weight:600; color:#1e293b;">{{ $customer->name }}</div>
                                    @if(!$customer->is_active)
                                        <span class="badge-danger" style="font-size:0.65rem; margin-top:0.2rem;">Nonaktif</span>
                                    @endif
                                </td>
                                <td style="color:#64748b;">{{ $customer->phone ?: '-' }}</td>
                                <td style="text-align:right; color:#334155;">{{ number_format($customer->credit_limit, 0, ',', '.') }}</td>
                                <td style="text-align:right; color:#4f46e5; font-weight:600;">{{ number_format($customer->remaining_credit_limit, 0, ',', '.') }}</td>
                                <td style="text-align:right; font-weight:700; color:{{ $customer->current_debt > 0 ? '#dc2626' : '#15803d' }};">
                                    {{ number_format($customer->current_debt, 0, ',', '.') }}
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('pelanggan.show', $customer->id) }}" class="btn-sm btn-warning">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:3rem; color:#94a3b8;">
                                    @if($search)
                                        Tidak ada pelanggan ditemukan untuk pencarian "<strong>{{ $search }}</strong>".
                                    @else
                                        Belum ada data pelanggan.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $customers->links() }}</div>
            @endif
        </div>

    </div>
</x-app-layout>
