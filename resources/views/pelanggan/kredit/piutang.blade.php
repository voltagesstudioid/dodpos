<x-app-layout>
    <x-slot name="header">Hutang & Piutang Pelanggan</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        .hp-page{max-width:1280px;margin:0 auto;padding:0 0 3rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:#0f172a;}

        /* ── HERO HEADER ── */
        .hp-hero{background:linear-gradient(135deg,#06090f 0%,#0d1322 35%,#111827 70%,#0a0e1a 100%);border-radius:20px;padding:2rem 2.25rem 3.5rem;margin-bottom:-2rem;position:relative;overflow:hidden;}
        .hp-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 75% 25%,rgba(245,158,11,0.18) 0%,transparent 60%),radial-gradient(ellipse at 20% 75%,rgba(16,185,129,0.08) 0%,transparent 50%);}
        .hp-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(245,158,11,0.5),rgba(16,185,129,0.3),transparent);}
        .hp-hero-inner{position:relative;z-index:1;}
        .hp-hero-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .hp-hero-badge{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);padding:0.3rem 0.875rem;border-radius:99px;font-size:0.65rem;font-weight:700;color:rgba(253,186,116,0.9);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.75rem;}
        .hp-hero-dot{width:6px;height:6px;border-radius:50%;background:#fbbf24;animation:hp-pulse 2s infinite;}
        @keyframes hp-pulse{0%,100%{opacity:1}50%{opacity:0.4}}
        .hp-hero-title{font-size:1.75rem;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;margin:0 0 0.35rem;}
        .hp-hero-sub{font-size:0.8125rem;color:rgba(255,255,255,0.4);margin:0;}
        .hp-hero-actions{display:flex;gap:0.5rem;align-items:center;}
        .hp-hero-btn{display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1.15rem;border-radius:10px;font-size:0.8rem;font-weight:700;cursor:pointer;transition:all .2s;border:none;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .hp-hero-btn svg{width:15px;height:15px;}
        .hp-hero-btn-amber{background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff;box-shadow:0 4px 14px rgba(217,119,6,0.3);}
        .hp-hero-btn-amber:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(217,119,6,0.4);}
        .hp-hero-btn-blue{background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#fff;box-shadow:0 4px 14px rgba(59,130,246,0.3);}
        .hp-hero-btn-blue:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,0.4);}
        .hp-hero-stats{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.25rem;}
        .hp-hero-label{font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.5rem;}
        .hp-hero-amount{font-size:2.5rem;font-weight:900;color:#fff;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.03em;line-height:1;}
        .hp-hero-rp{font-size:1rem;opacity:0.45;margin-right:3px;font-weight:700;}
        .hp-hero-chip{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.08);padding:4px 12px;border-radius:7px;font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.7);margin-top:0.75rem;}
        .hp-hero-right{display:flex;flex-direction:column;align-items:flex-end;gap:8px;padding-bottom:4px;}
        .hp-hero-count{font-size:2rem;font-weight:900;color:rgba(255,255,255,0.9);font-family:ui-monospace,monospace;line-height:1;}
        .hp-hero-count-label{font-size:0.68rem;font-weight:600;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:0.08em;}

        /* ── STAT CARDS ── */
        .hp-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:0.875rem;margin-bottom:1.5rem;position:relative;z-index:2;}
        .hp-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:1.15rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:all .2s;position:relative;overflow:hidden;}
        .hp-stat:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.06);}
        .hp-stat::before{content:'';position:absolute;top:0;right:0;width:60px;height:60px;border-radius:50%;opacity:0.06;transform:translate(15px,-15px);}
        .hp-stat-red::before{background:#dc2626;}
        .hp-stat-emerald::before{background:#059669;}
        .hp-stat-amber::before{background:#d97706;}
        .hp-stat-blue::before{background:#3b82f6;}
        .hp-stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;}
        .hp-stat-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;}
        .hp-ic-red{background:#fef2f2;color:#dc2626;}
        .hp-ic-emerald{background:#ecfdf5;color:#059669;}
        .hp-ic-amber{background:#fffbeb;color:#d97706;}
        .hp-ic-blue{background:#eff6ff;color:#3b82f6;}
        .hp-stat-tag{font-size:0.58rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;padding:3px 8px;border-radius:99px;}
        .hp-tag-red{background:#fef2f2;color:#991b1b;}
        .hp-tag-emerald{background:#ecfdf5;color:#065f46;}
        .hp-tag-amber{background:#fffbeb;color:#92400e;}
        .hp-tag-blue{background:#eff6ff;color:#1e40af;}
        .hp-stat-lbl{font-size:0.68rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;}
        .hp-stat-val{font-size:1.15rem;font-weight:900;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;letter-spacing:-0.02em;margin-top:0.2rem;}
        .hp-val-red{color:#dc2626;}
        .hp-val-emerald{color:#059669;}
        .hp-val-amber{color:#d97706;}
        .hp-val-blue{color:#3b82f6;}
        .hp-stat-sub{font-size:0.68rem;color:#94a3b8;margin-top:0.25rem;font-weight:500;}

        /* ── CARD ── */
        .hp-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,0.04);overflow:hidden;margin-bottom:1rem;}
        .hp-card-head{padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;border-bottom:1px solid #f1f5f9;background:linear-gradient(180deg,#fafbfc,#fff);}
        .hp-card-title{font-size:0.875rem;font-weight:800;margin:0;display:flex;align-items:center;gap:8px;color:#0f172a;}
        .hp-card-title svg{color:#94a3b8;}
        .hp-card-sub{font-size:0.72rem;color:#64748b;margin-top:2px;font-weight:500;}
        .hp-card-count{font-size:0.72rem;font-weight:700;color:#d97706;background:#fef3c7;padding:3px 10px;border-radius:99px;}
        .hp-card-tag{font-size:0.65rem;font-weight:700;color:#4f46e5;background:#e0e7ff;padding:3px 8px;border-radius:99px;}

        /* ── FILTER ── */
        .hp-filter{display:flex;flex-wrap:wrap;gap:0.65rem;padding:1rem 1.25rem;align-items:flex-end;background:#f8fafc;}
        .hp-fg{display:flex;flex-direction:column;gap:0.25rem;min-width:130px;flex:1;}
        .hp-fl{font-size:0.62rem;font-weight:700;text-transform:uppercase;color:#94a3b8;letter-spacing:0.06em;}
        .hp-fi{padding:0.5rem 0.75rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.8rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;transition:all .15s;background:#fff;color:#0f172a;font-weight:500;}
        .hp-fi:focus{outline:none;border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.1);}
        select.hp-fi{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.6rem center;padding-right:1.75rem;cursor:pointer;}
        .hp-filter-btns{display:flex;gap:0.4rem;align-self:flex-end;}
        .hp-btn{display:inline-flex;align-items:center;gap:0.35rem;padding:0.5rem 0.875rem;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;transition:all .15s;border:none;text-decoration:none;white-space:nowrap;font-family:inherit;}
        .hp-btn svg{width:14px;height:14px;}
        .hp-btn-dark{background:#0f172a;color:#fff;}
        .hp-btn-dark:hover{background:#1e293b;}
        .hp-btn-ghost{background:transparent;color:#64748b;border:1.5px solid #e2e8f0;}
        .hp-btn-ghost:hover{background:#f1f5f9;color:#0f172a;border-color:#94a3b8;}
        .hp-btn-amber{background:#d97706;color:#fff;}
        .hp-btn-amber:hover{background:#b45309;}
        .hp-btn-blue{background:#3b82f6;color:#fff;}
        .hp-btn-blue:hover{background:#2563eb;}
        .hp-btn-sm{padding:0.35rem 0.65rem;font-size:0.72rem;}
        .hp-btn-detail{background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;}
        .hp-btn-detail:hover{background:#dbeafe;}
        .hp-btn-danger{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;}
        .hp-btn-danger:hover{background:#fee2e2;}

        /* ── GRID LAYOUT ── */
        .hp-grid{display:grid;grid-template-columns:1fr 340px;gap:1.25rem;align-items:start;}
        .hp-main{min-width:0;}

        /* ── TABLE ── */
        .hp-table-scroll{width:100%;overflow-x:auto;}
        .hp-table{width:100%;border-collapse:collapse;min-width:900px;}
        .hp-table thead th{background:linear-gradient(180deg,#f8fafc,#f4f8fc);padding:0.75rem 1rem;text-align:left;font-size:0.62rem;font-weight:700;text-transform:uppercase;color:#94a3b8;border-bottom:2px solid #e2e8f0;letter-spacing:0.04em;}
        .hp-table tbody td{padding:0.8rem 1rem;border-bottom:1px solid #f1f5f9;font-size:0.8125rem;vertical-align:middle;}
        .hp-table tbody tr:last-child td{border-bottom:none;}
        .hp-table tbody tr{transition:background .15s;}
        .hp-table tbody tr:hover{background:#f8fafc;}
        .hp-table tbody tr.hp-overdue{background:#fffbeb;}
        .hp-table tbody tr.hp-overdue:hover{background:#fef3c7;}
        .th-num{width:40px;text-align:center;}
        .th-c{text-align:center;}
        .th-r{text-align:right;}
        .td-num{text-align:center;color:#94a3b8;font-size:0.78rem;font-weight:600;font-family:ui-monospace,monospace;}
        .td-cred-link{font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.78rem;color:#0f172a;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;}
        .td-cred-link:hover{color:#d97706;text-decoration:underline;}
        .td-customer{font-weight:700;color:#0f172a;font-size:0.8rem;}
        .td-date{font-weight:600;color:#0f172a;font-size:0.78rem;}
        .td-date-overdue{color:#dc2626;font-weight:700;}
        .td-date-sub{font-size:0.65rem;color:#b91c1c;font-weight:600;}
        .td-amount{font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;font-weight:700;font-size:0.78rem;white-space:nowrap;}
        .td-amount-danger{color:#dc2626;}
        .td-amount-success{color:#059669;}
        .td-amount-muted{color:#64748b;}

        /* ── BADGES ── */
        .td-badge{display:inline-flex;align-items:center;gap:0.2rem;padding:2px 8px;border-radius:99px;font-size:0.63rem;font-weight:700;white-space:nowrap;}
        .td-badge svg{width:10px;height:10px;}
        .td-badge-debt{background:#fef2f2;color:#991b1b;}
        .td-badge-credit{background:#eff6ff;color:#1e40af;}
        .td-badge-unpaid{background:#fef2f2;color:#991b1b;}
        .td-badge-partial{background:#fef3c7;color:#92400e;}
        .td-badge-paid{background:#dcfce7;color:#15803d;}
        .td-badge-overdue{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}

        /* ── PROGRESS BAR ── */
        .td-prog-wrap{display:flex;align-items:center;gap:0.5rem;}
        .td-prog{width:60px;height:6px;background:#f1f5f9;border-radius:99px;overflow:hidden;flex-shrink:0;}
        .td-prog-fill{height:100%;border-radius:99px;transition:width .3s;}

        /* ── ACTION CELL ── */
        .td-actions{display:flex;gap:0.35rem;align-items:center;}

        /* ── EMPTY STATE ── */
        .td-empty{text-align:center;padding:3rem 1rem !important;}
        .hp-empty{display:flex;flex-direction:column;align-items:center;}
        .hp-empty-ico{width:56px;height:56px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;}
        .hp-empty-ico svg{color:#94a3b8;}
        .hp-empty-title{font-size:0.9375rem;font-weight:800;color:#64748b;}
        .hp-empty-sub{font-size:0.8rem;color:#94a3b8;margin-top:0.25rem;font-weight:500;}
        .hp-empty-actions{margin-top:1rem;display:flex;gap:0.5rem;justify-content:center;}

        /* ── SIDEBAR LISTS ── */
        .hp-list{padding:0;}
        .hp-list-item{display:flex;align-items:center;gap:10px;padding:0.75rem 1.15rem;border-bottom:1px solid #f1f5f9;transition:background .1s;}
        .hp-list-item:last-child{border-bottom:none;}
        .hp-list-item:hover{background:#fafafa;}
        .hp-rank{width:24px;height:24px;border-radius:7px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:800;color:#64748b;flex-shrink:0;}
        .hp-rank-top{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e;}
        .hp-list-body{flex:1;min-width:0;}
        .hp-list-name{font-weight:700;font-size:0.8rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .hp-list-meta{font-size:0.68rem;color:#94a3b8;font-weight:500;}
        .hp-list-amount{font-size:0.78rem;font-weight:800;color:#dc2626;white-space:nowrap;flex-shrink:0;font-family:ui-monospace,'Cascadia Code','Fira Code',monospace;}
        .hp-list-empty{padding:1.5rem;text-align:center;font-size:0.82rem;color:#94a3b8;font-style:italic;}

        /* ── ALERTS ── */
        .hp-alert{padding:0.75rem 1.15rem;border-radius:10px;font-size:0.82rem;font-weight:600;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;animation:hp-fadeIn .3s ease;}
        .hp-alert-success{background:#dcfce7;color:#15803d;border:1px solid #a7f3d0;}
        .hp-alert-danger{background:#fee2e2;color:#991b1b;border:1px solid #fecaca;}
        .hp-alert svg{width:16px;height:16px;flex-shrink:0;}
        @keyframes hp-fadeIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}

        /* ── PAGINATION ── */
        .hp-pagination{padding:0.85rem 1.25rem;border-top:1px solid #f1f5f9;}

        /* ── RESPONSIVE ── */
        @@media(max-width:1024px){
            .hp-stats{grid-template-columns:repeat(2,1fr);}
            .hp-grid{grid-template-columns:1fr;}
            .hp-hero-amount{font-size:2rem;}
        }
        @@media(max-width:768px){
            .hp-filter{flex-direction:column;}
            .hp-fg{width:100%;min-width:100%;}
            .hp-hero{padding:1.5rem 1.25rem 3rem;border-radius:14px;}
            .hp-hero-title{font-size:1.35rem;}
            .hp-hero-amount{font-size:1.75rem;}
            .hp-hero-stats{flex-direction:column;align-items:flex-start;}
            .hp-hero-right{align-items:flex-start;flex-direction:row;gap:12px;}
            .hp-hero-actions{width:100%;}
            .hp-hero-btn{flex:1;justify-content:center;}
        }
        @@media(max-width:480px){
            .hp-stats{grid-template-columns:1fr;}
        }
    </style>
    @endpush

    <div class="hp-page">

        {{-- ─── HERO HEADER ─── --}}
        <div class="hp-hero">
            <div class="hp-hero-inner">
                <div class="hp-hero-top">
                    <div>
                        <div class="hp-hero-badge">
                            <span class="hp-hero-dot"></span>
                            Hutang &amp; Piutang
                        </div>
                        <h1 class="hp-hero-title">Piutang Aktif</h1>
                        <p class="hp-hero-sub">Pantau seluruh hutang dan piutang pelanggan yang belum lunas.</p>
                    </div>
                    <div class="hp-hero-actions">
                        <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="hp-hero-btn hp-hero-btn-amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Hutang Baru
                        </a>
                        <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit']) }}" class="hp-hero-btn hp-hero-btn-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Piutang Baru
                        </a>
                    </div>
                </div>
                <div class="hp-hero-stats">
                    <div>
                        <div class="hp-hero-label">Total Outstanding</div>
                        <div class="hp-hero-amount">
                            <span class="hp-hero-rp">Rp</span>{{ number_format($totalDebt + $totalCredit, 0, ',', '.') }}
                        </div>
                        <div class="hp-hero-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            {{ $customerCount }} Pelanggan Aktif
                        </div>
                    </div>
                    <div class="hp-hero-right">
                        <div>
                            <div class="hp-hero-count">{{ $totalActive }}</div>
                            <div class="hp-hero-count-label">Total Transaksi Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="hp-stats">
            <div class="hp-stat hp-stat-red">
                <div class="hp-stat-top">
                    <div class="hp-stat-ico hp-ic-red">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <span class="hp-stat-tag hp-tag-red">Hutang</span>
                </div>
                <div class="hp-stat-lbl">Total Hutang Pelanggan</div>
                <div class="hp-stat-val hp-val-red">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                <div class="hp-stat-sub">Yang belum dibayar pelanggan</div>
            </div>
            <div class="hp-stat hp-stat-emerald">
                <div class="hp-stat-top">
                    <div class="hp-stat-ico hp-ic-emerald">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span class="hp-stat-tag hp-tag-emerald">Piutang</span>
                </div>
                <div class="hp-stat-lbl">Total Piutang / Limit</div>
                <div class="hp-stat-val hp-val-emerald">Rp {{ number_format($totalCredit, 0, ',', '.') }}</div>
                <div class="hp-stat-sub">Yang belum diterima dari pelanggan</div>
            </div>
            <div class="hp-stat hp-stat-amber">
                <div class="hp-stat-top">
                    <div class="hp-stat-ico hp-ic-amber">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <span class="hp-stat-tag hp-tag-amber">Jatuh Tempo</span>
                </div>
                <div class="hp-stat-lbl">Transaksi Jatuh Tempo</div>
                <div class="hp-stat-val hp-val-amber">{{ $overdueCount }} Nota</div>
                <div class="hp-stat-sub">Rp {{ number_format($overdueAmount, 0, ',', '.') }}</div>
            </div>
            <div class="hp-stat hp-stat-blue">
                <div class="hp-stat-top">
                    <div class="hp-stat-ico hp-ic-blue">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="hp-stat-tag hp-tag-blue">Pelanggan</span>
                </div>
                <div class="hp-stat-lbl">Pelanggan Berhutang</div>
                <div class="hp-stat-val hp-val-blue">{{ $customerCount }} Orang</div>
                <div class="hp-stat-sub">Dengan piutang aktif</div>
            </div>
        </div>

        {{-- ─── ALERTS ─── --}}
        @if(session('success'))
            <div class="hp-alert hp-alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="hp-alert hp-alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ─── FILTER CARD ─── --}}
        <div class="hp-card">
            <form method="GET" action="{{ route('hutang.piutang') }}" class="hp-filter">
                <div class="hp-fg">
                    <label class="hp-fl">Pencarian</label>
                    <input type="text" name="search" class="hp-fi" placeholder="No. kredit atau nama pelanggan..." value="{{ request('search') }}">
                </div>
                <div class="hp-fg">
                    <label class="hp-fl">Jenis</label>
                    <select name="type" class="hp-fi">
                        <option value="">Semua</option>
                        <option value="debt" @selected(request('type') == 'debt')>Hutang</option>
                        <option value="credit" @selected(request('type') == 'credit')>Piutang</option>
                    </select>
                </div>
                <div class="hp-fg">
                    <label class="hp-fl">Status</label>
                    <select name="status" class="hp-fi">
                        <option value="">Semua</option>
                        <option value="unpaid" @selected(request('status') == 'unpaid')>Belum Lunas</option>
                        <option value="partial" @selected(request('status') == 'partial')>Sebagian</option>
                    </select>
                </div>
                <div class="hp-filter-btns">
                    <button type="submit" class="hp-btn hp-btn-dark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'type', 'status']))
                        <a href="{{ route('hutang.piutang') }}" class="hp-btn hp-btn-ghost">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ─── MAIN CONTENT GRID ─── --}}
        <div class="hp-grid">

            {{-- LEFT: Transaction Table --}}
            <div class="hp-main">
                <div class="hp-card">
                    <div class="hp-card-head">
                        <div>
                            <h3 class="hp-card-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Daftar Piutang Aktif
                            </h3>
                            <div class="hp-card-sub">Menampilkan {{ $credits->total() }} transaksi</div>
                        </div>
                        <span class="hp-card-count">{{ $credits->total() }} Data</span>
                    </div>
                    <div class="hp-table-scroll">
                        <table class="hp-table">
                            <thead>
                                <tr>
                                    <th class="th-num">#</th>
                                    <th>No. Kredit</th>
                                    <th class="th-c">Jenis</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Jatuh Tempo</th>
                                    <th class="th-r">Jumlah</th>
                                    <th class="th-r">Terbayar</th>
                                    <th>Sisa</th>
                                    <th class="th-c">Status</th>
                                    <th class="th-c">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($credits as $i => $cr)
                                @php
                                    $isOver = $cr->isOverdue();
                                    $remaining = (int) $cr->remaining_amount;
                                    $pct = (float) $cr->amount > 0 ? min(100, ((float) $cr->paid_amount / (float) $cr->amount) * 100) : 0;
                                    $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
                                @endphp
                                <tr class="{{ $isOver ? 'hp-overdue' : '' }}">
                                    <td class="td-num">{{ $credits->firstItem() + $i }}</td>
                                    <td>
                                        <a href="{{ route('pelanggan.kredit.show', $cr) }}" class="td-cred-link">
                                            {{ $cr->credit_number }}
                                            @if($isOver)
                                                <span class="td-badge td-badge-overdue">Telat</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="th-c">
                                        @if($cr->type === 'debt')
                                            <span class="td-badge td-badge-debt">Hutang</span>
                                        @else
                                            <span class="td-badge td-badge-credit">Piutang</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="td-customer">{{ $cr->customer->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="td-date">{{ $cr->transaction_date->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="td-date {{ $isOver ? 'td-date-overdue' : '' }}">
                                            {{ $cr->due_date ? $cr->due_date->format('d/m/Y') : '-' }}
                                        </span>
                                        @if($isOver)
                                            <div class="td-date-sub">{{ $cr->due_date->diffForHumans(null, true) }} lalu</div>
                                        @endif
                                    </td>
                                    <td class="th-r">
                                        <span class="td-amount">Rp {{ number_format((float) $cr->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="th-r">
                                        <span class="td-amount td-amount-success">Rp {{ number_format((float) $cr->paid_amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <div class="td-prog-wrap">
                                            <div class="td-prog">
                                                <div class="td-prog-fill" style="width:{{ (int) $pct }}%;background:{{ $barColor }};"></div>
                                            </div>
                                            <span class="td-amount {{ $remaining > 0 ? 'td-amount-danger' : 'td-amount-success' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="th-c">
                                        @if($cr->status === 'paid')
                                            <span class="td-badge td-badge-paid">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                Lunas
                                            </span>
                                        @elseif($cr->status === 'partial')
                                            <span class="td-badge td-badge-partial">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                                Sebagian
                                            </span>
                                        @else
                                            <span class="td-badge td-badge-unpaid">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                Belum Lunas
                                            </span>
                                        @endif
                                    </td>
                                    <td class="th-c">
                                        <div class="td-actions">
                                            <a href="{{ route('pelanggan.kredit.show', $cr) }}" class="hp-btn hp-btn-detail hp-btn-sm">Detail</a>
                                            @can('delete_hutang_piutang')
                                                @if($cr->status === 'unpaid')
                                                <form action="{{ route('pelanggan.kredit.destroy', $cr) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus {{ $cr->credit_number }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="hp-btn hp-btn-danger hp-btn-sm" title="Hapus">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="td-empty">
                                        <div class="hp-empty">
                                            <div class="hp-empty-ico">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            </div>
                                            <div class="hp-empty-title">Belum Ada Data</div>
                                            <div class="hp-empty-sub">Catat hutang atau piutang pertama Anda untuk mulai melacak.</div>
                                            <div class="hp-empty-actions">
                                                <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}" class="hp-btn hp-btn-amber">+ Hutang Baru</a>
                                                <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit']) }}" class="hp-btn hp-btn-blue">+ Piutang Baru</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($credits->hasPages())
                        <div class="hp-pagination">{{ $credits->links() }}</div>
                    @endif
                </div>
            </div>

            {{-- RIGHT: Sidebar Panels --}}
            <div class="hp-sidebar">

                {{-- Top Debtors --}}
                <div class="hp-card">
                    <div class="hp-card-head">
                        <h3 class="hp-card-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Top Debitur
                        </h3>
                        <span class="hp-card-tag">Hutang Terbesar</span>
                    </div>
                    <div class="hp-list">
                        @forelse ($topDebtors as $i => $td)
                            <div class="hp-list-item">
                                <div class="hp-rank {{ $i < 3 ? 'hp-rank-top' : '' }}">{{ $i + 1 }}</div>
                                <div class="hp-list-body">
                                    <div class="hp-list-name" title="{{ $td->customer->name ?? 'Unknown' }}">{{ $td->customer->name ?? 'Unknown' }}</div>
                                    <div class="hp-list-meta">Pelanggan</div>
                                </div>
                                <div class="hp-list-amount">
                                    Rp {{ number_format($td->total_remaining, 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div class="hp-list-empty">Belum ada data.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
