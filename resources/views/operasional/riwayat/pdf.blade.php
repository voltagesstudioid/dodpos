<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Operasional</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 12mm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #0f172a;
        }
        .header h1 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 4px 0;
            color: #0f172a;
        }
        .header .sub {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }
        .header .info {
            font-size: 10px;
            color: #64748b;
            margin-top: 6px;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 10px 14px;
            background: #f8fafc;
            border-radius: 6px;
            font-size: 10px;
        }
        .meta-left {
            text-align: left;
        }
        .meta-right {
            text-align: right;
        }
        .meta strong {
            color: #0f172a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table thead th {
            background: #1e293b;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 8px 10px;
            text-align: left;
            border: none;
        }
        table thead th.r {
            text-align: right;
        }
        table thead th.c {
            text-align: center;
        }
        table tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5px;
        }
        table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        table tbody td.r {
            text-align: right;
            font-family: 'DejaVu Sans Mono', monospace;
            font-weight: 600;
        }
        table tbody td.c {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            padding: 8px 0;
            border-top: 1px solid #e2e8f0;
        }
        .footer .page-number:before {
            content: "Halaman " counter(page);
        }
        .totals {
            margin-top: 8px;
            display: flex;
            justify-content: flex-end;
        }
        .totals table {
            width: auto;
            min-width: 280px;
            border-collapse: collapse;
        }
        .totals table td {
            padding: 5px 12px;
            font-size: 10px;
            border: none;
        }
        .totals table td.label {
            font-weight: 600;
            color: #64748b;
            text-align: right;
        }
        .totals table td.value {
            font-weight: 800;
            text-align: right;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .totals table tr.total-row td {
            border-top: 2px solid #0f172a;
            padding-top: 6px;
            font-size: 11px;
        }
        .totals table tr.total-row td.value {
            color: #dc2626;
        }
        .badge-vehicle {
            display: inline-block;
            padding: 1px 6px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $storeName }}</h1>
        <div class="sub">LAPORAN RIWAYAT PENGELUARAN OPERASIONAL</div>
        <div class="info">
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            @if($categoryName) · Kategori: {{ $categoryName }} @endif
        </div>
    </div>

    <div class="meta">
        <div class="meta-left">
            <strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }}<br>
            <strong>Total Transaksi:</strong> {{ $totalRecords }} data
        </div>
        <div class="meta-right">
            <strong>Total Pengeluaran:</strong> Rp {{ number_format($totalAmount, 0, ',', '.') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:12%;">Tanggal</th>
                <th style="width:16%;">Kategori</th>
                <th style="width:30%;">Keterangan</th>
                <th style="width:12%;">Unit</th>
                <th class="r" style="width:18%;">Nominal (Rp)</th>
                <th class="c" style="width:12%;">PIC</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $e)
            <tr>
                <td>{{ \Carbon\Carbon::parse($e->date)->format('d/m/Y') }}</td>
                <td>{{ $e->category?->name ?? '-' }}</td>
                <td>{{ $e->notes ?? '-' }}</td>
                <td>
                    @if($e->vehicle)
                        <span class="badge-vehicle">{{ strtoupper($e->vehicle->license_plate) }}</span>
                    @else
                        —
                    @endif
                </td>
                <td class="r">(Rp {{ number_format($e->amount, 0, ',', '.') }})</td>
                <td class="c">{{ $e->user?->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:30px;color:#94a3b8;">
                    Tidak ada data transaksi pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td class="label">Total Pengeluaran</td>
                <td class="value" style="color:#dc2626;">(Rp {{ number_format($totalAmount, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td class="label">Jumlah Transaksi</td>
                <td class="value">{{ $totalRecords }} data</td>
            </tr>
            <tr>
                <td class="label">Rata-rata per Transaksi</td>
                <td class="value">Rp {{ number_format($totalRecords > 0 ? intdiv($totalAmount, $totalRecords) : 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <span class="page-number"></span>
    </div>
</body>
</html>
