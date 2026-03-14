<x-app-layout>
<x-slot name="header">Kendaraan & Tim Pasgar</x-slot>

<style>
.vehicles-page{max-width:min(1200px,100%);margin:0 auto;padding:0 1rem;}
.vehicles-page .table-wrapper{width:100%;overflow:auto;}
.vehicles-cards{display:none;flex-direction:column;gap:0.75rem;}
.vehicle-card{border:1px solid #e2e8f0;border-radius:14px;background:#ffffff;padding:1rem;}
.vehicle-card-top{display:flex;justify-content:space-between;gap:0.75rem;align-items:flex-start;flex-wrap:wrap;}
.vehicle-card-title{display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;}
.vehicle-card-meta{display:grid;grid-template-columns:1fr;gap:0.35rem;margin-top:0.75rem;color:#64748b;font-size:0.85rem;}
.vehicle-card-actions{display:flex;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;margin-top:0.9rem;}
.vehicle-card-actions form{margin:0;}
@media (max-width: 960px){
    .vehicles-page{padding:0 0.75rem;}
}
@media (max-width: 860px){
    .vehicles-page .table-wrapper{display:none;}
    .vehicles-cards{display:flex;}
    .vehicles-page .form-row{display:flex;flex-direction:column;gap:0.75rem;}
    .vehicles-page .page-header{gap:0.75rem;align-items:flex-start;}
    .vehicles-page .page-header-actions{width:100%;}
    .vehicles-page .page-header-actions .btn-primary{width:100%;justify-content:center;}
    .vehicles-page .stat-grid{grid-template-columns:repeat(2,minmax(0,1fr));}
}
@media (max-width: 520px){
    .vehicles-page .stat-grid{grid-template-columns:1fr;}
    .vehicle-card-actions{justify-content:stretch;}
    .vehicle-card-actions a,.vehicle-card-actions button{width:100%;justify-content:center;}
}
</style>

<div class="page-container vehicles-page">
    <div class="page-header">
        <div>
            <div class="page-header-title">Kendaraan &amp; Tim Pasgar</div>
            <div class="page-header-subtitle">Kelola mobile warehouse untuk pasukan penjualan</div>
        </div>
        <div class="page-header-actions">
            @can('create_pasgar_kendaraan')
            <a href="{{ route('pasgar.vehicles.create') }}" class="btn-primary">➕ Tambah Kendaraan</a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div>
    @endif

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon indigo">🚚</div>
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value indigo">{{ $totalCount ?? $vehicles->total() }}</div>
                <span class="badge badge-gray">Sesuai filter</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">🔗</div>
            <div>
                <div class="stat-label">Tertaut Gudang</div>
                <div class="stat-value emerald">{{ $linkedCount ?? 0 }}</div>
                <span class="badge badge-success">Linked</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rose">⚠️</div>
            <div>
                <div class="stat-label">Belum Tertaut</div>
                <div class="stat-value rose">{{ $unlinkedCount ?? 0 }}</div>
                <span class="badge badge-danger">Unlinked</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">📦</div>
            <div>
                <div class="stat-label">Gudang Virtual</div>
                <div class="stat-value blue">{{ $linkedCount ?? 0 }}</div>
                <span class="badge badge-blue">VH-*</span>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Pencarian</div>
                <div class="panel-subtitle">Cari plat nomor, tipe, deskripsi, atau kode gudang virtual</div>
            </div>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('pasgar.vehicles.index') }}" class="form-row">
                <div>
                    <label class="form-label">Kata Kunci</label>
                    <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: BK 1010, Mobil Box, VH-BK1010">
                </div>
                <div>
                    <label class="form-label">Taut Gudang</label>
                    @php $link = request('link'); @endphp
                    <select name="link" class="form-input">
                        <option value="" {{ $link === null || $link === '' ? 'selected' : '' }}>Semua</option>
                        <option value="linked" {{ $link === 'linked' ? 'selected' : '' }}>Sudah tertaut</option>
                        <option value="unlinked" {{ $link === 'unlinked' ? 'selected' : '' }}>Belum tertaut</option>
                    </select>
                </div>
                <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                    <button type="submit" class="btn-primary">🔎 Terapkan</button>
                    <a href="{{ route('pasgar.vehicles.index') }}" class="btn-secondary">↺ Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">Daftar Kendaraan</div>
                <div class="panel-subtitle">Data kendaraan terbaru</div>
            </div>
        </div>
        <div class="panel-body">
            <div class="vehicles-cards">
                @forelse ($vehicles as $vehicle)
                    <div class="vehicle-card">
                        <div class="vehicle-card-top">
                            <div style="min-width:0;">
                                <div class="vehicle-card-title">
                                    <span class="badge badge-indigo">{{ $vehicle->license_plate }}</span>
                                    @if($vehicle->type)
                                        <span class="badge badge-gray">{{ $vehicle->type }}</span>
                                    @endif
                                </div>
                                <div class="vehicle-card-meta">
                                    <div>
                                        <span style="font-weight:900;color:#334155;">Gudang:</span>
                                        @if($vehicle->warehouse)
                                            <span class="badge badge-blue">{{ $vehicle->warehouse->code }}</span>
                                        @else
                                            <span class="badge badge-danger">Belum Tertaut</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span style="font-weight:900;color:#334155;">Keterangan:</span>
                                        <span>{{ $vehicle->description ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="vehicle-card-actions">
                                @can('edit_pasgar_kendaraan')
                                <a href="{{ route('pasgar.vehicles.edit', $vehicle) }}" class="btn-primary">✏️ Edit</a>
                                @endcan
                                @can('delete_pasgar_kendaraan')
                                <form action="{{ route('pasgar.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">🗑️ Hapus</button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="vehicle-card">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                            <div style="font-size:2rem;">🚚</div>
                            <div style="font-weight:900;color:#0f172a;">Belum ada data kendaraan</div>
                            <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                Tambahkan kendaraan untuk membuat gudang virtual dan mengelola stok Pasgar.
                            </div>
                            @can('create_pasgar_kendaraan')
                            <a href="{{ route('pasgar.vehicles.create') }}" class="btn-primary">➕ Tambah Kendaraan</a>
                            @endcan
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="table-wrapper">
                <table class="data-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Plat Nomor</th>
                            <th>Tipe Kendaraan</th>
                            <th>Gudang Virtual</th>
                            <th>Keterangan (Tim)</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td>
                                    <span class="badge badge-indigo">{{ $vehicle->license_plate }}</span>
                                </td>
                                <td>
                                    @if($vehicle->type)
                                        <span class="badge badge-gray">{{ $vehicle->type }}</span>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vehicle->warehouse)
                                        <span class="badge badge-blue">{{ $vehicle->warehouse->code }}</span>
                                    @else
                                        <span class="badge badge-danger">Belum Tertaut</span>
                                    @endif
                                </td>
                                <td style="color:#64748b;">{{ $vehicle->description ?: '-' }}</td>
                                <td style="text-align:right;">
                                    <div style="display:inline-flex;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                                        @can('edit_pasgar_kendaraan')
                                        <a href="{{ route('pasgar.vehicles.edit', $vehicle) }}" class="btn-primary">✏️ Edit</a>
                                        @endcan
                                        @can('delete_pasgar_kendaraan')
                                        <form action="{{ route('pasgar.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">🗑️ Hapus</button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 2.25rem;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                        <div style="font-size:2rem;">🚚</div>
                                        <div style="font-weight:900;color:#0f172a;">Belum ada data kendaraan</div>
                                        <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                            Tambahkan kendaraan untuk membuat gudang virtual dan mengelola stok Pasgar.
                                        </div>
                                        @can('create_pasgar_kendaraan')
                                        <a href="{{ route('pasgar.vehicles.create') }}" class="btn-primary">➕ Tambah Kendaraan</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>
