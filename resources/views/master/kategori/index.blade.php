<x-app-layout>
    <x-slot name="header">Kategori Barang</x-slot>
    <div class="page-container">
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon indigo">🗂️</div>
                <div>
                    <h1 class="ph-title">Kategori Barang</h1>
                    <p class="ph-subtitle">Kelola pengelompokan produk</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_master_kategori')
                <a href="{{ route('master.kategori.create') }}" class="btn-primary">＋ Tambah Kategori</a>
                @endcan
            </div>
        </div>

        <div class="panel animate-in animate-in-delay-1">
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari kategori..." class="form-input" style="flex:1;min-width:200px;max-width:320px;">
                    <button type="submit" class="btn-primary btn-sm">Cari</button>
                    @if(request('search'))<a href="{{ route('master.kategori') }}" class="btn-secondary btn-sm">× Reset</a>@endif
                </form>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:44px">#</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th style="text-align:center;">Jumlah Produk</th>
                            <th style="text-align:center;width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $i => $kategori)
                        <tr>
                            <td class="text-muted" style="font-size:0.75rem;">{{ $kategoris->firstItem() + $i }}</td>
                            <td><span class="td-main">{{ $kategori->name }}</span></td>
                            <td class="text-muted">{{ $kategori->description ?: '—' }}</td>
                            <td style="text-align:center;">
                                <span class="badge badge-blue">{{ $kategori->products_count }} produk</span>
                            </td>
                            <td style="text-align:center;">
                                <div class="act-grp" style="justify-content:center;">
                                    @can('edit_master_kategori')
                                    <a href="{{ route('master.kategori.edit', $kategori) }}" class="act-btn act-btn-edit">✏ Edit</a>
                                    @endcan
                                    @can('delete_master_kategori')
                                    <form action="{{ route('master.kategori.destroy', $kategori) }}" method="POST"
                                        onsubmit="return confirm('Hapus kategori \'{{ $kategori->name }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn act-btn-del">🗑</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5">
                            <div class="empty-state">
                                <span class="empty-state-icon">🗂️</span>
                                <div class="empty-state-title">Belum ada kategori</div>
                                @can('create_master_kategori')
                                <a href="{{ route('master.kategori.create') }}" class="btn-primary btn-sm">＋ Tambah Kategori</a>
                                @endcan
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($kategoris->hasPages())<div>{{ $kategoris->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
