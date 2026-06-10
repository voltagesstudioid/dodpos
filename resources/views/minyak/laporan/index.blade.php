<x-app-layout>
    @push('styles')
    <style>
        .lp-page { max-width:80rem; margin:0 auto; padding:0 1rem; }

        /* Header */
        .lp-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
        .lp-hdr-left { display:flex; align-items:center; gap:1rem; }
        .lp-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f97316,#ea580c);
            box-shadow:0 8px 24px rgba(234,88,12,0.3);
        }
        .lp-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .lp-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        /* Period Tabs */
        .lp-tabs { display:flex; gap:0.375rem; background:#f1f5f9; padding:0.25rem; border-radius:12px; }
        .lp-tab {
            padding:0.5rem 1rem; border-radius:9px; font-size:0.8125rem; font-weight:600;
            color:#64748b; cursor:pointer; transition:all 0.2s; border:none; font-family:inherit;
            text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .lp-tab.active { background:#fff; color:#0f172a; box-shadow:0 1px 3px rgba(0,0,0,0.08); }
        .lp-tab:hover:not(.active) { color:#475569; }

        /* Filter */
        .lp-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lp-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .lp-ff-fld { min-width:140px; }
        .lp-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .lp-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .lp-finput:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .lp-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .lp-fsel:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .lp-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; box-shadow:0 4px 12px rgba(234,88,12,0.25);
        }
        .lp-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(234,88,12,0.35); }
        .lp-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .lp-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }
        .lp-date-range { font-size:0.75rem; color:#64748b; padding:0.5rem 0.875rem; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0; display:inline-flex; align-items:center; gap:0.5rem; }

        /* KPI Row */
        .lp-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:0.75rem; margin-bottom:1.5rem; }
        .lp-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1rem 1.125rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .lp-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .lp-kpi:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.06); border-color:transparent; }
        .lp-kpi.orange::before  { background:linear-gradient(90deg,#f97316,#ea580c); }
        .lp-kpi.emerald::before { background:linear-gradient(90deg,#10b981,#059669); }
        .lp-kpi.blue::before    { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .lp-kpi.violet::before  { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
        .lp-kpi.red::before     { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .lp-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.375rem; }
        .lp-kpi-lbl { font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .lp-kpi-ico { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1rem; }
        .lp-kpi-ico.orange  { background:linear-gradient(135deg,#fff7ed,#ffedd5); }
        .lp-kpi-ico.emerald { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .lp-kpi-ico.blue    { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .lp-kpi-ico.violet  { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .lp-kpi-ico.red     { background:linear-gradient(135deg,#fef2f2,#fee2e2); }
        .lp-kpi-val { font-size:1.25rem; font-weight:800; letter-spacing:-0.02em; line-height:1; color:#0f172a; }
        .lp-kpi-val-sm { font-size:1rem; }
        .lp-kpi-foot { font-size:0.65rem; color:#94a3b8; margin-top:0.25rem; }

        /* Panels */
        .lp-panel {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04); transition:all 0.25s;
        }
        .lp-panel:hover { box-shadow:0 6px 24px rgba(0,0,0,0.06); }
        .lp-panel-hdr {
            display:flex; align-items:center; justify-content:space-between;
            padding:1rem 1.375rem; border-bottom:1px solid #f1f5f9;
        }
        .lp-panel-title { display:flex; align-items:center; gap:0.625rem; font-size:0.875rem; font-weight:700; color:#0f172a; }
        .lp-panel-title-ico {
            width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.875rem;
        }
        .lp-panel-body { padding:1.25rem 1.375rem; }

        /* Chart */
        .lp-chart { display:flex; align-items:flex-end; gap:0.375rem; height:140px; padding-top:0.5rem; }
        .lp-chart-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:0.375rem; height:100%; }
        .lp-chart-track { flex:1; width:100%; display:flex; align-items:flex-end; justify-content:center; }
        .lp-chart-bar {
            width:100%; max-width:36px; border-radius:5px 5px 2px 2px;
            background:linear-gradient(180deg,#f97316,#ea580c);
            transition:all 0.4s; position:relative; min-height:3px; opacity:0.85;
        }
        .lp-chart-bar:hover { opacity:1; transform:scaleY(1.05); box-shadow:0 -3px 10px rgba(249,115,22,0.3); }
        .lp-chart-val {
            position:absolute; top:-18px; left:50%; transform:translateX(-50%);
            font-size:0.5625rem; font-weight:700; color:#64748b; white-space:nowrap; opacity:0; transition:opacity 0.2s;
        }
        .lp-chart-bar:hover .lp-chart-val { opacity:1; }
        .lp-chart-lbl { font-size:0.5625rem; font-weight:600; color:#94a3b8; }

        /* Leaderboard */
        .lp-leader { display:flex; flex-direction:column; gap:0.375rem; }
        .lp-leader-item {
            display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0.875rem;
            border-radius:10px; transition:all 0.2s; border:1px solid transparent;
        }
        .lp-leader-item:hover { background:#f8fafc; border-color:#f1f5f9; }
        .lp-rank {
            width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center;
            font-size:0.75rem; font-weight:800; flex-shrink:0;
        }
        .lp-rank.gold   { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; }
        .lp-rank.silver { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); color:#475569; }
        .lp-rank.bronze { background:linear-gradient(135deg,#ffedd5,#fed7aa); color:#9a3412; }
        .lp-rank.normal { background:#f8fafc; color:#94a3b8; }
        .lp-leader-av {
            width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            font-size:0.8125rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); color:#c2410c; border:1px solid #fed7aa;
        }
        .lp-leader-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .lp-leader-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }
        .lp-leader-amt { font-size:0.875rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; white-space:nowrap; }
        .lp-pbar-track { flex:1; height:5px; background:#f1f5f9; border-radius:99px; overflow:hidden; min-width:30px; }
        .lp-pbar-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#f97316,#ea580c); transition:width 0.6s; }

        /* Table */
        .lp-tbl { width:100%; border-collapse:separate; border-spacing:0; }
        .lp-tbl-head { background:linear-gradient(180deg,#fff7ed,#fef9ee); border-bottom:2px solid #fed7aa; }
        .lp-tbl-head th { padding:0.75rem 1rem; font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#9a3412; white-space:nowrap; }
        .lp-tbl-body td { padding:0.75rem 1rem; border-bottom:1px solid #fef9ee; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .lp-tbl-body tr { transition:background 0.15s; }
        .lp-tbl-body tr:last-child td { border-bottom:none; }
        .lp-tbl-body tr:hover td { background:#fffbf3; }

        /* Payment Type */
        .lp-pay-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .lp-pay-item {
            padding:0.875rem 1rem; border-radius:12px; border:1px solid #f1f5f9; text-align:center;
            transition:all 0.2s;
        }
        .lp-pay-item:hover { border-color:#e2e8f0; }
        .lp-pay-item.active { border-color:#fed7aa; background:#fff7ed; }
        .lp-pay-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:0.25rem; }
        .lp-pay-val { font-size:1.25rem; font-weight:800; color:#0f172a; }
        .lp-pay-sub { font-size:0.6875rem; color:#94a3b8; margin-top:0.125rem; }

        /* Empty */
        .lp-empty { text-align:center; padding:2.5rem 1rem; }
        .lp-empty-ico {
            width:64px; height:64px; margin:0 auto 0.75rem; border-radius:50%;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); display:flex; align-items:center; justify-content:center;
        }
        .lp-empty-title { font-size:0.9375rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .lp-empty-sub { font-size:0.8125rem; color:#94a3b8; }

        @media(max-width:1024px) { .lp-kpis { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:640px) { .lp-kpis { grid-template-columns:repeat(2,1fr); } .lp-hdr { flex-direction:column; align-items:flex-start; } .lp-pay-grid { grid-template-columns:1fr; } }

        /* ===== SCREEN / PRINT TOGGLE ===== */
        .screen-only { display:block; }
        .print-only { display:none !important; }

        /* ===== PRINT STYLES ===== */
        @media print {
            .screen-only { display:none !important; }
            .print-only { display:block !important; }
            .sidebar,.sidebar-overlay,.topbar,#sidebar,#sidebar-overlay { display:none !important; }
            body { background:#fff !important; margin:0 !important; padding:0 !important; }
            .page-content { margin-left:0 !important; padding:0 !important; width:100% !important; }
            .py-4 { padding:0 !important; }

            /* Print Report Layout */
            .pr { font-family:'Plus Jakarta Sans',sans-serif; font-size:8pt; color:#1e293b; max-width:100%; }
            .pr * { box-sizing:border-box; }
            .pr-hdr { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:3px solid #1e293b; padding-bottom:10px; margin-bottom:14px; }
            .pr-company { font-size:16pt; font-weight:800; color:#1e293b; letter-spacing:-0.02em; }
            .pr-title { font-size:11pt; font-weight:600; color:#475569; margin-top:2px; }
            .pr-period { font-size:8pt; color:#64748b; margin-top:2px; }
            .pr-hdr-r { text-align:right; font-size:7pt; color:#94a3b8; }

            .pr-section { margin-bottom:14px; }
            .pr-section-title { font-size:9pt; font-weight:700; color:#1e293b; border-bottom:1px solid #cbd5e1; padding-bottom:4px; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; }

            /* KPI Grid */
            .pr-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:6px; margin-bottom:14px; }
            .pr-kpi { border:1px solid #e2e8f0; border-radius:4px; padding:8px 10px; }
            .pr-kpi-lbl { font-size:6.5pt; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }
            .pr-kpi-val { font-size:12pt; font-weight:800; color:#1e293b; margin-top:2px; }
            .pr-kpi-sub { font-size:6.5pt; color:#94a3b8; margin-top:1px; }

            /* Tables */
            .pr-tbl { width:100%; border-collapse:collapse; font-size:8pt; }
            .pr-tbl thead th { background:#f1f5f9; color:#475569; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; font-size:6.5pt; padding:6px 8px; border-bottom:2px solid #cbd5e1; text-align:left; }
            .pr-tbl tbody td { padding:5px 8px; border-bottom:1px solid #e2e8f0; }
            .pr-tbl tfoot td { padding:6px 8px; font-weight:700; border-top:2px solid #cbd5e1; background:#f8fafc; }
            .pr-tbl .text-right { text-align:right; }
            .pr-tbl .text-center { text-align:center; }

            /* Two-column layout */
            .pr-cols { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

            /* Bar chart in print */
            .pr-bar-row { display:flex; align-items:center; gap:6px; margin-bottom:3px; font-size:7pt; }
            .pr-bar-lbl { width:42px; text-align:right; color:#64748b; font-weight:600; flex-shrink:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .pr-bar-track { flex:1; height:10px; background:#e2e8f0; border-radius:2px; overflow:hidden; }
            .pr-bar-fill { height:100%; border-radius:2px; print-color-adjust:exact !important; -webkit-print-color-adjust:exact !important; }
            .pr-bar-val { width:70px; font-weight:700; flex-shrink:0; }

            /* Payment grid */
            .pr-pay-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:14px; }
            .pr-pay-item { border:1px solid #e2e8f0; border-radius:4px; padding:8px; text-align:center; }
            .pr-pay-lbl { font-size:6.5pt; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }
            .pr-pay-val { font-size:11pt; font-weight:800; color:#1e293b; margin-top:2px; }
            .pr-pay-sub { font-size:6.5pt; color:#94a3b8; margin-top:1px; }

            /* Sales ranking */
            .pr-rank { display:inline-flex; width:18px; height:18px; border-radius:4px; align-items:center; justify-content:center; font-size:6.5pt; font-weight:800; color:#fff; vertical-align:middle; print-color-adjust:exact !important; -webkit-print-color-adjust:exact !important; }
            .pr-sales-av { display:inline-flex; width:18px; height:18px; border-radius:50%; align-items:center; justify-content:center; font-size:6pt; font-weight:700; color:#fff; vertical-align:middle; print-color-adjust:exact !important; -webkit-print-color-adjust:exact !important; }

            /* Summary boxes */
            .pr-box { border:1px solid #e2e8f0; border-radius:4px; padding:8px 10px; margin-bottom:6px; }
            .pr-box-title { font-size:7pt; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px; }
            .pr-big-val { font-size:14pt; font-weight:800; }
            .pr-sub-val { font-size:7pt; color:#64748b; }

            /* Footer */
            .pr-footer { margin-top:20px; padding-top:10px; border-top:1px solid #cbd5e1; display:flex; justify-content:space-between; }
            .pr-sign { text-align:center; width:180px; }
            .pr-sign-line { border-bottom:1px solid #1e293b; margin-top:50px; margin-bottom:4px; }
            .pr-sign-name { font-size:8pt; font-weight:600; }
            .pr-sign-title { font-size:7pt; color:#64748b; }
            .pr-note { font-size:7pt; color:#94a3b8; max-width:300px; }

            @page { margin:1.5cm; size:A4 landscape; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="screen-only">
        <div class="lp-page">

            {{-- Header --}}
            <div class="lp-hdr">
                <div class="lp-hdr-left">
                    <div class="lp-hdr-ico">📊</div>
                    <div>
                        <div class="lp-hdr-title">Laporan Minyak</div>
                        <div class="lp-hdr-sub">Analisis penjualan, performa sales, dan tren produk</div>
                    </div>
                </div>
                <div class="lp-tabs">
                    <a href="{{ route('minyak.laporan', ['periode' => 'harian']) }}" class="lp-tab {{ $periode == 'harian' ? 'active' : '' }}">📅 Harian</a>
                    <a href="{{ route('minyak.laporan', ['periode' => 'mingguan']) }}" class="lp-tab {{ $periode == 'mingguan' ? 'active' : '' }}">📊 Mingguan</a>
                    <a href="{{ route('minyak.laporan', ['periode' => 'bulanan']) }}" class="lp-tab {{ $periode == 'bulanan' ? 'active' : '' }}">📈 Bulanan</a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="lp-filter">
                <form method="GET" class="lp-ff">
                    <div class="lp-ff-fld">
                        <label class="lp-flbl">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" value="{{ $dari->format('Y-m-d') }}" class="lp-finput">
                    </div>
                    <div class="lp-ff-fld">
                        <label class="lp-flbl">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" value="{{ $sampai->format('Y-m-d') }}" class="lp-finput">
                    </div>
                    <div>
                        <label class="lp-flbl">Sales</label>
                        <select name="sales_id" class="lp-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($salesList as $s)
                                <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="periode" value="custom">
                    <button type="submit" class="lp-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('minyak.laporan') }}" class="lp-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                    <div class="lp-date-range">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $dari->format('d M Y') }} — {{ $sampai->format('d M Y') }}
                    </div>
                </form>
            </div>

            {{-- KPI Cards --}}
            <div class="lp-kpis">
                <div class="lp-kpi orange">
                    <div class="lp-kpi-top">
                        <span class="lp-kpi-lbl">Total Penjualan</span>
                        <div class="lp-kpi-ico orange">💰</div>
                    </div>
                    <div class="lp-kpi-val lp-kpi-val-sm">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    <div class="lp-kpi-foot">Omzet periode ini</div>
                </div>
                <div class="lp-kpi emerald">
                    <div class="lp-kpi-top">
                        <span class="lp-kpi-lbl">Total Transaksi</span>
                        <div class="lp-kpi-ico emerald">🧾</div>
                    </div>
                    <div class="lp-kpi-val">{{ number_format($totalTransaksi) }}</div>
                    <div class="lp-kpi-foot">Jumlah faktur</div>
                </div>
                <div class="lp-kpi blue">
                    <div class="lp-kpi-top">
                        <span class="lp-kpi-lbl">Volume Terjual</span>
                        <div class="lp-kpi-ico blue">🛢️</div>
                    </div>
                    <div class="lp-kpi-val">{{ number_format($totalVolume, 0, ',', '.') }} <span style="font-size:0.7rem;font-weight:600;color:#94a3b8;">L</span></div>
                    <div class="lp-kpi-foot">Liter BBM terjual</div>
                </div>
                <div class="lp-kpi violet">
                    <div class="lp-kpi-top">
                        <span class="lp-kpi-lbl">Total Loading</span>
                        <div class="lp-kpi-ico violet">🚛</div>
                    </div>
                    <div class="lp-kpi-val">{{ number_format($totalLoading, 0, ',', '.') }} <span style="font-size:0.7rem;font-weight:600;color:#94a3b8;">L</span></div>
                    <div class="lp-kpi-foot">Muatan BBM keluar</div>
                </div>
                <div class="lp-kpi red">
                    <div class="lp-kpi-top">
                        <span class="lp-kpi-lbl">Piutang Baru</span>
                        <div class="lp-kpi-ico red">💳</div>
                    </div>
                    <div class="lp-kpi-val lp-kpi-val-sm">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                    <div class="lp-kpi-foot">Hutang dari penjualan</div>
                </div>
            </div>

            {{-- Row 1: Trend Chart + Payment Breakdown --}}
            <div style="display:grid; grid-template-columns:2fr 1fr; gap:1rem; margin-bottom:1rem;">
                {{-- Trend Chart --}}
                <div class="lp-panel">
                    <div class="lp-panel-hdr">
                        <div class="lp-panel-title">
                            <div class="lp-panel-title-ico" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);">📈</div>
                            Tren Penjualan
                        </div>
                        <span style="font-size:0.6875rem; color:#94a3b8;">{{ count($dailyTrend) }} hari</span>
                    </div>
                    <div class="lp-panel-body">
                        @if(count($dailyTrend) > 0)
                            @php
                                $maxTrend = max(array_column($dailyTrend, 'total') ?: [0]);
                                $maxTrend = max($maxTrend, 1);
                            @endphp
                            <div class="lp-chart">
                                @foreach($dailyTrend as $d)
                                    @php $h = max(($d['total'] / $maxTrend) * 100, 3); @endphp
                                    <div class="lp-chart-wrap">
                                        <div class="lp-chart-track">
                                            <div class="lp-chart-bar" style="height:{{ $h }}%;">
                                                <span class="lp-chart-val">Rp {{ number_format($d['total'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="lp-chart-lbl">{{ $d['tanggal'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="lp-empty">
                                <div class="lp-empty-ico">
                                    <svg width="28" height="28" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                                <div class="lp-empty-title">Belum Ada Data Tren</div>
                                <div class="lp-empty-sub">Data penjualan akan ditampilkan di sini</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Breakdown --}}
                <div class="lp-panel">
                    <div class="lp-panel-hdr">
                        <div class="lp-panel-title">
                            <div class="lp-panel-title-ico" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">💳</div>
                            Tipe Pembayaran
                        </div>
                    </div>
                    <div class="lp-panel-body">
                        <div class="lp-pay-grid">
                            <div class="lp-pay-item active">
                                <div class="lp-pay-lbl">Tunai</div>
                                <div class="lp-pay-val">{{ $tipeBayarStats['tunai'] }}</div>
                                <div class="lp-pay-sub">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
                            </div>
                            <div class="lp-pay-item">
                                <div class="lp-pay-lbl">Hutang</div>
                                <div class="lp-pay-val">{{ $tipeBayarStats['hutang'] }}</div>
                                <div class="lp-pay-sub">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                            </div>
                            <div class="lp-pay-item">
                                <div class="lp-pay-lbl">Transfer</div>
                                <div class="lp-pay-val">{{ $tipeBayarStats['transfer'] }}</div>
                                <div class="lp-pay-sub">—</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Row 2: Sales Performance + Product Performance --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                {{-- Sales Performance --}}
                <div class="lp-panel">
                    <div class="lp-panel-hdr">
                        <div class="lp-panel-title">
                            <div class="lp-panel-title-ico" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);">🏆</div>
                            Performa Sales
                        </div>
                        <span style="font-size:0.6875rem; color:#94a3b8;">{{ $salesPerformance->count() }} sales</span>
                    </div>
                    <div class="lp-panel-body">
                        @if($salesPerformance->count() > 0)
                            @php $maxSalesAmt = $salesPerformance->max('penjualans_sum_total') ?? 1; @endphp
                            <div class="lp-leader">
                                @foreach($salesPerformance as $i => $s)
                                    @php
                                        $rc = $i == 0 ? 'gold' : ($i == 1 ? 'silver' : ($i == 2 ? 'bronze' : 'normal'));
                                        $bw = $maxSalesAmt > 0 ? (($s->penjualans_sum_total ?? 0) / $maxSalesAmt) * 100 : 0;
                                    @endphp
                                    <div class="lp-leader-item">
                                        <div class="lp-rank {{ $rc }}">{{ $i + 1 }}</div>
                                        <div class="lp-leader-av">{{ substr($s->nama, 0, 1) }}</div>
                                        <div style="flex:1; min-width:0;">
                                            <div class="lp-leader-name">{{ $s->nama }}</div>
                                            <div class="lp-leader-sub">{{ $s->penjualans_count }} transaksi</div>
                                        </div>
                                        <div class="lp-pbar-track" style="max-width:80px;">
                                            <div class="lp-pbar-fill" style="width:{{ $bw }}%;"></div>
                                        </div>
                                        <div class="lp-leader-amt">Rp {{ number_format($s->penjualans_sum_total ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="lp-empty">
                                <div class="lp-empty-ico"><svg width="28" height="28" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                <div class="lp-empty-title">Belum Ada Data Sales</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Product Performance --}}
                <div class="lp-panel">
                    <div class="lp-panel-hdr">
                        <div class="lp-panel-title">
                            <div class="lp-panel-title-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">🛢️</div>
                            Performa Produk
                        </div>
                    </div>
                    <div class="lp-panel-body" style="padding:0;">
                        @if($produkPerformance->where('penjualans_count', '>', 0)->count() > 0)
                            <div style="overflow-x:auto;">
                                <table class="lp-tbl">
                                    <thead class="lp-tbl-head">
                                        <tr>
                                            <th style="text-align:left;">Produk</th>
                                            <th style="text-align:right;">Volume (L)</th>
                                            <th style="text-align:right;">Transaksi</th>
                                            <th style="text-align:right;">Omzet</th>
                                        </tr>
                                    </thead>
                                    <tbody class="lp-tbl-body">
                                        @foreach($produkPerformance->where('penjualans_count', '>', 0) as $p)
                                            <tr>
                                                <td>
                                                    <div style="font-weight:600; color:#1e293b;">{{ $p->nama }}</div>
                                                    <div style="font-size:0.6875rem; color:#94a3b8;">{{ $p->jenis ?? '' }}</div>
                                                </td>
                                                <td style="text-align:right; font-weight:700; color:#0284c7;">{{ number_format($p->penjualans_sum_jumlah ?? 0, 0, ',', '.') }}</td>
                                                <td style="text-align:right; font-weight:600;">{{ number_format($p->penjualans_count) }}</td>
                                                <td style="text-align:right; font-weight:700; color:#0f172a;">Rp {{ number_format($p->penjualans_sum_total ?? 0, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="lp-empty">
                                <div class="lp-empty-ico"><svg width="28" height="28" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                                <div class="lp-empty-title">Belum Ada Data Produk</div>
                                <div class="lp-empty-sub">Produk terjual akan muncul di sini</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Access --}}
            <div class="lp-panel" style="margin-bottom:1rem;">
                <div class="lp-panel-hdr">
                    <div class="lp-panel-title">
                        <div class="lp-panel-title-ico" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">⚡</div>
                        Akses Cepat
                    </div>
                </div>
                <div class="lp-panel-body">
                    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:0.75rem;">
                        <a href="{{ route('minyak.penjualan.index') }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.2s; background:#f8fafc;" onmouseover="this.style.background='#fff7ed';this.style.borderColor='#fed7aa'" onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#fff7ed,#ffedd5);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">🧾</div>
                            <div>
                                <div style="font-size:0.8125rem;font-weight:700;color:#0f172a;">Data Penjualan</div>
                                <div style="font-size:0.6875rem;color:#94a3b8;">Semua transaksi</div>
                            </div>
                        </a>
                        <a href="{{ route('minyak.setoran.index') }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.2s; background:#f8fafc;" onmouseover="this.style.background='#ecfdf5';this.style.borderColor='#a7f3d0'" onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">💵</div>
                            <div>
                                <div style="font-size:0.8125rem;font-weight:700;color:#0f172a;">Data Setoran</div>
                                <div style="font-size:0.6875rem;color:#94a3b8;">Verifikasi setoran</div>
                            </div>
                        </a>
                        <a href="{{ route('minyak.loading.index') }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.2s; background:#f8fafc;" onmouseover="this.style.background='#eff6ff';this.style.borderColor='#bfdbfe'" onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#eff6ff,#dbeafe);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">🚛</div>
                            <div>
                                <div style="font-size:0.8125rem;font-weight:700;color:#0f172a;">Loading Harian</div>
                                <div style="font-size:0.6875rem;color:#94a3b8;">Muatan BBM</div>
                            </div>
                        </a>
                        <a href="{{ route('minyak.stok.index') }}" style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.2s; background:#f8fafc;" onmouseover="this.style.background='#f5f3ff';this.style.borderColor='#ddd6fe'" onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#f5f3ff,#ede9fe);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">📊</div>
                            <div>
                                <div style="font-size:0.8125rem;font-weight:700;color:#0f172a;">Stok Kendaraan</div>
                                <div style="font-size:0.6875rem;color:#94a3b8;">Sisa stok per sales</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        </div>

        {{-- ========== PRINT REPORT ========== --}}
        <div class="print-only pr">
            {{-- Header --}}
            <div class="pr-hdr">
                <div>
                    <div class="pr-company">TOKO SEDERHANA</div>
                    <div class="pr-title">Laporan Penjualan Minyak</div>
                    <div class="pr-period">{{ $dari->isoFormat('D MMMM YYYY') }} &mdash; {{ $sampai->isoFormat('D MMMM YYYY') }}</div>
                </div>
                <div class="pr-hdr-r">
                    <div>Periode: {{ ucfirst($periode) }}</div>
                    <div>Dicetak: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB</div>
                </div>
            </div>

            {{-- KPI Summary --}}
            <div class="pr-kpis">
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Total Penjualan</div>
                    <div class="pr-kpi-val">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    <div class="pr-kpi-sub">Omzet periode ini</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Total Transaksi</div>
                    <div class="pr-kpi-val">{{ number_format($totalTransaksi) }}</div>
                    <div class="pr-kpi-sub">Jumlah faktur</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Volume Terjual</div>
                    <div class="pr-kpi-val">{{ number_format($totalVolume, 0, ',', '.') }} L</div>
                    <div class="pr-kpi-sub">Liter BBM terjual</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Total Loading</div>
                    <div class="pr-kpi-val">{{ number_format($totalLoading, 0, ',', '.') }} L</div>
                    <div class="pr-kpi-sub">Muatan BBM keluar</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Piutang Baru</div>
                    <div class="pr-kpi-val">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                    <div class="pr-kpi-sub">Hutang dari penjualan</div>
                </div>
            </div>

            {{-- Payment Breakdown --}}
            <div class="pr-section">
                <div class="pr-section-title">Tipe Pembayaran</div>
                <div class="pr-pay-grid">
                    <div class="pr-pay-item">
                        <div class="pr-pay-lbl">Tunai</div>
                        <div class="pr-pay-val">{{ $tipeBayarStats['tunai'] }}</div>
                        <div class="pr-pay-sub">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
                    </div>
                    <div class="pr-pay-item">
                        <div class="pr-pay-lbl">Hutang</div>
                        <div class="pr-pay-val">{{ $tipeBayarStats['hutang'] }}</div>
                        <div class="pr-pay-sub">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                    </div>
                    <div class="pr-pay-item">
                        <div class="pr-pay-lbl">Transfer</div>
                        <div class="pr-pay-val">{{ $tipeBayarStats['transfer'] }}</div>
                        <div class="pr-pay-sub">&mdash;</div>
                    </div>
                </div>
            </div>

            {{-- Daily Trend Chart --}}
            @if(count($dailyTrend) > 0)
            @php $maxTrend = max(array_column($dailyTrend, 'total') ?: [0]); $maxTrend = max($maxTrend, 1); @endphp
            <div class="pr-section">
                <div class="pr-section-title">Tren Penjualan Harian ({{ count($dailyTrend) }} hari)</div>
                @foreach($dailyTrend as $d)
                @php $pct = ($d['total'] / $maxTrend) * 100; @endphp
                <div class="pr-bar-row">
                    <div class="pr-bar-lbl">{{ $d['tanggal'] }}</div>
                    <div class="pr-bar-track"><div class="pr-bar-fill" style="width:{{ $pct }}%;background:linear-gradient(180deg,#f97316,#ea580c);"></div></div>
                    <div class="pr-bar-val">Rp {{ number_format($d['total'], 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Sales & Product Performance --}}
            <div class="pr-cols">
                {{-- Sales Performance --}}
                <div class="pr-section">
                    <div class="pr-section-title">Performa Sales ({{ $salesPerformance->count() }})</div>
                    <table class="pr-tbl">
                        <thead><tr><th>#</th><th>Sales</th><th class="text-center">Transaksi</th><th class="text-right">Omzet</th></tr></thead>
                        <tbody>
                        @forelse($salesPerformance as $i => $s)
                        @php $rc = $i == 0 ? '#f59e0b' : ($i == 1 ? '#94a3b8' : ($i == 2 ? '#d97706' : '#e2e8f0')); @endphp
                        <tr>
                            <td class="text-center"><span class="pr-rank" style="background:{{ $rc }};">{{ $i + 1 }}</span></td>
                            <td>
                                <span class="pr-sales-av" style="background:{{ ['#f97316','#ea580c','#c2410c','#d97706','#b45309','#9a3412'][$i % 6] }};">{{ substr($s->nama, 0, 1) }}</span>
                                {{ $s->nama }}
                            </td>
                            <td class="text-center">{{ $s->penjualans_count }}</td>
                            <td class="text-right">Rp {{ number_format($s->penjualans_sum_total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center" style="color:#94a3b8;padding:12px;">Tidak ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Product Performance --}}
                <div class="pr-section">
                    <div class="pr-section-title">Performa Produk</div>
                    <table class="pr-tbl">
                        <thead><tr><th>Produk</th><th class="text-right">Volume (L)</th><th class="text-right">Transaksi</th><th class="text-right">Omzet</th></tr></thead>
                        <tbody>
                        @forelse($produkPerformance->where('penjualans_count', '>', 0) as $p)
                        <tr>
                            <td>{{ $p->nama }}<br><span style="font-size:6pt;color:#94a3b8;">{{ $p->jenis ?? '' }}</span></td>
                            <td class="text-right" style="color:#0284c7;font-weight:700;">{{ number_format($p->penjualans_sum_jumlah ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($p->penjualans_count) }}</td>
                            <td class="text-right" style="font-weight:700;">Rp {{ number_format($p->penjualans_sum_total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center" style="color:#94a3b8;padding:12px;">Tidak ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer / Signature --}}
            <div class="pr-footer">
                <div class="pr-note">Laporan ini dicetak secara otomatis dari sistem DOD POS dan merupakan data yang sah.</div>
                <div class="pr-sign">
                    <div class="pr-sign-line"></div>
                    <div class="pr-sign-name">Supervisor</div>
                    <div class="pr-sign-title">Penanggung Jawab</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
