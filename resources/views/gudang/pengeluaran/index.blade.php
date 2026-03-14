<x-app-layout>
    <x-slot name="header">Pengeluaran Barang</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-red">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                        </div>
                        Mutasi Pengeluaran (Outbound)
                    </h1>
                    <p class="tr-subtitle">Riwayat semua pencatatan barang yang keluar atau dikurangi dari gudang.</p>
                </div>
                
                @can('create_pengeluaran_barang')
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.pengeluaran.create') }}" class="tr-btn tr-btn-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Keluarkan Barang
                    </a>
                </div>
                @endcan
            </div>

            {{-- TABBED NAVIGATION --}}
            <div class="tr-tabs">
                <a href="{{ route('gudang.pengeluaran') }}" class="tr-tab-item active-danger">
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

            {{-- MAIN CARD --}}
            <div class="tr-card">
                
                {{-- Search Bar --}}
                <div class="tr-card-header tr-filter-bar">
                    <form method="GET" action="{{ route('gudang.pengeluaran') }}" class="tr-filter-form">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. referensi atau nama barang...">
                        </div>
                        <button type="submit" class="tr-btn tr-btn-dark">Cari Data</button>
                        
                        @if(request('search'))
                            <a href="{{ route('gudang.pengeluaran') }}" class="tr-btn tr-btn-danger-outline">Reset Filter</a>
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
                                    <span class="tr-ref-badge">{{ $item->reference_number }}</span>
                                </td>
                                <td>
                                    <div class="tr-prod-name">{{ $item->product?->name ?? 'Produk Dihapus' }}</div>
                                    @if($item->batch_number)
                                        <div class="tr-prod-batch">Batch: <span class="tr-font-mono">{{ $item->batch_number }}</span></div>
                                    @endif
                                </td>
                                <td class="c">
                                    <span class="tr-qty-out-badge">−{{ $item->quantity }}</span>
                                </td>
                                <td>
                                    <div class="tr-wh-name">{{ $item->warehouse?->name ?? 'Gudang Dihapus' }}</div>
                                    @if($item->location)
                                        <div class="tr-loc-text">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                            Rak: {{ $item->location->name }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="tr-notes-text" title="{{ $item->notes ?? '' }}">
                                        {{ $item->notes ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="tr-user-text">{{ $item->user->name ?? 'Sistem' }}</div>
                                </td>
                                <td class="c">
                                    <div class="tr-actions-group">
                                        <a href="{{ route('gudang.pengeluaran.show', $item) }}" class="tr-action-btn view" title="Lihat Detail">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                        @can('delete_pengeluaran_barang')
                                        <form action="{{ route('gudang.pengeluaran.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus riwayat ini? Stok yang keluar akan dikembalikan ke dalam gudang.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tr-action-btn delete" title="Batalkan & Hapus">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
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
                                        @can('create_pengeluaran_barang')
                                            <a href="{{ route('gudang.pengeluaran.create') }}" class="tr-empty-link">Buat Pengeluaran Baru &rarr;</a>
                                        @endcan
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
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #3b82f6;
            --tr-primary-hover: #2563eb;
            --tr-success-bg: #ecfdf5;
            --tr-success-text: #059669;
            --tr-success-border: #a7f3d0;
            --tr-danger: #ef4444;
            --tr-danger-hover: #dc2626;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #b91c1c;
            --tr-danger-border: #fecaca;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-danger); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-red { background: var(--tr-danger-bg); color: var(--tr-danger); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── TABS ── */
        .tr-tabs { display: flex; gap: 2rem; border-bottom: 1px solid var(--tr-border); margin-bottom: 1.5rem; overflow-x: auto; white-space: nowrap; }
        .tr-tab-item { display: inline-flex; align-items: center; gap: 8px; padding-bottom: 0.75rem; color: var(--tr-text-muted); font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tr-tab-item:hover { color: var(--tr-text-main); }
        .tr-tab-item.active-danger { color: var(--tr-danger); border-bottom-color: var(--tr-danger); }

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

        /* ── BUTTONS ── */
        .tr-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600;
            cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent;
        }
        .tr-btn-dark { background: var(--tr-text-main); color: #ffffff; border-color: var(--tr-text-main); }
        .tr-btn-dark:hover { background: #000000; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tr-btn-danger { background: var(--tr-danger); color: #ffffff; border-color: var(--tr-danger); box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
        .tr-btn-danger:hover { background: var(--tr-danger-hover); transform: translateY(-1px); }
        .tr-btn-danger-outline { border-color: var(--tr-danger-border); color: var(--tr-danger-text); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-bg); }

        /* ── TABLE RESPONSIVE ── */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .tr-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
        .tr-table thead th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--tr-border); background: var(--tr-bg); white-space: nowrap; text-align: left; }
        .tr-table tbody tr { transition: background 0.15s ease; }
        .tr-table tbody tr:hover { background: #f8fafc; }
        .tr-table tbody td { padding: 1rem 1.25rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid var(--tr-border-light); }
        .tr-table tbody tr:last-child td { border-bottom: none; }
        .tr-table th.c, .tr-table td.c { text-align: center; }

        /* ── CELL FORMATTING ── */
        .tr-date-main { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; white-space: nowrap; }
        .tr-date-sub { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 2px; }
        
        .tr-ref-badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 6px; background: var(--tr-border-light); color: var(--tr-text-main); font-family: monospace; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.02em; }
        
        .tr-prod-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-batch { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-font-mono { font-family: monospace; background: var(--tr-bg); padding: 1px 4px; border-radius: 4px; border: 1px solid var(--tr-border); }

        .tr-qty-out-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.75rem; border-radius: 999px; background: var(--tr-danger-bg); color: var(--tr-danger-text); font-weight: 800; font-size: 0.85rem; border: 1px solid var(--tr-danger-border); }
        
        .tr-wh-name { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-loc-text { font-size: 0.7rem; color: var(--tr-text-muted); margin-top: 4px; display: flex; align-items: center; gap: 4px; font-weight: 500; }
        .tr-loc-text svg { color: var(--tr-text-light); }
        
        .tr-notes-text { font-size: 0.8rem; color: var(--tr-text-muted); max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .tr-user-text { font-size: 0.8rem; color: var(--tr-text-muted); font-weight: 500; }

        /* ── ACTION BUTTONS ── */
        .tr-actions-group { display: flex; gap: 6px; justify-content: center; }
        .tr-action-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 6px; border: 1px solid transparent; background: var(--tr-bg); color: var(--tr-text-muted); transition: all 0.2s; cursor: pointer; }
        .tr-action-btn.view:hover { background: var(--tr-primary-light); color: var(--tr-primary); border-color: #bfdbfe; }
        .tr-action-btn.delete:hover { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }

        /* ── EMPTY STATE ── */
        .tr-empty-state { text-align: center; padding: 4rem 1.5rem; }
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-danger-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-danger-text); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto 1.25rem; max-width: 400px; line-height: 1.5; }
        .tr-empty-link { font-size: 0.85rem; font-weight: 600; color: var(--tr-danger); text-decoration: none; }
        .tr-empty-link:hover { text-decoration: underline; }

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
</x-app-layout>