<x-app-layout>
    <x-slot name="header">
        <div class="ph">
            <div class="ph-left">
                <div class="ph-icon amber">💰</div>
                <div>
                    <div class="ph-breadcrumb">
                        <a href="{{ route('mineral.dashboard') }}">Mineral</a>
                        <span class="ph-breadcrumb-sep">/</span>
                        <span>Validasi Setoran Akhir Admin/SPV</span>
                    </div>
                    <h2 class="ph-title">Rekap Setoran Sales Mineral</h2>
                    <p class="ph-subtitle">Validasi Laporan Keuangan dan Barang Sisa Fisik armada hari ini.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="card p-0 mb-3">
        <div class="form-card-header">
            <div class="form-card-icon amber">💵</div>
            <div>
                <h3 class="form-card-title">Daftar Transmisi Setoran Sales (Masuk)</h3>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">ID Setoran</th>
                        <th width="15%">Waktu Pengajuan</th>
                        <th width="20%">Nama Sales</th>
                        <th width="20%">Total Tagihan Kasir</th>
                        <th width="15%">Disetor Cash</th>
                        <th width="10%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($setorans as $setoran)
                        <tr>
                            <td>
                                <div class="td-main">ST-M{{ str_pad($setoran->id, 5, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td>
                                <div class="td-main">{{ $setoran->created_at->format('d/m/Y') }}</div>
                                <div class="td-sub">{{ $setoran->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="td-main font-bold">{{ $setoran->sales->name ?? 'Sales Dihapus' }}</div>
                            </td>
                            <td>
                                <div class="td-main font-bold">Rp {{ number_format($setoran->expected_cash + $setoran->expected_tempo, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <div class="td-main font-bold text-green">Rp {{ number_format($setoran->actual_cash, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                @if($setoran->status == 'pending')
                                    <span class="badge badge-warning">Butuh Validasi</span>
                                @else
                                    <span class="badge badge-success">Ter-Verifikasi</span>
                                    <div class="td-sub mt-1">Oleh: {{ $setoran->verifier->name ?? '-' }}</div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('mineral.setoran.show', $setoran->id) }}" class="act-btn btn-sm {{ $setoran->status == 'pending' ? 'act-btn-edit' : 'act-btn-view' }}">
                                    @if($setoran->status == 'pending') Validasi Sekrang @else Lihat Record @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <span class="empty-state-icon">💤</span>
                                <div class="empty-state-title">Belum ada Setoran Masuk dari Aplikasi Sales</div>
                                <div class="empty-state-desc">Setoran biasanya dikirim Sales Mineral jam saat sore/malam usai muter armada rute harian.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-2 border-t border-gray-100">
            {{ $setorans->links() }}
        </div>
    </div>
</x-app-layout>
