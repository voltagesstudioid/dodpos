<x-app-layout>
    <x-slot name="header">Dashboard Admin 2</x-slot>

    <style>
        .da2-page{padding:1.5rem;display:flex;flex-direction:column;gap:1.5rem;font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;max-width:1100px;}

        /* ── greeting ── */
        .da2-greet{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;}
        .da2-greet h1{font-size:1.4rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;margin:0;}
        .da2-greet h1 span{color:#0d9488;}
        .da2-greet-date{font-size:.8rem;color:#64748b;font-weight:600;background:#f0fdfa;padding:.35rem .85rem;border-radius:8px;border:1px solid #ccfbf1;}

        /* ── stat cards ── */
        .da2-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1rem;}
        .da2-stat{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.35rem 1.3rem;display:flex;align-items:flex-start;gap:1rem;transition:all .25s;box-shadow:0 1px 3px rgba(0,0,0,.04);position:relative;overflow:hidden;}
        .da2-stat:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.07);border-color:#c7d2fe;}
        .da2-stat::after{content:'';position:absolute;top:0;right:0;width:80px;height:80px;border-radius:0 0 0 80px;opacity:.06;}
        .da2-stat.emerald::after{background:#059669;}
        .da2-stat.rose::after{background:#e11d48;}
        .da2-stat-ico{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .da2-stat-ico svg{width:22px;height:22px;}
        .da2-stat-ico.emerald{background:#ecfdf5;color:#059669;}
        .da2-stat-ico.rose{background:#fff1f2;color:#e11d48;}
        .da2-stat-info{display:flex;flex-direction:column;gap:.2rem;min-width:0;flex:1;}
        .da2-stat-label{font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;}
        .da2-stat-val{font-size:1.65rem;font-weight:800;line-height:1.1;letter-spacing:-.02em;}
        .da2-stat-val.emerald{color:#059669;}
        .da2-stat-val.rose{color:#e11d48;}
        .da2-stat-sub{font-size:.73rem;color:#94a3b8;margin-top:.15rem;}

        /* ── quick actions ── */
        .da2-section{display:flex;flex-direction:column;gap:1rem;}
        .da2-section-title{font-size:.88rem;font-weight:800;color:#0f172a;display:flex;align-items:center;gap:.5rem;}
        .da2-section-title svg{width:16px;height:16px;color:#0d9488;}
        .da2-quick{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.75rem;}
        .da2-quick-item{display:flex;gap:.85rem;align-items:center;padding:1rem 1.1rem;background:#fff;border:1px solid #e2e8f0;border-radius:14px;text-decoration:none;color:#0f172a;transition:all .2s;}
        .da2-quick-item:hover{border-color:#99f6e4;background:#f0fdfa;transform:translateY(-1px);box-shadow:0 4px 12px rgba(13,148,136,.08);}
        .da2-quick-ico{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .da2-quick-ico svg{width:20px;height:20px;}
        .da2-quick-ico.teal{background:#ccfbf1;color:#0d9488;}
        .da2-quick-ico.red{background:#ffe4e6;color:#e11d48;}
        .da2-quick-ico.indigo{background:#eef2ff;color:#6366f1;}
        .da2-quick-ico.amber{background:#fef3c7;color:#d97706;}
        .da2-quick-title{font-weight:700;font-size:.85rem;color:#0f172a;}
        .da2-quick-sub{font-size:.73rem;color:#64748b;margin-top:.1rem;}

        /* ── info panel ── */
        .da2-info{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:1.25rem 1.3rem;box-shadow:0 1px 3px rgba(0,0,0,.04);}
        .da2-info-title{font-size:.82rem;font-weight:800;color:#0f172a;margin-bottom:.85rem;display:flex;align-items:center;gap:.45rem;}
        .da2-info-title svg{width:15px;height:15px;color:#0d9488;}
        .da2-info-row{display:flex;justify-content:space-between;align-items:center;padding:.55rem 0;border-bottom:1px solid #f1f5f9;}
        .da2-info-row:last-child{border-bottom:none;padding-bottom:0;}
        .da2-info-key{font-size:.78rem;color:#64748b;}
        .da2-info-val{font-size:.78rem;font-weight:700;color:#0f172a;}
        .da2-badge{font-size:.68rem;font-weight:700;padding:.18rem .6rem;border-radius:999px;}
        .da2-badge-green{background:#ecfdf5;color:#059669;}
        .da2-badge-amber{background:#fffbeb;color:#d97706;}

        @@media(max-width:768px){
            .da2-page{padding:1rem;}
            .da2-stats{grid-template-columns:1fr;}
            .da2-quick{grid-template-columns:1fr;}
        }
    </style>

    <div class="da2-page">

        {{-- ─── GREETING ─── --}}
        <div class="da2-greet">
            <h1>Selamat Datang, <span>{{ Auth::user()->name }}</span> 👋</h1>
            <div class="da2-greet-date">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>

        {{-- ─── STAT CARDS ─── --}}
        <div class="da2-stats">
            <div class="da2-stat emerald">
                <div class="da2-stat-ico emerald">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <div class="da2-stat-info">
                    <div class="da2-stat-label">Omzet POS (Shift Anda)</div>
                    <div class="da2-stat-val emerald">Rp {{ number_format($omzetPOS, 0, ',', '.') }}</div>
                    <div class="da2-stat-sub">Transaksi hari ini milik Anda</div>
                </div>
            </div>
            <div class="da2-stat rose">
                <div class="da2-stat-ico rose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                </div>
                <div class="da2-stat-info">
                    <div class="da2-stat-label">Total Operasional Hari Ini</div>
                    <div class="da2-stat-val rose">Rp {{ number_format($pengeluaranOperasional, 0, ',', '.') }}</div>
                    <div class="da2-stat-sub">Seluruh pengeluaran operasional</div>
                </div>
            </div>
        </div>

        {{-- ─── QUICK ACTIONS ─── --}}
        <div class="da2-section">
            <div class="da2-section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                Akses Cepat
            </div>
            <div class="da2-quick">
                @can('view_pos_kasir')
                <a href="{{ route('kasir.index') }}" class="da2-quick-item">
                    <div class="da2-quick-ico teal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    </div>
                    <div>
                        <div class="da2-quick-title">Buka Kasir</div>
                        <div class="da2-quick-sub">Mulai transaksi POS</div>
                    </div>
                </a>
                @endcan

                <a href="{{ route('operasional.pengeluaran.create') }}" class="da2-quick-item">
                    <div class="da2-quick-ico red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </div>
                    <div>
                        <div class="da2-quick-title">Input Pengeluaran</div>
                        <div class="da2-quick-sub">Catat biaya operasional</div>
                    </div>
                </a>

                @can('view_sesi_kasir')
                <a href="{{ route('operasional.sesi.index') }}" class="da2-quick-item">
                    <div class="da2-quick-ico indigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="da2-quick-title">Sesi Kasir</div>
                        <div class="da2-quick-sub">Buka / tutup sesi kasir</div>
                    </div>
                </a>
                @endcan

                @can('view_transaksi')
                <a href="{{ route('transaksi.index') }}" class="da2-quick-item">
                    <div class="da2-quick-ico amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <div>
                        <div class="da2-quick-title">Riwayat Transaksi</div>
                        <div class="da2-quick-sub">Lihat & kelola transaksi</div>
                    </div>
                </a>
                @endcan
            </div>
        </div>

        {{-- ─── SYSTEM INFO ─── --}}
        <div class="da2-info">
            <div class="da2-info-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Info Sistem
            </div>
            <div class="da2-info-row">
                <span class="da2-info-key">Versi Aplikasi</span>
                <span class="da2-info-val" style="color:#0d9488;">v1.0.0</span>
            </div>
            <div class="da2-info-row">
                <span class="da2-info-key">Database</span>
                <span class="da2-badge da2-badge-green">Terhubung</span>
            </div>
            <div class="da2-info-row">
                <span class="da2-info-key">Mode</span>
                <span class="da2-badge da2-badge-amber">Development</span>
            </div>
            <div class="da2-info-row">
                <span class="da2-info-key">Waktu Server</span>
                <span class="da2-info-val">{{ now()->format('H:i:s') }} WITA</span>
            </div>
        </div>

    </div>
</x-app-layout>
