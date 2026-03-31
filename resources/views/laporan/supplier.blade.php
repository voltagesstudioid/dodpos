<x-app-layout>
    <x-slot name="header">Laporan Supplier</x-slot>

    <div class="page-container">

        {{-- ─── PRINT HEADER ─── --}}
        <div class="print-only-header">
            <div class="print-kop">
                <div class="print-kop-title">{{ config('app.name', 'DODPOS') }}</div>
                <div class="print-kop-subtitle">Sistem Manajemen Bisnis & Gudang</div>
            </div>
            <div class="print-title">LAPORAN DATA SUPPLIER</div>
            <div class="print-period">
                Status: Real-Time / Saat Ini ({{ now()->format('d/m/Y H:i') }})
            </div>
        </div>

        <!-- Page Header -->
        <div class="print-hidden" style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div>
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
        <div class="card print-no-border">
            <div class="print-hidden" style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
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

        {{-- ─── PRINT FOOTER ─── --}}
        <div class="print-only-footer">
            <div class="print-signature">
                <p>Dicetak Pada: {{ now()->format('d M Y, H:i') }}</p>
                <p>Oleh: <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong></p>
                <br><br><br><br>
                <p>( ________________________ )</p>
            </div>
        </div>

    </div>

    <style>
        @media screen {
            .print-only-header, .print-only-footer { display: none; }
        }
        @media print {
            @page { size: portrait; margin: 1cm; }
            body, .page-wrapper, .page-container, .page-content { background: #fff !important; color: #000 !important; padding:0 !important; margin:0 !important; }
            .sidebar, .topbar, .print-hidden { display: none !important; }
            .card.print-no-border { box-shadow: none !important; border: none !important; margin:0 !important; padding:0 !important;}
            form, button, a.btn-secondary { display: none !important; }

            /* Header Cetak */
            .print-only-header { display: block; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
            .print-kop { text-align: center; margin-bottom: 10px; }
            .print-kop-title { font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #000; line-height: 1; }
            .print-kop-subtitle { font-size: 12px; color: #444; }
            .print-title { font-size: 16px; font-weight: 800; text-align: center; margin-bottom: 5px; text-transform: uppercase; text-decoration: underline; color: #000; }
            .print-period { text-align: center; font-size: 11px; margin-bottom: 15px; color: #000; }

            /* Tabel Cetak */
            .data-table { border-collapse: collapse !important; width: 100% !important; font-size: 11px !important; color: #000 !important; margin-top:20px;}
            .data-table th, .data-table td { border: 1px solid #000 !important; padding: 6px 8px !important; color: #000 !important; }
            .data-table th { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-weight: bold !important; text-transform: uppercase; }
            .data-table tfoot td { border-top: 2px solid #000 !important; background: #fff !important; font-weight: bold; }
            
            /* Footer Cetak */
            .print-only-footer { display: flex; justify-content: flex-end; margin-top: 40px; page-break-inside: avoid; }
            .print-signature { text-align: center; width: 250px; font-size: 12px; color: #000; }

            /* Hide Action Column in Print */
            td:last-child, th:last-child { display: none !important; }
        }
    </style>

    <script>
        function triggerPrint() {
            window.print();
        }
    </script>
</x-app-layout>
