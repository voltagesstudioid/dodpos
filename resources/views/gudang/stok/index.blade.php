<x-app-layout>
    <x-slot name="header">Rekap Stok Barang</x-slot>

    @push('styles')
    <style>
        .v3-page { max-width: 1360px; margin: 0 auto; padding: 0 1.5rem 3rem; }

        .v3-hero {
            background: linear-gradient(135deg, #0c1222 0%, #162032 40%, #0f172a 100%);
            border-radius: 0 0 28px 28px;
            padding: 2.5rem 2.5rem 5rem;
            margin: -1.5rem -1.5rem 0;
            position: relative; overflow: hidden;
        }
        .v3-hero::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 600px 400px at 80% 10%, rgba(16,185,129,0.18) 0%, transparent 70%),
                radial-gradient(ellipse 500px 350px at 20% 90%, rgba(99,102,241,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 300px 300px at 50% 50%, rgba(14,165,233,0.08) 0%, transparent 70%);
        }
        .v3-hero::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent 5%, rgba(16,185,129,0.4) 30%, rgba(99,102,241,0.3) 70%, transparent 95%);
        }
        .v3-hero-inner { position: relative; z-index: 1; }

        .v3-hero-top { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
        .v3-hero-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25);
            padding: 5px 14px; border-radius: 99px;
            font-size: 0.65rem; font-weight: 700; color: rgba(110,231,183,0.9);
            text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 1rem;
        }
        .v3-hero-chip-dot { width: 6px; height: 6px; border-radius: 50%; background: #34d399; animation: v3-glow 2s ease-in-out infinite; }
        @keyframes v3-glow { 0%,100% { opacity:1; box-shadow: 0 0 0 0 rgba(52,211,153,0.4); } 50% { opacity:0.7; box-shadow: 0 0 0 6px rgba(52,211,153,0); } }

        .v3-hero-title {
            font-size: 2.25rem; font-weight: 900; color: #fff;
            letter-spacing: -0.04em; line-height: 1.05; margin: 0 0 0.5rem;
        }
        .v3-hero-sub { font-size: 0.875rem; color: rgba(255,255,255,0.4); margin: 0; line-height: 1.6; }

        .v3-hero-actions { display: flex; gap: 0.625rem; flex-wrap: wrap; align-items: flex-start; padding-top: 0.5rem; }
        .v3-btn-export {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
            color: #fff; padding: 0.7rem 1.375rem; border-radius: 12px;
            font-weight: 700; font-size: 0.8125rem; text-decoration: none;
            transition: all 0.25s; backdrop-filter: blur(12px);
        }
        .v3-btn-export:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.25); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .v3-btn-export svg { opacity: 0.7; }

        .v3-stats-float {
            position: relative; z-index: 10; margin-top: -2.75rem;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .v3-stat {
            background: rgba(255,255,255,0.92); backdrop-filter: blur(20px) saturate(1.8);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 16px; padding: 1.375rem 1.5rem;
            transition: all 0.3s; position: relative; overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        }
        .v3-stat:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.1), 0 2px 6px rgba(0,0,0,0.04); }
        .v3-stat::after {
            content: ''; position: absolute; bottom: 0; left: 1.5rem; right: 1.5rem; height: 3px;
            border-radius: 3px 3px 0 0; background: var(--c);
        }
        .v3-stat-top { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.875rem; }
        .v3-stat-ico {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .v3-stat-ico svg { width: 20px; height: 20px; }
        .v3-stat-ico.c-blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
        .v3-stat-ico.c-purple { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
        .v3-stat-ico.c-green { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; }
        .v3-stat-ico.c-amber { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }
        .v3-stat-label { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; }
        .v3-stat-value {
            font-size: 1.75rem; font-weight: 900; color: #0f172a;
            font-family: ui-monospace, 'SF Mono', monospace; letter-spacing: -0.03em; line-height: 1;
        }
        .v3-stat-value.sm { font-size: 1.25rem; }
        .v3-stat-foot { font-size: 0.7rem; color: #94a3b8; margin-top: 0.375rem; font-weight: 500; }

        .v3-alert {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.875rem 1.25rem; border-radius: 14px;
            margin-bottom: 1.25rem; font-size: 0.8125rem; font-weight: 600;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border: 1px solid #fde68a; color: #92400e;
            box-shadow: 0 2px 8px rgba(245,158,11,0.08);
        }
        .v3-alert svg { width: 20px; height: 20px; flex-shrink: 0; }

        .v3-panel {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 20px;
            overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        }
        .v3-panel-head {
            padding: 1.375rem 1.75rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 0.75rem;
        }
        .v3-panel-title {
            font-size: 1.0625rem; font-weight: 800; color: #0f172a; margin: 0;
            display: flex; align-items: center; gap: 0.625rem;
        }
        .v3-panel-title-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #34d399);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        }
        .v3-panel-count {
            font-size: 0.75rem; font-weight: 700; color: #64748b;
            background: #f1f5f9; padding: 4px 12px; border-radius: 99px;
        }

        .v3-filter {
            padding: 1.125rem 1.75rem; border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(180deg, #f8fafc, #fff);
        }
        .v3-filter-form { display: flex; gap: 0.625rem; align-items: center; flex-wrap: wrap; }
        .v3-search {
            flex: 1; min-width: 220px; position: relative;
        }
        .v3-search-ico {
            position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; pointer-events: none;
        }
        .v3-search-ico svg { width: 16px; height: 16px; }
        .v3-search input {
            width: 100%; padding: 0.6875rem 1rem 0.6875rem 2.625rem;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.8125rem; background: #fff; color: #0f172a;
            font-family: inherit; outline: none; transition: all 0.2s;
        }
        .v3-search input:focus { border-color: #10b981; box-shadow: 0 0 0 4px rgba(16,185,129,0.08); }
        .v3-search input::placeholder { color: #94a3b8; }

        .v3-sel {
            padding: 0.6875rem 2.25rem 0.6875rem 0.875rem;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.8125rem; background: #fff; color: #0f172a;
            font-family: inherit; outline: none; cursor: pointer;
            transition: all 0.2s; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 0.625rem center;
        }
        .v3-sel:focus { border-color: #10b981; box-shadow: 0 0 0 4px rgba(16,185,129,0.08); }

        .v3-btn-go {
            padding: 0.6875rem 1.25rem; border-radius: 10px;
            background: #0f172a; color: #fff; font-size: 0.8125rem;
            font-weight: 700; border: none; cursor: pointer;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;
            font-family: inherit;
        }
        .v3-btn-go:hover { background: #1e293b; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,23,42,0.2); }
        .v3-btn-go svg { width: 14px; height: 14px; }

        .v3-btn-x {
            padding: 0.6875rem 0.875rem; border-radius: 10px;
            background: #fff; color: #ef4444; font-size: 0.8125rem;
            font-weight: 600; border: 1.5px solid #fecaca; cursor: pointer;
            transition: all 0.2s; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .v3-btn-x:hover { background: #fef2f2; border-color: #f87171; }
        .v3-btn-x svg { width: 14px; height: 14px; }

        .v3-tbl-wrap { overflow-x: auto; }
        .v3-tbl { width: 100%; border-collapse: separate; border-spacing: 0; }
        .v3-tbl thead th {
            padding: 0.8125rem 1.5rem; text-align: left;
            font-size: 0.625rem; font-weight: 800; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.08em;
            background: #f8fafc; border-bottom: 1px solid #e2e8f0;
            white-space: nowrap; position: sticky; top: 0; z-index: 2;
        }
        .v3-tbl thead th.sort { cursor: pointer; user-select: none; }
        .v3-tbl thead th.sort:hover { color: #10b981; }
        .v3-tbl thead th.tc { text-align: center; }

        .v3-tbl tbody td {
            padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;
            font-size: 0.8125rem; color: #334155; vertical-align: middle;
        }
        .v3-tbl tbody tr { transition: all 0.15s ease; }
        .v3-tbl tbody tr:hover td { background: #f0fdf4; }
        .v3-tbl tbody tr:last-child td { border-bottom: none; }
        .v3-tbl .tc { text-align: center; }

        .v3-row-low td { background: linear-gradient(90deg, #fffbeb, #fef9c3) !important; }
        .v3-row-low:hover td { background: linear-gradient(90deg, #fef9c3, #fef08a) !important; }
        .v3-row-empty td { background: linear-gradient(90deg, #fef2f2, #fee2e2) !important; }
        .v3-row-empty:hover td { background: linear-gradient(90deg, #fee2e2, #fecaca) !important; }

        .v3-prod-name { font-weight: 700; color: #0f172a; font-size: 0.875rem; line-height: 1.3; }
        .v3-prod-sku { font-size: 0.6875rem; color: #94a3b8; font-family: ui-monospace, monospace; margin-top: 2px; font-weight: 500; }

        .v3-cat {
            display: inline-flex; align-items: center; padding: 3px 10px;
            border-radius: 6px; font-size: 0.6875rem; font-weight: 700;
            background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
        }

        .v3-wh-name { font-weight: 600; color: #0f172a; font-size: 0.8125rem; }
        .v3-wh-loc {
            font-size: 0.6875rem; color: #64748b; margin-top: 2px;
            display: flex; align-items: center; gap: 3px;
        }
        .v3-wh-loc svg { width: 11px; height: 11px; color: #94a3b8; }

        .v3-batch {
            display: inline-block; padding: 2px 8px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #4338ca; font-family: ui-monospace, monospace;
            font-size: 0.6875rem; font-weight: 700; border-radius: 5px;
            margin-bottom: 3px; letter-spacing: 0.02em;
        }
        .v3-exp { font-size: 0.8125rem; font-weight: 600; }
        .v3-exp.safe { color: #059669; }
        .v3-exp.warn { color: #d97706; }
        .v3-exp.danger { color: #dc2626; }
        .v3-exp-days { font-size: 0.625rem; font-weight: 700; margin-top: 2px; }
        .v3-exp-days.safe { color: #10b981; }
        .v3-exp-days.warn { color: #f59e0b; }
        .v3-exp-days.danger { color: #ef4444; }

        .v3-stock-cell { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .v3-stock-num {
            font-size: 1.25rem; font-weight: 900;
            font-family: ui-monospace, 'SF Mono', monospace;
            line-height: 1;
        }
        .v3-stock-num.ok { color: #059669; }
        .v3-stock-num.low { color: #d97706; }
        .v3-stock-num.out { color: #dc2626; }
        .v3-stock-num.mask { color: #94a3b8; }
        .v3-stock-brk { font-size: 0.625rem; color: #6366f1; font-weight: 700; letter-spacing: 0.01em; }
        .v3-stock-unit { font-size: 0.6875rem; color: #94a3b8; font-weight: 500; }
        .v3-stock-min {
            font-size: 0.5625rem; color: #b45309; font-weight: 800;
            background: #fef3c7; padding: 1px 7px; border-radius: 4px;
            text-transform: uppercase; letter-spacing: 0.04em;
        }

        .v3-empty {
            text-align: center; padding: 4rem 2rem;
        }
        .v3-empty-ico {
            width: 72px; height: 72px; border-radius: 20px;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem; border: 1px solid #bbf7d0;
        }
        .v3-empty-ico svg { width: 32px; height: 32px; color: #10b981; }
        .v3-empty h6 { font-size: 1rem; font-weight: 800; color: #374151; margin: 0 0 0.375rem; }
        .v3-empty p { font-size: 0.8125rem; color: #94a3b8; margin: 0; }

        .v3-pag { padding: 1rem 1.75rem; border-top: 1px solid #f1f5f9; background: #fafbfc; }

        @media (max-width: 1024px) {
            .v3-stats-float { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .v3-hero { padding: 1.75rem 1.25rem 4rem; border-radius: 0 0 20px 20px; }
            .v3-hero-title { font-size: 1.625rem; }
            .v3-stats-float { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .v3-filter-form { flex-direction: column; align-items: stretch; }
            .v3-search { min-width: 0; }
            .v3-page { padding: 0 1rem 2rem; }
        }
        @media (max-width: 480px) {
            .v3-stats-float { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    <div class="v3-page">

        <div class="v3-hero">
            <div class="v3-hero-inner">
                <div class="v3-hero-top">
                    <div>
                        <div class="v3-hero-chip"><span class="v3-hero-chip-dot"></span> Inventory Management</div>
                        <h1 class="v3-hero-title">Rekap Stok Barang</h1>
                        <p class="v3-hero-sub">Pantau penyebaran stok produk di seluruh gudang dan lokasi rak secara real-time.</p>
                    </div>
                    <div class="v3-hero-actions">
                        <a href="{{ route('gudang.stok.export', request()->only(['warehouse_id','category_id','search'])) }}" class="v3-btn-export">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="v3-stats-float">
            <div class="v3-stat" style="--c: #3b82f6;">
                <div class="v3-stat-top">
                    <div class="v3-stat-ico c-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6v6H9z"/></svg>
                    </div>
                    <div class="v3-stat-label">Total Record</div>
                </div>
                <div class="v3-stat-value">{{ number_format($totalRecords ?? 0) }}</div>
                <div class="v3-stat-foot">record stok aktif</div>
            </div>
            <div class="v3-stat" style="--c: #8b5cf6;">
                <div class="v3-stat-top">
                    <div class="v3-stat-ico c-purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div class="v3-stat-label">Nilai Stok</div>
                </div>
                <div class="v3-stat-value sm">{{ ($hideFinancial ?? false) ? '***' : 'Rp ' . number_format($totalStockValue ?? 0, 0, ',', '.') }}</div>
                <div class="v3-stat-foot">estimasi total inventory</div>
            </div>
            <div class="v3-stat" style="--c: #10b981;">
                <div class="v3-stat-top">
                    <div class="v3-stat-ico c-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    </div>
                    <div class="v3-stat-label">Gudang Aktif</div>
                </div>
                <div class="v3-stat-value">{{ $activeWarehouses ?? 0 }}</div>
                <div class="v3-stat-foot">lokasi penyimpanan</div>
            </div>
            <div class="v3-stat" style="--c: {{ ($lowStockCount ?? 0) > 0 ? '#f59e0b' : '#10b981' }};">
                <div class="v3-stat-top">
                    <div class="v3-stat-ico c-amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="v3-stat-label">Stok Menipis</div>
                </div>
                <div class="v3-stat-value" style="color: {{ ($lowStockCount ?? 0) > 0 ? '#d97706' : '#059669' }}">{{ $lowStockCount ?? 0 }}</div>
                <div class="v3-stat-foot">perlu restock segera</div>
            </div>
        </div>

        @if($maskStock)
            <div class="v3-alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Stok disembunyikan karena ada proses opname aktif.</span>
            </div>
        @endif

        <div class="v3-panel">
            <div class="v3-panel-head">
                <h3 class="v3-panel-title">
                    <span class="v3-panel-title-dot"></span>
                    Data Stok Per Gudang
                </h3>
                <span class="v3-panel-count">{{ $totalRecords ?? 0 }} record</span>
            </div>

            <div class="v3-filter">
                <form method="GET" action="{{ route('gudang.stok') }}" class="v3-filter-form" id="filter-form">
                    <div class="v3-search">
                        <span class="v3-search-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang atau SKU..." id="search-input">
                    </div>

                    <select name="warehouse_id" class="v3-sel">
                        <option value="">Semua Gudang</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ $warehouseId == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                        @endforeach
                    </select>

                    <select name="category_id" class="v3-sel">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="v3-btn-go">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filter
                    </button>

                    @if(request('search') || request('warehouse_id') || request('category_id'))
                        <a href="{{ route('gudang.stok') }}" class="v3-btn-x">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="v3-tbl-wrap">
                <table class="v3-tbl">
                    <thead>
                        <tr>
                            @php
                                $sortUrl = function($col) use ($sort, $dir) {
                                    $newDir = ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                                    return route('gudang.stok', array_merge(request()->except(['sort','dir','page']), ['sort' => $col, 'dir' => $newDir]));
                                };
                                $arrow = function($col) use ($sort, $dir) {
                                    if ($sort !== $col) return '<span style="opacity:0.25; margin-left:4px; font-size:0.7rem;">&#8693;</span>';
                                    return $dir === 'asc' ? '<span style="color:#10b981; margin-left:4px; font-size:0.7rem;">&#9650;</span>' : '<span style="color:#10b981; margin-left:4px; font-size:0.7rem;">&#9660;</span>';
                                };
                            @endphp
                            <th class="sort" onclick="location.href='{{ $sortUrl('product') }}'">Barang / SKU {!! $arrow('product') !!}</th>
                            <th class="sort" onclick="location.href='{{ $sortUrl('category') }}'">Kategori {!! $arrow('category') !!}</th>
                            <th class="sort" onclick="location.href='{{ $sortUrl('warehouse') }}'">Gudang & Lokasi {!! $arrow('warehouse') !!}</th>
                            <th>Batch / Expired</th>
                            <th class="tc sort" onclick="location.href='{{ $sortUrl('stock') }}'">Sisa Stok {!! $arrow('stock') !!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            @php
                                $minStk = $stock->product->min_stock ?? 0;
                                $isLow = $stock->stock > 0 && $stock->stock <= $minStk;
                                $isEmpty = $stock->stock == 0;
                                $displayStock = $maskStock ? '***' : number_format($stock->stock);
                            @endphp
                            <tr class="{{ $isLow ? 'v3-row-low' : ($isEmpty ? 'v3-row-empty' : '') }}">
                                <td>
                                    <div class="v3-prod-name">{{ $stock->product->name ?? '-' }}</div>
                                    <div class="v3-prod-sku">{{ $stock->product->sku ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="v3-cat">{{ $stock->product->category->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="v3-wh-name">{{ $stock->warehouse->name ?? '-' }}</div>
                                    <div class="v3-wh-loc">
                                        @if($stock->location)
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            {{ $stock->location->name }}
                                        @else
                                            Area Umum
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($stock->batch_number)
                                        <span class="v3-batch">{{ $stock->batch_number }}</span>
                                    @endif
                                    @if($stock->expired_date)
                                        @php
                                            $expDate = \Carbon\Carbon::parse($stock->expired_date);
                                            $daysLeft = now()->diffInDays($expDate, false);
                                        @endphp
                                        <div class="v3-exp {{ $expDate->isPast() ? 'danger' : ($daysLeft <= 30 ? 'warn' : 'safe') }}">
                                            {{ $expDate->format('d M Y') }}
                                        </div>
                                        @if(!$expDate->isPast())
                                            <div class="v3-exp-days {{ $daysLeft <= 30 ? 'warn' : 'safe' }}">{{ $daysLeft }} hari lagi</div>
                                        @else
                                            <div class="v3-exp-days danger">{{ abs($daysLeft) }} hari lalu</div>
                                        @endif
                                    @else
                                        <span style="color:#94a3b8; font-size:0.75rem;">-</span>
                                    @endif
                                </td>
                                <td class="tc">
                                    <div class="v3-stock-cell">
                                        @if($maskStock)
                                            <span class="v3-stock-num mask">***</span>
                                        @elseif($isEmpty)
                                            <span class="v3-stock-num out">0</span>
                                        @elseif($isLow)
                                            <span class="v3-stock-num low">{{ $displayStock }}</span>
                                        @else
                                            <span class="v3-stock-num ok">{{ $displayStock }}</span>
                                        @endif

                                        @if(!$maskStock && !$isEmpty)
                                            @php $breakdown = $stock->product->breakdownStock($stock->stock); @endphp
                                            @if($breakdown !== number_format($stock->stock) . ' ' . ($stock->product->base_unit_name ?? ''))
                                                <span class="v3-stock-brk">{{ $breakdown }}</span>
                                            @else
                                                <span class="v3-stock-unit">{{ $stock->product->base_unit_name ?? '' }}</span>
                                            @endif
                                        @elseif(!$maskStock && $isEmpty)
                                            <span class="v3-stock-unit">{{ $stock->product->base_unit_name ?? '' }}</span>
                                        @endif

                                        @if($isLow && !$maskStock)
                                            <span class="v3-stock-min">Min: {{ number_format($minStk) }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="v3-empty">
                                        <div class="v3-empty-ico">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                        </div>
                                        <h6>Data Stok Kosong</h6>
                                        <p>Tidak ada stok barang yang sesuai dengan kriteria pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stocks->hasPages())
                <div class="v3-pag">
                    {{ $stocks->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const filterForm = document.getElementById('filter-form');
            const warehouseSelect = document.querySelector('select[name="warehouse_id"]');
            const categorySelect = document.querySelector('select[name="category_id"]');

            if (searchInput) {
                let timer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => { filterForm.submit(); }, 400);
                });
            }
            if (warehouseSelect) warehouseSelect.addEventListener('change', () => { filterForm.submit(); });
            if (categorySelect) categorySelect.addEventListener('change', () => { filterForm.submit(); });

            filterForm.addEventListener('submit', () => {
                const btn = filterForm.querySelector('button[type="submit"]');
                if (btn) { btn.innerHTML = 'Memproses...'; btn.disabled = true; }
            });
        });
    </script>
    @endpush
</x-app-layout>
