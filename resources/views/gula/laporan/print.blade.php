<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Gula — {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 9pt; color: #1e293b; background: #fff; padding: 2cm; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        /* Header */
        .hdr { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #1e293b; padding-bottom: 12px; margin-bottom: 16px; }
        .hdr-company { font-size: 18pt; font-weight: 800; color: #1e293b; letter-spacing: -0.02em; }
        .hdr-title { font-size: 12pt; font-weight: 600; color: #475569; margin-top: 2px; }
        .hdr-period { font-size: 8pt; color: #64748b; margin-top: 3px; }
        .hdr-right { text-align: right; font-size: 7.5pt; color: #94a3b8; }

        /* KPIs */
        .kpis { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-bottom: 18px; }
        .kpi { border: 1.5px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; }
        .kpi-lbl { font-size: 6.5pt; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .kpi-val { font-size: 13pt; font-weight: 800; color: #1e293b; margin-top: 3px; }
        .kpi-sub { font-size: 7pt; color: #94a3b8; margin-top: 2px; }

        /* Section */
        .section { margin-bottom: 16px; }
        .section-title { font-size: 10pt; font-weight: 700; color: #1e293b; border-bottom: 1.5px solid #cbd5e1; padding-bottom: 5px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.05em; }

        /* Tables */
        .tbl { width: 100%; border-collapse: collapse; font-size: 8pt; }
        .tbl thead th { background: #f1f5f9; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; font-size: 6.5pt; padding: 7px 8px; border-bottom: 2px solid #cbd5e1; text-align: left; }
        .tbl tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        .tbl tbody tr:last-child td { border-bottom: none; }
        .tbl tfoot td { padding: 7px 8px; font-weight: 700; border-top: 2px solid #cbd5e1; background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Two columns */
        .cols-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Daily bars */
        .bar-row { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; font-size: 7.5pt; }
        .bar-lbl { width: 42px; text-align: right; color: #64748b; font-weight: 600; flex-shrink: 0; }
        .bar-track { flex: 1; height: 12px; background: #e2e8f0; border-radius: 3px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 3px; background: #f59e0b; print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; }
        .bar-fill.zero { background: #f1f5f9; }
        .bar-val { width: 80px; font-weight: 700; flex-shrink: 0; font-size: 7.5pt; }

        /* Badge */
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 6.5pt; font-weight: 700; }
        .badge.tunai { background: #dcfce7; color: #166534; }
        .badge.transfer { background: #dbeafe; color: #1d4ed8; }
        .badge.hutang { background: #fef3c7; color: #92400e; }

        /* Sales avatar */
        .sales-av { display: inline-flex; width: 18px; height: 18px; border-radius: 50%; align-items: center; justify-content: center; font-size: 6.5pt; font-weight: 700; color: #fff; vertical-align: middle; print-color-adjust: exact !important; -webkit-print-color-adjust: exact !important; background: #f59e0b; }

        /* Boxes */
        .box { border: 1.5px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; margin-bottom: 8px; }
        .box-title { font-size: 7pt; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .big-val { font-size: 14pt; font-weight: 800; }
        .sub-val { font-size: 7.5pt; color: #64748b; }

        /* Footer */
        .footer { margin-top: 24px; padding-top: 12px; border-top: 1.5px solid #cbd5e1; display: flex; justify-content: space-between; }
        .sign { text-align: center; width: 200px; }
        .sign-line { border-bottom: 1.5px solid #1e293b; margin-top: 55px; margin-bottom: 4px; }
        .sign-name { font-size: 9pt; font-weight: 600; }
        .sign-title { font-size: 7.5pt; color: #64748b; }
        .note { font-size: 7.5pt; color: #94a3b8; max-width: 340px; line-height: 1.5; }

        .empty-row { text-align: center; color: #94a3b8; padding: 16px; font-size: 8pt; }

        @page { margin: 1.5cm; size: A4 landscape; }

        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="hdr">
        <div>
            <div class="hdr-company">DOD POS</div>
            <div class="hdr-title">Laporan Penjualan Gula</div>
            <div class="hdr-period">{{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('D MMMM YYYY') }} &mdash; {{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('D MMMM YYYY') }}</div>
        </div>
        <div class="hdr-right">
            <div>Divisi: Gula</div>
            <div>Dicetak: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB</div>
            @if(request('sales_id'))
            <div>Sales: {{ $salesList->firstWhere('id', request('sales_id'))->nama ?? 'Filter aktif' }}</div>
            @else
            <div>Semua Sales</div>
            @endif
        </div>
    </div>

    {{-- KPI Summary --}}
    <div class="kpis">
        <div class="kpi">
            <div class="kpi-lbl">Total Penjualan</div>
            <div class="kpi-val mono">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $summary['jumlah_transaksi'] }} transaksi</div>
        </div>
        <div class="kpi">
            <div class="kpi-lbl">Tunai</div>
            <div class="kpi-val mono">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $summary['jumlah_transaksi'] > 0 ? number_format($summary['total_tunai'] / $summary['total_penjualan'] * 100, 1) : 0 }}% dari total</div>
        </div>
        <div class="kpi">
            <div class="kpi-lbl">Transfer</div>
            <div class="kpi-val mono">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $summary['jumlah_transaksi'] > 0 ? number_format($summary['total_transfer'] / $summary['total_penjualan'] * 100, 1) : 0 }}% dari total</div>
        </div>
        <div class="kpi">
            <div class="kpi-lbl">Penjualan Hutang</div>
            <div class="kpi-val mono">Rp {{ number_format($summary['penjualan_hutang'], 0, ',', '.') }}</div>
            <div class="kpi-sub">Piutang: Rp {{ number_format($summary['hutang_field'], 0, ',', '.') }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-lbl">Bayar di Tempat (Hutang)</div>
            <div class="kpi-val mono">Rp {{ number_format($summary['bayar_hutang'], 0, ',', '.') }}</div>
            <div class="kpi-sub">Uang tunai diterima</div>
        </div>
    </div>

    {{-- Daily Sales Chart --}}
    @if($dailyData->where('total', '>', 0)->count() > 0)
    @php $maxDaily = $dailyData->max('total') ?: 1; @endphp
    <div class="section">
        <div class="section-title">Grafik Penjualan Harian</div>
        @foreach($dailyData as $d)
        @php $pct = $maxDaily > 0 ? ($d->total / $maxDaily) * 100 : 0; @endphp
        <div class="bar-row">
            <div class="bar-lbl">{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m') }}</div>
            <div class="bar-track"><div class="bar-fill {{ $d->total > 0 ? '' : 'zero' }}" style="width:{{ max($pct, 2) }}%"></div></div>
            <div class="bar-val">
                @if($d->total > 0)
                    Rp {{ number_format($d->total, 0, ',', '.') }} <span style="color:#94a3b8;font-weight:400;">({{ $d->jumlah }})</span>
                @else
                    <span style="color:#cbd5e1">—</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="cols-2">
        {{-- Sales Performance --}}
        <div class="section">
            <div class="section-title">Performa Sales</div>
            <table class="tbl">
                <thead><tr><th>#</th><th>Sales</th><th class="text-center">Transaksi</th><th class="text-right">Omzet</th></tr></thead>
                <tbody>
                @php $hasSalesData = false; @endphp
                @foreach($salesPerformance as $i => $sp)
                @if(($sp->omzet ?? 0) > 0 || $sp->total_penjualan > 0)
                @php $hasSalesData = true; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>
                        <span class="sales-av">{{ strtoupper(substr($sp->nama, 0, 1)) }}</span>
                        {{ $sp->nama }}
                    </td>
                    <td class="text-center">{{ $sp->total_penjualan }}</td>
                    <td class="text-right mono">Rp {{ number_format($sp->omzet ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
                @if(!$hasSalesData)
                <tr><td colspan="4" class="empty-row">Tidak ada data</td></tr>
                @endif
                </tbody>
                @if($hasSalesData)
                <tfoot>
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="text-center">{{ $salesPerformance->sum('total_penjualan') }}</td>
                        <td class="text-right mono">Rp {{ number_format($salesPerformance->sum('omzet'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Product Performance --}}
        <div class="section">
            <div class="section-title">Performa Produk</div>
            <table class="tbl">
                <thead><tr><th>#</th><th>Produk</th><th class="text-center">Terjual</th><th class="text-right">Omzet</th></tr></thead>
                <tbody>
                @php $hasProductData = false; @endphp
                @foreach($productPerformance as $i => $pp)
                @if($pp->terjual > 0)
                @php $hasProductData = true; @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $pp->nama }}<br><span style="font-size:6pt;color:#94a3b8;">{{ $pp->jenis ?? '' }}</span></td>
                    <td class="text-center">{{ $pp->terjual }} {{ $pp->satuan ?? '' }}</td>
                    <td class="text-right mono">Rp {{ number_format($pp->omzet ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
                @if(!$hasProductData)
                <tr><td colspan="4" class="empty-row">Tidak ada data</td></tr>
                @endif
                </tbody>
                @if($hasProductData)
                <tfoot>
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="text-center">{{ $productPerformance->sum('terjual') }}</td>
                        <td class="text-right mono">Rp {{ number_format($productPerformance->sum('omzet'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Transaction Detail --}}
    @if($topTransactions->isNotEmpty())
    <div class="section">
        <div class="section-title">Detail Transaksi ({{ $topTransactions->count() }} transaksi)</div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>No. Faktur</th>
                    <th>Tanggal</th>
                    <th>Sales</th>
                    <th>Pelanggan</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topTransactions as $t)
                <tr>
                    <td class="mono" style="font-weight:600;">{{ $t->no_faktur }}</td>
                    <td>{{ $t->tanggal_jual->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->sales->nama ?? '-' }}</td>
                    <td style="font-weight:600;">{{ $t->pelanggan->nama ?? '-' }}</td>
                    <td class="text-center"><span class="badge {{ $t->tipe_bayar }}">{{ strtoupper($t->tipe_bayar) }}</span></td>
                    <td class="text-right mono">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">Total</td>
                    <td class="text-right mono">Rp {{ number_format($topTransactions->sum('total'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- Setoran & Hutang Summary --}}
    <div class="cols-2" style="margin-top:4px;">
        <div class="section">
            <div class="section-title">Ringkasan Setoran</div>
            @forelse($setoranSummary as $ss)
            @php
                $bg = match($ss->status) { 'terverifikasi'=>'#dcfce7;color:#166534', 'pending'=>'#fef3c7;color:#92400e', 'ditolak'=>'#fef2f2;color:#991b1b', default=>'#f1f5f9;color:#475569' };
                $label = match($ss->status) { 'terverifikasi'=>'Terverifikasi', 'pending'=>'Pending', 'ditolak'=>'Ditolak', default=>ucfirst($ss->status) };
            @endphp
            <div class="box">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <span class="badge" style="background:{{ $bg }};">{{ $label }}</span>
                        <span style="font-size:8pt;margin-left:6px;">{{ $ss->jumlah }} setoran</span>
                    </div>
                    <div class="mono" style="font-size:11pt;font-weight:700;">Rp {{ number_format($ss->total_setor, 0, ',', '.') }}</div>
                </div>
            </div>
            @empty
            <div style="font-size:8pt;color:#94a3b8;text-align:center;padding:16px;">Tidak ada data setoran</div>
            @endforelse
        </div>

        <div class="section">
            <div class="section-title">Ringkasan Hutang</div>
            <div class="box" style="background:#fef2f2;border-color:#fecaca;">
                <div class="box-title" style="color:#991b1b;">Total Piutang Aktif</div>
                <div class="big-val mono" style="color:#dc2626;">Rp {{ number_format($hutangSummary['total_hutang'], 0, ',', '.') }}</div>
            </div>
            <div class="box">
                <div class="box-title">Pelanggan dengan Hutang</div>
                <div class="big-val">{{ $hutangSummary['jumlah_pelanggan'] }}</div>
                <div class="sub-val">pelanggan</div>
            </div>
            <div class="box">
                <div class="box-title">Hutang Baru (Periode Ini)</div>
                <div class="big-val mono" style="color:#1e40af;">Rp {{ number_format($hutangSummary['hutang_baru'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Footer / Signature --}}
    <div class="footer">
        <div class="note">Laporan ini dicetak secara otomatis dari sistem DOD POS dan merupakan data yang sah. Dokumen ini tidak memerlukan tanda tangan basah.</div>
        <div class="sign">
            <div class="sign-line"></div>
            <div class="sign-name">Supervisor</div>
            <div class="sign-title">Penanggung Jawab</div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() { window.print(); });
    </script>
</body>
</html>
