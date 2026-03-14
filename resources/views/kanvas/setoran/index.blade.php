<x-app-layout>
<x-slot name="header">Validasi Setoran Kanvas</x-slot>

<div class="page-container">
    <div class="page-header">
        <div>
            <div class="page-header-title">Daftar Setoran Akhir Shift</div>
            <div class="page-header-subtitle">Rekonsiliasi kas aktual vs sistem, dan validasi unloading sisa stok</div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('kanvas.dashboard') }}" class="btn-secondary">📈 Dashboard</a>
            <a href="{{ route('kanvas.setoran.index') }}" class="btn-secondary">↺ Refresh</a>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon indigo">🧾</div>
            <div>
                <div class="stat-label">Total Closing</div>
                <div class="stat-value indigo">{{ number_format((int) ($totalCount ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-gray">Sesuai filter</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">⏳</div>
            <div>
                <div class="stat-label">Pending</div>
                <div class="stat-value amber">{{ number_format((int) ($pendingCount ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-warning">Butuh validasi</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon emerald">✅</div>
            <div>
                <div class="stat-label">Verified</div>
                <div class="stat-value emerald">{{ number_format((int) ($verifiedCount ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-success">Selesai</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">💰</div>
            <div>
                <div class="stat-label">Kas Verified Hari Ini</div>
                <div class="stat-value blue">Rp {{ number_format((float) ($verifiedTodayCash ?? 0), 0, ',', '.') }}</div>
                <span class="badge badge-blue">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-top: 1rem;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Pencarian</div>
                <div class="panel-subtitle">Cari nama sales, verifier, atau filter status dan tanggal submit</div>
            </div>
        </div>
        <div class="panel-body">
            <form method="GET" action="{{ route('kanvas.setoran.index') }}" class="form-row">
                <div>
                    <label class="form-label">Kata Kunci</label>
                    <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: nama sales, nama verifier">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    @php $st = request('status'); @endphp
                    <select name="status" class="form-input">
                        <option value="" {{ $st === null || $st === '' ? 'selected' : '' }}>Semua</option>
                        <option value="pending" {{ $st === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ $st === 'verified' ? 'selected' : '' }}>Verified</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-input">
                </div>
                <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                    <button type="submit" class="btn-primary">🔎 Terapkan</button>
                    <a href="{{ route('kanvas.setoran.index') }}" class="btn-secondary">↺ Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="panel" style="margin-top: 1rem;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Daftar Setoran</div>
                <div class="panel-subtitle">Klik detail untuk cek uang & unloading</div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-wrapper">
                <table class="data-table" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Waktu Submit</th>
                            <th>Sales Kanvas</th>
                            <th style="text-align:right;">Kas Aktual</th>
                            <th style="text-align:right;">Ekspektasi Kas</th>
                            <th style="text-align:right;">Ekspektasi Tempo</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:right;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($setorans as $set)
                            @php $diff = (float) $set->actual_cash - (float) $set->expected_cash; @endphp
                            <tr>
                                <td style="white-space:nowrap;">{{ $set->created_at?->format('d/m/Y H:i') }}</td>
                                <td style="font-weight:900;color:#0f172a;">{{ $set->sales->name ?? '-' }}</td>
                                <td style="text-align:right;font-weight:900;color:#15803d;">Rp {{ number_format((float) $set->actual_cash, 0, ',', '.') }}</td>
                                <td style="text-align:right;">Rp {{ number_format((float) $set->expected_cash, 0, ',', '.') }}</td>
                                <td style="text-align:right;color:#b91c1c;font-weight:800;">Rp {{ number_format((float) $set->expected_tempo, 0, ',', '.') }}</td>
                                <td style="text-align:center;">
                                    @if($set->status === 'verified')
                                        <span class="badge badge-success">Verified</span>
                                        <div style="margin-top:0.25rem;color:#64748b;font-size:0.75rem;">
                                            By: {{ $set->verifier->name ?? '-' }}
                                        </div>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td style="text-align:right;">
                                    <div style="display:inline-flex;gap:0.5rem;align-items:center;flex-wrap:wrap;justify-content:flex-end;">
                                        @if($diff !== 0.0)
                                            <span class="badge {{ $diff < 0 ? 'badge-danger' : 'badge-blue' }}">
                                                Selisih: Rp {{ number_format($diff, 0, ',', '.') }}
                                            </span>
                                        @endif
                                        <a href="{{ route('kanvas.setoran.show', $set->id) }}" class="btn-primary">🔎 Detail</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 2.25rem;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                        <div style="font-size:2rem;">💰</div>
                                        <div style="font-weight:900;color:#0f172a;">Belum ada setoran</div>
                                        <div style="font-size:0.875rem;text-align:center;max-width:560px;">
                                            Belum ada tim Kanvas yang melakukan closing setoran untuk filter yang dipilih.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $setorans->links() }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>
