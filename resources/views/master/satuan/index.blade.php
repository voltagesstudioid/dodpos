<x-app-layout>
    <x-slot name="header">Satuan Barang</x-slot>
    <div class="page-container">
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon amber">⚖️</div>
                <div>
                    <h1 class="ph-title">Satuan Barang</h1>
                    <p class="ph-subtitle">Kelola satuan ukuran (pcs, dus, karton, dll)</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_master_satuan')
                <a href="{{ route('master.satuan.create') }}" class="btn-primary">＋ Tambah Satuan</a>
                @endcan
            </div>
        </div>

        <div class="panel animate-in animate-in-delay-1">
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:0.625rem;align-items:center;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari nama atau singkatan..." class="form-input" style="flex:1;min-width:200px;max-width:320px;">
                    <button type="submit" class="btn-primary btn-sm">Cari</button>
                    @if(request('search'))<a href="{{ route('master.satuan') }}" class="btn-secondary btn-sm">× Reset</a>@endif
                </form>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:44px">#</th>
                            <th>Nama Satuan</th>
                            <th>Singkatan</th>
                            <th>Deskripsi</th>
                            <th style="text-align:center;">Digunakan</th>
                            <th style="text-align:center;width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $i => $unit)
                        <tr>
                            <td class="text-muted" style="font-size:0.75rem;">{{ $units->firstItem() + $i }}</td>
                            <td><span class="td-main">{{ $unit->name }}</span></td>
                            <td><span class="badge badge-indigo">{{ $unit->abbreviation }}</span></td>
                            <td class="text-muted">{{ $unit->description ?: '—' }}</td>
                            <td style="text-align:center;"><span class="badge badge-blue">{{ $unit->products_count }} produk</span></td>
                            <td style="text-align:center;">
                                <div class="act-grp" style="justify-content:center;">
                                    @can('edit_master_satuan')
                                    <a href="{{ route('master.satuan.edit', $unit) }}" class="act-btn act-btn-edit">✏ Edit</a>
                                    @endcan
                                    @can('delete_master_satuan')
                                    <form action="{{ route('master.satuan.destroy', $unit) }}" method="POST"
                                        onsubmit="return confirm('Hapus satuan \'{{ $unit->name }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn act-btn-del">🗑</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6">
                            <div class="empty-state">
                                <span class="empty-state-icon">⚖️</span>
                                <div class="empty-state-title">Belum ada satuan</div>
                                @can('create_master_satuan')
                                <a href="{{ route('master.satuan.create') }}" class="btn-primary btn-sm">＋ Tambah Satuan</a>
                                @endcan
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($units->hasPages())<div>{{ $units->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
