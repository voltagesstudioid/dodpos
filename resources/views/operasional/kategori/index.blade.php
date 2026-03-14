<x-app-layout>
    <x-slot name="header">Kategori Operasional</x-slot>
    <div class="page-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:700; color:#1e293b; margin:0;">🗂️ Kategori Operasional</h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.25rem 0 0;">Kelola kategori untuk pengeluaran operasional toko.</p>
            </div>
            @can('create_kategori_operasional')
            <a href="{{ route('operasional.kategori.create') }}" class="btn-primary">+ Tambah Kategori</a>
            @else
            <span class="btn-secondary" title="Butuh izin: create_kategori_operasional" style="opacity:0.6;cursor:not-allowed;">+ Tambah Kategori</span>
            @endcan
        </div>

        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif

        <div class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th style="text-align:center; width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $index => $kategori)
                        <tr>
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td style="font-weight:600; color:#1e293b;">{{ $kategori->name }}</td>
                            <td style="color:#64748b;">{{ $kategori->description ?? '-' }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex; gap:0.5rem; justify-content:center;">
                                    @can('edit_kategori_operasional')
                                        <a href="{{ route('operasional.kategori.edit', $kategori->id) }}" class="btn-sm btn-warning">Edit</a>
                                    @else
                                        <span class="btn-sm btn-secondary" title="Butuh izin: edit_kategori_operasional" style="opacity:0.6;cursor:not-allowed;">Edit</span>
                                    @endcan
                                    @can('delete_kategori_operasional')
                                        <form action="{{ route('operasional.kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-sm btn-danger">Hapus</button>
                                        </form>
                                    @else
                                        <span class="btn-sm btn-secondary" title="Butuh izin: delete_kategori_operasional" style="opacity:0.6;cursor:not-allowed;">Hapus</span>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:2rem; color:#94a3b8;">
                                Belum ada kategori operasional.
                             @can('create_kategori_operasional')
                             <a href="{{ route('operasional.kategori.create') }}" style="color:#6366f1;">Tambah sekarang →</a>
                             @else
                             <span style="color:#94a3b8;" title="Butuh izin: create_kategori_operasional">Tidak punya izin untuk menambah</span>
                             @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
