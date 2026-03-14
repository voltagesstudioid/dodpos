<x-app-layout>
<x-slot name="header">Dashboard Kanvas</x-slot>

<div class="page-container">
    <div class="page-header">
        <div>
            <div class="page-header-title">Pantauan Armada Kanvas</div>
            <div class="page-header-subtitle">Ringkasan stok berjalan & setoran shift hari ini</div>
        </div>
        <div class="page-header-actions">
            @can('create_kanvas_loading')
            <a href="{{ route('kanvas.loading.create') }}" class="btn-primary">🚚 Loading</a>
            @endcan
            @can('view_kanvas_setoran')
            <a href="{{ route('kanvas.setoran.index') }}" class="btn-secondary">💰 Setoran</a>
            @endcan
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon indigo">📦</div>
            <div>
                <div class="stat-label">Aset Barang di Jalan</div>
                <div class="stat-value indigo">Rp {{ number_format((float) $totalNilaiBarangDiJalan, 0, ',', '.') }}</div>
                <span class="badge badge-gray">{{ number_format((int) ($skuAktifCount ?? 0), 0, ',', '.') }} SKU aktif</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">🧾</div>
            <div>
                <div class="stat-label">Kas Setoran (Terverifikasi)</div>
                <div class="stat-value emerald">Rp {{ number_format((float) $totalSetoranMasuk, 0, ',', '.') }}</div>
                <span class="badge badge-success">{{ number_format((int) ($setoranTerverifikasiCount ?? 0), 0, ',', '.') }} setoran</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">🚐</div>
            <div>
                <div class="stat-label">Armada Aktif</div>
                <div class="stat-value blue">{{ number_format((int) ($armadaAktifCount ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-blue">Stok > 0</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">🎯</div>
            <div>
                <div class="stat-label">Target Kanvas</div>
                <div class="stat-value amber">{{ (int) ($targetPercent ?? 0) }}%</div>
                <span class="badge badge-warning">Dummy</span>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-top: 1rem;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Aksi Cepat Manager Kanvas</div>
                <div class="panel-subtitle">Shortcut operasional harian</div>
            </div>
        </div>
        <div class="panel-body">
            <div class="qa-grid" style="margin:0;">
                @can('create_kanvas_loading')
                <a href="{{ route('kanvas.loading.create') }}" class="qa-card qa-indigo">
                    <div class="qa-card-icon">📦</div>
                    <div>
                        <div class="qa-card-title">Loading Pagi</div>
                        <div class="qa-card-subtitle">Surat jalan & muat stok ke armada</div>
                    </div>
                    <div class="qa-card-arrow">➔</div>
                </a>
                @endcan
                @can('view_kanvas_rute')
                <a href="{{ route('kanvas.route.index') }}" class="qa-card qa-slate">
                    <div class="qa-card-icon">🗺️</div>
                    <div>
                        <div class="qa-card-title">Atur Rute SJ</div>
                        <div class="qa-card-subtitle">Manajemen area & daftar toko</div>
                    </div>
                    <div class="qa-card-arrow">➔</div>
                </a>
                @endcan
                @can('view_kanvas_setoran')
                <a href="{{ route('kanvas.setoran.index') }}" class="qa-card qa-amber">
                    <div class="qa-card-icon">💰</div>
                    <div>
                        <div class="qa-card-title">Setoran Sore</div>
                        <div class="qa-card-subtitle">Verifikasi cash & unloading sisa stok</div>
                    </div>
                    <div class="qa-card-arrow">➔</div>
                </a>
                @endcan
            </div>
        </div>
    </div>

</div>
</x-app-layout>
