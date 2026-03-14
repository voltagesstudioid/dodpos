<x-app-layout>
    <x-slot name="header">
        <div class="ph">
            <div class="ph-left">
                <div class="ph-icon teal">🚚</div>
                <div>
                    <div class="ph-breadcrumb">
                        <a href="{{ route('mineral.dashboard') }}">Mineral</a>
                        <span class="ph-breadcrumb-sep">/</span>
                        <span>Surat Jalan Loading</span>
                    </div>
                    <h2 class="ph-title">Loading Armada</h2>
                    <p class="ph-subtitle">Surat Jalan untuk memindahkan stok Gudang ke Mobil / Pickup Sales.</p>
                </div>
            </div>
            <div class="ph-actions">
                @can('create_mineral_loading')
                <a href="{{ route('mineral.loading.create') }}" class="btn-primary">
                    <span style="font-size:1.1rem">+</span> Buat Surat Jalan Baru
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="card p-0 mb-3">
        <div class="form-card-header">
            <div class="form-card-icon teal">📋</div>
            <div>
                <h3 class="form-card-title">Riwayat Loading Armada (Surat Jalan)</h3>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">No. SJ</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Sales / Driver</th>
                        <th width="20%">Status</th>
                        <th width="20%">Admin Pembuat</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loadings as $sj)
                        <tr>
                            <td>
                                <div class="td-main">SJ-M{{ str_pad($sj->id, 5, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td>
                                <div class="td-main">{{ \Carbon\Carbon::parse($sj->date)->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                <div class="td-main">{{ $sj->sales->name ?? 'Sales Dihapus' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-success">Selesai (Verified)</span>
                            </td>
                            <td>
                                <div class="td-sub">{{ $sj->admin->name ?? '-' }}</div>
                            </td>
                            <td>
                                <a href="{{ route('mineral.loading.show', $sj->id) }}" class="act-btn act-btn-view">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <span class="empty-state-icon">📄</span>
                                <div class="empty-state-title">Belum ada Surat Jalan (Loading)</div>
                                <div class="empty-state-desc">Pindahkan stok gudang ke armada sales menggunakan menu di atas.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-2 border-t border-gray-100">
            {{ $loadings->links() }}
        </div>
    </div>
</x-app-layout>
