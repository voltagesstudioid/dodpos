<x-app-layout>
    <x-slot name="header">Ringkasan Sesi Kasir</x-slot>

    <div class="tr-app-bg">
        <div class="tr-container">

            {{-- ─── HEADER ─── --}}
            <div class="tr-page-header">
                <div>
                    <h1 class="tr-title">Ringkasan Kasir</h1>
                    <p class="tr-subtitle">Pantau aktivitas kas fisik, mutasi, dan performa sesi berjalan.</p>
                </div>
                <div class="tr-header-actions">
                    @can('view_pos_kasir')
                        <a href="{{ route('kasir.index') }}" class="tr-btn-pos">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            Buka Layar POS
                        </a>
                    @endcan
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success')) <div class="tr-alert tr-alert-success">✅ {{ session('success') }}</div> @endif
            @if(session('error')) <div class="tr-alert tr-alert-danger">❌ {{ session('error') }}</div> @endif


            @if(!$activeSession)
                {{-- ─── NO ACTIVE SESSION ─── --}}
                <div class="tr-empty-hero">
                    <div class="icon-ring">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <h2>Sesi Kasir Belum Dibuka</h2>
                    <p>Anda belum memasukkan modal awal hari ini. Sistem POS terkunci hingga sesi baru dimulai.</p>
                    @can('view_pos_kasir')
                        <a href="{{ route('kasir.index') }}" class="tr-btn-primary">Mulai Sesi Sekarang &rarr;</a>
                    @endcan
                </div>
            @else
                {{-- ─── ACTIVE SESSION ─── --}}
                
                {{-- 1. MAIN WALLET CARD (Estimasi Kas & Form Tutup) --}}
                <div class="tr-wallet-card">
                    <div class="wallet-glow"></div>
                    <div class="wallet-content">
                        <div class="wallet-balance-section">
                            <span class="wallet-label">Estimasi Uang Fisik Laci (Seharusnya)</span>
                            <div class="wallet-balance">
                                <span class="currency">Rp</span>{{ number_format($expectedCash ?? 0, 0, ',', '.') }}
                            </div>
                            <div class="wallet-formula">
                                = Modal + Penjualan Tunai + DP Tunai + (Cash In - Cash Out)
                            </div>
                        </div>

                        @can('delete_sesi_kasir')
                            <div class="wallet-action-section">
                                <form method="POST" action="{{ route('kasir.close_session') }}" onsubmit="return confirm('Apakah Anda yakin ingin menutup sesi kasir ini? Pastikan uang fisik sudah dihitung.');" class="tr-close-form">
                                    @csrf
                                    <div class="close-inputs">
                                        <div class="input-wrapper">
                                            <span>Rp</span>
                                            <input type="number" name="actual_cash" placeholder="Uang Riil Laci" min="0" step="0.01" required>
                                        </div>
                                        <input type="text" name="notes" class="input-note" placeholder="Catatan selisih (Opsional)">
                                    </div>
                                    <button type="submit" class="btn-close-session">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        Tutup Kasir
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>

                {{-- 2. QUICK STATS (3 Columns) --}}
                <div class="tr-stats-row">
                    <div class="tr-stat-box">
                        <div class="stat-icon bg-indigo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                        <div class="stat-data">
                            <span class="label">Sesi Oleh: <strong>{{ $activeSession->user?->name ?? '-' }}</strong></span>
                            <span class="value text-indigo">{{ strtoupper($activeSession->status) }}</span>
                            <span class="desc">Buka: {{ optional($activeSession->opened_at ?? $activeSession->created_at)->format('H:i, d M Y') }}</span>
                        </div>
                    </div>
                    <div class="tr-stat-box">
                        <div class="stat-icon bg-emerald"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg></div>
                        <div class="stat-data">
                            <span class="label">Modal Awal Laci</span>
                            <span class="value text-emerald">Rp {{ number_format($activeSession->opening_amount ?? 0, 0, ',', '.') }}</span>
                            <span class="desc">Berdasarkan input awal hari</span>
                        </div>
                    </div>
                    <div class="tr-stat-box">
                        <div class="stat-icon bg-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                        <div class="stat-data">
                            <span class="label">Penjualan Tunai Bersih</span>
                            <span class="value text-blue">Rp {{ number_format($cashRevenue ?? 0, 0, ',', '.') }}</span>
                            <span class="desc">{{ $cashTransactions ?? 0 }} Transaksi Lunas</span>
                        </div>
                    </div>
                </div>

                {{-- 3. BENTO GRID (Detail Performa Kasir) --}}
                <div class="tr-bento-section">
                    <h3 class="section-title">Rincian Pergerakan Finansial</h3>
                    <div class="tr-bento-grid">
                        <div class="bento-card">
                            <div class="b-label">Total Omzet Keseluruhan</div>
                            <div class="b-val text-dark">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="bento-card">
                            <div class="b-label">Omzet Non-Tunai (TF/EDC)</div>
                            <div class="b-val text-purple">Rp {{ number_format($nonCashRevenue ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="bento-card">
                            <div class="b-label">Uang Muka (DP Tunai)</div>
                            <div class="b-val text-orange">Rp {{ number_format($creditDp ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="bento-card">
                            <div class="b-label">Total Transaksi (Struk)</div>
                            <div class="b-val text-dark">{{ $totalTransactions ?? 0 }} <small style="font-size:0.75rem;font-weight:600;color:#64748b;">Nota</small></div>
                        </div>
                    </div>
                </div>

                {{-- 4. MUTASI KAS MANUAL (Cash In / Out) --}}
                <div class="tr-mutasi-section">
                    <div class="mutasi-header">
                        <div>
                            <h3 class="section-title" style="margin:0;">Mutasi Kas Manual</h3>
                            <p class="section-subtitle">Catat pengeluaran/pemasukan laci di luar transaksi reguler.</p>
                        </div>
                    </div>

                    <div class="mutasi-body">
                        {{-- Form Input --}}
                        <div class="mutasi-form-card">
                            <form method="POST" action="{{ route('kasir.cash_movement') }}" class="quick-add-form">
                                @csrf
                                <div class="q-group w-type">
                                    <label>Tipe Mutasi</label>
                                    <div class="select-wrap">
                                        <select name="type" required>
                                            <option value="in">Masuk (IN)</option>
                                            <option value="out">Keluar (OUT)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="q-group w-amount">
                                    <label>Nominal (Rp)</label>
                                    <input type="number" name="amount" min="0.01" step="0.01" required placeholder="0">
                                </div>
                                <div class="q-group w-note">
                                    <label>Keterangan Tujuan</label>
                                    <input type="text" name="notes" placeholder="Misal: Uang kembalian, beli galon...">
                                </div>
                                <div class="q-group w-btn">
                                    <button type="submit">Catat Mutasi</button>
                                </div>
                            </form>
                        </div>

                        {{-- Table History --}}
                        @if(isset($cashMovements) && $cashMovements->count())
                            <div class="mutasi-table-card">
                                <div class="table-responsive">
                                    <table class="tr-table">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th class="c">Tipe</th>
                                                <th class="r">Nominal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cashMovements as $m)
                                                <tr>
                                                    <td class="text-muted">{{ optional($m->created_at)->format('H:i, d M') }}</td>
                                                    <td class="c">
                                                        @if($m->type === 'in')
                                                            <span class="badge-in">CASH IN</span>
                                                        @else
                                                            <span class="badge-out">CASH OUT</span>
                                                        @endif
                                                    </td>
                                                    <td class="r tr-font-mono {{ $m->type === 'in' ? 'text-emerald' : 'text-danger' }} font-bold">
                                                        {{ $m->type === 'in' ? '+' : '-' }}Rp {{ number_format((float) $m->amount, 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ $m->notes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --c-indigo: #4f46e5; --c-indigo-dark: #3730a3; --c-indigo-light: #e0e7ff;
            --c-emerald: #10b981; --c-emerald-light: #dcfce7;
            --c-blue: #3b82f6; --c-blue-light: #dbeafe;
            --c-danger: #ef4444; --c-danger-dark: #dc2626; --c-danger-light: #fee2e2;
            --c-warning: #f59e0b; --c-warning-light: #fef3c7;
            --c-purple: #8b5cf6;
            --bg-main: #f3f4f6; --bg-surface: #ffffff; --border: #e5e7eb;
            --t-main: #111827; --t-muted: #6b7280;
            --radius-lg: 20px; --radius-md: 12px;
        }

        .tr-app-bg { background-color: var(--bg-main); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--t-main); padding-bottom: 4rem; }
        .tr-container { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── HEADER ── */
        .tr-page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-title { font-size: 1.75rem; font-weight: 900; margin: 0 0 4px; letter-spacing: -0.02em; }
        .tr-subtitle { color: var(--t-muted); font-size: 0.95rem; margin: 0; }
        
        .tr-btn-pos { display: inline-flex; align-items: center; gap: 8px; background: var(--t-main); color: #fff; padding: 0.75rem 1.5rem; border-radius: 99px; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: 0.2s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .tr-btn-pos:hover { background: #000; transform: translateY(-2px); }

        /* ── ALERTS ── */
        .tr-alert { padding: 1rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 600; font-size: 0.9rem; }
        .tr-alert-success { background: var(--c-emerald-light); color: #065f46; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background: var(--c-danger-light); color: #991b1b; border: 1px solid #fecaca; }

        /* ── 1. WALLET HERO CARD ── */
        .tr-wallet-card { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-radius: 24px; padding: 2.5rem; position: relative; overflow: hidden; margin-bottom: 1.5rem; color: #fff; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .wallet-glow { position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(99,102,241,0.4) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none; }
        .wallet-content { display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; flex-wrap: wrap; gap: 2rem; }
        
        .wallet-label { font-size: 0.85rem; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem; }
        .wallet-balance { font-size: 3.5rem; font-weight: 900; line-height: 1; letter-spacing: -0.02em; font-family: ui-monospace, monospace; }
        .wallet-balance .currency { font-size: 1.5rem; vertical-align: super; opacity: 0.8; margin-right: 4px; }
        .wallet-formula { font-size: 0.8rem; color: rgba(255,255,255,0.5); margin-top: 0.75rem; font-weight: 500; }

        .wallet-action-section { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 1.25rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); width: 100%; max-width: 380px; }
        .tr-close-form { display: flex; flex-direction: column; gap: 0.75rem; }
        .close-inputs { display: flex; gap: 0.5rem; }
        .input-wrapper { display: flex; align-items: center; background: #fff; border-radius: 8px; overflow: hidden; flex: 1; }
        .input-wrapper span { padding: 0 0.75rem; color: var(--t-muted); font-weight: 800; font-size: 0.9rem; background: #f3f4f6; height: 100%; display: flex; align-items: center; border-right: 1px solid var(--border); }
        .input-wrapper input { border: none; padding: 0.6rem; width: 100%; font-weight: 700; font-family: monospace; font-size: 1rem; outline: none; color: var(--t-main); }
        .input-note { border: none; border-radius: 8px; padding: 0.6rem 0.75rem; outline: none; flex: 1; font-size: 0.85rem; }
        .btn-close-session { background: var(--c-danger); color: #fff; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; transition: 0.2s; font-size: 0.95rem; }
        .btn-close-session:hover { background: var(--c-danger-dark); }

        /* ── 2. QUICK STATS ── */
        .tr-stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .tr-stat-box { background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-data { display: flex; flex-direction: column; }
        .stat-data .label { font-size: 0.7rem; font-weight: 700; color: var(--t-muted); text-transform: uppercase; margin-bottom: 2px; }
        .stat-data .value { font-size: 1.2rem; font-weight: 900; font-family: monospace; line-height: 1.2; }
        .stat-data .desc { font-size: 0.75rem; color: var(--t-muted); font-weight: 500; margin-top: 2px; }

        /* ── 3. BENTO GRID ── */
        .tr-bento-section { margin-bottom: 2rem; }
        .section-title { font-size: 1.1rem; font-weight: 800; color: var(--t-main); margin: 0 0 1rem; }
        .tr-bento-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .bento-card { background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; display: flex; flex-direction: column; justify-content: center; }
        .bento-card .b-label { font-size: 0.8rem; font-weight: 700; color: var(--t-muted); margin-bottom: 4px; }
        .bento-card .b-val { font-size: 1.3rem; font-weight: 900; font-family: monospace; }

        /* ── 4. MUTASI KAS ── */
        .mutasi-header { margin-bottom: 1rem; }
        .section-subtitle { font-size: 0.85rem; color: var(--t-muted); margin: 2px 0 0; }
        .mutasi-body { display: flex; flex-direction: column; gap: 1rem; }
        
        .mutasi-form-card { background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; }
        .quick-add-form { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .q-group { display: flex; flex-direction: column; gap: 6px; }
        .q-group label { font-size: 0.75rem; font-weight: 700; color: var(--t-muted); text-transform: uppercase; }
        .w-type { width: 140px; } .w-amount { width: 180px; } .w-note { flex-grow: 1; }
        
        .quick-add-form input, .quick-add-form select { padding: 0.7rem 1rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.9rem; font-family: inherit; outline: none; background: #f9fafb; transition: 0.2s; }
        .quick-add-form input:focus, .quick-add-form select:focus { border-color: var(--c-indigo); background: #fff; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .quick-add-form button { background: var(--t-main); color: #fff; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s; height: 42px; }
        .quick-add-form button:hover { background: #000; transform: translateY(-1px); }

        .select-wrap { position: relative; }
        .select-wrap::after { content: '▼'; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 0.7rem; color: var(--t-muted); pointer-events: none; }
        .select-wrap select { appearance: none; width: 100%; }

        /* ── TABLES ── */
        .mutasi-table-card { background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .tr-table thead th { background: #f9fafb; padding: 0.75rem 1rem; text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--t-muted); border-bottom: 1px solid var(--border); }
        .tr-table tbody td { padding: 1rem; border-bottom: 1px solid #f3f4f6; font-size: 0.85rem; }
        .tr-table tbody tr:hover { background: #f9fafb; }
        .tr-table .c { text-align: center; } .tr-table .r { text-align: right; }

        /* ── UTILS ── */
        .bg-indigo { background: var(--c-indigo-light); color: var(--c-indigo); }
        .bg-emerald { background: var(--c-emerald-light); color: var(--c-emerald); }
        .bg-blue { background: var(--c-blue-light); color: var(--c-blue); }
        .text-indigo { color: var(--c-indigo); } .text-emerald { color: var(--c-emerald); }
        .text-blue { color: var(--c-blue); } .text-dark { color: var(--t-main); }
        .text-purple { color: var(--c-purple); } .text-orange { color: var(--c-warning); }
        .text-danger { color: var(--c-danger); } .text-muted { color: var(--t-muted); }
        
        .font-bold { font-weight: 800; }
        .tr-font-mono { font-family: ui-monospace, monospace; }
        
        .badge-in { background: var(--c-emerald-light); color: #065f46; padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; }
        .badge-out { background: var(--c-danger-light); color: #991b1b; padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; }

        /* EMPTY STATE HERO */
        .tr-empty-hero { background: var(--bg-surface); border: 1px dashed #cbd5e1; border-radius: var(--radius-lg); padding: 4rem 2rem; text-align: center; max-width: 600px; margin: 2rem auto; }
        .icon-ring { width: 80px; height: 80px; background: #f1f5f9; color: var(--t-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
        .icon-ring svg { width: 36px; height: 36px; }
        .tr-empty-hero h2 { font-size: 1.5rem; font-weight: 900; margin: 0 0 0.5rem; }
        .tr-empty-hero p { color: var(--t-muted); margin: 0 0 2rem; line-height: 1.5; }
        .tr-btn-primary { background: var(--c-indigo); color: #fff; padding: 0.85rem 2rem; border-radius: 99px; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.2s; }
        .tr-btn-primary:hover { background: var(--c-indigo-dark); }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .tr-stats-row { grid-template-columns: 1fr; }
            .wallet-content { flex-direction: column; align-items: flex-start; }
            .wallet-action-section { max-width: 100%; }
        }
        @media (max-width: 640px) {
            .close-inputs { flex-direction: column; }
            .quick-add-form { flex-direction: column; align-items: stretch; }
            .w-type, .w-amount, .w-note, .w-btn { width: 100%; }
            .quick-add-form button { height: auto; }
        }
    </style>
    @endpush
</x-app-layout>