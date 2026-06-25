<x-app-layout>
    <x-slot name="header">Rekap &amp; Closing Harian</x-slot>

    @push('styles')
    <style>
        /* ══ REKAP HARIAN — PREMIUM REDESIGN ══ */
        .rh-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 0 3rem;
        }

        /* ── HERO HEADER ── */
        .rh-hero-header {
            background: linear-gradient(135deg, #06090f 0%, #0d1322 35%, #111827 70%, #0a0e1a 100%);
            border-radius: 20px;
            padding: 2rem 2.25rem 3.25rem;
            margin-bottom: -1.75rem;
            position: relative;
            overflow: hidden;
        }
        .rh-hero-header::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 85% 20%, rgba(99,102,241,0.22) 0%, transparent 60%),
                        radial-gradient(ellipse at 15% 80%, rgba(16,185,129,0.1) 0%, transparent 50%);
        }
        .rh-hero-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), rgba(16,185,129,0.3), transparent);
        }
        .rh-hh-inner { position: relative; z-index: 1; }

        .rh-hh-top {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .rh-hh-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3);
            padding: 0.3rem 0.875rem; border-radius: 99px;
            font-size: 0.65rem; font-weight: 700; color: rgba(165,180,252,0.9);
            text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.875rem;
        }
        .rh-hh-badge-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #818cf8; animation: rh-pulse 2s infinite;
        }
        .rh-hh-title {
            font-size: 2rem; font-weight: 900; color: #fff;
            letter-spacing: -0.04em; line-height: 1.1; margin: 0 0 0.4rem;
        }
        .rh-hh-subtitle {
            font-size: 0.8125rem; color: rgba(255,255,255,0.45); margin: 0;
        }
        .rh-hh-refresh {
            display: flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 10px;
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.55); transition: all 0.2s; text-decoration: none;
            flex-shrink: 0;
        }
        .rh-hh-refresh:hover { background: rgba(255,255,255,0.14); color: #fff; }

        /* Omzet row */
        .rh-omzet-row {
            display: flex; align-items: flex-end; justify-content: space-between;
            flex-wrap: wrap; gap: 1.25rem;
        }
        .rh-omzet-label {
            font-size: 0.65rem; font-weight: 700; color: rgba(255,255,255,0.4);
            text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 0.5rem;
        }
        .rh-omzet-amount {
            font-size: 2.75rem; font-weight: 900; color: #fff;
            font-family: ui-monospace, monospace; letter-spacing: -0.03em; line-height: 1;
        }
        .rh-omzet-rp {
            font-size: 1rem; opacity: 0.45; margin-right: 3px; font-weight: 700;
        }
        .rh-omzet-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.08); padding: 4px 12px;
            border-radius: 7px; font-size: 0.68rem; font-weight: 600;
            color: rgba(255,255,255,0.7); margin-top: 0.875rem;
        }
        .rh-session-status {
            display: flex; flex-direction: column; align-items: flex-end; gap: 8px;
            padding-bottom: 4px;
        }
        .rh-live-badge {
            display: flex; align-items: center; gap: 6px;
            background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25);
            padding: 5px 14px; border-radius: 99px;
            font-size: 0.7rem; font-weight: 700; color: #6ee7b7;
        }
        .rh-live-dot {
            width: 7px; height: 7px; background: #10b981; border-radius: 50%;
            animation: rh-pulse 1.5s infinite;
        }
        .rh-closed-badge {
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
            padding: 5px 14px; border-radius: 99px;
            font-size: 0.7rem; font-weight: 700; color: rgba(255,255,255,0.45);
        }
        .rh-session-note { font-size: 0.72rem; color: rgba(255,255,255,0.35); }

        @keyframes rh-pulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.5; transform:scale(1.3); } }

        /* ── ALERTS ── */
        .rh-alert {
            display: flex; align-items: center; gap: 10px;
            padding: 0.8rem 1.125rem; border-radius: 12px;
            margin-bottom: 1rem; font-weight: 600; font-size: 0.8125rem;
            border: 1px solid;
        }
        .rh-alert-ok  { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .rh-alert-err { background: #fef2f2; color: #991b1b; border-color: #fecaca; }

        /* ── METRIC CARDS (floating) ── */
        .rh-metrics-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1rem; position: relative; z-index: 10; margin-bottom: 1.5rem;
        }
        .rh-metric-card {
            background: #fff; border: 1px solid #e2e8f0;
            border-radius: 16px; padding: 1.375rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.3s cubic-bezier(0.34,1.56,.64,1);
            position: relative; overflow: hidden;
        }
        .rh-metric-card::before {
            content: ''; position: absolute;
            bottom: -20px; right: -20px;
            width: 80px; height: 80px; border-radius: 50%; opacity: 0.07;
            transition: transform 0.3s ease;
        }
        .rh-metric-card.m-indigo::before { background: #4f46e5; }
        .rh-metric-card.m-slate::before  { background: #475569; }
        .rh-metric-card.m-status::before { background: #6b7280; }
        .rh-metric-card:hover { transform: translateY(-4px); box-shadow: 0 20px 48px rgba(0,0,0,0.12); }
        .rh-metric-card:hover::before { transform: scale(1.3); }

        .rh-mc-top {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1rem;
        }
        .rh-mc-label {
            font-size: 0.65rem; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.08em;
        }
        .rh-mc-tag {
            font-size: 0.6rem; font-weight: 700; padding: 2px 8px;
            border-radius: 5px;
        }
        .rh-mc-tag.t-default { background: #f1f5f9; color: #64748b; }
        .rh-mc-tag.t-ok   { background: #dcfce7; color: #166534; }
        .rh-mc-tag.t-err  { background: #fee2e2; color: #991b1b; }
        .rh-mc-tag.t-warn { background: #fef3c7; color: #92400e; }

        .rh-mc-val {
            font-size: 1.375rem; font-weight: 900; font-family: ui-monospace, monospace;
            line-height: 1; margin-bottom: 0.375rem; color: #0f172a;
        }
        .rh-mc-val.v-indigo { color: #4f46e5; }
        .rh-mc-val.v-red    { color: #dc2626; }
        .rh-mc-val.v-green  { color: #059669; }
        .rh-mc-val.v-muted  { color: #94a3b8; }
        .rh-mc-foot { font-size: 0.65rem; color: #94a3b8; line-height: 1.4; }

        /* ── SESSION MANAGEMENT PANEL ── */
        .rh-session-panel {
            background: #fff; border: 1px solid #e8edf5;
            border-radius: 18px; overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }
        .rh-sp-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 1rem; padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(180deg, #fdfdfe, #f9fbfd);
            flex-wrap: wrap;
        }
        .rh-sp-title {
            display: flex; align-items: center; gap: 0.625rem;
            font-weight: 800; font-size: 0.9375rem; color: #0f172a;
        }
        .rh-sp-title-icon {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            display: flex; align-items: center; justify-content: center;
            color: #4f46e5; flex-shrink: 0;
        }
        .rh-sp-hint {
            font-size: 0.72rem; color: #94a3b8; line-height: 1.5;
            max-width: 360px; text-align: right;
        }
        .rh-sp-body { padding: 1.25rem 1.5rem; }
        .rh-sp-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; }

        /* Kasir cards */
        .rh-kcard {
            border-radius: 14px; border: 1px solid #e2e8f0;
            padding: 1.125rem; background: #fafbfc;
            transition: all 0.2s;
        }
        .rh-kcard.is-active {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border-color: #86efac;
        }
        .rh-kcard-top { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.875rem; }
        .rh-kav {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .rh-kav.eceran { background: linear-gradient(135deg, #6366f1, #4f46e5); }
        .rh-kav.grosir  { background: linear-gradient(135deg, #38bdf8, #0ea5e9); }
        .rh-kav.idle    { background: linear-gradient(135deg, #cbd5e1, #94a3b8); }
        .rh-kcard-info { flex: 1; min-width: 0; }
        .rh-kcard-name  { font-weight: 700; font-size: 0.875rem; color: #0f172a; line-height: 1.2; }
        .rh-kcard-role  { font-size: 0.6rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; margin-top: 2px; }
        .rh-kpill {
            margin-left: auto; font-size: 0.63rem; font-weight: 700;
            padding: 3px 10px; border-radius: 99px; display: flex; align-items: center; gap: 5px;
            flex-shrink: 0;
        }
        .rh-kpill.active { background: #dcfce7; color: #166534; }
        .rh-kpill.closed { background: #f1f5f9; color: #64748b; }
        .rh-kpill-dot { width: 5px; height: 5px; background: #22c55e; border-radius: 50%; animation: rh-pulse 1.5s infinite; }

        .rh-kcard-stats {
            display: flex; gap: 1.25rem;
            padding: 0.75rem 0; margin-top: 0.5rem;
            border-top: 1px solid rgba(0,0,0,0.06);
        }
        .rh-ks-item { display: flex; flex-direction: column; gap: 2px; }
        .rh-ks-lbl { font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
        .rh-ks-val { font-size: 0.8125rem; font-weight: 700; font-family: ui-monospace, monospace; color: #0f172a; }

        /* Open session form */
        .rh-open-form { display: flex; flex-direction: column; gap: 0.625rem; margin-top: 0.5rem; }
        .rh-of-field label {
            display: block; font-size: 0.62rem; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;
        }
        .rh-of-field input {
            width: 100%; padding: 0.5625rem 0.875rem;
            border: 1.5px solid #e2e8f0; border-radius: 9px;
            font-size: 0.875rem; font-family: inherit;
            background: #fff; color: #1e293b;
            box-sizing: border-box; transition: all 0.2s; outline: none;
        }
        .rh-of-field input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .rh-open-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; border: none; padding: 0.5625rem 1rem;
            border-radius: 9px; font-size: 0.8rem; font-weight: 700;
            cursor: pointer; font-family: inherit;
            box-shadow: 0 4px 14px rgba(79,70,229,0.3);
            transition: all 0.25s cubic-bezier(0.34,1.56,.64,1);
        }
        .rh-open-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.4); }

        /* ── SESSION TABLE SECTION ── */
        .rh-section {
            background: #fff; border: 1px solid #e8edf5;
            border-radius: 18px; overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .rh-sec-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.125rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(180deg, #fdfdfe, #f9fbfd);
            flex-wrap: wrap; gap: 0.75rem;
        }
        .rh-sec-title-wrap { display: flex; align-items: center; gap: 0.75rem; }
        .rh-sec-icon {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            display: flex; align-items: center; justify-content: center; color: #d97706;
        }
        .rh-sec-title { font-size: 0.9375rem; font-weight: 700; color: #0f172a; }
        .rh-sec-count {
            font-size: 0.65rem; font-weight: 700; background: #f1f5f9;
            color: #64748b; padding: 3px 10px; border-radius: 99px;
        }
        .rh-cleanup-btn {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa;
            padding: 0.35rem 0.875rem; border-radius: 8px;
            font-weight: 700; font-size: 0.72rem; cursor: pointer;
            font-family: inherit; transition: all 0.2s;
        }
        .rh-cleanup-btn:hover { background: #ffedd5; }

        /* Empty state */
        .rh-empty {
            text-align: center; padding: 4rem 2rem;
        }
        .rh-empty-icon {
            width: 70px; height: 70px; border-radius: 20px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem; color: #94a3b8;
        }
        .rh-empty-title { font-size: 1rem; font-weight: 700; color: #475569; margin-bottom: 0.375rem; }
        .rh-empty-desc  { font-size: 0.8125rem; color: #94a3b8; }

        /* Table */
        .rh-tbl-wrap { overflow-x: auto; }
        .rh-tbl {
            width: 100%; min-width: 720px; border-collapse: separate; border-spacing: 0;
        }
        .rh-tbl th {
            background: linear-gradient(180deg, #f8fafc, #f3f6fb);
            color: #475569; font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em;
            padding: 0.8rem 1rem; text-align: left;
            border-bottom: 2px solid #e8edf5; white-space: nowrap;
        }
        .rh-tbl th.tr { text-align: right; }
        .rh-tbl th.tc { text-align: center; }
        .rh-tbl td {
            padding: 0.9375rem 1rem; border-bottom: 1px solid #f4f8fc;
            font-size: 0.8125rem; color: #374151; vertical-align: middle;
        }
        .rh-tbl tbody tr:last-child td { border-bottom: none; }
        .rh-tbl tbody tr { transition: background 0.15s; }
        .rh-tbl tbody tr:hover td { background: linear-gradient(90deg, #fafbff, #f8f9ff); }
        .rh-tbl .tr { text-align: right; }
        .rh-tbl .tc { text-align: center; }

        /* Kasir cell */
        .rh-tbl-kasir { display: flex; align-items: center; gap: 0.75rem; }
        .rh-tbl-av {
            width: 32px; height: 32px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .rh-tbl-av.eceran { background: linear-gradient(135deg, #6366f1, #4f46e5); }
        .rh-tbl-av.grosir  { background: linear-gradient(135deg, #38bdf8, #0ea5e9); }
        .rh-tbl-name { font-weight: 700; color: #0f172a; font-size: 0.8125rem; }
        .rh-tbl-time { font-size: 0.68rem; color: #94a3b8; margin-top: 2px; }

        /* Type badge */
        .rh-type-badge {
            font-size: 0.62rem; font-weight: 700; padding: 3px 9px;
            border-radius: 6px; display: inline-block;
        }
        .rh-type-badge.eceran { background: #eef2ff; color: #3730a3; }
        .rh-type-badge.grosir  { background: #e0f2fe; color: #075985; }

        /* Status */
        .rh-status-open {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 0.72rem; font-weight: 700; color: #16a34a;
        }
        .rh-status-dot { width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: rh-pulse 1.5s infinite; }
        .rh-status-closed { font-size: 0.72rem; font-weight: 600; color: #94a3b8; }

        /* Amount/variance */
        .rh-tbl-amount { font-weight: 800; color: #4f46e5; font-family: ui-monospace, monospace; }
        .rh-mono { font-family: ui-monospace, monospace; }
        .rh-tbl-na { font-size: 0.72rem; color: #cbd5e1; }
        .rh-var-neg  { color: #dc2626; font-weight: 700; font-family: ui-monospace, monospace; }
        .rh-var-pos  { color: #059669; font-weight: 700; font-family: ui-monospace, monospace; }
        .rh-var-zero { color: #94a3b8; font-family: ui-monospace, monospace; }
        .rh-done-badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 0.68rem; font-weight: 700; color: #16a34a;
            background: #dcfce7; padding: 3px 9px; border-radius: 6px;
        }

        /* Force close button */
        .rh-close-btn {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fff1f2; color: #be123c; border: 1px solid #fecdd3;
            padding: 0.375rem 0.75rem; border-radius: 8px;
            font-weight: 700; font-size: 0.72rem; cursor: pointer;
            font-family: inherit; transition: all 0.2s;
        }
        .rh-close-btn:hover { background: #ffe4e6; border-color: #fda4af; transform: translateY(-1px); }

        /* Responsive */
        @media (max-width: 768px) {
            .rh-metrics-grid { grid-template-columns: 1fr; }
            .rh-omzet-amount { font-size: 2rem; }
            .rh-sp-hint { display: none; }
        }
        @media (max-width: 540px) {
            .rh-metrics-grid { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    <div class="rh-page">

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="rh-alert rh-alert-ok" style="margin-bottom:1rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rh-alert rh-alert-err" style="margin-bottom:1rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ══ HERO HEADER ══ --}}
        <div class="rh-hero-header">
            <div class="rh-hh-inner">
                <div class="rh-hh-top">
                    <div>
                        <div class="rh-hh-badge">
                            <span class="rh-hh-badge-dot"></span>
                            Kasir
                        </div>
                        <h1 class="rh-hh-title">Rekap &amp; Closing Harian</h1>
                        <p class="rh-hh-subtitle">Pantau pendapatan, sesi kasir, dan closing untuk {{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <a href="{{ route('kasir.rekap_harian') }}" class="rh-hh-refresh" title="Muat ulang data">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                            <path d="M16 16h5v5"/>
                        </svg>
                    </a>
                </div>

                <div class="rh-omzet-row">
                    <div>
                        <div class="rh-omzet-label">Total Omzet Hari Ini</div>
                        <div class="rh-omzet-amount">
                            <span class="rh-omzet-rp">Rp</span>{{ number_format($todayRevenue, 0, ',', '.') }}
                        </div>
                        <div class="rh-omzet-chip">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 8h.01M10 8h.01"/></svg>
                            {{ $totalTransactions }} Transaksi Selesai
                        </div>
                    </div>
                    <div class="rh-session-status">
                        @php $activeSessions = $sessions->where('status', 'open')->count(); @endphp
                        @if($activeSessions > 0)
                            <span class="rh-live-badge">
                                <span class="rh-live-dot"></span>
                                {{ $activeSessions }} Sesi Masih Aktif
                            </span>
                        @else
                            <span class="rh-closed-badge">
                                ✓ Semua Sesi Ditutup
                            </span>
                        @endif
                        <span class="rh-session-note">Dari {{ $sessions->count() }} sesi kasir hari ini</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ METRIC CARDS (floating) ══ --}}
        <div class="rh-metrics-grid">
            {{-- Expected Cash --}}
            <div class="rh-metric-card m-indigo">
                <div class="rh-mc-top">
                    <span class="rh-mc-label">Uang Seharusnya</span>
                    <span class="rh-mc-tag t-default">Expected</span>
                </div>
                <div class="rh-mc-val v-indigo">Rp {{ number_format($totalExpectedCash, 0, ',', '.') }}</div>
                <div class="rh-mc-foot">Modal + Pemasukan Tunai − Pengeluaran</div>
            </div>

            {{-- Actual Cash --}}
            <div class="rh-metric-card m-slate">
                <div class="rh-mc-top">
                    <span class="rh-mc-label">Uang Fisik Riil</span>
                    <span class="rh-mc-tag t-default">Actual</span>
                </div>
                <div class="rh-mc-val">Rp {{ number_format($totalActualCash, 0, ',', '.') }}</div>
                <div class="rh-mc-foot">Hanya dari sesi yang sudah ditutup</div>
            </div>

            {{-- Variance --}}
            <div class="rh-metric-card m-status">
                <div class="rh-mc-top">
                    <span class="rh-mc-label">Total Selisih</span>
                    <span class="rh-mc-tag {{ $totalVariance == 0 ? 't-default' : ($totalVariance < 0 ? 't-err' : 't-warn') }}">
                        {{ $totalVariance == 0 ? 'Balanced' : ($totalVariance > 0 ? 'Surplus' : 'Shortage') }}
                    </span>
                </div>
                <div class="rh-mc-val {{ $totalVariance < 0 ? 'v-red' : ($totalVariance > 0 ? 'v-green' : 'v-muted') }}">
                    {{ $totalVariance > 0 ? '+' : '' }}Rp {{ number_format($totalVariance, 0, ',', '.') }}
                </div>
                <div class="rh-mc-foot">Berdasarkan sesi yang sudah ditutup</div>
            </div>
        </div>

        {{-- ══ SESSION MANAGEMENT PANEL ══ --}}
        @if($kasirUsers->count() > 0)
        <div class="rh-session-panel">
            <div class="rh-sp-header">
                <div class="rh-sp-title">
                    <div class="rh-sp-title-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    Buka Sesi Kasir Eceran
                </div>
                <span class="rh-sp-hint">
                    Grosir tidak perlu sesi terpisah — ikut aktif saat eceran dibuka, dan ikut ditutup saat eceran closing.
                </span>
            </div>
            <div class="rh-sp-body">
                <div class="rh-sp-cards">
                    @foreach($kasirUsers as $kasirUser)
                        @if($kasirUser->eceran_session)
                            {{-- Active session card --}}
                            <div class="rh-kcard is-active">
                                <div class="rh-kcard-top">
                                    <div class="rh-kav eceran">{{ strtoupper(substr($kasirUser->name, 0, 1)) }}</div>
                                    <div class="rh-kcard-info">
                                        <div class="rh-kcard-name">{{ $kasirUser->name }}</div>
                                        <div class="rh-kcard-role">{{ strtoupper($kasirUser->role) }}</div>
                                    </div>
                                    <span class="rh-kpill active">
                                        <span class="rh-kpill-dot"></span> Aktif
                                    </span>
                                </div>
                                <div class="rh-kcard-stats">
                                    <div class="rh-ks-item">
                                        <span class="rh-ks-lbl">Modal Awal</span>
                                        <span class="rh-ks-val">Rp {{ number_format($kasirUser->eceran_session->opening_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="rh-ks-item">
                                        <span class="rh-ks-lbl">Dibuka</span>
                                        <span class="rh-ks-val">{{ $kasirUser->eceran_session->created_at->format('H:i') }} WIB</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Open session form card --}}
                            <div class="rh-kcard">
                                <div class="rh-kcard-top">
                                    <div class="rh-kav idle">{{ strtoupper(substr($kasirUser->name, 0, 1)) }}</div>
                                    <div class="rh-kcard-info">
                                        <div class="rh-kcard-name">{{ $kasirUser->name }}</div>
                                        <div class="rh-kcard-role">{{ strtoupper($kasirUser->role) }}</div>
                                    </div>
                                    <span class="rh-kpill closed">Belum Buka</span>
                                </div>
                                <form action="{{ route('kasir.open_session_for') }}" method="POST" class="rh-open-form">
                                    @csrf
                                    <input type="hidden" name="target_user_id" value="{{ $kasirUser->id }}">
                                    <div class="rh-of-field">
                                        <label>Modal Awal (Rp)</label>
                                        <input type="text" inputmode="numeric" data-currency
                                            name="opening_amount" placeholder="Masukkan modal awal" required>
                                    </div>
                                    <button type="submit" class="rh-open-btn">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M12 5v14M5 12h14"/>
                                        </svg>
                                        Buka Sesi Eceran
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ══ SESSION TABLE ══ --}}
        <div class="rh-section">
            <div class="rh-sec-header">
                <div class="rh-sec-title-wrap">
                    <div class="rh-sec-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <div>
                        <div class="rh-sec-title">Sesi Kasir Eceran Hari Ini</div>
                    </div>
                    <span class="rh-sec-count">{{ $sessions->count() }} sesi</span>
                </div>

                @if($orphanedGrosirCount > 0)
                    <form action="{{ route('kasir.cleanup_orphaned_grosir') }}" method="POST"
                        onsubmit="return confirm('Bersihkan {{ $orphanedGrosirCount }} sesi grosir lama yang masih open?\n\nSesi ini adalah legacy dari sistem lama dan tidak mempengaruhi operasional saat ini.')">
                        @csrf
                        <button type="submit" class="rh-cleanup-btn">
                            🧹 Bersihkan {{ $orphanedGrosirCount }} Sesi Grosir Lama
                        </button>
                    </form>
                @endif
            </div>

            @if($sessions->isEmpty())
                <div class="rh-empty">
                    <div class="rh-empty-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <div class="rh-empty-title">Belum Ada Sesi Kasir</div>
                    <div class="rh-empty-desc">Belum ada sesi kasir eceran yang dibuka hari ini.</div>
                </div>
            @else
                <div class="rh-tbl-wrap">
                    <table class="rh-tbl">
                        <thead>
                            <tr>
                                <th>Kasir</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th class="tr">Omzet</th>
                                <th class="tr">Expected</th>
                                <th class="tr">Actual</th>
                                <th class="tr">Selisih</th>
                                <th class="tc">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $s)
                                <tr>
                                    <td>
                                        <div class="rh-tbl-kasir">
                                            <div class="rh-tbl-av {{ $s->type }}">
                                                {{ strtoupper(substr($s->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="rh-tbl-name">{{ $s->user->name ?? 'Kasir' }}</div>
                                                <div class="rh-tbl-time">Dibuka {{ $s->created_at->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="rh-type-badge {{ $s->type }}">{{ strtoupper($s->type) }}</span>
                                    </td>

                                    <td>
                                        @if($s->status === 'open')
                                            <span class="rh-status-open">
                                                <span class="rh-status-dot"></span> Aktif
                                            </span>
                                        @else
                                            <span class="rh-status-closed">Ditutup</span>
                                            <div class="rh-tbl-time">{{ \Carbon\Carbon::parse($s->closed_at)->format('H:i') }}</div>
                                        @endif
                                    </td>

                                    <td class="tr rh-tbl-amount">
                                        Rp {{ number_format($s->revenue ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td class="tr rh-mono" style="color:#374151;">
                                        Rp {{ number_format($s->calculated_expected_cash ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td class="tr rh-mono" style="color:#374151;">
                                        @if($s->status === 'open')
                                            <span class="rh-tbl-na">Belum Closing</span>
                                        @else
                                            Rp {{ number_format($s->actual_cash ?? 0, 0, ',', '.') }}
                                        @endif
                                    </td>

                                    <td class="tr">
                                        @if($s->status === 'open')
                                            <span class="rh-tbl-na">—</span>
                                        @else
                                            @php $var = ($s->actual_cash ?? 0) - ($s->calculated_expected_cash ?? 0); @endphp
                                            <span class="{{ $var < 0 ? 'rh-var-neg' : ($var > 0 ? 'rh-var-pos' : 'rh-var-zero') }}">
                                                {{ $var > 0 ? '+' : '' }}Rp {{ number_format($var, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="tc">
                                        @if($s->status === 'open')
                                            <form action="{{ route('kasir.force_close', $s->id) }}" method="POST"
                                                onsubmit="return confirm('Tutup paksa sesi {{ $s->user->name ?? 'Kasir' }} ({{ strtoupper($s->type) }})?\n\nActual Cash akan disamakan dengan Expected Cash (selisih = 0).')">
                                                @csrf
                                                <button type="submit" class="rh-close-btn">
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                        <path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                                                    </svg>
                                                    Tutup Paksa
                                                </button>
                                            </form>
                                        @else
                                            <span class="rh-done-badge">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 12 2 2 4-4"/></svg>
                                                Selesai
                                            </span>
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
</x-app-layout>
