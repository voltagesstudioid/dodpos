<x-app-layout>
    @push('styles')
    <style>
        .lr { max-width:84rem; margin:0 auto; padding:1.25rem 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .lr-hdr { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .lr-hdr-l { display:flex; align-items:center; gap:1rem; }
        .lr-hdr-ico {
            width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#f97316,#ea580c);
            box-shadow:0 8px 24px rgba(234,88,12,0.3);
        }
        .lr-hdr-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .lr-hdr-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }

        .lr-tabs { display:flex; gap:0.375rem; background:#f1f5f9; padding:0.25rem; border-radius:12px; }
        .lr-tab {
            padding:0.5rem 1rem; border-radius:9px; font-size:0.8125rem; font-weight:600;
            color:#64748b; cursor:pointer; transition:all 0.2s; border:none; font-family:inherit;
            text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .lr-tab.active { background:#fff; color:#0f172a; box-shadow:0 1px 3px rgba(0,0,0,0.08); }
        .lr-tab:hover:not(.active) { color:#475569; }

        .lr-filter {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.125rem 1.375rem;
            margin-bottom:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .lr-ff { display:flex; flex-wrap:wrap; align-items:flex-end; gap:0.75rem; }
        .lr-ff-fld { min-width:140px; flex:1; }
        .lr-flbl { display:block; font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; margin-bottom:0.375rem; }
        .lr-finput {
            width:100%; padding:0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s; font-family:inherit; box-sizing:border-box;
        }
        .lr-finput:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .lr-fsel {
            padding:0.625rem 2.25rem 0.625rem 0.875rem; border-radius:10px; border:1.5px solid #e2e8f0;
            background:#fff; font-size:0.8125rem; color:#1e293b; outline:none; transition:all 0.2s;
            font-family:inherit; cursor:pointer; appearance:none; width:100%; box-sizing:border-box;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 0.5rem center; background-size:16px;
        }
        .lr-fsel:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.12); }
        .lr-ff-acts { display:flex; gap:0.5rem; align-items:flex-end; }
        .lr-btn-f {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:none; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; box-shadow:0 4px 12px rgba(234,88,12,0.25);
        }
        .lr-btn-f:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(234,88,12,0.35); }
        .lr-btn-r {
            padding:0.625rem 1.25rem; border-radius:10px; font-size:0.8125rem; font-weight:600;
            border:1.5px solid #e2e8f0; cursor:pointer; transition:all 0.2s; font-family:inherit;
            background:#f8fafc; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;
        }
        .lr-btn-r:hover { background:#f1f5f9; border-color:#cbd5e1; color:#475569; }
        .lr-badge {
            font-size:0.75rem; color:#64748b; padding:0.5rem 0.875rem; background:#f8fafc; border-radius:8px;
            border:1px solid #e2e8f0; display:inline-flex; align-items:center; gap:0.5rem; white-space:nowrap;
        }

        .lr-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:0.75rem; margin-bottom:1.5rem; }
        .lr-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1rem 1.125rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .lr-kpi::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .lr-kpi:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.06); border-color:transparent; }
        .lr-kpi.orange::before { background:linear-gradient(90deg,#f97316,#ea580c); }
        .lr-kpi.emerald::before { background:linear-gradient(90deg,#10b981,#059669); }
        .lr-kpi.blue::before { background:linear-gradient(90deg,#3b82f6,#2563eb); }
        .lr-kpi.violet::before { background:linear-gradient(90deg,#8b5cf6,#7c3aed); }
        .lr-kpi.red::before { background:linear-gradient(90deg,#ef4444,#dc2626); }
        .lr-kpi-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.375rem; }
        .lr-kpi-lbl { font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .lr-kpi-ico { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1rem; }
        .lr-kpi-ico.orange { background:linear-gradient(135deg,#fff7ed,#ffedd5); }
        .lr-kpi-ico.emerald { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .lr-kpi-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .lr-kpi-ico.violet { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
        .lr-kpi-ico.red { background:linear-gradient(135deg,#fef2f2,#fee2e2); }
        .lr-kpi-val { font-size:1.25rem; font-weight:800; letter-spacing:-0.02em; line-height:1; color:#0f172a; }
        .lr-kpi-val.sm { font-size:1rem; }
        .lr-kpi-foot { font-size:0.65rem; color:#94a3b8; margin-top:0.25rem; }

        .lr-panel {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            box-shadow:0 1px 3px rgba(0,0,0,0.04); transition:all 0.25s;
        }
        .lr-panel-hdr {
            display:flex; align-items:center; justify-content:space-between;
            padding:1rem 1.375rem; border-bottom:1px solid #f1f5f9;
        }
        .lr-panel-title { display:flex; align-items:center; gap:0.625rem; font-size:0.875rem; font-weight:700; color:#0f172a; }
        .lr-panel-title-ico {
            width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.875rem;
        }
        .lr-panel-body { padding:1.25rem 1.375rem; }
        .lr-panel-body.nopad { padding:0; }

        .lr-chart { display:flex; align-items:flex-end; gap:0.375rem; height:140px; padding-top:0.5rem; }
        .lr-chart-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:0.375rem; height:100%; }
        .lr-chart-track { flex:1; width:100%; display:flex; align-items:flex-end; justify-content:center; }
        .lr-chart-bar {
            width:100%; max-width:36px; border-radius:5px 5px 2px 2px;
            background:linear-gradient(180deg,#f97316,#ea580c);
            transition:all 0.4s; position:relative; min-height:3px; opacity:0.85;
        }
        .lr-chart-bar:hover { opacity:1; transform:scaleY(1.05); box-shadow:0 -3px 10px rgba(249,115,22,0.3); }
        .lr-chart-val {
            position:absolute; top:-18px; left:50%; transform:translateX(-50%);
            font-size:0.5625rem; font-weight:700; color:#64748b; white-space:nowrap; opacity:0; transition:opacity 0.2s;
        }
        .lr-chart-bar:hover .lr-chart-val { opacity:1; }
        .lr-chart-lbl { font-size:0.5625rem; font-weight:600; color:#94a3b8; }

        .lr-pay-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; }
        .lr-pay-item {
            padding:0.875rem 1rem; border-radius:12px; border:1px solid #f1f5f9; text-align:center;
            transition:all 0.2s;
        }
        .lr-pay-item:hover { border-color:#e2e8f0; }
        .lr-pay-item.primary { border-color:#fed7aa; background:#fff7ed; }
        .lr-pay-lbl { font-size:0.675rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:0.25rem; }
        .lr-pay-cnt { font-size:1.25rem; font-weight:800; color:#0f172a; }
        .lr-pay-val { font-size:0.75rem; color:#64748b; font-weight:600; margin-top:0.125rem; }

        .lr-leader { display:flex; flex-direction:column; gap:0.375rem; }
        .lr-leader-item {
            display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0.875rem;
            border-radius:10px; transition:all 0.2s; border:1px solid transparent;
        }
        .lr-leader-item:hover { background:#f8fafc; border-color:#f1f5f9; }
        .lr-rank {
            width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center;
            font-size:0.75rem; font-weight:800; flex-shrink:0;
        }
        .lr-rank.gold { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; }
        .lr-rank.silver { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); color:#475569; }
        .lr-rank.bronze { background:linear-gradient(135deg,#ffedd5,#fed7aa); color:#9a3412; }
        .lr-rank.normal { background:#f8fafc; color:#94a3b8; }
        .lr-leader-av {
            width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            font-size:0.8125rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); color:#c2410c; border:1px solid #fed7aa;
        }
        .lr-leader-info { flex:1; min-width:0; }
        .lr-leader-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .lr-leader-sub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }
        .lr-leader-amt { font-size:0.875rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; white-space:nowrap; }
        .lr-pbar { flex:1; height:5px; background:#f1f5f9; border-radius:999px; overflow:hidden; min-width:30px; }
        .lr-pbar-fill { height:100%; border-radius:999px; background:linear-gradient(90deg,#f97316,#ea580c); transition:width 0.6s; }

        .lr-tbl { width:100%; border-collapse:separate; border-spacing:0; }
        .lr-tbl thead { background:linear-gradient(180deg,#fff7ed,#fef9ee); border-bottom:2px solid #fed7aa; }
        .lr-tbl th { padding:0.75rem 1rem; font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#9a3412; white-space:nowrap; text-align:left; }
        .lr-tbl td { padding:0.75rem 1rem; border-bottom:1px solid #fef9ee; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .lr-tbl tbody tr { transition:background 0.15s; }
        .lr-tbl tbody tr:last-child td { border-bottom:none; }
        .lr-tbl tbody tr:hover td { background:#fffbf3; }
        .lr-tbl .num { text-align:right; font-weight:600; }
        .lr-tbl .num.bold { font-weight:700; color:#0f172a; }

        .lr-quick { display:grid; grid-template-columns:repeat(2,1fr); gap:0.75rem; }
        .lr-qlink {
            display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; border-radius:12px;
            border:1px solid #e2e8f0; text-decoration:none; transition:all 0.2s; background:#f8fafc;
        }
        .lr-qlink:hover { border-color:#fed7aa; background:#fff7ed; }
        .lr-qico {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0;
        }
        .lr-qname { font-size:0.8125rem; font-weight:700; color:#0f172a; }
        .lr-qsub { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }

        .lr-empty { text-align:center; padding:2.5rem 1rem; }
        .lr-empty-ico {
            width:64px; height:64px; margin:0 auto 0.75rem; border-radius:50%;
            background:linear-gradient(135deg,#fff7ed,#ffedd5); display:flex; align-items:center; justify-content:center;
        }
        .lr-empty-title { font-size:0.9375rem; font-weight:700; color:#475569; margin-bottom:0.25rem; }
        .lr-empty-sub { font-size:0.8125rem; color:#94a3b8; }

        .screen-only { display:block; }
        .print-only { display:none !important; }

        @media(max-width:1024px){.lr-kpis{grid-template-columns:repeat(3,1fr)}.lr-quick{grid-template-columns:1fr}}
        @media(max-width:768px){.lr-hdr-title{font-size:1.25rem}.lr-hdr-ico{width:44px;height:44px;font-size:1.25rem}}
        @media(max-width:640px){
            .lr-kpis{grid-template-columns:repeat(2,1fr)}.lr-ff-fld{min-width:100%}.lr-ff-acts{width:100%}.lr-btn-f,.lr-btn-r{flex:1;justify-content:center}.lr-pay-grid{grid-template-columns:1fr}.lr-tabs{gap:0.25rem}.lr-tab{padding:0.375rem 0.625rem;font-size:0.75rem}
        }
        @media(max-width:480px){
            .lr{padding:1rem 0.75rem 2rem}.lr-hdr-title{font-size:1.125rem}
            .lr-hdr{flex-direction:column;align-items:stretch}.lr-hdr-l{flex-direction:column;align-items:flex-start;gap:0.5rem}
            .lr-kpis{grid-template-columns:1fr;gap:0.5rem}
        }

        /* Print styles */
        @media print {
            .screen-only { display:none !important; }
            .print-only { display:block !important; }
            .sidebar,.sidebar-overlay,.topbar,#sidebar,#sidebar-overlay { display:none !important; }
            body { background:#fff !important; margin:0 !important; padding:0 !important; }
            .page-content { margin-left:0 !important; padding:0 !important; width:100% !important; }
            .py-4 { padding:0 !important; }

            .pr { font-family:'Plus Jakarta Sans',sans-serif; font-size:8pt; color:#1e293b; max-width:100%; }
            .pr-hdr { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:3px solid #1e293b; padding-bottom:10px; margin-bottom:14px; }
            .pr-company { font-size:16pt; font-weight:800; color:#1e293b; letter-spacing:-0.02em; }
            .pr-title { font-size:11pt; font-weight:600; color:#475569; margin-top:2px; }
            .pr-period { font-size:8pt; color:#64748b; margin-top:2px; }
            .pr-hdr-r { text-align:right; font-size:7pt; color:#94a3b8; }

            .pr-section { margin-bottom:14px; }
            .pr-section-title { font-size:9pt; font-weight:700; color:#1e293b; border-bottom:1px solid #cbd5e1; padding-bottom:4px; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; }

            .pr-kpis { display:grid; grid-template-columns:repeat(5,1fr); gap:6px; margin-bottom:14px; }
            .pr-kpi { border:1px solid #e2e8f0; border-radius:4px; padding:8px 10px; }
            .pr-kpi-lbl { font-size:6.5pt; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }
            .pr-kpi-val { font-size:12pt; font-weight:800; color:#1e293b; margin-top:2px; }
            .pr-kpi-sub { font-size:6.5pt; color:#94a3b8; margin-top:1px; }

            .pr-tbl { width:100%; border-collapse:collapse; font-size:8pt; }
            .pr-tbl thead th { background:#f1f5f9; color:#475569; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; font-size:6.5pt; padding:6px 8px; border-bottom:2px solid #cbd5e1; text-align:left; }
            .pr-tbl tbody td { padding:5px 8px; border-bottom:1px solid #e2e8f0; }
            .pr-tbl tfoot td { padding:6px 8px; font-weight:700; border-top:2px solid #cbd5e1; background:#f8fafc; }
            .pr-tbl .text-right { text-align:right; }
            .pr-tbl .text-center { text-align:center; }

            .pr-cols { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

            .pr-bar-row { display:flex; align-items:center; gap:6px; margin-bottom:3px; font-size:7pt; }
            .pr-bar-lbl { width:42px; text-align:right; color:#64748b; font-weight:600; flex-shrink:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .pr-bar-track { flex:1; height:10px; background:#e2e8f0; border-radius:2px; overflow:hidden; }
            .pr-bar-fill { height:100%; border-radius:2px; print-color-adjust:exact !important; -webkit-print-color-adjust:exact !important; }
            .pr-bar-val { width:70px; font-weight:700; flex-shrink:0; }

            .pr-pay-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:6px; margin-bottom:14px; }
            .pr-pay-item { border:1px solid #e2e8f0; border-radius:4px; padding:8px; text-align:center; }
            .pr-pay-lbl { font-size:6.5pt; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; }
            .pr-pay-val { font-size:11pt; font-weight:800; color:#1e293b; margin-top:2px; }
            .pr-pay-sub { font-size:6.5pt; color:#94a3b8; margin-top:1px; }

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

    <div class="screen-only">
    <div class="lr">

        {{-- Header --}}
        <div class="lr-hdr">
            <div class="lr-hdr-l">
                <div class="lr-hdr-ico">📊</div>
                <div>
                    <div class="lr-hdr-title">Laporan Minyak</div>
                    <div class="lr-hdr-sub">Analisis penjualan, performa sales, dan tren produk</div>
                </div>
            </div>
            <div class="lr-tabs">
                <a href="{{ route('minyak.laporan', ['periode' => 'harian']) }}" class="lr-tab {{ $periode == 'harian' ? 'active' : '' }}">📅 Harian</a>
                <a href="{{ route('minyak.laporan', ['periode' => 'mingguan']) }}" class="lr-tab {{ $periode == 'mingguan' ? 'active' : '' }}">📊 Mingguan</a>
                <a href="{{ route('minyak.laporan', ['periode' => 'bulanan']) }}" class="lr-tab {{ $periode == 'bulanan' ? 'active' : '' }}">📈 Bulanan</a>
            </div>
        </div>

        {{-- Filter --}}
        <div class="lr-filter">
            <form method="GET" class="lr-ff">
                <div class="lr-ff-fld">
                    <label class="lr-flbl">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" value="{{ $dari->format('Y-m-d') }}" class="lr-finput">
                </div>
                <div class="lr-ff-fld">
                    <label class="lr-flbl">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" value="{{ $sampai->format('Y-m-d') }}" class="lr-finput">
                </div>
                <div class="lr-ff-fld">
                    <label class="lr-flbl">Sales</label>
                    <select name="sales_id" class="lr-fsel">
                        <option value="">Semua Sales</option>
                        @foreach($salesList as $s)
                            <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="periode" value="custom">
                <div class="lr-ff-acts">
                    <button type="submit" class="lr-btn-f">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Tampilkan
                    </button>
                    <a href="{{ route('minyak.laporan') }}" class="lr-btn-r">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                    <span class="lr-badge">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $dari->format('d M Y') }} — {{ $sampai->format('d M Y') }}
                    </span>
                </div>
            </form>
        </div>

        {{-- KPI --}}
        @php $rataTransaksi = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0; @endphp
        <div class="lr-kpis">
            <div class="lr-kpi orange">
                <div class="lr-kpi-top">
                    <span class="lr-kpi-lbl">Total Penjualan</span>
                    <div class="lr-kpi-ico orange">💰</div>
                </div>
                <div class="lr-kpi-val sm">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                <div class="lr-kpi-foot">Omzet periode ini</div>
            </div>
            <div class="lr-kpi emerald">
                <div class="lr-kpi-top">
                    <span class="lr-kpi-lbl">Total Transaksi</span>
                    <div class="lr-kpi-ico emerald">🧾</div>
                </div>
                <div class="lr-kpi-val">{{ number_format($totalTransaksi) }}</div>
                <div class="lr-kpi-foot">Rata-rata Rp {{ number_format($rataTransaksi, 0, ',', '.') }}/transaksi</div>
            </div>
            <div class="lr-kpi blue">
                <div class="lr-kpi-top">
                    <span class="lr-kpi-lbl">Volume Terjual</span>
                    <div class="lr-kpi-ico blue">🛢️</div>
                </div>
                <div class="lr-kpi-val">{{ number_format($totalVolume, 0, ',', '.') }} <span style="font-size:0.7rem;font-weight:600;color:#94a3b8;">L</span></div>
                <div class="lr-kpi-foot">Liter BBM terjual</div>
            </div>
            <div class="lr-kpi violet">
                <div class="lr-kpi-top">
                    <span class="lr-kpi-lbl">Pembayaran Tunai</span>
                    <div class="lr-kpi-ico violet">💵</div>
                </div>
                <div class="lr-kpi-val sm">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
                <div class="lr-kpi-foot">{{ $tipeBayarStats['tunai'] }} transaksi tunai</div>
            </div>
            <div class="lr-kpi red">
                <div class="lr-kpi-top">
                    <span class="lr-kpi-lbl">Piutang Baru</span>
                    <div class="lr-kpi-ico red">💳</div>
                </div>
                <div class="lr-kpi-val sm">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                <div class="lr-kpi-foot">{{ $tipeBayarStats['hutang'] }} transaksi hutang</div>
            </div>
        </div>

        {{-- Row 1: Chart + Payment --}}
        <div style="display:grid; grid-template-columns:2fr 1fr; gap:1rem; margin-bottom:1rem;">
            <div class="lr-panel">
                <div class="lr-panel-hdr">
                    <div class="lr-panel-title">
                        <div class="lr-panel-title-ico" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);">📈</div>
                        Tren Penjualan Harian
                    </div>
                    <span style="font-size:0.6875rem; color:#94a3b8;">{{ count($dailyTrend) }} hari</span>
                </div>
                <div class="lr-panel-body">
                    @if(count($dailyTrend) > 0)
                        @php $maxTrend = max(array_column($dailyTrend, 'total') ?: [0]); $maxTrend = max($maxTrend, 1); @endphp
                        <div class="lr-chart">
                            @foreach($dailyTrend as $d)
                                @php $h = max(($d['total'] / $maxTrend) * 100, 3); @endphp
                                <div class="lr-chart-wrap">
                                    <div class="lr-chart-track">
                                        <div class="lr-chart-bar" style="height:{{ $h }}%;">
                                            <span class="lr-chart-val">Rp {{ number_format($d['total'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="lr-chart-lbl">{{ $d['tanggal'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="lr-empty">
                            <div class="lr-empty-ico">
                                <svg width="28" height="28" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div class="lr-empty-title">Belum Ada Data Tren</div>
                            <div class="lr-empty-sub">Data penjualan akan ditampilkan di sini</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lr-panel">
                <div class="lr-panel-hdr">
                    <div class="lr-panel-title">
                        <div class="lr-panel-title-ico" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">💳</div>
                        Tipe Pembayaran
                    </div>
                </div>
                <div class="lr-panel-body">
                    <div class="lr-pay-grid">
                        <div class="lr-pay-item primary">
                            <div class="lr-pay-lbl">Tunai</div>
                            <div class="lr-pay-cnt">{{ $tipeBayarStats['tunai'] }}</div>
                            <div class="lr-pay-val">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
                        </div>
                        <div class="lr-pay-item">
                            <div class="lr-pay-lbl">Hutang</div>
                            <div class="lr-pay-cnt">{{ $tipeBayarStats['hutang'] }}</div>
                            <div class="lr-pay-val">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                        </div>
                        <div class="lr-pay-item">
                            <div class="lr-pay-lbl">Transfer</div>
                            <div class="lr-pay-cnt">{{ $tipeBayarStats['transfer'] }}</div>
                            <div class="lr-pay-val">—</div>
                        </div>
                    </div>
                    @php
                        $totalTipe = $tipeBayarStats['tunai'] + $tipeBayarStats['hutang'] + $tipeBayarStats['transfer'];
                    @endphp
                    @if($totalTipe > 0)
                    <div style="margin-top:0.75rem; display:flex; gap:0.375rem; height:6px;">
                        @php
                            $tPct = $tipeBayarStats['tunai'] / $totalTipe * 100;
                            $hPct = $tipeBayarStats['hutang'] / $totalTipe * 100;
                            $rPct = $tipeBayarStats['transfer'] / $totalTipe * 100;
                        @endphp
                        <div style="flex:{{ $tPct }}; background:#10b981; border-radius:999px;" title="Tunai {{ number_format($tPct,1) }}%"></div>
                        <div style="flex:{{ $hPct }}; background:#f59e0b; border-radius:999px;" title="Hutang {{ number_format($hPct,1) }}%"></div>
                        <div style="flex:{{ $rPct }}; background:#3b82f6; border-radius:999px;" title="Transfer {{ number_format($rPct,1) }}%"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Row 2: Sales + Product --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
            <div class="lr-panel">
                <div class="lr-panel-hdr">
                    <div class="lr-panel-title">
                        <div class="lr-panel-title-ico" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);">🏆</div>
                        Performa Sales
                    </div>
                    <span style="font-size:0.6875rem; color:#94a3b8;">{{ $salesPerformance->count() }} sales</span>
                </div>
                <div class="lr-panel-body">
                    @if($salesPerformance->count() > 0)
                        @php $maxSalesAmt = $salesPerformance->max('penjualans_sum_total') ?? 1; @endphp
                        <div class="lr-leader">
                            @foreach($salesPerformance as $i => $s)
                                @php
                                    $rc = $i == 0 ? 'gold' : ($i == 1 ? 'silver' : ($i == 2 ? 'bronze' : 'normal'));
                                    $bw = $maxSalesAmt > 0 ? (($s->penjualans_sum_total ?? 0) / $maxSalesAmt) * 100 : 0;
                                @endphp
                                <div class="lr-leader-item">
                                    <div class="lr-rank {{ $rc }}">{{ $i + 1 }}</div>
                                    <div class="lr-leader-av">{{ substr($s->nama, 0, 1) }}</div>
                                    <div class="lr-leader-info">
                                        <div class="lr-leader-name">{{ $s->nama }}</div>
                                        <div class="lr-leader-sub">{{ $s->penjualans_count }} transaksi</div>
                                    </div>
                                    <div class="lr-pbar" style="max-width:80px;">
                                        <div class="lr-pbar-fill" style="width:{{ $bw }}%;"></div>
                                    </div>
                                    <div class="lr-leader-amt">Rp {{ number_format($s->penjualans_sum_total ?? 0, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="lr-empty">
                            <div class="lr-empty-ico">
                                <svg width="28" height="28" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="lr-empty-title">Belum Ada Data Sales</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lr-panel">
                <div class="lr-panel-hdr">
                    <div class="lr-panel-title">
                        <div class="lr-panel-title-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">🛢️</div>
                        Performa Produk
                    </div>
                </div>
                <div class="lr-panel-body nopad">
                    @if($produkPerformance->where('penjualans_count', '>', 0)->count() > 0)
                        <div style="overflow-x:auto;">
                            <table class="lr-tbl">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="num">Volume (L)</th>
                                        <th class="num">Transaksi</th>
                                        <th class="num">Omzet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produkPerformance->where('penjualans_count', '>', 0) as $p)
                                        <tr>
                                            <td>
                                                <div style="font-weight:600; color:#1e293b;">{{ $p->nama }}</div>
                                                <div style="font-size:0.6875rem; color:#94a3b8;">{{ $p->jenis ?? '' }}</div>
                                            </td>
                                            <td class="num bold" style="color:#0284c7;">{{ number_format($p->penjualans_sum_jumlah ?? 0, 0, ',', '.') }}</td>
                                            <td class="num">{{ number_format($p->penjualans_count) }}</td>
                                            <td class="num bold">Rp {{ number_format($p->penjualans_sum_total ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="lr-empty">
                            <div class="lr-empty-ico">
                                <svg width="28" height="28" fill="none" stroke="#ea580c" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div class="lr-empty-title">Belum Ada Data Produk</div>
                            <div class="lr-empty-sub">Produk terjual akan muncul di sini</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Access --}}
        <div class="lr-panel">
            <div class="lr-panel-hdr">
                <div class="lr-panel-title">
                    <div class="lr-panel-title-ico" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">⚡</div>
                    Akses Cepat
                </div>
            </div>
            <div class="lr-panel-body">
                <div class="lr-quick">
                    <a href="{{ route('minyak.penjualan.index') }}" class="lr-qlink">
                        <div class="lr-qico" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);">🧾</div>
                        <div>
                            <div class="lr-qname">Data Penjualan</div>
                            <div class="lr-qsub">Semua transaksi penjualan</div>
                        </div>
                    </a>
                    <a href="{{ route('minyak.setoran.index') }}" class="lr-qlink">
                        <div class="lr-qico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);">💵</div>
                        <div>
                            <div class="lr-qname">Data Setoran</div>
                            <div class="lr-qsub">Verifikasi setoran sales</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
    </div>

    {{-- ========== PRINT ========== --}}
    <div class="print-only pr">
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

        <div class="pr-kpis">
            <div class="pr-kpi"><div class="pr-kpi-lbl">Total Penjualan</div><div class="pr-kpi-val">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div><div class="pr-kpi-sub">Omzet periode ini</div></div>
            <div class="pr-kpi"><div class="pr-kpi-lbl">Total Transaksi</div><div class="pr-kpi-val">{{ number_format($totalTransaksi) }}</div><div class="pr-kpi-sub">Jumlah faktur</div></div>
            <div class="pr-kpi"><div class="pr-kpi-lbl">Volume Terjual</div><div class="pr-kpi-val">{{ number_format($totalVolume, 0, ',', '.') }} L</div><div class="pr-kpi-sub">Liter BBM terjual</div></div>
            <div class="pr-kpi"><div class="pr-kpi-lbl">Pembayaran Tunai</div><div class="pr-kpi-val">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div><div class="pr-kpi-sub">Transaksi tunai</div></div>
            <div class="pr-kpi"><div class="pr-kpi-lbl">Piutang Baru</div><div class="pr-kpi-val">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div><div class="pr-kpi-sub">Hutang penjualan</div></div>
        </div>

        <div class="pr-section">
            <div class="pr-section-title">Tipe Pembayaran</div>
            <div class="pr-pay-grid">
                <div class="pr-pay-item"><div class="pr-pay-lbl">Tunai</div><div class="pr-pay-val">{{ $tipeBayarStats['tunai'] }}</div><div class="pr-pay-sub">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div></div>
                <div class="pr-pay-item"><div class="pr-pay-lbl">Hutang</div><div class="pr-pay-val">{{ $tipeBayarStats['hutang'] }}</div><div class="pr-pay-sub">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div></div>
                <div class="pr-pay-item"><div class="pr-pay-lbl">Transfer</div><div class="pr-pay-val">{{ $tipeBayarStats['transfer'] }}</div><div class="pr-pay-sub">&mdash;</div></div>
            </div>
        </div>

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

        <div class="pr-cols">
            <div class="pr-section">
                <div class="pr-section-title">Performa Sales ({{ $salesPerformance->count() }})</div>
                <table class="pr-tbl">
                    <thead><tr><th>#</th><th>Sales</th><th class="text-center">Transaksi</th><th class="text-right">Omzet</th></tr></thead>
                    <tbody>
                    @forelse($salesPerformance as $i => $s)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $s->nama }}</td>
                        <td class="text-center">{{ $s->penjualans_count }}</td>
                        <td class="text-right">Rp {{ number_format($s->penjualans_sum_total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center" style="color:#94a3b8;padding:12px;">Tidak ada data</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pr-section">
                <div class="pr-section-title">Performa Produk</div>
                <table class="pr-tbl">
                    <thead><tr><th>Produk</th><th class="text-right">Volume (L)</th><th class="text-right">Transaksi</th><th class="text-right">Omzet</th></tr></thead>
                    <tbody>
                    @forelse($produkPerformance->where('penjualans_count', '>', 0) as $p)
                    <tr>
                        <td>{{ $p->nama }}</td>
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

        <div class="pr-footer">
            <div class="pr-note">Laporan ini dicetak secara otomatis dari sistem DOD POS dan merupakan data yang sah.</div>
            <div class="pr-sign">
                <div class="pr-sign-line"></div>
                <div class="pr-sign-name">Supervisor</div>
                <div class="pr-sign-title">Penanggung Jawab</div>
            </div>
        </div>
    </div>
</x-app-layout>