<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .db-page { max-width:82rem; margin:0 auto; padding:0 1rem; font-family:'Plus Jakarta Sans',sans-serif; }

        /* ── Header Bar ── */
        .db-bar {
            display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
            margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid #f1f5f9;
        }
        .db-bar-left { display:flex; align-items:center; gap:1rem; }
        .db-bar-icon {
            width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0;
            background:linear-gradient(135deg,#2563eb,#1d4ed8);
            box-shadow:0 8px 24px rgba(37,99,235,0.25);
        }
        .db-bar-title { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; line-height:1.2; }
        .db-bar-sub { font-size:0.8125rem; color:#64748b; margin-top:2px; }
        .db-bar-date {
            display:inline-flex; align-items:center; gap:0.375rem;
            background:#eff6ff; color:#2563eb; padding:0.375rem 0.875rem; border-radius:99px;
            font-size:0.8125rem; font-weight:600; border:1px solid #bfdbfe;
        }
        .db-bar-actions { display:flex; gap:0.5rem; flex-wrap:wrap; }
        .db-bar-btn {
            display:inline-flex; align-items:center; gap:0.375rem;
            padding:0.5rem 1rem; border-radius:10px;
            font-size:0.8125rem; font-weight:600; text-decoration:none; transition:all 0.25s;
            border:none; cursor:pointer; font-family:inherit;
        }
        .db-bar-btn.primary {
            background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff;
            box-shadow:0 4px 12px rgba(37,99,235,0.3);
        }
        .db-bar-btn.primary:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(37,99,235,0.4); }
        .db-bar-btn.ghost { background:#f8fafc; color:#475569; border:1px solid #e2e8f0; }
        .db-bar-btn.ghost:hover { background:#f1f5f9; transform:translateY(-1px); }

        /* ── KPI Cards ── */
        .db-kpis { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
        .db-kpi {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.25rem 1.375rem;
            transition:all 0.3s; position:relative; overflow:hidden;
        }
        .db-kpi::before { content:''; position:absolute; top:0; left:0; bottom:0; width:4px; }
        .db-kpi:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,0.08); border-color:transparent; }
        .db-kpi.blue::before   { background:linear-gradient(180deg,#3b82f6,#2563eb); }
        .db-kpi.green::before  { background:linear-gradient(180deg,#10b981,#059669); }
        .db-kpi.amber::before  { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .db-kpi.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .db-kpi-top { display:flex; align-items:flex-start; justify-content:space-between; }
        .db-kpi-lbl { font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; }
        .db-kpi-val { font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; line-height:1.1; margin-top:0.625rem; }
        .db-kpi-val.blue   { color:#1d4ed8; }
        .db-kpi-val.green  { color:#059669; }
        .db-kpi-val.amber  { color:#b45309; }
        .db-kpi-val.purple { color:#6d28d9; }
        .db-kpi-val .unit { font-size:0.875rem; font-weight:600; color:#94a3b8; margin-left:2px; }
        .db-kpi-foot { font-size:0.72rem; color:#94a3b8; margin-top:0.5rem; display:flex; align-items:center; gap:0.25rem; }
        .db-kpi-ico {
            width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.25rem; flex-shrink:0;
        }
        .db-kpi-ico.blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .db-kpi-ico.green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .db-kpi-ico.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .db-kpi-ico.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }

        /* ── Secondary Row ── */
        .db-sec { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
        .db-sec-card {
            background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1.125rem 1.25rem;
            display:flex; align-items:center; gap:1rem; transition:all 0.25s;
        }
        .db-sec-card:hover { box-shadow:0 6px 20px rgba(0,0,0,0.05); transform:translateY(-2px); }
        .db-sec-ico {
            width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.25rem; flex-shrink:0;
        }
        .db-sec-ico.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .db-sec-ico.rose { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .db-sec-ico.teal { background:linear-gradient(135deg,#f0fdfa,#ccfbf1); }
        .db-sec-lbl { font-size:0.6875rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; }
        .db-sec-val { font-size:1.125rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; margin-top:2px; }
        .db-sec-sub { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }
        .db-sec-sub.danger { color:#dc2626; font-weight:700; }

        /* ── Panel ── */
        .db-panel {
            background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;
            transition:all 0.25s;
        }
        .db-panel:hover { box-shadow:0 8px 30px rgba(0,0,0,0.06); }
        .db-panel-hdr {
            display:flex; align-items:center; justify-content:space-between;
            padding:1rem 1.375rem; border-bottom:1px solid #f1f5f9;
            background:linear-gradient(180deg,#f8fafc,#fff);
        }
        .db-panel-title {
            display:flex; align-items:center; gap:0.5rem;
            font-size:0.9375rem; font-weight:700; color:#1e293b;
        }
        .db-panel-title::before {
            content:''; width:4px; height:18px; border-radius:2px;
            background:linear-gradient(180deg,#3b82f6,#2563eb);
        }
        .db-panel-title.purple::before { background:linear-gradient(180deg,#8b5cf6,#7c3aed); }
        .db-panel-title.amber::before { background:linear-gradient(180deg,#f59e0b,#d97706); }
        .db-panel-title.green::before { background:linear-gradient(180deg,#10b981,#059669); }
        .db-panel-badge {
            padding:0.25rem 0.625rem; border-radius:99px; font-size:0.6875rem; font-weight:700;
        }
        .db-panel-badge.amber { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
        .db-panel-badge.purple { background:#ede9fe; color:#6d28d9; border:1px solid #ddd6fe; }
        .db-panel-body { padding:1rem 1.375rem; }
        .db-panel-foot { padding:0.75rem 1.375rem; border-top:1px solid #f1f5f9; text-align:center; }
        .db-panel-link {
            font-size:0.8125rem; font-weight:600; color:#2563eb; text-decoration:none;
            display:inline-flex; align-items:center; gap:0.375rem; transition:color 0.2s;
        }
        .db-panel-link:hover { color:#1d4ed8; }

        /* ── Chart ── */
        .db-chart { position:relative; height:220px; }
        .db-chart canvas { width:100%!important; height:100%!important; }

        /* ── Pending Items ── */
        .db-pend { display:flex; align-items:center; justify-content:space-between; padding:0.75rem 0; border-bottom:1px solid #f8fafc; }
        .db-pend:last-child { border-bottom:none; }
        .db-pend-left { display:flex; align-items:center; gap:0.75rem; }
        .db-pend-av {
            width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:0.875rem; font-weight:700; flex-shrink:0;
            background:linear-gradient(135deg,#eff6ff,#dbeafe); color:#2563eb; border:1.5px solid #bfdbfe;
        }
        .db-pend-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .db-pend-date { font-size:0.6875rem; color:#94a3b8; margin-top:1px; }
        .db-pend-amt { font-size:0.875rem; font-weight:700; color:#0f172a; font-family:'JetBrains Mono',monospace; }
        .db-pend-badge {
            display:inline-flex; padding:0.1875rem 0.5rem; border-radius:99px;
            font-size:0.625rem; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a; margin-top:2px;
        }

        /* ── Rank Items ── */
        .db-rank { display:flex; align-items:center; gap:0.75rem; padding:0.625rem 0; border-bottom:1px solid #f8fafc; }
        .db-rank:last-child { border-bottom:none; }
        .db-rank-num {
            width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center;
            font-size:0.75rem; font-weight:800; flex-shrink:0;
        }
        .db-rank-num.gold   { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; }
        .db-rank-num.silver { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); color:#475569; }
        .db-rank-num.bronze { background:linear-gradient(135deg,#fff7ed,#ffedd5); color:#c2410c; }
        .db-rank-num.normal { background:#f8fafc; color:#94a3b8; }
        .db-rank-info { flex:1; min-width:0; }
        .db-rank-name { font-size:0.8125rem; font-weight:600; color:#1e293b; }
        .db-rank-bar { height:4px; border-radius:99px; background:#f1f5f9; margin-top:5px; overflow:hidden; }
        .db-rank-bar-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#3b82f6,#2563eb); transition:width 0.6s ease; }
        .db-rank-val {
            font-size:0.8125rem; font-weight:700; color:#0f172a; flex-shrink:0;
            text-align:right; min-width:80px; font-family:'JetBrains Mono',monospace;
        }

        /* ── Master Data Grid ── */
        .db-mini-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:0.75rem; }
        .db-mini {
            padding:1rem; border-radius:12px; border:1px solid #f1f5f9; text-align:center; transition:all 0.2s;
        }
        .db-mini:hover { border-color:#dbeafe; background:#f8faff; }
        .db-mini-val { font-size:1.5rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; }
        .db-mini-lbl { font-size:0.6875rem; font-weight:600; color:#94a3b8; margin-top:2px; text-transform:uppercase; letter-spacing:0.04em; }
        .db-mini.warn { border-color:#fecaca; background:#fff5f5; }
        .db-mini.warn .db-mini-val { color:#dc2626; }

        /* ── Empty State ── */
        .db-empty { text-align:center; padding:2.5rem 1rem; }
        .db-empty-ico {
            width:64px; height:64px; margin:0 auto 1rem; border-radius:50%;
            background:linear-gradient(135deg,#f1f5f9,#e2e8f0); display:flex; align-items:center; justify-content:center;
        }
        .db-empty-title { font-size:0.9375rem; font-weight:700; color:#475569; }
        .db-empty-sub { font-size:0.8125rem; color:#94a3b8; margin-top:0.25rem; }

        /* ── Grid Layouts ── */
        .db-grid2 { display:grid; grid-template-columns:3fr 2fr; gap:1.25rem; margin-bottom:1.5rem; }
        .db-grid2-eq { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }

        @media(max-width:1024px) {
            .db-kpis { grid-template-columns:repeat(2,1fr); }
            .db-sec { grid-template-columns:1fr; }
            .db-grid2, .db-grid2-eq { grid-template-columns:1fr; }
        }
        @media(max-width:640px) {
            .db-kpis { grid-template-columns:1fr; }
            .db-bar-title { font-size:1.25rem; }
            .db-mini-grid { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="py-4">
        <div class="db-page">

            {{-- Header Bar --}}
            <div class="db-bar">
                <div class="db-bar-left">
                    <div class="db-bar-icon">📊</div>
                    <div>
                        <div class="db-bar-title">Dashboard Mineral</div>
                        <div class="db-bar-sub">Monitoring penjualan & distribusi harian</div>
                    </div>
                </div>
                <div class="db-bar-date">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
                <div class="db-bar-actions">
                    <a href="{{ route('mineral.penjualan.create') }}" class="db-bar-btn primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Penjualan Baru
                    </a>
                    <a href="{{ route('mineral.setoran.index') }}" class="db-bar-btn ghost">Setoran</a>
                    <a href="{{ route('mineral.laporan') }}" class="db-bar-btn ghost">Laporan</a>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="db-kpis">
                <div class="db-kpi blue">
                    <div class="db-kpi-top">
                        <div>
                            <div class="db-kpi-lbl">Penjualan Hari Ini</div>
                            <div class="db-kpi-val blue">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</div>
                            <div class="db-kpi-foot">{{ $stats['transaksi_hari_ini'] }} transaksi</div>
                        </div>
                        <div class="db-kpi-ico blue">💰</div>
                    </div>
                </div>
                <div class="db-kpi green">
                    <div class="db-kpi-top">
                        <div>
                            <div class="db-kpi-lbl">Setoran Terverifikasi</div>
                            <div class="db-kpi-val green">Rp {{ number_format($stats['setoran_hari_ini'], 0, ',', '.') }}</div>
                            <div class="db-kpi-foot">Hari ini</div>
                        </div>
                        <div class="db-kpi-ico green">✅</div>
                    </div>
                </div>
                <div class="db-kpi amber">
                    <div class="db-kpi-top">
                        <div>
                            <div class="db-kpi-lbl">Loading Hari Ini</div>
                            <div class="db-kpi-val amber">{{ number_format($stats['loading_hari_ini'], 0, ',', '.') }}<span class="unit">Liter</span></div>
                            <div class="db-kpi-foot">Volume BBM dimuat</div>
                        </div>
                        <div class="db-kpi-ico amber">🚛</div>
                    </div>
                </div>
                <div class="db-kpi purple">
                    <div class="db-kpi-top">
                        <div>
                            <div class="db-kpi-lbl">Penjualan Bulan Ini</div>
                            <div class="db-kpi-val purple">Rp {{ number_format($statsBulanIni['total_penjualan'], 0, ',', '.') }}</div>
                            <div class="db-kpi-foot">{{ $statsBulanIni['total_transaksi'] }} transaksi</div>
                        </div>
                        <div class="db-kpi-ico purple">📈</div>
                    </div>
                </div>
            </div>

            {{-- Secondary Stats --}}
            <div class="db-sec">
                <div class="db-sec-card">
                    <div class="db-sec-ico blue">📋</div>
                    <div>
                        <div class="db-sec-lbl">Hutang Baru Bulan Ini</div>
                        <div class="db-sec-val">Rp {{ number_format($statsBulanIni['total_hutang_baru'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="db-sec-card">
                    <div class="db-sec-ico rose">⚠️</div>
                    <div>
                        <div class="db-sec-lbl">Total Sisa Hutang</div>
                        <div class="db-sec-val">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                        <div class="db-sec-sub danger">{{ $hutangOverdue }} jatuh tempo</div>
                    </div>
                </div>
                <div class="db-sec-card">
                    <div class="db-sec-ico teal">🏪</div>
                    <div>
                        <div class="db-sec-lbl">Data Master</div>
                        <div class="db-sec-val">{{ $master['total_sales'] }} Sales · {{ $master['total_pelanggan'] }} Pelanggan</div>
                        <div class="db-sec-sub">{{ $master['total_produk'] }} produk @if($master['stok_rendah'] > 0)<span class="db-sec-sub danger"> · {{ $master['stok_rendah'] }} stok rendah</span>@endif</div>
                    </div>
                </div>
            </div>

            {{-- Chart + Setoran Pending --}}
            <div class="db-grid2">
                {{-- Chart --}}
                <div class="db-panel">
                    <div class="db-panel-hdr">
                        <div class="db-panel-title">📊 Penjualan 7 Hari Terakhir</div>
                    </div>
                    <div class="db-panel-body">
                        <div class="db-chart">
                            <canvas id="chart-penjualan"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Setoran Pending --}}
                <div class="db-panel">
                    <div class="db-panel-hdr">
                        <div class="db-panel-title amber">⏳ Setoran Pending</div>
                        @if($setoranPending->count() > 0)
                            <span class="db-panel-badge amber">{{ $setoranPending->count() }}</span>
                        @endif
                    </div>
                    <div class="db-panel-body" style="padding-top:0.5rem; padding-bottom:0.5rem;">
                        @if($setoranPending->count() > 0)
                            @foreach($setoranPending as $setoran)
                                <div class="db-pend">
                                    <div class="db-pend-left">
                                        <div class="db-pend-av">{{ substr($setoran->sales->nama, 0, 1) }}</div>
                                        <div>
                                            <div class="db-pend-name">{{ $setoran->sales->nama }}</div>
                                            <div class="db-pend-date">{{ $setoran->tanggal->format('d M Y') }} · {{ $setoran->jumlah_transaksi }} trx</div>
                                        </div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div class="db-pend-amt">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</div>
                                        <span class="db-pend-badge">Pending</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="db-empty">
                                <div class="db-empty-ico">
                                    <svg width="28" height="28" fill="none" stroke="#64748b" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="db-empty-title">Semua setoran terverifikasi</div>
                                <div class="db-empty-sub">Tidak ada setoran pending</div>
                            </div>
                        @endif
                    </div>
                    <div class="db-panel-foot">
                        <a href="{{ route('mineral.setoran.index') }}" class="db-panel-link">Lihat Semua Setoran <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            </div>

            {{-- Top Sales + Master Data --}}
            <div class="db-grid2-eq">
                {{-- Top Sales --}}
                <div class="db-panel">
                    <div class="db-panel-hdr">
                        <div class="db-panel-title purple">🏆 Top Sales Bulan Ini</div>
                        @if($topSales->count() > 0)
                            <span class="db-panel-badge purple">{{ $topSales->count() }} sales</span>
                        @endif
                    </div>
                    <div class="db-panel-body" style="padding-top:0.5rem; padding-bottom:0.5rem;">
                        @if($topSales->count() > 0)
                            @php $maxTotal = $topSales->max('penjualans_sum_total') ?: 1; @endphp
                            @foreach($topSales as $index => $sales)
                                @php
                                    $pct = round(($sales->penjualans_sum_total / $maxTotal) * 100);
                                    $rankClass = $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal'));
                                @endphp
                                <div class="db-rank">
                                    <div class="db-rank-num {{ $rankClass }}">{{ $index + 1 }}</div>
                                    <div class="db-rank-info">
                                        <div class="db-rank-name">{{ $sales->nama }}</div>
                                        <div class="db-rank-bar"><div class="db-rank-bar-fill" style="width:{{ $pct }}%;"></div></div>
                                    </div>
                                    <div class="db-rank-val">Rp {{ number_format($sales->penjualans_sum_total ?? 0, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        @else
                            <div class="db-empty">
                                <div class="db-empty-ico">
                                    <svg width="28" height="28" fill="none" stroke="#64748b" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="db-empty-title">Belum ada data penjualan</div>
                                <div class="db-empty-sub">Bulan ini</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Master Data Summary --}}
                <div class="db-panel">
                    <div class="db-panel-hdr">
                        <div class="db-panel-title green">📦 Ringkasan Data</div>
                    </div>
                    <div class="db-panel-body">
                        <div class="db-mini-grid">
                            <div class="db-mini">
                                <div class="db-mini-val">{{ $master['total_sales'] }}</div>
                                <div class="db-mini-lbl">Sales Aktif</div>
                            </div>
                            <div class="db-mini">
                                <div class="db-mini-val">{{ $master['total_pelanggan'] }}</div>
                                <div class="db-mini-lbl">Pelanggan</div>
                            </div>
                            <div class="db-mini">
                                <div class="db-mini-val">{{ $master['total_produk'] }}</div>
                                <div class="db-mini-lbl">Produk</div>
                            </div>
                            <div class="db-mini {{ $master['stok_rendah'] > 0 ? 'warn' : '' }}">
                                <div class="db-mini-val">{{ $master['stok_rendah'] }}</div>
                                <div class="db-mini-lbl">Stok Rendah</div>
                            </div>
                        </div>
                    </div>
                    <div class="db-panel-foot">
                        <a href="{{ route('mineral.hutang.index') }}" class="db-panel-link">Kelola Hutang <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($penjualanChart);
        const labels = chartData.map(d => d.tanggal);
        const values = chartData.map(d => d.total);

        const ctx = document.getElementById('chart-penjualan').getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.15)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(ctx) {
                                return 'Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#94a3b8' },
                        border: { display: false }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                            color: '#94a3b8',
                            callback: function(v) {
                                if (v >= 1000000) return 'Rp ' + (v/1000000).toFixed(1) + 'jt';
                                if (v >= 1000) return 'Rp ' + (v/1000).toFixed(0) + 'rb';
                                return 'Rp ' + v;
                            }
                        },
                        border: { display: false }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
