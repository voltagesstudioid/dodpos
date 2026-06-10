<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hutang Pasgar</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #1e293b; font-size: 13px; }
        .page { max-width: 210mm; margin: 0 auto; background: #fff; }

        .hdr { padding: 24px 32px; border-bottom: 3px solid #065f46; display: flex; justify-content: space-between; align-items: flex-start; }
        .hdr-left h1 { font-size: 20px; font-weight: 800; color: #065f46; }
        .hdr-left p { font-size: 12px; color: #64748b; margin-top: 2px; }
        .hdr-right { text-align: right; font-size: 11px; color: #94a3b8; }
        .hdr-right strong { color: #065f46; font-size: 13px; display: block; margin-bottom: 2px; }

        .filter-bar { padding: 16px 32px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; gap: 12px; align-items: end; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; gap: 4px; }
        .filter-group label { font-size: 11px; font-weight: 600; color: #64748b; }
        .filter-group select, .filter-group input { padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; font-family: inherit; }
        .btn { padding: 7px 16px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit; }
        .btn-primary { background: #065f46; color: #fff; }
        .btn-print { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
        .btn-outline { background: #fff; color: #065f46; border: 1px solid #065f46; }

        .content { padding: 24px 32px; }

        .summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
        .s-card { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; }
        .s-card.blue { background: #eff6ff; border-color: #bfdbfe; }
        .s-card.amber { background: #fffbeb; border-color: #fde68a; }
        .s-card.purple { background: #faf5ff; border-color: #e9d5ff; }
        .s-card.red { background: #fef2f2; border-color: #fecaca; }
        .s-card-label { font-size: 10px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .s-card-value { font-size: 18px; font-weight: 800; color: #1e293b; margin-top: 2px; font-family: 'JetBrains Mono', monospace; }
        .s-card-sub { font-size: 10px; color: #94a3b8; margin-top: 1px; }

        .section { margin-bottom: 24px; }
        .section-title { font-size: 14px; font-weight: 700; color: #065f46; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 2px solid #d1fae5; display: flex; align-items: center; gap: 6px; }
        .section-title::before { content: ''; width: 4px; height: 16px; background: #065f46; border-radius: 2px; }

        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        thead th { background: #f0fdf4; color: #065f46; font-weight: 700; padding: 8px 10px; text-align: left; border-bottom: 2px solid #bbf7d0; font-size: 11px; text-transform: uppercase; letter-spacing: 0.03em; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; }
        tbody tr:hover { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mono { font-family: 'JetBrains Mono', monospace; font-size: 11px; }
        .text-bold { font-weight: 700; }
        .text-red { color: #dc2626; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        tfoot td { padding: 8px 10px; font-weight: 700; border-top: 2px solid #bbf7d0; background: #f0fdf4; }

        .footer { padding: 16px 32px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 10px; color: #94a3b8; }
        .empty { text-align: center; padding: 32px; color: #94a3b8; }

        .pagination-bar { display: flex; justify-content: center; gap: 4px; margin-top: 16px; flex-wrap: wrap; }
        .pagination-bar a, .pagination-bar span { display: inline-block; padding: 4px 10px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 11px; text-decoration: none; color: #334155; }
        .pagination-bar span.current { background: #065f46; color: #fff; border-color: #065f46; }

        .alert-overdue { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 10px 16px; font-size: 12px; color: #991b1b; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }

        @media print {
            body { background: #fff; font-size: 11px; }
            .no-print { display: none !important; }
            .page { max-width: none; box-shadow: none; margin: 0; }
            .hdr, .content { padding: 16px 20px; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
        }
        @page { size: A4; margin: 12mm; }
    </style>
</head>
<body>
    @if(!$isPrint)
    <div class="page no-print">
        <form method="GET" action="{{ route('pasgar.laporan.hutang') }}" class="filter-bar">
            <div class="filter-group">
                <label>Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}">
            </div>
            <div class="filter-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo }}">
            </div>
            <div class="filter-group">
                <label>Sales</label>
                <select name="sales_id">
                    <option value="">Semua Sales</option>
                    @foreach($allSales as $s)
                        <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->kode_sales }})</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="belum_lunas" {{ $status === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                    <option value="lunas" {{ $status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <a href="{{ route('pasgar.laporan.hutang', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'sales_id' => $salesId, 'status' => $status, 'print' => 1]) }}" target="_blank" class="btn btn-print">🖨️ Cetak</a>
            <a href="{{ route('pasgar.dashboard') }}" class="btn btn-outline">← Kembali</a>
        </form>
    </div>
    <div style="height:12px"></div>
    @endif

    <div class="page">
        <div class="hdr">
            <div class="hdr-left">
                <h1>Laporan Hutang Pelanggan Pasgar</h1>
                <p>Pasukan Garuda &middot; {{ \Carbon\Carbon::parse($dateFrom)->isoFormat('D MMMM YYYY') }} — {{ \Carbon\Carbon::parse($dateTo)->isoFormat('D MMMM YYYY') }}</p>
            </div>
            <div class="hdr-right">
                <strong>TOKO SEDERHANA</strong>
                Dicetak: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="content">
            {{-- Overdue alert --}}
            @if($summary['countOverdue'] > 0)
            <div class="alert-overdue">
                ⚠️ Ada <strong>{{ $summary['countOverdue'] }} hutang jatuh tempo</strong> yang perlu segera ditindaklanjuti!
            </div>
            @endif

            {{-- Summary --}}
            <div class="section">
                <div class="section-title">Ringkasan Hutang</div>
                <div class="summary">
                    <div class="s-card">
                        <div class="s-card-label">Total Hutang</div>
                        <div class="s-card-value">Rp {{ number_format($summary['totalHutang'], 0, ',', '.') }}</div>
                        <div class="s-card-sub">seluruh hutang tercatat</div>
                    </div>
                    <div class="s-card blue">
                        <div class="s-card-label">Total Dibayar</div>
                        <div class="s-card-value">Rp {{ number_format($summary['totalDibayar'], 0, ',', '.') }}</div>
                        <div class="s-card-sub">pembayaran diterima</div>
                    </div>
                    <div class="s-card red">
                        <div class="s-card-label">Total Sisa</div>
                        <div class="s-card-value">Rp {{ number_format($summary['totalSisa'], 0, ',', '.') }}</div>
                        <div class="s-card-sub">belum dilunasi</div>
                    </div>
                    <div class="s-card amber">
                        <div class="s-card-label">Hutang Aktif</div>
                        <div class="s-card-value">{{ $summary['countAktif'] }}</div>
                        <div class="s-card-sub">belum lunas</div>
                    </div>
                    <div class="s-card purple">
                        <div class="s-card-label">Sudah Lunas</div>
                        <div class="s-card-value">{{ $summary['countLunas'] }}</div>
                        <div class="s-card-sub">hutang selesai</div>
                    </div>
                    <div class="s-card red">
                        <div class="s-card-label">Jatuh Tempo</div>
                        <div class="s-card-value">{{ $summary['countOverdue'] }}</div>
                        <div class="s-card-sub">melebihi batas waktu</div>
                    </div>
                </div>
            </div>

            {{-- Per-sales breakdown --}}
            <div class="section">
                <div class="section-title">Hutang Per Sales</div>
                @if($bySales->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th style="width:4%">#</th>
                            <th>Nama Sales</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Total Hutang</th>
                            <th class="text-right">Dibayar</th>
                            <th class="text-right">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bySales as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-bold">{{ $row->nama_sales }} <span style="color:#94a3b8;font-size:10px">({{ $row->kode_sales }})</span></td>
                            <td class="text-center">{{ $row->jumlah }}</td>
                            <td class="text-right mono">Rp {{ number_format($row->total_hutang, 0, ',', '.') }}</td>
                            <td class="text-right mono" style="color:#065f46">Rp {{ number_format($row->total_dibayar, 0, ',', '.') }}</td>
                            <td class="text-right mono text-bold" style="color:#dc2626">Rp {{ number_format($row->total_sisa, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total</td>
                            <td class="text-center">{{ $bySales->sum('jumlah') }}</td>
                            <td class="text-right mono">Rp {{ number_format($bySales->sum('total_hutang'), 0, ',', '.') }}</td>
                            <td class="text-right mono" style="color:#065f46">Rp {{ number_format($bySales->sum('total_dibayar'), 0, ',', '.') }}</td>
                            <td class="text-right mono text-bold" style="color:#dc2626">Rp {{ number_format($bySales->sum('total_sisa'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="empty">Tidak ada data hutang</div>
                @endif
            </div>

            {{-- Detail hutang --}}
            <div class="section">
                <div class="section-title">Detail Hutang Pelanggan</div>
                @if($details->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th style="width:4%">#</th>
                            <th>Pelanggan</th>
                            <th>Sales</th>
                            <th>No. Transaksi</th>
                            <th class="text-right">Hutang</th>
                            <th class="text-right">Dibayar</th>
                            <th class="text-right">Sisa</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $i => $d)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-bold">{{ $d->pelanggan?->nama_toko ?? '-' }}</td>
                            <td>{{ $d->penjualan?->sales?->nama ?? '-' }}</td>
                            <td class="mono" style="font-size:10px">{{ $d->penjualan?->nomor_transaksi ?? '-' }}</td>
                            <td class="text-right mono">Rp {{ number_format($d->total_hutang, 0, ',', '.') }}</td>
                            <td class="text-right mono" style="color:#065f46">Rp {{ number_format($d->dibayar, 0, ',', '.') }}</td>
                            <td class="text-right mono text-bold" style="color:#dc2626">Rp {{ number_format($d->sisa, 0, ',', '.') }}</td>
                            <td style="font-size:11px;{{ $d->jatuh_tempo && $d->jatuh_tempo->isPast() ? 'color:#dc2626;font-weight:700' : '' }}">
                                {{ $d->jatuh_tempo ? $d->jatuh_tempo->format('d/m/Y') : '-' }}
                            </td>
                            <td class="text-center">
                                @if($d->status === 'lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($d->status === 'overdue')
                                    <span class="badge badge-danger">Jatuh Tempo</span>
                                @else
                                    <span class="badge badge-warning">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total ({{ $details->count() }} hutang)</td>
                            <td class="text-right mono">Rp {{ number_format($details->sum('total_hutang'), 0, ',', '.') }}</td>
                            <td class="text-right mono" style="color:#065f46">Rp {{ number_format($details->sum('dibayar'), 0, ',', '.') }}</td>
                            <td class="text-right mono text-bold" style="color:#dc2626">Rp {{ number_format($details->sum('sisa'), 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>

                @if(!$isPrint && $details instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination-bar">
                    {{ $details->links() }}
                </div>
                @endif
                @else
                <div class="empty">Tidak ada data hutang</div>
                @endif
            </div>
        </div>

        <div class="footer">
            Laporan Hutang Pasgar &middot; DOD POS &middot; {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    @if(!$isPrint)
    <div style="text-align:center;padding:16px" class="no-print">
        <button onclick="window.print()" class="btn btn-print" style="padding:10px 32px;font-size:14px">🖨️ Cetak Laporan</button>
    </div>
    @endif
</body>
</html>
