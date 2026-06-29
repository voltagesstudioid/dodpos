<x-app-layout>
    <x-slot name="header">Detail Kredit #{{ $kredit->credit_number }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        :root{--ck-radius:14px;--ck-surface:#fff;--ck-border:#e2e8f0;--ck-text:#0f172a;--ck-muted:#64748b;--ck-blue:#3b82f6;--ck-red:#ef4444;--ck-green:#10b981;--ck-amber:#f59e0b;--ck-purple:#7c3aed;--ck-indigo:#4f46e5;}
        .ck-page{max-width:1200px;margin:0 auto;padding:2rem 1.5rem 4rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:var(--ck-text);}

        /* ── BREADCRUMB ── */
        .ck-breadcrumb{display:flex;align-items:center;gap:6px;margin-bottom:1.25rem;font-size:0.85rem;}
        .ck-breadcrumb a{display:flex;align-items:center;gap:4px;text-decoration:none;color:var(--ck-muted);font-weight:700;transition:color .15s;}
        .ck-breadcrumb a:hover{color:var(--ck-blue);}
        .ck-breadcrumb .sep{color:#cbd5e1;}
        .ck-breadcrumb .current{font-weight:600;color:var(--ck-text);}

        /* ── HERO ── */
        .ck-hero{border-radius:20px;padding:2rem 2.25rem 2.5rem;margin-bottom:1.75rem;position:relative;overflow:hidden;}
        .ck-hero.debt{background:linear-gradient(135deg,#1a0f0f 0%,#2d1515 35%,#3d1c1c 70%,#2a0f0f 100%);}
        .ck-hero.credit{background:linear-gradient(135deg,#0f1a0f 0%,#152d15 35%,#1c3d1c 70%,#0f2a0f 100%);}
        .ck-hero::before{content:'';position:absolute;inset:0;}
        .ck-hero.debt::before{background:radial-gradient(ellipse at 85% 20%,rgba(239,68,68,.15) 0%,transparent 60%),radial-gradient(ellipse at 15% 80%,rgba(245,158,11,.08) 0%,transparent 50%);}
        .ck-hero.credit::before{background:radial-gradient(ellipse at 85% 20%,rgba(16,185,129,.15) 0%,transparent 60%),radial-gradient(ellipse at 15% 80%,rgba(20,184,166,.08) 0%,transparent 50%);}
        .ck-hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;}
        .ck-hero.debt::after{background:linear-gradient(90deg,transparent,rgba(239,68,68,.4),rgba(245,158,11,.2),transparent);}
        .ck-hero.credit::after{background:linear-gradient(90deg,transparent,rgba(16,185,129,.4),rgba(20,184,166,.2),transparent);}
        .ck-hero-inner{position:relative;z-index:1;}
        .ck-hero-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;}
        .ck-hero-icon{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:1rem;}
        .ck-hero.debt .ck-hero-icon{background:linear-gradient(135deg,#ef4444,#dc2626);}
        .ck-hero.credit .ck-hero-icon{background:linear-gradient(135deg,#10b981,#059669);}
        .ck-hero-icon svg{width:26px;height:26px;color:#fff;}
        .ck-hero-number{font-size:1.5rem;font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;margin-bottom:0.3rem;font-family:ui-monospace,monospace;}
        .ck-hero-type{font-size:0.85rem;color:rgba(255,255,255,.6);margin-top:0.25rem;}
        .ck-hero-type a{color:rgba(255,255,255,.8);text-decoration:none;font-weight:600;}
        .ck-hero-type a:hover{text-decoration:underline;}
        .ck-hero-badges{display:flex;gap:8px;flex-wrap:wrap;margin-top:0.75rem;}
        .ck-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:99px;font-size:0.7rem;font-weight:700;}
        .ck-badge-status-paid{background:rgba(16,185,129,.2);color:#6ee7b7;border:1px solid rgba(16,185,129,.3);}
        .ck-badge-status-partial{background:rgba(245,158,11,.2);color:#fcd34d;border:1px solid rgba(245,158,11,.3);}
        .ck-badge-status-unpaid{background:rgba(239,68,68,.2);color:#fca5a5;border:1px solid rgba(239,68,68,.3);}
        .ck-badge-overdue{background:rgba(239,68,68,.25);color:#fca5a5;border:1px solid rgba(239,68,68,.4);}
        .ck-hero-amount{text-align:right;}
        .ck-hero-amount-label{font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,.5);margin-bottom:0.3rem;}
        .ck-hero-amount-value{font-size:2rem;font-weight:900;color:#fff;font-family:ui-monospace,monospace;letter-spacing:-0.03em;line-height:1;}

        /* ── ALERTS ── */
        .ck-alert{display:flex;align-items:center;gap:10px;padding:0.8rem 1.1rem;border-radius:10px;margin-bottom:1rem;font-weight:600;font-size:0.82rem;border:1px solid;}
        .ck-alert-success{background:#f0fdf4;color:#166534;border-color:#bbf7d0;}
        .ck-alert-danger{background:#fef2f2;color:#991b1b;border-color:#fecaca;}
        .ck-alert svg{width:16px;height:16px;flex-shrink:0;}

        /* ── STATS ── */
        .ck-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;}
        .ck-stat{background:var(--ck-surface);border:1px solid var(--ck-border);border-radius:12px;padding:1.15rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.03);position:relative;overflow:hidden;}
        .ck-stat::before{content:'';position:absolute;top:0;left:0;width:4px;height:100%;border-radius:4px 0 0 4px;}
        .ck-stat.s-gray::before{background:#64748b;}
        .ck-stat.s-green::before{background:var(--ck-green);}
        .ck-stat.s-red::before{background:var(--ck-red);}
        .ck-stat.s-amber::before{background:var(--ck-amber);}
        .ck-stat-label{font-size:0.65rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.4rem;}
        .ck-stat-val{font-size:1.15rem;font-weight:900;font-family:ui-monospace,monospace;letter-spacing:-0.02em;line-height:1;}
        .ck-stat-sub{font-size:0.65rem;color:#94a3b8;margin-top:0.3rem;font-weight:500;}

        /* ── GRID ── */
        .ck-grid{display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;}

        /* ── CARDS ── */
        .ck-card{background:var(--ck-surface);border:1px solid var(--ck-border);border-radius:var(--ck-radius);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.03);margin-bottom:1.25rem;}
        .ck-card-head{padding:1rem 1.35rem;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;}
        .ck-card-title{font-size:0.9rem;font-weight:800;color:var(--ck-text);display:flex;align-items:center;gap:8px;}
        .ck-card-title-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .ck-card-title-icon.green{background:#ecfdf5;color:#059669;}
        .ck-card-title-icon.red{background:#fef2f2;color:#ef4444;}
        .ck-card-count{font-size:0.65rem;font-weight:700;background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:99px;}
        .ck-card-body{padding:1.35rem;}

        /* ── PROGRESS ── */
        .ck-progress{margin-bottom:1.5rem;}
        .ck-progress-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;}
        .ck-progress-label{font-size:0.78rem;font-weight:600;color:var(--ck-muted);}
        .ck-progress-pct{font-size:0.85rem;font-weight:800;color:var(--ck-text);font-family:ui-monospace,monospace;}
        .ck-progress-bar{background:#f1f5f9;border-radius:999px;height:12px;overflow:hidden;}
        .ck-progress-fill{height:100%;border-radius:999px;transition:width .8s cubic-bezier(.4,0,.2,1);}

        /* ── INFO ROWS ── */
        .ck-info-row{display:flex;justify-content:space-between;padding:0.5rem 0;font-size:0.82rem;border-bottom:1px solid #f8fafc;}
        .ck-info-row:last-child{border-bottom:none;}
        .ck-info-label{color:var(--ck-muted);font-weight:500;}
        .ck-info-val{font-weight:700;color:var(--ck-text);}
        .ck-info-val.danger{color:var(--ck-red);}

        /* ── OVERDUE ── */
        .ck-overdue{display:flex;align-items:center;gap:12px;padding:1rem 1.25rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;margin-top:1rem;}
        .ck-overdue-icon{width:36px;height:36px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#ef4444;}
        .ck-overdue-title{font-size:0.85rem;font-weight:700;color:#991b1b;}
        .ck-overdue-sub{font-size:0.75rem;color:#b91c1c;margin-top:2px;}

        /* ── PAYMENT LIST ── */
        .ck-payment{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid #f8fafc;transition:background .1s;}
        .ck-payment:last-child{border-bottom:none;}
        .ck-payment:hover{background:#fafafa;}
        .ck-payment-date{text-align:center;flex-shrink:0;width:48px;}
        .ck-payment-date .day{font-size:1.1rem;font-weight:900;color:var(--ck-text);line-height:1;}
        .ck-payment-date .month{font-size:0.6rem;font-weight:700;color:#94a3b8;text-transform:uppercase;}
        .ck-payment-info{flex:1;min-width:0;}
        .ck-payment-method{font-weight:700;font-size:0.82rem;color:var(--ck-text);}
        .ck-payment-ref{font-size:0.72rem;color:#94a3b8;margin-top:2px;font-family:ui-monospace,monospace;}
        .ck-payment-by{font-size:0.68rem;color:#94a3b8;margin-top:2px;}
        .ck-payment-amount{text-align:right;flex-shrink:0;}
        .ck-payment-amount .value{font-size:0.95rem;font-weight:800;color:var(--ck-green);font-family:ui-monospace,monospace;}
        .ck-payment-del{flex-shrink:0;}
        .ck-del-btn{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;padding:0.35rem 0.65rem;border-radius:6px;font-size:0.68rem;font-weight:600;cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:4px;transition:all .15s;}
        .ck-del-btn:hover{background:#fee2e2;}
        .ck-del-btn svg{width:12px;height:12px;}

        /* ── EMPTY ── */
        .ck-empty{padding:2.5rem 1.5rem;text-align:center;color:#94a3b8;}
        .ck-empty-icon{width:48px;height:48px;border-radius:12px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;color:#cbd5e1;}
        .ck-empty-text{font-size:0.85rem;font-weight:600;color:#64748b;}

        /* ── SIDEBAR ── */
        .ck-sidebar{position:sticky;top:1rem;}
        .ck-sidebar .ck-card{margin-bottom:1rem;}
        .ck-sidebar-head{padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;font-weight:700;font-size:0.85rem;color:var(--ck-text);}

        /* ── FORM ── */
        .ck-fg{margin-bottom:0.875rem;}
        .ck-fl{display:block;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;margin-bottom:0.4rem;}
        .ck-fi{width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e2e8f0;border-radius:8px;font-size:0.85rem;font-family:inherit;transition:all .15s;box-sizing:border-box;background:#fafbfc;font-weight:500;color:var(--ck-text);outline:none;}
        .ck-fi:focus{border-color:var(--ck-blue);background:#fff;box-shadow:0 0 0 3px rgba(59,130,246,.08);}
        .ck-fi.error{border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.1);}
        select.ck-fi{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 0.7rem center;padding-right:1.75rem;}
        textarea.ck-fi{resize:vertical;min-height:60px;}
        .ck-max-row{display:flex;justify-content:space-between;align-items:center;margin-top:0.3rem;font-size:0.72rem;}
        .ck-max-lbl{color:#94a3b8;}
        .ck-max-val{color:var(--ck-blue);font-weight:700;cursor:pointer;font-family:ui-monospace,monospace;}
        .ck-max-val:hover{text-decoration:underline;}
        .ck-err{color:#dc2626;font-size:0.72rem;font-weight:600;margin-top:0.3rem;display:none;}
        .ck-quick{display:grid;grid-template-columns:repeat(4,1fr);gap:0.4rem;margin-bottom:0.875rem;}
        .ck-quick-btn{padding:0.5rem;border:1.5px solid #e2e8f0;border-radius:8px;background:#fff;font-size:0.72rem;font-weight:700;color:#475569;cursor:pointer;transition:all .15s;font-family:inherit;text-align:center;}
        .ck-quick-btn:hover{border-color:var(--ck-blue);color:var(--ck-blue);background:#eff6ff;}
        .ck-submit{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:0.75rem;border:none;border-radius:10px;font-size:0.85rem;font-weight:800;cursor:pointer;transition:all .2s;font-family:inherit;}
        .ck-submit.debt{background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;box-shadow:0 4px 14px rgba(239,68,68,.3);}
        .ck-submit.debt:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(239,68,68,.4);}
        .ck-submit.credit{background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,.3);}
        .ck-submit.credit:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(16,185,129,.4);}
        .ck-submit svg{width:16px;height:16px;}

        /* ── LUNAS ── */
        .ck-lunas{text-align:center;padding:2.5rem 1.25rem;}
        .ck-lunas-icon{width:64px;height:64px;margin:0 auto 1rem;background:#d1fae5;border-radius:18px;display:flex;align-items:center;justify-content:center;}
        .ck-lunas-icon svg{width:32px;height:32px;color:#059669;}
        .ck-lunas-title{font-size:1.1rem;font-weight:800;color:#065f46;}
        .ck-lunas-sub{font-size:0.8rem;color:var(--ck-muted);margin-top:0.3rem;}

        /* ── DANGER ZONE ── */
        .ck-danger{border:1px solid #fecaca;border-radius:10px;padding:1rem 1.25rem;margin-top:0.5rem;}
        .ck-danger-title{font-size:0.78rem;font-weight:700;color:#991b1b;margin-bottom:0.3rem;display:flex;align-items:center;gap:6px;}
        .ck-danger-title svg{width:14px;height:14px;}
        .ck-danger-sub{font-size:0.72rem;color:#b91c1c;margin-bottom:0.75rem;line-height:1.4;}
        .ck-danger-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:0.6rem;border:1.5px solid #fca5a5;border-radius:8px;background:#fff;font-size:0.8rem;font-weight:700;color:#dc2626;cursor:pointer;transition:all .15s;font-family:inherit;}
        .ck-danger-btn:hover{background:#fef2f2;}
        .ck-danger-btn svg{width:14px;height:14px;}

        @media(max-width:1024px){
            .ck-grid{grid-template-columns:1fr;}
            .ck-stats{grid-template-columns:repeat(2,1fr);}
            .ck-sidebar{position:static;}
        }
        @media(max-width:640px){
            .ck-stats{grid-template-columns:1fr;}
            .ck-hero-amount-value{font-size:1.5rem;}
        }
    </style>
    @endpush

    @php
        $isDebt = $kredit->type === 'debt';
        $isOver = $kredit->isOverdue();
        $remaining = (int) $kredit->remaining_amount;
        $pct = (float) $kredit->amount > 0 ? min(100, ((float) $kredit->paid_amount / (float) $kredit->amount) * 100) : 0;
        $barColor = $pct >= 100 ? '#10b981' : ($pct > 50 ? '#f59e0b' : '#ef4444');
        $hasPayments = $kredit->payments->isNotEmpty();
    @endphp

    <div class="ck-page">

        {{-- BREADCRUMB --}}
        <nav class="ck-breadcrumb">
            <a href="{{ route('pelanggan.kredit.index') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Hutang & Piutang
            </a>
            <span class="sep">/</span>
            <span class="current">{{ $kredit->credit_number }}</span>
        </nav>

        @if(session('success'))
            <div class="ck-alert ck-alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="ck-alert ck-alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- HERO --}}
        <div class="ck-hero {{ $isDebt ? 'debt' : 'credit' }}">
            <div class="ck-hero-inner">
                <div class="ck-hero-top">
                    <div style="display:flex;align-items:center;">
                        <div class="ck-hero-icon">
                            @if($isDebt)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="ck-hero-number">{{ $kredit->credit_number }}</div>
                            <div class="ck-hero-type">
                                {{ $kredit->type_label }} &mdash;
                                <a href="{{ route('pelanggan.show', $kredit->customer) }}">{{ $kredit->customer->name }}</a>
                            </div>
                            <div class="ck-hero-badges">
                                @if($kredit->status === 'paid')
                                    <span class="ck-badge ck-badge-status-paid">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        Lunas
                                    </span>
                                @elseif($kredit->status === 'partial')
                                    <span class="ck-badge ck-badge-status-partial">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                        Sebagian
                                    </span>
                                @else
                                    <span class="ck-badge ck-badge-status-unpaid">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        Belum Lunas
                                    </span>
                                @endif
                                @if($isOver)
                                    <span class="ck-badge ck-badge-overdue">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        Jatuh Tempo
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="ck-hero-amount">
                        <div class="ck-hero-amount-label">Total</div>
                        <div class="ck-hero-amount-value">Rp {{ number_format((float) $kredit->amount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="ck-stats">
            <div class="ck-stat s-gray">
                <div class="ck-stat-label">Tgl Transaksi</div>
                <div class="ck-stat-val" style="color:#475569;">{{ $kredit->transaction_date->format('d/m/Y') }}</div>
            </div>
            <div class="ck-stat s-amber">
                <div class="ck-stat-label">Jatuh Tempo</div>
                <div class="ck-stat-val" style="color:{{ $isOver ? 'var(--ck-red)' : '#b45309' }};">
                    {{ $kredit->due_date ? $kredit->due_date->format('d/m/Y') : '-' }}
                </div>
            </div>
            <div class="ck-stat s-green">
                <div class="ck-stat-label">Terbayar</div>
                <div class="ck-stat-val" style="color:var(--ck-green);">Rp {{ number_format((float) $kredit->paid_amount, 0, ',', '.') }}</div>
                <div class="ck-stat-sub">{{ number_format($pct, 1) }}% selesai</div>
            </div>
            <div class="ck-stat s-red">
                <div class="ck-stat-label">Sisa</div>
                <div class="ck-stat-val" style="color:var(--ck-red);">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="ck-grid">

            {{-- LEFT COLUMN --}}
            <div>

                {{-- Progress & Details --}}
                <div class="ck-card">
                    <div class="ck-card-head">
                        <div class="ck-card-title">
                            <div class="ck-card-title-icon" style="background:#eff6ff;color:#3b82f6;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            </div>
                            Progress Pembayaran
                        </div>
                    </div>
                    <div class="ck-card-body">
                        <div class="ck-progress">
                            <div class="ck-progress-head">
                                <span class="ck-progress-label">Progress</span>
                                <span class="ck-progress-pct">{{ number_format($pct, 1) }}%</span>
                            </div>
                            <div class="ck-progress-bar">
                                <div class="ck-progress-fill" id="progressFill" style="width:0%;background:{{ $barColor }};" data-pct="{{ (int) $pct }}"></div>
                            </div>
                        </div>

                        <div class="ck-info-row">
                            <span class="ck-info-label">Total Hutang</span>
                            <span class="ck-info-val">Rp {{ number_format((float) $kredit->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="ck-info-row">
                            <span class="ck-info-label">Sudah Dibayar</span>
                            <span class="ck-info-val" style="color:var(--ck-green);">Rp {{ number_format((float) $kredit->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="ck-info-row">
                            <span class="ck-info-label">Sisa Hutang</span>
                            <span class="ck-info-val" style="color:var(--ck-red);">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                        </div>

                        @if($kredit->description)
                        <div style="margin-top:1rem;padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;font-size:0.82rem;color:#475569;display:flex;align-items:flex-start;gap:8px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            {{ $kredit->description }}
                        </div>
                        @endif

                        @if($isOver)
                            @php $daysOverdue = $kredit->due_date->diffInDays(now()); @endphp
                            <div class="ck-overdue">
                                <div class="ck-overdue-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                </div>
                                <div>
                                    <div class="ck-overdue-title">Terlambat {{ $daysOverdue }} hari</div>
                                    <div class="ck-overdue-sub">Segera lakukan pembayaran untuk menyelesaikan hutang ini.</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment History --}}
                <div class="ck-card">
                    <div class="ck-card-head">
                        <div class="ck-card-title">
                            <div class="ck-card-title-icon green">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            Riwayat Pembayaran
                        </div>
                        @if($hasPayments)
                            <span class="ck-card-count">{{ $kredit->payments->count() }} transaksi</span>
                        @endif
                    </div>
                    <div class="ck-card-body" style="padding:0;">
                        @if(!$hasPayments)
                            <div class="ck-empty">
                                <div class="ck-empty-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                </div>
                                <div class="ck-empty-text">Belum ada pembayaran</div>
                            </div>
                        @else
                            @foreach($kredit->payments as $p)
                            <div class="ck-payment">
                                <div class="ck-payment-date">
                                    <div class="day">{{ $p->payment_date->format('d') }}</div>
                                    <div class="month">{{ $p->payment_date->format('M') }}</div>
                                </div>
                                <div class="ck-payment-info">
                                    <div class="ck-payment-method">{{ $p->payment_method_label }}</div>
                                    @if($p->reference_number)
                                        <div class="ck-payment-ref">Ref: {{ $p->reference_number }}</div>
                                    @endif
                                    <div class="ck-payment-by">Oleh: {{ $p->createdBy->name ?? '-' }}</div>
                                </div>
                                <div class="ck-payment-amount">
                                    <div class="value">Rp {{ number_format((float) $p->amount, 0, ',', '.') }}</div>
                                </div>
                                @can('delete_hutang_piutang')
                                <div class="ck-payment-del">
                                    <form action="{{ route('pelanggan.kredit.delete_payment', $p) }}" method="POST" onsubmit="return confirm('Hapus pembayaran Rp {{ number_format((float) $p->amount, 0, ',', '.') }} ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ck-del-btn" title="Hapus pembayaran">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                                @endcan
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="ck-sidebar">

                @if($kredit->status !== 'paid' && $remaining > 0)
                    <div class="ck-card">
                        <div class="ck-sidebar-head">
                            {{ $isDebt ? 'Bayar Hutang' : 'Cairkan Piutang' }}
                        </div>
                        <div class="ck-card-body">
                            <form action="{{ route('pelanggan.kredit.pay', $kredit) }}" method="POST" id="paymentForm" novalidate>
                                @csrf
                                <div class="ck-fg">
                                    <label class="ck-fl">Tanggal Bayar</label>
                                    <input type="date" name="payment_date" class="ck-fi" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date')<div style="font-size:0.72rem;color:#dc2626;font-weight:600;margin-top:0.2rem;">{{ $message }}</div>@enderror
                                </div>
                                <div class="ck-fg">
                                    <label class="ck-fl">Jumlah (Rp)</label>
                                    <input type="text" inputmode="numeric" name="amount" id="amountInput" data-currency class="ck-fi"
                                           data-max="{{ $remaining }}"
                                           value="{{ old('amount') }}"
                                           placeholder="Maks: {{ number_format($remaining, 0, ',', '.') }}"
                                           required>
                                    <div class="ck-max-row">
                                        <span class="ck-max-lbl">Maksimal:</span>
                                        <span class="ck-max-val" id="maxBtn">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="ck-err" id="amountError">Jumlah tidak boleh melebihi sisa hutang</div>
                                    @error('amount')<div style="font-size:0.72rem;color:#dc2626;font-weight:600;margin-top:0.2rem;">{{ $message }}</div>@enderror
                                </div>

                                <div class="ck-quick">
                                    <button type="button" class="ck-quick-btn" data-pct="25">25%</button>
                                    <button type="button" class="ck-quick-btn" data-pct="50">50%</button>
                                    <button type="button" class="ck-quick-btn" data-pct="75">75%</button>
                                    <button type="button" class="ck-quick-btn" data-pct="100">Lunas</button>
                                </div>

                                <div class="ck-fg">
                                    <label class="ck-fl">Metode Bayar</label>
                                    <select name="payment_method" id="paymentMethod" class="ck-fi" required>
                                        <option value="cash" @selected(old('payment_method')=='cash')>Tunai</option>
                                        <option value="transfer" @selected(old('payment_method')=='transfer')>Transfer</option>
                                    </select>
                                </div>
                                <div class="ck-fg" id="refField">
                                    <label class="ck-fl">ID Transfer <span id="refReq" style="color:#ef4444;display:none;">*</span></label>
                                    <input type="text" name="reference_number" id="refInput" class="ck-fi" placeholder="No. referensi transfer...">
                                </div>
                                <div class="ck-fg" style="margin-bottom:0;">
                                    <label class="ck-fl">Catatan</label>
                                    <textarea name="notes" class="ck-fi" rows="2" placeholder="Opsional...">{{ old('notes') }}</textarea>
                                </div>

                                <div style="margin-top:1.25rem;">
                                    <button type="submit" class="ck-submit {{ $isDebt ? 'debt' : 'credit' }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Simpan Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="ck-card">
                        <div class="ck-lunas">
                            <div class="ck-lunas-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div class="ck-lunas-title">Sudah Lunas!</div>
                            <div class="ck-lunas-sub">Semua pembayaran telah selesai.</div>
                        </div>
                    </div>
                @endif

                {{-- Delete Zone --}}
                @if($kredit->status === 'unpaid' && !$hasPayments)
                    <div class="ck-danger">
                        <div class="ck-danger-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            Zona Berbahaya
                        </div>
                        <div class="ck-danger-sub">Hapus catatan ini secara permanen. Tindakan ini tidak dapat dibatalkan.</div>
                        <form action="{{ route('pelanggan.kredit.destroy', $kredit) }}" method="POST" onsubmit="return confirm('Yakin hapus catatan {{ $kredit->credit_number }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ck-danger-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Hapus Catatan
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    (function() {
        var progressEl = document.getElementById('progressFill');
        if (progressEl) {
            var pct = Math.max(0, Math.min(100, parseInt(progressEl.dataset.pct || '0', 10)));
            requestAnimationFrame(function() { progressEl.style.width = pct + '%'; });
        }

        var amountInput = document.getElementById('amountInput');
        var maxBtn = document.getElementById('maxBtn');
        var amountError = document.getElementById('amountError');
        var form = document.getElementById('paymentForm');
        var maxAmount = parseInt(amountInput ? amountInput.dataset.max : '0', 10);

        function parseCurrency(v) { return String(v).replace(/[^0-9]/g, '') || '0'; }

        function validateAmount() {
            var val = parseInt(parseCurrency(amountInput.value), 10);
            if (val > maxAmount) {
                amountInput.classList.add('error');
                if (amountError) { amountError.textContent = 'Jumlah melebihi sisa'; amountError.style.display = 'block'; }
                return false;
            } else if (val < 1) {
                amountInput.classList.add('error');
                if (amountError) { amountError.textContent = 'Jumlah harus lebih dari 0'; amountError.style.display = 'block'; }
                return false;
            } else {
                amountInput.classList.remove('error');
                if (amountError) amountError.style.display = 'none';
                return true;
            }
        }

        if (amountInput && maxAmount > 0) {
            amountInput.addEventListener('input', validateAmount);

            if (form) {
                form.addEventListener('submit', function(e) {
                    var val = parseInt(parseCurrency(amountInput.value), 10);
                    if (!validateAmount() || isNaN(val) || val < 1) {
                        e.preventDefault();
                        amountInput.focus();
                        return false;
                    }
                    amountInput.value = val;
                });
            }
        }

        if (maxBtn && amountInput) {
            maxBtn.addEventListener('click', function() {
                amountInput.value = maxAmount.toLocaleString('id-ID');
                validateAmount();
            });
        }

        document.querySelectorAll('.ck-quick-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var pctVal = parseInt(this.dataset.pct, 10);
                var val = Math.floor(maxAmount * pctVal / 100);
                if (amountInput) {
                    amountInput.value = val.toLocaleString('id-ID');
                    validateAmount();
                }
            });
        });
    })();
    </script>
    @endpush
</x-app-layout>
