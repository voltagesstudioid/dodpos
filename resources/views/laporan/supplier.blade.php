<x-app-layout>
    <x-slot name="header">Laporan Supplier</x-slot>

    <div class="page-container">

        <!-- Page Header -->
        <div style="margin-bottom:1.5rem;">
            <h1 style="font-size:1.5rem; font-weight:800; color:#1e293b; margin:0;">🏭 Laporan Supplier</h1>
            <p style="color:#64748b; font-size:0.875rem; margin:0.35rem 0 0;">Ringkasan data supplier aktif dan status hutang berjalan.</p>
        </div>

        <!-- Summary Cards -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1.25rem; margin-bottom:1.5rem;">

            <div class="card" style="padding:1.5rem; border-top:4px solid #3b82f6;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">🏭 Total Supplier Aktif</div>
                <div style="font-size:2rem; font-weight:900; color:#1d4ed8; line-height:1;">{{ number_format($totalSuppliers, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Supplier terdaftar</div>
            </div>

            <div class="card" style="padding:1.5rem; border-top:4px solid #ef4444;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">💳 Total Hutang Berjalan</div>
                <div style="font-size:2rem; font-weight:900; color:#dc2626; line-height:1;">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Hutang yang belum lunas</div>
            </div>

            <div class="card" style="padding:1.5rem; border-top:4px solid #f59e0b;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">⚠️ Supplier Memiliki Hutang</div>
                <div style="font-size:2rem; font-weight:900; color:#d97706; line-height:1;">{{ $suppliersWithDebt }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Supplier masih ada hutang</div>
            </div>

        </div>

        <!-- Table Section -->
        <div class="card">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
                <h3 style="font-size:0.95rem; font-weight:700; color:#1e293b; margin:0;">📋 Daftar Supplier Berdasarkan Hutang</h3>
                <form action="{{ route('laporan.supplier') }}" method="GET" style="display:flex; gap:0.5rem; align-items:center;">
                    <input type="text" name="search" value="{{ $search }}" placeholder="🔍 Cari nama supplier / kontak..." class="form-input" style="width:280px;">
                    <button type="submit" class="btn-primary" style="padding:0.5rem 1rem;">Cari</button>
                    @if($search) <a href="{{ route('laporan.supplier') }}" class="btn-secondary" style="padding:0.5rem 1rem;">Reset</a> @endif
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nama Supplier</th>
                            <th>Kontak Person</th>
                            <th>Telepon</th>
                            <th style="text-align:right;">Total Tagihan (Rp)</th>
                            <th style="text-align:right;">Sisa Hutang (Rp)</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $i => $supplier)
                            @php
                                $outstandingDebts  = $supplier->debts->whereIn('status', ['unpaid', 'partial']);
                                $totalInvoiceAmt   = $outstandingDebts->sum('total_amount');
                                $totalRemainingAmt = $outstandingDebts->sum(fn($d) => $d->total_amount - $d->paid_amount);
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $suppliers->firstItem() + $i }}</td>
                                <td>
                                    <div style="font-weight:600; color:#1e293b;">{{ $supplier->name }}</div>
                                    @if(!$supplier->active)
                                        <span class="badge-danger" style="font-size:0.65rem; margin-top:0.2rem;">Nonaktif</span>
                                    @endif
                                </td>
                                <td style="color:#64748b;">{{ $supplier->contact_person ?: '-' }}</td>
                                <td style="color:#64748b;">{{ $supplier->phone ?: '-' }}</td>
                                <td style="text-align:right; color:#334155; font-weight:600;">{{ number_format($totalInvoiceAmt, 0, ',', '.') }}</td>
                                <td style="text-align:right; font-weight:700; color:{{ $totalRemainingAmt > 0 ? '#dc2626' : '#15803d' }};">
                                    {{ number_format($totalRemainingAmt, 0, ',', '.') }}
                                </td>
                                <td style="text-align:center; white-space:nowrap;">
                                    <a href="{{ route('master.supplier.edit', $supplier->id) }}" class="btn-sm btn-warning" style="margin-right:0.35rem;">✏️ Edit</a>
                                    <a href="{{ route('pembelian.hutang.index', ['supplier_id' => $supplier->id]) }}" class="btn-sm btn-primary">📄 Hutang</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:3rem; color:#94a3b8;">
                                    @if($search)
                                        Tidak ada supplier ditemukan untuk pencarian "<strong>{{ $search }}</strong>".
                                    @else
                                        Belum ada data supplier.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($suppliers->hasPages())
                <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $suppliers->links() }}</div>
            @endif
        </div>

    </div>
</x-app-layout>
