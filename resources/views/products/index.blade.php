<x-app-layout>
    <x-slot name="header">Data Produk</x-slot>
    <style>
        .pd-page{max-width:1400px;margin:0 auto;padding:1.5rem;}
        .pd-header{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .pd-header-left{display:flex;align-items:center;gap:0.875rem;}
        .pd-icon-box{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;flex-shrink:0;}
        .pd-title{font-size:1.375rem;font-weight:700;color:#1e293b;margin:0;line-height:1.3;}
        .pd-subtitle{font-size:0.8125rem;color:#64748b;margin:0.125rem 0 0;}
        .pd-actions{display:flex;gap:0.5rem;flex-wrap:wrap;}

        .pd-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:0.75rem;margin-bottom:1.5rem;}
        .pd-stat{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1rem 1.125rem;display:flex;align-items:center;gap:0.875rem;transition:box-shadow .2s;}
        .pd-stat:hover{box-shadow:0 2px 8px rgba(0,0,0,.06);}
        .pd-stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .pd-stat-icon.bg-indigo{background:#eef2ff;color:#6366f1;}
        .pd-stat-icon.bg-amber{background:#fffbeb;color:#d97706;}
        .pd-stat-icon.bg-rose{background:#fff1f2;color:#e11d48;}
        .pd-stat-icon.bg-emerald{background:#ecfdf5;color:#059669;}
        .pd-stat-value{font-size:1.375rem;font-weight:700;color:#1e293b;line-height:1.2;font-family:'JetBrains Mono',monospace;}
        .pd-stat-label{font-size:0.75rem;color:#94a3b8;margin-top:0.125rem;}

        .pd-tabs{display:flex;gap:1.5rem;border-bottom:1px solid #e2e8f0;margin-bottom:1.25rem;}
        .pd-tab{padding-bottom:0.75rem;font-size:0.875rem;font-weight:500;text-decoration:none;color:#64748b;border-bottom:2px solid transparent;transition:all .2s;}
        .pd-tab.active{color:#4f46e5;font-weight:600;border-bottom-color:#4f46e5;}
        .pd-tab:hover:not(.active){color:#334155;}

        .pd-filter{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1rem;}
        .pd-filter form{display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;width:100%;}
        .pd-filter input,.pd-filter select{height:38px;border:1px solid #e2e8f0;border-radius:8px;padding:0 0.75rem;font-size:0.8125rem;outline:none;transition:border-color .2s;}
        .pd-filter input:focus,.pd-filter select:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);}
        .pd-filter input{flex:1;min-width:200px;max-width:360px;}
        .pd-filter select{width:180px;}

        .pd-table-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;}
        .pd-table-header{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;border-bottom:1px solid #f1f5f9;}
        .pd-table-title{font-size:0.9375rem;font-weight:600;color:#1e293b;display:flex;align-items:center;gap:0.5rem;}
        .pd-table-meta{font-size:0.75rem;color:#94a3b8;}

        .pd-table{width:100%;border-collapse:collapse;}
        .pd-table thead th{padding:0.75rem 1rem;font-size:0.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#94a3b8;background:#f8fafc;border-bottom:1px solid #e2e8f0;white-space:nowrap;}
        .pd-table tbody tr{border-bottom:1px solid #f1f5f9;transition:background .15s;}
        .pd-table tbody tr:hover{background:#f8fafc;}
        .pd-table tbody tr:last-child{border-bottom:none;}
        .pd-table tbody td{padding:0.75rem 1rem;font-size:0.8125rem;color:#475569;vertical-align:middle;}
        .pd-table .pd-row-num{width:44px;font-size:0.6875rem;color:#cbd5e1;text-align:center;}

        .pd-product-name{font-weight:600;color:#1e293b;font-size:0.875rem;line-height:1.4;}
        .pd-sku-badge{display:inline-block;background:#eef2ff;color:#4f46e5;font-size:0.6875rem;font-weight:600;font-family:'JetBrains Mono',monospace;padding:0.125rem 0.5rem;border-radius:4px;letter-spacing:0.02em;}
        .pd-barcode{font-family:'JetBrains Mono',monospace;font-size:0.6875rem;color:#94a3b8;}
        .pd-category-badge{display:inline-flex;align-items:center;gap:0.25rem;background:#f1f5f9;color:#475569;font-size:0.75rem;font-weight:500;padding:0.25rem 0.625rem;border-radius:6px;}
        .pd-price{font-weight:700;color:#059669;font-family:'JetBrains Mono',monospace;font-size:0.8125rem;}
        .pd-stock-ok{display:inline-flex;align-items:center;gap:0.25rem;background:#ecfdf5;color:#059669;font-size:0.75rem;font-weight:600;padding:0.25rem 0.625rem;border-radius:6px;font-family:'JetBrains Mono',monospace;}
        .pd-stock-low{display:inline-flex;align-items:center;gap:0.25rem;background:#fff1f2;color:#e11d48;font-size:0.75rem;font-weight:600;padding:0.25rem 0.625rem;border-radius:6px;font-family:'JetBrains Mono',monospace;}
        .pd-stock-lock{display:inline-flex;align-items:center;gap:0.25rem;background:#fefce8;color:#ca8a04;font-size:0.75rem;font-weight:500;padding:0.25rem 0.625rem;border-radius:6px;}
        .pd-conv-dot{display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:6px;background:#eef2ff;color:#6366f1;font-size:0.6875rem;font-weight:700;font-family:'JetBrains Mono',monospace;}

        .pd-act{display:flex;gap:0.375rem;justify-content:center;}
        .pd-act-btn{display:inline-flex;align-items:center;gap:0.25rem;padding:0.375rem 0.75rem;border-radius:7px;font-size:0.75rem;font-weight:500;text-decoration:none;border:1px solid;transition:all .15s;cursor:pointer;}
        .pd-act-edit{background:#eef2ff;color:#4f46e5;border-color:#c7d2fe;}
        .pd-act-edit:hover{background:#e0e7ff;}
        .pd-act-del{background:#fff1f2;color:#e11d48;border-color:#fecdd3;}
        .pd-act-del:hover{background:#ffe4e6;}
        .pd-act-view{background:#ecfdf5;color:#059669;border-color:#a7f3d0;}
        .pd-act-view:hover{background:#d1fae5;}

        .pd-empty{text-align:center;padding:3rem 1rem;}
        .pd-empty-icon{font-size:2.5rem;margin-bottom:0.75rem;opacity:.5;}
        .pd-empty-title{font-size:0.9375rem;font-weight:600;color:#64748b;margin-bottom:0.375rem;}
        .pd-empty-desc{font-size:0.8125rem;color:#94a3b8;margin-bottom:1rem;}

        .pd-pagination{padding:1rem 1.25rem;border-top:1px solid #f1f5f9;}
    </style>

    <div class="pd-page">
        {{-- Header --}}
        <div class="pd-header">
            <div class="pd-header-left">
                <div class="pd-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <div>
                    <h1 class="pd-title">Data Produk</h1>
                    <p class="pd-subtitle">Kelola master data barang, harga &amp; multi-satuan</p>
                </div>
            </div>
            <div class="pd-actions">
                @can('create_master_produk')
                <a href="{{ route('products.import') }}" class="btn-secondary" style="display:inline-flex;align-items:center;gap:0.375rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Import CSV
                </a>
                <a href="{{ route('products.create') }}" class="btn-primary" style="display:inline-flex;align-items:center;gap:0.375rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Produk
                </a>
                @endcan
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="pd-stats">
            <div class="pd-stat">
                <div class="pd-stat-icon bg-indigo">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <div>
                    <div class="pd-stat-value">{{ number_format($stats['totalProducts']) }}</div>
                    <div class="pd-stat-label">Total Produk</div>
                </div>
            </div>
            <div class="pd-stat">
                <div class="pd-stat-icon bg-rose">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div>
                    <div class="pd-stat-value">{{ number_format($stats['lowStockCount']) }}</div>
                    <div class="pd-stat-label">Stok Rendah</div>
                </div>
            </div>
            <div class="pd-stat">
                <div class="pd-stat-icon bg-emerald">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div>
                    <div class="pd-stat-value">{{ number_format($stats['totalCategories']) }}</div>
                    <div class="pd-stat-label">Kategori</div>
                </div>
            </div>
            <div class="pd-stat">
                <div class="pd-stat-icon bg-amber">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                </div>
                <div>
                    <div class="pd-stat-value">{{ number_format($stats['totalUnits']) }}</div>
                    <div class="pd-stat-label">Satuan</div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="pd-tabs">
            <a href="{{ route('products.index') }}" class="pd-tab active">
                @can('view_stok_gudang') 📦 Stok Barang Induk @else 🏷️ Data Produk @endcan
            </a>
            @can('view_stok_gudang')
            <a href="{{ route('gudang.stok-semua') }}" class="pd-tab">📈 Sebaran Semua Gudang</a>
            @endcan
        </div>

        {{-- Filter --}}
        <div class="pd-filter">
            <form method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍  Cari nama, SKU, atau barcode...">
                <select name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('products.index') }}" class="btn-secondary btn-sm">× Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="pd-table-card">
            <div class="pd-table-header">
                <div class="pd-table-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Daftar Produk
                </div>
                <div class="pd-table-meta">{{ $products->total() }} produk ditemukan</div>
            </div>

            <div class="table-wrapper">
                <table class="pd-table">
                    <thead>
                        <tr>
                            <th class="pd-row-num">#</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th style="text-align:right;">Harga Jual</th>
                            <th style="text-align:center;">Stok</th>
                            <th style="text-align:center;width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $i => $item)
                        <tr>
                            <td class="pd-row-num">{{ $products->firstItem() + $i }}</td>
                            <td>
                                <div class="pd-product-name">{{ $item->name }}</div>
                                <div style="display:flex;align-items:center;gap:0.375rem;margin-top:0.25rem;">
                                    <span class="pd-sku-badge">{{ $item->sku }}</span>
                                    @if($item->barcode)
                                        <span class="pd-barcode">{{ $item->barcode }}</span>
                                    @endif
                                    @if($item->unitConversions && $item->unitConversions->count() > 0)
                                        <span class="pd-conv-dot" title="{{ $item->unitConversions->count() }} konversi satuan">{{ $item->unitConversions->count() }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="pd-category-badge">{{ $item->category->name ?? '—' }}</span>
                            </td>
                            <td>
                                <span style="font-size:0.8125rem;color:#475569;">{{ $item->unit->abbreviation ?? 'pcs' }}</span>
                            </td>
                            <td style="text-align:right;">
                                <span class="pd-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align:center;">
                                @can('view_stok_gudang')
                                    @php $isLow = $item->stock <= $item->min_stock && $item->min_stock > 0; @endphp
                                    @if(($maskStock ?? false) === true)
                                        <span class="pd-stock-lock">🔒 Terkunci</span>
                                    @else
                                        <span class="{{ $isLow ? 'pd-stock-low' : 'pd-stock-ok' }}">
                                            {{ number_format($item->stock) }} {{ $item->unit ? $item->unit->abbreviation : 'pcs' }}
                                        </span>
                                        @if($isLow)<div style="font-size:0.625rem;color:#e11d48;margin-top:2px;">⚠ Min: {{ $item->min_stock }}</div>@endif
                                    @endif
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endcan
                            </td>
                            <td style="text-align:center;">
                                <div class="pd-act">
                                    <a href="{{ route('products.show', $item) }}" class="pd-act-btn pd-act-view" title="Detail">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @can('edit_master_produk')
                                    <a href="{{ route('products.edit', $item) }}" class="pd-act-btn pd-act-edit">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </a>
                                    @endcan
                                    @can('delete_master_produk')
                                    <form action="{{ route('products.destroy', $item) }}" method="POST"
                                        onsubmit="return confirm('Hapus produk \'{{ $item->name }}\'? Data tidak bisa dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="pd-act-btn pd-act-del" title="Hapus">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="pd-empty">
                                    <div class="pd-empty-icon">📦</div>
                                    <div class="pd-empty-title">Belum ada produk</div>
                                    <div class="pd-empty-desc">Mulai tambahkan produk ke dalam sistem</div>
                                    @can('create_master_produk')
                                    <a href="{{ route('products.create') }}" class="btn-primary btn-sm">＋ Tambah Produk Pertama</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="pd-pagination">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
