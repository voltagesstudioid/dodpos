<x-app-layout>
    <x-slot name="header">Laporan Keuangan</x-slot>

    <div class="page-container">

        <!-- Page Header -->
        <div style="margin-bottom:1.5rem;">
            <h1 style="font-size:1.5rem; font-weight:800; color:#1e293b; margin:0 0 0.25rem; display:flex; align-items:center; gap:0.5rem;">
                💰 Laporan Keuangan
            </h1>
            <p style="color:#64748b; font-size:0.875rem; margin:0;">Ringkasan pendapatan, pengeluaran, dan laba bersih dalam periode tertentu.</p>
        </div>

        <!-- Filter Section -->
        <div class="card" style="padding:1.25rem 1.5rem; margin-bottom:1.5rem;">
            <form action="{{ route('laporan.keuangan') }}" method="GET">
                <div style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap;">
                    <div style="display:flex; flex-direction:column; gap:0.35rem;">
                        <label class="form-label" style="font-size:0.8rem;">📅 Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-input" style="width:175px;">
                    </div>
                    <div style="display:flex; flex-direction:column; gap:0.35rem;">
                        <label class="form-label" style="font-size:0.8rem;">📅 Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-input" style="width:175px;">
                    </div>
                    <button type="submit" class="btn-primary" style="height:38px;">🔍 Tampilkan</button>
                </div>
            </form>
        </div>

        @php $profitPositive = $netProfit >= 0; @endphp
        <style>
            .keu-profit-pos { border-top: 4px solid #22c55e; }
            .keu-profit-neg { border-top: 4px solid #ef4444; }
            .keu-text-profit-pos { color: #15803d; }
            .keu-text-profit-neg { color: #dc2626; }
        </style>

        <!-- Summary Cards -->
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1.25rem; margin-bottom:1.5rem;">

            <div class="card" style="padding:1.5rem; border-top:4px solid #3b82f6;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">📈 Total Pendapatan</div>
                <div style="font-size:1.75rem; font-weight:900; color:#1d4ed8; line-height:1;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Dari penjualan / Sales Order</div>
            </div>

            <div class="card" style="padding:1.5rem; border-top:4px solid #ef4444;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">📉 Total Pengeluaran</div>
                <div style="font-size:1.75rem; font-weight:900; color:#dc2626; line-height:1;">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">Dari Purchase Order (PO)</div>
            </div>

            <div class="card {{ $profitPositive ? 'keu-profit-pos' : 'keu-profit-neg' }}" style="padding:1.5rem;">
                <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">
                    {{ $profitPositive ? '✅' : '⚠️' }} Laba Bersih
                </div>
                <div class="{{ $profitPositive ? 'keu-text-profit-pos' : 'keu-text-profit-neg' }}" style="font-size:1.75rem; font-weight:900; line-height:1;">
                    {{ $netProfit < 0 ? '-' : '' }}Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                </div>
                <div style="font-size:0.75rem; color:#64748b; margin-top:0.5rem;">
                    {{ $profitPositive ? 'Untung periode ini' : 'Rugi periode ini' }}
                </div>
            </div>

        </div>

        <!-- Chart -->
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                <h3 style="font-size:1rem; font-weight:700; color:#1e293b; margin:0;">📊 Grafik Tren Keuangan Harian</h3>
                <span style="font-size:0.75rem; color:#64748b; background:#f1f5f9; padding:0.25rem 0.75rem; border-radius:99px;">{{ $dateFrom }} — {{ $dateTo }}</span>
            </div>
            <div style="position:relative; height:320px;">
                <canvas id="keuanganChart"></canvas>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="card">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="font-size:0.95rem; font-weight:700; color:#1e293b; margin:0;">📋 Rincian Per Hari</h3>
                <span style="font-size:0.75rem; color:#94a3b8;">{{ $dates->count() }} hari ditampilkan</span>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th style="text-align:right;">Pendapatan (Rp)</th>
                            <th style="text-align:right;">Pengeluaran (Rp)</th>
                            <th style="text-align:right;">Selisih / Laba (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dates->reverse() as $row)
                            @php $selisih = $row['revenue'] - $row['expense']; @endphp
                            <tr>
                                <td style="font-weight:600; color:#334155;">{{ $row['date'] }}</td>
                                <td style="text-align:right; color:#1d4ed8; font-weight:600;">{{ number_format($row['revenue'], 0, ',', '.') }}</td>
                                <td style="text-align:right; color:#dc2626; font-weight:600;">{{ number_format($row['expense'], 0, ',', '.') }}</td>
                                <td class="{{ $selisih >= 0 ? 'keu-text-profit-pos' : 'keu-text-profit-neg' }}" style="text-align:right; font-weight:700;">
                                    {{ $selisih < 0 ? '-' : '+' }} {{ number_format(abs($selisih), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:3rem; color:#94a3b8;">
                                    Tidak ada data untuk rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($dates->count() > 0)
                    <tfoot>
                        <tr style="background:#f8fafc;">
                            <td style="font-weight:800; color:#1e293b; padding:1rem 1.25rem; font-size:0.825rem; text-transform:uppercase; letter-spacing:0.04em;">Grand Total</td>
                            <td style="text-align:right; font-weight:800; color:#1d4ed8; padding:1rem 1.25rem; border-top:2px solid #e2e8f0;">{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                            <td style="text-align:right; font-weight:800; color:#dc2626; padding:1rem 1.25rem; border-top:2px solid #e2e8f0;">{{ number_format($totalPembelian, 0, ',', '.') }}</td>
                            <td class="{{ $profitPositive ? 'keu-text-profit-pos' : 'keu-text-profit-neg' }}" style="text-align:right; font-weight:800; padding:1rem 1.25rem; border-top:2px solid #e2e8f0;">
                                {{ $netProfit < 0 ? '-' : '+' }} {{ number_format(abs($netProfit), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>

    <script type="application/json" id="keuangan-chart-data">{!! json_encode($dates->values(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('keuanganChart').getContext('2d');
            const rawEl = document.getElementById('keuangan-chart-data');
            const raw = rawEl ? JSON.parse(rawEl.textContent || '[]') : [];
            const data = [...raw].reverse();

            const labels   = data.map(d => d.date);
            const revenues = data.map(d => d.revenue);
            const expenses = data.map(d => d.expense);
            const profits  = data.map(d => d.revenue - d.expense);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Pendapatan',
                            data: revenues,
                            backgroundColor: 'rgba(59,130,246,0.75)',
                            borderColor: '#3b82f6',
                            borderRadius: 5,
                            order: 2
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenses,
                            backgroundColor: 'rgba(239,68,68,0.75)',
                            borderColor: '#ef4444',
                            borderRadius: 5,
                            order: 2
                        },
                        {
                            label: 'Laba Bersih',
                            data: profits,
                            type: 'line',
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34,197,94,0.08)',
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#22c55e',
                            pointRadius: 5,
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, padding: 20 } },
                        tooltip: {
                            callbacks: {
                                label: c => c.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y)
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
