<x-app-layout>
    <x-slot name="header">Data Kendaraan Operasional</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">
            
            {{-- ─── HEADER AREA ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Armada</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        </div>
                        Data Kendaraan
                    </h1>
                    <p class="tr-subtitle">Kelola seluruh unit armada kendaraan untuk mendukung operasional toko.</p>
                </div>

                <div class="tr-header-actions">
                    @can('create_kendaraan_operasional')
                        <a href="{{ route('operasional.kendaraan.create') }}" class="tr-btn tr-btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah Kendaraan
                        </a>
                    @else
                        <button class="tr-btn tr-btn-disabled" title="Izin diperlukan: create_kendaraan_operasional" disabled>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Tambah Kendaraan
                        </button>
                    @endcan
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- ─── STATS CARDS ─── --}}
            <div class="tr-stats-row">
                <div class="tr-stat-card">
                    <div class="tr-stat-icon bg-indigo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $totalVehicles }}</div>
                        <div class="tr-stat-label">Total Kendaraan</div>
                    </div>
                </div>
                <div class="tr-stat-card">
                    <div class="tr-stat-icon bg-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $vehiclesWithExpenses }}</div>
                        <div class="tr-stat-label">Digunakan</div>
                    </div>
                </div>
                <div class="tr-stat-card">
                    <div class="tr-stat-icon bg-warning">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value">{{ $totalExpensesCount }}</div>
                        <div class="tr-stat-label">Total Transaksi</div>
                    </div>
                </div>
                <a href="{{ route('operasional.kendaraan.export') }}" class="tr-stat-card tr-stat-clickable">
                    <div class="tr-stat-icon bg-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </div>
                    <div>
                        <div class="tr-stat-value tr-stat-small">Export</div>
                        <div class="tr-stat-label">Download CSV</div>
                    </div>
                </a>
            </div>

            {{-- ─── FILTER & SEARCH ─── --}}
            <div class="tr-filter-bar">
                <form method="GET" action="{{ route('operasional.kendaraan.index') }}" class="tr-filter-form">
                    <div class="tr-search-box">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="search" class="tr-search-input" placeholder="Cari plat nomor, jenis..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="tr-btn tr-btn-dark">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('operasional.kendaraan.index') }}" class="tr-btn tr-btn-outline">Reset</a>
                    @endif
                </form>
            </div>

            {{-- ─── MAIN TABLE CARD ─── --}}
            <div class="tr-card">
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;" class="c">No</th>
                                <th>Plat Nomor</th>
                                <th>Jenis / Tipe</th>
                                <th>Keterangan</th>
                                <th class="r" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicles as $index => $kendaraan)
                                <tr>
                                    <td class="c tr-text-muted tr-font-mono">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="tr-license-plate">
                                            {{ strtoupper($kendaraan->license_plate) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="tr-font-bold tr-text-main">{{ $kendaraan->type ?: '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="tr-text-muted">{{ $kendaraan->description ?: '— Tidak ada keterangan' }}</div>
                                    </td>
                                    <td class="r">
                                        <div class="tr-actions-group">
                                            @can('edit_kendaraan_operasional')
                                                <a href="{{ route('operasional.kendaraan.edit', $kendaraan->id) }}" class="tr-action-btn edit" title="Edit Data">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                            @endcan

                                            @can('delete_kendaraan_operasional')
                                                <form action="{{ route('operasional.kendaraan.destroy', $kendaraan->id) }}" method="POST" onsubmit="return confirm('Hapus data kendaraan ini?');" class="tr-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="tr-action-btn delete" title="Hapus Data">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">🚚</div>
                                            <h6>Belum ada kendaraan</h6>
                                            <p>Daftar armada kendaraan operasional Anda akan muncul di sini.</p>
                                            @can('create_kendaraan_operasional')
                                                <a href="{{ route('operasional.kendaraan.create') }}" class="tr-link">Tambah Unit Sekarang &rarr;</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-indigo: #4f46e5;
            --tr-indigo-light: #e0e7ff;
            --tr-danger: #ef4444;
            --tr-danger-bg: #fef2f2;
            --tr-success: #10b981;
            --tr-success-bg: #ecfdf5;
            --tr-radius: 14px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; }
        .tr-page { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* HEADER */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-indigo); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.625rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.935rem; color: var(--tr-text-muted); margin-top: 6px; max-width: 500px; line-height: 1.5; }

        /* BUTTONS */
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.625rem 1.25rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; }
        .tr-btn-primary { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f1f5f9; }
        .tr-btn-disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; }

        /* CARD & TABLE */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; }
        .tr-table thead th { background: #f8fafc; padding: 1rem 1.25rem; text-align: left; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-table tbody td { padding: 1.125rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table .c { text-align: center; }
        .tr-table .r { text-align: right; }

        /* ACTIONS */
        .tr-actions-group { display: flex; gap: 8px; justify-content: flex-end; }
        .tr-action-btn { width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--tr-border); background: white; color: var(--tr-text-muted); transition: 0.2s; cursor: pointer; }
        .tr-action-btn.edit:hover { color: var(--tr-indigo); border-color: var(--tr-indigo); background: var(--tr-indigo-light); }
        .tr-action-btn.delete:hover { color: var(--tr-danger); border-color: var(--tr-danger); background: var(--tr-danger-bg); }
        .tr-inline { display: inline; }

        /* PLATE DESIGN */
        .tr-license-plate { display: inline-block; padding: 4px 10px; background: #1e293b; color: white; border-radius: 6px; font-family: ui-monospace, monospace; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.05em; border: 1px solid #0f172a; }

        /* ALERTS */
        .tr-alert { padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem; }
        .tr-alert-success { background: var(--tr-success-bg); color: #065f46; border: 1px solid #a7f3d0; }
        
        .tr-text-muted { color: var(--tr-text-muted); }
        .tr-text-main { color: var(--tr-text-main); }
        .tr-font-bold { font-weight: 700; }
        .tr-font-mono { font-family: ui-monospace, monospace; }

        .tr-empty-state { padding: 4rem 2rem; text-align: center; }
        .tr-empty-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .tr-empty-state h6 { font-size: 1.125rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--tr-text-main); }
        .tr-empty-state p { color: var(--tr-text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; }
        .tr-link { color: var(--tr-indigo); font-weight: 700; text-decoration: none; }

        /* Stats */
        .tr-stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        @media (max-width: 992px) { .tr-stats-row { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .tr-stats-row { grid-template-columns: 1fr; } }
        
        .tr-stat-card { background: var(--tr-surface); border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border: 1px solid var(--tr-border); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .tr-stat-card.tr-stat-clickable { text-decoration: none; color: inherit; transition: all 0.2s; }
        .tr-stat-card.tr-stat-clickable:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .tr-stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
        .tr-stat-value { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); line-height: 1; }
        .tr-stat-value.tr-stat-small { font-size: 1.125rem; }
        .tr-stat-label { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 0.25rem; font-weight: 600; }
        .bg-purple { background: #8b5cf6; }
        .bg-warning { background: #f59e0b; }
        .bg-success { background: #10b981; }
        .tr-btn-dark { background: #1e293b; color: white; }
        .tr-btn-dark:hover { background: #0f172a; }

        /* Filter */
        .tr-filter-bar { margin-bottom: 1rem; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .tr-search-box { position: relative; flex: 1; min-width: 250px; }
        .tr-search-box svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .tr-search-input { width: 100%; padding: 0.625rem 1rem 0.625rem 2.5rem; border: 1px solid var(--tr-border); border-radius: 10px; font-size: 0.875rem; }
        .tr-search-input:focus { outline: none; border-color: var(--tr-indigo); }

        /* Pagination */
        .pagination { margin-top: 1.5rem; justify-content: center; }
        .page-item.active .page-link { background: var(--tr-indigo); border-color: var(--tr-indigo); }
        .page-link { color: var(--tr-indigo); }

        @media (max-width: 640px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-btn { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
    
    {{-- Pagination --}}
    @if($vehicles->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $vehicles->links() }}
    </div>
    @endif
</x-app-layout>