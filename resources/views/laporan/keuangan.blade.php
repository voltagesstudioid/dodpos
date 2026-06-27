<x-app-layout>
<x-slot name="header">Laporan Keuangan</x-slot>

<style>
/* ── LAYOUT ──────────────────────────────────────────── */
.keu-wrap {
    width: 100%;
    max-width: 1600px;
    padding: 1.5rem;
    animation: fadeSlideIn .3s ease both;
}

/* ── PAGE HEADER ────────────────────────────────────── */
.keu-hd {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 1.25rem; margin-bottom: 1.5rem; flex-wrap: wrap;
}
.keu-hd-l { display: flex; gap: .875rem; align-items: center; }
.keu-hd-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: linear-gradient(135deg, #059669, #10b981);
    display: flex; align-items: center; justify-content: center;
    color: #fff; flex-shrink: 0;
    box-shadow: 0 6px 18px rgba(5,150,105,.28);
}
.keu-hd-title {
    font-size: 1.5rem; font-weight: 800; color: #0f172a;
    letter-spacing: -.03em; line-height: 1.15; margin: 0;
}
.keu-hd-sub { font-size: .8125rem; color: #64748b; margin: .15rem 0 0; }
.keu-hd-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }

/* ── ACTION BUTTONS ─────────────────────────────────── */
.keu-btn {
    display: inline-flex; align-items: center; gap: .375rem;
    padding: .45rem .85rem; border-radius: 8px;
    font-size: .75rem; font-weight: 700; cursor: pointer;
    text-decoration: none; border: 1px solid; transition: all .18s;
    font-family: inherit; white-space: nowrap; line-height: 1.5;
}
.keu-btn:active { transform: scale(.97); }
.keu-btn-ghost   { background: #fff; border-color: #e2e8f0; color: #475569; }
.keu-btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
.keu-btn-primary {
    background: #059669; border-color: transparent; color: #fff;
    box-shadow: 0 3px 10px rgba(5,150,105,.25);
}
.keu-btn-primary:hover { background: #047857; transform: translateY(-1px); box-shadow: 0 5px 16px rgba(5,150,105,.35); }
.keu-btn-success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
.keu-btn-success:hover { background: #d1fae5; }
.keu-btn-danger  { background: #fff1f2; border-color: #fecdd3; color: #be123c; }
.keu-btn-danger:hover { background: #ffe4e6; border-color: #fda4af; }

/* ── DATE FILTER PANEL ───────────────────────────────── */
.keu-filter {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
    display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;
}
.keu-fg {
    display: flex; flex-direction: column; gap: .25rem;
}
.keu-fg label {
    font-size: .65rem; font-weight: 800; color: #475569;
    text-transform: uppercase; letter-spacing: .06em;
}
.keu-fi {
    padding: .5rem .75rem; border-radius: 8px; border: 1.5px solid #e2e8f0;
    background: #fff; color: #1e293b; font-size: .8125rem;
    font-family: inherit; outline: none; transition: all .2s;
    width: 175px;
}
.keu-fi:focus { border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,.1); }

/* ── KPI GRID ────────────────────────────────────────── */
.keu-kpi {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1280px) { .keu-kpi { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 720px)  { .keu-kpi { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .keu-kpi { grid-template-columns: 1fr; } }

.keu-kpi-card {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; padding: 1.125rem 1.25rem;
    display: flex; flex-direction: column; gap: .5rem;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
    transition: all .25s; position: relative; overflow: hidden;
}
.keu-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg, 0 10px 30px -3px rgba(0,0,0,.1));
}
.keu-kpi-top {
    display: flex; align-items: center; justify-content: space-between;
}
.keu-kpi-ico {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.keu-kpi-badge {
    font-size: .6rem; font-weight: 800; padding: .125rem .5rem;
    border-radius: 5px; letter-spacing: .02em;
}
.keu-kpi-label {
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #94a3b8;
}
.keu-kpi-val {
    font-size: 1.375rem; font-weight: 900; line-height: 1;
    letter-spacing: -.03em;
    animation: countUp .4s ease both;
}
.keu-kpi-foot {
    font-size: .675rem; color: #94a3b8;
    display: flex; align-items: center; gap: .3rem;
}

/* KPI color variants */
.kpi-blue  .keu-kpi-ico { background: #eef2ff; color: #4f46e5; }
.kpi-blue  .keu-kpi-val { color: #4f46e5; }
.kpi-blue  .keu-kpi-badge { background: #eef2ff; color: #4338ca; }

.kpi-rose  .keu-kpi-ico { background: #fff1f2; color: #e11d48; }
.kpi-rose  .keu-kpi-val { color: #e11d48; }
.kpi-rose  .keu-kpi-badge { background: #fee2e2; color: #be123c; }

.kpi-green .keu-kpi-ico { background: #ecfdf5; color: #059669; }
.kpi-green .keu-kpi-val { color: #059669; }
.kpi-green .keu-kpi-badge { background: #ecfdf5; color: #047857; }

.kpi-amber .keu-kpi-ico { background: #fffbeb; color: #d97706; }
.kpi-amber .keu-kpi-val { color: #d97706; }
.kpi-amber .keu-kpi-badge { background: #fef3c7; color: #92400e; }

.kpi-purple .keu-kpi-ico { background: #faf5ff; color: #7c3aed; }
.kpi-purple .keu-kpi-val { color: #7c3aed; }
.kpi-purple .keu-kpi-badge { background: #f3e8ff; color: #6d28d9; }

/* Profit-specific border top */
.keu-border-green { border-top: 4px solid #22c55e; }
.keu-border-red   { border-top: 4px solid #ef4444; }

/* ── CHART CARD ──────────────────────────────────────── */
.keu-chart {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
}
.keu-chart-hd {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 1rem;
}
.keu-chart-title {
    font-size: .9375rem; font-weight: 700; color: #0f172a; margin: 0;
}
.keu-chart-period {
    font-size: .6875rem; color: #64748b;
    background: #f1f5f9; padding: .2rem .65rem;
    border-radius: 99px; font-weight: 600;
}
.keu-chart-body { position: relative; height: 320px; }

/* ── TABLE CARD ──────────────────────────────────────── */
.keu-table-card {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; overflow: hidden;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
}
.keu-table-hd {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; justify-content: space-between; align-items: center;
}
.keu-table-title {
    font-size: .875rem; font-weight: 700; color: #0f172a; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.keu-table-count {
    font-size: .6875rem; color: #94a3b8;
}

/* Profit text helpers */
.keu-text-green { color: #15803d; }
.keu-text-red   { color: #dc2626; }

/* ── PRINT ──────────────────────────────────────────── */
@media print {
    .keu-hd-actions, .keu-filter, .stk-sidebar { display: none !important; }
    .keu-chart { break-inside: avoid; }
    .keu-kpi-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
    body { background: #fff !important; }
}

@keyframes countUp { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

/* ── RESPONSIVE ──────────────────────────────────────── */
@media (max-width: 640px) {
    .keu-wrap { padding: .75rem; }
    .keu-hd-title { font-size: 1.25rem; }
    .keu-hd-icon { width: 40px; height: 40px; }
    .keu-fi { width: 100%; }
    .keu-filter { flex-direction: column; align-items: stretch; }
    .keu-filter .keu-fg { width: 100%; }
}
</style>

<div class="keu-wrap">

    @php
        $isPrint = (bool) ($isPrint ?? request()->boolean('print'));
        $profitPositive = ($netProfit ?? 0) >= 0;
        $periodLabel = \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') . ' — ' . \Carbon\Carbon::parse($dateTo)->format('d/m/Y');
    @endphp

    {{-- PRINT PREVIEW TOOLBAR --}}
    @if($isPrint && request()->boolean('preview'))
        @include('print.partials.preview-toolbar', ['title' => 'Laporan Keuangan'])
    @endif

    {{-- PRINT HEADER --}}
    @if($isPrint)
        <div style="margin-bottom:1rem; border-bottom:2px solid #e2e8f0; padding-bottom:.75rem;">
            <div style="font-size:1.25rem; font-weight:900; color:#0f172a;">Laporan Keuangan</div>
            <div style="font-size:.75rem; color:#475569; margin-top:.25rem;">
                Periode: <strong>{{ $periodLabel }}</strong>
                &bull; Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
            </div>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="keu-hd">
        <div class="keu-hd-l">
            <div class="keu-hd-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div>
                <h1 class="keu-hd-title">Laporan Keuangan</h1>
                <p class="keu-hd-sub">Ringkasan pendapatan, HPP, pengeluaran, dan laba dalam periode tertentu.</p>
            </div>
        </div>

        @if(!$isPrint)
            <div class="keu-hd-actions">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv', 'page' => null]) }}" class="keu-btn keu-btn-ghost">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    CSV
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="keu-btn keu-btn-success">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Excel
                </a>
                <a href="{{ request()->fullUrlWithQuery(['print' => 1, 'preview' => 1, 'page' => null]) }}" target="_blank" class="keu-btn keu-btn-ghost">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak
                </a>
            </div>
        @endif
    </div>

    {{-- DATE FILTER --}}
    @if(!$isPrint)
        <form action="{{ route('laporan.keuangan') }}" method="GET" class="keu-filter">
            @if($isPrint)
                <input type="hidden" name="print" value="1">
            @endif
            @if(request()->has('preview'))
                <input type="hidden" name="preview" value="1">
            @endif
            <div class="keu-fg">
                <label for="date-from">Dari Tanggal</label>
                <input type="date" name="date_from" id="date-from" value="{{ $dateFrom }}" class="keu-fi">
            </div>
            <div class="keu-fg">
                <label for="date-to">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date-to" value="{{ $dateTo }}" class="keu-fi">
            </div>
            <button type="submit" class="keu-btn keu-btn-primary" style="height:36px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Tampilkan
            </button>
        </form>
    @endif

    {{-- KPI CARDS (5 cards) --}}
    <div class="keu-kpi">
        {{-- Total Pendapatan --}}
        <div class="keu-kpi-card kpi-blue" style="animation-delay:0s">
            <div class="keu-kpi-top">
                <div class="keu-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <span class="keu-kpi-badge">Revenue</span>
            </div>
            <div class="keu-kpi-label">Total Pendapatan</div>
            <div class="keu-kpi-val">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
            <div class="keu-kpi-foot">Dari transaksi POS selesai</div>
        </div>

        {{-- HPP --}}
        <div class="keu-kpi-card kpi-rose" style="animation-delay:.04s">
            <div class="keu-kpi-top">
                <div class="keu-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <span class="keu-kpi-badge">COGS</span>
            </div>
            <div class="keu-kpi-label">Harga Pokok Penjualan</div>
            <div class="keu-kpi-val">Rp {{ number_format($totalHPP ?? 0, 0, ',', '.') }}</div>
            <div class="keu-kpi-foot">Biaya perolehan produk terjual</div>
        </div>

        {{-- Laba Kotor --}}
        <div class="keu-kpi-card kpi-green" style="animation-delay:.08s">
            <div class="keu-kpi-top">
                <div class="keu-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                    </svg>
                </div>
                <span class="keu-kpi-badge">Gross</span>
            </div>
            <div class="keu-kpi-label">Laba Kotor</div>
            <div class="keu-kpi-val">Rp {{ number_format($labaKotor ?? 0, 0, ',', '.') }}</div>
            <div class="keu-kpi-foot">Pendapatan &minus; HPP</div>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="keu-kpi-card kpi-amber" style="animation-delay:.12s">
            <div class="keu-kpi-top">
                <div class="keu-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path d="M12 8v8"/><path d="M8 12h8"/>
                    </svg>
                </div>
                <span class="keu-kpi-badge">Outflow</span>
            </div>
            <div class="keu-kpi-label">Total Pengeluaran</div>
            <div class="keu-kpi-val">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</div>
            <div class="keu-kpi-foot">PO diterima + Biaya Operasional</div>
        </div>

        {{-- Laba Bersih --}}
        <div class="keu-kpi-card kpi-purple {{ $profitPositive ? 'keu-border-green' : 'keu-border-red' }}" style="animation-delay:.16s">
            <div class="keu-kpi-top">
                <div class="keu-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
                    </svg>
                </div>
                <span class="keu-kpi-badge" style="background:{{ $profitPositive ? '#dcfce7' : '#fee2e2' }};color:{{ $profitPositive ? '#15803d' : '#991b1b' }};">
                    {{ $profitPositive ? 'Untung' : 'Rugi' }}
                </span>
            </div>
            <div class="keu-kpi-label">Laba Bersih</div>
            <div class="keu-kpi-val {{ $profitPositive ? 'keu-text-green' : 'keu-text-red' }}">
                Rp {{ number_format(abs($netProfit ?? 0), 0, ',', '.') }}
            </div>
            <div class="keu-kpi-foot">
                {{ $profitPositive ? 'Untung periode ini' : 'Rugi periode ini' }}
            </div>
        </div>
    </div>

    {{-- DETAIL ROW: HPP & breakdown --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:1rem; margin-bottom:1.5rem; font-size:.8rem;">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow-sm);">
            <span style="color:#64748b;font-weight:600;">Biaya Operasional</span>
            <span style="font-weight:800;color:#475569;">Rp {{ number_format($totalOperasional ?? 0, 0, ',', '.') }}</span>
        </div>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow-sm);">
            <span style="color:#64748b;font-weight:600;">PO Diterima</span>
            <span style="font-weight:800;color:#475569;">Rp {{ number_format($totalPoReceived ?? 0, 0, ',', '.') }}</span>
        </div>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow-sm);">
            <span style="color:#64748b;font-weight:600;">Margin Kotor</span>
            <span style="font-weight:800;{{ $labaKotor >= 0 ? 'color:#059669;' : 'color:#dc2626;' }}">
                @php $marginPct = $totalRevenue > 0 ? round($labaKotor / $totalRevenue * 100, 1) : 0; @endphp
                {{ $marginPct }}%
            </span>
        </div>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow-sm);">
            <span style="color:#64748b;font-weight:600;">Margin Bersih</span>
            <span style="font-weight:800;{{ $profitPositive ? 'color:#059669;' : 'color:#dc2626;' }}">
                @php $netMarginPct = $totalRevenue > 0 ? round($netProfit / $totalRevenue * 100, 1) : 0; @endphp
                {{ $profitPositive ? '+' : '' }}{{ $netMarginPct }}%
            </span>
        </div>
    </div>

    {{-- CHART --}}
    @if(!$isPrint)
        <div class="keu-chart">
            <div class="keu-chart-hd">
                <h3 class="keu-chart-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" style="vertical-align:middle;margin-right:.375rem;"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                    Tren Keuangan Harian
                </h3>
                <span class="keu-chart-period">{{ $periodLabel }}</span>
            </div>
            <div class="keu-chart-body">
                <canvas id="keuanganChart"></canvas>
            </div>
        </div>
    @endif

    {{-- DETAIL TABLE --}}
    <div class="keu-table-card">
        <div class="keu-table-hd">
            <h3 class="keu-table-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Rincian Per Hari
            </h3>
            <span class="keu-table-count">{{ $dates->count() }} hari ditampilkan</span>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th style="text-align:right;">Pendapatan (Rp)</th>
                        <th style="text-align:right;">Pengeluaran (Rp)</th>
                        <th style="text-align:right;">Laba Bersih (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dates->reverse() as $row)
                        @php $s = (float) ($row['profit'] ?? 0); @endphp
                        <tr>
                            <td style="font-weight:600; color:#334155;">{{ $row['date'] }}</td>
                            <td style="text-align:right; color:#4f46e5; font-weight:600;">{{ number_format($row['revenue'] ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right; color:#dc2626; font-weight:600;">{{ number_format($row['expense'] ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right; font-weight:700; {{ $s >= 0 ? 'color:#15803d;' : 'color:#dc2626;' }}">
                                {{ $s < 0 ? '−' : '+' }}{{ number_format(abs($s), 0, ',', '.') }}
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
                    <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
                        <td style="font-weight:800; color:#1e293b; padding:.875rem 1rem; text-transform:uppercase; letter-spacing:.04em; font-size:.75rem;">Grand Total</td>
                        <td style="text-align:right; font-weight:800; color:#4f46e5; padding:.875rem 1rem;">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align:right; font-weight:800; color:#dc2626; padding:.875rem 1rem;">{{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align:right; font-weight:800; padding:.875rem 1rem; {{ $profitPositive ? 'color:#15803d;' : 'color:#dc2626;' }}">
                            {{ $netProfit < 0 ? '−' : '+' }}{{ number_format(abs($netProfit ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>

{{-- CHART JS --}}
@if(!$isPrint)
    <script type="application/json" id="keuangan-chart-data">{!! json_encode($dates->values(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('keuanganChart');
        if (!ctx) return;
        const rawEl = document.getElementById('keuangan-chart-data');
        if (!rawEl) return;
        try {
            const raw = JSON.parse(rawEl.textContent || '[]');
            const data = [...raw].reverse();

            const labels   = data.map(d => d.date);
            const revenues = data.map(d => d.revenue);
            const expenses = data.map(d => d.expense);
            const profits  = data.map(d => d.profit);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Pendapatan',
                            data: revenues,
                            backgroundColor: 'rgba(79,70,229,0.7)',
                            borderColor: '#4f46e5',
                            borderRadius: 4,
                            order: 2
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenses,
                            backgroundColor: 'rgba(239,68,68,0.7)',
                            borderColor: '#ef4444',
                            borderRadius: 4,
                            order: 2
                        },
                        {
                            label: 'Laba Bersih',
                            data: profits,
                            type: 'line',
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5,150,105,0.07)',
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#059669',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, padding: 16, font: { size: 11 } } },
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
                            ticks: {
                                font: { size: 10 },
                                callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 9 }, maxRotation: 45 }
                        }
                    }
                }
            });
        } catch (e) { console.error('Chart error:', e); }
    });
    </script>
@endif

{{-- PRINT TRIGGER --}}
@if($isPrint && !request()->boolean('preview'))
    <script>window.addEventListener('load', function(){ window.print(); });</script>
@endif

</x-app-layout>