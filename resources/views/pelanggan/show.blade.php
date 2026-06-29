<x-app-layout>
    <x-slot name="header">Detail Pelanggan — {{ $pelanggan->name }}</x-slot>

    @push('styles')
    <style>
        :root{--pg-radius:14px;--pg-surface:#fff;--pg-border:#e2e8f0;--pg-text:#0f172a;--pg-muted:#64748b;--pg-blue:#3b82f6;--pg-red:#ef4444;--pg-green:#10b981;--pg-amber:#f59e0b;--pg-purple:#7c3aed;--pg-indigo:#4f46e5;}
        .pg-page{max-width:1200px;margin:0 auto;padding:2rem 1.5rem 4rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:var(--pg-text);}
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        /* ── BREADCRUMB ── */
        .pg-breadcrumb{display:flex;align-items:center;gap:6px;margin-bottom:1.25rem;font-size:0.85rem;}
        .pg-breadcrumb a{display:flex;align-items:center;gap:4px;text-decoration:none;color:var(--pg-muted);font-weight:700;transition:color .15s;}
        .pg-breadcrumb a:hover{color:var(--pg-blue);}
        .pg-breadcrumb .sep{color:#cbd5e1;}
        .pg-breadcrumb .current{font-weight:600;color:var(--pg-text);}

        /* ── HERO ── */
        .pg-hero{background:linear-gradient(135deg,#06090f 0%,#0d1322 35%,#111827 70%,#0a0e1a 100%);border-radius:20px;padding:2rem 2.25rem 2.5rem;margin-bottom:1.75rem;position:relative;overflow:hidden;}
        .pg-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 85% 20%,rgba(99,102,241,.22) 0%,transparent 60%),radial-gradient(ellipse at 15% 80%,rgba(16,185,129,.1) 0%,transparent 50%);}
        .pg-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(99,102,241,.5),rgba(16,185,129,.3),transparent);}
        .pg-hero-inner{position:relative;z-index:1;}
        .pg-hero-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .pg-hero-avatar{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:900;color:#fff;background:linear-gradient(135deg,#6366f1,#4f46e5);flex-shrink:0;margin-right:1rem;}
        .pg-hero-name{font-size:1.75rem;font-weight:900;color:#fff;letter-spacing:-0.04em;line-height:1.1;margin-bottom:0.3rem;}
        .pg-hero-meta{display:flex;flex-wrap:wrap;gap:0.75rem;font-size:0.8rem;color:rgba(255,255,255,.5);margin-top:0.5rem;}
        .pg-hero-meta span{display:flex;align-items:center;gap:5px;}
        .pg-hero-actions{display:flex;gap:0.5rem;flex-wrap:wrap;}
        .pg-hero-btn{display:inline-flex;align-items:center;gap:5px;padding:0.5rem 1rem;border-radius:8px;font-size:0.78rem;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .15s;}
        .pg-hero-btn-primary{background:rgba(99,102,241,.2);color:#a5b4fc;border:1px solid rgba(99,102,241,.3);}
        .pg-hero-btn-primary:hover{background:rgba(99,102,241,.35);color:#c7d2fe;}
        .pg-hero-btn-ghost{background:rgba(255,255,255,.07);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.1);}
        .pg-hero-btn-ghost:hover{background:rgba(255,255,255,.14);color:#fff;}
        .pg-hero-btn-danger{background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.25);}
        .pg-hero-btn-danger:hover{background:rgba(239,68,68,.25);}
        .pg-status-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:99px;font-size:0.7rem;font-weight:700;}
        .pg-status-active{background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.25);color:#6ee7b7;}
        .pg-status-inactive{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.45);}
        .pg-cat-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:99px;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;}
        .pg-cat-eceran{background:rgba(20,184,166,.15);color:#5eead4;border:1px solid rgba(20,184,166,.25);}
        .pg-cat-grosir{background:rgba(139,92,246,.15);color:#c4b5fd;border:1px solid rgba(139,92,246,.25);}
        .pg-cat-pos{background:rgba(59,130,246,.15);color:#93c5fd;border:1px solid rgba(59,130,246,.25);}

        /* ── STAT CARDS ── */
        .pg-stats{display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:1.5rem;}
        .pg-stat{background:var(--pg-surface);border:1px solid var(--pg-border);border-radius:12px;padding:1.15rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.03);position:relative;overflow:hidden;}
        .pg-stat::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;border-radius:4px 0 0 4px;}
        .pg-stat.s-red::before{background:var(--pg-red);}
        .pg-stat.s-blue::before{background:var(--pg-blue);}
        .pg-stat.s-green::before{background:var(--pg-green);}
        .pg-stat.s-purple::before{background:var(--pg-purple);}
        .pg-stat.s-amber::before{background:var(--pg-amber);}
        .pg-stat-label{font-size:0.65rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.4rem;}
        .pg-stat-val{font-size:1.25rem;font-weight:900;font-family:ui-monospace,monospace;letter-spacing:-0.02em;line-height:1;}
        .pg-stat-val.v-red{color:var(--pg-red);}
        .pg-stat-val.v-blue{color:var(--pg-blue);}
        .pg-stat-val.v-green{color:var(--pg-green);}
        .pg-stat-val.v-purple{color:var(--pg-purple);}
        .pg-stat-val.v-amber{color:var(--pg-amber);}
        .pg-stat-sub{font-size:0.65rem;color:#94a3b8;margin-top:0.3rem;font-weight:500;}

        /* ── LAYOUT ── */
        .pg-grid{display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;}

        /* ── CARDS ── */
        .pg-card{background:var(--pg-surface);border:1px solid var(--pg-border);border-radius:var(--pg-radius);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.03);margin-bottom:1.25rem;}
        .pg-card-head{padding:1rem 1.35rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;}
        .pg-card-title{font-size:0.9rem;font-weight:800;color:var(--pg-text);display:flex;align-items:center;gap:8px;}
        .pg-card-title-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .pg-card-title-icon.red{background:#fef2f2;color:#ef4444;}
        .pg-card-title-icon.blue{background:#eff6ff;color:#3b82f6;}
        .pg-card-title-icon.purple{background:#faf5ff;color:#7c3aed;}
        .pg-card-count{font-size:0.65rem;font-weight:700;background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:99px;}
        .pg-card-body{padding:0;}

        /* ── TABLE ── */
        .pg-tbl-wrap{overflow-x:auto;}
        .pg-tbl{width:100%;border-collapse:collapse;font-size:0.78rem;}
        .pg-tbl th{text-align:left;padding:0.6rem 1rem;font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;border-bottom:2px solid #f3f4f6;white-space:nowrap;}
        .pg-tbl td{padding:0.65rem 1rem;border-bottom:1px solid #f9fafb;color:#374151;vertical-align:middle;}
        .pg-tbl tr:last-child td{border-bottom:none;}
        .pg-tbl tr:hover{background:#fafafa;}
        .pg-tbl .tr{text-align:right;}
        .pg-tbl .tc{text-align:center;}
        .pg-mono{font-family:ui-monospace,monospace;font-size:0.73rem;}
        .pg-bold{font-weight:700;}

        /* ── BADGES ── */
        .pg-badge{display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:999px;font-size:0.63rem;font-weight:700;white-space:nowrap;}
        .pg-badge-blue{background:#dbeafe;color:#1e40af;}
        .pg-badge-purple{background:#f3e8ff;color:#6b21a8;}
        .pg-badge-green{background:#dcfce7;color:#166534;}
        .pg-badge-red{background:#fee2e2;color:#991b1b;}
        .pg-badge-amber{background:#fef3c7;color:#92400e;}
        .pg-badge-gray{background:#f1f5f9;color:#64748b;}
        .pg-badge-overdue{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;}

        /* ── SIDEBAR ── */
        .pg-sidebar .pg-card{margin-bottom:1rem;}
        .pg-sidebar-head{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;font-weight:700;font-size:0.85rem;color:var(--pg-text);}
        .pg-sidebar-body{padding:1rem 1.25rem;}
        .pg-sidebar-btn{display:flex;align-items:center;gap:8px;width:100%;padding:0.6rem 0.875rem;border-radius:8px;font-size:0.8rem;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .15s;margin-bottom:0.5rem;}
        .pg-sidebar-btn:last-child{margin-bottom:0;}
        .pg-sidebar-btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;box-shadow:0 2px 6px rgba(79,70,229,.2);}
        .pg-sidebar-btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 10px rgba(79,70,229,.3);}
        .pg-sidebar-btn-secondary{background:#f8fafc;color:#475569;border:1px solid #e2e8f0;}
        .pg-sidebar-btn-secondary:hover{background:#f1f5f9;color:#1e293b;}
        .pg-sidebar-btn-danger{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;}
        .pg-sidebar-btn-danger:hover{background:#fee2e2;}

        .pg-info-row{display:flex;justify-content:space-between;padding:0.4rem 0;font-size:0.8rem;border-bottom:1px solid #f8fafc;}
        .pg-info-row:last-child{border-bottom:none;}
        .pg-info-label{color:var(--pg-muted);font-weight:500;}
        .pg-info-val{font-weight:700;color:var(--pg-text);}

        .pg-notes{font-size:0.82rem;color:#475569;line-height:1.5;white-space:pre-wrap;}

        .pg-empty{padding:2.5rem 1.5rem;text-align:center;color:#94a3b8;}
        .pg-empty-icon{width:48px;height:48px;border-radius:12px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;color:#cbd5e1;}
        .pg-empty-text{font-size:0.85rem;font-weight:600;color:#64748b;}
        .pg-empty-sub{font-size:0.78rem;color:#94a3b8;margin-top:0.2rem;}

        .pg-debt-row{display:flex;align-items:center;gap:1rem;padding:0.85rem 1.25rem;border-bottom:1px solid #f8fafc;transition:background .1s;}
        .pg-debt-row:last-child{border-bottom:none;}
        .pg-debt-row:hover{background:#fafafa;}
        .pg-debt-row.overdue{background:#fffbeb;}
        .pg-debt-info{flex:1;min-width:0;}
        .pg-debt-number{font-weight:700;font-size:0.8rem;color:var(--pg-indigo);font-family:ui-monospace,monospace;}
        .pg-debt-desc{font-size:0.78rem;color:#475569;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .pg-debt-due{font-size:0.7rem;color:#94a3b8;margin-top:2px;}
        .pg-debt-due.overdue{color:#ef4444;font-weight:600;}
        .pg-debt-amount{text-align:right;flex-shrink:0;}
        .pg-debt-amount .label{font-size:0.6rem;color:#94a3b8;font-weight:600;text-transform:uppercase;}
        .pg-debt-amount .value{font-size:0.9rem;font-weight:800;color:var(--pg-red);font-family:ui-monospace,monospace;}

        .pg-trx-row{display:flex;align-items:center;gap:0.85rem;padding:0.75rem 1.25rem;border-bottom:1px solid #f8fafc;transition:background .1s;}
        .pg-trx-row:last-child{border-bottom:none;}
        .pg-trx-row:hover{background:#fafafa;}
        .pg-trx-date{text-align:center;flex-shrink:0;width:48px;}
        .pg-trx-date .day{font-size:1.1rem;font-weight:900;color:var(--pg-text);line-height:1;}
        .pg-trx-date .month{font-size:0.6rem;font-weight:700;color:#94a3b8;text-transform:uppercase;}
        .pg-trx-info{flex:1;min-width:0;}
        .pg-trx-inv{font-weight:700;font-size:0.8rem;color:var(--pg-indigo);font-family:ui-monospace,monospace;text-decoration:none;}
        .pg-trx-inv:hover{text-decoration:underline;}
        .pg-trx-items{font-size:0.72rem;color:#94a3b8;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .pg-trx-meta{text-align:right;flex-shrink:0;}
        .pg-trx-amount{font-size:0.85rem;font-weight:800;color:var(--pg-text);font-family:ui-monospace,monospace;}
        .pg-trx-method{font-size:0.65rem;color:#94a3b8;margin-top:2px;}

        @media(max-width:1024px){
            .pg-grid{grid-template-columns:1fr;}
            .pg-stats{grid-template-columns:repeat(3,1fr);}
        }
        @media(max-width:640px){
            .pg-stats{grid-template-columns:1fr 1fr;}
            .pg-hero-name{font-size:1.3rem;}
        }
    </style>
    @endpush

    <div class="pg-page">

        @if(session('success'))
            <div style="display:flex;align-items:center;gap:10px;padding:0.8rem 1.1rem;border-radius:10px;margin-bottom:1rem;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;font-weight:600;font-size:0.82rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="display:flex;align-items:center;gap:10px;padding:0.8rem 1.1rem;border-radius:10px;margin-bottom:1rem;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;font-weight:600;font-size:0.82rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- BREADCRUMB --}}
        <nav class="pg-breadcrumb">
            <a href="{{ route('pelanggan.index') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Pelanggan
            </a>
            <span class="sep">/</span>
            <span class="current">{{ $pelanggan->name }}</span>
        </nav>

        {{-- HERO --}}
        <div class="pg-hero">
            <div class="pg-hero-inner">
                <div class="pg-hero-top">
                    <div style="display:flex;align-items:center;">
                        <div class="pg-hero-avatar">{{ strtoupper(substr($pelanggan->name, 0, 1)) }}</div>
                        <div>
                            <div class="pg-hero-name">{{ $pelanggan->name }}</div>
                            <div style="display:flex;gap:8px;align-items:center;margin-top:6px;flex-wrap:wrap;">
                                <span class="pg-cat-badge pg-cat-{{ $pelanggan->category }}">{{ ucfirst($pelanggan->category) }}</span>
                                <span class="pg-status-badge {{ $pelanggan->is_active ? 'pg-status-active' : 'pg-status-inactive' }}">
                                    @if($pelanggan->is_active)
                                        <span style="width:6px;height:6px;background:#22c55e;border-radius:50%;"></span> Aktif
                                    @else
                                        Nonaktif
                                    @endif
                                </span>
                            </div>
                            <div class="pg-hero-meta">
                                @if($pelanggan->phone)
                                    <span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        {{ $pelanggan->phone }}
                                    </span>
                                @endif
                                @if($pelanggan->email)
                                    <span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        {{ $pelanggan->email }}
                                    </span>
                                @endif
                                @if($pelanggan->address)
                                    <span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ Str::limit($pelanggan->address, 60) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="pg-hero-actions">
                        <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="pg-hero-btn pg-hero-btn-primary">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST" onsubmit="return confirm('Yakin menghapus pelanggan {{ $pelanggan->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="pg-hero-btn pg-hero-btn-danger">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="pg-stats">
            <div class="pg-stat s-red">
                <div class="pg-stat-label">Hutang Aktif</div>
                <div class="pg-stat-val v-red">Rp {{ number_format($pelanggan->current_debt, 0, ',', '.') }}</div>
                <div class="pg-stat-sub">{{ $activeDebts->count() }} catatan aktif</div>
            </div>
            <div class="pg-stat s-blue">
                <div class="pg-stat-label">Limit Kredit</div>
                <div class="pg-stat-val v-blue">Rp {{ number_format($pelanggan->credit_limit, 0, ',', '.') }}</div>
                <div class="pg-stat-sub">Batas maksimal</div>
            </div>
            <div class="pg-stat s-green">
                <div class="pg-stat-label">Sisa Limit</div>
                <div class="pg-stat-val v-green">Rp {{ number_format($pelanggan->remaining_credit_limit, 0, ',', '.') }}</div>
                <div class="pg-stat-sub">Tersedia</div>
            </div>
            <div class="pg-stat s-purple">
                <div class="pg-stat-label">Total Transaksi</div>
                <div class="pg-stat-val v-purple">{{ number_format($totalTransactions) }}x</div>
                <div class="pg-stat-sub">Selesai</div>
            </div>
            <div class="pg-stat s-amber">
                <div class="pg-stat-label">Total Belanja</div>
                <div class="pg-stat-val v-amber">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</div>
                <div class="pg-stat-sub">Seluruh periode</div>
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="pg-grid">

            {{-- LEFT COLUMN --}}
            <div>

                {{-- Active Debts --}}
                @if($activeDebts->count() > 0)
                <div class="pg-card">
                    <div class="pg-card-head">
                        <div class="pg-card-title">
                            <div class="pg-card-title-icon red">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            Hutang Belum Lunas
                        </div>
                        <span class="pg-card-count">{{ $activeDebts->count() }} catatan</span>
                    </div>
                    <div class="pg-card-body">
                        @foreach($activeDebts as $d)
                        <div class="pg-debt-row {{ $d->isOverdue() ? 'overdue' : '' }}">
                            <div class="pg-debt-info">
                                <div class="pg-debt-number">{{ $d->credit_number }}</div>
                                <div class="pg-debt-desc">{{ $d->description }}</div>
                                <div class="pg-debt-due {{ $d->isOverdue() ? 'overdue' : '' }}">
                                    @if($d->isOverdue())
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:-1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        Jatuh tempo {{ $d->due_date ? $d->due_date->format('d/m/Y') : '-' }} (TERLAMBAT)
                                    @else
                                        Jatuh tempo: {{ $d->due_date ? $d->due_date->format('d/m/Y') : '-' }}
                                    @endif
                                </div>
                            </div>
                            <div class="pg-debt-amount">
                                <div class="label">Sisa</div>
                                <div class="value">Rp {{ number_format($d->remaining_amount, 0, ',', '.') }}</div>
                            </div>
                            <a href="{{ route('pelanggan.kredit.show', $d) }}" class="pg-hero-btn pg-hero-btn-primary" style="flex-shrink:0;font-size:0.7rem;padding:0.35rem 0.75rem;">Bayar</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Purchase History --}}
                <div class="pg-card">
                    <div class="pg-card-head">
                        <div class="pg-card-title">
                            <div class="pg-card-title-icon blue">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            </div>
                            Riwayat Pembelian
                        </div>
                        <span class="pg-card-count">{{ $purchaseHistory->count() }} transaksi</span>
                    </div>
                    <div class="pg-card-body">
                        @if($purchaseHistory->isEmpty())
                            <div class="pg-empty">
                                <div class="pg-empty-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                </div>
                                <div class="pg-empty-text">Belum ada riwayat pembelian</div>
                                <div class="pg-empty-sub">Transaksi POS akan muncul di sini</div>
                            </div>
                        @else
                            @foreach($purchaseHistory as $trx)
                            <div class="pg-trx-row">
                                <div class="pg-trx-date">
                                    <div class="day">{{ $trx->created_at->format('d') }}</div>
                                    <div class="month">{{ $trx->created_at->format('M') }}</div>
                                </div>
                                <div class="pg-trx-info">
                                    <a href="{{ route('transaksi.show', $trx) }}" class="pg-trx-inv">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</a>
                                    <div class="pg-trx-items">
                                        @if($trx->details && $trx->details->isNotEmpty())
                                            @foreach($trx->details->take(3) as $d)
                                                {{ $d->product?->name ?? '?' }} ({{ $d->unit_qty ?? $d->quantity }})@if(!$loop->last),@endif
                                            @endforeach
                                            @if($trx->details->count() > 3)
                                                <span style="color:#94a3b8;"> +{{ $trx->details->count() - 3 }} lagi</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="pg-trx-meta">
                                    <div class="pg-trx-amount">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</div>
                                    <div class="pg-trx-method">
                                        <span class="pg-badge {{ $trx->sale_type === 'grosir' ? 'pg-badge-purple' : 'pg-badge-blue' }}">{{ ucfirst($trx->sale_type ?? 'eceran') }}</span>
                                        <span style="color:#94a3b8;font-size:0.65rem;">{{ strtoupper($trx->payment_method) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Credit History --}}
                <div class="pg-card">
                    <div class="pg-card-head">
                        <div class="pg-card-title">
                            <div class="pg-card-title-icon purple">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            </div>
                            Riwayat Kredit
                        </div>
                        <span class="pg-card-count">{{ $recentCredits->count() }} catatan</span>
                    </div>
                    @if($recentCredits->isEmpty())
                        <div class="pg-empty">
                            <div class="pg-empty-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div class="pg-empty-text">Belum ada riwayat kredit</div>
                        </div>
                    @else
                    <div class="pg-tbl-wrap">
                        <table class="pg-tbl">
                            <thead>
                                <tr>
                                    <th>No. Kredit</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th class="tr">Jumlah</th>
                                    <th class="tc">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCredits as $rc)
                                <tr>
                                    <td><a href="{{ route('pelanggan.kredit.show', $rc) }}" class="pg-trx-inv">{{ $rc->credit_number }}</a></td>
                                    <td>
                                        <span class="pg-badge {{ $rc->type === 'debt' ? 'pg-badge-red' : 'pg-badge-green' }}">
                                            {{ $rc->type === 'debt' ? 'Hutang' : 'Piutang' }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.78rem;">{{ Str::limit($rc->description, 35) }}</td>
                                    <td style="font-size:0.78rem;white-space:nowrap;">{{ $rc->transaction_date->format('d/m/Y') }}</td>
                                    <td class="tr pg-mono pg-bold">Rp {{ number_format($rc->amount, 0, ',', '.') }}</td>
                                    <td class="tc">
                                        @if($rc->status === 'paid')
                                            <span class="pg-badge pg-badge-green">Lunas</span>
                                        @elseif($rc->status === 'partial')
                                            <span class="pg-badge pg-badge-amber">Sebagian</span>
                                        @else
                                            <span class="pg-badge pg-badge-gray">Belum Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="pg-sidebar">

                {{-- Quick Actions --}}
                <div class="pg-card">
                    <div class="pg-sidebar-head">Aksi Cepat</div>
                    <div class="pg-sidebar-body">
                        <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt', 'customer_id'=>$pelanggan->id]) }}" class="pg-sidebar-btn pg-sidebar-btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                            Catat Hutang
                        </a>
                        <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit', 'customer_id'=>$pelanggan->id]) }}" class="pg-sidebar-btn pg-sidebar-btn-secondary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                            Catat Piutang
                        </a>
                        <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="pg-sidebar-btn pg-sidebar-btn-secondary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit Data
                        </a>
                    </div>
                </div>

                {{-- Info --}}
                <div class="pg-card">
                    <div class="pg-sidebar-head">Informasi</div>
                    <div class="pg-sidebar-body">
                        <div class="pg-info-row">
                            <span class="pg-info-label">Telepon</span>
                            <span class="pg-info-val">{{ $pelanggan->phone ?: '-' }}</span>
                        </div>
                        <div class="pg-info-row">
                            <span class="pg-info-label">Email</span>
                            <span class="pg-info-val">{{ $pelanggan->email ?: '-' }}</span>
                        </div>
                        <div class="pg-info-row">
                            <span class="pg-info-label">Kategori</span>
                            <span class="pg-info-val">{{ ucfirst($pelanggan->category) }}</span>
                        </div>
                        <div class="pg-info-row">
                            <span class="pg-info-label">Terdaftar</span>
                            <span class="pg-info-val">{{ $pelanggan->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                @if($pelanggan->notes)
                <div class="pg-card">
                    <div class="pg-sidebar-head">Catatan</div>
                    <div class="pg-sidebar-body">
                        <div class="pg-notes">{{ $pelanggan->notes }}</div>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>

</x-app-layout>
