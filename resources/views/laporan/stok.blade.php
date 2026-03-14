<x-app-layout>
    <x-slot name="header">Laporan Stok</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Laporan & Analitik</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        </div>
                        Laporan Stok Global
                    </h1>
                    <p class="tr-subtitle">Ringkasan kondisi dan sebaran stok seluruh produk di semua gudang.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.stok') }}" class="tr-btn tr-btn-outline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        Rekap per Gudang
                    </a>
                    <a href="{{ route('gudang.minstok') }}" class="tr-btn tr-btn-warning-outline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        Min. Stok
                    </a>
                    <a href="{{ route('gudang.expired') }}" class="tr-btn tr-btn-danger-outline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Expired
                    </a>
                </div>
            </div>

            {{-- ─── TABBED NAVIGATION ─── --}}
            <div class="tr-tabs">
                <a href="{{ route('products.index') }}" class="tr-tab-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    Stok Barang Induk (Master)
                </a>
                <a href="{{ route('gudang.stok-semua') }}" class="tr-tab-item active-indigo">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg>
                    Sebaran Semua Gudang
                </a>
            </div>

            {{-- ─── KPI SUMMARY CARDS ─── --}}
            <div class="tr-kpi-grid">
                <div class="tr-kpi-card border-indigo">
                    <div class="tr-kpi-icon bg-indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-value text-indigo">{{ number_format($totalProducts) }}</div>
                        <div class="tr-kpi-label">Total Produk</div>
                    </div>
                </div>
                <div class="tr-kpi-card border-success">
                    <div class="tr-kpi-icon bg-success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-value text-success">{{ number_format($totalStockQty) }}</div>
                        <div class="tr-kpi-label">Total Qty Fisik</div>
                    </div>
                </div>
                <div class="tr-kpi-card border-warning">
                    <div class="tr-kpi-icon bg-warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-value text-warning">{{ $lowStockCount }}</div>
                        <div class="tr-kpi-label">Hampir Habis</div>
                    </div>
                </div>
                <div class="tr-kpi-card border-danger">
                    <div class="tr-kpi-icon bg-danger">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-value text-danger">{{ $expiredCount }}</div>
                        <div class="tr-kpi-label">Batch Kadaluarsa</div>
                    </div>
                </div>
                <div class="tr-kpi-card border-orange">
                    <div class="tr-kpi-icon bg-orange">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div>
                        <div class="tr-kpi-value text-orange">{{ $nearExpiredCount }}</div>
                        <div class="tr-kpi-label">Akan Exp. (30 Hr)</div>
                    </div>
                </div>
            </div>

            {{-- ─── MAIN LAYOUT (2 COLUMNS) ─── --}}
            <div class="tr-layout-grid">
                
                {{-- KOLOM KIRI: TABEL UTAMA --}}
                <div class="tr-col-main">
                    <div class="tr-card">
                        
                        {{-- Filter Bar --}}
                        <div class="tr-card-header tr-filter-bar">
                            <form method="GET" class="tr-filter-form">
                                <div class="tr-form-group tr-flex-1">
                                    <label class="tr-label">Cari Produk</label>
                                    <div class="tr-search">
                                        <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                        <input type="text" name="search" value="{{ $search }}" placeholder="Ketik Nama / SKU...">
                                    </div>
                                </div>
                                <div class="tr-form-group">
                                    <label class="tr-label">Kategori</label>
                                    <div class="tr-select-wrapper">
                                        <select name="category_id" class="tr-select">
                                            <option value="">Semua Kategori</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="tr-form-group">
                                    <label class="tr-label">Filter Gudang</label>
                                    <div class="tr-select-wrapper">
                                        <select name="warehouse_id" class="tr-select">
                                            <option value="">Semua Gudang</option>
                                            @foreach($warehouses as $wh)
                                                <option value="{{ $wh->id }}" @selected($warehouseId == $wh->id)>{{ $wh->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="tr-filter-actions">
                                    <button type="submit" class="tr-btn tr-btn-dark">Filter</button>
                                    @if($search || $categoryId || $warehouseId)
                                        <a href="{{ route('laporan.stok') }}" class="tr-btn tr-btn-outline">Reset</a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        {{-- Main Table --}}
                        <div class="table-responsive">
                            <table class="tr-table">
                                <thead>
                                    <tr>
                                        <th>Detail Produk</th>
                                        <th>Sebaran Gudang</th>
                                        <th class="c">Total Global</th>
                                        <th class="c">Batas Min.</th>
                                        <th class="c">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $p)
                                        @php
                                            $isLow = $p->min_stock > 0 && $p->stock <= $p->min_stock;
                                            $perGudang = $warehouseStocks->get($p->id, collect());
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="tr-prod-name">{{ $p->name }}</div>
                                                <div class="tr-prod-sku">SKU: {{ $p->sku }}</div>
                                                <div class="tr-prod-cat">{{ $p->category?->name ?? 'Tanpa Kategori' }}</div>
                                            </td>
                                            
                                            <td style="min-width: 240px;">
                                                @if($perGudang->isEmpty())
                                                    <span class="tr-text-muted tr-italic">Tidak ada stok tercatat</span>
                                                @else
                                                    <div class="tr-breakdown-list">
                                                        @foreach($perGudang as $wh)
                                                            <div class="tr-breakdown-item">
                                                                <span class="wh-name">
                                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                                                    {{ $wh->warehouse->name ?? '-' }}
                                                                </span>
                                                                <span class="wh-qty">
                                                                    {{ number_format($wh->total_stock) }} 
                                                                    <span class="wh-unit">{{ $p->unit?->abbreviation ?? '' }}</span>
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            
                                            <td class="c">
                                                <div class="tr-stock-total {{ $isLow ? 'text-warning' : 'text-success' }}">
                                                    {{ number_format($p->stock) }}
                                                </div>
                                                <div class="tr-unit-text">{{ $p->unit?->abbreviation ?? '' }}</div>
                                            </td>
                                            
                                            <td class="c tr-font-semibold tr-text-muted">{{ $p->min_stock ?? '-' }}</td>
                                            
                                            <td class="c">
                                                @if($p->stock == 0)
                                                    <span class="tr-badge tr-badge-danger">HABIS</span>
                                                @elseif($isLow)
                                                    <span class="tr-badge tr-badge-warning">MENIPIS</span>
                                                @else
                                                    <span class="tr-badge tr-badge-success">AMAN</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="tr-empty-state">
                                                    <div class="tr-empty-icon">
                                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="6" y1="8" x2="6.01" y2="8"></line><line x1="10" y1="8" x2="10.01" y2="8"></line></svg>
                                                    </div>
                                                    <h6>Tidak Ada Produk Ditemukan</h6>
                                                    <p>Coba sesuaikan filter pencarian, kategori, atau gudang di atas.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($products->hasPages())
                            <div class="tr-pagination">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KOLOM KANAN: WIDGET SIDEBAR --}}
                <div class="tr-col-side">
                    
                    {{-- Widget: Stok Per Gudang --}}
                    <div class="tr-widget-card">
                        <div class="tr-widget-header">
                            <h3 class="tr-widget-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                Total Stok Gudang
                            </h3>
                        </div>
                        <div class="tr-widget-body">
                            @forelse ($warehouses as $wh)
                                <div class="tr-widget-item">
                                    <div class="item-left">
                                        <div class="item-title">{{ $wh->name }}</div>
                                        <div class="item-desc">{{ $wh->stock_lines ?? 0 }} record tersimpan</div>
                                    </div>
                                    <div class="item-right text-indigo">
                                        {{ number_format($wh->total_qty ?? 0) }}
                                    </div>
                                </div>
                            @empty
                                <div class="tr-empty-compact">Belum ada data gudang.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Widget: Low Stock Alert --}}
                    @if($lowStockProducts->count())
                        <div class="tr-widget-card border-warning">
                            <div class="tr-widget-header bg-warning-soft">
                                <h3 class="tr-widget-title text-warning">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                    Stok Menipis (Alert)
                                </h3>
                            </div>
                            <div class="tr-widget-body">
                                @foreach($lowStockProducts as $p)
                                    <div class="tr-widget-item">
                                        <div class="item-left">
                                            <div class="item-title">{{ $p->name }}</div>
                                            <div class="item-desc">Min limit: {{ $p->min_stock }}</div>
                                        </div>
                                        <div class="item-right">
                                            <span class="tr-badge tr-badge-warning">{{ $p->stock }} Sisa</span>
                                        </div>
                                    </div>
                                @endforeach
                                <a href="{{ route('gudang.minstok') }}" class="tr-widget-link text-warning">Lihat Semua Alert &rarr;</a>
                            </div>
                        </div>
                    @endif

                    {{-- Widget: Recent Movements --}}
                    <div class="tr-widget-card">
                        <div class="tr-widget-header">
                            <h3 class="tr-widget-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                Aktivitas Terbaru
                            </h3>
                        </div>
                        <div class="tr-widget-body">
                            @forelse($recentMovements as $m)
                                <div class="tr-widget-item align-start">
                                    <div class="item-left">
                                        <div class="item-title tr-truncate" style="max-width: 180px;" title="{{ $m->product?->name ?? '-' }}">
                                            {{ $m->product?->name ?? '-' }}
                                        </div>
                                        <div class="item-desc">{{ $m->warehouse?->name }} • {{ $m->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="item-right">
                                        @if($m->type === 'in')
                                            <span class="tr-val-plus text-success">+{{ (int) $m->quantity }}</span>
                                        @else
                                            <span class="tr-val-minus text-danger">−{{ (int) $m->quantity }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="tr-empty-compact">Belum ada pergerakan.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            
            --tr-indigo: #4f46e5; /* Primary */
            --tr-indigo-hover: #4338ca;
            --tr-indigo-light: #e0e7ff;
            
            --tr-success: #10b981;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            
            --tr-warning: #f59e0b;
            --tr-warning-bg: #fef3c7;
            --tr-warning-text: #b45309;
            
            --tr-danger: #ef4444;
            --tr-danger-bg: #fee2e2;
            --tr-danger-text: #991b1b;
            
            --tr-orange: #f97316;
            --tr-orange-bg: #ffedd5;
            --tr-orange-text: #c2410c;

            --tr-radius-lg: 14px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.02);
            --tr-shadow-card: 0 4px 12px -2px rgba(0, 0, 0, 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1400px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-indigo); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.6rem; font-weight: 900; color: var(--tr-text-main); margin: 0 0 0.4rem 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; line-height: 1.4; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* ── TABS ── */
        .tr-tabs { display: flex; gap: 2rem; border-bottom: 1px solid var(--tr-border); margin-bottom: 1.5rem; overflow-x: auto; white-space: nowrap; }
        .tr-tab-item { display: inline-flex; align-items: center; gap: 8px; padding-bottom: 0.75rem; color: var(--tr-text-muted); font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tr-tab-item:hover { color: var(--tr-text-main); }
        .tr-tab-item.active-indigo { color: var(--tr-indigo); border-bottom-color: var(--tr-indigo); }

        /* ── KPI GRID ── */
        .tr-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .tr-kpi-card { background: var(--tr-surface); border-radius: var(--tr-radius-md); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); border-left-width: 4px; }
        .tr-kpi-card.border-indigo { border-left-color: var(--tr-indigo); }
        .tr-kpi-card.border-success { border-left-color: var(--tr-success); }
        .tr-kpi-card.border-warning { border-left-color: var(--tr-warning); }
        .tr-kpi-card.border-danger { border-left-color: var(--tr-danger); }
        .tr-kpi-card.border-orange { border-left-color: var(--tr-orange); }
        
        .tr-kpi-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .bg-success { background: var(--tr-success-bg); color: var(--tr-success); }
        .bg-warning { background: var(--tr-warning-bg); color: var(--tr-warning); }
        .bg-danger { background: var(--tr-danger-bg); color: var(--tr-danger); }
        .bg-orange { background: var(--tr-orange-bg); color: var(--tr-orange); }
        
        .tr-kpi-value { font-size: 1.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 2px; color: var(--tr-text-main); }
        .tr-kpi-label { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.02em; line-height: 1.3; }
        
        .text-indigo { color: var(--tr-indigo); }
        .text-success { color: var(--tr-success); }
        .text-warning { color: var(--tr-warning); }
        .text-danger { color: var(--tr-danger); }
        .text-orange { color: var(--tr-orange); }

        /* ── LAYOUT GRID (KIRI & KANAN) ── */
        .tr-layout-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: flex-start; }
        .tr-col-main { min-width: 0; } /* Prevents table blowout */
        .tr-col-side { display: flex; flex-direction: column; gap: 1.5rem; position: sticky; top: 1.5rem; }

        /* ── MAIN CARD & FILTERS ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-card); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        
        .tr-filter-form { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-flex-1 { flex: 1; min-width: 220px; }
        .tr-label { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 0.85rem; border: 1px solid var(--tr-border); transition: border-color 0.2s; height: 38px; }
        .tr-search:focus-within { border-color: var(--tr-indigo); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); flex-shrink: 0; }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        
        .tr-select-wrapper { position: relative; }
        .tr-select { padding: 0.5rem 0.85rem; padding-right: 2rem; border: 1px solid var(--tr-border); border-radius: 6px; font-family: inherit; font-size: 0.85rem; color: var(--tr-text-main); background: var(--tr-bg); appearance: none; outline: none; transition: border-color 0.2s; cursor: pointer; height: 38px; min-width: 160px; }
        .tr-select:focus { border-color: var(--tr-indigo); background: #ffffff; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        
        .tr-filter-actions { display: flex; gap: 6px; height: 38px; align-items: center; }

        /* BUTTONS */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 38px; }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); background: #f8fafc; }
        
        .tr-btn-warning-outline { border-color: var(--tr-warning-border); color: var(--tr-warning-text); background: var(--tr-warning-bg); }
        .tr-btn-warning-outline:hover { background: #fde68a; }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: var(--tr-danger-bg); }
        .tr-btn-danger-outline:hover { background: #fecaca; }

        /* ── MAIN TABLE ── */
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.5rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1.25rem 1.5rem; font-size: 0.85rem; vertical-align: top; }
        .tr-table tbody tr:last-child { border-bottom: none; }
        
        .tr-table th.c, .tr-table td.c { text-align: center; }

        /* Cells */
        .tr-prod-name { font-weight: 800; color: var(--tr-text-main); font-size: 0.9rem; line-height: 1.3; }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-prod-cat { font-size: 0.75rem; font-weight: 600; color: var(--tr-indigo); background: var(--tr-indigo-light); display: inline-block; padding: 2px 6px; border-radius: 4px; margin-top: 6px; }
        .tr-font-mono { font-family: monospace; font-weight: 600; color: var(--tr-text-main); }
        
        /* Breakdown Gudang List */
        .tr-breakdown-list { display: flex; flex-direction: column; gap: 6px; }
        .tr-breakdown-item { display: flex; justify-content: space-between; align-items: center; padding-bottom: 6px; border-bottom: 1px dashed var(--tr-border-light); }
        .tr-breakdown-item:last-child { border-bottom: none; padding-bottom: 0; }
        .wh-name { font-size: 0.75rem; font-weight: 600; color: var(--tr-text-muted); display: flex; align-items: center; gap: 4px; }
        .wh-name svg { color: var(--tr-text-light); }
        .wh-qty { font-size: 0.85rem; font-weight: 800; color: var(--tr-indigo); }
        .wh-unit { font-size: 0.7rem; font-weight: 500; color: var(--tr-text-light); }
        .tr-italic { font-style: italic; }

        .tr-stock-total { font-size: 1.25rem; font-weight: 900; line-height: 1; }
        .tr-unit-text { font-size: 0.75rem; font-weight: 600; color: var(--tr-text-light); margin-top: 4px; }
        .tr-font-semibold { font-weight: 600; }

        .tr-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.35rem 0.6rem; border-radius: 6px; font-weight: 800; font-size: 0.7rem; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); border: 1px solid #bbf7d0; }
        .tr-badge-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); border: 1px solid var(--tr-warning-border); }
        .tr-badge-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border: 1px solid #fecaca; }

        /* ── SIDE WIDGETS ── */
        .tr-widget-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-widget-card.border-warning { border: 1px solid var(--tr-warning-border); }
        
        .tr-widget-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-widget-header.bg-warning-soft { background: var(--tr-warning-bg); border-bottom-color: var(--tr-warning-border); }
        .tr-widget-title { font-size: 0.9rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 8px; }
        
        .tr-widget-body { display: flex; flex-direction: column; }
        .tr-widget-item { display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); }
        .tr-widget-item.align-start { align-items: flex-start; }
        .tr-widget-item:last-child { border-bottom: none; }
        
        .item-title { font-size: 0.85rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 2px; }
        .item-desc { font-size: 0.75rem; color: var(--tr-text-muted); }
        .item-right { font-weight: 800; font-size: 0.9rem; }
        
        .tr-widget-link { display: block; text-align: center; padding: 0.85rem; font-size: 0.8rem; font-weight: 700; text-decoration: none; border-top: 1px solid var(--tr-warning-border); background: var(--tr-warning-bg); transition: background 0.2s; }
        .tr-widget-link:hover { filter: brightness(0.95); }
        
        .tr-truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .tr-val-plus { font-family: monospace; font-weight: 800; }
        .tr-val-minus { font-family: monospace; font-weight: 800; }

        /* ── EMPTY STATES & PAGINATION ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 48px; height: 48px; border-radius: 50%; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--tr-text-light); }
        .tr-empty-state h6 { font-size: 1rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 300px; line-height: 1.4; }
        
        .tr-empty-compact { padding: 1.5rem; text-align: center; font-size: 0.8rem; color: var(--tr-text-light); font-style: italic; }

        .tr-pagination { padding: 1rem 1.5rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .tr-layout-grid { grid-template-columns: 1fr; }
            .tr-col-side { position: static; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
        }
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-header-actions .tr-btn { flex: 1; justify-content: center; }
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-filter-actions { flex-direction: row; }
            .tr-filter-actions .tr-btn { flex: 1; }
        }
    </style>
    @endpush
</x-app-layout>