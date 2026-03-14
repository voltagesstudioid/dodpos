<x-app-layout>
    <x-slot name="header">Sesi Kasir</x-slot>

    <div class="page-container" style="max-width:1100px;">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">❌ {{ session('error') }}</div> @endif

        <div class="page-header">
            <div>
                <div class="page-header-title">Ringkasan Sesi Kasir</div>
                <div class="page-header-subtitle">Pantau modal awal dan kas seharusnya berjalan</div>
            </div>
            <div class="page-header-actions">
                @can('view_pos_kasir')
                    <a href="{{ route('kasir.index') }}" class="btn-secondary">🖥️ Kasir / POS</a>
                @endcan
                @if($activeSession)
                    @can('delete_sesi_kasir')
                        <form method="POST" action="{{ route('kasir.close_session') }}" onsubmit="return confirm('Yakin tutup sesi kasir?');" style="margin:0;display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                            @csrf
                            <input type="number" name="actual_cash" class="form-input" placeholder="Kas fisik (Rp)" min="0" step="0.01" required style="max-width:210px;">
                            <input type="text" name="notes" class="form-input" placeholder="Catatan (opsional)" style="max-width:260px;">
                            <button type="submit" class="btn-danger">🔒 Tutup Kasir</button>
                        </form>
                    @endcan
                @endif
            </div>
        </div>

        @if(!$activeSession)
            <div class="panel">
                <div class="panel-body">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;color:#64748b;padding:1.75rem 0;">
                        <div style="font-size:2rem;">🔒</div>
                        <div style="font-weight:900;color:#0f172a;">Sesi kasir belum dibuka</div>
                        <div style="font-size:0.875rem;text-align:center;max-width:520px;">
                            Modal awal belum ada, jadi kasir belum bisa digunakan.
                        </div>
                        @can('view_pos_kasir')
                            <a href="{{ route('kasir.index') }}" class="btn-primary">Buka Halaman Kasir</a>
                        @endcan
                    </div>
                </div>
            </div>
        @else
            <div class="stat-grid" style="margin-bottom:1rem;">
                <div class="stat-card">
                    <div class="stat-icon indigo">🏷️</div>
                    <div>
                        <div class="stat-label">Status Sesi</div>
                        <div class="stat-value indigo">{{ strtoupper($activeSession->status) }}</div>
                        <span class="badge badge-gray">ID: {{ $activeSession->id }}</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon emerald">💵</div>
                    <div>
                        <div class="stat-label">Modal Awal</div>
                        <div class="stat-value emerald">Rp {{ number_format($activeSession->opening_amount ?? 0, 0, ',', '.') }}</div>
                        <span class="badge badge-gray">Dibuka: {{ optional($activeSession->opened_at ?? $activeSession->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue">🧾</div>
                    <div>
                        <div class="stat-label">Penjualan Tunai</div>
                        <div class="stat-value blue">Rp {{ number_format($cashRevenue ?? 0, 0, ',', '.') }}</div>
                        <span class="badge badge-gray">{{ $cashTransactions ?? 0 }} transaksi tunai</span>
                    </div>
                </div>

                <div class="stat-card" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none;">
                    <div class="stat-icon" style="background:rgba(255,255,255,0.18);color:#fff;">💰</div>
                    <div>
                        <div class="stat-label" style="color:rgba(255,255,255,0.85);">Kas Seharusnya Sekarang</div>
                        <div class="stat-value" style="color:#fff;">Rp {{ number_format($expectedCash ?? 0, 0, ',', '.') }}</div>
                        <span class="badge" style="background:rgba(255,255,255,0.18);color:#fff;border:none;">Modal + tunai + DP kredit + cash in/out</span>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Detail</div>
                        <div class="panel-subtitle">Untuk memastikan modal awal sudah ikut dihitung</div>
                    </div>
                </div>
                <div class="panel-body">
                    <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0.75rem;">
                        <div class="card" style="padding:1rem;">
                            <div style="font-size:0.75rem;color:#94a3b8;font-weight:900;text-transform:uppercase;">Dibuka Oleh</div>
                            <div style="font-size:0.95rem;font-weight:900;color:#0f172a;margin-top:0.25rem;">
                                {{ $activeSession->user?->name ?? '-' }}
                            </div>
                        </div>
                        <div class="card" style="padding:1rem;">
                            <div style="font-size:0.75rem;color:#94a3b8;font-weight:900;text-transform:uppercase;">Total Transaksi</div>
                            <div style="font-size:0.95rem;font-weight:900;color:#0f172a;margin-top:0.25rem;">
                                {{ $totalTransactions ?? 0 }} transaksi
                            </div>
                        </div>
                        <div class="card" style="padding:1rem;">
                            <div style="font-size:0.75rem;color:#94a3b8;font-weight:900;text-transform:uppercase;">Omzet Non-Tunai</div>
                            <div style="font-size:0.95rem;font-weight:900;color:#0f172a;margin-top:0.25rem;">
                                Rp {{ number_format($nonCashRevenue ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="card" style="padding:1rem;">
                            <div style="font-size:0.75rem;color:#94a3b8;font-weight:900;text-transform:uppercase;">Total Omzet</div>
                            <div style="font-size:0.95rem;font-weight:900;color:#0f172a;margin-top:0.25rem;">
                                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="card" style="padding:1rem;">
                            <div style="font-size:0.75rem;color:#94a3b8;font-weight:900;text-transform:uppercase;">DP Kredit (Tunai)</div>
                            <div style="font-size:0.95rem;font-weight:900;color:#0f172a;margin-top:0.25rem;">
                                Rp {{ number_format($creditDp ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="card" style="padding:1rem;">
                            <div style="display:flex;justify-content:space-between;gap:0.75rem;flex-wrap:wrap;">
                                <div>
                                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:900;text-transform:uppercase;">Cash In</div>
                                    <div style="font-size:0.95rem;font-weight:900;color:#16a34a;margin-top:0.25rem;">
                                        Rp {{ number_format($cashIn ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size:0.75rem;color:#94a3b8;font-weight:900;text-transform:uppercase;">Cash Out</div>
                                    <div style="font-size:0.95rem;font-weight:900;color:#ef4444;margin-top:0.25rem;">
                                        Rp {{ number_format($cashOut ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert" style="margin-top:1rem;">
                        Kas seharusnya saat ini = modal awal + penjualan tunai + DP kredit + cash in - cash out.
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:1rem;">
                <div class="panel-header">
                    <div>
                        <div class="panel-title">Cash In / Out</div>
                        <div class="panel-subtitle">Catat uang masuk/keluar dari laci kas (di luar transaksi)</div>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('kasir.cash_movement') }}" style="display:flex;gap:0.75rem;align-items:end;flex-wrap:wrap;">
                        @csrf
                        <div style="min-width:160px;">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-input" required>
                                <option value="in">Cash In</option>
                                <option value="out">Cash Out</option>
                            </select>
                        </div>
                        <div style="min-width:220px;">
                            <label class="form-label">Nominal</label>
                            <input type="number" name="amount" class="form-input" min="0.01" step="0.01" required placeholder="0">
                        </div>
                        <div style="flex:1;min-width:260px;">
                            <label class="form-label">Catatan</label>
                            <input type="text" name="notes" class="form-input" placeholder="Misal: belanja kebutuhan kasir">
                        </div>
                        <button type="submit" class="btn-primary">💾 Simpan</button>
                    </form>

                    @if(isset($cashMovements) && $cashMovements->count())
                        <div class="table-wrapper" style="margin-top:1rem;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Tipe</th>
                                        <th style="text-align:right;">Nominal</th>
                                        <th>Catatan</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cashMovements as $m)
                                        <tr>
                                            <td>{{ optional($m->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($m->type === 'in')
                                                    <span class="badge badge-success">IN</span>
                                                @else
                                                    <span class="badge badge-danger">OUT</span>
                                                @endif
                                            </td>
                                            <td style="text-align:right;font-weight:900;">Rp {{ number_format((float) $m->amount, 0, ',', '.') }}</td>
                                            <td>{{ $m->notes ?? '-' }}</td>
                                            <td>{{ $m->user?->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
