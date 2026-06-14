<x-app-layout>
    <x-slot name="header">Mutasi Pengeluaran</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-purple">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        </div>
                        Mutasi Pengeluaran
                    </h1>
                    <p class="tr-subtitle">Riwayat semua pencatatan barang yang keluar atau dikurangi secara manual dari gudang.</p>
                </div>
                <div class="tr-header-actions">
                    @can('create_pengeluaran_barang')
                    <a href="{{ route('gudang.pengeluaran.create') }}" class="tr-btn tr-btn-primary" style="background: var(--tr-danger-text); border-color: var(--tr-danger-text);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Keluarkan Barang
                    </a>
                    @endcan
                </div>
            </div>

            {{-- TABBED NAVIGATION --}}
            <div class="tr-tabs">
                <a href="{{ route('gudang.pengeluaran') }}" class="tr-tab-item active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Mutasi Pengeluaran
                </a>
                <a href="{{ route('gudang.transfer') }}" class="tr-tab-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"></path><path d="M4 20L21 3"></path><path d="M21 16v5h-5"></path><path d="M15 15l6 6"></path><path d="M4 4l5 5"></path></svg>
                    Transfer Cabang
                </a>
            </div>

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error')) 
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') }}
                </div> 
            @endif

            {{-- STATS CARDS --}}
            <div class="tr-stats-grid-3" style="margin-bottom: 1.5rem;">
                <div class="tr-stat-card border-indigo">
                    <div class="tr-stat-icon bg-indigo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalPengeluaranBulanIni ?? 0) }}</div>
                        <div class="tr-stat-label">Total Transaksi Keluar (Bulan Ini)</div>
                    </div>
                </div>
                <div class="tr-stat-card border-orange">
                    <div class="tr-stat-icon bg-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($totalQtyBulanIni ?? 0) }}</div>
                        <div class="tr-stat-label">Total Qty Keluar (Bulan Ini)</div>
                    </div>
                </div>
                <div class="tr-stat-card border-purple">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ number_format($transaksiHariIni ?? 0) }}</div>
                        <div class="tr-stat-label">Transaksi Hari Ini</div>
                    </div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="tr-card">
                
                {{-- Filter Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.pengeluaran') }}" class="tr-filter-form" id="filterForm" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no referensi atau nama produk...">
                        </div>
                        
                        <button type="submit" class="tr-btn tr-btn-primary" id="filterBtn">Cari Data</button>
                        
                        @if(request()->filled('search'))
                            <a href="{{ route('gudang.pengeluaran') }}" class="tr-btn tr-btn-outline" style="color:var(--tr-danger-text); border-color:var(--tr-danger-border);">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Referensi</th>
                                <th>Barang & Batch</th>
                                <th class="c">Qty Keluar</th>
                                <th>Gudang / Lokasi</th>
                                <th>Keterangan</th>
                                <th>Petugas</th>
                                <th class="c" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movements as $item)
                                <tr>
                                    <td>
                                        <div class="tr-date-main">{{ $item->created_at->format('d M Y') }}</div>
                                        <div class="tr-date-sub">{{ $item->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <span class="tr-font-mono">{{ $item->reference_number }}</span>
                                    </td>
                                    <td>
                                        <div class="tr-prod-name">{{ $item->product?->name ?? 'Produk Dihapus' }}</div>
                                        @if($item->batch_number)
                                            <div class="tr-prod-sku" style="margin-top: 4px;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline; vertical-align:text-bottom"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8l-2 4h12z"/></svg>
                                                {{ $item->batch_number }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="c">
                                        @php
                                            $hasUnit = $item->unit_id && $item->quantity_in_unit && (float)$item->conversion_factor > 1;
                                            $unitName = $item->unit?->abbreviation ?? $item->unit?->name ?? '';
                                            $qtyInUnit = $hasUnit ? (float)$item->quantity_in_unit : null;
                                            $baseQty = (int)$item->quantity;
                                            $baseUnitName = $item->product?->unit?->abbreviation ?? $item->product?->unit?->name ?? '';
                                        @endphp
                                        <span class="tr-qty-badge" style="background:var(--tr-danger-bg); color:var(--tr-danger-text);">
                                            -{{ $hasUnit ? number_format($qtyInUnit, 0) : number_format($baseQty, 0) }}
                                        </span>
                                        <span style="font-size: 0.75rem; font-weight: 600; color:var(--tr-text-muted);">
                                            {{ $hasUnit ? $unitName : ($baseUnitName ?: '') }}
                                        </span>
                                        @if($hasUnit)
                                            <div class="tr-conversion-text" style="margin-top:4px;">= {{ number_format($baseQty, 0) }} {{ $baseUnitName ?: 'base' }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="tr-route-wh">{{ $item->warehouse?->name ?? 'Gudang Dihapus' }}</div>
                                        @if($item->location)
                                            <div style="font-size:0.75rem; color:var(--tr-text-muted); margin-top:4px; font-weight:500;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline; vertical-align:text-bottom"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                {{ $item->location->name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="tr-notes-text" title="{{ $item->notes ?? '' }}">
                                            {{ $item->notes ?? '—' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tr-user-name">{{ $item->user?->name ?? 'Sistem' }}</div>
                                        <div class="tr-user-role">{{ strtoupper(substr($item->user?->name ?? 'S', 0, 1)) }}</div>
                                    </td>
                                    <td class="c">
                                        <div style="display: flex; gap: 4px; justify-content: center;">
                                            <a href="{{ route('gudang.pengeluaran.show', $item) }}" class="tr-btn tr-btn-outline" style="padding: 0.4rem; height: auto;" title="Lihat Detail">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            @can('delete_pengeluaran_barang')
                                            <form action="{{ route('gudang.pengeluaran.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus data ini? Stok akan otomatis dikembalikan.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="tr-btn" style="padding: 0.4rem; height: auto; background: var(--tr-danger-bg); color: var(--tr-danger-text); border: 1px solid var(--tr-danger-border);" title="Hapus">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                            </div>
                                            <h6>Belum ada riwayat pengeluaran</h6>
                                            <p>Data barang yang dikeluarkan secara manual akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($movements->hasPages())
                    <div class="tr-pagination">
                        {{ $movements->links() }}
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
            --tr-primary-hover: #2563eb;
            --tr-primary-light: #eff6ff;
            --tr-success-bg: #ecfdf5;
            --tr-success-text: #059669;
            --tr-success-border: #a7f3d0;
            --tr-warning-bg: #fef3c7;
            --tr-warning-text: #92400e;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #dc2626;
            --tr-danger-border: #fecaca;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-danger-text); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-purple { background: #f3e8ff; color: #7e22ce; }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── TABS ── */
        .tr-tabs { display: flex; gap: 2rem; border-bottom: 1px solid var(--tr-border); margin-bottom: 1.5rem; overflow-x: auto; white-space: nowrap; }
        .tr-tab-item { display: inline-flex; align-items: center; gap: 8px; padding-bottom: 0.75rem; color: var(--tr-text-muted); font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tr-tab-item:hover { color: var(--tr-text-main); }
        .tr-tab-item.active { color: var(--tr-primary); border-bottom-color: var(--tr-primary); }

        /* ── STATS GRID ── */
        .tr-stats-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        @media (max-width: 992px) { .tr-stats-grid-3 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px) { .tr-stats-grid-3 { grid-template-columns: 1fr; } }
        .tr-stat-card { background: var(--tr-surface); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); }
        .tr-stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); line-height: 1; }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; }
        .bg-indigo { background: #4f46e5; }
        .bg-purple { background: #8b5cf6; }
        .bg-orange { background: #f59e0b; }
        .border-indigo { border-left: 4px solid #4f46e5; }
        .border-purple { border-left: 4px solid #8b5cf6; }
        .border-orange { border-left: 4px solid #f59e0b; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: var(--tr-success-border); }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 1rem; border: 1px solid var(--tr-border); flex: 1; min-width: 280px; transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-primary); background: #ffffff; }
        .tr-search-icon { color: var(--tr-text-light); }
        .tr-search input { border: none; background: transparent; font-size: 0.85rem; font-family: inherit; color: var(--tr-text-main); outline: none; width: 100%; }
        .tr-search input::placeholder { color: var(--tr-text-light); }
        .tr-select { padding: 0.4rem 0.8rem; border-radius: 6px; border: 1px solid var(--tr-border); font-size: 0.8rem; background: white; font-family: inherit; color: var(--tr-text-main); height: 36px; }

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; height: 36px;
        }
        .tr-btn-primary { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-primary:hover { background: #000000; transform: translateY(-1px); }
        .tr-btn-primary:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* Spinner for Buttons */
        .tr-spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: tr-spin 0.8s linear infinite; }
        @keyframes tr-spin { to { transform: rotate(360deg); } }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 960px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.c, .tr-table td.c { text-align: center; }
        .tr-table th.r, .tr-table td.r { text-align: right; }

        /* ── CELL FORMATTING ── */
        .tr-date-main { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        .tr-dot-divider { color: var(--tr-border); margin: 0 2px; }
        
        .tr-user-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-user-role { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 600; letter-spacing: 0.05em; margin-top: 2px; }
        
        .tr-prod-name { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        .tr-font-mono { font-family: monospace; background: var(--tr-border-light); padding: 2px 6px; border-radius: 4px; color: var(--tr-text-main); font-size:0.75rem; font-weight:600;}
        
        .tr-qty-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.75rem; border-radius: 999px; background: var(--tr-primary-light); color: var(--tr-primary); font-weight: 800; font-size: 0.85rem; }
        
        .tr-unit-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.15rem 0.5rem; border-radius: 6px; background: var(--tr-bg); color: var(--tr-text-muted); font-weight: 600; font-size: 0.75rem; border: 1px solid var(--tr-border); }
        
        .tr-conversion-text { font-size: 0.7rem; color: var(--tr-text-light); margin-top: 2px; }
        
        .tr-route-box { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .tr-route-wh { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-route-arrow { color: var(--tr-text-light); }
        .tr-notes-text { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 6px; font-style: italic; background: var(--tr-bg); padding: 4px 8px; border-radius: 4px; border: 1px solid var(--tr-border-light); display: inline-block; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .tr-badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-badge-success { background: var(--tr-success-bg); color: var(--tr-success-text); }
        .tr-badge-warning { background: var(--tr-warning-bg); color: var(--tr-warning-text); }
        .tr-badge-done { background: var(--tr-bg); color: var(--tr-text-main); border: 1px solid var(--tr-border); }
        .tr-status-sub { font-size: 0.7rem; color: var(--tr-text-muted); font-weight: 500; margin-top: 4px; }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: #f3e8ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: #7e22ce; }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto; max-width: 400px; line-height: 1.5; }

        /* ── PAGINATION ── */
        .tr-pagination { padding: 1rem 1.25rem; border-top: 1px solid var(--tr-border-light); background: #ffffff; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-header-actions { width: 100%; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-filter-form { flex-direction: column; align-items: stretch; }
            .tr-search { width: 100%; min-width: auto; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animasi Loading saat Filter
            const filterForm = document.getElementById('filterForm');
            const filterBtn = document.getElementById('filterBtn');
            if (filterForm && filterBtn) {
                filterForm.addEventListener('submit', function () {
                    filterBtn.disabled = true;
                    filterBtn.innerHTML = '<span class="tr-spinner"></span> Memuat...';
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
