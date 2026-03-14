<x-app-layout>
    <x-slot name="header">
        Dashboard Gula
    </x-slot>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Dashboard Komoditas Gula</div>
                <div class="page-header-subtitle">Pantau stok gudang, armada aktif, dan muatan berjalan</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('gula.stok.index') }}" class="btn-secondary">📦 Stok Gudang</a>
                <a href="{{ route('gula.loading.index') }}" class="btn-primary">🚚 Loading</a>
            </div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon blue">📦</div>
                <div>
                    <div class="stat-label">Global Stok Karungan</div>
                    <div class="stat-value blue">{{ number_format((int) $globalKarung, 0, ',', '.') }} <span style="font-size:1rem;color:#64748b;">Karung</span></div>
                    <span class="badge badge-blue">Gudang Induk</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon emerald">🍬</div>
                <div>
                    <div class="stat-label">Global Stok Eceran</div>
                    <div class="stat-value emerald">{{ number_format((int) $globalEceran, 0, ',', '.') }} <span style="font-size:1rem;color:#64748b;">Bks</span></div>
                    <span class="badge badge-success">1kg</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon indigo">📦</div>
                <div>
                    <div class="stat-label">Global Stok Bal</div>
                    <div class="stat-value indigo">{{ number_format((int) $globalBal, 0, ',', '.') }} <span style="font-size:1rem;color:#64748b;">Bal</span></div>
                    <span class="badge badge-indigo">Gudang Induk</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon amber">🚚</div>
                <div>
                    <div class="stat-label">Armada Aktif Muatan</div>
                    <div class="stat-value amber">{{ number_format((int) ($armadaAktifCount ?? 0), 0, ',', '.') }} <span style="font-size:1rem;color:#64748b;">Truk/Pikap</span></div>
                    <span class="badge badge-warning">Stok > 0</span>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Aksi Cepat</div>
                    <div class="panel-subtitle">Shortcut operasional harian gula</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="qa-grid" style="margin:0;">
                    <a href="{{ route('gula.stok.index') }}" class="qa-card qa-indigo">
                        <div class="qa-card-icon">📦</div>
                        <div>
                            <div class="qa-card-title">Stok Gudang Induk</div>
                            <div class="qa-card-subtitle">Pantau stok & harga produk gula</div>
                        </div>
                        <div class="qa-card-arrow">➔</div>
                    </a>
                    <a href="{{ route('gula.repacking.index') }}" class="qa-card qa-slate">
                        <div class="qa-card-icon">✂️</div>
                        <div>
                            <div class="qa-card-title">Repacking & Susut</div>
                            <div class="qa-card-subtitle">Catat repacking, pecah bal, dan susut</div>
                        </div>
                        <div class="qa-card-arrow">➔</div>
                    </a>
                    <a href="{{ route('gula.loading.index') }}" class="qa-card qa-amber">
                        <div class="qa-card-icon">🚚</div>
                        <div>
                            <div class="qa-card-title">Loading Armada</div>
                            <div class="qa-card-subtitle">Surat jalan gudang → kendaraan sales</div>
                        </div>
                        <div class="qa-card-arrow">➔</div>
                    </a>
                    <a href="{{ route('gula.setoran.index') }}" class="qa-card qa-emerald">
                        <div class="qa-card-icon">💰</div>
                        <div>
                            <div class="qa-card-title">Validasi Setoran</div>
                            <div class="qa-card-subtitle">Verifikasi cash & tarik sisa muatan</div>
                        </div>
                        <div class="qa-card-arrow">➔</div>
                    </a>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Detail Muatan Armada Berjalan</div>
                    <div class="panel-subtitle">Sisa stok live di kendaraan sales gula hari ini</div>
                </div>
                <span class="badge badge-gray">{{ number_format((int) ($armadaAktifCount ?? 0), 0, ',', '.') }} armada</span>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Plat Nomor / Armada</th>
                                <th>Sales (Driver)</th>
                                <th style="text-align:right;">Stok Karungan</th>
                                <th style="text-align:right;">Stok Bal</th>
                                <th style="text-align:right;">Stok Eceran (1kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeVehicles->groupBy('vehicle_id') as $vehicleId => $stocks)
                                @php
                                    $vehicle = $stocks->first()->vehicle;
                                    $sales = $stocks->first()->sales;
                                    $totalKA = (int) $stocks->sum('qty_karung');
                                    $totalBAL = (int) $stocks->sum('qty_bal');
                                    $totalEC = (int) $stocks->sum('qty_eceran');
                                @endphp
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;">
                                            <span class="badge badge-indigo">{{ $vehicle->license_plate ?? 'Unknown' }}</span>
                                            @if($vehicle?->type)
                                                <span class="badge badge-gray">{{ $vehicle->type }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="font-weight:900;color:#0f172a;">{{ $sales->name ?? 'Unknown' }}</td>
                                    <td style="text-align:right;">
                                        <span class="badge badge-blue">{{ number_format($totalKA, 0, ',', '.') }} Krg</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="badge badge-indigo">{{ number_format($totalBAL, 0, ',', '.') }} Bal</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="badge badge-success">{{ number_format($totalEC, 0, ',', '.') }} Bks</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">🚛</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada armada aktif</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:560px;">
                                                Belum ada proses loading barang atau semua kendaraan sudah kosong hari ini.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
