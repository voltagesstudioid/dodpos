<x-app-layout>
    <x-slot name="header">Penerimaan Barang Gudang (Non-PO)</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        </div>
                        Penerimaan Barang — Gudang
                    </h1>
                    <p class="tr-subtitle">Stok masuk yang tidak terkait Purchase Order (retur, stok awal, koreksi, konsinyasi, transfer)</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.penerimaan.export', request()->only(['search', 'source_type', 'date_from', 'date_to'])) }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                    <a href="{{ route('gudang.terimapo.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Terima dari PO
                    </a>
                    @can('create_penerimaan_barang')
                    <a href="{{ route('gudang.penerimaan.create') }}" class="tr-btn tr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Terima Barang
                    </a>
                    @endcan
                </div>
            </div>

            {{-- STATS CARDS --}}
            <div class="tr-stats-grid-4">
                <div class="tr-stat-card border-green">
                    <div class="tr-stat-icon bg-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">+{{ number_format($totalQty ?? 0) }}</div>
                        <div class="tr-stat-label">Total Qty Masuk</div>
                    </div>
                </div>
                <div class="tr-stat-card border-blue">
                    <div class="tr-stat-icon bg-blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalTransactions ?? 0) }}</div>
                        <div class="tr-stat-label">Total Transaksi</div>
                    </div>
                </div>
                <div class="tr-stat-card border-purple">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ count($statsBySource) }}</div>
                        <div class="tr-stat-label">Jenis Sumber</div>
                    </div>
                </div>
                <div class="tr-stat-card border-orange">
                    <div class="tr-stat-icon bg-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $movements->first()?->created_at?->diffForHumans() ?? '-' }}</div>
                        <div class="tr-stat-label">Transaksi Terakhir</div>
                    </div>
                </div>
            </div>

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success" style="margin-top: 1.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="tr-alert tr-alert-danger" style="margin-top: 1.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- MAIN CARD --}}
            <div class="tr-card" style="margin-top: 1.5rem;">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.penerimaan') }}" class="tr-filter-form">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. referensi / produk...">
                        </div>
                        
                        <select name="source_type" class="tr-select">
                            <option value="">Semua Sumber</option>
                            @foreach($sourceTypes as $k => $label)
                                <option value="{{ $k }}" {{ request('source_type') == $k ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="tr-select" placeholder="Dari Tanggal">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="tr-select" placeholder="Sampai Tanggal">
                        
                        <button type="submit" class="tr-btn tr-btn-dark">Filter</button>
                        
                        @if(request('search') || request('source_type') || request('date_from') || request('date_to'))
                            <a href="{{ route('gudang.penerimaan') }}" class="tr-btn tr-btn-danger-outline">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>No. Referensi</th>
                                <th>Sumber</th>
                                <th>Produk</th>
                                <th>Gudang</th>
                                <th class="c">Qty</th>
                                <th>Petugas</th>
                                <th class="c" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movements as $m)
                            @php
                                $sourceBadge = match(optional($m)->source_type) {
                                    'retur_pelanggan' => ['class'=>'tr-badge-warning', 'label'=>'Retur'],
                                    'stok_awal'       => ['class'=>'tr-badge-purple',  'label'=>'Stok Awal'],
                                    'koreksi'         => ['class'=>'tr-badge-danger',  'label'=>'Koreksi'],
                                    'transfer_masuk'  => ['class'=>'tr-badge-blue',    'label'=>'Transfer'],
                                    'konsinyasi'      => ['class'=>'tr-badge-success', 'label'=>'Konsinyasi'],
                                    default           => ['class'=>'tr-badge-gray',    'label'=>'Lainnya'],
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="tr-date-main">{{ $m->created_at->format('d M Y') }}</div>
                                    <div class="tr-date-sub">{{ $m->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <span class="tr-ref-badge">{{ $m->reference_number }}</span>
                                </td>
                                <td>
                                    <span class="tr-badge {{ $sourceBadge['class'] }}">{{ $sourceBadge['label'] }}</span>
                                </td>
                                <td>
                                    <div class="tr-prod-name">{{ $m->product?->name ?? '-' }}</div>
                                    <div class="tr-prod-sku">{{ $m->product?->sku ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="tr-wh-name">{{ $m->warehouse?->name ?? '-' }}</div>
                                </td>
                                <td class="c">
                                    <span class="tr-qty-in-badge">+{{ $m->quantity }}</span>
                                </td>
                                <td>
                                    <div class="tr-user-text">{{ $m->user?->name ?? '-' }}</div>
                                </td>
                                <td class="c">
                                    <div class="tr-actions-group">
                                        <a href="{{ route('gudang.penerimaan.show', $m) }}" class="tr-action-btn view" title="Detail">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        @can('delete_penerimaan_barang')
                                        <form action="{{ route('gudang.penerimaan.destroy', $m) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus riwayat ini? Stok yang masuk akan dikurangi.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tr-action-btn delete" title="Hapus">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="tr-empty-state">
                                        <div class="tr-empty-icon">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        </div>
                                        <h6>Belum ada data penerimaan</h6>
                                        <p>Data barang masuk non-PO akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($movements->hasPages())
                    <div class="tr-pagination">
                        {{ $movements->withQueryString()->links() }}
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
</x-app-layout>
