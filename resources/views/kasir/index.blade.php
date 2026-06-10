<x-app-layout>
    <x-slot name="header">Kasir / POS</x-slot>

    <div class="k-page">

        {{-- ─── HEADER ─── --}}
        <div class="k-header">
            <div>
                <h1 class="k-title">Mode Kasir</h1>
                <p class="k-subtitle">Pilih mode transaksi untuk memulai operasional kasir hari ini.</p>
            </div>
            <a href="{{ route('transaksi.index') }}" class="k-btn-history">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                Riwayat Transaksi
            </a>
        </div>

        {{-- ─── MODE CARDS ─── --}}
        <div class="k-grid">

            {{-- ═══ ECERAN CARD ═══ --}}
            <div class="k-card k-card-eceran">
                <div class="k-card-top">
                    <div class="k-card-icon-wrap k-icon-eceran">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    </div>
                    <div>
                        <div class="k-card-label">ECERAN</div>
                        <div class="k-card-name">Kasir Retail</div>
                    </div>
                    @if($eceranSession)
                        <span class="k-status k-status-active"><span class="k-dot"></span> Aktif</span>
                    @else
                        <span class="k-status k-status-inactive">Nonaktif</span>
                    @endif
                </div>

                <p class="k-card-desc">Transaksi pelanggan umum dengan harga satuan terkecil. Cocok untuk penjualan harian.</p>

                @if($eceranSession)
                    <div class="k-stats">
                        <div class="k-stat">
                            <span class="k-stat-label">Modal Awal</span>
                            <span class="k-stat-value">Rp {{ number_format($eceranSession->opening_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="k-stat">
                            <span class="k-stat-label">Pendapatan Kas</span>
                            <span class="k-stat-value k-highlight">Rp {{ number_format($eceranRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="k-stat">
                            <span class="k-stat-label">Total Kas</span>
                            <span class="k-stat-value">Rp {{ number_format($eceranExpected, 0, ',', '.') }}</span>
                        </div>
                        <div class="k-stat">
                            <span class="k-stat-label">Dibuka</span>
                            <span class="k-stat-value">{{ $eceranSession->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('kasir.eceran') }}" class="k-card-action k-action-eceran">
                        Buka Kasir Eceran
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                @else
                    <div class="k-card-empty">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>Sesi belum dibuka</span>
                        @if(auth()->user()->role === 'supervisor')
                            <button type="button" class="k-open-btn" onclick="document.getElementById('modal-eceran').style.display='flex'">Buka Sesi Eceran</button>
                        @else
                            <span class="k-need-super">Hubungi Supervisor untuk membuka sesi</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ═══ GROSIR CARD ═══ --}}
            <div class="k-card k-card-grosir">
                <div class="k-card-top">
                    <div class="k-card-icon-wrap k-icon-grosir">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </div>
                    <div>
                        <div class="k-card-label">GROSIR</div>
                        <div class="k-card-name">Kasir Wholesale</div>
                    </div>
                    @if($grosirSession)
                        <span class="k-status k-status-active"><span class="k-dot"></span> Aktif</span>
                    @else
                        <span class="k-status k-status-inactive">Nonaktif</span>
                    @endif
                </div>

                <p class="k-card-desc">Transaksi reseller &amp; pembelian besar. Mendukung harga bertingkat dan multi-satuan.</p>

                @if($grosirSession)
                    <div class="k-stats">
                        <div class="k-stat">
                            <span class="k-stat-label">Pendapatan Kas</span>
                            <span class="k-stat-value k-highlight">Rp {{ number_format($grosirRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="k-stat">
                            <span class="k-stat-label">Total Kas</span>
                            <span class="k-stat-value">Rp {{ number_format($grosirExpected, 0, ',', '.') }}</span>
                        </div>
                        <div class="k-stat">
                            <span class="k-stat-label">Dibuka</span>
                            <span class="k-stat-value">{{ $grosirSession->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('kasir.grosir') }}" class="k-card-action k-action-grosir">
                        Buka Kasir Grosir
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                @else
                    <div class="k-card-empty">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>Sesi belum dibuka</span>
                        @if(auth()->user()->role === 'supervisor')
                            <form action="{{ route('kasir.open_session_grosir') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="k-open-btn">Buka Sesi Grosir</button>
                            </form>
                        @else
                            <span class="k-need-super">Hubungi Supervisor untuk membuka sesi</span>
                        @endif
                    </div>
                @endif
            </div>

        </div>

        {{-- ─── TIP ─── --}}
        <div class="k-tip">
            <span class="k-tip-icon">💡</span>
            <p>Gunakan <strong>barcode scanner</strong> pada kolom pencarian di halaman kasir untuk mempercepat proses input barang.</p>
        </div>

    </div>

    {{-- ═══ MODAL: BUKA SESI ECERAN ═══ --}}
    @if(auth()->user()->role === 'supervisor' && !$eceranSession)
    <div id="modal-eceran" class="k-modal-overlay" style="display:none;">
        <div class="k-modal">
            <div class="k-modal-header">
                <h3>Buka Sesi Kasir Eceran</h3>
                <button type="button" class="k-modal-close" onclick="document.getElementById('modal-eceran').style.display='none'">&times;</button>
            </div>
            <form action="{{ route('kasir.open_session') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="eceran">
                <div class="k-form-group">
                    <label>Modal Awal (Rp)</label>
                    <input type="number" name="opening_amount" min="0" value="{{ old('opening_amount') }}" placeholder="0" required autofocus>
                </div>
                <div class="k-form-group">
                    <label>Catatan (opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Misal: Modal laci 1">{{ old('notes') }}</textarea>
                </div>
                <div class="k-modal-footer">
                    <button type="button" class="k-btn-cancel" onclick="document.getElementById('modal-eceran').style.display='none'">Batal</button>
                    <button type="submit" class="btn-primary">Buka Sesi Eceran</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    @push('styles')
    <style>
        .k-page { max-width: 920px; margin: 0 auto; padding: 1.5rem; }

        /* ── HEADER ── */
        .k-header { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem; }
        .k-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; }
        .k-subtitle { font-size: 0.875rem; color: #64748b; margin: 0; }
        .k-btn-history { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; color: #4f46e5; background: #eef2ff; text-decoration: none; transition: 0.2s; border: 1px solid #e0e7ff; }
        .k-btn-history:hover { background: #e0e7ff; transform: translateY(-1px); }

        /* ── GRID ── */
        .k-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem; }

        /* ── CARD ── */
        .k-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column; transition: border-color 0.2s, box-shadow 0.2s; }
        .k-card-eceran:hover { border-color: #10b981; box-shadow: 0 8px 24px -4px rgba(16,185,129,0.12); }
        .k-card-grosir:hover { border-color: #3b82f6; box-shadow: 0 8px 24px -4px rgba(59,130,246,0.12); }

        .k-card-top { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; flex-wrap: wrap; }
        .k-card-icon-wrap { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .k-icon-eceran { background: #d1fae5; color: #059669; }
        .k-icon-grosir { background: #dbeafe; color: #2563eb; }
        .k-card-label { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #94a3b8; }
        .k-card-name { font-size: 1rem; font-weight: 800; color: #0f172a; line-height: 1.2; }
        .k-card-desc { font-size: 0.8125rem; color: #64748b; line-height: 1.6; margin: 0 0 1rem 0; }

        /* ── STATUS BADGE ── */
        .k-status { margin-left: auto; font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 999px; letter-spacing: 0.03em; display: inline-flex; align-items: center; gap: 5px; }
        .k-status-active { background: #dcfce7; color: #166534; }
        .k-status-inactive { background: #f1f5f9; color: #94a3b8; }
        .k-dot { width: 6px; height: 6px; border-radius: 50%; background: #10b981; animation: k-pulse 1.5s infinite; }

        /* ── STATS GRID ── */
        .k-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem; }
        .k-stat { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px; padding: 0.5rem 0.75rem; }
        .k-stat-label { display: block; font-size: 0.65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; }
        .k-stat-value { display: block; font-size: 0.875rem; font-weight: 700; color: #0f172a; font-family: 'Courier New', monospace; margin-top: 2px; }
        .k-highlight { color: #4f46e5; }

        /* ── ACTION BUTTON ── */
        .k-card-action { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: 10px; font-weight: 700; font-size: 0.875rem; text-decoration: none; transition: 0.2s; margin-top: auto; }
        .k-action-eceran { background: #d1fae5; color: #059669; }
        .k-action-eceran:hover { background: #10b981; color: #fff; }
        .k-action-grosir { background: #dbeafe; color: #2563eb; }
        .k-action-grosir:hover { background: #3b82f6; color: #fff; }

        /* ── EMPTY STATE ── */
        .k-card-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; padding: 1.5rem 0; margin-top: auto; text-align: center; }
        .k-card-empty span { font-size: 0.8125rem; color: #94a3b8; }
        .k-open-btn { background: #4f46e5; color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: 0.2s; margin-top: 0.25rem; }
        .k-open-btn:hover { background: #4338ca; transform: translateY(-1px); }
        .k-need-super { font-size: 0.75rem !important; color: #f59e0b !important; font-style: italic; }

        /* ── TIP ── */
        .k-tip { display: flex; align-items: center; gap: 0.75rem; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 10px; padding: 0.875rem 1rem; }
        .k-tip-icon { font-size: 1.25rem; flex-shrink: 0; }
        .k-tip p { margin: 0; font-size: 0.8125rem; color: #92400e; line-height: 1.5; }

        /* ── MODAL ── */
        .k-modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999; backdrop-filter: blur(2px); }
        .k-modal { background: #fff; border-radius: 16px; padding: 1.5rem; width: 90%; max-width: 420px; box-shadow: 0 24px 48px rgba(0,0,0,0.15); animation: k-modalIn 0.2s ease; }
        .k-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .k-modal-header h3 { margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; }
        .k-modal-close { background: none; border: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer; line-height: 1; padding: 0 4px; }
        .k-modal-close:hover { color: #0f172a; }
        .k-modal-footer { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem; }
        .k-btn-cancel { background: #f1f5f9; color: #64748b; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .k-btn-cancel:hover { background: #e2e8f0; }
        .k-form-group { margin-bottom: 0.875rem; }
        .k-form-group label { display: block; font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem; }
        .k-form-group input, .k-form-group select, .k-form-group textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; font-family: inherit; transition: border 0.2s; box-sizing: border-box; }
        .k-form-group input:focus, .k-form-group select:focus, .k-form-group textarea:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }

        /* ── ANIMATIONS ── */
        @keyframes k-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        @keyframes k-modalIn { from { opacity:0; transform:scale(0.95); } to { opacity:1; transform:scale(1); } }

        /* ── RESPONSIVE ── */
        @media (max-width: 720px) {
            .k-grid { grid-template-columns: 1fr; }
            .k-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
    @endpush
</x-app-layout>
