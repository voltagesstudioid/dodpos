<x-app-layout>
<x-slot name="header">Loading Armada Kanvas</x-slot>

<div class="page-container">
    <div class="page-header">
        <div>
            <div class="page-header-title">Surat Jalan (Loading) Armada Kanvas</div>
            <div class="page-header-subtitle">Riwayat SJ untuk pemindahan stok Gudang → Armada</div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('kanvas.dashboard') }}" class="btn-secondary">📈 Dashboard</a>
            @can('create_kanvas_loading')
            <a href="{{ route('kanvas.loading.create') }}" class="btn-primary">➕ Load Barang Pagi</a>
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
                <div class="stat-label">Berangkat</div>
                <div class="stat-value emerald">{{ number_format((int) ($completedCount ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-success">completed</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">📝</div>
            <div>
                <div class="stat-label">Draft</div>
                <div class="stat-value amber">{{ number_format((int) ($draftCount ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-warning">loading</span>
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
                <div class="panel-subtitle">Cari No SJ, Sales, Admin, atau status</div>
            </div>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('kanvas.loading.index') }}" class="form-row">
                <div>
                    <label class="form-label">Kata Kunci</label>
                    <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: SJ-KVS, nama sales, nama admin">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    @php $st = request('status'); @endphp
                    <select name="status" class="form-input">
                        <option value="" {{ $st === null || $st === '' ? 'selected' : '' }}>Semua</option>
                        <option value="completed" {{ $st === 'completed' ? 'selected' : '' }}>Berangkat</option>
                        <option value="loading" {{ $st === 'loading' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-input">
                </div>
                <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                    <button type="submit" class="btn-primary">🔎 Terapkan</button>
                    <a href="{{ route('kanvas.loading.index') }}" class="btn-secondary">↺ Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 1rem;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Riwayat Surat Jalan</div>
                <div class="panel-subtitle">Daftar SJ terbaru</div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-wrapper">
                <table class="data-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No SJ</th>
                            <th>Tanggal</th>
                            <th>Supir / Sales</th>
                            <th>Admin Penerbit</th>
                            <th style="text-align:right;">Item</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loadings as $sj)
                            <tr>
                                <td>
                                    <span class="badge badge-indigo">SJ-KVS-{{ str_pad($sj->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($sj->date)->format('d/m/Y') }}</td>
                                <td>{{ $sj->sales->name ?? '-' }}</td>
                                <td>{{ $sj->admin->name ?? '-' }}</td>
                                <td style="text-align:right;font-weight:900;color:#0f172a;">{{ number_format((int) ($sj->items_count ?? 0), 0, ',', '.') }}</td>
                                <td style="text-align:center;">
                                    @if($sj->status === 'completed')
                                        <span class="badge badge-success">Berangkat</span>
                                    @elseif($sj->status === 'loading')
                                        <span class="badge badge-warning">Draft</span>
                                    @else
                                        <span class="badge badge-gray">{{ $sj->status ?? '-' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 2.25rem;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                        <div style="font-size:2rem;">📦</div>
                                        <div style="font-weight:900;color:#0f172a;">Belum ada riwayat Surat Jalan</div>
                                        <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                            Buat SJ loading untuk memindahkan stok Gudang ke Armada Kanvas.
                                        </div>
                                        @can('create_kanvas_loading')
                                        <a href="{{ route('kanvas.loading.create') }}" class="btn-primary">➕ Load Barang Pagi</a>
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
