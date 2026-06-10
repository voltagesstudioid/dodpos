<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .db{max-width:80rem;margin:0 auto;padding:1.25rem 1rem 3rem;font-family:'Plus Jakarta Sans',sans-serif}

        /* Hero */
        .db-hero{background:linear-gradient(135deg,#0f172a 0%,#1e293b 40%,#334155 100%);border-radius:20px;padding:2rem 2.25rem;margin-bottom:1.5rem;position:relative;overflow:hidden}
        .db-hero::before{content:'';position:absolute;top:-60%;right:-10%;width:500px;height:500px;background:radial-gradient(circle,rgba(245,158,11,.12) 0%,transparent 70%);border-radius:50%}
        .db-hero::after{content:'';position:absolute;bottom:-40%;left:5%;width:350px;height:350px;background:radial-gradient(circle,rgba(99,102,241,.08) 0%,transparent 70%);border-radius:50%}
        .db-hero-top{display:flex;align-items:flex-start;justify-content:space-between;gap:1.5rem;flex-wrap:wrap;position:relative;z-index:1}
        .db-hero-title{font-size:1.625rem;font-weight:800;color:#fff;letter-spacing:-.03em}
        .db-hero-sub{font-size:.8125rem;color:rgba(255,255,255,.55);margin-top:.375rem}
        .db-hero-date{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:99px;padding:.375rem .875rem;font-size:.75rem;color:rgba(255,255,255,.75);margin-top:.75rem;backdrop-filter:blur(8px)}
        .db-hero-actions{display:flex;gap:.625rem;position:relative;z-index:1}
        .db-hero-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.625rem 1.125rem;border-radius:12px;font-size:.8125rem;font-weight:700;text-decoration:none;transition:all .25s;border:none;cursor:pointer;font-family:inherit}
        .db-hero-btn-p{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 4px 16px rgba(245,158,11,.35)}
        .db-hero-btn-p:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(245,158,11,.45)}
        .db-hero-btn-s{background:rgba(255,255,255,.08);color:rgba(255,255,255,.85);border:1px solid rgba(255,255,255,.15)}
        .db-hero-btn-s:hover{background:rgba(255,255,255,.14);transform:translateY(-2px)}

        /* KPI */
        .db-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:.875rem;margin-bottom:1.5rem}
        .db-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem 1.375rem;position:relative;overflow:hidden;transition:all .3s}
        .db-kpi::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
        .db-kpi:hover{transform:translateY(-4px);box-shadow:0 12px 36px rgba(0,0,0,.08);border-color:transparent}
        .db-kpi.amber::before{background:linear-gradient(90deg,#f59e0b,#d97706)}
        .db-kpi.green::before{background:linear-gradient(90deg,#10b981,#059669)}
        .db-kpi.violet::before{background:linear-gradient(90deg,#8b5cf6,#7c3aed)}
        .db-kpi.blue::before{background:linear-gradient(90deg,#3b82f6,#2563eb)}
        .db-kpi-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:.875rem}
        .db-kpi-lbl{font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8}
        .db-kpi-ico{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.125rem}
        .db-kpi-ico.amber{background:linear-gradient(135deg,#fffbeb,#fef3c7)}
        .db-kpi-ico.green{background:linear-gradient(135deg,#ecfdf5,#d1fae5)}
        .db-kpi-ico.violet{background:linear-gradient(135deg,#f5f3ff,#ede9fe)}
        .db-kpi-ico.blue{background:linear-gradient(135deg,#eff6ff,#dbeafe)}
        .db-kpi-val{font-size:1.625rem;font-weight:800;color:#0f172a;letter-spacing:-.03em;line-height:1}
        .db-kpi-val .u{font-size:.8125rem;font-weight:600;color:#94a3b8;margin-left:3px}
        .db-kpi-foot{font-size:.6875rem;color:#94a3b8;margin-top:.5rem;display:flex;align-items:center;gap:.375rem}
        .db-kpi-chip{display:inline-flex;align-items:center;gap:.25rem;padding:.125rem .5rem;border-radius:99px;font-size:.625rem;font-weight:700;margin-top:.375rem}
        .db-kpi-chip.up{background:#ecfdf5;color:#059669;border:1px solid #a7f3d0}
        .db-kpi-chip.warn{background:#fffbeb;color:#d97706;border:1px solid #fde68a}

        /* Panels */
        .db-panel{background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;transition:box-shadow .25s;margin-bottom:1.25rem}
        .db-panel:hover{box-shadow:0 8px 28px rgba(0,0,0,.05)}
        .db-panel-hdr{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.375rem;border-bottom:1px solid #f1f5f9}
        .db-panel-title{display:flex;align-items:center;gap:.625rem;font-size:.875rem;font-weight:700;color:#0f172a}
        .db-panel-ico{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.875rem;flex-shrink:0}
        .db-panel-link{display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:600;color:#6366f1;text-decoration:none;padding:.375rem .75rem;border-radius:8px;transition:all .2s}
        .db-panel-link:hover{background:#eef2ff;color:#4f46e5}
        .db-panel-body{padding:1.125rem 1.375rem}

        /* Chart */
        .db-chart{display:flex;align-items:flex-end;gap:.5rem;height:150px;padding-top:.5rem}
        .db-chart-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:.375rem;height:100%}
        .db-chart-track{flex:1;width:100%;display:flex;align-items:flex-end;justify-content:center}
        .db-chart-bar{width:100%;max-width:44px;border-radius:8px 8px 3px 3px;background:linear-gradient(180deg,#f59e0b,#d97706);transition:all .5s cubic-bezier(.4,0,.2,1);min-height:4px;opacity:.85;position:relative}
        .db-chart-bar:hover{opacity:1;transform:scaleY(1.06);box-shadow:0 -6px 16px rgba(245,158,11,.3)}
        .db-chart-bar .tip{position:absolute;top:-24px;left:50%;transform:translateX(-50%);font-size:.625rem;font-weight:700;color:#475569;white-space:nowrap;opacity:0;transition:opacity .2s}
        .db-chart-bar:hover .tip{opacity:1}
        .db-chart-lbl{font-size:.625rem;font-weight:600;color:#94a3b8}

        /* Stat rows */
        .db-stat-row{display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid #f8fafc}
        .db-stat-row:last-child{border-bottom:none}
        .db-stat-left{display:flex;align-items:center;gap:.625rem}
        .db-stat-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
        .db-stat-dot.blue{background:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.12)}
        .db-stat-dot.green{background:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
        .db-stat-dot.red{background:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.12)}
        .db-stat-dot.purple{background:#8b5cf6;box-shadow:0 0 0 3px rgba(139,92,246,.12)}
        .db-stat-dot.amber{background:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
        .db-stat-name{font-size:.8125rem;font-weight:500;color:#475569}
        .db-stat-val{font-size:.9375rem;font-weight:800;color:#0f172a;letter-spacing:-.02em}
        .db-stat-val.sm{font-size:.8125rem}
        .db-stat-val.red{color:#dc2626}
        .db-stat-val.green{color:#059669}
        .db-stat-val.amber{color:#d97706}

        /* Leaderboard */
        .db-leader{display:flex;flex-direction:column;gap:.375rem}
        .db-leader-item{display:flex;align-items:center;gap:.875rem;padding:.75rem .875rem;border-radius:12px;transition:all .2s;border:1px solid transparent}
        .db-leader-item:hover{background:#f8fafc;border-color:#f1f5f9}
        .db-leader-rank{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;flex-shrink:0}
        .db-leader-rank.g{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e}
        .db-leader-rank.s{background:linear-gradient(135deg,#f1f5f9,#e2e8f0);color:#475569}
        .db-leader-rank.b{background:linear-gradient(135deg,#ffedd5,#fed7aa);color:#9a3412}
        .db-leader-rank.n{background:#f8fafc;color:#94a3b8}
        .db-leader-av{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.8125rem;font-weight:700;flex-shrink:0;background:linear-gradient(135deg,#eef2ff,#e0e7ff);color:#4338ca}
        .db-leader-name{font-size:.8125rem;font-weight:600;color:#1e293b}
        .db-leader-bar{flex:1;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;min-width:30px;margin-top:.375rem}
        .db-leader-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,#f59e0b,#d97706);transition:width .8s cubic-bezier(.4,0,.2,1)}
        .db-leader-amt{font-size:.875rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;flex-shrink:0;margin-left:.5rem}

        /* Pending items */
        .db-pending{display:flex;flex-direction:column;gap:.5rem}
        .db-pending-item{display:flex;align-items:center;justify-content:space-between;padding:.75rem .875rem;border-radius:10px;border:1px solid #f1f5f9;transition:all .2s}
        .db-pending-item:hover{background:#fffbeb;border-color:#fde68a}
        .db-pending-av{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.8125rem;font-weight:700;flex-shrink:0;background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e}
        .db-pending-name{font-size:.8125rem;font-weight:600;color:#1e293b}
        .db-pending-date{font-size:.6875rem;color:#94a3b8;margin-top:1px}
        .db-pending-amt{font-size:.8125rem;font-weight:700;color:#0f172a}
        .db-pending-badge{display:inline-flex;align-items:center;gap:.25rem;padding:.125rem .5rem;border-radius:6px;font-size:.625rem;font-weight:700;background:#fef3c7;color:#b45309;margin-top:2px}

        /* Quick Nav */
        .db-nav{display:grid;grid-template-columns:repeat(4,1fr);gap:.875rem}
        .db-nav-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1rem 1.125rem;display:flex;align-items:center;gap:.875rem;transition:all .3s;cursor:pointer;text-decoration:none}
        .db-nav-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.06);border-color:transparent}
        .db-nav-ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
        .db-nav-ico.blue{background:linear-gradient(135deg,#eff6ff,#dbeafe)}
        .db-nav-ico.green{background:linear-gradient(135deg,#ecfdf5,#d1fae5)}
        .db-nav-ico.amber{background:linear-gradient(135deg,#fffbeb,#fef3c7)}
        .db-nav-ico.violet{background:linear-gradient(135deg,#f5f3ff,#ede9fe)}
        .db-nav-title{font-size:.8125rem;font-weight:700;color:#0f172a}
        .db-nav-sub{font-size:.6875rem;color:#94a3b8;margin-top:1px}

        /* Empty */
        .db-empty{text-align:center;padding:2.5rem 1rem}
        .db-empty-ico{width:64px;height:64px;margin:0 auto .75rem;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-radius:50%;display:flex;align-items:center;justify-content:center}
        .db-empty-title{font-size:.875rem;font-weight:600;color:#64748b;margin-bottom:.25rem}
        .db-empty-sub{font-size:.75rem;color:#94a3b8}

        /* Stok Jalan */
        .db-stok-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:.75rem}
        .db-stok-card{background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:.875rem 1rem;transition:all .2s}
        .db-stok-card:hover{border-color:#e2e8f0;box-shadow:0 4px 12px rgba(0,0,0,.04)}

        /* Feature summary cards */
        .db-feat{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem}
        .db-feat-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;transition:all .3s}
        .db-feat-card:hover{transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,.06);border-color:transparent}
        .db-feat-accent{height:4px;width:100%}
        .db-feat-accent.sky{background:linear-gradient(90deg,#0ea5e9,#0284c7)}
        .db-feat-accent.emerald{background:linear-gradient(90deg,#10b981,#059669)}
        .db-feat-accent.rose{background:linear-gradient(90deg,#f43f5e,#e11d48)}
        .db-feat-body{padding:1.125rem 1.25rem}
        .db-feat-top{display:flex;align-items:center;gap:.75rem;margin-bottom:.875rem}
        .db-feat-ico{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
        .db-feat-ico.sky{background:linear-gradient(135deg,#f0f9ff,#e0f2fe)}
        .db-feat-ico.emerald{background:linear-gradient(135deg,#ecfdf5,#d1fae5)}
        .db-feat-ico.rose{background:linear-gradient(135deg,#fff1f2,#ffe4e6)}
        .db-feat-title{font-size:.8125rem;font-weight:700;color:#0f172a}
        .db-feat-sub{font-size:.6875rem;color:#94a3b8;margin-top:1px}
        .db-feat-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;margin-bottom:.875rem}
        .db-feat-stat{padding:.5rem .625rem;border-radius:10px;background:#f8fafc;border:1px solid #f1f5f9}
        .db-feat-stat-lbl{font-size:.5625rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8}
        .db-feat-stat-val{font-size:.875rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;margin-top:1px}
        .db-feat-stat-val.sky{color:#0284c7}
        .db-feat-stat-val.emerald{color:#059669}
        .db-feat-stat-val.rose{color:#e11d48}
        .db-feat-btn{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.625rem;border-radius:10px;font-size:.8125rem;font-weight:600;text-decoration:none;transition:all .25s;border:none;cursor:pointer;font-family:inherit}
        .db-feat-btn.sky{background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;box-shadow:0 4px 14px rgba(14,165,233,.25)}
        .db-feat-btn.sky:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(14,165,233,.35)}
        .db-feat-btn.emerald{background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,.25)}
        .db-feat-btn.emerald:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(16,185,129,.35)}
        .db-feat-btn.slate{background:linear-gradient(135deg,#1e293b,#0f172a);color:#fff;box-shadow:0 4px 14px rgba(15,23,42,.2)}
        .db-feat-btn.slate:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(15,23,42,.3)}

        @media(max-width:1024px){.db-kpis{grid-template-columns:repeat(2,1fr)}.db-feat{grid-template-columns:1fr}.db-nav{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:640px){.db-kpis{grid-template-columns:1fr}.db-nav{grid-template-columns:1fr}.db-hero{padding:1.5rem}.db-hero-title{font-size:1.25rem}.db-feat{grid-template-columns:1fr}}
    </style>
    @endpush

    <div class="db">

        {{-- Hero --}}
        <div class="db-hero">
            <div class="db-hero-top">
                <div>
                    <div class="db-hero-title">📊 Dashboard Minyak</div>
                    <p class="db-hero-sub">Ringkasan aktivitas penjualan dan operasional hari ini</p>
                    <div class="db-hero-date">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                </div>
                <div class="db-hero-actions">
                    <a href="{{ route('minyak.penjualan.create') }}" class="db-hero-btn db-hero-btn-p">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Penjualan Baru
                    </a>
                    <a href="{{ route('minyak.loading.create') }}" class="db-hero-btn db-hero-btn-s">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Loading Baru
                    </a>
                </div>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="db-kpis">
            <div class="db-kpi amber">
                <div class="db-kpi-top"><span class="db-kpi-lbl">Penjualan Hari Ini</span><div class="db-kpi-ico amber">💰</div></div>
                <div class="db-kpi-val">Rp {{ number_format($stats['penjualan_hari_ini'], 0, ',', '.') }}</div>
                <div class="db-kpi-foot">Bulan ini: Rp {{ number_format($statsBulanIni['total_penjualan'], 0, ',', '.') }}</div>
            </div>
            <div class="db-kpi green">
                <div class="db-kpi-top"><span class="db-kpi-lbl">Transaksi Hari Ini</span><div class="db-kpi-ico green">🧾</div></div>
                <div class="db-kpi-val">{{ number_format($stats['transaksi_hari_ini']) }}<span class="u">transaksi</span></div>
                <div class="db-kpi-foot">Bulan ini: {{ number_format($statsBulanIni['total_transaksi']) }} transaksi</div>
            </div>
            <div class="db-kpi violet">
                <div class="db-kpi-top"><span class="db-kpi-lbl">Setoran Terverifikasi</span><div class="db-kpi-ico violet">✅</div></div>
                <div class="db-kpi-val">Rp {{ number_format($stats['setoran_hari_ini'], 0, ',', '.') }}</div>
                <div class="db-kpi-foot">Total setoran hari ini</div>
            </div>
            <div class="db-kpi blue">
                <div class="db-kpi-top"><span class="db-kpi-lbl">Loading Hari Ini</span><div class="db-kpi-ico blue">🚛</div></div>
                <div class="db-kpi-val">{{ number_format($stats['loading_hari_ini'], 0, ',', '.') }}<span class="u">Liter</span></div>
                <div class="db-kpi-foot">Muatan BBM keluar hari ini</div>
            </div>
        </div>

        {{-- Feature Summary Cards --}}
        <div class="db-feat">
            {{-- Ringkasan per Sales --}}
            <div class="db-feat-card">
                <div class="db-feat-accent sky"></div>
                <div class="db-feat-body">
                    <div class="db-feat-top">
                        <div class="db-feat-ico sky">👥</div>
                        <div><div class="db-feat-title">Ringkasan per Sales</div><div class="db-feat-sub">Performa penjualan harian</div></div>
                    </div>
                    <div class="db-feat-stats">
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Sales Aktif</div><div class="db-feat-stat-val sky">{{ $master['total_sales'] }}</div></div>
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Transaksi</div><div class="db-feat-stat-val sky">{{ number_format($stats['transaksi_hari_ini']) }}</div></div>
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Loading (L)</div><div class="db-feat-stat-val sky">{{ number_format($stats['loading_hari_ini'], 0, ',', '.') }}</div></div>
                    </div>
                    <a href="{{ route('minyak.sales.index') }}" class="db-feat-btn sky">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Lihat Data Sales
                    </a>
                </div>
            </div>

            {{-- Rekapitulasi Bulanan --}}
            <div class="db-feat-card">
                <div class="db-feat-accent emerald"></div>
                <div class="db-feat-body">
                    <div class="db-feat-top">
                        <div class="db-feat-ico emerald">📅</div>
                        <div><div class="db-feat-title">Rekapitulasi Bulanan</div><div class="db-feat-sub">Rekap penjualan bulan ini</div></div>
                    </div>
                    <div class="db-feat-stats">
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Penjualan</div><div class="db-feat-stat-val emerald" style="font-size:.75rem">Rp {{ number_format($statsBulanIni['total_penjualan'], 0, ',', '.') }}</div></div>
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Transaksi</div><div class="db-feat-stat-val emerald">{{ number_format($statsBulanIni['total_transaksi']) }}</div></div>
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Hutang Baru</div><div class="db-feat-stat-val rose" style="font-size:.75rem">Rp {{ number_format($statsBulanIni['total_hutang_baru'], 0, ',', '.') }}</div></div>
                    </div>
                    <a href="{{ route('minyak.penjualan.index') }}" class="db-feat-btn emerald">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Lihat Penjualan
                    </a>
                </div>
            </div>

            {{-- Daftar Piutang --}}
            <div class="db-feat-card">
                <div class="db-feat-accent rose"></div>
                <div class="db-feat-body">
                    <div class="db-feat-top">
                        <div class="db-feat-ico rose">💳</div>
                        <div><div class="db-feat-title">Daftar Piutang</div><div class="db-feat-sub">Status hutang pelanggan</div></div>
                    </div>
                    <div class="db-feat-stats">
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Total Hutang</div><div class="db-feat-stat-val rose" style="font-size:.75rem">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div></div>
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Overdue</div><div class="db-feat-stat-val {{ $hutangOverdue > 0 ? 'rose' : 'emerald' }}">{{ $hutangOverdue }}</div></div>
                        <div class="db-feat-stat"><div class="db-feat-stat-lbl">Pending</div><div class="db-feat-stat-val" style="color:#d97706">{{ $setoranPending->count() }}</div></div>
                    </div>
                    <a href="{{ route('minyak.hutang.index') }}" class="db-feat-btn slate">
                        Lihat Data Hutang
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Chart + Monthly --}}
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            {{-- 7-Day Chart --}}
            <div class="db-panel">
                <div class="db-panel-hdr">
                    <div class="db-panel-title"><div class="db-panel-ico" style="background:linear-gradient(135deg,#fffbeb,#fef3c7)">📈</div>Tren Penjualan 7 Hari</div>
                </div>
                <div class="db-panel-body">
                    @php $maxChart = max(max(array_column($penjualanChart, 'total') ?: [0]), 1); @endphp
                    <div class="db-chart">
                        @foreach($penjualanChart as $bar)
                            @php $h = $maxChart > 0 ? max(($bar['total'] / $maxChart) * 100, 4) : 4; @endphp
                            <div class="db-chart-col">
                                <div class="db-chart-track"><div class="db-chart-bar" style="height:{{ $h }}%"><span class="tip">Rp {{ number_format($bar['total'], 0, ',', '.') }}</span></div></div>
                                <div class="db-chart-lbl">{{ $bar['tanggal'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Monthly Summary --}}
            <div class="db-panel">
                <div class="db-panel-hdr">
                    <div class="db-panel-title"><div class="db-panel-ico" style="background:linear-gradient(135deg,#f5f3ff,#ede9fe)">📅</div>Bulan Ini</div>
                </div>
                <div class="db-panel-body">
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot blue"></div><span class="db-stat-name">Total Penjualan</span></div><span class="db-stat-val sm">Rp {{ number_format($statsBulanIni['total_penjualan'], 0, ',', '.') }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot green"></div><span class="db-stat-name">Total Transaksi</span></div><span class="db-stat-val">{{ number_format($statsBulanIni['total_transaksi']) }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot red"></div><span class="db-stat-name">Hutang Baru</span></div><span class="db-stat-val sm red">Rp {{ number_format($statsBulanIni['total_hutang_baru'], 0, ',', '.') }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot purple"></div><span class="db-stat-name">Produk Aktif</span></div><span class="db-stat-val">{{ $master['total_produk'] }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot amber"></div><span class="db-stat-name">Pelanggan</span></div><span class="db-stat-val">{{ $master['total_pelanggan'] }}</span></div>
                </div>
            </div>
        </div>

        {{-- Middle Row: Master + Hutang + Setoran Pending --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.25rem;">
            {{-- Data Master --}}
            <div class="db-panel">
                <div class="db-panel-hdr">
                    <div class="db-panel-title"><div class="db-panel-ico" style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">🗂️</div>Data Master</div>
                </div>
                <div class="db-panel-body">
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot blue"></div><span class="db-stat-name">Sales Aktif</span></div><span class="db-stat-val">{{ $master['total_sales'] }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot green"></div><span class="db-stat-name">Pelanggan</span></div><span class="db-stat-val">{{ $master['total_pelanggan'] }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot purple"></div><span class="db-stat-name">Produk</span></div><span class="db-stat-val">{{ $master['total_produk'] }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot red"></div><span class="db-stat-name">Stok Rendah</span></div><span class="db-stat-val {{ $master['stok_rendah'] > 0 ? 'red' : '' }}">{{ $master['stok_rendah'] }}</span></div>
                </div>
            </div>

            {{-- Status Hutang --}}
            <div class="db-panel">
                <div class="db-panel-hdr">
                    <div class="db-panel-title"><div class="db-panel-ico" style="background:linear-gradient(135deg,#fef2f2,#fee2e2)">💳</div>Status Hutang</div>
                    <a href="{{ route('minyak.hutang.index') }}" class="db-panel-link">Detail <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                </div>
                <div class="db-panel-body">
                    <div style="padding:.875rem 1rem;background:linear-gradient(135deg,#fef2f2,#fff1f2);border-radius:12px;border:1px solid #fecaca;margin-bottom:.875rem">
                        <div style="font-size:.625rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-bottom:.375rem">Total Hutang Aktif</div>
                        <div style="font-size:1.375rem;font-weight:800;color:#dc2626;letter-spacing:-.03em">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
                    </div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot red"></div><span class="db-stat-name">Overdue</span></div><span style="display:inline-flex;align-items:center;gap:.25rem;padding:.125rem .625rem;border-radius:99px;font-size:.6875rem;font-weight:700;{{ $hutangOverdue > 0 ? 'background:#fef2f2;color:#dc2626;border:1px solid #fecaca' : 'background:#ecfdf5;color:#059669;border:1px solid #a7f3d0' }}">{{ $hutangOverdue > 0 ? '⚠️' : '✨' }} {{ $hutangOverdue }}</span></div>
                    <div class="db-stat-row"><div class="db-stat-left"><div class="db-stat-dot amber"></div><span class="db-stat-name">Setoran Pending</span></div><span class="db-stat-val amber">{{ $setoranPending->count() }}</span></div>
                </div>
            </div>

            {{-- Setoran Pending --}}
            <div class="db-panel">
                <div class="db-panel-hdr">
                    <div class="db-panel-title">
                        <div class="db-panel-ico" style="background:linear-gradient(135deg,#fffbeb,#fef3c7)">💵</div>
                        Setoran Pending
                        @if($setoranPending->count() > 0)<span style="display:inline-flex;align-items:center;justify-content:center;min-width:20px;height:20px;border-radius:6px;background:#fef3c7;color:#b45309;font-size:.6875rem;font-weight:800;padding:0 5px">{{ $setoranPending->count() }}</span>@endif
                    </div>
                    <a href="{{ route('minyak.setoran.index') }}" class="db-panel-link">Semua <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                </div>
                <div class="db-panel-body">
                    @if($setoranPending->count() > 0)
                    <div class="db-pending">
                        @foreach($setoranPending as $setoran)
                        <div class="db-pending-item">
                            <div style="display:flex;align-items:center;gap:.625rem">
                                <div class="db-pending-av">{{ substr($setoran->sales->nama, 0, 1) }}</div>
                                <div><div class="db-pending-name">{{ $setoran->sales->nama }}</div><div class="db-pending-date">{{ $setoran->tanggal->format('d M Y') }}</div></div>
                            </div>
                            <div style="text-align:right"><div class="db-pending-amt">Rp {{ number_format($setoran->total_setor, 0, ',', '.') }}</div><div class="db-pending-badge">⏳ Pending</div></div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="db-empty">
                        <div class="db-empty-ico"><svg width="32" height="32" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        <div class="db-empty-title">Semua Beres!</div>
                        <div class="db-empty-sub">Tidak ada setoran pending</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Sales --}}
        <div class="db-panel">
            <div class="db-panel-hdr">
                <div class="db-panel-title"><div class="db-panel-ico" style="background:linear-gradient(135deg,#fef3c7,#fde68a)">🏆</div>Top Sales Bulan Ini</div>
                @if($topSales->count() > 0)<span style="display:inline-flex;align-items:center;gap:.375rem;padding:.375rem .75rem;border-radius:8px;background:#f8fafc;border:1px solid #f1f5f9;font-size:.75rem;font-weight:600;color:#64748b">👥 {{ $topSales->count() }} sales</span>@endif
            </div>
            <div class="db-panel-body">
                @if($topSales->count() > 0)
                    @php $maxAmt = $topSales->max('penjualans_sum_total') ?? 1; @endphp
                    <div class="db-leader">
                        @foreach($topSales as $i => $s)
                            @php $rc = $i == 0 ? 'g' : ($i == 1 ? 's' : ($i == 2 ? 'b' : 'n')); $bw = $maxAmt > 0 ? (($s->penjualans_sum_total ?? 0) / $maxAmt) * 100 : 0; @endphp
                            <div class="db-leader-item">
                                <div class="db-leader-rank {{ $rc }}">{{ $i + 1 }}</div>
                                <div class="db-leader-av">{{ substr($s->nama, 0, 1) }}</div>
                                <div style="flex:1;min-width:0">
                                    <div class="db-leader-name">{{ $s->nama }}</div>
                                    <div class="db-leader-bar"><div class="db-leader-fill" style="width:{{ $bw }}%"></div></div>
                                </div>
                                <div class="db-leader-amt">Rp {{ number_format($s->penjualans_sum_total ?? 0, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="db-empty">
                        <div class="db-empty-ico"><svg width="32" height="32" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                        <div class="db-empty-title">Belum Ada Data</div>
                        <div class="db-empty-sub">Data penjualan bulan ini akan muncul di sini</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Stok di Jalan --}}
        @if(count($stokDiJalan) > 0)
        <div style="margin-bottom:1.5rem">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                <div>
                    <div style="font-size:1rem;font-weight:800;color:#0f172a;margin:0">🛢️ Stok di Jalan</div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:2px">Total: {{ number_format($totalStokDiJalan, 0, ',', '.') }} Liter masih di tangki kendaraan</div>
                </div>
                <a href="{{ route('minyak.stok.index') }}" style="font-size:.75rem;font-weight:600;color:#6366f1;text-decoration:none">Detail &rarr;</a>
            </div>
            <div class="db-stok-grid">
                @foreach($stokDiJalan as $data)
                <div class="db-stok-card">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem">
                        <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#eef2ff,#e0e7ff);display:flex;align-items:center;justify-content:center;font-size:.6875rem;font-weight:800;color:#4f46e5">{{ strtoupper(substr($data['sales']->nama ?? '?', 0, 1)) }}</div>
                        <div><div style="font-size:.8125rem;font-weight:700;color:#0f172a">{{ $data['sales']->nama ?? '-' }}</div><div style="font-size:.625rem;color:#94a3b8">{{ $data['sales']->no_kendaraan ?? '-' }}</div></div>
                    </div>
                    <div style="font-size:1.125rem;font-weight:800;color:#059669;margin-bottom:.25rem">{{ number_format($data['total_sisa'], 0, ',', '.') }} L</div>
                    @foreach($data['detail'] as $d)
                    <div style="display:flex;justify-content:space-between;font-size:.6875rem;color:#64748b;padding:2px 0"><span>{{ $d['produk'] }}</span><span style="font-weight:600">{{ number_format($d['sisa'], 0, ',', '.') }} L</span></div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Quick Nav --}}
        <div class="db-nav">
            <a href="{{ route('minyak.penjualan.index') }}" class="db-nav-card"><div class="db-nav-ico blue">🧾</div><div><div class="db-nav-title">Data Penjualan</div><div class="db-nav-sub">Lihat semua transaksi</div></div></a>
            <a href="{{ route('minyak.setoran.index') }}" class="db-nav-card"><div class="db-nav-ico green">💵</div><div><div class="db-nav-title">Data Setoran</div><div class="db-nav-sub">Verifikasi setoran</div></div></a>
            <a href="{{ route('minyak.loading.index') }}" class="db-nav-card"><div class="db-nav-ico amber">🚛</div><div><div class="db-nav-title">Loading Harian</div><div class="db-nav-sub">Muatan BBM hari ini</div></div></a>
            <a href="{{ route('minyak.stok.index') }}" class="db-nav-card"><div class="db-nav-ico violet">📊</div><div><div class="db-nav-title">Stok Kendaraan</div><div class="db-nav-sub">Sisa stok per sales</div></div></a>
        </div>

    </div>
</x-app-layout>
