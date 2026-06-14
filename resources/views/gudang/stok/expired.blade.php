<x-app-layout>
    <x-slot name="header">Barang Expired & Segera Expired</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --ex-bg: #f8fafc;
            --ex-surface: #ffffff;
            --ex-border: #e2e8f0;
            --ex-border-light: #f1f5f9;
            --ex-text: #0f172a;
            --ex-text-secondary: #64748b;
            --ex-text-muted: #94a3b8;
            --ex-primary: #4f46e5;
            --ex-primary-bg: #eef2ff;
            --ex-success: #10b981;
            --ex-success-bg: #ecfdf5;
            --ex-success-text: #065f46;
            --ex-warning: #f59e0b;
            --ex-warning-bg: #fffbeb;
            --ex-warning-text: #b45309;
            --ex-warning-border: #fde68a;
            --ex-danger: #ef4444;
            --ex-danger-bg: #fef2f2;
            --ex-danger-text: #991b1b;
            --ex-danger-border: #fecaca;
            --ex-orange: #f97316;
            --ex-orange-bg: #fff7ed;
            --ex-orange-text: #c2410c;
            --ex-orange-border: #fed7aa;
            --ex-radius: 10px;
            --ex-radius-lg: 14px;
            --ex-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .ex-wrap { max-width: 1280px; margin: 0 auto; padding: 1.5rem 1rem; background: var(--ex-bg); min-height: 100vh; }

        /* Header */
        .ex-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .ex-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--ex-danger-text); margin-bottom: 0.25rem; }
        .ex-title { font-size: 1.5rem; font-weight: 800; color: var(--ex-text); margin: 0; display: flex; align-items: center; gap: 0.625rem; letter-spacing: -0.02em; }
        .ex-title-icon { width: 38px; height: 38px; background: var(--ex-danger-bg); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--ex-danger); border: 1px solid var(--ex-danger-border); }
        .ex-subtitle { font-size: 0.85rem; color: var(--ex-text-secondary); margin: 0.35rem 0 0; line-height: 1.5; }
        .ex-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* Buttons */
        .ex-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .ex-btn-outline { border-color: var(--ex-border); color: var(--ex-text-secondary); background: var(--ex-surface); }
        .ex-btn-outline:hover { border-color: var(--ex-text-muted); color: var(--ex-text); background: #f8fafc; }
        .ex-btn-danger { background: var(--ex-danger); color: #fff; }
        .ex-btn-danger:hover { background: #dc2626; transform: translateY(-1px); }

        /* KPI Stats */
        .ex-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .ex-stat { background: var(--ex-surface); border: 1px solid var(--ex-border); border-radius: var(--ex-radius-lg); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--ex-shadow); border-left: 4px solid; transition: transform 0.15s, box-shadow 0.15s; }
        .ex-stat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.07); }
        .ex-stat.expired { border-left-color: var(--ex-danger); }
        .ex-stat.week { border-left-color: var(--ex-orange); }
        .ex-stat.month { border-left-color: var(--ex-warning); }
        .ex-stat.total { border-left-color: var(--ex-primary); }
        .ex-stat-ico { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ex-stat-ico.expired { background: var(--ex-danger-bg); color: var(--ex-danger); }
        .ex-stat-ico.week { background: var(--ex-orange-bg); color: var(--ex-orange); }
        .ex-stat-ico.month { background: var(--ex-warning-bg); color: var(--ex-warning); }
        .ex-stat-ico.total { background: var(--ex-primary-bg); color: var(--ex-primary); }
        .ex-stat-val { font-size: 1.5rem; font-weight: 800; color: var(--ex-text); line-height: 1; }
        .ex-stat-lbl { font-size: 0.72rem; color: var(--ex-text-secondary); font-weight: 600; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.03em; }

        /* Main card */
        .ex-card { background: var(--ex-surface); border: 1px solid var(--ex-border); border-radius: var(--ex-radius-lg); overflow: hidden; box-shadow: var(--ex-shadow); }
        .ex-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid var(--ex-border-light); background: #fafbfc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; }
        .ex-card-title { font-size: 0.95rem; font-weight: 800; color: var(--ex-text); margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .ex-card-sub { font-size: 0.78rem; color: var(--ex-text-secondary); margin: 0; }

        /* Filter */
        .ex-filter-card { background: var(--ex-surface); border: 1px solid var(--ex-border); border-radius: var(--ex-radius-lg); padding: 1rem 1.25rem; margin-bottom: 1.25rem; box-shadow: var(--ex-shadow); }
        .ex-filter-row { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .ex-search { display: flex; align-items: center; gap: 0.5rem; background: var(--ex-bg); border: 1px solid var(--ex-border); border-radius: 8px; padding: 0 0.875rem; flex: 1; min-width: 220px; transition: border-color 0.2s; height: 40px; }
        .ex-search:focus-within { border-color: var(--ex-danger); background: #fff; }
        .ex-search svg { color: var(--ex-text-muted); flex-shrink: 0; }
        .ex-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--ex-text); outline: none; width: 100%; }
        .ex-search input::placeholder { color: var(--ex-text-muted); }
        .ex-select { padding: 0.5rem 2rem 0.5rem 0.875rem; border: 1px solid var(--ex-border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; color: var(--ex-text); background: var(--ex-bg); appearance: none; outline: none; cursor: pointer; transition: border-color 0.2s; height: 40px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; background-size: 14px; }
        .ex-select:focus { border-color: var(--ex-danger); background-color: #fff; }

        /* Table */
        .ex-twrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .ex-tbl { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .ex-tbl thead th { font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ex-text-secondary); padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--ex-border); background: var(--ex-bg); white-space: nowrap; text-align: left; }
        .ex-tbl thead th.c, .ex-tbl tbody td.c { text-align: center; }
        .ex-tbl thead th.r, .ex-tbl tbody td.r { text-align: right; }
        .ex-tbl tbody tr { transition: background 0.15s; }
        .ex-tbl tbody tr:hover { background: #fafbfc; }
        .ex-tbl tbody tr.row-expired td { background: #fef2f2; }
        .ex-tbl tbody tr.row-expired:hover td { background: #fee2e2; }
        .ex-tbl tbody tr.row-soon td { background: var(--ex-orange-bg); }
        .ex-tbl tbody tr.row-soon:hover td { background: #ffedd5; }
        .ex-tbl tbody tr.row-warning td { background: var(--ex-warning-bg); }
        .ex-tbl tbody tr.row-warning:hover td { background: #fef3c7; }
        .ex-tbl tbody td { padding: 1rem 1.25rem; font-size: 0.8125rem; vertical-align: middle; border-bottom: 1px solid var(--ex-border-light); }
        .ex-tbl tbody tr:last-child td { border-bottom: none; }

        /* Cells */
        .ex-prod-name { font-weight: 700; color: var(--ex-text); font-size: 0.875rem; }
        .ex-prod-batch { font-size: 0.72rem; color: var(--ex-text-muted); font-family: monospace; margin-top: 3px; background: var(--ex-border-light); display: inline-block; padding: 1px 5px; border-radius: 4px; }
        .ex-wh-pill { background: #eef2ff; color: var(--ex-primary); font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 99px; display: inline-block; }
        .ex-loc-text { font-size: 0.7rem; color: var(--ex-text-muted); margin-top: 5px; display: flex; align-items: center; gap: 4px; font-weight: 500; }
        .ex-loc-text.text-muted { color: var(--ex-text-muted); font-style: italic; }
        .ex-stock-qty { font-size: 1.1rem; font-weight: 800; color: var(--ex-text); }
        .ex-stock-unit { font-size: 0.7rem; color: var(--ex-text-muted); font-weight: 500; margin-top: 2px; }

        /* Date display */
        .ex-date-row { display: flex; align-items: center; gap: 6px; font-weight: 600; color: var(--ex-text); }
        .ex-date-row svg { color: var(--ex-text-muted); flex-shrink: 0; }
        .ex-days-left { margin-top: 4px; font-size: 0.72rem; font-weight: 700; }
        .ex-days-left.expired { color: var(--ex-danger-text); }
        .ex-days-left.soon { color: var(--ex-orange-text); }
        .ex-days-left.warning { color: var(--ex-warning-text); }

        /* Alert badge */
        .ex-alert-badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.3rem 0.65rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; border: 1px solid transparent; }
        .ex-alert-badge.expired { background: var(--ex-danger-bg); color: var(--ex-danger-text); border-color: var(--ex-danger-border); }
        .ex-alert-badge.soon { background: var(--ex-orange-bg); color: var(--ex-orange-text); border-color: var(--ex-orange-border); }
        .ex-alert-badge.warning { background: var(--ex-warning-bg); color: var(--ex-warning-text); border-color: var(--ex-warning-border); }
        .ex-pulse { display: inline-block; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; animation: ex-pulse 1.5s infinite; }
        .ex-pulse.expired { background: var(--ex-danger); }
        .ex-pulse.soon { background: var(--ex-orange); }
        .ex-pulse.warning { background: var(--ex-warning); }
        @keyframes ex-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        /* Empty state */
        .ex-empty { text-align: center; padding: 4rem 2rem; }
        .ex-empty-ico { width: 60px; height: 60px; border-radius: 50%; background: var(--ex-success-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--ex-success); }
        .ex-empty h5 { font-size: 1.05rem; font-weight: 800; color: var(--ex-text); margin: 0 0 0.35rem; }
        .ex-empty p { font-size: 0.85rem; color: var(--ex-text-secondary); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* Pagination */
        .ex-pag { padding: 0.875rem 1.25rem; border-top: 1px solid var(--ex-border-light); background: var(--ex-surface); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; }
        .ex-pag-info { font-size: 0.75rem; color: var(--ex-text-muted); }

        @media (max-width: 1024px) { .ex-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            .ex-header { flex-direction: column; align-items: flex-start; }
            .ex-header-actions { width: 100%; }
            .ex-btn { width: 100%; justify-content: center; }
            .ex-filter-row { flex-direction: column; }
            .ex-search { width: 100%; }
        }
    </style>
    @endpush

    <div class="ex-wrap">

        {{-- Header --}}
        <div class="ex-header">
            <div>
                <div class="ex-eyebrow">Peringatan Sistem</div>
                <h1 class="ex-title">
                    <span class="ex-title-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    Barang Kedaluwarsa
                </h1>
                <p class="ex-subtitle">Daftar stok yang sudah expired atau akan expired dalam <strong>{{ $daysThreshold }} hari</strong> ke depan.</p>
            </div>
            <div class="ex-header-actions">
                <a href="{{ route('gudang.stok') }}" class="ex-btn ex-btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    Semua Stok
                </a>
            </div>
        </div>

        {{-- KPI Stats --}}
        @php
            $alreadyExpired = $expiredStocks->filter(fn($s) => \Carbon\Carbon::parse($s->expired_date)->isPast() && !\Carbon\Carbon::parse($s->expired_date)->isToday())->count();
            $expireIn7 = $expiredStocks->filter(fn($s) => !\Carbon\Carbon::parse($s->expired_date)->isPast() && \Carbon\Carbon::parse($s->expired_date)->diffInDays(now()) <= 7)->count();
            $expireIn30 = $expiredStocks->filter(fn($s) => !\Carbon\Carbon::parse($s->expired_date)->isPast() && \Carbon\Carbon::parse($s->expired_date)->diffInDays(now()) <= 30)->count();
        @endphp
        <div class="ex-stats">
            <div class="ex-stat expired">
                <div class="ex-stat-ico expired">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <div class="ex-stat-val">{{ $alreadyExpired }}</div>
                    <div class="ex-stat-lbl">Sudah Expired</div>
                </div>
            </div>
            <div class="ex-stat week">
                <div class="ex-stat-ico week">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div>
                    <div class="ex-stat-val">{{ $expireIn7 }}</div>
                    <div class="ex-stat-lbl">Expired 7 Hari</div>
                </div>
            </div>
            <div class="ex-stat month">
                <div class="ex-stat-ico month">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <div class="ex-stat-val">{{ $expireIn30 }}</div>
                    <div class="ex-stat-lbl">Expired 30 Hari</div>
                </div>
            </div>
            <div class="ex-stat total">
                <div class="ex-stat-ico total">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div>
                    <div class="ex-stat-val">{{ $expiredStocks->total() }}</div>
                    <div class="ex-stat-lbl">Total Batch</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="ex-filter-card">
            <form method="GET" action="{{ route('gudang.expired') }}" class="ex-filter-row">
                <div class="ex-search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk / batch...">
                </div>
                <select name="status" class="ex-select">
                    <option value="">Semua Status</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Sudah Expired</option>
                    <option value="7days" {{ request('status') == '7days' ? 'selected' : '' }}>Expired 7 Hari</option>
                    <option value="30days" {{ request('status') == '30days' ? 'selected' : '' }}>Expired 30 Hari</option>
                </select>
                <button type="submit" class="ex-btn ex-btn-danger">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('gudang.expired') }}" class="ex-btn ex-btn-outline">Reset</a>
                @endif
            </form>
        </div>

        {{-- Main Card --}}
        <div class="ex-card">
            <div class="ex-card-head">
                <div>
                    <div class="ex-card-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Daftar Stok Kedaluwarsa
                    </div>
                    <div class="ex-card-sub">{{ $expiredStocks->total() }} batch perlu perhatian segera</div>
                </div>
            </div>
            <div class="ex-twrap">
                <table class="ex-tbl">
                    <thead>
                        <tr>
                            <th>Produk & Batch</th>
                            <th>Gudang & Lokasi</th>
                            <th class="r">Sisa Stok</th>
                            <th>Tanggal Expired</th>
                            <th class="c">Status Peringatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expiredStocks as $stock)
                            @php
                                $expDate = \Carbon\Carbon::parse($stock->expired_date);
                                $isExpired = $expDate->isPast() && !$expDate->isToday();
                                $daysLeft = round(now()->diffInDays($expDate, false));
                                $isSoon = !$isExpired && $daysLeft <= 7;
                                $isWarning = !$isExpired && !$isSoon && $daysLeft <= 30;

                                $rowClass = $isExpired ? 'row-expired' : ($isSoon ? 'row-soon' : 'row-warning');
                                $badgeClass = $isExpired ? 'expired' : ($isSoon ? 'soon' : 'warning');
                                $daysClass = $isExpired ? 'expired' : ($isSoon ? 'soon' : 'warning');
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>
                                    <div class="ex-prod-name">{{ $stock->product?->name ?? 'Produk Dihapus' }}</div>
                                    @if($stock->batch_number)
                                        <span class="ex-prod-batch">Batch: {{ $stock->batch_number }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="ex-wh-pill">{{ $stock->warehouse?->name ?? '-' }}</span>
                                    @if($stock->location)
                                        <div class="ex-loc-text">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            Rak: {{ $stock->location?->name }}
                                        </div>
                                    @else
                                        <div class="ex-loc-text text-muted">Area Umum</div>
                                    @endif
                                </td>
                                <td class="r">
                                    <div class="ex-stock-qty">{{ $stock->stock }}</div>
                                    <div class="ex-stock-unit">{{ $stock->product?->unit?->abbreviation ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="ex-date-row">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        {{ $expDate->format('d M Y') }}
                                    </div>
                                    <div class="ex-days-left {{ $daysClass }}">
                                        @if($isExpired)
                                            {{ abs($daysLeft) }} hari yang lalu
                                        @else
                                            {{ $daysLeft }} hari lagi
                                        @endif
                                    </div>
                                </td>
                                <td class="c">
                                    @if($isExpired)
                                        <span class="ex-alert-badge expired">
                                            <span class="ex-pulse expired"></span>
                                            Sudah Expired
                                        </span>
                                    @elseif($isSoon)
                                        <span class="ex-alert-badge soon">
                                            <span class="ex-pulse soon"></span>
                                            Kritis (≤7 hari)
                                        </span>
                                    @else
                                        <span class="ex-alert-badge warning">
                                            <span class="ex-pulse warning"></span>
                                            Segera Expired
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="ex-empty">
                                        <div class="ex-empty-ico">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        </div>
                                        <h5>Stok Barang Aman! 🎉</h5>
                                        <p>Tidak ada stok yang kadaluwarsa atau mendekati masa kadaluwarsa dalam <strong>{{ $daysThreshold }} hari</strong> ke depan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expiredStocks->hasPages())
                <div class="ex-pag">
                    <div class="ex-pag-info">
                        Menampilkan {{ $expiredStocks->firstItem() ?? 0 }}–{{ $expiredStocks->lastItem() ?? 0 }} dari {{ $expiredStocks->total() }} batch
                    </div>
                    <div>{{ $expiredStocks->withQueryString()->links() }}</div>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>