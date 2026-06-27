<x-app-layout>
    <x-slot name="header">Transaksi Penjualan</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .tx-page{max-width:1100px;margin:0 auto;padding:0 0 3rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#0f172a;}

        /* ── HERO HEADER ── */
        .tx-hero{background:linear-gradient(135deg,#06090f 0%,#0d1322 35%,#111827 70%,#0a0e1a 100%);border-radius:20px;padding:2rem 2.25rem 3.5rem;margin-bottom:-2rem;position:relative;overflow:hidden;}
        .tx-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 80% 20%,rgba(99,102,241,0.2) 0%,transparent 60%),radial-gradient(ellipse at 20% 80%,rgba(16,185,129,0.08) 0%,transparent 50%);}
        .tx-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(99,102,241,0.5),rgba(16,185,129,0.3),transparent);}
        .tx-hero-inner{position:relative;z-index:1;}
        .tx-hero-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .tx-hero-badge{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.3);padding:0.3rem 0.875rem;border-radius:99px;font-size:0.65rem;font-weight:700;color:rgba(165,180,252,0.9);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;}
        .tx-hero-dot{width:6px;height:6px;border-radius:50%;background:#818cf8;animation:tx-pulse 2s infinite;}
        @keyframes tx-pulse{0%,100%{opacity:1}50%{opacity:0.4}}
        .tx-hero-title{font-size:1.75rem;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;margin:0 0 0.35rem;}
        .tx-hero-sub{font-size:0.8125rem;color:rgba(255,255,255,0.4);margin:0;}
        .tx-hero-actions{display:flex;gap:0.5rem;align-items:center;}
        .tx-hero-btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1.15rem;border-radius:10px;font-size:0.8rem;font-weight:700;cursor:pointer;transition:all .2s;border:none;text-decoration:none;white-space:nowrap;}
        .tx-hero-btn svg{width:16px;height:16px;}
        .tx-hero-btn-primary{background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;box-shadow:0 4px 14px rgba(79,70,229,0.3);}
        .tx-hero-btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(79,70,229,0.4);}

        .tx-hero-stats{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.25rem;}
        .tx-hero-revenue-label{font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.5rem;}
        .tx-hero-revenue-amount{font-size:2.5rem;font-weight:900;color:#fff;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.03em;line-height:1;}
        .tx-hero-revenue-rp{font-size:1rem;opacity:0.45;margin-right:3px;font-weight:700;}
        .tx-hero-chip{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);padding:4px 12px;border-radius:7px;font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.7);margin-top:0.75rem;}
        .tx-hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:8px;padding-bottom:4px;}
        .tx-hero-count{font-size:2rem;font-weight:900;color:rgba(255,255,255,0.9);font-family:ui-monospace,monospace;line-height:1;}
        .tx-hero-count-label{font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:0.08em;}

        /* ── STAT CARDS ── */
        .tx-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:0.875rem;margin-bottom:1.5rem;position:relative;z-index:2;}
        .tx-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.15rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all .2s;position:relative;overflow:hidden;}
        .tx-stat:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.06);}
        .tx-stat::before{content:'';position:absolute;top:0;right:0;width:60px;height:60px;border-radius:50%;opacity:0.06;transform:translate(15px,-15px);}
        .tx-stat.emerald::before{background:#059669;}
        .tx-stat.blue::before{background:#3b82f6;}
        .tx-stat.indigo::before{background:#4f46e5;}
        .tx-stat.amber::before{background:#d97706;}
        .tx-stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
        .tx-stat-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .tx-stat-ico svg{width:18px;height:18px;}
        .tx-stat.emerald .tx-stat-ico{background:#ecfdf5;color:#059669;}
        .tx-stat.blue .tx-stat-ico{background:#eff6ff;color:#3b82f6;}
        .tx-stat.indigo .tx-stat-ico{background:#eef2ff;color:#4f46e5;}
        .tx-stat.amber .tx-stat-ico{background:#fffbeb;color:#d97706;}
        .tx-stat-tag{font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;padding:3px 8px;border-radius:99px;}
        .tx-stat.emerald .tx-stat-tag{background:#ecfdf5;color:#065f46;}
        .tx-stat.blue .tx-stat-tag{background:#eff6ff;color:#1e40af;}
        .tx-stat.indigo .tx-stat-tag{background:#eef2ff;color:#4338ca;}
        .tx-stat.amber .tx-stat-tag{background:#fffbeb;color:#92400e;}
        .tx-stat-lbl{font-size:0.68rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;}
        .tx-stat-val{font-size:1.2rem;font-weight:900;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;margin-top:0.2rem;}
        .tx-stat.emerald .tx-stat-val{color:#059669;}
        .tx-stat.blue .tx-stat-val{color:#3b82f6;}
        .tx-stat.indigo .tx-stat-val{color:#4f46e5;}
        .tx-stat.amber .tx-stat-val{color:#d97706;}

        /* ── ALERT ── */
        .tx-alert{padding:0.75rem 1.15rem;border-radius:10px;font-size:0.82rem;font-weight:600;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;animation:tx-fadeIn .3s ease;}
        .tx-alert-ok{background:#dcfce7;color:#15803d;border:1px solid #a7f3d0;}
        .tx-alert-err{background:#fee2e2;color:#991b1b;border:1px solid #fecaca;}
        .tx-alert svg{width:16px;height:16px;flex-shrink:0;}
        @keyframes tx-fadeIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}

        /* ── MAIN CARD ── */
        .tx-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);overflow:hidden;}
        .tx-card-hdr{padding:1.15rem 1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.75rem;border-bottom:1px solid #f1f5f9;background:linear-gradient(180deg,#fafbfc,#fff);}
        .tx-card-title{font-size:0.9375rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:0.5rem;}
        .tx-card-title svg{width:18px;height:18px;color:#4f46e5;}
        .tx-card-sub{font-size:0.75rem;color:#64748b;margin-top:0.15rem;font-weight:500;}
        .tx-card-count{font-size:0.72rem;font-weight:700;color:#4f46e5;background:#e0e7ff;padding:3px 10px;border-radius:99px;}

        /* ── FILTER ── */
        .tx-filter{padding:1rem 1.5rem;background:#f8fafc;border-bottom:1px solid #e2e8f0;}
        .tx-filter-form{display:flex;flex-wrap:wrap;gap:0.6rem;align-items:flex-end;}
        .tx-fg{display:flex;flex-direction:column;gap:0.25rem;}
        .tx-fl{font-size:0.62rem;font-weight:700;text-transform:uppercase;color:#94a3b8;letter-spacing:0.06em;}
        .tx-fi{padding:0.5rem 0.75rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.8rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;transition:all .15s;background:#fff;color:#0f172a;font-weight:500;}
        .tx-fi:focus{outline:none;border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.1);}
        .tx-fi::placeholder{color:#94a3b8;}
        select.tx-fi{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.6rem center;padding-right:1.75rem;cursor:pointer;}
        .tx-fi-search{min-width:180px;flex:1;max-width:240px;}
        .tx-filter-btns{display:flex;gap:0.4rem;align-self:flex-end;}
        .tx-btn{display:inline-flex;align-items:center;gap:0.35rem;padding:0.5rem 0.875rem;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;transition:all .15s;border:none;text-decoration:none;white-space:nowrap;}
        .tx-btn svg{width:14px;height:14px;}
        .tx-btn-dark{background:#0f172a;color:#fff;}
        .tx-btn-dark:hover{background:#1e293b;}
        .tx-btn-ghost{background:transparent;color:#64748b;border:1.5px solid #e2e8f0;}
        .tx-btn-ghost:hover{background:#f1f5f9;color:#0f172a;border-color:#94a3b8;}

        /* ── TABLE ── */
        .tx-tbl-wrap{overflow-x:auto;}
        .tx-tbl{width:100%;border-collapse:collapse;font-size:0.8125rem;min-width:980px;}
        .tx-tbl th{text-align:left;padding:0.75rem 1rem;font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;background:linear-gradient(180deg,#f8fafc,#f4f8fc);border-bottom:2px solid #e2e8f0;white-space:nowrap;}
        .tx-tbl td{padding:0.8rem 1rem;border-bottom:1px solid #f1f5f9;color:#334155;vertical-align:middle;}
        .tx-tbl tr:last-child td{border-bottom:none;}
        .tx-tbl tbody tr{transition:background .15s;}
        .tx-tbl tbody tr:hover{background:#f8fafc;}
        .tx-tbl .c{text-align:center;}
        .tx-tbl .r{text-align:right;}

        .tx-voided td{opacity:0.4;background:#f8fafc;}
        .tx-voided td .tx-badge,.tx-voided td .tx-inv{display:inline-block;text-decoration:none;}
        .tx-voided td .tx-inv{text-decoration:none;}

        .tx-mono{font-family:ui-monospace,'Cascadia Code','Fira Code',Consolas,monospace;font-size:0.75rem;}

        .tx-inv{font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.8rem;color:#4f46e5;background:#eef2ff;padding:2px 8px;border-radius:6px;display:inline-block;}
        .tx-inv-add{font-size:0.6rem;font-weight:700;color:#92400e;background:#fef3c7;padding:1px 6px;border-radius:4px;margin-top:3px;display:inline-block;}
        .tx-driver-info{font-size:0.63rem;color:#94a3b8;margin-top:3px;font-weight:500;}

        .tx-badge{display:inline-flex;align-items:center;gap:0.2rem;padding:2px 8px;border-radius:99px;font-size:0.65rem;font-weight:700;white-space:nowrap;}
        .tx-badge svg{width:10px;height:10px;}
        .tx-badge-success{background:#dcfce7;color:#15803d;}
        .tx-badge-danger{background:#fee2e2;color:#991b1b;}
        .tx-badge-blue{background:#dbeafe;color:#1e40af;}
        .tx-badge-purple{background:#f3e8ff;color:#7c3aed;}
        .tx-badge-teal{background:#ccfbf1;color:#0f766e;}
        .tx-badge-gray{background:#f1f5f9;color:#64748b;}
        .tx-badge-amber{background:#fef3c7;color:#92400e;}

        .tx-method{font-weight:600;font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.03em;}

        .tx-date{font-weight:700;font-size:0.8rem;color:#0f172a;}
        .tx-time{font-size:0.68rem;color:#94a3b8;margin-top:1px;font-weight:500;}

        .tx-kasir{font-weight:700;color:#0f172a;font-size:0.8rem;}

        .tx-amt{font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.8rem;white-space:nowrap;}
        .tx-amt-main{color:#0f172a;}
        .tx-amt-paid{color:#059669;}
        .tx-amt-change{color:#d97706;}

        .tx-print{font-size:0.63rem;font-weight:700;}

        .tx-actions{display:flex;gap:0.35rem;justify-content:center;}
        .tx-action{width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;border:1.5px solid #e2e8f0;background:#fff;color:#94a3b8;transition:all .15s;cursor:pointer;text-decoration:none;}
        .tx-action svg{width:14px;height:14px;}
        .tx-action.view:hover{color:#4f46e5;border-color:#a5b4fc;background:#eef2ff;}
        .tx-action.print:hover{color:#059669;border-color:#6ee7b7;background:#ecfdf5;}
        .tx-action.retur:hover{color:#d97706;border-color:#fcd34d;background:#fffbeb;}

        /* ── EMPTY ── */
        .tx-empty{text-align:center;padding:3.5rem 1rem;}
        .tx-empty-ico{width:56px;height:56px;margin:0 auto 1rem;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;}
        .tx-empty-ico svg{width:28px;height:28px;color:#94a3b8;}
        .tx-empty-title{font-size:0.9375rem;font-weight:800;color:#64748b;}
        .tx-empty-sub{font-size:0.8rem;color:#94a3b8;margin-top:0.25rem;font-weight:500;}

        .tx-pagination{padding:1rem 1.5rem;display:flex;justify-content:center;border-top:1px solid #f1f5f9;}

        /* ── RESPONSIVE ── */
        @media(max-width:1024px){
            .tx-stats{grid-template-columns:1fr 1fr;}
            .tx-hero-revenue-amount{font-size:2rem;}
        }
        @media(max-width:768px){
            .tx-stats{grid-template-columns:1fr 1fr;}
            .tx-filter-form{flex-direction:column;}
            .tx-fi-search{max-width:100%;}
            .tx-fg{width:100%;}
            .tx-fi,.tx-fg select{width:100%;}
            .tx-hero{padding:1.5rem 1.25rem 3rem;border-radius:14px;}
            .tx-hero-title{font-size:1.35rem;}
            .tx-hero-revenue-amount{font-size:1.75rem;}
            .tx-hero-stats{flex-direction:column;align-items:flex-start;}
            .tx-hero-right{align-items:flex-start;flex-direction:row;gap:12px;}
        }
        @media(max-width:480px){
            .tx-stats{grid-template-columns:1fr;}
        }
    </style>
    @endpush

    <div class="tx-page">

        {{-- ─── HERO HEADER ─── --}}
        <div class="tx-hero">
            <div class="tx-hero-inner">
                <div class="tx-hero-top">
                    <div>
                        <div class="tx-hero-badge">
                            <span class="tx-hero-dot"></span>
                            Manajemen Kasir
                        </div>
                        <h1 class="tx-hero-title">Transaksi Penjualan</h1>
                        <p class="tx-hero-sub">Pantau riwayat seluruh transaksi kasir, detail pendapatan, dan cetak ulang struk.</p>
                    </div>
                    <div class="tx-hero-actions">
                        <a href="{{ route('kasir.index') }}" class="tx-hero-btn tx-hero-btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            Buka Layar Kasir
                        </a>
                    </div>
                </div>
                <div class="tx-hero-stats">
                    <div>
                        <div class="tx-hero-revenue-label">Pendapatan Hari Ini</div>
                        <div class="tx-hero-revenue-amount">
                            <span class="tx-hero-revenue-rp">Rp</span>{{ number_format($todayRevenue, 0, ',', '.') }}
                        </div>
                        <div class="tx-hero-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ \Carbon\Carbon::today()->format('d M Y') }}
                        </div>
                    </div>
                    <div class="tx-hero-right">
                        <div>
                            <div class="tx-hero-count">{{ $todayCount }}</div>
                            <div class="tx-hero-count-label">Transaksi Sukses Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="tx-stats">
            <div class="tx-stat emerald">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <span class="tx-stat-tag">Hari Ini</span>
                </div>
                <div class="tx-stat-lbl">Pendapatan</div>
                <div class="tx-stat-val">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="tx-stat blue">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="tx-stat-tag">Hari Ini</span>
                </div>
                <div class="tx-stat-lbl">Transaksi Sukses</div>
                <div class="tx-stat-val">{{ $todayCount }} Nota</div>
            </div>
            <div class="tx-stat indigo">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                    </div>
                    <span class="tx-stat-tag">Filter</span>
                </div>
                <div class="tx-stat-lbl">Total Pendapatan</div>
                <div class="tx-stat-val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="tx-stat amber">
                <div class="tx-stat-top">
                    <div class="tx-stat-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <span class="tx-stat-tag">Filter</span>
                </div>
                <div class="tx-stat-lbl">Total Transaksi</div>
                <div class="tx-stat-val">{{ $totalCount }} Nota</div>
            </div>
        </div>

        {{-- ─── ALERTS ─── --}}
        @if(session('success'))
            <div class="tx-alert tx-alert-ok">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="tx-alert tx-alert-err">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ─── DATA CARD ─── --}}
        <div class="tx-card">
            <div class="tx-card-hdr">
                <div>
                    <div class="tx-card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Riwayat Transaksi
                    </div>
                    <div class="tx-card-sub">Menampilkan <strong>{{ $transactions->total() }}</strong> transaksi</div>
                </div>
                <span class="tx-card-count">{{ $transactions->total() }} Data</span>
            </div>

            {{-- Filters --}}
            <div class="tx-filter">
                <form method="GET" class="tx-filter-form">
                    <div class="tx-fg">
                        <label class="tx-fl">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice, kasir, pelanggan..." class="tx-fi tx-fi-search">
                    </div>
                    <div class="tx-fg" style="width:135px;">
                        <label class="tx-fl">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="tx-fi">
                    </div>
                    <div class="tx-fg" style="width:135px;">
                        <label class="tx-fl">Sampai</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="tx-fi">
                    </div>
                    <div class="tx-fg" style="width:120px;">
                        <label class="tx-fl">Jenis</label>
                        <select name="sale_type" class="tx-fi">
                            <option value="">Semua</option>
                            <option value="eceran" @selected(request('sale_type')=='eceran')>Eceran</option>
                            <option value="grosir" @selected(request('sale_type')=='grosir')>Grosir</option>
                        </select>
                    </div>
                    <div class="tx-fg" style="width:120px;">
                        <label class="tx-fl">Metode</label>
                        <select name="payment_method" class="tx-fi">
                            <option value="">Semua</option>
                            <option value="cash" @selected(request('payment_method')=='cash')>Tunai</option>
                            <option value="transfer" @selected(request('payment_method')=='transfer')>Transfer</option>
                            <option value="qris" @selected(request('payment_method')=='qris')>QRIS</option>
                            <option value="kredit" @selected(request('payment_method')=='kredit')>Limit</option>
                        </select>
                    </div>
                    <div class="tx-fg" style="width:120px;">
                        <label class="tx-fl">Status</label>
                        <select name="status" class="tx-fi">
                            <option value="">Semua</option>
                            <option value="completed" @selected(request('status')=='completed')>Selesai</option>
                            <option value="voided" @selected(request('status')=='voided')>Void</option>
                        </select>
                    </div>
                    @if($kasirUsers->isNotEmpty())
                    <div class="tx-fg" style="width:145px;">
                        <label class="tx-fl">Kasir</label>
                        <select name="user_id" class="tx-fi">
                            <option value="">Semua Kasir</option>
                            @foreach($kasirUsers as $ku)
                                <option value="{{ $ku->id }}" @selected(request('user_id') == $ku->id)>
                                    {{ $ku->name }} ({{ ucfirst($ku->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="tx-filter-btns">
                        <button type="submit" class="tx-btn tx-btn-dark">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Filter
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="tx-btn tx-btn-ghost">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="tx-tbl-wrap">
                <table class="tx-tbl">
                    <thead>
                        <tr>
                            <th style="width:36px;">#</th>
                            <th>No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th class="c">Jenis</th>
                            <th class="c">Item</th>
                            <th>Bayar</th>
                            <th class="r">Total</th>
                            <th class="r">Diterima</th>
                            <th class="r">Kembali</th>
                            <th class="c">Status</th>
                            <th class="c">Cetak</th>
                            <th class="c" style="width:95px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $i => $trx)
                        @php
                            $isVoided = $trx->status === 'voided';
                            $grandTotal = $trx->grand_total;
                            $totalPaid = $trx->total_paid;
                            $changeAmt = $totalPaid - $grandTotal;
                            $totalItems = $trx->details->count();
                            foreach ($trx->additionalTransactions as $at) {
                                $totalItems += $at->details->count();
                            }
                            $methodLabel = match($trx->payment_method) {
                                'cash' => 'Tunai',
                                'transfer' => 'Transfer',
                                'qris' => 'QRIS',
                                'kredit' => 'Kredit',
                                default => ucfirst($trx->payment_method ?? '-'),
                            };
                        @endphp
                        <tr class="{{ $isVoided ? 'tx-voided' : '' }}">
                            <td class="tx-mono" style="color:#94a3b8;">{{ $transactions->firstItem() + $i }}</td>
                            <td>
                                <span class="tx-inv">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                                @if($trx->hasAdditionalItems())
                                    <div><span class="tx-inv-add">+{{ $trx->additionalTransactions->count() }} Tam</span></div>
                                @endif
                                @if($trx->vehicle_id || $trx->driver_name)
                                    <div class="tx-driver-info">
                                        {{ $trx->vehicle?->license_plate ?? '' }}
                                        @if($trx->driver_name) {{ $trx->driver_name }} @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="tx-date">{{ $trx->created_at->format('d M Y') }}</div>
                                <div class="tx-time">{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="tx-kasir">{{ $trx->user?->name ?? '-' }}</div>
                                @if($trx->customer?->name)
                                    <span class="tx-badge {{ $trx->customer->category === 'grosir' ? 'tx-badge-purple' : 'tx-badge-teal' }}" style="margin-top:3px;">
                                        {{ $trx->customer->name }}
                                    </span>
                                @elseif($trx->customer?->category)
                                    <span class="tx-badge {{ $trx->customer->category === 'grosir' ? 'tx-badge-purple' : 'tx-badge-teal' }}" style="margin-top:3px;">
                                        {{ ucfirst($trx->customer->category) }}
                                    </span>
                                @endif
                            </td>
                            <td class="c">
                                @if($trx->sale_type === 'grosir')
                                    <span class="tx-badge tx-badge-purple">Grosir</span>
                                @elseif($trx->sale_type === 'eceran')
                                    <span class="tx-badge tx-badge-blue">Eceran</span>
                                @else
                                    <span style="color:#94a3b8;">-</span>
                                @endif
                            </td>
                            <td class="c">
                                <span class="tx-badge tx-badge-blue">{{ $totalItems }} item</span>
                            </td>
                            <td>
                                <span class="tx-method">{{ $methodLabel }}</span>
                            </td>
                            <td class="r">
                                <span class="tx-amt tx-amt-main">{{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </td>
                            <td class="r">
                                <span class="tx-amt tx-amt-paid">{{ number_format($totalPaid, 0, ',', '.') }}</span>
                            </td>
                            <td class="r">
                                <span class="tx-amt tx-amt-change">{{ number_format(max(0, $changeAmt), 0, ',', '.') }}</span>
                            </td>
                            <td class="c">
                                @if($trx->status === 'completed')
                                    <span class="tx-badge tx-badge-success">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Selesai
                                    </span>
                                @elseif($isVoided)
                                    <span class="tx-badge tx-badge-danger">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        Void
                                    </span>
                                @else
                                    <span class="tx-badge tx-badge-gray">{{ $trx->status }}</span>
                                @endif
                            </td>
                            <td class="c">
                                @if($trx->print_count > 0)
                                    <span class="tx-badge tx-badge-success tx-print" title="Dicetak {{ $trx->print_count }}x, terakhir: {{ $trx->last_printed_at?->format('d/m/Y H:i') }}">
                                        {{ $trx->print_count }}x
                                    </span>
                                @else
                                    <span class="tx-badge tx-badge-gray tx-print">-</span>
                                @endif
                            </td>
                            <td class="c">
                                <div class="tx-actions">
                                    <a href="{{ route('transaksi.show', $trx) }}" class="tx-action view" title="Detail">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @if($trx->status === 'completed')
                                        <a href="{{ route('print.receipt', $trx->id) }}" target="_blank" class="tx-action print" title="Cetak Struk">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                        </a>
                                        @can('edit_transaksi')
                                        <a href="{{ route('transaksi.retur.create', $trx) }}" class="tx-action retur" title="Retur">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                        </a>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13">
                                <div class="tx-empty">
                                    <div class="tx-empty-ico">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <div class="tx-empty-title">Tidak Ada Transaksi</div>
                                    <div class="tx-empty-sub">Belum ada data transaksi atau filter tidak cocok.</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="tx-pagination">{{ $transactions->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
