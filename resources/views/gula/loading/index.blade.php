<x-app-layout>
    <x-slot name="header">Loading Armada (Gula)</x-slot>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Riwayat Mutasi / Surat Jalan</div>
                <div class="page-header-subtitle">Surat jalan pemindahan stok Gudang Induk → Armada Sales</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('gula.dashboard') }}" class="btn-secondary">📊 Dashboard</a>
                @can('create_gula_loading')
                <a href="{{ route('gula.loading.create') }}" class="btn-primary">➕ Buat Surat Jalan</a>
                @endcan
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon indigo">📄</div>
                <div>
                    <div class="stat-label">Total SJ</div>
                    <div class="stat-value indigo">{{ number_format((int) ($totalCount ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-gray">Sesuai filter</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon emerald">🚚</div>
                <div>
                    <div class="stat-label">Dimuat</div>
                    <div class="stat-value emerald">{{ number_format((int) ($loadedCount ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-success">loaded</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon slate">🔒</div>
                <div>
                    <div class="stat-label">Selesai / Disetor</div>
                    <div class="stat-value slate">{{ number_format((int) ($returnedCount ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-gray">returned</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">📅</div>
                <div>
                    <div class="stat-label">SJ Hari Ini</div>
                    <div class="stat-value blue">{{ number_format((int) ($todayCount ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-blue">{{ now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Pencarian</div>
                    <div class="panel-subtitle">Cari nomor SJ, plat kendaraan, sales, admin, atau catatan</div>
                </div>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('gula.loading.index') }}" class="form-row">
                    <div>
                        <label class="form-label">Kata Kunci</label>
                        <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: GLD-202603, BK 1010, nama sales">
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        @php $st = request('status'); @endphp
                        <select name="status" class="form-input">
                            <option value="" {{ $st === null || $st === '' ? 'selected' : '' }}>Semua</option>
                            <option value="loaded" {{ $st === 'loaded' ? 'selected' : '' }}>Dimuat</option>
                            <option value="returned" {{ $st === 'returned' ? 'selected' : '' }}>Selesai / Disetor</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="form-input">
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                        <button type="submit" class="btn-primary">🔎 Terapkan</button>
                        <a href="{{ route('gula.loading.index') }}" class="btn-secondary">↺ Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Daftar Surat Jalan</div>
                    <div class="panel-subtitle">Riwayat mutasi gula ke armada</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No. SJ</th>
                                <th>Tanggal</th>
                                <th>Armada</th>
                                <th>Sales</th>
                                <th style="text-align:right;">Muatan</th>
                                <th>Admin</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loadings as $loading)
                                @php
                                    $itemsCount = $loading->items?->count() ?? 0;
                                    $totalKrg = (int) ($loading->items?->sum('qty_karung') ?? 0);
                                    $totalEcr = (int) ($loading->items?->sum('qty_eceran') ?? 0);
                                @endphp
                                <tr>
                                    <td><span class="badge badge-indigo">{{ $loading->loading_number }}</span></td>
                                    <td style="white-space:nowrap;color:#64748b;">{{ \Carbon\Carbon::parse($loading->date)->format('d/m/Y') }}</td>
                                    <td>{{ $loading->vehicle->license_plate ?? '-' }}</td>
                                    <td style="font-weight:900;color:#0f172a;">{{ $loading->sales->name ?? '-' }}</td>
                                    <td style="text-align:right;">
                                        <div style="display:inline-flex;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                                            <span class="badge badge-gray">{{ number_format($itemsCount, 0, ',', '.') }} item</span>
                                            @if($totalKrg > 0)
                                                <span class="badge badge-blue">{{ number_format($totalKrg, 0, ',', '.') }} Krg</span>
                                            @endif
                                            @if($totalEcr > 0)
                                                <span class="badge badge-success">{{ number_format($totalEcr, 0, ',', '.') }} Bks</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="color:#64748b;">{{ $loading->user->name ?? '-' }}</td>
                                    <td style="text-align:center;">
                                        @if($loading->status === 'loaded')
                                            <span class="badge badge-success">Dimuat</span>
                                        @elseif($loading->status === 'returned')
                                            <span class="badge badge-gray">Selesai</span>
                                        @else
                                            <span class="badge badge-warning">{{ $loading->status ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        <button type="button" class="btn-secondary" disabled style="opacity:0.6;cursor:not-allowed;" onclick="alert('Fitur Cetak PDF Surat Jalan sedang dikembangkan')">🖨️ Cetak</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">🚚</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada surat jalan</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:560px;">
                                                Buat surat jalan untuk memindahkan stok Gudang Induk ke armada sales gula.
                                            </div>
                                            @can('create_gula_loading')
                                            <a href="{{ route('gula.loading.create') }}" class="btn-primary">➕ Buat Surat Jalan Baru</a>
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
    </div>
</x-app-layout>
