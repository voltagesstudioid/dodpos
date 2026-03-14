<x-app-layout>
<x-slot name="header">Journey Plan (Rute)</x-slot>

<div class="page-container">
    <div class="page-header">
        <div>
            <div class="page-header-title">Manajemen Rute &amp; Journey Plan</div>
            <div class="page-header-subtitle">Kelola area rute, jadwal hari, dan daftar toko kunjungan</div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('kanvas.dashboard') }}" class="btn-secondary">📈 Dashboard</a>
            @can('create_kanvas_rute')
            <a href="{{ route('kanvas.route.create') }}" class="btn-primary">➕ Tambah Area</a>
            @endcan
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon indigo">🗺️</div>
            <div>
                <div class="stat-label">Total Rute</div>
                <div class="stat-value indigo">{{ number_format((int) ($totalRoutes ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-gray">Sesuai filter</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">🏪</div>
            <div>
                <div class="stat-label">Total Toko</div>
                <div class="stat-value emerald">{{ number_format((int) ($totalStores ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-success">Terdaftar</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">📅</div>
            <div>
                <div class="stat-label">Hari Aktif</div>
                <div class="stat-value blue">{{ number_format((int) ($activeDaysCount ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-blue">Jadwal</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">🧭</div>
            <div>
                <div class="stat-label">Journey Plan</div>
                <div class="stat-value amber">Aktif</div>
                <span class="badge badge-warning">Kanvas</span>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-top: 1rem;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Pencarian</div>
                <div class="panel-subtitle">Cari nama rute, keterangan area, atau filter hari</div>
            </div>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('kanvas.route.index') }}" class="form-row">
                <div>
                    <label class="form-label">Kata Kunci</label>
                    <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: Medan, Area Barat, Senin">
                </div>
                <div>
                    <label class="form-label">Hari</label>
                    @php $day = request('day'); @endphp
                    <select name="day" class="form-input">
                        <option value="" {{ $day === null || $day === '' ? 'selected' : '' }}>Semua</option>
                        @foreach(($dayOptions ?? collect()) as $opt)
                            <option value="{{ $opt }}" {{ $day === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                    <button type="submit" class="btn-primary">🔎 Terapkan</button>
                    <a href="{{ route('kanvas.route.index') }}" class="btn-secondary">↺ Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 1rem;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Daftar Rute</div>
                <div class="panel-subtitle">Area rute dan total toko kunjungan</div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nama Rute (Area)</th>
                            <th>Jadwal Hari</th>
                            <th>Keterangan Area</th>
                            <th style="text-align:right;">Total Toko</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($routes as $rt)
                            <tr>
                                <td style="font-weight:900;color:#0f172a;">{{ $rt->name }}</td>
                                <td>
                                    @if($rt->day_of_week)
                                        <span class="badge badge-gray">{{ $rt->day_of_week }}</span>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                                <td style="color:#64748b;">{{ $rt->area_description ?? '-' }}</td>
                                <td style="text-align:right;font-weight:900;color:#0f172a;">{{ number_format((int) ($rt->stores_count ?? 0), 0, ',', '.') }}</td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn-secondary" disabled style="opacity:0.6;cursor:not-allowed;">👁️ Detail</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 2.25rem;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                        <div style="font-size:2rem;">🗺️</div>
                                        <div style="font-weight:900;color:#0f172a;">Belum ada rute Kanvas</div>
                                        <div style="font-size:0.875rem;text-align:center;max-width:560px;">
                                            Buat area rute untuk mengatur daftar toko kunjungan berdasarkan hari.
                                        </div>
                                        @can('create_kanvas_rute')
                                        <a href="{{ route('kanvas.route.create') }}" class="btn-primary">➕ Tambah Area Rute</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $routes->links() }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>
