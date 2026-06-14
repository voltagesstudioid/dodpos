<x-app-layout>
    <x-slot name="header">Rekap Stok Barang</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        Rekap Stok Barang
                    </h1>
                    <p class="tr-subtitle">Detail penyebaran stok produk di seluruh gudang dan lokasi rak</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.stok.export', request()->only(['warehouse_id','category_id','search'])) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                </div>
            </div>

            {{-- STATS CARDS --}}
            <div class="tr-stats-grid-4">
                <div class="tr-stat-card border-blue">
                    <div class="tr-stat-icon bg-blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6v6H9z"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalRecords ?? 0) }}</div>
                        <div class="tr-stat-label">Total Record Stok</div>
                    </div>
                </div>
                <div class="tr-stat-card border-purple">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ ($hideFinancial ?? false) ? '***' : 'Rp ' . number_format($totalStockValue ?? 0, 0, ',', '.') }}</div>
                        <div class="tr-stat-label">Total Nilai Stok</div>
                    </div>
                </div>
                <div class="tr-stat-card border-green">
                    <div class="tr-stat-icon bg-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $activeWarehouses ?? 0 }}</div>
                        <div class="tr-stat-label">Gudang Aktif</div>
                    </div>
                </div>
                <div class="tr-stat-card border-orange">
                    <div class="tr-stat-icon bg-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $lowStockCount ?? 0 }}</div>
                        <div class="tr-stat-label">Stok Hampir Habis</div>
                    </div>
                </div>
            </div>

            @if($maskStock)
                <div class="tr-alert tr-alert-warning" style="margin-top: 1.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Stok disembunyikan karena ada proses opname aktif.
                </div>
            @endif

            {{-- MAIN CARD --}}
            <div class="tr-card" style="margin-top: 1.5rem;">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.stok') }}" class="tr-filter-form" id="filter-form">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang atau SKU..." id="search-input">
                        </div>
                        
                        <select name="warehouse_id" class="tr-select">
                            <option value="">Semua Gudang</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $warehouseId == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        
                        <select name="category_id" class="tr-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        
                        <button type="submit" class="tr-btn tr-btn-dark">Filter</button>
                        
                        @if(request('search') || request('warehouse_id') || request('category_id'))
                            <a href="{{ route('gudang.stok') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                @php
                                    $sortUrl = function($col) use ($sort, $dir) {
                                        $newDir = ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                                        return route('gudang.stok', array_merge(request()->except(['sort','dir','page']), ['sort' => $col, 'dir' => $newDir]));
                                    };
                                    $arrow = function($col) use ($sort, $dir) {
                                        if ($sort !== $col) return '<span class="sort-arrow" style="opacity:0.3">&#8693;</span>';
                                        return $dir === 'asc' ? '<span class="sort-arrow text-primary">&#8593;</span>' : '<span class="sort-arrow text-primary">&#8595;</span>';
                                    };
                                @endphp
                                <th style="cursor:pointer" onclick="location.href='{{ $sortUrl('product') }}'">Barang / SKU {!! $arrow('product') !!}</th>
                                <th style="cursor:pointer" onclick="location.href='{{ $sortUrl('category') }}'">Kategori {!! $arrow('category') !!}</th>
                                <th style="cursor:pointer" onclick="location.href='{{ $sortUrl('warehouse') }}'">Gudang & Lokasi {!! $arrow('warehouse') !!}</th>
                                <th>Batch / Expired</th>
                                <th class="c" style="cursor:pointer" onclick="location.href='{{ $sortUrl('stock') }}'">Sisa Stok {!! $arrow('stock') !!}</th>
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
                                <tr @if($isLow) style="background: #fffbeb;" @elseif($isEmpty) style="background: #fef2f2;" @endif>
                                    <td>
                                        <div class="tr-prod-name">{{ $stock->product->name ?? '-' }}</div>
                                        <div class="tr-prod-sku">SKU: {{ $stock->product->sku ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="tr-badge tr-badge-gray">{{ $stock->product->category->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-wh-name">{{ $stock->warehouse->name ?? '-' }}</div>
                                        @if($stock->location)
                                            <div class="tr-date-sub">Rak: {{ $stock->location->name }}</div>
                                        @else
                                            <div class="tr-date-sub">Area Umum</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($stock->batch_number)
                                            <div class="tr-ref-badge" style="display:inline-block; margin-bottom:4px;">{{ $stock->batch_number }}</div><br>
                                        @endif
                                        @if($stock->expired_date)
                                            @php
                                                $expDate = \Carbon\Carbon::parse($stock->expired_date);
                                                $daysLeft = now()->diffInDays($expDate, false);
                                            @endphp
                                            <div class="tr-date-main @if($expDate->isPast()) text-danger @elseif($daysLeft <= 30) text-warning @else text-success @endif" style="font-size:0.8rem">
                                                {{ $expDate->format('d M Y') }}
                                            </div>
                                        @else
                                            <span class="tr-date-sub">-</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        @if($maskStock)
                                            <span class="tr-qty-in-badge text-muted">***</span>
                                        @elseif($isEmpty)
                                            <span class="tr-qty-in-badge text-danger" style="color: #ef4444">0</span>
                                        @elseif($isLow)
                                            <span class="tr-qty-in-badge text-warning" style="color: #f59e0b">{{ $displayStock }}</span>
                                            @php $breakdown = $stock->product->breakdownStock($stock->stock); @endphp
                                            @if($breakdown !== number_format($stock->stock) . ' ' . ($stock->product->base_unit_name ?? ''))
                                                <div class="tr-date-sub" style="font-size:0.65rem; color:#6366f1; font-weight:600; margin-top:2px;">{{ $breakdown }}</div>
                                            @endif
                                            <div class="tr-date-sub text-warning" style="color: #f59e0b; font-weight:700">Min: {{ number_format($minStk) }}</div>
                                        @else
                                            <span class="tr-qty-in-badge">{{ $displayStock }}</span>
                                        @endif
                                        @if(!$maskStock && !$isEmpty)
                                            @php $breakdown = $stock->product->breakdownStock($stock->stock); @endphp
                                            @if($breakdown !== number_format($stock->stock) . ' ' . ($stock->product->base_unit_name ?? ''))
                                                <div class="tr-date-sub" style="font-size:0.65rem; color:#6366f1; font-weight:600; margin-top:2px;">{{ $breakdown }}</div>
                                            @else
                                                <div class="tr-date-sub">{{ $stock->product->base_unit_name ?? '' }}</div>
                                            @endif
                                        @elseif(!$maskStock && $isEmpty)
                                            <div class="tr-date-sub">{{ $stock->product->base_unit_name ?? '' }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
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
                    <div class="tr-pagination">
                        {{ $stocks->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-primary: #3b82f6;
            --tr-success: #10b981;
            --tr-warning: #f59e0b;
            --tr-danger: #ef4444;
            --tr-purple: #8b5cf6;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Header */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-success); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 10px; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 8px; border-radius: 10px; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; }
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* Stats Grid 4 */
        .tr-stats-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        @media (max-width: 992px) { .tr-stats-grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px) { .tr-stats-grid-4 { grid-template-columns: 1fr; } }

        .tr-stat-card { background: var(--tr-surface); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); }
        .tr-stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); line-height: 1; }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; }

        /* Colors */
        .bg-green { background: #10b981; }
        .bg-blue { background: #3b82f6; }
        .bg-purple { background: #8b5cf6; }
        .bg-orange { background: #f59e0b; }
        .border-green { border-left: 4px solid #10b981; }
        .border-blue { border-left: 4px solid #3b82f6; }
        .border-purple { border-left: 4px solid #8b5cf6; }
        .border-orange { border-left: 4px solid #f59e0b; }

        /* Card */
        .tr-card { background: var(--tr-surface); border-radius: 12px; border: 1px solid var(--tr-border); overflow: hidden; }
        .tr-card-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border); }
        .tr-filter-bar { background: #f8fafc; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .tr-search { display: flex; align-items: center; gap: 8px; background: white; border-radius: 8px; padding: 0.5rem 1rem; border: 1px solid var(--tr-border); flex: 1; min-width: 200px; }
        .tr-search input { border: none; background: transparent; font-size: 0.9rem; outline: none; width: 100%; }
        .tr-select { padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid var(--tr-border); font-size: 0.9rem; background: white; }

        /* Buttons */
        .tr-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .tr-btn-primary { background: var(--tr-primary); color: white; }
        .tr-btn-outline { border: 1px solid var(--tr-border); color: var(--tr-text-muted); background: white; }
        .tr-btn-dark { background: var(--tr-text-main); color: white; }
        .tr-btn-danger-outline { border: 1px solid #fecaca; color: #b91c1c; background: transparent; }

        /* Alerts */
        .tr-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 10px; }
        .tr-alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .tr-alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
        .tr-alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        /* Table */
        .tr-table { width: 100%; border-collapse: collapse; }
        .tr-table th, .tr-table td { padding: 0.875rem 1rem; text-align: left; font-size: 0.875rem; border-bottom: 1px solid var(--tr-border); }
        .tr-table th { font-weight: 600; color: var(--tr-text-muted); background: #f8fafc; }
        .tr-table .c { text-align: center; }
        .tr-date-main { font-weight: 600; color: var(--tr-text-main); }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); }
        .tr-ref-badge { font-family: monospace; font-size: 0.8rem; color: #4f46e5; background: #e0e7ff; padding: 0.25rem 0.5rem; border-radius: 6px; }
        .tr-prod-name { font-weight: 600; color: var(--tr-text-main); }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); }
        .tr-wh-name { color: var(--tr-text-main); }
        .tr-user-text { color: var(--tr-text-muted); font-size: 0.85rem; }
        .tr-qty-in-badge { font-weight: 800; color: #10b981; font-size: 1.1rem; }

        /* Text colors */
        .text-success { color: #10b981 !important; }
        .text-warning { color: #f59e0b !important; }
        .text-danger { color: #ef4444 !important; }
        .text-primary { color: #3b82f6 !important; }
        .text-muted { color: #64748b !important; }

        /* Badges */
        .tr-badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
        .tr-badge-warning { background: #fef3c7; color: #92400e; }
        .tr-badge-purple { background: #ede9fe; color: #5b21b6; }
        .tr-badge-danger { background: #fee2e2; color: #991b1b; }
        .tr-badge-blue { background: #dbeafe; color: #1e40af; }
        .tr-badge-success { background: #d1fae5; color: #065f46; }
        .tr-badge-gray { background: #f3f4f6; color: #374151; }

        /* Actions */
        .tr-actions-group { display: flex; gap: 0.25rem; justify-content: center; }
        .tr-action-btn { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; }
        .tr-action-btn.view { background: #f3f4f6; color: #374151; }
        .tr-action-btn.delete { background: #fee2e2; color: #991b1b; }

        /* Empty State */
        .tr-empty-state { text-align: center; padding: 3rem; color: var(--tr-text-muted); }
        .tr-empty-icon { margin-bottom: 1rem; opacity: 0.5; }

        /* Pagination */
        .tr-pagination { padding: 1rem 1.25rem; }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const filterForm = document.getElementById('filter-form');
            const warehouseSelect = document.querySelector('select[name="warehouse_id"]');
            const categorySelect = document.querySelector('select[name="category_id"]');
            
            // Real-time search dengan debounce
            if (searchInput) {
                let timer;
                searchInput.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        filterForm.submit();
                    }, 400);
                });
            }
            
            // Auto-submit saat select berubah
            if (warehouseSelect) {
                warehouseSelect.addEventListener('change', () => {
                    filterForm.submit();
                });
            }
            
            if (categorySelect) {
                categorySelect.addEventListener('change', () => {
                    filterForm.submit();
                });
            }
            
            // Tambahkan loading indicator
            filterForm.addEventListener('submit', () => {
                const submitBtn = filterForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = 'Memproses...';
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
