<x-app-layout>
    <x-slot name="header">Data Kendaraan Operasional</x-slot>
    <div class="page-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <div>
                <h1 style="font-size:1.375rem; font-weight:700; color:#1e293b; margin:0;">🚚 Data Kendaraan</h1>
                <p style="color:#64748b; font-size:0.875rem; margin:0.25rem 0 0;">Kelola armada kendaraan untuk operasional toko.</p>
            </div>
            @can('create_kendaraan_operasional')
            <a href="{{ route('operasional.kendaraan.create') }}" class="btn-primary">+ Tambah Kendaraan</a>
            @else
            <span class="btn-secondary" title="Butuh izin: create_kendaraan_operasional" style="opacity:0.6;cursor:not-allowed;">+ Tambah Kendaraan</span>
            @endcan
        </div>

        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif

        <div class="card">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Plat Nomor</th>
                            <th>Jenis/Tipe</th>
                            <th>Keterangan</th>
                            <th style="text-align:center; width:150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $index => $kendaraan)
                        <tr>
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td style="font-weight:600; color:#1e293b;">{{ strtoupper($kendaraan->license_plate) }}</td>
                            <td>{{ $kendaraan->type ?? '-' }}</td>
                            <td style="color:#64748b;">{{ $kendaraan->description ?? '-' }}</td>
                            <td style="text-align:center;">
                                <div style="display:flex; gap:0.5rem; justify-content:center;">
                                    @can('edit_kendaraan_operasional')
                                        <a href="{{ route('operasional.kendaraan.edit', $kendaraan->id) }}" class="btn-sm btn-warning">Edit</a>
                                    @else
                                        <span class="btn-sm btn-secondary" title="Butuh izin: edit_kendaraan_operasional" style="opacity:0.6;cursor:not-allowed;">Edit</span>
                                    @endcan
                                    @can('delete_kendaraan_operasional')
                                        <form action="{{ route('operasional.kendaraan.destroy', $kendaraan->id) }}" method="POST" onsubmit="return confirm('Hapus kendaraan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-sm btn-danger">Hapus</button>
                                        </form>
                                    @else
                                        <span class="btn-sm btn-secondary" title="Butuh izin: delete_kendaraan_operasional" style="opacity:0.6;cursor:not-allowed;">Hapus</span>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:2rem; color:#94a3b8;">
                                Belum ada data kendaraan.
                             @can('create_kendaraan_operasional')
                             <a href="{{ route('operasional.kendaraan.create') }}" style="color:#6366f1;">Tambah sekarang →</a>
                             @else
                             <span style="color:#94a3b8;" title="Butuh izin: create_kendaraan_operasional">Tidak punya izin untuk menambah</span>
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
