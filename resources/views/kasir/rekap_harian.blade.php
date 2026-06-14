<x-app-layout>
    <x-slot name="header">Rekap & Closing Harian</x-slot>

    <div class="rh-page">

        {{-- HEADER --}}
        <div class="rh-head">
            <div>
                <h1 class="rh-title">Rekap & Closing Harian</h1>
                <p class="rh-subtitle">Pantau pendapatan, sesi kasir, dan closing untuk {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('kasir.rekap_harian') }}" class="rh-refresh" title="Muat ulang">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
            </a>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="rh-alert rh-alert-ok">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rh-alert rh-alert-err">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- SESSION MANAGEMENT PANEL (Eceran only — grosir follows eceran) --}}
        @if($kasirUsers->count() > 0)
        <div class="rh-panel">
            <div class="rh-panel-head">
                <div class="rh-panel-title">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Buka Sesi Kasir Eceran
                </div>
                <span class="rh-panel-hint">Grosir tidak perlu sesi terpisah — ikut aktif saat eceran dibuka, dan ikut ditutup saat eceran closing.</span>
            </div>

            <div class="rh-panel-cards">
                @foreach($kasirUsers as $kasirUser)
                    @if($kasirUser->eceran_session)
                        <div class="rh-card rh-card-active">
                            <div class="rh-card-top">
                                <div class="rh-av eceran">{{ strtoupper(substr($kasirUser->name, 0, 1)) }}</div>
                                <div class="rh-card-id">
                                    <span class="rh-card-name">{{ $kasirUser->name }}</span>
                                    <span class="rh-card-role">{{ strtoupper($kasirUser->role) }}</span>
                                </div>
                                <span class="rh-pill active">
                                    <span class="rh-pill-dot"></span> Aktif
                                </span>
                            </div>
                            <div class="rh-card-detail">
                                <div>
                                    <span class="rh-detail-lbl">Modal Awal</span>
                                    <span class="rh-detail-val">Rp {{ number_format($kasirUser->eceran_session->opening_amount, 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="rh-detail-lbl">Dibuka</span>
                                    <span class="rh-detail-val">{{ $kasirUser->eceran_session->created_at->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rh-card">
                            <div class="rh-card-top">
                                <div class="rh-av idle">{{ strtoupper(substr($kasirUser->name, 0, 1)) }}</div>
                                <div class="rh-card-id">
                                    <span class="rh-card-name">{{ $kasirUser->name }}</span>
                                    <span class="rh-card-role">{{ strtoupper($kasirUser->role) }}</span>
                                </div>
                                <span class="rh-pill closed">Belum Buka</span>
                            </div>
                            <form action="{{ route('kasir.open_session_for') }}" method="POST" class="rh-form">
                                @csrf
                                <input type="hidden" name="target_user_id" value="{{ $kasirUser->id }}">
                                <div class="rh-field">
                                    <label>Modal Awal (Rp)</label>
                                    <input type="number" name="opening_amount" min="0" placeholder="Masukkan modal awal" required>
                                </div>
                                <button type="submit" class="rh-btn-primary">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
                                    Buka Sesi Eceran
                                </button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- HERO: TOTAL OMZET HARI INI --}}
        <div class="rh-hero">
            <div class="rh-hero-glow"></div>
            <div class="rh-hero-inner">
                <div>
                    <div class="rh-hero-label">TOTAL OMZET HARI INI</div>
                    <div class="rh-hero-amount"><span class="rh-hero-rp">Rp</span>{{ number_format($todayRevenue, 0, ',', '.') }}</div>
                    <div class="rh-hero-chip">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 8h.01M10 8h.01"/></svg>
                        {{ $totalTransactions }} Transaksi Selesai
                    </div>
                </div>
                <div class="rh-hero-side">
                    @php $activeSessions = $sessions->where('status', 'open')->count(); @endphp
                    @if($activeSessions > 0)
                        <span class="rh-hero-live">
                            <span class="rh-hero-live-dot"></span>
                            {{ $activeSessions }} Sesi Masih Aktif
                        </span>
                    @else
                        <span class="rh-hero-closed-badge">Semua Sesi Ditutup</span>
                    @endif
                    <div class="rh-hero-note">Dari total {{ $sessions->count() }} sesi kasir hari ini</div>
                </div>
            </div>
        </div>

        {{-- METRIC CARDS --}}
        <div class="rh-metrics">
            <div class="rh-metric">
                <div class="rh-metric-head">
                    <span class="rh-metric-lbl">Uang Seharusnya</span>
                    <span class="rh-metric-tag">Expected</span>
                </div>
                <div class="rh-metric-val indigo">Rp {{ number_format($totalExpectedCash, 0, ',', '.') }}</div>
                <div class="rh-metric-foot">Modal + Pemasukan Tunai − Pengeluaran</div>
            </div>
            <div class="rh-metric">
                <div class="rh-metric-head">
                    <span class="rh-metric-lbl">Uang Fisik Riil</span>
                    <span class="rh-metric-tag">Actual</span>
                </div>
                <div class="rh-metric-val">Rp {{ number_format($totalActualCash, 0, ',', '.') }}</div>
                <div class="rh-metric-foot">Hanya dari sesi yang sudah ditutup</div>
            </div>
            <div class="rh-metric">
                <div class="rh-metric-head">
                    <span class="rh-metric-lbl">Total Selisih</span>
                    <span class="rh-metric-tag {{ $totalVariance == 0 ? 'ok' : ($totalVariance < 0 ? 'err' : 'warn') }}">
                        {{ $totalVariance == 0 ? 'Balanced' : ($totalVariance > 0 ? 'Surplus' : 'Shortage') }}
                    </span>
                </div>
                <div class="rh-metric-val {{ $totalVariance < 0 ? 'red' : ($totalVariance > 0 ? 'green' : 'muted') }}">
                    {{ $totalVariance > 0 ? '+' : '' }}Rp {{ number_format($totalVariance, 0, ',', '.') }}
                </div>
                <div class="rh-metric-foot">Berdasarkan sesi yang sudah ditutup</div>
            </div>
        </div>

        {{-- SESSION TABLE --}}
        <div class="rh-section-head">
            <h2 class="rh-section-title">Sesi Kasir Eceran Hari Ini</h2>
            <span class="rh-section-count">{{ $sessions->count() }} sesi</span>
            @if($orphanedGrosirCount > 0)
                <form action="{{ route('kasir.cleanup_orphaned_grosir') }}" method="POST" style="display:inline;" onsubmit="return confirm('Bersihkan {{ $orphanedGrosirCount }} sesi grosir lama yang masih open?\n\nSesi ini adalah legacy dari sistem lama dan tidak mempengaruhi operasional saat ini.')">
                    @csrf
                    <button type="submit" class="rh-btn-close" style="font-size:0.7rem;padding:0.3rem 0.7rem;">
                        🧹 Bersihkan {{ $orphanedGrosirCount }} Sesi Grosir Lama
                    </button>
                </form>
            @endif
        </div>

        @if($sessions->isEmpty())
            <div class="rh-empty">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <p class="rh-empty-title">Belum Ada Sesi Kasir</p>
                <p class="rh-empty-desc">Belum ada sesi kasir eceran yang dibuka hari ini.</p>
            </div>
        @else
            <div class="rh-tbl-wrap">
                <table class="rh-tbl">
                    <thead>
                        <tr>
                            <th>Kasir</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th class="tr">Omzet</th>
                            <th class="tr">Expected</th>
                            <th class="tr">Actual</th>
                            <th class="tr">Selisih</th>
                            <th class="tc">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $s)
                            <tr>
                                <td>
                                    <div class="rh-tbl-kasir">
                                        <div class="rh-av-sm {{ $s->type }}">{{ strtoupper(substr($s->user->name ?? '?', 0, 1)) }}</div>
                                        <div>
                                            <div class="rh-tbl-name">{{ $s->user->name ?? 'Kasir' }}</div>
                                            <div class="rh-tbl-time">Dibuka {{ $s->created_at->format('H:i') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="rh-type-badge {{ $s->type }}">{{ strtoupper($s->type) }}</span>
                                </td>
                                <td>
                                    @if($s->status === 'open')
                                        <span class="rh-status-open">
                                            <span class="rh-status-dot"></span> Aktif
                                        </span>
                                    @else
                                        <span class="rh-status-closed">Ditutup</span>
                                        <div class="rh-tbl-time">{{ \Carbon\Carbon::parse($s->closed_at)->format('H:i') }}</div>
                                    @endif
                                </td>
                                <td class="tr rh-mono rh-tbl-amount">Rp {{ number_format($s->revenue ?? 0, 0, ',', '.') }}</td>
                                <td class="tr rh-mono">Rp {{ number_format($s->calculated_expected_cash ?? 0, 0, ',', '.') }}</td>
                                <td class="tr rh-mono">
                                    @if($s->status === 'open')
                                        <span class="rh-tbl-na">Belum Closing</span>
                                    @else
                                        Rp {{ number_format($s->actual_cash ?? 0, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="tr rh-mono">
                                    @if($s->status === 'open')
                                        <span class="rh-tbl-na">—</span>
                                    @else
                                        @php $var = ($s->actual_cash ?? 0) - ($s->calculated_expected_cash ?? 0); @endphp
                                        <span class="{{ $var < 0 ? 'rh-var-neg' : ($var > 0 ? 'rh-var-pos' : 'rh-var-zero') }}">
                                            {{ $var > 0 ? '+' : '' }}Rp {{ number_format($var, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="tc">
                                    @if($s->status === 'open')
                                        <form action="{{ route('kasir.force_close', $s->id) }}" method="POST"
                                            onsubmit="return confirm('Tutup paksa sesi {{ $s->user->name ?? 'Kasir' }} ({{ strtoupper($s->type) }})?\n\nActual Cash akan disamakan dengan Expected Cash (selisih = 0).')">
                                            @csrf
                                            <button type="submit" class="rh-btn-close">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                                Tutup Paksa
                                            </button>
                                        </form>
                                    @else
                                        <span class="rh-tbl-done">✓ Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

    @push('styles')
    <style>
        .rh-page { max-width: 1080px; margin: 0 auto; padding: 1.5rem 1.25rem 3rem; }

        /* HEADER */
        .rh-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; }
        .rh-title { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin: 0 0 3px; }
        .rh-subtitle { font-size: 0.8rem; color: #64748b; margin: 0; }
        .rh-refresh { display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #f1f5f9; color: #64748b; transition: 0.2s; }
        .rh-refresh:hover { background: #e2e8f0; color: #0f172a; }

        /* ALERTS */
        .rh-alert { display: flex; align-items: center; gap: 8px; padding: 0.7rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-weight: 600; font-size: 0.8rem; }
        .rh-alert-ok { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .rh-alert-err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* SESSION PANEL */
        .rh-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; margin-bottom: 1.25rem; }
        .rh-panel-head { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 1rem; padding-bottom: 0.875rem; border-bottom: 1px solid #f1f5f9; }
        .rh-panel-title { display: flex; align-items: center; gap: 7px; font-weight: 800; font-size: 0.9rem; color: #0f172a; }
        .rh-panel-title svg { color: #4f46e5; flex-shrink: 0; }
        .rh-panel-hint { font-size: 0.7rem; color: #94a3b8; margin-left: auto; text-align: right; max-width: 320px; line-height: 1.4; }

        .rh-panel-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0.75rem; }

        /* ADMIN CARD */
        .rh-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.875rem; }
        .rh-card-active { background: #f0fdf4; border-color: #86efac; }
        .rh-card-top { display: flex; align-items: center; gap: 8px; margin-bottom: 0.625rem; }
        .rh-av { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; color: #fff; flex-shrink: 0; }
        .rh-av.eceran { background: #4f46e5; }
        .rh-av.grosir { background: #0ea5e9; }
        .rh-av.idle { background: #94a3b8; }
        .rh-card-id { display: flex; flex-direction: column; }
        .rh-card-name { font-weight: 700; font-size: 0.82rem; color: #0f172a; line-height: 1.2; }
        .rh-card-role { font-size: 0.58rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }

        .rh-pill { margin-left: auto; font-size: 0.6rem; font-weight: 700; padding: 2px 8px; border-radius: 99px; flex-shrink: 0; }
        .rh-pill.active { background: #dcfce7; color: #166534; display: flex; align-items: center; gap: 4px; }
        .rh-pill-dot { width: 5px; height: 5px; background: #22c55e; border-radius: 50%; animation: rh-blink 1.5s infinite; }
        .rh-pill.closed { background: #f1f5f9; color: #64748b; }

        .rh-card-detail { display: flex; gap: 1rem; font-size: 0.72rem; padding-top: 0.5rem; border-top: 1px solid rgba(0,0,0,0.06); }
        .rh-detail-lbl { color: #64748b; font-weight: 500; }
        .rh-detail-val { color: #0f172a; font-weight: 700; font-family: ui-monospace, monospace; }

        /* FORM in card */
        .rh-form { display: flex; flex-direction: column; gap: 0.4rem; }
        .rh-field label { display: block; font-size: 0.62rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 3px; }
        .rh-field input { width: 100%; padding: 0.45rem 0.6rem; border: 1px solid #e2e8f0; border-radius: 7px; font-size: 0.82rem; font-family: inherit; box-sizing: border-box; transition: border 0.2s; }
        .rh-field input:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }

        .rh-btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 5px; background: #4f46e5; color: #fff; border: none; padding: 0.5rem 0.875rem; border-radius: 7px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: 0.2s; }
        .rh-btn-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .rh-btn-primary.grosir { background: #0ea5e9; }
        .rh-btn-primary.grosir:hover { background: #0284c7; }

        /* HERO */
        .rh-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%); border-radius: 14px; padding: 1.5rem 1.75rem; position: relative; overflow: hidden; margin-bottom: 1rem; }
        .rh-hero-glow { position: absolute; top: -40%; right: -8%; width: 260px; height: 260px; background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .rh-hero-inner { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .rh-hero-label { font-size: 0.62rem; font-weight: 700; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 0.5rem; }
        .rh-hero-amount { font-size: 2.2rem; font-weight: 900; font-family: ui-monospace, monospace; color: #fff; letter-spacing: -0.02em; line-height: 1; }
        .rh-hero-rp { font-size: 0.85rem; opacity: 0.55; margin-right: 3px; font-weight: 700; }
        .rh-hero-chip { display: inline-flex; align-items: center; gap: 5px; background: rgba(255,255,255,0.08); padding: 3px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 600; color: rgba(255,255,255,0.75); margin-top: 0.75rem; }
        .rh-hero-side { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; }
        .rh-hero-live { display: flex; align-items: center; gap: 6px; background: rgba(16,185,129,0.15); padding: 4px 11px; border-radius: 99px; font-size: 0.68rem; font-weight: 700; color: #6ee7b7; }
        .rh-hero-live-dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; animation: rh-blink 1.5s infinite; }
        .rh-hero-closed-badge { background: rgba(255,255,255,0.08); padding: 4px 11px; border-radius: 99px; font-size: 0.68rem; font-weight: 700; color: rgba(255,255,255,0.55); }
        .rh-hero-note { font-size: 0.72rem; color: rgba(255,255,255,0.5); }

        @keyframes rh-blink { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }

        /* METRICS */
        .rh-metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.75rem; }
        @media (max-width: 640px) { .rh-metrics { grid-template-columns: 1fr; } .rh-hero-amount { font-size: 1.75rem; } }
        .rh-metric { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.125rem 1.25rem; }
        .rh-metric-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .rh-metric-lbl { font-size: 0.63rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; }
        .rh-metric-tag { font-size: 0.56rem; font-weight: 700; padding: 2px 7px; border-radius: 5px; background: #f1f5f9; color: #64748b; }
        .rh-metric-tag.ok { background: #dcfce7; color: #166534; }
        .rh-metric-tag.err { background: #fee2e2; color: #991b1b; }
        .rh-metric-tag.warn { background: #fef3c7; color: #92400e; }
        .rh-metric-val { font-size: 1.2rem; font-weight: 900; font-family: ui-monospace, monospace; color: #0f172a; }
        .rh-metric-val.indigo { color: #4f46e5; }
        .rh-metric-val.red { color: #dc2626; }
        .rh-metric-val.green { color: #059669; }
        .rh-metric-val.muted { color: #94a3b8; }
        .rh-metric-foot { font-size: 0.64rem; color: #94a3b8; margin-top: 4px; }

        /* SECTION */
        .rh-section-head { display: flex; align-items: center; gap: 8px; margin-bottom: 0.875rem; }
        .rh-section-title { font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0; }
        .rh-section-count { font-size: 0.65rem; font-weight: 700; background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 99px; }

        /* EMPTY */
        .rh-empty { background: #fff; border: 2px dashed #e2e8f0; border-radius: 14px; padding: 3rem 2rem; text-align: center; max-width: 440px; margin: 0 auto; }
        .rh-empty-title { font-size: 1rem; font-weight: 700; color: #475569; margin: 0.75rem 0 4px; }
        .rh-empty-desc { font-size: 0.8rem; color: #94a3b8; margin: 0; }

        /* TABLE */
        .rh-tbl-wrap { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow-x: auto; }
        .rh-tbl { width: 100%; border-collapse: collapse; min-width: 680px; }
        .rh-tbl th { background: #f8fafc; padding: 0.7rem 1rem; text-align: left; font-size: 0.63rem; font-weight: 800; text-transform: uppercase; color: #94a3b8; border-bottom: 1px solid #e2e8f0; letter-spacing: 0.04em; }
        .rh-tbl td { padding: 0.8rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.8rem; }
        .rh-tbl tbody tr:hover { background: #fafbfd; }
        .rh-tbl .tr { text-align: right; }
        .rh-tbl .tc { text-align: center; }
        .rh-tbl-kasir { display: flex; align-items: center; gap: 8px; }
        .rh-av-sm { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; color: #fff; flex-shrink: 0; }
        .rh-av-sm.eceran { background: #4f46e5; }
        .rh-av-sm.grosir { background: #0ea5e9; }
        .rh-tbl-name { font-weight: 700; color: #0f172a; font-size: 0.8rem; }
        .rh-tbl-time { font-size: 0.68rem; color: #94a3b8; }
        .rh-tbl-amount { font-weight: 700; color: #4f46e5; }
        .rh-tbl-na { font-size: 0.72rem; color: #cbd5e1; }
        .rh-tbl-done { font-size: 0.72rem; color: #16a34a; font-weight: 600; }
        .rh-mono { font-family: ui-monospace, monospace; }

        .rh-type-badge { font-size: 0.62rem; font-weight: 700; padding: 2px 8px; border-radius: 5px; }
        .rh-type-badge.eceran { background: #e0e7ff; color: #3730a3; }
        .rh-type-badge.grosir { background: #e0f2fe; color: #075985; }

        .rh-status-open { display: inline-flex; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; color: #16a34a; }
        .rh-status-dot { width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: rh-blink 1.5s infinite; }
        .rh-status-closed { font-size: 0.72rem; font-weight: 600; color: #94a3b8; }

        .rh-var-neg { color: #dc2626; }
        .rh-var-pos { color: #059669; }
        .rh-var-zero { color: #94a3b8; }

        /* BUTTONS */
        .rh-btn-close { display: inline-flex; align-items: center; gap: 4px; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 0.35rem 0.7rem; border-radius: 6px; font-weight: 700; font-size: 0.7rem; cursor: pointer; transition: 0.2s; }
        .rh-btn-close:hover { background: #fca5a5; color: #7f1d1d; }
    </style>
    @endpush
</x-app-layout>
