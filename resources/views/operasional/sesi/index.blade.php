<x-app-layout>
    <x-slot name="header">Operasional — Laporan Sesi & Modal</x-slot>

    <div class="os-page">

        {{-- ═══ PAGE HEADER ═══ --}}
        <div class="os-header">
            <div class="os-header-text">
                <span class="os-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="4" width="20" height="16" rx="2"/><line x1="6" y1="8" x2="6.01" y2="8"/><line x1="10" y1="8" x2="10.01" y2="8"/></svg>
                    Operasional Kas
                </span>
                <h1 class="os-title">Laporan Sesi & Modal</h1>
                <p class="os-subtitle">Pantau alokasi modal awal, penggunaan kas, dan status sesi operasional harian.</p>
            </div>
            <div class="os-header-actions">
                <a href="{{ route('operasional.riwayat.index') }}" class="os-btn os-btn-ghost">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    Riwayat Pengeluaran
                </a>
                <a href="{{ route('operasional.pengeluaran.create') }}" class="os-btn os-btn-primary">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Input Pengeluaran
                </a>
            </div>
        </div>

        {{-- ═══ ALERTS ═══ --}}
        @if(session('success'))
            <div class="os-alert os-alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="os-alert os-alert-danger">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ═══ STAT CARDS ═══ --}}
        <div class="os-stats">
            <div class="os-stat stat-indigo">
                <div class="os-stat-deco"></div>
                <div class="os-stat-head">
                    <div class="os-stat-ico">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                </div>
                <div class="os-stat-val">{{ $totalSessions ?? 0 }}</div>
                <div class="os-stat-lbl">Total Sesi Terbuka</div>
                <div class="os-stat-foot"><span class="os-foot-pill pill-indigo">{{ $openSessionsCount ?? 0 }} aktif</span></div>
            </div>

            <div class="os-stat stat-emerald">
                <div class="os-stat-deco"></div>
                <div class="os-stat-head">
                    <div class="os-stat-ico">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    @if(($activeSession->status ?? null) === 'open')
                        <span class="os-live"><span class="os-live-dot"></span>Aktif</span>
                    @else
                        <span class="os-live live-off"><span class="os-live-dot"></span>Tidak Aktif</span>
                    @endif
                </div>
                <div class="os-stat-val">{{ $openSessionsCount ?? 0 }}</div>
                <div class="os-stat-lbl">Sesi Aktif Saat Ini</div>
                <div class="os-stat-foot"><span class="os-foot-pill pill-emerald">{{ $totalSessions ?? 0 }} total riwayat</span></div>
            </div>

            <div class="os-stat stat-amber">
                <div class="os-stat-deco"></div>
                <div class="os-stat-head">
                    <div class="os-stat-ico">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                </div>
                <div class="os-stat-val os-mono">Rp {{ number_format((float) ($totalOpening ?? 0), 0, ',', '.') }}</div>
                <div class="os-stat-lbl">Total Alokasi Modal</div>
                <div class="os-stat-foot"><span class="os-foot-pill pill-amber">Sisa: Rp {{ number_format((float) ($totalRemaining ?? 0), 0, ',', '.') }}</span></div>
            </div>

            <div class="os-stat stat-rose">
                <div class="os-stat-deco"></div>
                <div class="os-stat-head">
                    <div class="os-stat-ico">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
                <div class="os-stat-val os-mono">Rp {{ number_format((float) ($totalUsed ?? 0), 0, ',', '.') }}</div>
                <div class="os-stat-lbl">Total Dana Terpakai</div>
                <div class="os-stat-foot"><span class="os-foot-pill pill-rose">Dari total alokasi</span></div>
            </div>
        </div>

        {{-- ═══ SESSION CONTROLLER ═══ --}}
        <div class="os-panel" style="margin-bottom: 1.5rem;">
            <div class="os-panel-head">
                <div class="os-panel-title-row">
                    <div class="os-panel-title-box">
                        <div class="os-panel-ico">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        </div>
                        <div>
                            <h3 class="os-panel-title">Kontrol Sesi Operasional</h3>
                            <p class="os-panel-sub">Buka sesi baru dengan modal awal atau tutup sesi yang sedang berjalan.</p>
                        </div>
                    </div>
                    @if(($activeSession->status ?? null) === 'open')
                        <span class="os-status-pill pill-open"><span class="pill-dot dot-green"></span>Sesi Aktif</span>
                    @else
                        <span class="os-status-pill pill-closed"><span class="pill-dot dot-red"></span>Tidak Ada Sesi</span>
                    @endif
                </div>
            </div>

            <div class="os-panel-body">
                @if(($activeSession->status ?? null) === 'open')
                    @php
                        $used = (float) ($activeSession->expenses_sum_amount ?? 0);
                        $remain = max(0, (float) $activeSession->opening_amount - $used);
                    @endphp

                    <div class="os-session-info">
                        <div class="os-info-row">
                            <div class="os-info-chip">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>{{ \Carbon\Carbon::parse($activeSession->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="os-info-chip">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>{{ $activeSession->user->name ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="os-finance-row">
                            <div class="os-fin fin-amber">
                                <span class="os-fin-lbl">Modal Awal</span>
                                <span class="os-fin-val os-mono">Rp {{ number_format((float) $activeSession->opening_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="os-fin fin-rose">
                                <span class="os-fin-lbl">Dana Terpakai</span>
                                <span class="os-fin-val os-mono">Rp {{ number_format($used, 0, ',', '.') }}</span>
                            </div>
                            <div class="os-fin fin-emerald">
                                <span class="os-fin-lbl">Sisa Saldo Kas</span>
                                <span class="os-fin-val os-mono">Rp {{ number_format($remain, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @can('manage_sesi_operasional')
                            <form method="POST" action="{{ route('operasional.close_session') }}" class="os-close-form" onsubmit="return confirm('Apakah Anda yakin ingin menutup sesi operasional ini?');">
                                @csrf
                                <div class="os-close-fields">
                                    <div class="os-field">
                                        <label class="os-label">Saldo Akhir Aktual <span class="os-opt">(Opsional)</span></label>
                                        <div class="os-prefix-wrap">
                                            <span class="os-prefix">Rp</span>
                                            <input type="number" name="closing_amount" class="os-input os-mono" min="0" value="{{ old('closing_amount', 0) }}" placeholder="0">
                                        </div>
                                        <p class="os-hint">Catat saldo riil di laci. Kosongkan = sistem akan mencatat sesuai sisa saldo kas (Modal - Terpakai).</p>
                                    </div>
                                </div>
                                <button type="submit" class="os-btn os-btn-danger">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Tutup Sesi
                                </button>
                            </form>
                        @else
                            <div class="os-info-alert">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                Anda tidak memiliki izin untuk menutup sesi. Hubungi Supervisor.
                            </div>
                        @endcan
                    </div>

                @else
                    @can('manage_sesi_operasional')
                        <form method="POST" action="{{ route('operasional.open_session') }}" class="os-open-form">
                            @csrf
                            <div class="os-form-grid">
                                <div class="os-field">
                                    <label class="os-label">Modal Awal <span class="os-req">*</span></label>
                                    <div class="os-prefix-wrap">
                                        <span class="os-prefix">Rp</span>
                                        <input type="number" name="opening_amount" class="os-input os-mono @error('opening_amount') input-err @enderror" min="0" required value="{{ old('opening_amount', 0) }}">
                                    </div>
                                    @error('opening_amount') <p class="os-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="os-field">
                                    <label class="os-label">Metode Sumber Modal <span class="os-req">*</span></label>
                                    <div class="os-select-wrap">
                                        <select name="payment_method" class="os-select" required>
                                            @php $pm = old('payment_method', 'Tunai'); @endphp
                                            <option value="Tunai" {{ $pm === 'Tunai' ? 'selected' : '' }}>Uang Tunai (Cash)</option>
                                            <option value="Transfer" {{ $pm === 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="os-field" style="margin-top: 1rem;">
                                <label class="os-label">Catatan Sesi <span class="os-opt">(Opsional)</span></label>
                                <textarea name="notes" rows="2" class="os-textarea" placeholder="Contoh: Alokasi modal operasional hari ini...">{{ old('notes') }}</textarea>
                            </div>
                            <div class="os-form-actions">
                                <button type="submit" class="os-btn os-btn-success">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Buka Sesi Baru
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="os-locked">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <h4>Sesi Operasional Ditutup</h4>
                            <p>Sistem tidak menerima catatan pengeluaran. Hubungi Supervisor untuk membuka sesi baru.</p>
                        </div>
                    @endcan
                @endif
            </div>
        </div>

        {{-- ═══ SESSION HISTORY TABLE ═══ --}}
        <div class="os-panel">
            <div class="os-panel-head">
                <div class="os-panel-title-box">
                    <div class="os-panel-ico">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                    </div>
                    <div>
                        <h3 class="os-panel-title">Riwayat Log Sesi</h3>
                        <p class="os-panel-sub">Data historis sesi operasional yang pernah dijalankan.</p>
                    </div>
                </div>
            </div>

            <div class="os-table-wrap">
                <table class="os-table">
                    <thead>
                        <tr>
                            <th>Waktu Pembukaan</th>
                            <th>PIC / Admin</th>
                            <th class="os-r">Modal Awal</th>
                            <th class="os-r">Total Terpakai</th>
                            <th class="os-r">Sisa Modal</th>
                            <th class="os-c">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sessions as $session)
                            @php
                                $terpakai = (float) ($session->expenses_sum_amount ?? 0);
                                $sisa = (float) $session->opening_amount - $terpakai;
                            @endphp
                            <tr>
                                <td><span class="os-date">{{ \Carbon\Carbon::parse($session->created_at)->format('d M Y, H:i') }}</span></td>
                                <td><span class="os-pic">{{ $session->user->name ?? '-' }}</span></td>
                                <td class="os-r os-mono td-indigo">Rp {{ number_format((float) $session->opening_amount, 0, ',', '.') }}</td>
                                <td class="os-r os-mono td-rose">Rp {{ number_format($terpakai, 0, ',', '.') }}</td>
                                <td class="os-r os-mono fw-bold {{ $sisa < 0 ? 'td-rose' : 'td-emerald' }}">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                                <td class="os-c">
                                    @if($session->status === 'open')
                                        <span class="os-pill pill-emerald"><span class="pill-dot dot-green"></span>Aktif</span>
                                    @else
                                        <span class="os-pill pill-gray">Ditutup</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">
                                <div class="os-empty">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <h4>Belum ada riwayat sesi</h4>
                                    <p>Data sesi akan muncul setelah Anda membuka dan menutup sesi operasional pertama.</p>
                                </div>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sessions->hasPages())
                <div class="os-paginate">{{ $sessions->links() }}</div>
            @endif
        </div>

    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        .os-page { font-family: 'Plus Jakarta Sans', sans-serif; max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* Header */
        .os-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .os-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 99px; background: #EEF2FF; color: #4338CA; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
        .os-title { font-size: 1.5rem; font-weight: 800; margin: 0; letter-spacing: -.02em; }
        .os-subtitle { font-size: .85rem; color: #64748b; margin-top: 4px; }
        .os-header-actions { display: flex; gap: .5rem; flex-shrink: 0; }

        /* Buttons */
        .os-btn { display: inline-flex; align-items: center; gap: 7px; padding: .55rem 1.1rem; border-radius: 8px; font-size: .82rem; font-weight: 700; cursor: pointer; transition: .2s; border: 1.5px solid transparent; text-decoration: none; }
        .os-btn-primary { background: #4f46e5; color: #fff; border-color: #4f46e5; }
        .os-btn-primary:hover { background: #4338ca; border-color: #4338ca; box-shadow: 0 4px 12px rgba(79,70,229,.25); }
        .os-btn-ghost { background: #fff; color: #334155; border-color: #e2e8f0; }
        .os-btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; }
        .os-btn-success { background: #10b981; color: #fff; border-color: #10b981; }
        .os-btn-success:hover { background: #059669; border-color: #059669; box-shadow: 0 4px 12px rgba(16,185,129,.25); }
        .os-btn-danger { background: #ef4444; color: #fff; border-color: #ef4444; }
        .os-btn-danger:hover { background: #dc2626; border-color: #dc2626; box-shadow: 0 4px 12px rgba(239,68,68,.25); }

        /* Alerts */
        .os-alert { padding: .85rem 1.15rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: .85rem; }
        .os-alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .os-alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Stat Cards */
        .os-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem; }
        .os-stat { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem 1.5rem; position: relative; overflow: hidden; transition: border-color .2s, box-shadow .2s; }
        .os-stat:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
        .os-stat-deco { position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; opacity: .06; }
        .stat-indigo { border-top: 3px solid #6366f1; } .stat-indigo .os-stat-deco { background: #6366f1; } .stat-indigo .os-stat-ico { background: #EEF2FF; color: #4f46e5; }
        .stat-emerald { border-top: 3px solid #10b981; } .stat-emerald .os-stat-deco { background: #10b981; } .stat-emerald .os-stat-ico { background: #f0fdf4; color: #059669; }
        .stat-amber { border-top: 3px solid #f59e0b; } .stat-amber .os-stat-deco { background: #f59e0b; } .stat-amber .os-stat-ico { background: #fffbeb; color: #d97706; }
        .stat-rose { border-top: 3px solid #f43f5e; } .stat-rose .os-stat-deco { background: #f43f5e; } .stat-rose .os-stat-ico { background: #fff1f2; color: #e11d48; }
        .os-stat-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: .75rem; }
        .os-stat-ico { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .os-stat-val { font-size: 1.5rem; font-weight: 900; line-height: 1; margin-bottom: 4px; }
        .os-stat-lbl { font-size: .68rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; }
        .os-stat-foot { margin-top: .65rem; }
        .os-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }

        .os-foot-pill { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: .62rem; font-weight: 700; }
        .pill-indigo { background: #EEF2FF; color: #4338CA; }
        .pill-emerald { background: #f0fdf4; color: #059669; }
        .pill-amber { background: #fffbeb; color: #b45309; }
        .pill-rose { background: #fff1f2; color: #be123c; }

        .os-live { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 99px; font-size: .62rem; font-weight: 700; background: #f0fdf4; color: #15803d; }
        .os-live-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; animation: os-pulse 1.5s infinite; }
        .os-live.live-off { background: #f8fafc; color: #94a3b8; }
        .os-live.live-off .os-live-dot { background: #cbd5e1; animation: none; }
        @@keyframes os-pulse { 0%,100% { opacity:1; } 50% { opacity:.3; } }

        /* Panel */
        .os-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
        .os-panel-head { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .75rem; }
        .os-panel-title-row { display: flex; align-items: center; gap: .75rem; }
        .os-panel-title-box { display: flex; align-items: center; gap: .75rem; }
        .os-panel-ico { width: 36px; height: 36px; border-radius: 9px; background: #EEF2FF; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .os-panel-title { font-size: .95rem; font-weight: 800; margin: 0; }
        .os-panel-sub { font-size: .72rem; color: #94a3b8; margin-top: 2px; }
        .os-panel-body { padding: 1.5rem; }

        /* Status pills */
        .os-status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 99px; font-size: .72rem; font-weight: 700; }
        .pill-dot { width: 7px; height: 7px; border-radius: 50%; }
        .pill-open { background: #f0fdf4; color: #15803d; }
        .pill-open .pill-dot { background: #22c55e; animation: os-pulse 1.5s infinite; }
        .pill-closed { background: #fef2f2; color: #991b1b; }
        .pill-closed .pill-dot { background: #ef4444; }

        /* Session info */
        .os-session-info { background: #fafbfc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; }
        .os-info-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px dashed #e2e8f0; }
        .os-info-chip { display: inline-flex; align-items: center; gap: 7px; padding: 6px 12px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; font-size: .8rem; font-weight: 600; color: #334155; }
        .os-info-chip svg { color: #94a3b8; }

        .os-finance-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .os-fin { padding: 1rem 1.25rem; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; display: flex; flex-direction: column; gap: 4px; border-left-width: 4px; }
        .os-fin-lbl { font-size: .68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; }
        .os-fin-val { font-size: 1.2rem; font-weight: 900; }
        .fin-amber { border-left-color: #f59e0b; } .fin-amber .os-fin-val { color: #d97706; }
        .fin-rose { border-left-color: #f43f5e; } .fin-rose .os-fin-val { color: #e11d48; }
        .fin-emerald { border-left-color: #10b981; } .fin-emerald .os-fin-val { color: #059669; }

        .os-close-form { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .os-close-fields { flex: 1; min-width: 200px; }
        .os-info-alert { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: .85rem 1rem; border-radius: 8px; display: flex; align-items: center; gap: 8px; font-size: .8rem; font-weight: 600; }

        /* Forms */
        .os-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .os-field { display: flex; flex-direction: column; gap: 5px; }
        .os-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: .04em; }
        .os-req { color: #ef4444; }
        .os-opt { font-weight: 500; text-transform: none; letter-spacing: 0; }
        .os-input, .os-textarea, .os-select { padding: .6rem .9rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: .85rem; background: #fcfcfd; transition: .2s; font-family: inherit; color: #0f172a; width: 100%; outline: none; }
        .os-input:focus, .os-select:focus, .os-textarea:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
        .os-textarea { resize: vertical; min-height: 70px; }
        .os-hint { font-size: .7rem; color: #94a3b8; margin-top: 3px; }
        .os-error { color: #ef4444; font-size: .72rem; font-weight: 600; }
        .os-prefix-wrap { display: flex; align-items: stretch; }
        .os-prefix { display: flex; align-items: center; padding: 0 .75rem; background: #f1f5f9; border: 1.5px solid #e2e8f0; border-right: none; border-radius: 8px 0 0 8px; font-size: .8rem; font-weight: 800; color: #64748b; }
        .os-prefix-wrap .os-input { border-radius: 0 8px 8px 0; }
        .os-select-wrap { position: relative; }
        .os-select-wrap::after { content: ''; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .os-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }
        .os-form-actions { margin-top: 1.25rem; display: flex; justify-content: flex-end; }
        .os-open-form { max-width: 100%; }
        .os-locked { padding: 2.5rem; text-align: center; color: #94a3b8; }
        .os-locked svg { margin-bottom: .5rem; }
        .os-locked h4 { font-size: .95rem; font-weight: 800; color: #334155; margin: 0 0 4px; }
        .os-locked p { font-size: .8rem; margin: 0; }

        /* Table */
        .os-table-wrap { width: 100%; overflow-x: auto; }
        .os-table { width: 100%; border-collapse: collapse; min-width: 750px; }
        .os-table thead th { background: #f8fafc; padding: .8rem 1.25rem; text-align: left; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; border-bottom: 1px solid #e2e8f0; }
        .os-table tbody td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: .82rem; vertical-align: middle; }
        .os-table tbody tr:hover { background: #fafbfc; }
        .os-r { text-align: right; }
        .os-c { text-align: center; }
        .os-date { font-weight: 600; color: #334155; }
        .os-pic { font-weight: 700; }
        .td-indigo { color: #4f46e5; font-weight: 800; }
        .td-emerald { color: #059669; }
        .td-rose { color: #e11d48; }
        .fw-bold { font-weight: 800; }

        .os-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 99px; font-size: .68rem; font-weight: 700; }
        .os-pill.pill-emerald { background: #f0fdf4; color: #059669; }
        .os-pill.pill-emerald .pill-dot { background: #22c55e; }
        .os-pill.pill-gray { background: #f1f5f9; color: #94a3b8; }

        .os-empty { padding: 3rem 2rem; text-align: center; }
        .os-empty svg { color: #cbd5e1; margin-bottom: .5rem; }
        .os-empty h4 { font-size: .95rem; font-weight: 800; color: #334155; margin: 0 0 4px; }
        .os-empty p { font-size: .8rem; color: #94a3b8; margin: 0; }

        .os-paginate { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; }

        @@media (max-width: 900px) { .os-stats { grid-template-columns: repeat(2, 1fr); } .os-finance-row { grid-template-columns: 1fr; } }
        @@media (max-width: 640px) {
            .os-page { padding: 1rem; }
            .os-header { flex-direction: column; align-items: stretch; }
            .os-header-actions { flex-direction: column; }
            .os-btn { justify-content: center; }
            .os-stats { grid-template-columns: 1fr; }
            .os-form-grid { grid-template-columns: 1fr; }
            .os-close-form { flex-direction: column; align-items: stretch; }
        }
        @@media print {
            .os-header-actions, .os-close-form, .os-open-form, .os-paginate { display: none !important; }
            .os-page { padding: 0; } .os-stat, .os-panel { break-inside: avoid; box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>
    @endpush
</x-app-layout>
