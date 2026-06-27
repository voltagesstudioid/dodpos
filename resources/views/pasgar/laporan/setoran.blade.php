@extends('layouts.app', ['title' => 'Laporan Setoran'])

@push('styles')
<style>
    .lp-primary { color: #7c3aed; }
    .lp-bg-primary { background: linear-gradient(135deg, #7c3aed, #a855f7); }
    .lp-table th { background: #f8fafc; color: #475569; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.75rem 1rem; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
    .lp-table td { padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.8125rem; color: #374151; vertical-align: middle; }
    .lp-table tbody tr:last-child td { border-bottom: none; }
    .lp-table tbody tr:hover td { background: #fafbff; }

    .filter-bar-custom {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem;
        display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .filter-group { display: flex; flex-direction: column; gap: 0.3rem; flex: 1; min-width: 160px; }
    .filter-group label { font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }

    .report-header {
        padding: 1.5rem 2rem; border-bottom: 1px solid #e2e8f0;
        display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;
    }
    .report-body { padding: 2rem; }
    .report-footer {
        padding: 1.25rem 2rem; border-top: 1px solid #e2e8f0;
        text-align: center; font-size: 0.8rem; color: #94a3b8; font-weight: 600; background: #f8fafc;
    }

    .stat-mini {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;
    }
    .stat-mini-card {
        background: #fff; border-radius: 12px; padding: 1.1rem; position: relative; overflow: hidden;
    }
    .stat-mini-card .label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.3rem; }
    .stat-mini-card .value { font-size: 1.35rem; font-weight: 800; letter-spacing: -0.03em; line-height: 1; }
    .stat-mini-card .sub { font-size: 0.68rem; margin-top: 0.25rem; font-weight: 500; }

    /* Status badges */
    .stat-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.1rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; }
    .stat-badge.pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .stat-badge.verified { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .stat-badge.rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .selisih-warning {
        background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1rem 1.25rem;
        display: flex; align-items: center; gap: 0.75rem; color: #991b1b; margin-top: 1rem;
    }

    @media print {
        body * { visibility: hidden; }
        .report-section, .report-section * { visibility: visible; }
        .report-section { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .report-paper { border: none !important; box-shadow: none !important; border-radius: 0 !important; }
        .filter-bar-custom { display: none !important; }
        .report-header { padding: 1rem 0; border-bottom: 2px solid #000; }
        .report-body { padding: 1rem 0; }
        .stat-mini-card { border: 1px solid #ccc !important; padding: 0.5rem; border-radius: 4px; }
        .lp-table th { background: #eee !important; color: #000; border-bottom: 1px solid #000; padding: 0.4rem 0.5rem; font-size: 9px; }
        .lp-table td { padding: 0.4rem 0.5rem; border-bottom: 1px solid #eee; font-size: 10px; }
        .stat-badge { border: 1px solid #ccc; background: transparent !important; color: #000 !important; }
        .selisih-warning { border: 1px solid #ccc; }
        .report-footer { border-top: 1px dashed #ccc; background: transparent; }
        .back-link { display: none !important; }
        @page { margin: 12mm; size: A4; }
    }
</style>
@endpush

@section('content')
<div class="page-container animate-in">
    {{-- Page Header --}}
    <div class="ph no-print">
        <div class="ph-left">
            <div class="ph-icon" style="background:linear-gradient(135deg,#7c3aed,#a855f7);box-shadow:0 4px 14px rgba(124,58,237,.35);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </div>
            <div>
                <h1 class="ph-title">Laporan Setoran</h1>
                <div class="ph-subtitle">Pasukan Garuda</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    @if(!$isPrint)
    <div class="no-print mb-3">
        <form method="GET" action="{{ route('pasgar.laporan.setoran') }}" class="filter-bar-custom">
            <div class="filter-group">
                <label>Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input">
            </div>
            <div class="filter-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="form-input">
            </div>
            <div class="filter-group">
                <label>Sales</label>
                <select name="sales_id" class="form-input">
                    <option value="">Semua Sales</option>
                    @foreach($allSales as $s)
                        <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->kode_sales }})</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="terverifikasi" {{ $status === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div style="display:flex;gap:0.5rem;">
                <button type="submit" class="btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Terapkan
                </button>
                <a href="{{ route('pasgar.laporan.setoran', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'sales_id' => $salesId, 'status' => $status, 'print' => 1]) }}" target="_blank" class="btn-secondary" title="Cetak">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak
                </a>
            </div>
        </form>
    </div>
    @endif

    {{-- Report Document --}}
    <div class="report-section">
        <div class="panel report-paper" style="overflow:visible;">
            {{-- Header --}}
            <div class="report-header">
                <div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:1.25rem;font-weight:800;color:#1e293b;">
                        <div style="width:5px;height:22px;background:#7c3aed;border-radius:4px;"></div>
                        Laporan Setoran Pasgar
                    </div>
                    <div style="font-size:0.85rem;color:#64748b;margin-top:0.35rem;font-weight:500;">
                        Periode: {{ \Carbon\Carbon::parse($dateFrom)->isoFormat('D MMMM YYYY') }} — {{ \Carbon\Carbon::parse($dateTo)->isoFormat('D MMMM YYYY') }}
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:1rem;font-weight:800;color:#7c3aed;">{{ $storeSetting->store_name ?? 'TOKO' }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="report-body">
                {{-- Status Counts --}}
                <div style="display:flex;gap:0.75rem;margin-bottom:1.5rem;flex-wrap:wrap;">
                    <div class="stat-badge pending">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $summary['countPending'] }} Pending
                    </div>
                    <div class="stat-badge verified">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        {{ $summary['countVerified'] }} Terverifikasi
                    </div>
                    @if($summary['countRejected'] > 0)
                    <div class="stat-badge rejected">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        {{ $summary['countRejected'] }} Ditolak
                    </div>
                    @endif
                </div>

                {{-- Summary Cards --}}
                <div style="margin-bottom:1.5rem;">
                    <div style="font-size:0.82rem;font-weight:700;color:#1e293b;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Ringkasan Setoran (Seluruh Status)
                    </div>
                    <div class="stat-mini">
                        <div class="stat-mini-card" style="border:1.5px solid #a7f3d0;background:#ecfdf5;">
                            <div class="label" style="color:#047857;">Total Penjualan</div>
                            <div class="value" style="color:#065f46;">Rp {{ number_format($summary['totalPenjualan'], 0, ',', '.') }}</div>
                            <div class="sub" style="color:#6ee7b7;">{{ $summary['totalCount'] }} setoran</div>
                        </div>
                        <div class="stat-mini-card" style="border:1.5px solid #bfdbfe;background:#eff6ff;">
                            <div class="label" style="color:#1d4ed8;">Uang Disetor</div>
                            <div class="value" style="color:#1e3a8a;">Rp {{ number_format($summary['totalSetor'], 0, ',', '.') }}</div>
                            <div class="sub" style="color:#93c5fd;">{{ $summary['countVerified'] }} terverifikasi</div>
                        </div>
                        <div class="stat-mini-card" style="border:1.5px solid #fde68a;background:#fffbeb;">
                            <div class="label" style="color:#b45309;">Total Tunai</div>
                            <div class="value" style="color:#92400e;">Rp {{ number_format($summary['totalTunai'], 0, ',', '.') }}</div>
                            <div class="sub" style="color:#fcd34d;">fisik</div>
                        </div>
                        <div class="stat-mini-card" style="border:1.5px solid #e9d5ff;background:#faf5ff;">
                            <div class="label" style="color:#7e22ce;">Total Transfer</div>
                            <div class="value" style="color:#581c87;">Rp {{ number_format($summary['totalTransfer'], 0, ',', '.') }}</div>
                            <div class="sub" style="color:#d8b4fe;">Bank / QRIS</div>
                        </div>
                    </div>

                    @if($summary['totalSelisih'] != 0)
                    <div class="selisih-warning">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <div>
                            <span style="font-weight:700;">Selisih setoran terverifikasi:</span>
                            <span style="font-weight:800;color:#7f1d1d;font-size:1rem;">Rp {{ number_format(abs($summary['totalSelisih']), 0, ',', '.') }}</span>
                            <span style="font-weight:600;">{{ $summary['totalSelisih'] > 0 ? '(Uang fisik lebih dari sistem)' : '(Uang fisik kurang dari sistem)' }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Per-Sales Breakdown --}}
                <div style="margin-bottom:2rem;">
                    <div style="font-size:0.82rem;font-weight:700;color:#1e293b;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Rekapitulasi Per Sales
                    </div>
                    @if($bySales->isNotEmpty())
                    <div class="table-container" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                        <table class="lp-table" style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th style="width:4%;">No</th>
                                    <th>Nama Sales</th>
                                    <th class="text-center">Setoran</th>
                                    <th class="text-center">Pending</th>
                                    <th class="text-center">Verified</th>
                                    <th class="text-center">Ditolak</th>
                                    <th class="text-right">Total Setor</th>
                                    <th class="text-right">Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bySales as $i => $row)
                                <tr>
                                    <td style="text-align:center;">{{ $i + 1 }}</td>
                                    <td><span style="font-weight:600;color:#1e293b;">{{ $row->nama_sales }}</span></td>
                                    <td class="text-center"><span class="badge badge-gray">{{ $row->jumlah_setoran }}</span></td>
                                    <td class="text-center">{{ $row->pending }}</td>
                                    <td class="text-center"><span style="font-weight:700;color:#059669;">{{ $row->terverifikasi }}</span></td>
                                    <td class="text-center">{{ $row->ditolak }}</td>
                                    <td class="text-right" style="font-weight:700;">Rp {{ number_format($row->total_setor, 0, ',', '.') }}</td>
                                    <td class="text-right" style="font-weight:{{ $row->total_selisih != 0 ? '700' : '500' }};color:{{ $row->total_selisih > 0 ? '#059669' : ($row->total_selisih < 0 ? '#dc2626' : '#64748b') }};">
                                        {{ $row->total_selisih > 0 ? '+' : '' }}Rp {{ number_format($row->total_selisih, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state" style="border:1px solid #e2e8f0;border-radius:12px;">
                        <div class="empty-state-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="empty-state-title">Tidak ada data per sales</div>
                        <div class="empty-state-desc">Tidak ada rekapan setoran pada periode ini.</div>
                    </div>
                    @endif
                </div>

                {{-- Detail History --}}
                <div>
                    <div style="font-size:0.82rem;font-weight:700;color:#1e293b;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Rincian Histori Setoran
                    </div>
                    @if($details->isNotEmpty())
                    <div class="table-container" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                        <table class="lp-table" style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th style="width:4%;">No</th>
                                    <th>No. Setoran</th>
                                    <th>Tanggal</th>
                                    <th>Sales</th>
                                    <th class="text-right">Penjualan</th>
                                    <th class="text-right">Disetor</th>
                                    <th class="text-right">Selisih</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $i => $d)
                                <tr>
                                    <td style="text-align:center;">{{ $details->firstItem() + $i }}</td>
                                    <td><span style="font-weight:600;color:#7c3aed;">{{ $d->nomor_setoran }}</span></td>
                                    <td>{{ $d->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $d->sales?->nama ?? '-' }}</td>
                                    <td class="text-right">Rp {{ number_format($d->total_penjualan, 0, ',', '.') }}</td>
                                    <td class="text-right" style="font-weight:600;">Rp {{ number_format($d->total_setor, 0, ',', '.') }}</td>
                                    <td class="text-right" style="font-weight:{{ $d->selisih != 0 ? '700' : '500' }};color:{{ $d->selisih > 0 ? '#059669' : ($d->selisih < 0 ? '#dc2626' : '#64748b') }};">
                                        {{ $d->selisih > 0 ? '+' : '' }}Rp {{ number_format($d->selisih, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($d->status === 'terverifikasi')
                                            <span class="badge badge-success">Terverifikasi</span>
                                        @elseif($d->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(!$isPrint && $details instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div style="padding:0.75rem 1rem;border-top:1px solid #f1f5f9;">
                            {{ $details->links() }}
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="empty-state" style="border:1px solid #e2e8f0;border-radius:12px;">
                        <div class="empty-state-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div class="empty-state-title">Belum ada data setoran</div>
                        <div class="empty-state-desc">Tidak ada rincian histori setoran pada periode ini.</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="report-footer">
                {{ $storeSetting->store_name ?? 'Sistem DODPOS' }} &copy; {{ date('Y') }} &middot; Laporan Resmi Setoran Pasukan Garuda
            </div>
        </div>

        @if(!$isPrint)
        <div class="no-print back-link" style="margin-top:1.5rem;text-align:center;">
            <a href="{{ route('pasgar.dashboard') }}" class="btn-secondary" style="display:inline-flex;align-items:center;gap:0.4rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
