<x-app-layout>
    <x-slot name="header">Data Produk</x-slot>
    <div class="page-container">

        {{-- Premium Page Header --}}
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon indigo">🏷️</div>
                <div>
                    <h1 class="ph-title">Data Produk</h1>
                    <p class="ph-subtitle">Kelola master data barang & multi-satuan harga</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_master_produk')
                <a href="{{ route('products.create') }}" class="btn-primary">
                    ＋ Tambah Produk
                </a>
                <a href="{{ route('products.import') }}" class="btn-secondary">
                    📥 Import CSV
                </a>
                @endcan
            </div>
        </div>

        {{-- Tabbed Navigation --}}
        <div style="display:flex; gap:1.5rem; border-bottom:1px solid #e2e8f0; margin-bottom:1.5rem; padding-bottom:0px;">
            <a href="{{ route('products.index') }}" style="padding-bottom:0.75rem; border-bottom:2px solid #4f46e5; color:#4f46e5; font-weight:600; text-decoration:none; font-size:0.875rem;">
                @can('view_stok_gudang')
                📦 Stok Barang Induk (Master)
                @else
                🏷️ Data Produk
                @endcan
            </a>
            @can('view_stok_gudang')
            <a href="{{ route('gudang.stok-semua') }}" style="padding-bottom:0.75rem; color:#64748b; font-weight:500; text-decoration:none; font-size:0.875rem; transition:color 0.2s;">
                📈 Sebaran Semua Gudang
            </a>
            @endcan
        </div>

        {{-- Search & Filter --}}
        <div class="panel animate-in animate-in-delay-1" style="margin-bottom:1rem;">
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;width:100%;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari nama, SKU, atau barcode..."
                        class="form-input" style="flex:1;min-width:220px;max-width:380px;">
                    <select name="category_id" class="form-input" style="width:200px;">
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
        </div>

        {{-- Data Table --}}
        <div class="panel animate-in animate-in-delay-2">
            <div class="tbl-header">
                <div>
                    <div class="tbl-title">📦 Daftar Produk</div>
                    <div class="tbl-meta">{{ $products->total() }} produk ditemukan</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:44px">#</th>
                            <th>Produk & SKU</th>
                            <th>Kategori</th>
                            <th style="text-align:right;">Harga Jual</th>
                            <th style="text-align:center;">Stok</th>
                            <th style="text-align:center;width:130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $i => $item)
                        <tr>
                            <td class="text-muted" style="font-size:0.75rem;">{{ $products->firstItem() + $i }}</td>
                            <td>
                                <div class="td-main">{{ $item->name }}</div>
                                <div class="td-sub">
                                    <span class="badge badge-indigo">{{ $item->sku }}</span>
                                    @if($item->barcode)
                                        <span style="color:#cbd5e1;margin:0 3px;">·</span>
                                        <span style="font-family:monospace;font-size:0.7rem;color:#94a3b8;">{{ $item->barcode }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="td-main" style="font-size:0.8125rem;">{{ $item->category->name }}</div>
                            </td>
                            <td style="text-align:right;">
                                <span style="font-weight:700;color:#059669;font-size:0.875rem;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                @can('view_stok_gudang')
                                    @php $isLow = $item->stock <= $item->min_stock; @endphp
                                    @if(($maskStock ?? false) === true)
                                        <span class="badge badge-warning badge-dot">Terkunci</span>
                                    @else
                                        <span class="badge {{ $isLow ? 'badge-danger' : 'badge-blue' }} badge-dot">
                                            {{ $item->stock }} {{ $item->unit ? $item->unit->abbreviation : 'pcs' }}
                                        </span>
                                        @if($isLow)<div style="font-size:0.68rem;color:#ef4444;margin-top:2px;">⚠ Min stok</div>@endif
                                    @endif
                                @else
                                    <span class="text-muted" title="Butuh izin: view_stok_gudang">—</span>
                                @endcan
                            </td>
                            <td style="text-align:center;">
                                <div class="act-grp" style="justify-content:center;">
                                    @can('edit_master_produk')
                                    <a href="{{ route('products.edit', $item) }}" class="act-btn act-btn-edit">✏ Edit</a>
                                    @endcan
                                    @can('delete_master_produk')
                                    <form action="{{ route('products.destroy', $item) }}" method="POST"
                                        onsubmit="return confirm('Hapus produk \'{{ $item->name }}\'? Data tidak bisa dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn act-btn-del">🗑</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <span class="empty-state-icon">📦</span>
                                    <div class="empty-state-title">Belum ada produk</div>
                                    <div class="empty-state-desc">Mulai tambahkan produk ke dalam sistem</div>
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
                <div>{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
