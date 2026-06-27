<x-app-layout>
<x-slot name="header">Laporan Stok Global</x-slot>

<style>
/* ── LAYOUT ──────────────────────────────────────────── */
.stk-wrap {
    width: 100%;
    max-width: 1700px;
    padding: 1.5rem;
    animation: fadeSlideIn .3s ease both;
}

/* ── PAGE HEADER ────────────────────────────────────── */
.stk-hd {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 1.25rem; margin-bottom: 1.5rem; flex-wrap: wrap;
}
.stk-hd-l { display: flex; gap: .875rem; align-items: center; }
.stk-hd-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    display: flex; align-items: center; justify-content: center;
    color: #fff; flex-shrink: 0;
    box-shadow: 0 6px 18px rgba(79,70,229,.28);
}
.stk-hd-title {
    font-size: 1.5rem; font-weight: 800; color: #0f172a;
    letter-spacing: -.03em; line-height: 1.15; margin: 0;
}
.stk-hd-sub { font-size: .8125rem; color: #64748b; margin: .15rem 0 0; }
.stk-hd-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; }

/* ── ACTION BUTTONS ─────────────────────────────────── */
.stk-btn {
    display: inline-flex; align-items: center; gap: .375rem;
    padding: .45rem .85rem; border-radius: 8px;
    font-size: .75rem; font-weight: 700; cursor: pointer;
    text-decoration: none; border: 1px solid; transition: all .18s;
    font-family: inherit; white-space: nowrap;
    line-height: 1.5;
}
.stk-btn:active { transform: scale(.97); }
.stk-btn-ghost   { background: #fff; border-color: #e2e8f0; color: #475569; }
.stk-btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
.stk-btn-warn    { background: #fffbeb; border-color: #fde68a; color: #92400e; }
.stk-btn-warn:hover { background: #fef3c7; border-color: #fbbf24; }
.stk-btn-danger  { background: #fff1f2; border-color: #fecdd3; color: #be123c; }
.stk-btn-danger:hover { background: #ffe4e6; border-color: #fda4af; }
.stk-btn-primary {
    background: #4f46e5; border-color: transparent; color: #fff;
    box-shadow: 0 3px 10px rgba(79,70,229,.25);
}
.stk-btn-primary:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 5px 16px rgba(79,70,229,.35); }
.stk-btn-success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
.stk-btn-success:hover { background: #d1fae5; }

/* Badge on buttons */
.stk-btn-badge {
    background: var(--c, #f59e0b); color: #fff;
    padding: .05rem .4rem; border-radius: 99px; font-size: .625rem;
    font-weight: 800; line-height: 1.4;
}

/* ── KPI CARDS ──────────────────────────────────────── */
.stk-kpi {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1280px) { .stk-kpi { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 720px)  { .stk-kpi { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .stk-kpi { grid-template-columns: 1fr; } }

.stk-kpi-card {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; padding: 1.125rem 1.25rem;
    display: flex; flex-direction: column; gap: .5rem;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
    transition: all .25s; position: relative; overflow: hidden;
}
.stk-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg, 0 10px 30px -3px rgba(0,0,0,.1));
}
.stk-kpi-top {
    display: flex; align-items: center; justify-content: space-between;
}
.stk-kpi-ico {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stk-kpi-badge {
    font-size: .625rem; font-weight: 800; padding: .125rem .5rem;
    border-radius: 6px; letter-spacing: .02em;
}
.stk-kpi-label {
    font-size: .675rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #94a3b8;
}
.stk-kpi-val {
    font-size: 1.875rem; font-weight: 900; line-height: 1;
    letter-spacing: -.04em;
    animation: countUp .4s ease both;
}
.stk-kpi-foot {
    font-size: .7rem; color: #94a3b8;
    display: flex; align-items: center; gap: .3rem;
}

/* KPI color variants */
.kpi-brand .stk-kpi-ico { background: #eef2ff; color: #4f46e5; }
.kpi-brand .stk-kpi-val { color: #4f46e5; }
.kpi-brand .stk-kpi-badge { background: #eef2ff; color: #4338ca; }

.kpi-green .stk-kpi-ico { background: #ecfdf5; color: #059669; }
.kpi-green .stk-kpi-val { color: #059669; }
.kpi-green .stk-kpi-badge { background: #ecfdf5; color: #047857; }

.kpi-amber .stk-kpi-ico { background: #fffbeb; color: #d97706; }
.kpi-amber .stk-kpi-val { color: #d97706; }
.kpi-amber .stk-kpi-badge { background: #fef3c7; color: #92400e; }

.kpi-rose .stk-kpi-ico { background: #fff1f2; color: #e11d48; }
.kpi-rose .stk-kpi-val { color: #e11d48; }
.kpi-rose .stk-kpi-badge { background: #ffe4e6; color: #be123c; }

.kpi-orange .stk-kpi-ico { background: #fff7ed; color: #ea580c; }
.kpi-orange .stk-kpi-val { color: #ea580c; }
.kpi-orange .stk-kpi-badge { background: #ffedd5; color: #9a3412; }

/* ── TWO-COLUMN LAYOUT ──────────────────────────────── */
.stk-body {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.25rem;
    align-items: flex-start;
}
@media (max-width: 1100px) {
    .stk-body { grid-template-columns: 1fr; }
}

/* ── PANEL (card container) ─────────────────────────── */
.stk-panel {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
    overflow: hidden;
}
.stk-panel-hd {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fcfcfd;
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; flex-wrap: wrap;
}
.stk-panel-title {
    font-size: .875rem; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: .5rem;
}
.stk-panel-meta { font-size: .6875rem; color: #94a3b8; margin-top: 2px; }

/* ── FILTER BAR ─────────────────────────────────────── */
.stk-filters {
    padding: .875rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbfc;
    display: flex; gap: .75rem; flex-wrap: wrap; align-items: flex-end;
}
.stk-fg {
    display: flex; flex-direction: column; gap: .25rem;
    flex: 1; min-width: 180px;
}
.stk-fg label {
    font-size: .65rem; font-weight: 800; color: #475569;
    text-transform: uppercase; letter-spacing: .06em;
}
.stk-fi-wrap { position: relative; }
.stk-fi-wrap svg {
    position: absolute; left: .75rem; top: 50%;
    transform: translateY(-50%); color: #94a3b8; pointer-events: none;
}
.stk-fi {
    width: 100%; padding: .5rem .75rem .5rem 2.2rem;
    border-radius: 8px; border: 1.5px solid #e2e8f0;
    background: #fff; color: #1e293b; font-size: .8125rem;
    font-family: inherit; outline: none; transition: all .2s;
}
.stk-fs {
    width: 100%; padding: .5rem .75rem;
    border-radius: 8px; border: 1.5px solid #e2e8f0;
    background: #fff; color: #1e293b; font-size: .8125rem;
    font-family: inherit; outline: none; transition: all .2s; cursor: pointer;
    appearance: auto;
}
.stk-fi:focus, .stk-fs:focus {
    border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.stk-fa { display: flex; gap: .5rem; align-items: flex-end; padding-bottom: 1px; }

/* ── TABLE ──────────────────────────────────────────── */
.stk-tbl-wrap { overflow-x: auto; }
.stk-tbl {
    width: 100%; border-collapse: collapse; min-width: 760px;
}
.stk-tbl th {
    padding: .65rem 1rem;
    font-size: .65rem; font-weight: 800; color: #64748b;
    text-transform: uppercase; letter-spacing: .06em;
    background: #f8fafc;
    border-bottom: 2px solid #e8edf4;
    text-align: left; white-space: nowrap;
}
.stk-tbl th.c { text-align: center; }
.stk-tbl td {
    padding: .8rem 1rem;
    font-size: .8125rem; color: #374151;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}
.stk-tbl td.c { text-align: center; }
.stk-tbl tr:last-child td { border-bottom: none; }
.stk-tbl tbody tr { transition: background .1s; }
.stk-tbl tbody tr:hover td { background: #fafbff; }

.stk-prod-name { font-weight: 700; color: #0f172a; font-size: .875rem; }
.stk-prod-sku  { font-size: .6875rem; color: #94a3b8; margin-top: 1px; }
.stk-prod-cat {
    display: inline-block; margin-top: .375rem;
    padding: .15rem .55rem; background: #eef2ff; color: #4338ca;
    font-size: .65rem; font-weight: 700; border-radius: 5px;
}

.stk-wh-grid { display: flex; flex-direction: column; gap: .3rem; }
.stk-wh-cell {
    display: flex; justify-content: space-between; align-items: center;
    padding: .3rem .55rem; border-radius: 6px;
    background: #f8fafc; border: 1px solid #e8edf4;
    font-size: .75rem;
}
.stk-wh-cell-l { display: flex; align-items: center; gap: .3rem; color: #64748b; font-weight: 600; }
.stk-wh-cell-r { font-weight: 700; color: #4f46e5; }

.stk-qty {
    text-align: center;
}
.stk-qty-num {
    font-size: 1.25rem; font-weight: 900; line-height: 1;
    letter-spacing: -.03em;
}
.stk-qty-num.ok  { color: #059669; }
.stk-qty-num.low { color: #d97706; }
.stk-qty-num.out { color: #e11d48; }
.stk-qty-unit { font-size: .675rem; color: #94a3b8; font-weight: 600; margin-top: 2px; }

.stk-bar {
    width: 56px; height: 4px; border-radius: 99px;
    background: #e2e8f0; margin: .3rem auto 0; overflow: hidden;
}
.stk-bar-fill { height: 100%; border-radius: 99px; transition: width .4s; }

.stk-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: .25rem .65rem; border-radius: 99px;
    font-size: .6875rem; font-weight: 800; letter-spacing: .03em;
    white-space: nowrap;
}
.stk-badge-ok     { background: #dcfce7; color: #15803d; }
.stk-badge-warn   { background: #fef3c7; color: #92400e; }
.stk-badge-danger { background: #fee2e2; color: #991b1b; }

.stk-min-val {
    font-weight: 700; color: #475569;
}
.stk-min-na {
    font-weight: 600; color: #cbd5e1;
}

/* ── SIDEBAR ────────────────────────────────────────── */
.stk-side {
    display: flex; flex-direction: column; gap: 1rem;
    position: sticky; top: 1.5rem;
}
@media (max-width: 1100px) {
    .stk-side { position: static; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
}

.stk-widget {
    background: #fff; border-radius: var(--radius, 12px);
    border: 1px solid #e2e8f0; overflow: hidden;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
}
.stk-widget-hd {
    padding: .75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: .8125rem; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: .5rem;
    background: #fcfcfd;
}
.stk-widget-bd { display: flex; flex-direction: column; }
.stk-wi {
    display: flex; justify-content: space-between; align-items: center;
    padding: .75rem 1rem; border-bottom: 1px solid #f4f7fc;
    transition: background .12s; gap: .5rem;
}
.stk-wi:last-child { border-bottom: none; }
.stk-wi:hover { background: #fafbff; }
.stk-wi-l {
    flex: 1; min-width: 0;
}
.stk-wi-l h4 {
    font-size: .8125rem; font-weight: 700; color: #0f172a;
    margin: 0 0 .1rem;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.stk-wi-l p  { font-size: .675rem; color: #64748b; margin: 0; }
.stk-wi-r {
    font-weight: 800; font-size: .8125rem; flex-shrink: 0;
    margin-left: auto;
}

.stk-wbar { margin-top: .3rem; }
.stk-wbar-track { height: 3px; border-radius: 99px; background: #e2e8f0; overflow: hidden; }
.stk-wbar-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #4f46e5, #7c3aed); }

/* Alert widget */
.stk-widget--warn { border-color: #fde68a; }
.stk-widget--warn .stk-widget-hd {
    background: linear-gradient(135deg, #fef9e7, #fef3c7);
    border-color: #fde68a; color: #92400e;
}
.stk-widget--warn .stk-wi:last-child { border-bottom-color: #fde68a; }

.stk-widget-link {
    display: block; text-align: center; padding: .65rem;
    font-size: .7rem; font-weight: 700; text-decoration: none;
    background: #fef9e7; color: #92400e; border-top: 1px solid #fde68a;
    transition: background .18s;
}
.stk-widget-link:hover { background: #fef3c7; }

/* Alert count badge */
.stk-alert-count {
    margin-left: auto; background: #f59e0b; color: #fff;
    padding: .05rem .45rem; border-radius: 99px;
    font-size: .625rem; font-weight: 800; line-height: 1.5;
}

/* Movement type colors */
.stk-move-in  { color: #059669; }
.stk-move-out { color: #e11d48; }

/* ── EMPTY STATE ────────────────────────────────────── */
.stk-empty {
    text-align: center; padding: 3rem 1.5rem;
}
.stk-empty-ico {
    width: 56px; height: 56px; border-radius: 16px;
    background: #f1f5f9;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; color: #94a3b8;
}
.stk-empty h3 { font-size: .9375rem; font-weight: 700; color: #1e293b; margin: 0 0 .3rem; }
.stk-empty p  { font-size: .8125rem; color: #64748b; margin: 0; }

/* ── PAGINATION ─────────────────────────────────────── */
.stk-pag {
    padding: .875rem 1.25rem;
    border-top: 1px solid #f1f5f9;
    display: flex; justify-content: center;
}

/* ── PRINT ──────────────────────────────────────────── */
@media print {
    .stk-side, .stk-hd-actions, .stk-filters, .stk-pag { display: none !important; }
    .stk-body { grid-template-columns: 1fr !important; }
    .stk-panel { box-shadow: none !important; border: none !important; }
    body { background: #fff !important; }
    .stk-wrap { padding: 0 !important; }
}

@keyframes countUp { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

/* ── RESPONSIVE TUNING ──────────────────────────────── */
@media (max-width: 640px) {
    .stk-wrap { padding: .75rem; }
    .stk-hd-title { font-size: 1.25rem; }
    .stk-hd-icon { width: 40px; height: 40px; }
    .stk-hd-actions .stk-btn { font-size: .7rem; padding: .35rem .65rem; }
    .stk-fg { min-width: 140px; }
}
</style>

<div class="stk-wrap">

    @php
        $isPrint    = (bool) ($isPrint ?? request()->boolean('print'));
        $printTitle = 'Laporan Stok Global';
    @endphp

    {{-- PRINT HEADER --}}
    @if($isPrint)
    <div style="margin-bottom:1.25rem; border-bottom:2px solid #e2e8f0; padding-bottom:.75rem;">
        <div style="font-size:1.25rem; font-weight:900; color:#0f172a;">{{ $printTitle }}</div>
        <div style="font-size:.75rem; color:#64748b; margin-top:.25rem;">
            @if($search) Pencarian: <strong>{{ $search }}</strong> &bull; @endif
            @if($warehouseId) Gudang: <strong>{{ optional($warehouses->firstWhere('id', (int)$warehouseId))->name ?? $warehouseId }}</strong> &bull; @endif
            @if($categoryId) Kategori: <strong>{{ optional($categories->firstWhere('id', (int)$categoryId))->name ?? $categoryId }}</strong> &bull; @endif
            Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
        </div>
    </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="stk-hd">
        <div class="stk-hd-l">
            <div class="stk-hd-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>
            <div>
                <h1 class="stk-hd-title">{{ $printTitle }}</h1>
                <p class="stk-hd-sub">Ringkasan kondisi dan sebaran stok seluruh produk di semua gudang.</p>
            </div>
        </div>

        <div class="stk-hd-actions">
            <a href="{{ route('gudang.stok') }}" class="stk-btn stk-btn-ghost">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Rekap Per Gudang
            </a>
            <a href="{{ route('gudang.minstok') }}" class="stk-btn stk-btn-warn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Min. Stok
                @if(($lowStockCount ?? 0) > 0)
                    <span class="stk-btn-badge" style="--c:#f59e0b">{{ $lowStockCount }}</span>
                @endif
            </a>
            <a href="{{ route('gudang.expired') }}" class="stk-btn stk-btn-danger">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Expired
                @if(($expiredCount ?? 0) > 0)
                    <span class="stk-btn-badge" style="--c:#e11d48">{{ $expiredCount }}</span>
                @endif
            </a>

            @if(!$isPrint)
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv', 'page' => null]) }}" class="stk-btn stk-btn-ghost">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    CSV
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="stk-btn stk-btn-success">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Excel
                </a>
                <a href="{{ request()->fullUrlWithQuery(['print' => 1, 'preview' => 1, 'page' => null]) }}" target="_blank" class="stk-btn stk-btn-ghost">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak
                </a>
            @endif
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="stk-kpi">
        <div class="stk-kpi-card kpi-brand" style="animation-delay:0s">
            <div class="stk-kpi-top">
                <div class="stk-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="M8 4v4M16 4v4M2 12h20"/>
                    </svg>
                </div>
                <span class="stk-kpi-badge">Produk</span>
            </div>
            <div class="stk-kpi-label">Total Produk Terdaftar</div>
            <div class="stk-kpi-val">{{ number_format($totalProducts ?? 0) }}</div>
            <div class="stk-kpi-foot">Semua kategori</div>
        </div>

        <div class="stk-kpi-card kpi-green" style="animation-delay:.04s">
            <div class="stk-kpi-top">
                <div class="stk-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
                <span class="stk-kpi-badge">Qty</span>
            </div>
            <div class="stk-kpi-label">Total Qty Fisik</div>
            <div class="stk-kpi-val">{{ number_format($totalStockQty ?? 0) }}</div>
            <div class="stk-kpi-foot">Seluruh gudang aktif</div>
        </div>

        <div class="stk-kpi-card kpi-amber" style="animation-delay:.08s">
            <div class="stk-kpi-top">
                <div class="stk-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                @if(($lowStockCount ?? 0) > 0)
                    <span class="stk-kpi-badge">Perlu Restok</span>
                @endif
            </div>
            <div class="stk-kpi-label">Stok Hampir Habis</div>
            <div class="stk-kpi-val">{{ number_format($lowStockCount ?? 0) }}</div>
            <div class="stk-kpi-foot">Di bawah minimum limit</div>
        </div>

        <div class="stk-kpi-card kpi-rose" style="animation-delay:.12s">
            <div class="stk-kpi-top">
                <div class="stk-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                @if(($expiredCount ?? 0) > 0)
                    <span class="stk-kpi-badge" style="background:#ffe4e6;color:#be123c;">Kritis</span>
                @endif
            </div>
            <div class="stk-kpi-label">Batch Kadaluarsa</div>
            <div class="stk-kpi-val">{{ number_format($expiredCount ?? 0) }}</div>
            <div class="stk-kpi-foot">Sudah melewati tanggal</div>
        </div>

        <div class="stk-kpi-card kpi-orange" style="animation-delay:.16s">
            <div class="stk-kpi-top">
                <div class="stk-kpi-ico">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                @if(($nearExpiredCount ?? 0) > 0)
                    <span class="stk-kpi-badge" style="background:#ffedd5;color:#9a3412;">30 Hari</span>
                @endif
            </div>
            <div class="stk-kpi-label">Akan Expired (30hr)</div>
            <div class="stk-kpi-val">{{ number_format($nearExpiredCount ?? 0) }}</div>
            <div class="stk-kpi-foot">Mendekati tanggal kadaluarsa</div>
        </div>
    </div>

    {{-- MAIN BODY --}}
    <div class="stk-body">

        {{-- LEFT: TABLE --}}
        <div class="stk-panel">
            <div class="stk-panel-hd">
                <div>
                    <div class="stk-panel-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                        </svg>
                        Daftar Produk &amp; Sebaran Stok
                    </div>
                    <div class="stk-panel-meta">
                        {{ $products->total() }} produk ditemukan
                        @if($search) &bull; Pencarian: "{{ $search }}" @endif
                        @if($warehouseId) &bull; Gudang terfilter @endif
                        @if($categoryId) &bull; Kategori terfilter @endif
                    </div>
                </div>
                @if($search || $categoryId || $warehouseId)
                    <a href="{{ route('laporan.stok') }}" class="stk-btn stk-btn-ghost" style="font-size:.6875rem;padding:.35rem .65rem;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Reset Filter
                    </a>
                @endif
            </div>

            {{-- FILTERS --}}
            <div class="stk-filters">
                <form method="GET" action="{{ route('laporan.stok') }}" style="display:contents;">
                    @if($isPrint)
                        <input type="hidden" name="print" value="1">
                    @endif
                    @if(request()->has('preview'))
                        <input type="hidden" name="preview" value="1">
                    @endif
                    <div class="stk-fg">
                        <label for="search-input">Cari Produk</label>
                        <div class="stk-fi-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}" class="stk-fi" id="search-input" placeholder="Nama atau SKU produk...">
                        </div>
                    </div>
                    <div class="stk-fg">
                        <label for="category-filter">Kategori</label>
                        <select name="category_id" class="stk-fs" id="category-filter">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(($categoryId ?? '') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="stk-fg">
                        <label for="warehouse-filter">Gudang</label>
                        <select name="warehouse_id" class="stk-fs" id="warehouse-filter">
                            <option value="">Semua Gudang</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" @selected(($warehouseId ?? '') == $wh->id)>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="stk-fa">
                        <button type="submit" class="stk-btn stk-btn-primary" id="filter-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="stk-tbl-wrap">
                <table class="stk-tbl">
                    <thead>
                        <tr>
                            <th style="width:34%">Detail Produk</th>
                            <th style="width:30%">Sebaran Gudang</th>
                            <th class="c" style="width:14%">Total Global</th>
                            <th class="c" style="width:10%">Min. Limit</th>
                            <th class="c" style="width:12%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $p)
                            @php
                                $stock   = $maskStock ? 0 : $p->stock;
                                $minStok = $maskStock ? 0 : ($p->min_stock ?? 0);
                                $isLow   = !$maskStock && $minStok > 0 && $stock <= $minStok;
                                $isOut   = $stock <= 0;
                                $perGudang = $warehouseStocks->get($p->id, collect());

                                // Health bar: % terhadap min_stok (capped 0-100)
                                $healthPct = $minStok > 0
                                    ? min(100, max(0, round($stock / $minStok * 100)))
                                    : 100;
                                $barColor  = $isOut ? '#e11d48' : ($isLow ? '#f59e0b' : '#059669');
                            @endphp
                            <tr>
                                <td>
                                    <div class="stk-prod-name">{{ $p->name }}</div>
                                    <div class="stk-prod-sku">SKU: {{ $p->sku ?? '—' }}</div>
                                    @if($p->category)
                                        <span class="stk-prod-cat">{{ $p->category->name }}</span>
                                    @else
                                        <span class="stk-prod-cat" style="background:#f1f5f9;color:#64748b;">Tanpa Kategori</span>
                                    @endif
                                </td>
                                <td>
                                    @if($perGudang->isEmpty())
                                        <span style="font-size:.75rem;color:#94a3b8;font-style:italic;">Tidak ada stok tercatat di gudang</span>
                                    @else
                                        <div class="stk-wh-grid">
                                            @foreach($perGudang as $wh)
                                                <div class="stk-wh-cell">
                                                    <span class="stk-wh-cell-l">
                                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                                        {{ $wh->warehouse->name ?? '—' }}
                                                    </span>
                                                    <span class="stk-wh-cell-r">{{ $maskStock ? '***' : number_format($wh->total_stock) }} {{ $p->unit?->abbreviation ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="c">
                                    <div class="stk-qty">
                                        <div class="stk-qty-num {{ $isOut ? 'out' : ($isLow ? 'low' : 'ok') }}">
                                            {{ $maskStock ? '***' : number_format($stock) }}
                                        </div>
                                        <div class="stk-qty-unit">{{ $p->unit?->abbreviation ?? '' }}</div>
                                        @if(!$maskStock && $minStok > 0)
                                            <div class="stk-bar">
                                                <div class="stk-bar-fill" style="width:{{ $healthPct }}%; background:{{ $barColor }};"></div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="c">
                                    <span class="{{ $maskStock || !$minStok ? 'stk-min-na' : 'stk-min-val' }}">
                                        {{ $maskStock ? '***' : ($minStok ?: '—') }}
                                    </span>
                                </td>
                                <td class="c">
                                    @if($maskStock)
                                        <span class="stk-badge stk-badge-ok">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                            AMAN
                                        </span>
                                    @elseif($isOut)
                                        <span class="stk-badge stk-badge-danger">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            HABIS
                                        </span>
                                    @elseif($isLow)
                                        <span class="stk-badge stk-badge-warn">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/></svg>
                                            MENIPIS
                                        </span>
                                    @else
                                        <span class="stk-badge stk-badge-ok">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                            AMAN
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="stk-empty">
                                        <div class="stk-empty-ico">
                                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                        </div>
                                        <h3>Tidak ada produk ditemukan</h3>
                                        <p>Coba sesuaikan filter pencarian, kategori, atau gudang.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($products->hasPages())
                <div class="stk-pag">{{ $products->links() }}</div>
            @endif
        </div>

        {{-- RIGHT: SIDEBAR --}}
        <div class="stk-side">

            {{-- Total Stok per Gudang --}}
            <div class="stk-widget">
                <div class="stk-widget-hd">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Total Stok per Gudang
                </div>
                <div class="stk-widget-bd">
                    @php $maxWhQty = $warehouses->max('total_qty') ?: 1; @endphp
                    @forelse($warehouses as $wh)
                        @php
                            $whQty   = $wh->total_qty ?? 0;
                            $pct     = $maxWhQty > 0 ? round($whQty / $maxWhQty * 100) : 0;
                            $whQtyDisplay = $maskStock ? '***' : number_format($whQty);
                        @endphp
                        <div class="stk-wi">
                            <div class="stk-wi-l">
                                <h4>{{ $wh->name }}</h4>
                                <p>{{ number_format($wh->stock_lines ?? 0) }} record tersimpan</p>
                                <div class="stk-wbar">
                                    <div class="stk-wbar-track">
                                        <div class="stk-wbar-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="stk-wi-r" style="color:#4f46e5;">{{ $whQtyDisplay }}</div>
                        </div>
                    @empty
                        <div class="stk-wi"><p style="color:#94a3b8; font-style:italic; margin:0; font-size:.75rem;">Belum ada gudang terdaftar</p></div>
                    @endforelse
                </div>
            </div>

            {{-- Stok Menipis Alert --}}
            @if(($lowStockProducts ?? collect())->isNotEmpty())
                <div class="stk-widget stk-widget--warn">
                    <div class="stk-widget-hd">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Stok Menipis
                        <span class="stk-alert-count">{{ $lowStockProducts->count() }}</span>
                    </div>
                    <div class="stk-widget-bd">
                        @foreach($lowStockProducts as $prod)
                            @php
                                $pctLeft = !$maskStock && ($prod->min_stock ?? 0) > 0
                                    ? max(0, min(100, round($prod->stock / $prod->min_stock * 100)))
                                    : 0;
                            @endphp
                            <div class="stk-wi">
                                <div class="stk-wi-l">
                                    <h4>{{ $prod->name }}</h4>
                                    <p>Min: {{ $maskStock ? '***' : number_format($prod->min_stock) }} &bull; Sisa: {{ $maskStock ? '***' : number_format($prod->stock) }}</p>
                                    @if(!$maskStock)
                                        <div class="stk-wbar">
                                            <div class="stk-wbar-track">
                                                <div class="stk-wbar-fill" style="width:{{ $pctLeft }}%; background:linear-gradient(90deg, #f59e0b, #ef4444);"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="stk-wi-r">
                                    <span class="stk-badge stk-badge-warn" style="font-size:.625rem;">{{ $maskStock ? '***' : $prod->stock }} sisa</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('gudang.minstok') }}" class="stk-widget-link">Lihat Semua Alert &rarr;</a>
                </div>
            @endif

            {{-- Aktivitas Terbaru --}}
            <div class="stk-widget">
                <div class="stk-widget-hd">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Aktivitas Stok Terbaru
                </div>
                <div class="stk-widget-bd">
                    @forelse($recentMovements ?? [] as $move)
                        <div class="stk-wi">
                            <div class="stk-wi-l">
                                <h4>{{ $move->product?->name ?? '—' }}</h4>
                                <p>{{ $move->warehouse?->name ?? '—' }} &bull; {{ $move->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="stk-wi-r" style="font-family:monospace;">
                                @if($move->type === 'in')
                                    <span class="stk-move-in">+{{ (int)$move->quantity }}</span>
                                @else
                                    <span class="stk-move-out">-{{ (int)$move->quantity }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="stk-wi"><p style="color:#94a3b8;font-style:italic;margin:0;font-size:.75rem;">Belum ada aktivitas stok</p></div>
                    @endforelse
                </div>
            </div>

        </div>{{-- /sidebar --}}
    </div>{{-- /body --}}
</div>{{-- /wrap --}}

@if($isPrint && !request()->boolean('preview'))
    <script>window.addEventListener('load', function(){ window.print(); });</script>
@endif

</x-app-layout>