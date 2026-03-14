<x-app-layout>
    <x-slot name="header">Laporan Modal Operasional</x-slot>

    <div class="page-container">
        <div class="page-header">
            <div>
                <div class="page-header-title">Riwayat Sesi &amp; Modal Operasional</div>
                <div class="page-header-subtitle">Pantau modal awal, pemakaian, dan status sesi operasional</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('operasional.riwayat.index') }}" class="btn-secondary">📜 Riwayat Pengeluaran</a>
                <a href="{{ route('operasional.pengeluaran.create') }}" class="btn-primary">💸 Input Pengeluaran</a>
            </div>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon indigo">📅</div>
                <div>
                    <div class="stat-label">Total Sesi</div>
                    <div class="stat-value indigo">{{ $totalSessions ?? 0 }}</div>
                    <span class="badge badge-gray">Riwayat</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon emerald">🟢</div>
                <div>
                    <div class="stat-label">Sesi Aktif</div>
                    <div class="stat-value emerald">{{ $openSessionsCount ?? 0 }}</div>
                    <span class="badge badge-success">Open</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">💰</div>
                <div>
                    <div class="stat-label">Total Modal</div>
                    <div class="stat-value blue">Rp {{ number_format((float) ($totalOpening ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-blue">Opening</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rose">💸</div>
                <div>
                    <div class="stat-label">Total Terpakai</div>
                    <div class="stat-value rose">Rp {{ number_format((float) ($totalUsed ?? 0), 0, ',', '.') }}</div>
                    <span class="badge badge-danger">Used</span>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Kontrol Sesi</div>
                    <div class="panel-subtitle">Buka sesi dengan modal awal atau tutup sesi yang sedang aktif</div>
                </div>
                @if(($activeSession->status ?? null) === 'open')
                    <span class="badge badge-success">Sesi Aktif</span>
                @else
                    <span class="badge badge-warning">Tidak ada sesi aktif</span>
                @endif
            </div>
            <div class="panel-body">
                @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-danger">❌ {{ session('error') }}</div> @endif

                @if(($activeSession->status ?? null) === 'open')
                    @php
                        $used = (float) ($activeSession->expenses_sum_amount ?? 0);
                        $remain = max(0, (float) $activeSession->opening_amount - $used);
                    @endphp
                    <div style="display:grid;grid-template-columns:1fr;gap:1rem;">
                        <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
                            <span class="badge badge-indigo">Dibuka: {{ \Carbon\Carbon::parse($activeSession->created_at)->format('d M Y H:i') }}</span>
                            <span class="badge badge-gray">Petugas: {{ $activeSession->user->name ?? '-' }}</span>
                            <span class="badge badge-blue">Modal: Rp {{ number_format((float) $activeSession->opening_amount, 0, ',', '.') }}</span>
                            <span class="badge badge-danger">Terpakai: Rp {{ number_format($used, 0, ',', '.') }}</span>
                            <span class="badge badge-success">Sisa: Rp {{ number_format($remain, 0, ',', '.') }}</span>
                        </div>

                        @can('manage_sesi_operasional')
                            <form method="POST" action="{{ route('operasional.close_session') }}" onsubmit="return confirm('Yakin tutup sesi operasional?');" style="display:grid;grid-template-columns:1fr auto;gap:0.75rem;align-items:end;">
                                @csrf
                                <div>
                                    <label class="form-label">Saldo Akhir (Opsional)</label>
                                    <input type="number" name="closing_amount" class="form-input" min="0" value="{{ old('closing_amount', 0) }}" placeholder="0">
                                    <div class="form-hint">Jika tidak diisi, sistem akan menyimpan 0.</div>
                                </div>
                                <button type="submit" class="btn-danger">🔒 Tutup Sesi</button>
                            </form>
                        @else
                            <div class="alert alert-info" role="alert">
                                ℹ️ Hubungi supervisor untuk menutup sesi operasional.
                            </div>
                        @endcan
                    </div>
                @else
                    @can('manage_sesi_operasional')
                        <form method="POST" action="{{ route('operasional.open_session') }}" style="display:grid;grid-template-columns:1fr;gap:1rem;">
                            @csrf
                            <div class="form-row">
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label">Modal Awal (Rp) <span class="required">*</span></label>
                                    <input type="number" name="opening_amount" class="form-input @error('opening_amount') input-error @enderror" min="0" required value="{{ old('opening_amount', 0) }}">
                                    @error('opening_amount') <div class="form-error">⚠ {{ $message }}</div> @enderror
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label">Metode Modal <span class="required">*</span></label>
                                    @php $pm = old('payment_method', 'Tunai'); @endphp
                                    <select name="payment_method" class="form-input" required>
                                        <option value="Tunai" {{ $pm === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="Transfer" {{ $pm === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" rows="2" class="form-input" placeholder="Contoh: Modal operasional hari ini">{{ old('notes') }}</textarea>
                            </div>
                            <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;">
                                <button type="submit" class="btn-primary">✅ Buka Sesi</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info" role="alert">
                            ℹ️ Hubungi supervisor untuk membuka sesi operasional.
                        </div>
                    @endcan
                @endif
            </div>
        </div>

        <div class="panel" style="margin-top: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Riwayat Sesi</div>
                    <div class="panel-subtitle">Daftar sesi terbaru</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tgl Buka</th>
                                <th>Kasir / Admin</th>
                                <th style="text-align:right;">Modal Awal</th>
                                <th style="text-align:right;">Total Terpakai</th>
                                <th style="text-align:right;">Sisa Modal</th>
                                <th style="text-align:center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $session)
                                @php
                                    $terpakai = (float) ($session->expenses_sum_amount ?? 0);
                                    $sisa = (float) $session->opening_amount - $terpakai;
                                @endphp
                                <tr>
                                    <td style="white-space:nowrap;">
                                        {{ \Carbon\Carbon::parse($session->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td>{{ $session->user->name ?? '-' }}</td>
                                    <td style="text-align:right;font-weight:900;color:#0f172a;">Rp {{ number_format((float) $session->opening_amount, 0, ',', '.') }}</td>
                                    <td style="text-align:right;color:#b91c1c;font-weight:800;">Rp {{ number_format($terpakai, 0, ',', '.') }}</td>
                                    @if($sisa < 0)
                                    <td style="text-align:right;font-weight:900;color:#b91c1c;">
                                    @else
                                    <td style="text-align:right;font-weight:900;color:#15803d;">
                                    @endif
                                        Rp {{ number_format($sisa, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align:center;">
                                        @if($session->status === 'open')
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-gray">Ditutup</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 2.25rem;">
                                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;">
                                            <div style="font-size:2rem;">📊</div>
                                            <div style="font-weight:900;color:#0f172a;">Belum ada riwayat sesi</div>
                                            <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                                                Buka sesi operasional untuk mulai mencatat pengeluaran.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1rem;">
                    {{ $sessions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
