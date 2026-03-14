<x-app-layout>
<x-slot name="header">Pesanan Pasgar</x-slot>

<div class="page-header">
    <div>
        <div class="page-header-title">Mutasi / Loading Barang</div>
        <div class="page-header-subtitle">Riwayat pemindahan stok dari Gudang ke Kendaraan Pasgar</div>
    </div>
    <div class="page-header-actions">
        @can('create_pasgar_pesanan')
        <a href="{{ route('pasgar.loadings.create') }}" class="btn-primary">➕ Buat Loading Baru</a>
        @endcan
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon indigo">📦</div>
        <div>
            <div class="stat-label">Total</div>
            <div class="stat-value indigo">{{ $totalCount ?? $loadings->total() }}</div>
            <span class="badge badge-gray">Sesuai filter</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">⏳</div>
        <div>
            <div class="stat-label">Menunggu</div>
            <div class="stat-value amber">{{ $pendingCount ?? 0 }}</div>
            <span class="badge badge-warning">Pending</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald">✅</div>
        <div>
            <div class="stat-label">Selesai</div>
            <div class="stat-value emerald">{{ $approvedCount ?? 0 }}</div>
            <span class="badge badge-success">Approved</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose">🧩</div>
        <div>
            <div class="stat-label">Lainnya</div>
            <div class="stat-value rose">{{ $otherCount ?? 0 }}</div>
            <span class="badge badge-danger">Ditolak / lainnya</span>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <div>
            <div class="panel-title">Daftar Loading</div>
            <div class="panel-subtitle">Cari berdasarkan nomor, gudang, kendaraan, atau pembuat</div>
        </div>
    </div>
    <div class="panel-body">
        <form method="GET" action="{{ route('pasgar.loadings.index') }}" class="form-row" style="margin-bottom: 1rem;">
            <div>
                <label class="form-label">Pencarian</label>
                <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: TRF-2026..., Gudang, Kendaraan, Nama user">
            </div>
            <div>
                <label class="form-label">Status</label>
                @php $st = request('status'); @endphp
                <select name="status" class="form-input">
                    <option value="" {{ $st === null || $st === '' ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ $st === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ $st === 'approved' ? 'selected' : '' }}>Selesai</option>
                    <option value="disiapkan" {{ $st === 'disiapkan' ? 'selected' : '' }}>Disiapkan</option>
                    <option value="rejected" {{ $st === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                <button type="submit" class="btn-primary">🔎 Terapkan</button>
                <a href="{{ route('pasgar.loadings.index') }}" class="btn-secondary">↺ Reset</a>
            </div>
        </form>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Gudang Asal</th>
                        <th>Tujuan (Kendaraan)</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loadings as $loading)
                        <tr>
                            <td>
                                <span class="badge badge-indigo">{{ $loading->transfer_number }}</span>
                            </td>
                            <td>{{ $loading->date?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $loading->fromWarehouse?->name ?? '-' }}</td>
                            <td><span style="font-weight:700;color:#0f172a;">{{ $loading->toWarehouse?->name ?? '-' }}</span></td>
                            <td>
                                @if($loading->status === 'pending')
                                    <span class="badge badge-warning">Menunggu</span>
                                @elseif($loading->status === 'approved')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($loading->status === 'disiapkan')
                                    <span class="badge badge-blue">Disiapkan</span>
                                @elseif($loading->status === 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-gray">{{ $loading->status }}</span>
                                @endif
                            </td>
                            <td>{{ $loading->creator?->name ?? '-' }}</td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                                    <a href="{{ route('pasgar.loadings.show', $loading) }}" class="btn-secondary">👁️ Detail</a>
                                    @if($loading->status === 'pending')
                                        @can('edit_pasgar_pesanan')
                                        <a href="{{ route('pasgar.loadings.edit', $loading) }}" class="btn-primary">✏️ Edit</a>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 2.25rem;">
                                <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                    <div style="font-size:2rem;">📦</div>
                                    <div style="font-weight:900;color:#0f172a;">Belum ada riwayat loading</div>
                                    <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                        Buat loading baru untuk memindahkan stok dari gudang ke kendaraan Pasgar.
                                    </div>
                                    @can('create_pasgar_pesanan')
                                    <a href="{{ route('pasgar.loadings.create') }}" class="btn-primary">➕ Buat Loading Baru</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $loadings->links() }}
        </div>
    </div>
</div>
</x-app-layout>
