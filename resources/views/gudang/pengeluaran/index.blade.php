<x-app-layout>
    <x-slot name="header">Pengeluaran Barang</x-slot>

    <div class="ob-page">

        {{-- ══════════ HEADER SECTION ══════════ --}}
        <div class="ob-header">
            <div class="ob-header-left">
                <div class="ob-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                </div>
                <div>
                    <div class="ob-eyebrow">Manajemen Gudang</div>
                    <h1 class="ob-title">Mutasi Pengeluaran</h1>
                    <p class="ob-subtitle">Riwayat semua pencatatan barang yang keluar atau dikurangi dari gudang.</p>
                </div>
            </div>
            @can('create_pengeluaran_barang')
            <a href="{{ route('gudang.pengeluaran.create') }}" class="ob-btn ob-btn-danger">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Keluarkan Barang
            </a>
            @endcan
        </div>

        {{-- ══════════ TABS ══════════ --}}
        <div class="ob-tabs">
            <a href="{{ route('gudang.pengeluaran') }}" class="ob-tab ob-tab-active">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Mutasi Pengeluaran
            </a>
            <a href="{{ route('gudang.transfer') }}" class="ob-tab">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 3h5v5"/><path d="M4 20L21 3"/><path d="M21 16v5h-5"/><path d="M15 15l6 6"/><path d="M4 4l5 5"/></svg>
                Transfer Cabang
            </a>
        </div>

        {{-- ══════════ ALERTS ══════════ --}}
        @if(session('success'))
        <div class="ob-alert ob-alert-success">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="ob-alert ob-alert-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- ══════════ MAIN CARD ══════════ --}}
        <div class="ob-card">

            {{-- SEARCH / FILTER BAR --}}
            <div class="ob-filter">
                <form method="GET" action="{{ route('gudang.pengeluaran') }}" class="ob-filter-form">
                    <div class="ob-search-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. referensi atau nama barang..." class="ob-search-input">
                    </div>
                    <button type="submit" class="ob-btn ob-btn-dark">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Cari Data
                    </button>
                    @if(request('search'))
                    <a href="{{ route('gudang.pengeluaran') }}" class="ob-btn ob-btn-ghost ob-btn-clear">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Reset
                    </a>
                    @endif
                </form>
                @if(request('search'))
                <div class="ob-filter-info">
                    Menampilkan hasil pencarian: <strong>"{{ request('search') }}"</strong>
                    — {{ $movements->total() }} data ditemukan
                </div>
                @endif
            </div>

            {{-- TABLE --}}
            <div class="ob-table-wrap">
                <table class="ob-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Referensi</th>
                            <th>Barang & Batch</th>
                            <th class="c">Qty Keluar</th>
                            <th>Gudang / Lokasi</th>
                            <th>Keterangan</th>
                            <th>Petugas</th>
                            <th class="c" style="width:90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $item)
                        <tr>
                            <td>
                                <div class="ob-date-main">{{ $item->created_at->format('d M Y') }}</div>
                                <div class="ob-date-sub">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <span class="ob-ref">{{ $item->reference_number }}</span>
                            </td>
                            <td>
                                <div class="ob-prod-name">{{ $item->product?->name ?? 'Produk Dihapus' }}</div>
                                @if($item->batch_number)
                                <div class="ob-prod-batch">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8l-2 4h12z"/></svg>
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
                                <span class="ob-qty-out">
                                    −{{ $hasUnit ? number_format($qtyInUnit, 0) : number_format($baseQty, 0) }}
                                    <span class="ob-qty-unit">{{ $hasUnit ? $unitName : ($baseUnitName ?: '') }}</span>
                                </span>
                                @if($hasUnit)
                                <div class="ob-qty-base">= {{ number_format($baseQty, 0) }} {{ $baseUnitName ?: 'base' }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="ob-wh-name">{{ $item->warehouse?->name ?? 'Gudang Dihapus' }}</div>
                                @if($item->location)
                                <div class="ob-loc">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    {{ $item->location->name }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="ob-notes" title="{{ $item->notes ?? '' }}">
                                    {{ $item->notes ?? '—' }}
                                </div>
                            </td>
                            <td>
                                <div class="ob-user">
                                    <span class="ob-user-avatar">{{ strtoupper(substr($item->user->name ?? 'S', 0, 1)) }}</span>
                                    <span class="ob-user-name">{{ $item->user->name ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            <td class="c">
                                <div class="ob-actions">
                                    <a href="{{ route('gudang.pengeluaran.show', $item) }}" class="ob-act-btn ob-act-view" title="Lihat Detail">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    @can('delete_pengeluaran_barang')
                                    <form action="{{ route('gudang.pengeluaran.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin menghapus? Stok akan dikembalikan ke gudang.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ob-act-btn ob-act-del" title="Hapus">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="ob-empty">
                                    <div class="ob-empty-icon">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    </div>
                                    <h3>Belum ada riwayat pengeluaran</h3>
                                    <p>Data barang yang dikeluarkan secara manual akan muncul di sini.</p>
                                    @can('create_pengeluaran_barang')
                                    <a href="{{ route('gudang.pengeluaran.create') }}" class="ob-empty-link">
                                        Buat Pengeluaran Baru
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($movements->hasPages())
            <div class="ob-pagination">
                <div class="ob-pagination-info">
                    Menampilkan {{ $movements->firstItem() ?? 0 }}–{{ $movements->lastItem() ?? 0 }} dari {{ $movements->total() }} data
                </div>
                <div class="ob-pagination-links">
                    {{ $movements->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>

    </div>

    @push('styles')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    :root {
        --ob-bg: #f1f5f9; --ob-surface: #fff; --ob-border: #e2e8f0; --ob-border-light: #f1f5f9;
        --ob-text: #0f172a; --ob-text2: #475569; --ob-muted: #94a3b8;
        --ob-danger: #ef4444; --ob-danger-hover: #dc2626; --ob-danger-bg: #fef2f2; --ob-danger-text: #991b1b; --ob-danger-border: #fecaca;
        --ob-success-bg: #ecfdf5; --ob-success-text: #065f46; --ob-success-border: #a7f3d0;
        --ob-blue: #3b82f6; --ob-blue-bg: #eff6ff; --ob-blue-border: #bfdbfe;
        --ob-r: 12px; --ob-r-sm: 8px;
    }
    *, *::before, *::after { box-sizing: border-box; }
    .ob-page { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: var(--ob-text); max-width: 1280px; margin: 0 auto; padding: 1.5rem 1.25rem 3rem; background: var(--ob-bg); min-height: 100vh; }

    /* ── HEADER ── */
    .ob-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem; }
    .ob-header-left { display: flex; gap: 0.875rem; align-items: flex-start; }
    .ob-icon-box { width: 48px; height: 48px; border-radius: 12px; background: var(--ob-danger-bg); color: var(--ob-danger); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ob-eyebrow { font-size: 0.65rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--ob-danger); margin-bottom: 0.2rem; }
    .ob-title { font-size: 1.4rem; font-weight: 800; margin: 0; letter-spacing: -0.02em; line-height: 1.2; }
    .ob-subtitle { font-size: 0.82rem; color: var(--ob-text2); margin: 0.25rem 0 0; line-height: 1.5; }

    /* ── TABS ── */
    .ob-tabs { display: flex; gap: 0; border-bottom: 2px solid var(--ob-border); margin-bottom: 1.25rem; overflow-x: auto; }
    .ob-tab { display: inline-flex; align-items: center; gap: 7px; padding: 0.7rem 1.25rem; color: var(--ob-muted); font-size: 0.85rem; font-weight: 600; text-decoration: none; border-bottom: 2.5px solid transparent; margin-bottom: -2px; transition: all .15s; white-space: nowrap; border-radius: 6px 6px 0 0; }
    .ob-tab:hover { color: var(--ob-text2); background: #f8fafc; }
    .ob-tab-active { color: var(--ob-danger); border-bottom-color: var(--ob-danger); }

    /* ── BUTTONS ── */
    .ob-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.55rem 1.15rem; border-radius: var(--ob-r-sm); font-size: 0.82rem; font-weight: 700; font-family: inherit; cursor: pointer; border: 1px solid transparent; text-decoration: none; transition: all .15s; white-space: nowrap; height: 40px; }
    .ob-btn-danger { background: var(--ob-danger); color: #fff; box-shadow: 0 2px 8px rgba(239,68,68,.2); }
    .ob-btn-danger:hover { background: var(--ob-danger-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,.25); }
    .ob-btn-dark { background: var(--ob-text); color: #fff; }
    .ob-btn-dark:hover { background: #000; transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,.15); }
    .ob-btn-ghost { background: transparent; border-color: var(--ob-border); color: var(--ob-text2); }
    .ob-btn-ghost:hover { border-color: var(--ob-muted); color: var(--ob-text); }
    .ob-btn-clear { color: var(--ob-danger-text); border-color: var(--ob-danger-border); }
    .ob-btn-clear:hover { background: var(--ob-danger-bg); }

    /* ── ALERTS ── */
    .ob-alert { display: flex; align-items: center; gap: 10px; padding: 0.85rem 1.125rem; border-radius: var(--ob-r-sm); margin-bottom: 1rem; font-size: 0.84rem; font-weight: 500; border: 1px solid; }
    .ob-alert-success { background: var(--ob-success-bg); color: var(--ob-success-text); border-color: var(--ob-success-border); }
    .ob-alert-danger { background: var(--ob-danger-bg); color: var(--ob-danger-text); border-color: var(--ob-danger-border); }

    /* ── CARD ── */
    .ob-card { background: var(--ob-surface); border: 1px solid var(--ob-border); border-radius: var(--ob-r); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04); }

    /* ── FILTER BAR ── */
    .ob-filter { padding: 1rem 1.25rem; border-bottom: 1px solid var(--ob-border-light); background: #fff; }
    .ob-filter-form { display: flex; gap: 0.65rem; align-items: center; flex-wrap: wrap; }
    .ob-search-box { display: flex; align-items: center; gap: 8px; background: #f8fafc; border-radius: var(--ob-r-sm); padding: 0 0.85rem; border: 1.5px solid var(--ob-border); flex: 1; min-width: 260px; transition: all .15s; height: 40px; }
    .ob-search-box:focus-within { border-color: var(--ob-blue); background: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,.08); }
    .ob-search-box svg { color: var(--ob-muted); flex-shrink: 0; }
    .ob-search-input { border: none; background: transparent; font-size: 0.84rem; font-family: inherit; color: var(--ob-text); outline: none; width: 100%; }
    .ob-search-input::placeholder { color: var(--ob-muted); }
    .ob-filter-info { margin-top: 0.65rem; font-size: 0.78rem; color: var(--ob-text2); padding: 0.4rem 0.65rem; background: var(--ob-blue-bg); border-radius: 6px; display: inline-block; }
    .ob-filter-info strong { color: var(--ob-text); }

    /* ── TABLE ── */
    .ob-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .ob-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
    .ob-table thead th { font-size: 0.63rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--ob-muted); padding: 0.75rem 1.125rem; border-bottom: 1.5px solid var(--ob-border); background: #fafbfc; white-space: nowrap; text-align: left; user-select: none; }
    .ob-table tbody tr { transition: background .1s; }
    .ob-table tbody tr:hover { background: #f8fafc; }
    .ob-table tbody td { padding: 0.85rem 1.125rem; font-size: 0.84rem; vertical-align: middle; border-bottom: 1px solid var(--ob-border-light); }
    .ob-table tbody tr:last-child td { border-bottom: none; }
    .ob-table th.c, .ob-table td.c { text-align: center; }

    /* ── CELL: DATE ── */
    .ob-date-main { font-weight: 700; color: var(--ob-text); font-size: 0.84rem; white-space: nowrap; }
    .ob-date-sub { font-size: 0.72rem; color: var(--ob-muted); margin-top: 2px; font-family: monospace; }

    /* ── CELL: REF ── */
    .ob-ref { display: inline-block; padding: 0.2rem 0.55rem; border-radius: 6px; background: #f1f5f9; color: var(--ob-text); font-family: monospace; font-size: 0.78rem; font-weight: 700; letter-spacing: .01em; border: 1px solid var(--ob-border-light); }

    /* ── CELL: PRODUCT ── */
    .ob-prod-name { font-weight: 700; color: var(--ob-text); font-size: 0.84rem; line-height: 1.35; }
    .ob-prod-batch { display: inline-flex; align-items: center; gap: 4px; font-size: 0.7rem; color: var(--ob-muted); margin-top: 3px; background: #f8fafc; padding: 0.1rem 0.4rem; border-radius: 4px; border: 1px solid var(--ob-border-light); font-family: monospace; font-weight: 600; }

    /* ── CELL: QTY ── */
    .ob-qty-out { display: inline-flex; align-items: center; justify-content: center; min-width: 36px; padding: 0.2rem 0.6rem; border-radius: 999px; background: var(--ob-danger-bg); color: var(--ob-danger-text); font-weight: 800; font-size: 0.85rem; border: 1px solid var(--ob-danger-border); }
    .ob-qty-unit { font-size: 0.7rem; font-weight: 600; margin-left: 3px; opacity: .75; }
    .ob-qty-base { font-size: 0.65rem; color: var(--ob-muted); margin-top: 3px; font-weight: 600; font-family: monospace; }

    /* ── CELL: WAREHOUSE ── */
    .ob-wh-name { font-weight: 600; color: var(--ob-text); font-size: 0.84rem; }
    .ob-loc { display: flex; align-items: center; gap: 4px; font-size: 0.72rem; color: var(--ob-muted); margin-top: 3px; font-weight: 500; }

    /* ── CELL: NOTES ── */
    .ob-notes { font-size: 0.8rem; color: var(--ob-text2); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* ── CELL: USER ── */
    .ob-user { display: flex; align-items: center; gap: 7px; }
    .ob-user-avatar { width: 26px; height: 26px; border-radius: 6px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; flex-shrink: 0; }
    .ob-user-name { font-size: 0.8rem; font-weight: 500; color: var(--ob-text2); }

    /* ── ACTIONS ── */
    .ob-actions { display: flex; gap: 5px; justify-content: center; }
    .ob-act-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--ob-r-sm); border: 1px solid var(--ob-border); background: var(--ob-surface); color: var(--ob-muted); transition: all .15s; cursor: pointer; text-decoration: none; }
    .ob-act-view:hover { background: var(--ob-blue-bg); color: var(--ob-blue); border-color: var(--ob-blue-border); }
    .ob-act-del:hover { background: var(--ob-danger-bg); color: var(--ob-danger); border-color: var(--ob-danger-border); }

    /* ── EMPTY STATE ── */
    .ob-empty { text-align: center; padding: 4rem 1.5rem; }
    .ob-empty-icon { width: 64px; height: 64px; border-radius: 50%; background: var(--ob-danger-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; color: var(--ob-danger); opacity: .6; }
    .ob-empty h3 { font-size: 1rem; font-weight: 800; color: var(--ob-text); margin: 0 0 0.35rem; }
    .ob-empty p { font-size: 0.84rem; color: var(--ob-muted); margin: 0 auto 1.5rem; max-width: 380px; line-height: 1.55; }
    .ob-empty-link { display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 700; color: var(--ob-danger); text-decoration: none; padding: 0.5rem 1.25rem; border: 1.5px solid var(--ob-danger-border); border-radius: var(--ob-r-sm); transition: all .15s; }
    .ob-empty-link:hover { background: var(--ob-danger-bg); }

    /* ── PAGINATION ── */
    .ob-pagination { padding: 0.85rem 1.25rem; border-top: 1px solid var(--ob-border-light); background: #fff; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; }
    .ob-pagination-info { font-size: 0.78rem; color: var(--ob-muted); font-weight: 500; }
    .ob-pagination-links nav { display: flex; gap: 2px; }
    .ob-pagination-links nav span,
    .ob-pagination-links nav a { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid var(--ob-border); color: var(--ob-text2); text-decoration: none; transition: all .12s; }
    .ob-pagination-links nav a:hover { background: #f8fafc; color: var(--ob-text); }
    .ob-pagination-links nav span[aria-current="page"] { background: var(--ob-danger); color: #fff; border-color: var(--ob-danger); }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .ob-header { flex-direction: column; align-items: stretch; }
        .ob-btn-danger { width: 100%; justify-content: center; }
        .ob-filter-form { flex-direction: column; align-items: stretch; }
        .ob-search-box { width: 100%; min-width: auto; }
        .ob-pagination { flex-direction: column; align-items: stretch; text-align: center; }
    }
    </style>
    @endpush
</x-app-layout>
