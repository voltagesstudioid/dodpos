<x-app-layout>
<x-slot name="header">Laporan Stok Global</x-slot>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap');

    .stok-wrap {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        width: 100%;
        padding: 1.75rem 2rem;
        max-width: 1700px;
        animation: fadeSlideIn .35s ease both;
    }

    /* ── PAGE HEADER ─────────────────────────────────────── */
    .stk-ph {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.25rem;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
    }
    .stk-ph-left { display: flex; gap: 1rem; align-items: center; }
    .stk-ph-icon {
        width: 52px; height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        display: flex; align-items: center; justify-content: center;
        color: white; flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(79,70,229,.3);
    }
    .stk-ph-title {
        font-size: 1.625rem; font-weight: 900;
        color: #0f172a; letter-spacing: -.035em; margin: 0 0 .2rem;
        line-height: 1.1;
    }
    .stk-ph-sub { font-size: .85rem; color: #64748b; margin: 0; }
    .stk-ph-actions { display: flex; gap: .625rem; flex-wrap: wrap; align-items: center; }

    .stk-btn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem .9rem; border-radius: 10px;
        font-size: .8rem; font-weight: 700; cursor: pointer;
        text-decoration: none; border: 1.5px solid; transition: all .2s;
        font-family: inherit; white-space: nowrap;
    }
    .stk-btn-ghost { background: white; border-color: #e2e8f0; color: #475569; }
    .stk-btn-ghost:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .stk-btn-warn { background: #fffbeb; border-color: #fde68a; color: #92400e; }
    .stk-btn-warn:hover { background: #fef3c7; border-color: #fbbf24; }
    .stk-btn-danger { background: #fff1f2; border-color: #fecdd3; color: #be123c; }
    .stk-btn-danger:hover { background: #ffe4e6; border-color: #fda4af; }
    .stk-btn-primary {
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        border-color: transparent; color: white;
        box-shadow: 0 4px 12px rgba(79,70,229,.25);
    }
    .stk-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.35); }
    .stk-btn-success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
    .stk-btn-success:hover { background: #d1fae5; }

    /* ── KPI GRID ─────────────────────────────────────────── */
    .stk-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 1300px) { .stk-kpi-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 780px)  { .stk-kpi-grid { grid-template-columns: repeat(2, 1fr); } }

    .stk-kpi {
        background: #fff; border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.375rem;
        display: flex; flex-direction: column; gap: .625rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        transition: all .25s; position: relative; overflow: hidden;
        cursor: default;
    }
    .stk-kpi::after {
        content: ''; position: absolute;
        top: -20px; right: -20px;
        width: 80px; height: 80px; border-radius: 50%;
        opacity: .06; transition: all .3s;
    }
    .stk-kpi:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,.09); }
    .stk-kpi:hover::after { transform: scale(1.4); opacity: .1; }
    .stk-kpi-row { display: flex; align-items: center; justify-content: space-between; }
    .stk-kpi-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .stk-kpi-label {
        font-size: .68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #94a3b8; margin-bottom: .2rem;
    }
    .stk-kpi-value {
        font-size: 2rem; font-weight: 900; line-height: 1;
        letter-spacing: -.04em;
        animation: countUp .45s ease both;
    }
    .stk-kpi-footer { font-size: .72rem; color: #94a3b8; display: flex; align-items: center; gap: .3rem; }

    .kpi-indigo .stk-kpi-icon { background: linear-gradient(135deg, #eef2ff, #e0e7ff); color: #4f46e5; }
    .kpi-indigo .stk-kpi-value { color: #4f46e5; }
    .kpi-indigo::after { background: #4f46e5; }

    .kpi-emerald .stk-kpi-icon { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #059669; }
    .kpi-emerald .stk-kpi-value { color: #059669; }
    .kpi-emerald::after { background: #059669; }

    .kpi-amber .stk-kpi-icon { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #d97706; }
    .kpi-amber .stk-kpi-value { color: #d97706; }
    .kpi-amber::after { background: #f59e0b; }

    .kpi-rose .stk-kpi-icon { background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #e11d48; }
    .kpi-rose .stk-kpi-value { color: #e11d48; }
    .kpi-rose::after { background: #e11d48; }

    .kpi-orange .stk-kpi-icon { background: linear-gradient(135deg, #fff7ed, #ffedd5); color: #ea580c; }
    .kpi-orange .stk-kpi-value { color: #ea580c; }
    .kpi-orange::after { background: #f97316; }

    /* ── MAIN LAYOUT ──────────────────────────────────────── */
    .stk-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 1.25rem;
        align-items: flex-start;
    }
    @media (max-width: 1100px) { .stk-layout { grid-template-columns: 1fr; } }

    /* ── MAIN PANEL ───────────────────────────────────────── */
    .stk-panel {
        background: white; border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 12px rgba(0,0,0,.04);
        overflow: hidden;
    }
    .stk-panel-header {
        padding: 1.125rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fdfdfe, #f9fafc);
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap;
    }
    .stk-panel-title {
        font-size: .9375rem; font-weight: 800; color: #0f172a;
        display: flex; align-items: center; gap: .5rem;
    }
    .stk-panel-meta { font-size: .72rem; color: #94a3b8; margin-top: 2px; }

    /* ── FILTER BAR ───────────────────────────────────────── */
    .stk-filter-bar {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex; gap: .75rem; flex-wrap: wrap; align-items: flex-end;
    }
    .stk-fg {
        display: flex; flex-direction: column; gap: .3rem;
        flex: 1; min-width: 200px;
    }
    .stk-fg label {
        font-size: .68rem; font-weight: 800; color: #475569;
        text-transform: uppercase; letter-spacing: .06em;
    }
    .stk-input-wrap { position: relative; }
    .stk-input-wrap svg {
        position: absolute; left: .8rem; top: 50%;
        transform: translateY(-50%); color: #94a3b8; pointer-events: none;
    }
    .stk-fi {
        width: 100%; padding: .575rem .875rem;
        padding-left: 2.4rem;
        border-radius: 9px; border: 1.5px solid #e2e8f0;
        background: white; color: #1e293b; font-size: .875rem;
        font-family: inherit; outline: none; transition: all .2s;
    }
    .stk-fs {
        width: 100%; padding: .575rem .875rem;
        border-radius: 9px; border: 1.5px solid #e2e8f0;
        background: white; color: #1e293b; font-size: .875rem;
        font-family: inherit; outline: none; transition: all .2s; cursor: pointer;
    }
    .stk-fi:focus, .stk-fs:focus {
        border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1);
        background: white;
    }
    .stk-filter-actions { display: flex; gap: .5rem; align-items: flex-end; padding-bottom: 1px; }

    /* ── TABLE ────────────────────────────────────────────── */
    .stk-table-wrap { overflow-x: auto; }
    .stk-table {
        width: 100%; border-collapse: collapse; min-width: 760px;
    }
    .stk-table thead th {
        padding: .75rem 1.25rem;
        font-size: .68rem; font-weight: 800; color: #64748b;
        text-transform: uppercase; letter-spacing: .06em;
        background: linear-gradient(180deg, #f8fafc, #f4f8fc);
        border-bottom: 2px solid #e8edf4;
        text-align: left; white-space: nowrap;
    }
    .stk-table thead th.center { text-align: center; }
    .stk-table tbody tr {
        border-bottom: 1px solid #f4f7fc;
        transition: background .14s;
    }
    .stk-table tbody tr:last-child { border-bottom: none; }
    .stk-table tbody tr:hover td { background: linear-gradient(90deg, #fafbff, #f8f9ff); }
    .stk-table td {
        padding: .9rem 1.25rem;
        font-size: .845rem; color: #374151;
        vertical-align: middle;
    }
    .stk-table td.center { text-align: center; }

    .stk-prod-name { font-weight: 800; color: #0f172a; margin-bottom: .15rem; font-size: .9rem; }
    .stk-prod-sku { font-size: .72rem; color: #94a3b8; }
    .stk-prod-cat {
        display: inline-block; margin-top: .375rem;
        padding: .2rem .6rem; background: #eef2ff; color: #4338ca;
        font-size: .68rem; font-weight: 700; border-radius: 6px;
    }

    /* Warehouse chips */
    .stk-wh-list { display: flex; flex-direction: column; gap: .4rem; }
    .stk-wh-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: .35rem .65rem; border-radius: 8px;
        background: #f8fafc; border: 1px solid #e8edf4;
        font-size: .78rem;
    }
    .stk-wh-name { display: flex; align-items: center; gap: .35rem; color: #64748b; font-weight: 600; }
    .stk-wh-qty { font-weight: 800; color: #4f46e5; font-size: .82rem; }

    /* Stock qty + bar */
    .stk-qty-wrap { text-align: center; }
    .stk-qty-num {
        font-size: 1.4rem; font-weight: 900; line-height: 1;
        letter-spacing: -.03em; margin-bottom: .15rem;
    }
    .stk-qty-num.ok   { color: #059669; }
    .stk-qty-num.low  { color: #d97706; }
    .stk-qty-num.out  { color: #e11d48; }
    .stk-qty-unit { font-size: .7rem; color: #94a3b8; font-weight: 600; }

    /* Health bar under qty */
    .stk-hbar {
        width: 64px; height: 4px; border-radius: 99px;
        background: #e2e8f0; margin: .3rem auto 0; overflow: hidden;
    }
    .stk-hbar-fill { height: 100%; border-radius: 99px; transition: width .4s; }

    /* Status badges */
    .stk-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .3rem .75rem; border-radius: 99px;
        font-size: .72rem; font-weight: 800; letter-spacing: .04em;
    }
    .stk-badge-ok    { background: #dcfce7; color: #15803d; }
    .stk-badge-warn  { background: #fef3c7; color: #92400e; }
    .stk-badge-danger{ background: #fee2e2; color: #991b1b; }

    /* ── SIDEBAR ──────────────────────────────────────────── */
    .stk-sidebar {
        display: flex; flex-direction: column; gap: 1.125rem;
        position: sticky; top: 1.5rem;
    }
    @media (max-width: 1100px) {
        .stk-sidebar { position: static; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
    }

    .stk-widget {
        background: white; border-radius: 18px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }
    .stk-widget-hd {
        padding: .875rem 1.125rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: .875rem; font-weight: 800; color: #0f172a;
        display: flex; align-items: center; gap: .5rem;
        background: linear-gradient(180deg, #fdfdfe, #f9fafc);
    }
    .stk-widget-body { display: flex; flex-direction: column; }
    .stk-w-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: .875rem 1.125rem; border-bottom: 1px solid #f4f7fc;
        transition: background .14s;
    }
    .stk-w-item:last-child { border-bottom: none; }
    .stk-w-item:hover { background: #fafbff; }
    .stk-w-item-left h4 { font-size: .85rem; font-weight: 700; color: #0f172a; margin: 0 0 .15rem; }
    .stk-w-item-left p  { font-size: .7rem; color: #64748b; margin: 0; }
    .stk-w-item-right { font-weight: 800; font-size: .875rem; }

    /* Warehouse stock bar */
    .stk-wbar { margin-top: .375rem; }
    .stk-wbar-track { height: 3px; border-radius: 99px; background: #e2e8f0; overflow: hidden; margin-top: .25rem; }
    .stk-wbar-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #4f46e5, #7c3aed); }

    /* Widget alert */
    .stk-widget-warn { border-color: #fde68a; }
    .stk-widget-warn .stk-widget-hd { background: linear-gradient(135deg, #fef9e7, #fef3c7); border-color: #fde68a; color: #92400e; }

    .stk-widget-link {
        display: block; text-align: center; padding: .75rem;
        font-size: .75rem; font-weight: 700; text-decoration: none;
        background: #fef9e7; color: #92400e; border-top: 1px solid #fde68a;
        transition: background .2s;
    }
    .stk-widget-link:hover { background: #fef3c7; }

    /* ── EMPTY STATE ─────────────────────────────────────── */
    .stk-empty {
        text-align: center; padding: 3.5rem 1.5rem;
    }
    .stk-empty-icon {
        width: 64px; height: 64px; border-radius: 18px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; color: #94a3b8;
    }
    .stk-empty h3 { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 .375rem; }
    .stk-empty p  { font-size: .845rem; color: #64748b; margin: 0; }

    /* ── PAGINATION ─────────────────────────────────────── */
    .stk-pag { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; }

    /* ── PRINT ─────────────────────────────────────────── */
    @media print {
        .stk-sidebar, .stk-ph-actions, .stk-filter-bar, .stk-pag { display: none !important; }
        .stk-layout { grid-template-columns: 1fr !important; }
        .stk-panel { box-shadow: none !important; border: none !important; }
        body { background: #fff !important; }
    }

    @keyframes countUp { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
    @keyframes fadeSlideIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
</style>

<div class="stok-wrap">

    @php
        $isPrint = (bool) ($isPrint ?? request()->boolean('print'));
        $totalQtyFormatted = number_format($totalStockQty ?? 0);
    @endphp

    {{-- PRINT HEADER --}}
    @if($isPrint)
        <div style="margin-bottom:1.5rem; border-bottom:2px solid #e2e8f0; padding-bottom:.75rem;">
            <div style="font-size:1.375rem; font-weight:900; color:#0f172a;">📦 Laporan Stok Global</div>
            <div style="font-size:.8rem; color:#64748b; margin-top:.25rem;">
                @if($search ?? null) Pencarian: <strong>{{ $search }}</strong> &bull; @endif
                @if($warehouseId ?? null) Gudang: <strong>{{ optional($warehouses->firstWhere('id', (int)$warehouseId))->name ?? $warehouseId }}</strong> &bull; @endif
                @if($categoryId ?? null) Kategori: <strong>{{ optional($categories->firstWhere('id', (int)$categoryId))->name ?? $categoryId }}</strong> &bull; @endif
                Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
            </div>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="stk-ph">
        <div class="stk-ph-left">
            <div class="stk-ph-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
            </div>
            <div>
                <h1 class="stk-ph-title">Laporan Stok Global</h1>
                <p class="stk-ph-sub">Ringkasan kondisi dan sebaran stok seluruh produk di semua gudang.</p>
            </div>
        </div>

        <div class="stk-ph-actions">
            <a href="{{ route('gudang.stok') }}" class="stk-btn stk-btn-ghost">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Rekap Per Gudang
            </a>
            <a href="{{ route('gudang.minstok') }}" class="stk-btn stk-btn-warn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Min. Stok
                @if(($lowStockCount ?? 0) > 0)
                    <span style="background:#f59e0b;color:white;padding:.1rem .45rem;border-radius:99px;font-size:.65rem;">{{ $lowStockCount }}</span>
                @endif
            </a>
            <a href="{{ route('gudang.expired') }}" class="stk-btn stk-btn-danger">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Expired
                @if(($expiredCount ?? 0) > 0)
                    <span style="background:#e11d48;color:white;padding:.1rem .45rem;border-radius:99px;font-size:.65rem;">{{ $expiredCount }}</span>
                @endif
            </a>

            @if(!$isPrint)
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv', 'page' => null]) }}" class="stk-btn stk-btn-ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    CSV
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'xlsx', 'page' => null]) }}" class="stk-btn stk-btn-success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Excel
                </a>
                <a href="{{ request()->fullUrlWithQuery(['print' => 1, 'preview' => 1, 'page' => null]) }}" target="_blank" class="stk-btn stk-btn-ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak
                </a>
            @endif
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="stk-kpi-grid">
        {{-- Total Produk --}}
        <div class="stk-kpi kpi-indigo" style="animation-delay:.04s">
            <div class="stk-kpi-row">
                <div class="stk-kpi-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="M8 4v4M16 4v4M2 12h20"/>
                    </svg>
                </div>
                <div style="font-size:.7rem;font-weight:700;color:#c7d2fe;background:#eef2ff;padding:.15rem .5rem;border-radius:6px;">Produk</div>
            </div>
            <div>
                <div class="stk-kpi-label">Total Produk Terdaftar</div>
                <div class="stk-kpi-value">{{ number_format($totalProducts ?? 0) }}</div>
            </div>
            <div class="stk-kpi-footer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Semua kategori
            </div>
        </div>

        {{-- Total Stok Fisik --}}
        <div class="stk-kpi kpi-emerald" style="animation-delay:.08s">
            <div class="stk-kpi-row">
                <div class="stk-kpi-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
                <div style="font-size:.7rem;font-weight:700;color:#a7f3d0;background:#ecfdf5;padding:.15rem .5rem;border-radius:6px;">Qty</div>
            </div>
            <div>
                <div class="stk-kpi-label">Total Qty Fisik</div>
                <div class="stk-kpi-value">{{ $totalQtyFormatted }}</div>
            </div>
            <div class="stk-kpi-footer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Seluruh gudang aktif
            </div>
        </div>

        {{-- Hampir Habis --}}
        <div class="stk-kpi kpi-amber" style="animation-delay:.12s">
            <div class="stk-kpi-row">
                <div class="stk-kpi-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                @if(($lowStockCount ?? 0) > 0)
                    <div style="font-size:.65rem;font-weight:800;color:#92400e;background:#fef3c7;padding:.15rem .5rem;border-radius:6px;animation:pulse-dot 1.5s ease infinite;">⚠ PERLU RESTOK</div>
                @endif
            </div>
            <div>
                <div class="stk-kpi-label">Stok Hampir Habis</div>
                <div class="stk-kpi-value">{{ number_format($lowStockCount ?? 0) }}</div>
            </div>
            <div class="stk-kpi-footer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Di bawah minimum limit
            </div>
        </div>

        {{-- Kadaluarsa --}}
        <div class="stk-kpi kpi-rose" style="animation-delay:.16s">
            <div class="stk-kpi-row">
                <div class="stk-kpi-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                @if(($expiredCount ?? 0) > 0)
                    <div style="font-size:.65rem;font-weight:800;color:#991b1b;background:#fee2e2;padding:.15rem .5rem;border-radius:6px;">‼ KRITIS</div>
                @endif
            </div>
            <div>
                <div class="stk-kpi-label">Batch Kadaluarsa</div>
                <div class="stk-kpi-value">{{ number_format($expiredCount ?? 0) }}</div>
            </div>
            <div class="stk-kpi-footer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Sudah melewati tanggal
            </div>
        </div>

        {{-- Akan Expired --}}
        <div class="stk-kpi kpi-orange" style="animation-delay:.2s">
            <div class="stk-kpi-row">
                <div class="stk-kpi-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                @if(($nearExpiredCount ?? 0) > 0)
                    <div style="font-size:.65rem;font-weight:800;color:#9a3412;background:#ffedd5;padding:.15rem .5rem;border-radius:6px;">30 Hari</div>
                @endif
            </div>
            <div>
                <div class="stk-kpi-label">Akan Expired (30hr)</div>
                <div class="stk-kpi-value">{{ number_format($nearExpiredCount ?? 0) }}</div>
            </div>
            <div class="stk-kpi-footer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Mendekati tanggal kadaluarsa
            </div>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="stk-layout">

        {{-- LEFT: PRODUCT TABLE --}}
        <div class="stk-panel">
            {{-- Panel Header --}}
            <div class="stk-panel-header">
                <div>
                    <div class="stk-panel-title">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                        </svg>
                        Daftar Produk &amp; Sebaran Stok
                    </div>
                    <div class="stk-panel-meta">
                        {{ $products->total() }} produk ditemukan
                        @if($search ?? null) &bull; Pencarian: "{{ $search }}" @endif
                        @if($warehouseId ?? null) &bull; Gudang terfilter @endif
                        @if($categoryId ?? null) &bull; Kategori terfilter @endif
                    </div>
                </div>
                <div style="display:flex;gap:.5rem;align-items:center;">
                    @if($search || $categoryId || $warehouseId)
                        <a href="{{ route('laporan.stok') }}" class="stk-btn stk-btn-ghost" style="font-size:.75rem;padding:.4rem .7rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Reset Filter
                        </a>
                    @endif
                </div>
            </div>

            {{-- Filter --}}
            <div class="stk-filter-bar">
                <form method="GET" style="display:contents;">
                    <div class="stk-fg">
                        <label>Cari Produk</label>
                        <div class="stk-input-wrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ $search ?? '' }}" class="stk-fi" placeholder="Nama atau SKU produk..." id="search-input">
                        </div>
                    </div>
                    <div class="stk-fg">
                        <label>Kategori</label>
                        <select name="category_id" class="stk-fs" id="category-filter">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(($categoryId ?? '') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="stk-fg">
                        <label>Gudang</label>
                        <select name="warehouse_id" class="stk-fs" id="warehouse-filter">
                            <option value="">Semua Gudang</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" @selected(($warehouseId ?? '') == $wh->id)>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="stk-filter-actions">
                        <button type="submit" class="stk-btn stk-btn-primary" id="filter-btn">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="15" y2="15"/></svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="stk-table-wrap">
                <table class="stk-table">
                    <thead>
                        <tr>
                            <th style="width:35%">Detail Produk</th>
                            <th style="width:30%">Sebaran Gudang</th>
                            <th class="center" style="width:13%">Total Global</th>
                            <th class="center" style="width:10%">Min. Limit</th>
                            <th class="center" style="width:12%">Status Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $p)
                            @php
                                $isLow = ($p->min_stock ?? 0) > 0 && $p->stock <= $p->min_stock;
                                $isOut = $p->stock <= 0;
                                $perGudang = $warehouseStocks->get($p->id, collect());
                                // Health % for progress bar (capped at 100)
                                $healthPct = ($p->min_stock ?? 0) > 0
                                    ? min(100, round($p->stock / $p->min_stock * 100))
                                    : 100;
                                $barColor = $isOut ? '#e11d48' : ($isLow ? '#f59e0b' : '#059669');
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
                                        <span style="font-size:.78rem;color:#94a3b8;font-style:italic;">Tidak ada stok tercatat di gudang</span>
                                    @else
                                        <div class="stk-wh-list">
                                            @foreach($perGudang as $wh)
                                                <div class="stk-wh-item">
                                                    <span class="stk-wh-name">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                                        {{ $wh->warehouse->name ?? '—' }}
                                                    </span>
                                                    <span class="stk-wh-qty">{{ number_format($wh->total_stock) }} {{ $p->unit?->abbreviation ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="center">
                                    <div class="stk-qty-wrap">
                                        <div class="stk-qty-num {{ $isOut ? 'out' : ($isLow ? 'low' : 'ok') }}">
                                            {{ number_format($p->stock) }}
                                        </div>
                                        <div class="stk-qty-unit">{{ $p->unit?->abbreviation ?? '' }}</div>
                                        @if(($p->min_stock ?? 0) > 0)
                                            <div class="stk-hbar">
                                                <div class="stk-hbar-fill" style="width:{{ $healthPct }}%; background:{{ $barColor }};"></div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="center" style="font-weight:700; color:{{ ($p->min_stock ?? 0) > 0 ? '#475569' : '#cbd5e1' }}">
                                    {{ $p->min_stock ?? '—' }}
                                </td>
                                <td class="center">
                                    @if($isOut)
                                        <span class="stk-badge stk-badge-danger">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            HABIS
                                        </span>
                                    @elseif($isLow)
                                        <span class="stk-badge stk-badge-warn">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/></svg>
                                            MENIPIS
                                        </span>
                                    @else
                                        <span class="stk-badge stk-badge-ok">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                            AMAN
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="stk-empty">
                                        <div class="stk-empty-icon">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
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

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="stk-pag">{{ $products->links() }}</div>
            @endif
        </div>

        {{-- RIGHT: SIDEBAR --}}
        <div class="stk-sidebar">

            {{-- Total Stock per Gudang --}}
            <div class="stk-widget">
                <div class="stk-widget-hd">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Total Stok per Gudang
                </div>
                <div class="stk-widget-body">
                    @php
                        $maxWhQty = $warehouses->max('total_qty') ?: 1;
                    @endphp
                    @forelse($warehouses as $wh)
                        @php $pct = $maxWhQty > 0 ? round(($wh->total_qty ?? 0) / $maxWhQty * 100) : 0; @endphp
                        <div class="stk-w-item">
                            <div class="stk-w-item-left" style="flex:1; min-width:0;">
                                <h4>{{ $wh->name }}</h4>
                                <p>{{ number_format($wh->stock_lines ?? 0) }} record tersimpan</p>
                                <div class="stk-wbar">
                                    <div class="stk-wbar-track">
                                        <div class="stk-wbar-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="stk-w-item-right" style="color:#4f46e5; margin-left:.75rem; flex-shrink:0;">
                                {{ number_format($wh->total_qty ?? 0) }}
                            </div>
                        </div>
                    @empty
                        <div class="stk-w-item">
                            <p style="color:#94a3b8; font-style:italic; margin:0; font-size:.8rem;">Belum ada gudang terdaftar</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Stok Menipis Alert --}}
            @if(($lowStockProducts ?? collect())->count() > 0)
                <div class="stk-widget stk-widget-warn">
                    <div class="stk-widget-hd">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Stok Menipis
                        <span style="margin-left:auto;background:#f59e0b;color:white;padding:.1rem .5rem;border-radius:99px;font-size:.65rem;font-weight:800;">{{ $lowStockProducts->count() }}</span>
                    </div>
                    <div class="stk-widget-body">
                        @foreach($lowStockProducts as $prod)
                            @php
                                $pctLeft = ($prod->min_stock ?? 0) > 0
                                    ? max(0, min(100, round($prod->stock / $prod->min_stock * 100)))
                                    : 0;
                            @endphp
                            <div class="stk-w-item">
                                <div class="stk-w-item-left" style="flex:1; min-width:0;">
                                    <h4 style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">{{ $prod->name }}</h4>
                                    <p>Min: {{ number_format($prod->min_stock) }} &bull; Sisa: {{ number_format($prod->stock) }}</p>
                                    <div class="stk-wbar">
                                        <div class="stk-wbar-track">
                                            <div class="stk-wbar-fill" style="width:{{ $pctLeft }}%; background:linear-gradient(90deg, #f59e0b, #ef4444);"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="stk-w-item-right" style="margin-left:.625rem; flex-shrink:0;">
                                    <span class="stk-badge stk-badge-warn" style="font-size:.65rem;">{{ $prod->stock }} sisa</span>
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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Aktivitas Stok Terbaru
                </div>
                <div class="stk-widget-body">
                    @php
                        /** @var \App\Models\StockMovement[] $recentMovements */
                    @endphp
                    @forelse($recentMovements ?? [] as $move)
                        <div class="stk-w-item">
                            <div class="stk-w-item-left" style="flex:1; min-width:0;">
                                <h4 style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">
                                    {{ $move->product?->name ?? '—' }}
                                </h4>
                                <p>{{ $move->warehouse?->name ?? '—' }} &bull; {{ $move->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="stk-w-item-right" style="font-family:monospace;margin-left:.5rem;flex-shrink:0;">
                                @if($move->type === 'in')
                                    <span style="color:#059669;font-size:.9rem;">+{{ (int)$move->quantity }}</span>
                                @else
                                    <span style="color:#e11d48;font-size:.9rem;">-{{ (int)$move->quantity }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="stk-w-item">
                            <p style="color:#94a3b8;font-style:italic;margin:0;font-size:.8rem;">Belum ada aktivitas stok</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>{{-- /sidebar --}}
    </div>{{-- /layout --}}
</div>{{-- /stok-wrap --}}

@if($isPrint && !request()->boolean('preview'))
    <script>window.addEventListener('load', function(){ window.print(); });</script>
@endif

</x-app-layout>
