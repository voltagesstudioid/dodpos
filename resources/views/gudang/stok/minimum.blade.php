<x-app-layout>
    <x-slot name="header">Alert Minimum Stok</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --ms-bg: #f8fafc;
            --ms-surface: #ffffff;
            --ms-border: #e2e8f0;
            --ms-border-light: #f1f5f9;
            --ms-text: #0f172a;
            --ms-text-secondary: #64748b;
            --ms-text-muted: #94a3b8;
            --ms-primary: #4f46e5;
            --ms-primary-bg: #eef2ff;
            --ms-success: #10b981;
            --ms-success-bg: #ecfdf5;
            --ms-success-text: #065f46;
            --ms-warning: #f59e0b;
            --ms-warning-bg: #fffbeb;
            --ms-warning-text: #b45309;
            --ms-warning-border: #fde68a;
            --ms-danger: #ef4444;
            --ms-danger-bg: #fef2f2;
            --ms-danger-text: #991b1b;
            --ms-danger-border: #fecaca;
            --ms-radius: 10px;
            --ms-radius-lg: 14px;
            --ms-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }

        .ms-wrap { max-width: 1280px; margin: 0 auto; padding: 1.5rem 1rem; background: var(--ms-bg); min-height: 100vh; }

        /* Header */
        .ms-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .ms-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--ms-warning); margin-bottom: 0.25rem; }
        .ms-title { font-size: 1.5rem; font-weight: 800; color: var(--ms-text); margin: 0; display: flex; align-items: center; gap: 0.625rem; letter-spacing: -0.02em; }
        .ms-title-icon { width: 38px; height: 38px; background: var(--ms-warning-bg); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--ms-warning); border: 1px solid var(--ms-warning-border); }
        .ms-subtitle { font-size: 0.85rem; color: var(--ms-text-secondary); margin: 0.35rem 0 0; line-height: 1.5; }
        .ms-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* KPI Stats */
        .ms-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .ms-stat { background: var(--ms-surface); border: 1px solid var(--ms-border); border-radius: var(--ms-radius-lg); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--ms-shadow); border-left: 4px solid; transition: transform 0.15s, box-shadow 0.15s; }
        .ms-stat:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.07); }
        .ms-stat.total { border-left-color: var(--ms-warning); }
        .ms-stat.critical { border-left-color: var(--ms-danger); }
        .ms-stat.empty { border-left-color: #7f1d1d; }
        .ms-stat.value { border-left-color: var(--ms-primary); }
        .ms-stat-ico { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ms-stat-ico.total { background: var(--ms-warning-bg); color: var(--ms-warning); }
        .ms-stat-ico.critical { background: var(--ms-danger-bg); color: var(--ms-danger); }
        .ms-stat-ico.empty { background: #fef2f2; color: #7f1d1d; }
        .ms-stat-ico.value { background: var(--ms-primary-bg); color: var(--ms-primary); }
        .ms-stat-val { font-size: 1.5rem; font-weight: 800; color: var(--ms-text); line-height: 1; }
        .ms-stat-lbl { font-size: 0.72rem; color: var(--ms-text-secondary); font-weight: 600; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.03em; }

        /* Buttons */
        .ms-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .ms-btn-primary { background: var(--ms-primary); color: #fff; box-shadow: 0 2px 6px rgba(79,70,229,0.25); }
        .ms-btn-primary:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(79,70,229,0.3); }
        .ms-btn-outline { border-color: var(--ms-border); color: var(--ms-text-secondary); background: var(--ms-surface); }
        .ms-btn-outline:hover { border-color: var(--ms-text-muted); color: var(--ms-text); background: #f8fafc; }
        .ms-btn-warning { background: var(--ms-warning); color: #fff; box-shadow: 0 2px 6px rgba(245,158,11,0.25); }
        .ms-btn-warning:hover { background: #d97706; transform: translateY(-1px); }
        .ms-btn-sm { padding: 0.35rem 0.75rem; font-size: 0.78rem; }

        /* Filter card */
        .ms-filter-card { background: var(--ms-surface); border: 1px solid var(--ms-border); border-radius: var(--ms-radius-lg); padding: 1rem 1.25rem; margin-bottom: 1.25rem; box-shadow: var(--ms-shadow); }
        .ms-filter-row { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .ms-search { display: flex; align-items: center; gap: 0.5rem; background: var(--ms-bg); border: 1px solid var(--ms-border); border-radius: 8px; padding: 0 0.875rem; flex: 1; min-width: 220px; transition: border-color 0.2s; height: 40px; }
        .ms-search:focus-within { border-color: var(--ms-warning); background: #fff; }
        .ms-search svg { color: var(--ms-text-muted); flex-shrink: 0; }
        .ms-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--ms-text); outline: none; width: 100%; }
        .ms-search input::placeholder { color: var(--ms-text-muted); }
        .ms-select { padding: 0.5rem 2rem 0.5rem 0.875rem; border: 1px solid var(--ms-border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; color: var(--ms-text); background: var(--ms-bg); appearance: none; outline: none; cursor: pointer; transition: border-color 0.2s; height: 40px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; background-size: 14px; }
        .ms-select:focus { border-color: var(--ms-warning); background-color: #fff; }

        /* Main card */
        .ms-card { background: var(--ms-surface); border: 1px solid var(--ms-border); border-radius: var(--ms-radius-lg); overflow: hidden; box-shadow: var(--ms-shadow); }
        .ms-card-head { padding: 1rem 1.5rem; border-bottom: 1px solid var(--ms-border-light); background: #fafbfc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; }
        .ms-card-title { font-size: 0.95rem; font-weight: 800; color: var(--ms-text); margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .ms-card-sub { font-size: 0.78rem; color: var(--ms-text-secondary); margin: 0; }

        /* Table */
        .ms-twrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .ms-tbl { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
        .ms-tbl thead th { font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ms-text-secondary); padding: 0.875rem 1.25rem; border-bottom: 1px solid var(--ms-border); background: var(--ms-bg); white-space: nowrap; text-align: left; }
        .ms-tbl thead th.c, .ms-tbl tbody td.c { text-align: center; }
        .ms-tbl thead th.r, .ms-tbl tbody td.r { text-align: right; }
        .ms-tbl tbody tr { transition: background 0.15s; }
        .ms-tbl tbody tr:hover { background: #fafbfc; }
        .ms-tbl tbody tr.row-critical td { background: #fff7ed; }
        .ms-tbl tbody tr.row-critical:hover td { background: #fef3c7; }
        .ms-tbl tbody tr.row-empty td { background: #fef2f2; }
        .ms-tbl tbody tr.row-empty:hover td { background: #fee2e2; }
        .ms-tbl tbody td { padding: 1rem 1.25rem; font-size: 0.8125rem; vertical-align: middle; border-bottom: 1px solid var(--ms-border-light); }
        .ms-tbl tbody tr:last-child td { border-bottom: none; }

        /* Cell formatting */
        .ms-prod-name { font-weight: 700; color: var(--ms-text); font-size: 0.875rem; }
        .ms-prod-sku { font-size: 0.72rem; color: var(--ms-text-muted); font-family: monospace; margin-top: 3px; background: var(--ms-border-light); display: inline-block; padding: 1px 5px; border-radius: 4px; }
        .ms-cat-badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 6px; background: #f1f5f9; color: #475569; font-size: 0.7rem; font-weight: 700; border: 1px solid var(--ms-border); }

        /* Stock gauge */
        .ms-stock-val { font-size: 1.25rem; font-weight: 800; line-height: 1; }
        .ms-stock-val.ok { color: var(--ms-success); }
        .ms-stock-val.warning { color: var(--ms-warning); }
        .ms-stock-val.critical { color: var(--ms-danger); }
        .ms-stock-val.empty { color: #7f1d1d; }
        .ms-stock-unit { font-size: 0.7rem; color: var(--ms-text-muted); font-weight: 500; margin-top: 2px; }
        .ms-min-val { font-size: 0.8rem; font-weight: 600; color: var(--ms-text-secondary); }
        .ms-stock-bar-wrap { margin-top: 6px; width: 100%; max-width: 120px; }
        .ms-stock-bar { height: 5px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
        .ms-stock-bar-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease; }
        .ms-stock-bar-fill.ok { background: var(--ms-success); }
        .ms-stock-bar-fill.warning { background: var(--ms-warning); }
        .ms-stock-bar-fill.critical { background: var(--ms-danger); }
        .ms-stock-bar-fill.empty { background: #7f1d1d; }

        /* Status badge */
        .ms-status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.3rem 0.65rem; border-radius: 999px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap; border: 1px solid transparent; }
        .ms-status-badge.out { background: #fef2f2; color: #7f1d1d; border-color: var(--ms-danger-border); }
        .ms-status-badge.critical { background: var(--ms-danger-bg); color: var(--ms-danger-text); border-color: var(--ms-danger-border); }
        .ms-status-badge.warning { background: var(--ms-warning-bg); color: var(--ms-warning-text); border-color: var(--ms-warning-border); }
        .ms-pulse { display: inline-block; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; animation: ms-pulse 1.5s infinite; }
        .ms-pulse.out { background: #7f1d1d; }
        .ms-pulse.critical { background: var(--ms-danger); }
        .ms-pulse.warning { background: var(--ms-warning); }
        @keyframes ms-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        /* Action buttons */
        .ms-act-po { display: inline-flex; align-items: center; gap: 4px; padding: 0.3rem 0.7rem; border-radius: 6px; font-size: 0.72rem; font-weight: 700; text-decoration: none; background: var(--ms-primary-bg); color: var(--ms-primary); border: 1px solid #c7d2fe; transition: all 0.2s; white-space: nowrap; }
        .ms-act-po:hover { background: #e0e7ff; border-color: #818cf8; transform: translateY(-1px); }

        /* Empty state */
        .ms-empty { text-align: center; padding: 4rem 2rem; }
        .ms-empty-ico { width: 60px; height: 60px; border-radius: 50%; background: var(--ms-success-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--ms-success); }
        .ms-empty h5 { font-size: 1.05rem; font-weight: 800; color: var(--ms-text); margin: 0 0 0.35rem; }
        .ms-empty p { font-size: 0.85rem; color: var(--ms-text-secondary); margin: 0 auto; max-width: 380px; line-height: 1.5; }

        /* Pagination */
        .ms-pag { padding: 0.875rem 1.25rem; border-top: 1px solid var(--ms-border-light); background: var(--ms-surface); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; }
        .ms-pag-info { font-size: 0.75rem; color: var(--ms-text-muted); }

        /* Responsive */
        @media (max-width: 1024px) { .ms-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            .ms-stats { grid-template-columns: repeat(2, 1fr); }
            .ms-header { flex-direction: column; align-items: flex-start; }
            .ms-header-actions { width: 100%; }
            .ms-btn { width: 100%; justify-content: center; }
            .ms-filter-row { flex-direction: column; }
            .ms-search { width: 100%; }
        }
        @media (max-width: 480px) { .ms-stats { grid-template-columns: 1fr; } }
    </style>
    @endpush

    <div class="ms-wrap">

        {{-- Header --}}
        <div class="ms-header">
            <div>
                <div class="ms-eyebrow">Peringatan Stok</div>
                <h1 class="ms-title">
                    <span class="ms-title-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </span>
                    Alert Minimum Stok
                </h1>
                <p class="ms-subtitle">Produk yang stok globalnya berada di bawah batas minimum. Segera lakukan reorder.</p>
            </div>
            <div class="ms-header-actions">
                <a href="{{ route('gudang.stok') }}" class="ms-btn ms-btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    Semua Stok
                </a>
                @php
                    $reorderQs = '';
                    $reorderParts = [];
                    foreach ($lowStockProducts as $p) {
                        $suggest = max(($p->min_stock ?? 0) - ($p->stock ?? 0), 0);
                        if ($suggest > 0) $reorderParts[] = 'add[]='.$p->id.'&qty[]='.$suggest;
                    }
                    $reorderQs = implode('&', $reorderParts);
                @endphp
                @if(!empty($reorderQs))
                <a href="{{ route('pembelian.order.create') }}?payment_term=credit&due_date={{ now()->addDays(30)->format('Y-m-d') }}&{{ $reorderQs }}" class="ms-btn ms-btn-warning">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Draft PO untuk Semua
                </a>
                @endif
            </div>
        </div>

        {{-- KPI Stats --}}
        @php
            $totalLow = $lowStockProducts->total();
            $criticalCount = $lowStockProducts->filter(fn($p) => ($p->stock ?? 0) <= (($p->min_stock ?? 0) / 2) && ($p->stock ?? 0) > 0)->count();
            $outOfStockCount = $lowStockProducts->filter(fn($p) => ($p->stock ?? 0) == 0)->count();
            $totalEstValue = $lowStockProducts->sum(function($p) {
                $suggest = max(($p->min_stock ?? 0) - ($p->stock ?? 0), 0);
                return $suggest * floatval($p->purchase_price ?? 0);
            });
        @endphp
        <div class="ms-stats">
            <div class="ms-stat total">
                <div class="ms-stat-ico total">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div>
                    <div class="ms-stat-val">{{ number_format($totalLow) }}</div>
                    <div class="ms-stat-lbl">Total SKU Warning</div>
                </div>
            </div>
            <div class="ms-stat critical">
                <div class="ms-stat-ico critical">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div>
                    <div class="ms-stat-val">{{ $criticalCount }}</div>
                    <div class="ms-stat-lbl">Stok Sangat Kritis</div>
                </div>
            </div>
            <div class="ms-stat empty">
                <div class="ms-stat-ico empty">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <div class="ms-stat-val">{{ $outOfStockCount }}</div>
                    <div class="ms-stat-lbl">Out of Stock</div>
                </div>
            </div>
            <div class="ms-stat value">
                <div class="ms-stat-ico value">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div>
                    <div class="ms-stat-val" style="font-size:1rem;">{{ ($hideFinancial ?? false) ? '***' : 'Rp ' . number_format($totalEstValue, 0, ',', '.') }}</div>
                    <div class="ms-stat-lbl">Est. Nilai Reorder</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="ms-filter-card">
            <form method="GET" action="{{ route('gudang.minstok') }}" class="ms-filter-row">
                <div class="ms-search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk / SKU...">
                </div>
                <select name="category" class="ms-select">
                    <option value="">Semua Kategori</option>
                    @foreach(($categories ?? []) as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="level" class="ms-select">
                    <option value="">Semua Level</option>
                    <option value="empty" {{ request('level') == 'empty' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>Sangat Kritis</option>
                    <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>Warning</option>
                </select>
                <button type="submit" class="ms-btn ms-btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filter
                </button>
                @if(request('search') || request('category') || request('level'))
                    <a href="{{ route('gudang.minstok') }}" class="ms-btn ms-btn-outline">Reset</a>
                @endif
            </form>
        </div>

        {{-- Main Table --}}
        <div class="ms-card">
            <div class="ms-card-head">
                <div>
                    <div class="ms-card-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                        Daftar Produk Perlu Reorder
                    </div>
                    <div class="ms-card-sub">{{ $lowStockProducts->total() }} produk membutuhkan perhatian</div>
                </div>
            </div>
            <div class="ms-twrap">
                <table class="ms-tbl">
                    <thead>
                        <tr>
                            <th>Produk & SKU</th>
                            <th>Kategori</th>
                            <th class="c">Batas Min</th>
                            <th class="c">Sisa Stok</th>
                            <th class="c">Status</th>
                            <th class="r">Saran Reorder</th>
                            <th class="r">Est. Nilai</th>
                            <th class="c" style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $product)
                            @php
                                $stock = $product->stock ?? 0;
                                $minStock = $product->min_stock ?? 0;
                                $isCritical = $stock > 0 && $stock <= ($minStock / 2);
                                $isEmpty = $stock == 0;
                                $stockPct = $minStock > 0 ? min(100, ($stock / $minStock) * 100) : 0;
                                $suggest = max($minStock - $stock, 0);
                                $estValue = $suggest * floatval($product->purchase_price ?? 0);

                                $rowClass = $isEmpty ? 'row-empty' : ($isCritical ? 'row-critical' : '');
                                $valClass = $isEmpty ? 'empty' : ($isCritical ? 'critical' : 'warning');
                                $barClass = $isEmpty ? 'empty' : ($isCritical ? 'critical' : 'warning');
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>
                                    <div class="ms-prod-name">{{ $product->name }}</div>
                                    <span class="ms-prod-sku">{{ $product->sku }}</span>
                                </td>
                                <td>
                                    <span class="ms-cat-badge">{{ $product->category?->name ?? '-' }}</span>
                                </td>
                                <td class="c">
                                    <div class="ms-min-val">{{ $minStock }}</div>
                                    <div class="ms-stock-unit">{{ $product->unit?->abbreviation ?? 'pcs' }}</div>
                                </td>
                                <td class="c">
                                    @if(($maskStock ?? false) === true)
                                        <span class="ms-status-badge warning">Terkunci</span>
                                    @else
                                        <div class="ms-stock-val {{ $valClass }}">{{ $stock }}</div>
                                        <div class="ms-stock-unit">{{ $product->unit?->abbreviation ?? 'pcs' }}</div>
                                        <div class="ms-stock-bar-wrap">
                                            <div class="ms-stock-bar">
                                                <div class="ms-stock-bar-fill {{ $barClass }}" style="width: {{ $stockPct }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="c">
                                    @if($isEmpty)
                                        <span class="ms-status-badge out">
                                            <span class="ms-pulse out"></span>
                                            Out of Stock
                                        </span>
                                    @elseif($isCritical)
                                        <span class="ms-status-badge critical">
                                            <span class="ms-pulse critical"></span>
                                            Sangat Kritis
                                        </span>
                                    @else
                                        <span class="ms-status-badge warning">
                                            <span class="ms-pulse warning"></span>
                                            Reorder Soon
                                        </span>
                                    @endif
                                </td>
                                <td class="r">
                                    <div style="font-weight:800; color:var(--ms-primary); font-size:1rem;">+{{ $suggest }}</div>
                                    <div class="ms-stock-unit">{{ $product->unit?->abbreviation ?? 'pcs' }}</div>
                                </td>
                                <td class="r">
                                    <div style="font-weight:700; color:var(--ms-text); font-size:0.875rem;">
                                        {{ ($hideFinancial ?? false) ? '***' : 'Rp ' . number_format($estValue, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="c">
                                    @if($suggest > 0)
                                    <a href="{{ route('pembelian.order.create') }}?payment_term=credit&due_date={{ now()->addDays(30)->format('Y-m-d') }}&add[]={{ $product->id }}&qty[]={{ $suggest }}" class="ms-act-po">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                        Draft PO
                                    </a>
                                    @else
                                        <span style="color:var(--ms-text-muted); font-size:0.75rem;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="ms-empty">
                                        <div class="ms-empty-ico">
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        </div>
                                        <h5>Stok Aman! 🎉</h5>
                                        <p>Saat ini tidak ada produk yang berada di bawah batas minimum stok. Gudang dalam kondisi baik.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($lowStockProducts->hasPages())
                <div class="ms-pag">
                    <div class="ms-pag-info">
                        Menampilkan {{ $lowStockProducts->firstItem() ?? 0 }}–{{ $lowStockProducts->lastItem() ?? 0 }} dari {{ $lowStockProducts->total() }} produk
                    </div>
                    <div>{{ $lowStockProducts->withQueryString()->links() }}</div>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
