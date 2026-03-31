<x-app-layout>
    <x-slot name="header">Rekap Stok Barang</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Gudang</div>
                    <h1 class="tr-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="tr-title-icon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        Rekap Stok Barang
                    </h1>
                    <p class="tr-subtitle">Detail penyebaran stok produk di seluruh gudang dan lokasi rak.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.stok.export', request()->only(['warehouse_id', 'search'])) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                    <a href="{{ route('gudang.penerimaan.create') }}" class="tr-btn tr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Terima Barang
                    </a>
                </div>
            </div>

            {{-- SUMMARY STATS --}}
            <div class="tr-stats-grid">
                <div class="tr-stat-card border-blue">
                    <div class="tr-stat-icon bg-blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $totalItems ?? $stocks->total() }}</div>
                        <div class="tr-stat-label">Total Record Stok</div>
                    </div>
                </div>
                <div class="tr-stat-card border-green">
                    <div class="tr-stat-icon bg-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $warehouses->count() }}</div>
                        <div class="tr-stat-label">Gudang Aktif</div>
                    </div>
                </div>
                <div class="tr-stat-card border-orange">
                    <div class="tr-stat-icon bg-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $stocks->pluck('location_id')->filter()->unique()->count() }}</div>
                        <div class="tr-stat-label">Lokasi / Rak Aktif</div>
                    </div>
                </div>
                <div class="tr-stat-card border-red">
                    <div class="tr-stat-icon bg-red">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $stocks->filter(fn($s) => $s->stock <= ($s->product->min_stock ?? 0) && $s->stock > 0)->count() }}</div>
                        <div class="tr-stat-label">Stok Hampir Habis</div>
                    </div>
                </div>
            </div>

            {{-- MAIN CARD (FILTER & TABLE) --}}
            <div class="tr-card">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.stok') }}" class="tr-filter-form">
                        <select name="warehouse_id" class="tr-input tr-select">
                            <option value="">Semua Gudang</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $warehouseId == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang / SKU...">
                        </div>
                        
                        <button type="submit" class="tr-btn tr-btn-primary">Filter</button>
                        
                        @if(request('search') || request('warehouse_id'))
                            <a href="{{ route('gudang.stok') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Barang / SKU</th>
                                <th>Kategori</th>
                                <th>Gudang & Lokasi</th>
                                <th>Batch / Expired</th>
                                <th class="r">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stocks as $stock)
                                @php
                                    $minStk = $stock->product->min_stock ?? 0;
                                    $isLow = $stock->stock > 0 && $stock->stock <= $minStk;
                                    $isEmpty = $stock->stock == 0;
                                @endphp
                                <tr class="{{ $isEmpty ? 'tr-row-empty' : '' }}">
                                    <td>
                                        <div class="tr-prod-name">{{ $stock->product->name }}</div>
                                        <div class="tr-prod-sku">SKU: {{ $stock->product->sku }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-category">{{ $stock->product->category->name ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="tr-wh-pill">{{ $stock->warehouse->name }}</span>
                                        @if($stock->location)
                                            <div class="tr-loc-text">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                                Rak: {{ $stock->location->name }}
                                            </div>
                                        @else
                                            <div class="tr-loc-text text-muted">Area Umum</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($stock->batch_number)
                                            <div class="tr-batch-text">{{ $stock->batch_number }}</div>
                                        @endif
                                        
                                        @if($stock->expired_date)
                                            @php 
                                                $expDate = \Carbon\Carbon::parse($stock->expired_date); 
                                                $isExpired = $expDate->isPast(); 
                                            @endphp
                                            <div class="tr-exp-text {{ $isExpired ? 'tr-text-danger' : 'tr-text-warning' }}">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                {{ $isExpired ? 'Kadaluarsa' : $expDate->format('d M Y') }}
                                            </div>
                                        @else
                                            <span class="tr-text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="r">
                                        @if($isEmpty)
                                            <span class="tr-stock-badge empty">0</span>
                                        @elseif($isLow)
                                            <span class="tr-stock-badge low">{{ $stock->stock }}</span>
                                            <div class="tr-stock-min">Min: {{ $minStk }}</div>
                                        @else
                                            <span class="tr-stock-badge ok">{{ $stock->stock }}</span>
                                        @endif
                                        <div class="tr-unit">{{ $stock->product->unit->abbreviation ?? '' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tr-empty">
                                            <div class="tr-empty-icon">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
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
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #3b82f6;
            --tr-primary-light: #eff6ff;
            --tr-success-bg: #dcfce7;
            --tr-success-text: #166534;
            --tr-warning-bg: #fef3c7;
            --tr-warning-text: #92400e;
            --tr-danger-bg: #fee2e2;
            --tr-danger-text: #991b1b;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; }
        .tr-page {
            padding: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--tr-text-main);
        }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-primary); margin-bottom: 0.25rem; }
        .tr-title { font-size: 1.4rem; font-weight: 800; color: var(--tr-text-main); letter-spacing: -0.02em; margin: 0; display: flex; align-items: center; gap: 8px; }
        .tr-title-icon { color: var(--tr-primary); background: var(--tr-primary-light); padding: 4px; border-radius: 6px; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.25rem 0 0; font-weight: 500; }
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── STATS GRID ── */
        .tr-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .tr-stat-card {
            background: var(--tr-surface);
            padding: 1rem 1.25rem;
            border-radius: var(--tr-radius-md);
            border: 1px solid var(--tr-border);
            display: flex; align-items: center; gap: 1rem;
            box-shadow: var(--tr-shadow-sm);
            border-left-width: 4px;
        }
        .tr-stat-card.border-blue { border-left-color: var(--tr-primary); }
        .tr-stat-card.border-green { border-left-color: #10b981; }
        .tr-stat-card.border-orange { border-left-color: #f59e0b; }
        .tr-stat-card.border-red { border-left-color: #ef4444; }
        
        .tr-stat-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tr-stat-icon.bg-blue { background: #eff6ff; color: var(--tr-primary); }
        .tr-stat-icon.bg-green { background: #ecfdf5; color: #10b981; }
        .tr-stat-icon.bg-orange { background: #fffbeb; color: #f59e0b; }
        .tr-stat-icon.bg-red { background: #fef2f2; color: #ef4444; }
        
        .tr-stat-value { font-size: 1.35rem; font-weight: 800; line-height: 1.1; color: var(--tr-text-main); }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); font-weight: 500; margin-top: 2px; }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-card-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); }
        .tr-filter-form { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }
        
        .tr-input {
            padding: 0.4rem 0.8rem;
            border: 1px solid var(--tr-border);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.8rem;
            color: var(--tr-text-main);
            outline: none;
            background: #f8fafc;
            transition: border-color 0.2s;
        }
        .tr-input:focus { border-color: var(--tr-primary); background: #ffffff; }
        .tr-select { min-width: 180px; font-weight: 500; cursor: pointer; }

        .tr-search { display: flex; align-items: center; gap: 6px; background: var(--tr-bg); border-radius: 6px; padding: 0.4rem 0.8rem; border: 1px solid var(--tr-border); flex: 1; min-width: 200px; transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-primary); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); }
        .tr-search input { border: none; background: transparent; font-size: 0.8rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        .tr-search input::placeholder { color: var(--tr-text-light); }

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 4px;
            padding: 0.45rem 0.85rem; border-radius: 6px; font-size: 0.8rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s;
        }
        .tr-btn-primary { background: var(--tr-text-main); color: #ffffff; border: 1px solid var(--tr-text-main); }
        .tr-btn-primary:hover { background: #000000; }
        .tr-btn-outline { background: transparent; border: 1px solid var(--tr-border); color: var(--tr-text-muted); }
        .tr-btn-outline:hover { border-color: var(--tr-text-main); color: var(--tr-text-main); }
        .tr-btn-danger-outline { background: transparent; border: 1px solid #fca5a5; color: #ef4444; }
        .tr-btn-danger-outline:hover { background: #fef2f2; }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 700px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease, opacity 0.2s; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody tr.tr-row-empty { opacity: 0.6; } /* Faded out if stock is 0 */
        .tr-table tbody td { padding: 0.85rem 1.25rem; font-size: 0.8rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELLS ── */
        .tr-prod-name { font-weight: 700; font-size: 0.85rem; color: var(--tr-text-main); line-height: 1.3; }
        .tr-prod-sku  { font-size: 0.7rem; color: var(--tr-text-muted); font-family: monospace; margin-top: 2px; }
        .tr-category { font-weight: 500; color: var(--tr-text-muted); }
        
        .tr-wh-pill { background: var(--tr-primary-light); color: var(--tr-primary); font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 4px; display: inline-block; }
        .tr-loc-text { font-size: 0.7rem; color: var(--tr-text-muted); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
        .tr-loc-text.text-muted { color: var(--tr-text-light); }
        
        .tr-batch-text { font-family: monospace; font-size: 0.75rem; font-weight: 600; color: var(--tr-text-main); }
        .tr-exp-text { font-size: 0.7rem; font-weight: 600; display: flex; align-items: center; gap: 4px; margin-top: 2px; }
        .tr-text-danger { color: #ef4444; }
        .tr-text-warning { color: #d97706; }
        .tr-text-muted { color: var(--tr-text-light); }

        .tr-stock-badge { padding: 0.2rem 0.6rem; border-radius: 99px; font-weight: 800; font-size: 0.95rem; display: inline-block; }
        .tr-stock-badge.empty { background: var(--tr-border-light); color: var(--tr-text-muted); }
        .tr-stock-badge.low { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        .tr-stock-badge.ok { background: var(--tr-success-bg); color: var(--tr-success-text); }
        
        .tr-stock-min { font-size: 0.65rem; color: var(--tr-warning-text); font-weight: 600; margin-top: 2px; }
        .tr-unit { font-size: 0.7rem; color: var(--tr-text-light); font-weight: 500; margin-top: 2px; }

        /* ── EMPTY STATE ── */
        .tr-empty { text-align: center; padding: 3rem 1.5rem; }
        .tr-empty-icon { width: 48px; height: 48px; border-radius: 8px; background: var(--tr-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--tr-text-light); }
        .tr-empty h6 { font-size: 0.95rem; font-weight: 600; color: var(--tr-text-main); margin-bottom: 0.25rem; }
        .tr-empty p { font-size: 0.8rem; color: var(--tr-text-muted); margin: 0 auto; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; }
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-search, .tr-select { width: 100%; min-width: auto; }
        }
    </style>
    @endpush
</x-app-layout>