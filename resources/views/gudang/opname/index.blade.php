<x-app-layout>
    <x-slot name="header">Opname Stok</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Audit Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-warning">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14h6"></path><path d="M9 18h6"></path><path d="M9 10h6"></path></svg>
                        </div>
                        Riwayat Opname Stok
                    </h1>
                    <p class="tr-subtitle">Catatan penyesuaian antara stok fisik di lapangan vs stok di sistem.</p>
                </div>
                
                @can('create_opname_stok')
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.opname.create') }}" class="tr-btn tr-btn-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        Buat Opname Baru
                    </a>
                </div>
                @endcan
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
                    <form method="GET" action="{{ route('gudang.opname') }}" class="tr-filter-form">
                        <div class="tr-search">
                            <svg class="tr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. dokumen, SKU, atau nama barang...">
                        </div>
                        <button type="submit" class="tr-btn tr-btn-dark">Cari Data</button>
                        
                        @if(request('search'))
                            <a href="{{ route('gudang.opname') }}" class="tr-btn tr-btn-danger-outline">Reset Filter</a>
                        @endif
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Dokumen</th>
                                <th>Barang / Produk</th>
                                <th>Gudang & Lokasi</th>
                                <th class="c">Selisih</th>
                                <th>Keterangan</th>
                                <th>Petugas</th>
                                <th class="c" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($adjustments as $adj)
                            <tr>
                                <td>
                                    <div class="tr-date-main">{{ $adj->created_at->format('d M Y') }}</div>
                                    <div class="tr-date-sub">{{ $adj->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <span class="tr-ref-badge">{{ $adj->reference_number }}</span>
                                </td>
                                <td>
                                    <div class="tr-prod-name">{{ $adj->product->name ?? 'Produk Dihapus' }}</div>
                                    <div class="tr-prod-sku">SKU: <span class="tr-font-mono">{{ $adj->product->sku ?? '-' }}</span></div>
                                </td>
                                <td>
                                    <div class="tr-wh-name">{{ $adj->warehouse->name ?? '-' }}</div>
                                    @if($adj->location)
                                        <div class="tr-loc-text">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                            Rak: {{ $adj->location->name }}
                                        </div>
                                    @else
                                        <div class="tr-loc-text text-muted">Area Umum</div>
                                    @endif
                                </td>
                                <td class="c">
                                    {{-- Diff Logic: Plus, Minus, or Zero --}}
                                    @if($adj->quantity > 0)
                                        <span class="tr-diff-badge plus">+{{ $adj->quantity }}</span>
                                    @elseif($adj->quantity < 0)
                                        <span class="tr-diff-badge minus">{{ $adj->quantity }}</span>
                                    @else
                                        <span class="tr-diff-badge zero">±0</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="tr-notes-text" title="{{ $adj->notes ?? '' }}">
                                        {{ $adj->notes ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="tr-user-text">{{ $adj->user->name ?? 'Sistem' }}</div>
                                </td>
                                <td class="c">
                                    <div class="tr-actions-group">
                                        <a href="{{ route('gudang.opname.show', $adj) }}" class="tr-action-btn view" title="Lihat Detail">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                        @can('delete_opname_stok')
                                        <form action="{{ route('gudang.opname.destroy', $adj) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus riwayat ini? Penyesuaian stok akan dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tr-action-btn delete" title="Batalkan Opname">
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
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14h6"></path><path d="M9 18h6"></path><path d="M9 10h6"></path></svg>
                                        </div>
                                        <h6>Belum ada riwayat opname</h6>
                                        <p>Data penyesuaian/sinkronisasi fisik barang di gudang akan muncul di sini.</p>
                                        @can('create_opname_stok')
                                            <a href="{{ route('gudang.opname.create') }}" class="tr-empty-link">Mulai Opname Baru &rarr;</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($adjustments->hasPages())
                    <div class="tr-pagination">
                        {{ $adjustments->withQueryString()->links() }}
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
            --tr-success-bg: #ecfdf5;
            --tr-success-text: #059669;
            --tr-success-border: #a7f3d0;
            --tr-danger-bg: #fef2f2;
            --tr-danger-text: #dc2626;
            --tr-danger-border: #fecaca;
            --tr-warning: #f59e0b;
            --tr-warning-hover: #d97706;
            --tr-warning-bg: #fffbeb;
            --tr-warning-text: #b45309;
            --tr-warning-border: #fde68a;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 1.5rem; max-width: 1280px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-warning); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-warning { background: var(--tr-warning-bg); color: var(--tr-warning); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── ALERTS ── */
        .tr-alert { display: flex; align-items: center; gap: 10px; padding: 1rem 1.25rem; border-radius: var(--tr-radius-md); margin-bottom: 1.25rem; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent; }
        .tr-alert-success { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: var(--tr-success-border); }
        .tr-alert-danger { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }

        /* ── CARD & FILTER ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-filter-bar { padding: 1rem 1.25rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-filter-form { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .tr-search { display: flex; align-items: center; gap: 8px; background: var(--tr-bg); border-radius: 6px; padding: 0.5rem 1rem; border: 1px solid var(--tr-border); flex: 1; min-width: 280px; transition: border-color 0.2s; }
        .tr-search:focus-within { border-color: var(--tr-warning); background: #ffffff; }
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
        
        /* Tema Warna Opname Stok (Warning/Amber) */
        .tr-btn-warning { background: var(--tr-warning); color: #ffffff; border-color: var(--tr-warning); box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2); }
        .tr-btn-warning:hover { background: var(--tr-warning-hover); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3); }
        
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
        
        /* Ref badge menggunakan warna amber untuk Opname */
        .tr-ref-badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 6px; background: var(--tr-warning-bg); color: var(--tr-warning-text); border: 1px solid var(--tr-warning-border); font-family: monospace; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.02em; }
        
        .tr-prod-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; line-height: 1.3; }
        .tr-prod-sku { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-font-mono { font-family: monospace; background: var(--tr-bg); padding: 1px 4px; border-radius: 4px; border: 1px solid var(--tr-border); color: var(--tr-text-main); }

        /* Diff Badges (Kunci dari tabel Opname) */
        .tr-diff-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.25rem 0.6rem; border-radius: 999px; font-weight: 800; font-size: 0.85rem; border: 1px solid transparent; min-width: 44px;}
        .tr-diff-badge.plus { background: var(--tr-success-bg); color: var(--tr-success-text); border-color: var(--tr-success-border); }
        .tr-diff-badge.minus { background: var(--tr-danger-bg); color: var(--tr-danger-text); border-color: var(--tr-danger-border); }
        .tr-diff-badge.zero { background: var(--tr-bg); color: var(--tr-text-muted); border-color: var(--tr-border); }
        
        .tr-wh-name { font-weight: 600; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-loc-text { font-size: 0.7rem; color: var(--tr-text-muted); margin-top: 4px; display: flex; align-items: center; gap: 4px; font-weight: 500; }
        .tr-loc-text.text-muted { color: var(--tr-text-light); font-style: italic; }
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
        .tr-empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--tr-warning-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--tr-warning); }
        .tr-empty-state h6 { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin-bottom: 0.35rem; }
        .tr-empty-state p { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0 auto 1.25rem; max-width: 400px; line-height: 1.5; }
        .tr-empty-link { font-size: 0.85rem; font-weight: 600; color: var(--tr-warning); text-decoration: none; }
        .tr-empty-link:hover { text-decoration: underline; color: var(--tr-warning-hover); }

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