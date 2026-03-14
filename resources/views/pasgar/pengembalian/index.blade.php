<x-app-layout>
    <x-slot name="header">Pengembalian Sisa Barang</x-slot>

    <div class="page-container">
        @if(session('success'))<div class="alert alert-success">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">❌ {{ session('error') }}</div>@endif

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">↩ Pengembalian Sisa Barang Pasgar</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Riwayat pengembalian stok dari kendaraan ke gudang utama</p>
                </div>
                @can('create_pasgar_pengembalian')
                <a href="{{ route('pasgar.pengembalian.create') }}" class="btn-primary">＋ Buat Pengembalian</a>
                @endcan
            </div>

            {{-- Filter --}}
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <form method="GET" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Cari No. Transfer</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="TRF-..." class="form-input" style="width:200px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem;">Status</label>
                        <select name="status" class="form-input" style="width:150px;">
                            <option value="">Semua</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">🔍 Filter</button>
                    <a href="{{ route('pasgar.pengembalian.index') }}" class="btn-secondary btn-sm">Reset</a>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No. Transfer</th>
                            <th>Tanggal</th>
                            <th>Dari (Kendaraan)</th>
                            <th>Ke (Gudang)</th>
                            <th>Jml Item</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $ret)
                        <tr>
                            <td style="font-weight:600; color:#4f46e5;">{{ $ret->transfer_number }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($ret->date)->format('d M Y') }}</td>
                            <td>{{ $ret->fromWarehouse?->name ?? '—' }}</td>
                            <td>{{ $ret->toWarehouse?->name ?? '—' }}</td>
                            <td style="text-align:center;">{{ $ret->items->count() }}</td>
                            <td>
                                @if($ret->status === 'completed')
                                    <span class="badge-success">Selesai</span>
                                @else
                                    <span class="badge-indigo">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pasgar.pengembalian.show', $ret) }}" class="btn-secondary btn-sm">👁 Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:3rem; color:#94a3b8;">
                                <div style="font-size:2rem; margin-bottom:0.5rem;">↩</div>
                                <div>Belum ada pengembalian barang.</div>
                                @can('create_pasgar_pengembalian')
                                <a href="{{ route('pasgar.pengembalian.create') }}" class="btn-primary btn-sm" style="margin-top:0.75rem; display:inline-flex;">+ Buat Sekarang</a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($returns->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9;">{{ $returns->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
