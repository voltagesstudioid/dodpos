<x-app-layout>
    <x-slot name="header">SDM / HR - Laporan Modal</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── PAGE HEADER ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Operasional Kas</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="6" y1="8" x2="6.01" y2="8"></line><line x1="10" y1="8" x2="10.01" y2="8"></line></svg>
                        </div>
                        Laporan Sesi & Modal
                    </h1>
                    <p class="tr-subtitle">Pantau alokasi modal awal, penggunaan kas, dan status sesi operasional harian.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('operasional.riwayat.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        Riwayat Pengeluaran
                    </a>
                    <a href="{{ route('operasional.pengeluaran.create') }}" class="tr-btn tr-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Input Pengeluaran
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
            @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif

            {{-- ─── STAT GRID (KPI) ─── --}}
            <div class="tr-kpi-grid">
                <div class="tr-kpi-card border-indigo">
                    <div class="tr-kpi-icon bg-indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <div class="tr-kpi-info">
                        <div class="label">Total Sesi Terbuka</div>
                        <div class="value text-indigo">{{ $totalSessions ?? 0 }} <small>Riwayat</small></div>
                    </div>
                </div>
                
                <div class="tr-kpi-card border-success">
                    <div class="tr-kpi-icon bg-success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div class="tr-kpi-info">
                        <div class="label">Sesi Aktif Saat Ini</div>
                        <div class="value text-success">{{ $openSessionsCount ?? 0 }} <small>Sesi</small></div>
                    </div>
                </div>

                <div class="tr-kpi-card border-warning">
                    <div class="tr-kpi-icon bg-warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                    <div class="tr-kpi-info">
                        <div class="label">Total Alokasi Modal</div>
                        <div class="value text-warning tr-font-mono">Rp {{ number_format((float) ($totalOpening ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="tr-kpi-card border-danger">
                    <div class="tr-kpi-icon bg-danger">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="tr-kpi-info">
                        <div class="label">Total Dana Terpakai</div>
                        <div class="value text-danger tr-font-mono">Rp {{ number_format((float) ($totalUsed ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            {{-- ─── SESSION CONTROLLER CARD ─── --}}
            <div class="tr-card" style="margin-bottom: 1.5rem;">
                <div class="tr-card-header tr-flex-between">
                    <div>
                        <h2 class="tr-section-title">Kontrol Sesi Operasional</h2>
                        <p class="tr-card-subtitle">Buka sesi baru dengan modal awal atau tutup sesi yang sedang berjalan.</p>
                    </div>
                    @if(($activeSession->status ?? null) === 'open')
                        <span class="tr-status-pill success">🟢 Sesi Aktif</span>
                    @else
                        <span class="tr-status-pill danger">🔴 Tidak Ada Sesi</span>
                    @endif
                </div>

                <div class="tr-card-body">
                    @if(($activeSession->status ?? null) === 'open')
                        @php
                            $used = (float) ($activeSession->expenses_sum_amount ?? 0);
                            $remain = max(0, (float) $activeSession->opening_amount - $used);
                        @endphp
                        
                        {{-- Active Session Details --}}
                        <div class="tr-active-session-box">
                            <div class="info-strip">
                                <div class="info-item">
                                    <span class="label">Waktu Dibuka</span>
                                    <span class="val">{{ \Carbon\Carbon::parse($activeSession->created_at)->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Petugas / PIC</span>
                                    <span class="val">{{ $activeSession->user->name ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="finance-strip">
                                <div class="fin-box border-warning">
                                    <span>Modal Awal</span>
                                    <strong class="tr-font-mono text-warning">Rp {{ number_format((float) $activeSession->opening_amount, 0, ',', '.') }}</strong>
                                </div>
                                <div class="fin-box border-danger">
                                    <span>Dana Terpakai</span>
                                    <strong class="tr-font-mono text-danger">Rp {{ number_format($used, 0, ',', '.') }}</strong>
                                </div>
                                <div class="fin-box border-success">
                                    <span>Sisa Saldo Kas</span>
                                    <strong class="tr-font-mono text-success">Rp {{ number_format($remain, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            @can('manage_sesi_operasional')
                                <form method="POST" action="{{ route('operasional.close_session') }}" class="tr-close-session-form" onsubmit="return confirm('Apakah Anda yakin ingin menutup sesi operasional ini?');">
                                    @csrf
                                    <div class="tr-form-group">
                                        <label class="tr-label">Input Saldo Akhir Aktual <span class="tr-optional">(Opsional)</span></label>
                                        <div class="tr-input-prefix-group">
                                            <span class="prefix">Rp</span>
                                            <input type="number" name="closing_amount" class="tr-input tr-font-mono" min="0" value="{{ old('closing_amount', 0) }}" placeholder="0">
                                        </div>
                                        <div class="tr-input-hint">Catat saldo riil di laci. Jika dibiarkan kosong, sistem mencatat nilai 0.</div>
                                    </div>
                                    <button type="submit" class="tr-btn tr-btn-danger">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        Tutup Sesi
                                    </button>
                                </form>
                            @else
                                <div class="tr-alert-info">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                    Anda tidak memiliki izin untuk menutup sesi operasional. Silakan hubungi Supervisor.
                                </div>
                            @endcan
                        </div>

                    @else
                        {{-- No Active Session - Open Form --}}
                        @can('manage_sesi_operasional')
                            <form method="POST" action="{{ route('operasional.open_session') }}" class="tr-open-session-form">
                                @csrf
                                <div class="tr-grid-2">
                                    <div class="tr-form-group">
                                        <label class="tr-label">Tentukan Modal Awal <span class="tr-req">*</span></label>
                                        <div class="tr-input-prefix-group">
                                            <span class="prefix">Rp</span>
                                            <input type="number" name="opening_amount" class="tr-input tr-font-mono @error('opening_amount') is-invalid @enderror" min="0" required value="{{ old('opening_amount', 0) }}">
                                        </div>
                                        @error('opening_amount') <div class="tr-error-msg">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="tr-form-group">
                                        <label class="tr-label">Metode Sumber Modal <span class="tr-req">*</span></label>
                                        <div class="tr-select-wrapper">
                                            <select name="payment_method" class="tr-select" required>
                                                @php $pm = old('payment_method', 'Tunai'); @endphp
                                                <option value="Tunai" {{ $pm === 'Tunai' ? 'selected' : '' }}>Uang Tunai (Cash)</option>
                                                <option value="Transfer" {{ $pm === 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="tr-form-group" style="margin-top: 1rem;">
                                    <label class="tr-label">Catatan Sesi <span class="tr-optional">(Opsional)</span></label>
                                    <textarea name="notes" rows="2" class="tr-textarea" placeholder="Contoh: Alokasi modal operasional hari ini...">{{ old('notes') }}</textarea>
                                </div>

                                <div class="tr-form-actions">
                                    <button type="submit" class="tr-btn tr-btn-success">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        Buka Sesi Baru
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="tr-empty-state">
                                <div class="tr-empty-icon">🔒</div>
                                <h6>Sesi Operasional Ditutup</h6>
                                <p>Sistem sedang tidak menerima catatan pengeluaran. Hubungi Supervisor untuk membuka sesi baru.</p>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>

            {{-- ─── SESSION HISTORY TABLE ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Riwayat Log Sesi Operasional</h2>
                    <p class="tr-card-subtitle">Data historis sesi yang pernah dijalankan dalam sistem.</p>
                </div>

                <div class="table-responsive">
                    <table class="tr-table">
                        <thead>
                            <tr>
                                <th>Waktu Pembukaan</th>
                                <th>PIC / Admin</th>
                                <th class="r">Modal Awal</th>
                                <th class="r">Total Terpakai</th>
                                <th class="r">Sisa Modal</th>
                                <th class="c">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $session)
                                @php
                                    $terpakai = (float) ($session->expenses_sum_amount ?? 0);
                                    $sisa = (float) $session->opening_amount - $terpakai;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="tr-date-box">{{ \Carbon\Carbon::parse($session->created_at)->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="tr-font-bold">{{ $session->user->name ?? '-' }}</td>
                                    <td class="r tr-font-mono text-indigo tr-font-bold">
                                        Rp {{ number_format((float) $session->opening_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="r tr-font-mono text-danger tr-font-bold">
                                        Rp {{ number_format($terpakai, 0, ',', '.') }}
                                    </td>
                                    <td class="r tr-font-mono tr-font-bold {{ $sisa < 0 ? 'text-danger' : 'text-success' }}">
                                        Rp {{ number_format($sisa, 0, ',', '.') }}
                                    </td>
                                    <td class="c">
                                        @if($session->status === 'open')
                                            <span class="tr-badge tr-badge-success">Aktif</span>
                                        @else
                                            <span class="tr-badge tr-badge-gray">Ditutup</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tr-empty-state">
                                            <div class="tr-empty-icon">📊</div>
                                            <h6>Belum ada riwayat sesi</h6>
                                            <p>Data sesi historis akan muncul di sini setelah Anda membuka dan menutup sesi operasional pertama.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($sessions->hasPages())
                    <div class="tr-pagination-wrapper">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-indigo: #4f46e5; --tr-indigo-hover: #4338ca; --tr-indigo-light: #e0e7ff;
            --tr-success: #10b981; --tr-success-hover: #059669; --tr-success-light: #dcfce7;
            --tr-danger: #ef4444; --tr-danger-hover: #dc2626; --tr-danger-light: #fee2e2;
            --tr-warning: #f59e0b; --tr-warning-light: #fef3c7;
            --tr-border: #e2e8f0; --tr-bg: #f8fafc; --tr-surface: #ffffff;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 14px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 4rem; color: var(--tr-text-main); }
        .tr-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-indigo); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.625rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin-top: 6px; }
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── ALERTS ── */
        .tr-alert { padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem; }
        .tr-alert-success { background: var(--tr-success-light); color: #065f46; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background: var(--tr-danger-light); color: #991b1b; border: 1px solid #fecaca; }
        .tr-alert-info { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 1rem; border-radius: 10px; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 600; }

        /* ── KPI GRID ── */
        .tr-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .tr-kpi-card { background: var(--tr-surface); padding: 1.25rem; border-radius: 16px; border: 1px solid var(--tr-border); display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-left-width: 4px; }
        .tr-kpi-card.border-indigo { border-left-color: var(--tr-indigo); }
        .tr-kpi-card.border-success { border-left-color: var(--tr-success); }
        .tr-kpi-card.border-warning { border-left-color: var(--tr-warning); }
        .tr-kpi-card.border-danger { border-left-color: var(--tr-danger); }
        .tr-kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tr-kpi-info .label { font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .tr-kpi-info .value { font-size: 1.4rem; font-weight: 900; line-height: 1; }
        .tr-kpi-info small { font-size: 0.8rem; font-weight: 600; color: var(--tr-text-muted); }

        /* ── CARD & FORM ── */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafafa; }
        .tr-flex-between { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .tr-section-title { font-size: 1rem; font-weight: 800; margin: 0; }
        .tr-card-subtitle { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; }
        .tr-card-body { padding: 1.5rem; }

        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 0; }
        .tr-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-req { color: var(--tr-danger); }
        .tr-optional { font-weight: 500; text-transform: none; letter-spacing: 0; }
        
        .tr-input, .tr-textarea, .tr-select { padding: 0.65rem 1rem; border: 1.5px solid var(--tr-border); border-radius: 8px; font-size: 0.9rem; background: #fcfcfd; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); width: 100%; outline: none; }
        .tr-input:focus, .tr-select:focus, .tr-textarea:focus { border-color: var(--tr-indigo); background: #fff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .tr-textarea { resize: vertical; min-height: 80px; }
        .tr-input-hint { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 4px; font-weight: 500; }
        .tr-error-msg { color: var(--tr-danger); font-size: 0.75rem; font-weight: 600; margin-top: 4px; }

        .tr-input-prefix-group { display: flex; align-items: stretch; }
        .tr-input-prefix-group .prefix { display: flex; align-items: center; padding: 0 0.85rem; background: #f1f5f9; border: 1.5px solid var(--tr-border); border-right: none; border-radius: 8px 0 0 8px; font-size: 0.85rem; font-weight: 800; color: var(--tr-text-muted); }
        .tr-input-prefix-group .tr-input { border-radius: 0 8px 8px 0; }

        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }

        /* ── ACTIVE SESSION BOX ── */
        .tr-active-session-box { background: #f8fafc; border: 1px solid var(--tr-border); border-radius: 12px; padding: 1.5rem; }
        .info-strip { display: flex; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed var(--tr-border); }
        .info-item { display: flex; flex-direction: column; gap: 4px; }
        .info-item .label { font-size: 0.7rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; }
        .info-item .val { font-size: 0.9rem; font-weight: 800; }
        
        .finance-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .fin-box { background: #fff; padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid var(--tr-border); border-left-width: 4px; display: flex; flex-direction: column; gap: 4px; }
        .fin-box span { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; }
        .fin-box strong { font-size: 1.35rem; }

        .tr-close-session-form { display: grid; grid-template-columns: 1fr auto; gap: 1rem; align-items: start; background: #fff; padding: 1.25rem; border-radius: 10px; border: 1px solid var(--tr-border); }

        /* ── BUTTONS ── */
        .tr-form-actions { margin-top: 1.5rem; display: flex; justify-content: flex-end; }
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.65rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; }
        .tr-btn-primary { background: var(--tr-indigo); color: white; }
        .tr-btn-primary:hover { background: var(--tr-indigo-hover); }
        .tr-btn-success { background: var(--tr-success); color: white; }
        .tr-btn-success:hover { background: var(--tr-success-hover); }
        .tr-btn-danger { background: var(--tr-danger); color: white; }
        .tr-btn-danger:hover { background: var(--tr-danger-hover); }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f1f5f9; }

        /* ── TABLE ── */
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .tr-table thead th { background: #f8fafc; padding: 0.85rem 1.25rem; text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-table tbody td { padding: 1.125rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; vertical-align: middle; }
        .tr-table tbody tr:hover { background: #fafafa; }
        .tr-table .c { text-align: center; }
        .tr-table .r { text-align: right; }

        /* ── UTILS ── */
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .bg-success { background: var(--tr-success-light); color: var(--tr-success); }
        .bg-danger { background: var(--tr-danger-light); color: var(--tr-danger); }
        .bg-warning { background: var(--tr-warning-light); color: var(--tr-warning); }

        .text-indigo { color: var(--tr-indigo); }
        .text-success { color: var(--tr-success); }
        .text-danger { color: var(--tr-danger); }
        .text-warning { color: var(--tr-warning); }

        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .tr-font-bold { font-weight: 800; }
        .tr-date-box { font-weight: 600; color: var(--tr-text-main); }

        .tr-badge { padding: 0.25rem 0.65rem; border-radius: 99px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.02em; }
        .tr-badge-success { background: var(--tr-success-light); color: #065f46; }
        .tr-badge-gray { background: #f1f5f9; color: var(--tr-text-muted); }

        .tr-status-pill { padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: 800; }
        .tr-status-pill.success { background: var(--tr-success-light); color: #065f46; }
        .tr-status-pill.danger { background: var(--tr-danger-light); color: #991b1b; }

        .tr-empty-state { padding: 4rem 2rem; text-align: center; }
        .tr-empty-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .tr-empty-state h6 { font-size: 1.1rem; font-weight: 800; margin-bottom: 4px; color: var(--tr-text-main); }
        .tr-empty-state p { color: var(--tr-text-muted); font-size: 0.85rem; }

        .tr-pagination-wrapper { padding: 1.25rem 1.5rem; border-top: 1px solid #f1f5f9; }

        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-header-actions { flex-direction: column; }
            .tr-btn { justify-content: center; }
            .tr-grid-2 { grid-template-columns: 1fr; }
            .tr-close-session-form { grid-template-columns: 1fr; gap: 1rem; }
        }
    </style>
    @endpush
</x-app-layout>