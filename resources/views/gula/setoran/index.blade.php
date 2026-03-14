<x-app-layout>
    <x-slot name="header">Validasi Setoran (Gula)</x-slot>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Daftar Rekap Setoran Sales</div>
                <div class="page-header-subtitle">Riwayat kas tunai, piutang tempo, dan validasi setoran akhir hari</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('gula.dashboard') }}" class="btn-secondary">📊 Dashboard</a>
                <a href="{{ route('gula.setoran.index') }}" class="btn-secondary">↺ Refresh</a>
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon indigo">🧾</div>
                <div>
                    <div class="stat-label">Total Setoran</div>
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
                    <div class="stat-label">Total Kas</div>
                    <div class="stat-value blue">Rp {{ number_format((float) ($totalCash ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-blue">{{ number_format((int) ($todayCount ?? 0), 0, ',', '.') }} hari ini</span>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Pencarian</div>
                    <div class="panel-subtitle">Cari sales/verifikator atau filter status dan tanggal closing</div>
                </div>
            </div>
            <div class="panel-body">
                <form method="GET" action="{{ route('gula.setoran.index') }}" class="form-row">
                    <div>
                        <label class="form-label">Kata Kunci</label>
                        <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Contoh: nama sales, catatan, verifier">
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        @php $st = request('status'); @endphp
                        <select name="status" class="form-input">
                            <option value="" {{ $st === null || $st === '' ? 'selected' : '' }}>Semua</option>
                            <option value="pending" {{ $st === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $st === 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ $st === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Closing</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="form-input">
                    </div>
                    <div style="display:flex;gap:0.5rem;align-items:flex-end;flex-wrap:wrap;">
                        <button type="submit" class="btn-primary">🔎 Terapkan</button>
                        <a href="{{ route('gula.setoran.index') }}" class="btn-secondary">↺ Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Daftar Setoran</div>
                    <div class="panel-subtitle">Klik detail untuk pemeriksaan nota & sisa stok</div>
                </div>
                <span class="badge badge-danger">Piutang: Rp {{ number_format((float) ($totalPiutang ?? 0), 0, ',', '.') }}</span>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Tanggal Closing</th>
                                <th>Sales</th>
                                <th style="text-align:right;">Uang Kas</th>
                                <th style="text-align:right;">Total Piutang</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($setorans as $setoran)
                                <tr>
                                    <td style="white-space:nowrap;">
                                        <div style="font-weight:900;color:#0f172a;">{{ \Carbon\Carbon::parse($setoran->date)->format('d/m/Y') }}</div>
                                        <div style="color:#64748b;font-size:0.75rem;">Submit: {{ \Carbon\Carbon::parse($setoran->created_at)->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:0.25rem;">
                                            <div style="font-weight:900;color:#0f172a;">{{ $setoran->sales->name ?? '-' }}</div>
                                            @if($setoran->notes)
                                                <div style="color:#64748b;font-size:0.75rem;line-height:1.2;max-width:340px;" class="text-truncate">{{ $setoran->notes }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="text-align:right;font-weight:900;color:#15803d;">Rp {{ number_format((float) $setoran->total_cash, 0, ',', '.') }}</td>
                                    <td style="text-align:right;">
                                        @if((float) $setoran->total_piutang > 0)
                                            <span class="badge badge-danger">Rp {{ number_format((float) $setoran->total_piutang, 0, ',', '.') }}</span>
                                        @else
                                            <span class="badge badge-success">Rp 0</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        @if($setoran->status === 'verified')
                                            <span class="badge badge-success">Verified</span>
                                            <div style="margin-top:0.25rem;color:#64748b;font-size:0.75rem;">Oleh: {{ $setoran->verifiedBy->name ?? '-' }}</div>
                                        @elseif($setoran->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="{{ route('gula.setoran.show', $setoran->id) }}" class="btn-primary">
                                            {{ $setoran->status === 'verified' ? '🔎 Detail' : '✅ Validasi' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">💳</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada setoran</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:560px;">
                                                Belum ada pengajuan laporan setoran (closing aplikasi) dari Sales Pasukan Gula untuk filter yang dipilih.
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
