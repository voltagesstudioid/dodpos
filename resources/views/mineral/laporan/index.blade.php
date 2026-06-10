<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .lp-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* Header */
        .lp-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .lp-hdr-l { display:flex; align-items:center; gap:1rem; }
        .lp-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#8b5cf6,#7c3aed);
            box-shadow:0 8px 24px rgba(124,58,237,0.3);
        }
        .lp-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .lp-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .lp-hdr-btn {
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.7rem 1.375rem; border-radius:12px; font-size:0.8125rem; font-weight:600;
            text-decoration:none; transition:all 0.25s cubic-bezier(0.4,0,0.2,1); border:none; cursor:pointer;
            background:linear-gradient(135deg,#10b981,#059669); color:#fff;
            box-shadow:0 4px 14px rgba(5,150,105,0.35);
        }
        .lp-hdr-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(5,150,105,0.45); }

        /* Filter */
        .lp-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lp-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .lp-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .lp-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit;
        }
        .lp-finput:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,0.12); }
        .lp-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .lp-fsel:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,0.12); }
        .lp-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff; box-shadow:0 4px 12px rgba(124,58,237,0.25);
        }
        .lp-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(124,58,237,0.35); }
        .lp-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .lp-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }

        /* KPI Row */
        .lp-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:1rem; margin-bottom:1.5rem; }
        .lp-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .lp-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .lp-kpi:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.07); border-color:transparent; }
        .lp-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .lp-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .lp-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .lp-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .lp-kpi.red::before    { background:linear-gradient(180deg,#ef4444,#dc2626); }
        .lp-kpi-top { display:flex; align-items:center; justify-content:space-between; }
        .lp-kpi-left { display:flex; flex-direction:column; gap:0.25rem; }
        .lp-kpi-lbl { font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; }
        .lp-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; line-height:1; }
        .lp-kpi-val.purple { color:#7c3aed; }
        .lp-kpi-val.blue   { color:#2563eb; }
        .lp-kpi-val.green  { color:#059669; }
        .lp-kpi-val.amber  { color:#d97706; }
        .lp-kpi-val.red    { color:#dc2626; }
        .lp-kpi-foot { font-size:0.6875rem; color:#94a3b8; margin-top:0.375rem; }
        .lp-kpi-ico { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; }
        .lp-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .lp-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .lp-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .lp-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .lp-kpi-ico.red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); }

        /* Charts Row */
        .lp-charts { display:grid; grid-template-columns:repeat(2,1fr); gap:1.5rem; margin-bottom:1.5rem; }
        .lp-chart {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lp-chart-title {
            font-size:0.9375rem; font-weight:700; color:#0f172a; margin-bottom:1rem;
            display:flex; align-items:center; gap:0.5rem;
        }
        .lp-chart-title::before {
            content:''; width:4px; height:16px; border-radius:2px;
            background:linear-gradient(180deg,#8b5cf6,#7c3aed);
        }

        /* Bar Chart */
        .lp-bar { display:flex; align-items:center; gap:0.75rem; margin-bottom:0.625rem; }
        .lp-bar-lbl { width:3.5rem; font-size:0.75rem; color:#64748b; flex-shrink:0; }
        .lp-bar-track { flex:1; height:1.5rem; background:#f1f5f9; border-radius:8px; overflow:hidden; }
        .lp-bar-fill { height:100%; border-radius:8px; transition:width 0.5s ease; }
        .lp-bar-fill.purple { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
        .lp-bar-fill.blue { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .lp-bar-info { width:6rem; text-align:right; flex-shrink:0; }
        .lp-bar-val { font-size:0.8125rem; font-weight:700; color:#1e293b; }
        .lp-bar-sub { font-size:0.6875rem; color:#94a3b8; }

        /* Sales Avatar */
        .lp-sales-av {
            width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;
        }

        /* Product Table */
        .lp-prod {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lp-prod-hdr {
            background:linear-gradient(180deg,#f5f3ff,#ede9fe); border-bottom:2px solid #c4b5fd;
            padding:1rem 1.375rem;
        }
        .lp-prod-title {
            font-size:0.9375rem; font-weight:700; color:#5b21b6;
            display:flex; align-items:center; gap:0.5rem;
        }
        .lp-prod-title::before {
            content:''; width:4px; height:16px; border-radius:2px;
            background:linear-gradient(180deg,#8b5cf6,#7c3aed);
        }
        .lp-tbl-head { background:linear-gradient(180deg,#f5f3ff,#ede9fe); border-bottom:2px solid #c4b5fd; }
        .lp-tbl-head th {
            padding:0.75rem 1.25rem; font-size:0.675rem; font-weight:700; text-transform:uppercase;
            letter-spacing:0.07em; color:#5b21b6; white-space:nowrap;
        }
        .lp-tbl-body td { padding:0.875rem 1.25rem; border-bottom:1px solid #f1f5f9; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .lp-tbl-body tr { transition:background 0.15s; }
        .lp-tbl-body tr:last-child td { border-bottom:none; }
        .lp-tbl-body tr:hover td { background:linear-gradient(90deg,#faf5ff,#f5f3ff); }

        /* Product cell */
        .lp-prod-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .lp-prod-type { font-size:0.6875rem; color:#94a3b8; }

        /* Volume badge */
        .lp-vol {
            display:inline-flex; align-items:center; gap:0.25rem;
            padding:0.25rem 0.625rem; border-radius:8px; font-size:0.75rem; font-weight:700;
            font-family:'JetBrains Mono',monospace;
        }
        .lp-vol.amber { background:#fffbeb; color:#d97706; }

        /* Progress bar */
        .lp-progress { height:6px; background:#f1f5f9; border-radius:99px; overflow:hidden; }
        .lp-progress-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#f59e0b,#d97706); transition:width 0.5s ease; }

        /* Summary Cards */
        .lp-summary { display:grid; grid-template-columns:repeat(2,1fr); gap:1.5rem; }
        .lp-summary-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lp-summary-title {
            font-size:0.9375rem; font-weight:700; color:#0f172a; margin-bottom:1rem;
            display:flex; align-items:center; gap:0.5rem;
        }
        .lp-summary-title::before {
            content:''; width:4px; height:16px; border-radius:2px;
            background:linear-gradient(180deg,#8b5cf6,#7c3aed);
        }

        /* Setoran status */
        .lp-setoran { display:flex; align-items:center; justify-content:space-between; padding:0.75rem 1rem; background:#f8fafc; border-radius:12px; margin-bottom:0.625rem; }
        .lp-setoran-left { display:flex; align-items:center; gap:0.75rem; }
        .lp-setoran-badge {
            padding:0.25rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:700;
        }
        .lp-setoran-badge.pending { background:#fffbeb; color:#d97706; }
        .lp-setoran-badge.terverifikasi { background:#ecfdf5; color:#059669; }
        .lp-setoran-badge.ditolak { background:#fef2f2; color:#dc2626; }
        .lp-setoran-count { font-size:0.8125rem; color:#64748b; }
        .lp-setoran-total { font-size:0.875rem; font-weight:700; color:#1e293b; font-family:'JetBrains Mono',monospace; }

        /* Hutang metrics */
        .lp-hutang { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .lp-hutang-item { padding:1rem; border-radius:12px; text-align:center; }
        .lp-hutang-item.red { background:#fef2f2; }
        .lp-hutang-item.amber { background:#fffbeb; }
        .lp-hutang-item.blue { background:#eff6ff; }
        .lp-hutang-val { font-size:1.25rem; font-weight:800; letter-spacing:-0.02em; }
        .lp-hutang-val.red { color:#dc2626; }
        .lp-hutang-val.amber { color:#d97706; }
        .lp-hutang-val.blue { color:#2563eb; }
        .lp-hutang-lbl { font-size:0.6875rem; color:#64748b; margin-top:0.25rem; }

        /* Empty */
        .lp-empty { text-align:center; padding:2rem; color:#94a3b8; font-size:0.875rem; }

        @media(max-width:1280px) { .lp-kpis { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:1024px) { .lp-charts { grid-template-columns:1fr; } .lp-summary { grid-template-columns:1fr; } }
        @media(max-width:768px)  { .lp-kpis { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px)  { .lp-kpis { grid-template-columns:1fr; } .lp-hdr-title { font-size:1.25rem; } }

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
            .pr-hdr-l {}
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

            /* Sales table rows */
            .pr-sales-av { display:inline-flex; width:18px; height:18px; border-radius:50%; align-items:center; justify-content:center; font-size:6pt; font-weight:700; color:#fff; vertical-align:middle; print-color-adjust:exact !important; -webkit-print-color-adjust:exact !important; }

            /* Setoran badges */
            .pr-badge { display:inline-block; padding:2px 6px; border-radius:3px; font-size:6.5pt; font-weight:700; }

            /* Summary boxes */
            .pr-box { border:1px solid #e2e8f0; border-radius:4px; padding:8px 10px; margin-bottom:6px; }
            .pr-box-title { font-size:7pt; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px; }
            .pr-big-val { font-size:14pt; font-weight:800; }
            .pr-sub-val { font-size:7pt; color:#64748b; }

            /* Bar chart in print */
            .pr-bar-row { display:flex; align-items:center; gap:6px; margin-bottom:3px; font-size:7pt; }
            .pr-bar-lbl { width:50px; text-align:right; color:#64748b; font-weight:600; flex-shrink:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .pr-bar-track { flex:1; height:10px; background:#e2e8f0; border-radius:2px; overflow:hidden; }
            .pr-bar-fill { height:100%; border-radius:2px; print-color-adjust:exact !important; -webkit-print-color-adjust:exact !important; }
            .pr-bar-val { width:70px; font-weight:700; flex-shrink:0; }

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
                <div class="lp-hdr-l">
                    <div class="lp-hdr-ico">📊</div>
                    <div>
                        <div class="lp-hdr-title">Laporan Penjualan</div>
                        <div class="lp-hdr-sub">Analisis penjualan mineral</div>
                    </div>
                </div>
                <button type="button" onclick="window.print()" class="lp-hdr-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Laporan
                </button>
            </div>

            {{-- Filter --}}
            <div class="lp-filter">
                <form method="GET" class="lp-ff">
                    <div>
                        <label class="lp-flbl">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="lp-finput">
                    </div>
                    <div>
                        <label class="lp-flbl">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="lp-finput">
                    </div>
                    <div>
                        <label class="lp-flbl">Sales</label>
                        <select name="sales_id" class="lp-fsel">
                            <option value="">Semua Sales</option>
                            @foreach($salesList as $s)
                                <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="lp-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('mineral.laporan') }}" class="lp-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </form>
            </div>

            {{-- KPI Cards --}}
            <div class="lp-kpis">
                <div class="lp-kpi purple">
                    <div class="lp-kpi-top">
                        <div class="lp-kpi-left">
                            <span class="lp-kpi-lbl">Total Penjualan</span>
                            <div>
                                <span class="lp-kpi-val purple">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</span>
                            </div>
                            <div class="lp-kpi-foot">Omzet total periode ini</div>
                        </div>
                        <div class="lp-kpi-ico purple">💰</div>
                    </div>
                </div>
                <div class="lp-kpi blue">
                    <div class="lp-kpi-top">
                        <div class="lp-kpi-left">
                            <span class="lp-kpi-lbl">Transaksi</span>
                            <div>
                                <span class="lp-kpi-val blue">{{ $summary['jumlah_transaksi'] }}</span>
                            </div>
                            <div class="lp-kpi-foot">Jumlah transaksi</div>
                        </div>
                        <div class="lp-kpi-ico blue">📋</div>
                    </div>
                </div>
                <div class="lp-kpi green">
                    <div class="lp-kpi-top">
                        <div class="lp-kpi-left">
                            <span class="lp-kpi-lbl">Tunai</span>
                            <div>
                                <span class="lp-kpi-val green">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</span>
                            </div>
                            <div class="lp-kpi-foot">Pembayaran tunai</div>
                        </div>
                        <div class="lp-kpi-ico green">💵</div>
                    </div>
                </div>
                <div class="lp-kpi amber">
                    <div class="lp-kpi-top">
                        <div class="lp-kpi-left">
                            <span class="lp-kpi-lbl">Transfer</span>
                            <div>
                                <span class="lp-kpi-val amber">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</span>
                            </div>
                            <div class="lp-kpi-foot">Pembayaran transfer</div>
                        </div>
                        <div class="lp-kpi-ico amber">🔄</div>
                    </div>
                </div>
                <div class="lp-kpi red">
                    <div class="lp-kpi-top">
                        <div class="lp-kpi-left">
                            <span class="lp-kpi-lbl">Hutang Baru</span>
                            <div>
                                <span class="lp-kpi-val red">Rp {{ number_format($summary['total_hutang'], 0, ',', '.') }}</span>
                            </div>
                            <div class="lp-kpi-foot">Piutang baru periode ini</div>
                        </div>
                        <div class="lp-kpi-ico red">⚠️</div>
                    </div>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="lp-charts">
                {{-- Daily Chart --}}
                <div class="lp-chart">
                    <div class="lp-chart-title">Penjualan Harian</div>
                    @if($dailyData->count() > 0)
                        @foreach($dailyData as $d)
                        @php
                            $maxVal = $dailyData->max('total');
                            $percentage = $maxVal > 0 ? ($d->total / $maxVal) * 100 : 0;
                        @endphp
                        <div class="lp-bar">
                            <div class="lp-bar-lbl">{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m') }}</div>
                            <div class="lp-bar-track">
                                <div class="lp-bar-fill purple" style="width:{{ $percentage }}%"></div>
                            </div>
                            <div class="lp-bar-info">
                                <div class="lp-bar-val">Rp {{ number_format($d->total/1000, 0) }}k</div>
                                <div class="lp-bar-sub">{{ $d->jumlah }} trx</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="lp-empty">Tidak ada data penjualan</div>
                    @endif
                </div>

                {{-- Sales Performance --}}
                <div class="lp-chart">
                    <div class="lp-chart-title">Performa Sales</div>
                    @if($salesPerformance->count() > 0)
                        @foreach($salesPerformance as $sp)
                        @php
                            $maxOmzet = $salesPerformance->max('omzet') ?: 1;
                            $percentage = $maxOmzet > 0 ? ($sp->omzet / $maxOmzet) * 100 : 0;
                        @endphp
                        <div class="lp-bar">
                            <div class="lp-sales-av">{{ substr($sp->nama, 0, 1) }}</div>
                            <div class="lp-bar-lbl" style="width:5rem;">{{ $sp->nama }}</div>
                            <div class="lp-bar-track">
                                <div class="lp-bar-fill blue" style="width:{{ $percentage }}%"></div>
                            </div>
                            <div class="lp-bar-info">
                                <div class="lp-bar-val">Rp {{ number_format($sp->omzet/1000, 0) }}k</div>
                                <div class="lp-bar-sub">{{ $sp->total_penjualan }} trx</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="lp-empty">Tidak ada data sales</div>
                    @endif
                </div>
            </div>

            {{-- Product Performance --}}
            <div class="lp-prod">
                <div class="lp-prod-hdr">
                    <div class="lp-prod-title">Performa Produk</div>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:separate; border-spacing:0;">
                        <thead class="lp-tbl-head">
                            <tr>
                                <th style="text-align:left;">Produk</th>
                                <th style="text-align:center;">Terjual</th>
                                <th style="text-align:right;">Omzet</th>
                                <th style="text-align:left;">Grafik</th>
                            </tr>
                        </thead>
                        <tbody class="lp-tbl-body">
                            @foreach($productPerformance as $pp)
                            @php
                                $maxTerjual = $productPerformance->max('terjual') ?: 1;
                                $percentage = $maxTerjual > 0 ? ($pp->terjual / $maxTerjual) * 100 : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="lp-prod-name">{{ $pp->nama }}</div>
                                    <div class="lp-prod-type">{{ $pp->jenis }}</div>
                                </td>
                                <td style="text-align:center;">
                                    <span class="lp-vol amber">{{ $pp->terjual }} {{ $pp->satuan }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <span style="font-family:'JetBrains Mono',monospace; font-size:0.8125rem; font-weight:600; color:#1e293b;">Rp {{ number_format($pp->omzet, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <div class="lp-progress">
                                        <div class="lp-progress-fill" style="width:{{ $percentage }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Setoran & Hutang Summary --}}
            <div class="lp-summary">
                {{-- Setoran Status --}}
                <div class="lp-summary-card">
                    <div class="lp-summary-title">Status Setoran</div>
                    @if($setoranSummary->count() > 0)
                        @foreach($setoranSummary as $ss)
                        <div class="lp-setoran">
                            <div class="lp-setoran-left">
                                <span class="lp-setoran-badge {{ $ss->status }}">{{ ucfirst($ss->status) }}</span>
                                <span class="lp-setoran-count">{{ $ss->jumlah }} setoran</span>
                            </div>
                            <span class="lp-setoran-total">Rp {{ number_format($ss->total, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="lp-empty">Tidak ada data setoran</div>
                    @endif
                </div>

                {{-- Hutang Summary --}}
                <div class="lp-summary-card">
                    <div class="lp-summary-title">Ringkasan Hutang</div>
                    <div class="lp-hutang">
                        <div class="lp-hutang-item red">
                            <div class="lp-hutang-val red">Rp {{ number_format($hutangSummary['total_hutang']/1000000, 1) }}M</div>
                            <div class="lp-hutang-lbl">Total Piutang</div>
                        </div>
                        <div class="lp-hutang-item amber">
                            <div class="lp-hutang-val amber">{{ $hutangSummary['jumlah_pelanggan'] }}</div>
                            <div class="lp-hutang-lbl">Pelanggan Hutang</div>
                        </div>
                        <div class="lp-hutang-item blue">
                            <div class="lp-hutang-val blue">Rp {{ number_format($hutangSummary['hutang_baru']/1000000, 1) }}M</div>
                            <div class="lp-hutang-lbl">Hutang Baru</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>

        {{-- ========== PRINT REPORT ========== --}}
        <div class="print-only pr">
            {{-- Header --}}
            <div class="pr-hdr">
                <div class="pr-hdr-l">
                    <div class="pr-company">DOD POS</div>
                    <div class="pr-title">Laporan Penjualan Mineral</div>
                    <div class="pr-period">{{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('D MMMM YYYY') }} &mdash; {{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('D MMMM YYYY') }}</div>
                </div>
                <div class="pr-hdr-r">
                    <div>Dicetak: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB</div>
                </div>
            </div>

            {{-- KPI Summary --}}
            <div class="pr-kpis">
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Total Penjualan</div>
                    <div class="pr-kpi-val">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Jumlah Transaksi</div>
                    <div class="pr-kpi-val">{{ number_format($summary['jumlah_transaksi']) }}</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Total Tunai</div>
                    <div class="pr-kpi-val">Rp {{ number_format($summary['total_tunai'], 0, ',', '.') }}</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Total Transfer</div>
                    <div class="pr-kpi-val">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</div>
                </div>
                <div class="pr-kpi">
                    <div class="pr-kpi-lbl">Total Hutang</div>
                    <div class="pr-kpi-val">Rp {{ number_format($summary['total_hutang'], 0, ',', '.') }}</div>
                </div>
            </div>

            {{-- Daily Sales Chart --}}
            @if($dailyData->count() > 0)
            @php $maxDaily = $dailyData->max('total') ?: 1; @endphp
            <div class="pr-section">
                <div class="pr-section-title">Grafik Penjualan Harian</div>
                @foreach($dailyData as $d)
                @php $pct = ($d->total / $maxDaily) * 100; @endphp
                <div class="pr-bar-row">
                    <div class="pr-bar-lbl">{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m') }}</div>
                    <div class="pr-bar-track"><div class="pr-bar-fill" style="width:{{ $pct }}%;background:#3b82f6;"></div></div>
                    <div class="pr-bar-val">Rp {{ number_format($d->total, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="pr-cols">
                {{-- Sales Performance --}}
                <div class="pr-section">
                    <div class="pr-section-title">Performa Sales</div>
                    <table class="pr-tbl">
                        <thead><tr><th>#</th><th>Sales</th><th class="text-center">Transaksi</th><th class="text-right">Omzet</th></tr></thead>
                        <tbody>
                        @forelse($salesPerformance as $i => $sp)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>
                                <span class="pr-sales-av" style="background:{{ ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981','#06b6d4'][$i % 6] }};">{{ substr($sp->nama, 0, 1) }}</span>
                                {{ $sp->nama }}
                            </td>
                            <td class="text-center">{{ $sp->total_penjualan }}</td>
                            <td class="text-right">Rp {{ number_format($sp->omzet ?? 0, 0, ',', '.') }}</td>
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
                        <thead><tr><th>#</th><th>Produk</th><th class="text-center">Terjual</th><th class="text-right">Omzet</th></tr></thead>
                        <tbody>
                        @forelse($productPerformance as $i => $pp)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $pp->nama }}<br><span style="font-size:6pt;color:#94a3b8;">{{ $pp->kategori ?? '' }}</span></td>
                            <td class="text-center">{{ $pp->terjual }}</td>
                            <td class="text-right">Rp {{ number_format($pp->omzet ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center" style="color:#94a3b8;padding:12px;">Tidak ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Setoran & Hutang Summary --}}
            <div class="pr-cols" style="margin-top:14px;">
                <div class="pr-section">
                    <div class="pr-section-title">Ringkasan Setoran</div>
                    @forelse($setoranSummary as $ss)
                    @php
                        $bg = match($ss->status) { 'confirmed'=>'#dcfce7;color:#166534', 'pending'=>'#fef3c7;color:#92400e', 'rejected'=>'#fef2f2;color:#991b1b', default=>'#f1f5f9;color:#475569' };
                        $label = match($ss->status) { 'confirmed'=>'Dikonfirmasi', 'pending'=>'Pending', 'rejected'=>'Ditolak', default=>ucfirst($ss->status) };
                    @endphp
                    <div class="pr-box">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <span class="pr-badge" style="background:{{ $bg }};">{{ $label }}</span>
                                <span style="font-size:8pt;margin-left:4px;">{{ $ss->jumlah }} setoran</span>
                            </div>
                            <div style="font-size:10pt;font-weight:700;">Rp {{ number_format($ss->total ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @empty
                    <div style="font-size:8pt;color:#94a3b8;text-align:center;padding:12px;">Tidak ada data setoran</div>
                    @endforelse
                </div>

                <div class="pr-section">
                    <div class="pr-section-title">Ringkasan Hutang</div>
                    <div class="pr-box" style="background:#fef2f2;">
                        <div class="pr-box-title" style="color:#991b1b;">Total Hutang Aktif</div>
                        <div class="pr-big-val" style="color:#dc2626;">Rp {{ number_format($hutangSummary['total_hutang'], 0, ',', '.') }}</div>
                    </div>
                    <div class="pr-box">
                        <div class="pr-box-title">Pelanggan dengan Hutang</div>
                        <div class="pr-big-val">{{ $hutangSummary['jumlah_pelanggan'] }}</div>
                        <div class="pr-sub-val">pelanggan</div>
                    </div>
                    <div class="pr-box">
                        <div class="pr-box-title">Hutang Baru (Periode Ini)</div>
                        <div class="pr-big-val" style="color:#1e40af;">Rp {{ number_format($hutangSummary['hutang_baru'], 0, ',', '.') }}</div>
                    </div>
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
