<x-app-layout>
    <x-slot name="header">Data Supplier</x-slot>
    <div class="page-container">
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon teal">🏭</div>
                <div>
                    <h1 class="ph-title">Data Supplier</h1>
                    <p class="ph-subtitle">Kelola data pemasok barang</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_master_supplier')
                <a href="{{ route('master.supplier.create') }}" class="btn-primary">＋ Tambah Supplier</a>
                @endcan
            </div>
        </div>

        <div class="panel animate-in animate-in-delay-1">
            <div class="filter-bar">
                <form method="GET" style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="🔍  Cari nama, email, telepon..." class="form-input" style="flex:1;min-width:200px;max-width:300px;">
                    <select name="status" class="form-input" style="width:150px;">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status')==='1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status')==='0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    @if(request('search') || request('status') !== null)
                        <a href="{{ route('master.supplier') }}" class="btn-secondary btn-sm">× Reset</a>
                    @endif
                </form>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:44px">#</th>
                            <th style="width:120px;">Kode Supp</th>
                            <th>Nama Supplier</th>
                            <th>Kontak Person</th>
                            <th>Telepon</th>
                            <th>Kota/Wilayah</th>
                            <th>Status</th>
                            <th style="text-align:center;width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $i => $supplier)
                        <tr>
                            <td class="text-muted" style="font-size:0.75rem;">{{ $suppliers->firstItem() + $i }}</td>
                            <td><span style="font-family:monospace;font-weight:700;color:#64748b;background:#f1f5f9;padding:0.2rem 0.5rem;border-radius:6px;font-size:0.8rem;">{{ $supplier->code ?: '—' }}</span></td>
                            <td>
                                <div class="td-main">{{ $supplier->name }}</div>
                                @if($supplier->email)<div class="td-sub">{{ $supplier->email }}</div>@endif
                            </td>
                            <td>{{ $supplier->contact_person ?: '—' }}</td>
                            <td>{{ $supplier->phone ?: '—' }}</td>
                            <td>{{ $supplier->city ?: '—' }}</td>
                            <td>
                                <span class="badge {{ $supplier->active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $supplier->active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div class="act-grp" style="justify-content:center;">
                                    @can('edit_master_supplier')
                                    <a href="{{ route('master.supplier.edit', $supplier) }}" class="act-btn act-btn-edit">✏ Edit</a>
                                    @endcan
                                    @can('delete_master_supplier')
                                    <form action="{{ route('master.supplier.destroy', $supplier) }}" method="POST"
                                        onsubmit="return confirm('Hapus supplier \'{{ $supplier->name }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn act-btn-del">🗑</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8">
                            <div class="empty-state">
                                <span class="empty-state-icon">🏭</span>
                                <div class="empty-state-title">Belum ada supplier</div>
                                @can('create_master_supplier')
                                <a href="{{ route('master.supplier.create') }}" class="btn-primary btn-sm">＋ Tambah Supplier</a>
                                @endcan
                            </div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($suppliers->hasPages())<div>{{ $suppliers->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
