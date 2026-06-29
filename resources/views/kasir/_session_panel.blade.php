{{-- Session Panel Partial — Premium Redesign --}}
{{-- Expects: $s, $st, $type, $label, $closeRoute, $accent --}}

@push('styles')
<style>
    .sk-panel { padding-top: 2rem; }

    .sk-hero-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
        padding: 1.75rem 2rem; margin-bottom: 1.25rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        position: relative; overflow: hidden;
    }
    .sk-hero-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, #10b981, #059669, #10b981);
    }
    .sk-hero-card-inner { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; }
    .sk-hero-card-left { flex: 1; min-width: 280px; }
    .sk-hero-card-label {
        font-size: 0.65rem; font-weight: 700; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.75rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .sk-hero-card-label-icon {
        width: 20px; height: 20px; border-radius: 5px;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        display: flex; align-items: center; justify-content: center;
    }
    .sk-hero-card-amount {
        font-size: 2.75rem; font-weight: 900; color: #0f172a;
        font-family: ui-monospace, monospace; letter-spacing: -0.03em; line-height: 1;
        margin-bottom: 1rem;
    }
    .sk-hero-card-amount small { font-size: 1rem; color: #64748b; margin-right: 4px; font-weight: 700; }
    .sk-hero-card-chips { display: flex; gap: 6px; flex-wrap: wrap; }
    .sk-chip {
        background: #f1f5f9; padding: 4px 10px; border-radius: 6px;
        font-size: 0.65rem; font-weight: 600; color: #475569;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .sk-chip-op { color: #94a3b8; font-size: 0.7rem; font-weight: 500; }
    .sk-chip.plus { background: #dcfce7; color: #166534; }
    .sk-chip.minus { background: #fee2e2; color: #991b1b; }

    .sk-hero-card-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem; }
    .sk-status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: #dcfce7; border: 1px solid #86efac;
        padding: 6px 14px; border-radius: 99px;
        font-size: 0.7rem; font-weight: 700; color: #059669;
    }
    .sk-status-dot { width: 7px; height: 7px; background: #10b981; border-radius: 50%; animation: sk-pulse 1.5s infinite; }
    .sk-hero-card-meta { font-size: 0.75rem; color: #64748b; text-align: right; line-height: 1.6; }
    .sk-hero-card-meta strong { color: #0f172a; font-weight: 700; }

    .sk-section { margin-bottom: 1.25rem; }
    .sk-section-title {
        font-size: 0.875rem; font-weight: 700; color: #0f172a;
        margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .sk-section-title-icon {
        width: 24px; height: 24px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
    }
    .sk-section-title-icon.indigo { background: #eef2ff; color: #4f46e5; }
    .sk-section-title-icon.emerald { background: #dcfce7; color: #059669; }
    .sk-section-title-icon.rose { background: #fee2e2; color: #dc2626; }

    .sk-bd-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem; }
    .sk-bd-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
        padding: 1rem; display: flex; align-items: center; gap: 0.75rem;
        transition: all 0.2s; position: relative;
    }
    .sk-bd-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); border-color: #e0e7ff; }
    .sk-bd-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .sk-bd-icon svg { width: 18px; height: 18px; }
    .sk-bd-icon.i-modal { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }
    .sk-bd-icon.i-cash { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #16a34a; }
    .sk-bd-icon.i-dp { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
    .sk-bd-icon.i-in { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
    .sk-bd-icon.i-out { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
    .sk-bd-info { flex: 1; min-width: 0; }
    .sk-bd-lbl { font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px; }
    .sk-bd-val { font-size: 0.875rem; font-weight: 800; font-family: ui-monospace, monospace; color: #0f172a; }
    .sk-bd-sub { font-size: 0.65rem; color: #94a3b8; margin-top: 2px; }
    .sk-bd-sign {
        position: absolute; top: 8px; right: 10px;
        font-size: 0.75rem; font-weight: 800;
        width: 18px; height: 18px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .sk-bd-sign.plus { background: #dcfce7; color: #16a34a; }
    .sk-bd-sign.minus { background: #fee2e2; color: #dc2626; }

    .sk-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
    .sk-stat-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;
        padding: 1.25rem 1.5rem; transition: all 0.2s;
        position: relative; overflow: hidden;
    }
    .sk-stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
    }
    .sk-stat-card.indigo::before { background: linear-gradient(180deg, #4f46e5, #6366f1); }
    .sk-stat-card.purple::before { background: linear-gradient(180deg, #7c3aed, #8b5cf6); }
    .sk-stat-card.emerald::before { background: linear-gradient(180deg, #059669, #10b981); }
    .sk-stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .sk-stat-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
    .sk-stat-lbl { font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.07em; }
    .sk-stat-amt { font-size: 1.5rem; font-weight: 900; font-family: ui-monospace, monospace; color: #0f172a; letter-spacing: -0.02em; }
    .sk-stat-foot { font-size: 0.7rem; color: #94a3b8; margin-top: 4px; }
    .sk-stat-badge {
        font-size: 0.6rem; font-weight: 700; padding: 3px 8px; border-radius: 5px;
        background: #f1f5f9; color: #64748b;
    }
    .sk-stat-badge.live { background: #dcfce7; color: #059669; animation: sk-pulse 2s infinite; }

    .sk-close-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
        overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .sk-close-header {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fef2f2, #fff);
        display: flex; align-items: center; gap: 0.875rem;
    }
    .sk-close-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(239,68,68,0.15);
    }
    .sk-close-icon svg { width: 20px; height: 20px; color: #dc2626; }
    .sk-close-title { font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0; }
    .sk-close-sub { font-size: 0.75rem; color: #64748b; margin: 3px 0 0; }
    .sk-close-body { padding: 1.5rem; }
    .sk-close-grid { display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 1rem; align-items: end; }
    .sk-field { display: flex; flex-direction: column; gap: 6px; }
    .sk-field label { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .sk-expected {
        font-size: 1.125rem; font-weight: 800; font-family: ui-monospace, monospace;
        color: #4f46e5; background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        padding: 0.75rem 1rem; border-radius: 10px; text-align: center;
        border: 1px solid #c7d2fe;
    }
    .sk-input-wrap {
        display: flex; align-items: center; background: #fff;
        border: 2px solid #e2e8f0; border-radius: 10px; overflow: hidden;
        transition: all 0.2s;
    }
    .sk-input-wrap:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); }
    .sk-input-prefix {
        padding: 0 0.875rem; color: #64748b; font-weight: 700; font-size: 0.875rem;
        background: #f8fafc; border-right: 1px solid #e2e8f0;
        display: flex; align-items: center; min-height: 44px;
    }
    .sk-input-wrap input {
        border: none; padding: 0.7rem 0.875rem; width: 100%;
        font-weight: 800; font-family: ui-monospace, monospace; font-size: 1rem;
        outline: none; background: transparent;
    }
    .sk-variance {
        display: flex; flex-direction: column; gap: 4px;
        background: #f8fafc; border: 2px solid #e2e8f0;
        border-radius: 10px; padding: 0.625rem 1rem; min-height: 44px;
        justify-content: center; transition: all 0.2s;
    }
    .sk-variance.v-pos { background: #dcfce7; border-color: #86efac; }
    .sk-variance.v-neg { background: #fee2e2; border-color: #fca5a5; }
    .sk-variance.v-zero { background: #f0fdf4; border-color: #86efac; }
    .sk-var-amt { font-size: 1rem; font-weight: 800; font-family: ui-monospace, monospace; color: #0f172a; }
    .sk-note-input {
        border: 2px solid #e2e8f0; border-radius: 10px;
        padding: 0.7rem 1rem; font-size: 0.875rem; font-family: inherit;
        outline: none; background: #fff; transition: all 0.2s; width: 100%;
    }
    .sk-note-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); }
    .sk-close-action { margin-top: 1.25rem; display: flex; justify-content: flex-end; }
    .sk-close-btn {
        background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff;
        border: none; padding: 0.75rem 1.75rem; border-radius: 10px;
        font-weight: 700; display: flex; align-items: center; gap: 8px;
        cursor: pointer; transition: all 0.2s; font-size: 0.875rem;
        box-shadow: 0 4px 14px rgba(239,68,68,0.35);
    }
    .sk-close-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(239,68,68,0.4); }

    .sk-mutasi-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
        overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .sk-mutasi-header {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #f8fafc, #fff);
    }
    .sk-mutasi-title { font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0; }
    .sk-mutasi-desc { font-size: 0.75rem; color: #64748b; margin: 3px 0 0; }
    .sk-mutasi-body { padding: 1.5rem; }
    .sk-mf { display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .sk-mf-group { display: flex; flex-direction: column; gap: 5px; }
    .sk-mf-group label { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .sk-mf-grow { flex: 1; min-width: 120px; }
    .sk-mf-grow2 { flex: 2; min-width: 160px; }
    .sk-mf select, .sk-mf input {
        padding: 0.625rem 0.875rem; border: 2px solid #e2e8f0; border-radius: 10px;
        font-size: 0.875rem; font-family: inherit; outline: none;
        background: #fff; transition: all 0.2s;
    }
    .sk-mf select { appearance: none; padding-right: 2rem; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; }
    .sk-mf select:focus, .sk-mf input:focus { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79,70,229,0.1); }
    .sk-mf-btn {
        background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;
        border: none; padding: 0.625rem 1.25rem; border-radius: 10px;
        font-weight: 700; cursor: pointer; font-size: 0.875rem;
        transition: all 0.2s; box-shadow: 0 4px 12px rgba(15,23,42,0.2);
    }
    .sk-mf-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,23,42,0.3); }

    .sk-mt-wrap { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .sk-mt { width: 100%; border-collapse: collapse; min-width: 500px; }
    .sk-mt th {
        background: linear-gradient(180deg, #f8fafc, #f4f8fc);
        padding: 0.75rem 1rem; text-align: left;
        font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
        color: #64748b; border-bottom: 2px solid #e2e8f0; letter-spacing: 0.06em;
    }
    .sk-mt td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.8125rem; }
    .sk-mt tbody tr { transition: background 0.15s; }
    .sk-mt tbody tr:hover { background: linear-gradient(90deg, #fafbff, #f8f9ff); }
    .sk-mt tbody tr:last-child td { border-bottom: none; }
    .sk-mt .tc { text-align: center; }
    .sk-mt .tr { text-align: right; }
    .sk-mt-time { color: #64748b; font-size: 0.75rem; }
    .sk-mt-mono { font-family: ui-monospace, monospace; font-weight: 700; }
    .sk-tag-in { background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 700; }
    .sk-tag-out { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 700; }
    .sk-mt-empty { text-align: center; padding: 2rem; color: #94a3b8; font-size: 0.875rem; }

    @keyframes sk-pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }

    @media (max-width: 1024px) { .sk-bd-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) {
        .sk-bd-grid { grid-template-columns: repeat(2, 1fr); }
        .sk-stats-grid { grid-template-columns: 1fr; }
        .sk-close-grid { grid-template-columns: 1fr; }
        .sk-hero-card { padding: 1.25rem; }
        .sk-hero-card-amount { font-size: 2rem; }
    }
    @media (max-width: 480px) {
        .sk-bd-grid { grid-template-columns: 1fr; }
        .sk-mf { flex-direction: column; align-items: stretch; }
        .sk-mf-grow, .sk-mf-grow2 { min-width: 0; }
    }
</style>
@endpush

<div class="sk-panel">

    {{-- 1. HERO CARD --}}
    <div class="sk-hero-card">
        <div class="sk-hero-card-inner">
            <div class="sk-hero-card-left">
                <div class="sk-hero-card-label">
                    <span class="sk-hero-card-label-icon">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                    </span>
                    Estimasi Uang Fisik Laci — {{ $label }}
                </div>
                <div class="sk-hero-card-amount">
                    <small>Rp</small>{{ number_format($st['expectedCash'] ?? 0, 0, ',', '.') }}
                </div>
                <div class="sk-hero-card-chips">
                    <span class="sk-chip plus">Modal</span><span class="sk-chip-op">+</span>
                    <span class="sk-chip plus">Tunai</span><span class="sk-chip-op">+</span>
                    <span class="sk-chip plus">DP</span><span class="sk-chip-op">+</span>
                    <span class="sk-chip plus">Cash In</span><span class="sk-chip-op">−</span>
                    <span class="sk-chip minus">Cash Out</span>
                </div>
            </div>
            <div class="sk-hero-card-right">
                <div class="sk-status-badge"><span class="sk-status-dot"></span> SESI TERBUKA</div>
                <div class="sk-hero-card-meta">
                    <span>Kasir: <strong>{{ $s->user?->name ?? '-' }}</strong></span><br>
                    <span>Dibuka: {{ optional($s->opened_at ?? $s->created_at)->format('H:i, d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. BREAKDOWN GRID --}}
    <div class="sk-section">
        <h3 class="sk-section-title">
            <span class="sk-section-title-icon indigo">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </span>
            Komponen Perhitungan Kas
        </h3>
        <div class="sk-bd-grid">
            <div class="sk-bd-card">
                <div class="sk-bd-icon i-modal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg></div>
                <div class="sk-bd-info">
                    <div class="sk-bd-lbl">Modal Awal</div>
                    <div class="sk-bd-val">Rp {{ number_format($s->opening_amount ?? 0, 0, ',', '.') }}</div>
                </div>
                <span class="sk-bd-sign plus">+</span>
            </div>
            <div class="sk-bd-card">
                <div class="sk-bd-icon i-cash"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                <div class="sk-bd-info">
                    <div class="sk-bd-lbl">Penjualan Tunai</div>
                    <div class="sk-bd-val">Rp {{ number_format($st['cashRevenue'] ?? 0, 0, ',', '.') }}</div>
                    <div class="sk-bd-sub">{{ $st['cashTransactions'] ?? 0 }} transaksi</div>
                </div>
                <span class="sk-bd-sign plus">+</span>
            </div>
            <div class="sk-bd-card">
                <div class="sk-bd-icon i-dp"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></div>
                <div class="sk-bd-info">
                    <div class="sk-bd-lbl">DP Kredit</div>
                    <div class="sk-bd-val">Rp {{ number_format($st['creditDp'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <span class="sk-bd-sign plus">+</span>
            </div>
            <div class="sk-bd-card">
                <div class="sk-bd-icon i-in"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
                <div class="sk-bd-info">
                    <div class="sk-bd-lbl">Cash In</div>
                    <div class="sk-bd-val">Rp {{ number_format($st['cashIn'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <span class="sk-bd-sign plus">+</span>
            </div>
            <div class="sk-bd-card">
                <div class="sk-bd-icon i-out"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg></div>
                <div class="sk-bd-info">
                    <div class="sk-bd-lbl">Cash Out</div>
                    <div class="sk-bd-val">Rp {{ number_format($st['cashOut'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <span class="sk-bd-sign minus">−</span>
            </div>
        </div>
    </div>

    {{-- 3. STATS CARDS --}}
    <div class="sk-section">
        <div class="sk-stats-grid">
            <div class="sk-stat-card indigo">
                <div class="sk-stat-top">
                    <span class="sk-stat-lbl">Total Omzet</span>
                    <span class="sk-stat-badge">Semua Metode</span>
                </div>
                <div class="sk-stat-amt">Rp {{ number_format($st['totalRevenue'] ?? 0, 0, ',', '.') }}</div>
                <div class="sk-stat-foot">{{ $st['totalTransactions'] ?? 0 }} nota transaksi</div>
            </div>
            <div class="sk-stat-card purple">
                <div class="sk-stat-top">
                    <span class="sk-stat-lbl">Non-Tunai</span>
                    <span class="sk-stat-badge">TF / EDC</span>
                </div>
                <div class="sk-stat-amt">Rp {{ number_format($st['nonCashRevenue'] ?? 0, 0, ',', '.') }}</div>
                <div class="sk-stat-foot">Transfer & elektronik</div>
            </div>
            <div class="sk-stat-card emerald">
                <div class="sk-stat-top">
                    <span class="sk-stat-lbl">Durasi Sesi</span>
                    <span class="sk-stat-badge live">LIVE</span>
                </div>
                <div class="sk-stat-amt" data-session-start="{{ optional($s->opened_at ?? $s->created_at)->toIso8601String() }}">--</div>
                <div class="sk-stat-foot">Sejak {{ optional($s->opened_at ?? $s->created_at)->format('H:i, d M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- 4. TUTUP KASIR --}}
    @can('delete_sesi_kasir')
    <div class="sk-section">
        <div class="sk-close-card">
            <div class="sk-close-header">
                <div class="sk-close-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <h3 class="sk-close-title">Tutup Kasir {{ $label }}</h3>
                    <p class="sk-close-sub">Hitung uang fisik di laci, masukkan nominal, lalu konfirmasi penutupan.</p>
                </div>
            </div>
            <div class="sk-close-body">
                <form method="POST" action="{{ route($closeRoute) }}">
                    @csrf
                    <input type="hidden" id="expected-{{ $type }}" value="{{ $st['expectedCash'] ?? 0 }}">
                    <div class="sk-close-grid">
                        <div class="sk-field">
                            <label>Estimasi Sistem</label>
                            <div class="sk-expected">Rp {{ number_format($st['expectedCash'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="sk-field">
                            <label>Uang Fisik Laci (Aktual)</label>
                            <div class="sk-input-wrap">
                                <span class="sk-input-prefix">Rp</span>
                                <input type="text" inputmode="numeric" data-currency name="actual_cash" id="actual-{{ $type }}" min="0" required placeholder="0" oninput="calcVariance('{{ $type }}')">
                            </div>
                        </div>
                        <div class="sk-field">
                            <label>Selisih (Variance)</label>
                            <div class="sk-variance" id="var-box-{{ $type }}">
                                <span class="sk-var-amt" id="var-amt-{{ $type }}">Rp 0</span>
                                <span class="badge badge-gray" id="var-badge-{{ $type }}">Belum diisi</span>
                            </div>
                        </div>
                    </div>
                    <div class="sk-field" style="margin-top: 1rem;">
                        <label>Catatan Selisih (Opsional)</label>
                        <input type="text" name="notes" class="sk-note-input" placeholder="Misal: selisih karena kembalian kurang...">
                    </div>
                    <div class="sk-close-action">
                        <button type="submit" class="sk-close-btn" onclick="return confirmClose('{{ $type }}')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Konfirmasi & Tutup Kasir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    {{-- 5. MUTASI KAS --}}
    @can('delete_sesi_kasir')
    <div class="sk-section">
        <div class="sk-mutasi-card">
            <div class="sk-mutasi-header">
                <h3 class="sk-mutasi-title">Mutasi Kas Manual — {{ $label }}</h3>
                <p class="sk-mutasi-desc">Catat pengeluaran/pemasukan laci di luar transaksi reguler.</p>
            </div>
            <div class="sk-mutasi-body">
                <form method="POST" action="{{ route('kasir.cash_movement') }}" class="sk-mf">
                    @csrf
                    <input type="hidden" name="session_type" value="{{ $type }}">
                    <div class="sk-mf-group">
                        <label>Tipe</label>
                        <select name="type" required>
                            <option value="in">+ Masuk</option>
                            <option value="out">− Keluar</option>
                        </select>
                    </div>
                    <div class="sk-mf-group sk-mf-grow">
                        <label>Nominal (Rp)</label>
                        <input type="text" inputmode="numeric" data-currency name="amount" min="1" required placeholder="0">
                    </div>
                    <div class="sk-mf-group sk-mf-grow2">
                        <label>Keterangan</label>
                        <input type="text" name="notes" placeholder="Misal: beli galon, kembalian...">
                    </div>
                    <div class="sk-mf-group">
                        <button type="submit" class="sk-mf-btn">Catat Mutasi</button>
                    </div>
                </form>

                @if(isset($st['cashMovements']) && $st['cashMovements']->count())
                    <div class="sk-mt-wrap">
                        <table class="sk-mt">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th class="tc">Tipe</th>
                                    <th class="tr">Nominal</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($st['cashMovements'] as $m)
                                    <tr>
                                        <td class="sk-mt-time">{{ optional($m->created_at)->format('H:i, d M') }}</td>
                                        <td class="tc">
                                            @if($m->type==='in')
                                                <span class="sk-tag-in">MASUK</span>
                                            @else
                                                <span class="sk-tag-out">KELUAR</span>
                                            @endif
                                        </td>
                                        <td class="tr sk-mt-mono" style="color: {{ $m->type==='in' ? '#16a34a' : '#dc2626' }}">
                                            {{ $m->type==='in' ? '+' : '-' }}Rp {{ number_format((float)$m->amount, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $m->notes ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="sk-mt-empty">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom: 0.5rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <div>Belum ada mutasi kas pada sesi ini.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endcan

</div>
