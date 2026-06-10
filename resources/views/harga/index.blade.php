<x-app-layout>
    <x-slot name="header">Daftar Harga</x-slot>

    <div class="hg-page">

        {{-- ─── STAT CARD ─── --}}
        <div class="hg-stat-card">
            <div class="hg-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div>
                <div class="hg-stat-label">Daftar Harga Produk</div>
                <div class="hg-stat-value">{{ $totalCount }} <span class="hg-stat-unit">produk</span></div>
            </div>
            @if(request('search') || request('category_id'))
                <span class="hg-filter-tag">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter Aktif
                </span>
            @endif
        </div>

        {{-- ─── FILTER BAR ─── --}}
        <div class="hg-filter-bar">
            <form method="GET" class="hg-filter-form">
                <div class="hg-input-group">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, SKU, atau barcode..." class="hg-search-input">
                </div>
                <select name="category_id" class="hg-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="hg-btn hg-btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Filter
                </button>
                <a href="{{ route('harga.index') }}" class="hg-btn hg-btn-ghost">Reset</a>
                <button type="button" onclick="window.print()" class="hg-btn hg-btn-ghost">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Cetak
                </button>
                @can('create_master_produk')
                    <a href="{{ route('products.create') }}" class="hg-btn hg-btn-accent">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Harga
                    </a>
                @endcan
            </form>
        </div>

        {{-- ─── PRICE TABLE ─── --}}
        <div class="hg-card">
            <div class="hg-table-wrap">
                <table class="hg-table" id="price-table">
                    <thead>
                        <tr>
                            <th class="hg-th-num" rowspan="2">#</th>
                            <th rowspan="2">Produk</th>
                            <th rowspan="2">Kategori</th>
                            <th colspan="3" class="hg-th-ecer">Harga Eceran</th>
                            <th colspan="3" class="hg-th-gros">Harga Grosir</th>
                            <th class="hg-th-act" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th class="hg-th-ecer-sm">Satuan</th>
                            <th class="hg-th-ecer-sm hg-th-center">Isi</th>
                            <th class="hg-th-ecer-sm hg-th-price">Harga</th>
                            <th class="hg-th-gros-sm">Satuan</th>
                            <th class="hg-th-gros-sm hg-th-center">Isi</th>
                            <th class="hg-th-gros-sm hg-th-price">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $i => $product)
                            @php
                                $units    = $product->unitConversions->sortBy('conversion_factor');
                                $baseUnit = $units->firstWhere('is_base_unit', true) ?? $units->first();
                                $rowCount = max(1, $units->count());
                            @endphp

                            @if($units->isEmpty())
                                <tr>
                                    <td class="hg-num">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="hg-prod-name">{{ $product->name }}</div>
                                        <div class="hg-prod-sku">{{ $product->sku }}</div>
                                    </td>
                                    <td class="hg-cat">{{ $product->category?->name ?? '-' }}</td>
                                    <td colspan="6" class="hg-empty">Belum ada data satuan &amp; harga</td>
                                    <td class="hg-act">
                                        @can('edit_master_produk')
                                            <a href="{{ route('products.edit', $product) }}" class="hg-act-btn" title="Edit Harga">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                        @endcan
                                        @can('delete_master_produk')
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="hg-del-form" onsubmit="return confirm('Yakin menghapus produk beserta harganya?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="hg-act-btn hg-act-del" title="Hapus">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @else
                                @foreach($units as $j => $uc)
                                    <tr class="{{ $uc->is_base_unit ? 'hg-base-row' : '' }}">
                                        @if($j === 0)
                                            <td class="hg-num" rowspan="{{ $rowCount }}">{{ $i + 1 }}</td>
                                            <td rowspan="{{ $rowCount }}" class="hg-prod-cell">
                                                <div class="hg-prod-name">{{ $product->name }}</div>
                                                <div class="hg-prod-sku">{{ $product->sku }}</div>
                                            </td>
                                            <td class="hg-cat" rowspan="{{ $rowCount }}">{{ $product->category?->name ?? '-' }}</td>
                                        @endif

                                        {{-- Eceran --}}
                                        <td class="hg-ecer-unit">
                                            {{ $uc->unit->name ?? '-' }}
                                            @if($uc->is_base_unit) <span class="hg-base-tag">Dasar</span> @endif
                                        </td>
                                        <td class="hg-ecer-factor">{{ $uc->conversion_factor }}x</td>
                                        <td class="hg-ecer-price hg-price">
                                            @if($uc->sell_price_ecer > 0)
                                                Rp {{ number_format((float) $uc->sell_price_ecer, 0, ',', '.') }}
                                            @else
                                                <span class="hg-na">-</span>
                                            @endif
                                        </td>

                                        {{-- Grosir --}}
                                        <td class="hg-gros-unit">{{ $uc->unit->name ?? '-' }}</td>
                                        <td class="hg-gros-factor">{{ $uc->conversion_factor }}x</td>
                                        <td class="hg-gros-price hg-price">
                                            @if($uc->sell_price_grosir > 0)
                                                Rp {{ number_format((float) $uc->sell_price_grosir, 0, ',', '.') }}
                                            @else
                                                <span class="hg-na">-</span>
                                            @endif
                                        </td>

                                        @if($j === 0)
                                            <td class="hg-act" rowspan="{{ $rowCount }}">
                                                @can('edit_master_produk')
                                                    <a href="{{ route('products.edit', $product) }}" class="hg-act-btn" title="Edit Harga">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </a>
                                                @endcan
                                                @can('delete_master_produk')
                                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="hg-del-form" onsubmit="return confirm('Yakin menghapus produk beserta harganya?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="hg-act-btn hg-act-del" title="Hapus">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Row separator --}}
                            <tr class="hg-sep"><td colspan="10"></td></tr>

                        @empty
                            <tr>
                                <td colspan="10" class="hg-empty-main">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    <p>Tidak ada produk yang sesuai dengan filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Legend --}}
        <div class="hg-legend">
            <span class="hg-legend-item"><span class="hg-legend-dot hg-dot-base"></span>Baris kuning = satuan dasar (terkecil)</span>
            <span class="hg-legend-sep">|</span>
            <span>Harga per: {{ now()->format('d M Y, H:i') }}</span>
        </div>

    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .hg-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem 4rem; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #0f172a; }

        /* ── Stat Card ── */
        .hg-stat-card { display: flex; align-items: center; gap: 1rem; background: #fff; border: 1px solid #e2e8f0; border-left: 4px solid #4f46e5; border-radius: 12px; padding: 1.15rem 1.5rem; margin-bottom: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .hg-stat-icon { width: 44px; height: 44px; border-radius: 10px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .hg-stat-label { font-size: 0.72rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; }
        .hg-stat-value { font-size: 1.5rem; font-weight: 900; color: #0f172a; letter-spacing: -0.02em; font-family: ui-monospace, 'Cascadia Code', 'Fira Code', monospace; }
        .hg-stat-unit { font-size: 0.85rem; font-weight: 600; color: #64748b; }
        .hg-filter-tag { display: inline-flex; align-items: center; gap: 4px; font-size: 0.72rem; font-weight: 700; color: #4f46e5; background: #e0e7ff; padding: 3px 10px; border-radius: 99px; margin-left: auto; }

        /* ── Filter Bar ── */
        .hg-filter-bar { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .hg-filter-form { display: flex; gap: 0.65rem; flex-wrap: wrap; align-items: center; }
        .hg-input-group { display: flex; align-items: center; gap: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0 0.75rem; flex: 1; min-width: 200px; color: #94a3b8; }
        .hg-input-group:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.08); }
        .hg-search-input { border: none; background: transparent; padding: 0.6rem 0; font-size: 0.85rem; font-weight: 500; color: #0f172a; outline: none; width: 100%; font-family: inherit; }
        .hg-select { border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 0.75rem; font-size: 0.85rem; font-weight: 600; color: #0f172a; background: #f8fafc; outline: none; font-family: inherit; cursor: pointer; min-width: 160px; }
        .hg-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.08); }

        .hg-btn { display: inline-flex; align-items: center; gap: 5px; padding: 0.6rem 1rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.15s; border: 1px solid transparent; text-decoration: none; white-space: nowrap; font-family: inherit; }
        .hg-btn-primary { background: #4f46e5; color: #fff; box-shadow: 0 2px 6px rgba(79,70,229,0.15); }
        .hg-btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .hg-btn-ghost { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
        .hg-btn-ghost:hover { background: #f1f5f9; color: #0f172a; }
        .hg-btn-accent { background: #4f46e5; color: #fff; }
        .hg-btn-accent:hover { background: #4338ca; transform: translateY(-1px); }

        /* ── Card ── */
        .hg-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); overflow: hidden; }

        /* ── Table ── */
        .hg-table-wrap { width: 100%; overflow-x: auto; }
        .hg-table { width: 100%; border-collapse: collapse; min-width: 800px; }

        .hg-table thead th { background: #f8fafc; padding: 0.7rem 0.75rem; text-align: left; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #e2e8f0; letter-spacing: 0.03em; }
        .hg-table tbody td { padding: 0.6rem 0.75rem; font-size: 0.85rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

        .hg-th-num { width: 40px; text-align: center; border-right: 2px solid #e2e8f0; }
        .hg-th-act { width: 70px; text-align: center; }
        .hg-th-center { text-align: center; }
        .hg-th-price { text-align: right; border-right: 2px solid #e2e8f0; }

        .hg-th-ecer { background: #f0fdf4; color: #15803d; text-align: center; border-bottom: 1px solid #bbf7d0; }
        .hg-th-gros { background: #eff6ff; color: #1d4ed8; text-align: center; border-bottom: 1px solid #bfdbfe; border-right: 2px solid #e2e8f0; }
        .hg-th-ecer-sm { background: #f0fdf4; color: #15803d; font-size: 0.65rem; }
        .hg-th-gros-sm { background: #eff6ff; color: #1d4ed8; font-size: 0.65rem; }

        /* Cells */
        .hg-num { text-align: center; color: #94a3b8; font-size: 0.8rem; font-weight: 600; border-right: 2px solid #f1f5f9; }
        .hg-prod-cell { vertical-align: middle; }
        .hg-prod-name { font-weight: 700; font-size: 0.88rem; color: #0f172a; }
        .hg-prod-sku { font-size: 0.7rem; color: #94a3b8; font-weight: 500; font-family: ui-monospace, 'Cascadia Code', monospace; }
        .hg-cat { font-size: 0.8rem; color: #64748b; font-weight: 600; border-right: 2px solid #f1f5f9; vertical-align: middle; }

        /* Eceran cells */
        .hg-ecer-unit { background: #fafffe; font-weight: 600; }
        .hg-ecer-factor { background: #fafffe; text-align: center; color: #64748b; font-size: 0.8rem; }
        .hg-ecer-price { background: #fafffe; text-align: right; font-weight: 800; color: #15803d; border-right: 2px solid #e2e8f0; }

        /* Grosir cells */
        .hg-gros-unit { background: #f8faff; font-weight: 600; color: #1d4ed8; }
        .hg-gros-factor { background: #f8faff; text-align: center; color: #64748b; font-size: 0.8rem; }
        .hg-gros-price { background: #f8faff; text-align: right; font-weight: 800; color: #1d4ed8; border-right: 2px solid #e2e8f0; }

        .hg-price { font-family: ui-monospace, 'Cascadia Code', 'Fira Code', Consolas, monospace; }
        .hg-na { color: #d1d5db; font-weight: 400; }
        .hg-base-tag { font-size: 0.58rem; background: #fef9c3; color: #a16207; padding: 1px 5px; border-radius: 4px; font-weight: 700; margin-left: 3px; text-transform: uppercase; letter-spacing: 0.03em; }

        /* Base row highlight */
        .hg-base-row td.hg-ecer-unit, .hg-base-row td.hg-ecer-factor, .hg-base-row td.hg-ecer-price { background: #fefce8; }
        .hg-base-row td.hg-gros-unit, .hg-base-row td.hg-gros-factor, .hg-base-row td.hg-gros-price { background: #fef9f0; }

        .hg-empty { text-align: center; color: #94a3b8; font-size: 0.82rem; border-right: 2px solid #f1f5f9; }
        .hg-empty-main { text-align: center; padding: 3rem !important; color: #94a3b8; }
        .hg-empty-main svg { margin-bottom: 0.5rem; opacity: 0.4; }
        .hg-empty-main p { margin: 0; font-weight: 600; }

        /* Separator */
        .hg-sep td { height: 3px; background: #f1f5f9; padding: 0 !important; border: none !important; }

        /* Actions */
        .hg-act { text-align: center; vertical-align: middle; }
        .hg-act-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #64748b; cursor: pointer; transition: all 0.15s; text-decoration: none; margin: 2px; }
        .hg-act-btn:hover { background: #f1f5f9; color: #4f46e5; border-color: #c7d2fe; }
        .hg-act-del:hover { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
        .hg-del-form { display: inline; margin: 0; }

        /* ── Legend ── */
        .hg-legend { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.75rem; font-size: 0.72rem; color: #94a3b8; font-weight: 500; justify-content: flex-end; }
        .hg-legend-item { display: inline-flex; align-items: center; gap: 4px; }
        .hg-legend-dot { width: 10px; height: 10px; border-radius: 3px; display: inline-block; }
        .hg-dot-base { background: #fef9c3; border: 1px solid #fde047; }
        .hg-legend-sep { color: #e2e8f0; }

        /* ── Print ── */
        @media print {
            .sidebar, .topbar, .hg-filter-bar, .hg-stat-card, .hg-legend { display: none !important; }
            .main-wrapper { margin-left: 0 !important; padding: 0 !important; }
            .hg-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
            .hg-page { padding: 0.5rem !important; }
            .hg-table th, .hg-table td { font-size: 0.7rem !important; padding: 0.3rem 0.5rem !important; }
            .hg-act { display: none !important; }
            body { font-size: 11px; }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .hg-filter-form { flex-direction: column; }
            .hg-input-group { min-width: 100%; }
            .hg-select { width: 100%; }
            .hg-stat-card { flex-wrap: wrap; }
            .hg-filter-tag { margin-left: 0; }
        }
    </style>
    @endpush
</x-app-layout>
