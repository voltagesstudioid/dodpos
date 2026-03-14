<x-app-layout>
    <x-slot name="header">Data Gudang</x-slot>

    <div class="page-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:700; color:#1e293b; margin:0;">🏢 Data Gudang</h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.25rem 0 0;">Kelola titik penyimpanan barang/stok</p>
            </div>
            @can('create_master_gudang')
            <a href="{{ route('master.gudang.create') }}" class="btn-primary">+ Tambah Gudang</a>
            @endcan
        </div>

        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif

        <div class="card" style="padding:1rem; margin-bottom:1rem;">
            <form method="GET" style="display:flex; gap:0.75rem; align-items:center;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode gudang..." class="form-input" style="flex:1; max-width:320px;">
                <button type="submit" class="btn-primary">Cari</button>
                @if(request('search')) <a href="{{ route('master.gudang') }}" class="btn-secondary">Reset</a> @endif
            </form>
        </div>

        <div class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Kode</th>
                            <th>Nama Gudang</th>
                            <th>Alamat & PIC</th>
                            <th>Status</th>
                            <th style="text-align:center; width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warehouses as $i => $gudang)
                        <tr>
                            <td class="text-muted">{{ $warehouses->firstItem() + $i }}</td>
                            <td><span class="badge-indigo">{{ $gudang->code ?: '-' }}</span></td>
                            <td>
                                <div style="font-weight:600; color:#1e293b;">{{ $gudang->name }}</div>
                                @if($gudang->phone)<div style="font-size:0.75rem; color:#64748b;">📞 {{ $gudang->phone }}</div>@endif
                            </td>
                            <td>
                                <div style="font-size:0.875rem;">{{ $gudang->address ?: '-' }}</div>
                                <div style="font-size:0.75rem; color:#64748b; margin-top:0.25rem;">PIC: {{ $gudang->pic ?: '-' }}</div>
                            </td>
                            <td>
                                @if($gudang->active)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex; gap:0.5rem; justify-content:center;">
                                    @can('edit_master_gudang')
                                    <a href="{{ route('master.gudang.edit', $gudang) }}" class="btn-sm btn-warning">Edit</a>
                                    @endcan
                                    @can('delete_master_gudang')
                                    <form action="{{ route('master.gudang.destroy', $gudang) }}" method="POST" onsubmit="return confirm('Hapus gudang {{ $gudang->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-sm btn-danger">Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:2rem; color:#94a3b8;">
                                Belum ada data gudang.
                                @can('create_master_gudang')
                                <a href="{{ route('master.gudang.create') }}" style="color:#6366f1;">Tambah sekarang →</a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($warehouses->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f1f5f9;">{{ $warehouses->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
