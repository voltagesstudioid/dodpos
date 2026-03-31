<x-app-layout>
    <x-slot name="header">Kasir / POS</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER & SESSION INFO ─── --}}
            <div class="tr-hero-section">
                <div class="tr-hero-text">
                    <h1 class="tr-title">Pilih Mode Kasir</h1>
                    <p class="tr-subtitle">Tentukan mode transaksi operasional yang akan Anda gunakan saat ini.</p>
                </div>

                {{-- Status Sesi Aktif --}}
                <div class="tr-session-strip">
                    <div class="tr-session-badge">
                        <span class="tr-pulse-dot"></span> Sesi Aktif
                    </div>
                    @if(isset($activeSession))
                        <div class="tr-session-details">
                            <div class="tr-s-item">
                                <span class="s-lbl">Modal Awal</span>
                                <span class="s-val">Rp {{ number_format($activeSession->opening_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="tr-s-item">
                                <span class="s-lbl">Total Kas</span>
                                <span class="s-val text-indigo">Rp {{ number_format($expectedCash ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="tr-s-item">
                                <span class="s-lbl">Waktu Mulai</span>
                                <span class="s-val">{{ optional($activeSession->created_at)->format('H:i') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─── POS MODE CARDS ─── --}}
            <div class="tr-pos-grid">
                
                {{-- KARTU ECERAN --}}
                <a href="{{ route('kasir.eceran') }}" class="tr-pos-card tr-card-retail">
                    <div class="tr-pos-card-inner">
                        <div class="tr-pos-header">
                            <div class="tr-pos-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            </div>
                            <span class="tr-badge-mode">ECERAN</span>
                        </div>
                        <div class="tr-pos-content">
                            <h3 class="tr-pos-name">Kasir Eceran (Retail)</h3>
                            <p class="tr-pos-desc">Gunakan mode ini untuk pelanggan umum dengan harga per satuan terkecil. Proses checkout lebih cepat.</p>
                            <div class="tr-pos-pills">
                                <span>⚡ Transaksi Cepat</span>
                                <span>🛒 Harga Satuan</span>
                            </div>
                        </div>
                        <div class="tr-pos-action">
                            Buka Kasir Eceran 
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </div>
                </a>

                {{-- KARTU GROSIR --}}
                <a href="{{ route('kasir.grosir') }}" class="tr-pos-card tr-card-wholesale">
                    <div class="tr-pos-card-inner">
                        <div class="tr-pos-header">
                            <div class="tr-pos-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            </div>
                            <span class="tr-badge-mode">GROSIR</span>
                        </div>
                        <div class="tr-pos-content">
                            <h3 class="tr-pos-name">Kasir Grosir (Wholesale)</h3>
                            <p class="tr-pos-desc">Gunakan mode ini untuk reseller atau pembelian dalam jumlah besar. Mendukung harga bertingkat dan multi-satuan.</p>
                            <div class="tr-pos-pills">
                                <span>📦 Multi Satuan</span>
                                <span>💰 Harga Bertingkat</span>
                            </div>
                        </div>
                        <div class="tr-pos-action">
                            Buka Kasir Grosir 
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </div>
                </a>

            </div>

            {{-- ─── BOTTOM TIPS & LINKS ─── --}}
            <div class="tr-info-banner">
                <div class="tr-info-content">
                    <div class="tr-info-icon">💡</div>
                    <div>
                        <h4 class="tr-info-title">Tips Penggunaan</h4>
                        <p class="tr-info-desc">Gunakan alat pemindai (<i>Barcode Scanner</i>) pada kolom pencarian di halaman kasir untuk mempercepat proses input barang ke keranjang.</p>
                    </div>
                </div>
                <a href="{{ route('transaksi.index') }}" class="tr-btn tr-btn-dark">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Lihat Riwayat Transaksi
                </a>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-indigo: #4f46e5;
            --tr-indigo-light: #e0e7ff;
            --tr-emerald: #10b981;
            --tr-emerald-light: #dcfce7;
            --tr-blue: #3b82f6;
            --tr-blue-light: #dbeafe;
            --tr-border: #e2e8f0;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; padding-bottom: 4rem; }
        .tr-page { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── HERO & SESSION ── */
        .tr-hero-section { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem; }
        .tr-hero-text { flex: 1; min-width: 250px; }
        .tr-title { font-size: 1.75rem; font-weight: 900; color: var(--tr-text-main); margin: 0 0 6px 0; letter-spacing: -0.02em; }
        .tr-subtitle { font-size: 0.95rem; color: var(--tr-text-muted); margin: 0; line-height: 1.5; }

        .tr-session-strip { background: #ffffff; border: 1px solid var(--tr-border); border-radius: 12px; display: flex; align-items: center; padding: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); flex-wrap: wrap; gap: 0.5rem; }
        .tr-session-badge { display: flex; align-items: center; gap: 8px; background: var(--tr-emerald-light); color: #065f46; font-size: 0.8rem; font-weight: 800; padding: 0.5rem 1rem; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-pulse-dot { width: 8px; height: 8px; background-color: var(--tr-emerald); border-radius: 50%; box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); animation: tr-pulse 1.5s infinite; }
        
        .tr-session-details { display: flex; flex-wrap: wrap; gap: 0.5rem; padding: 0 0.5rem; }
        .tr-s-item { background: #f8fafc; border: 1px solid var(--tr-border); padding: 0.4rem 0.8rem; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-size: 0.85rem; }
        .s-lbl { color: var(--tr-text-muted); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; }
        .s-val { font-weight: 800; color: var(--tr-text-main); font-family: monospace; font-size: 0.9rem;}
        .text-indigo { color: var(--tr-indigo); }

        /* ── POS CARDS GRID ── */
        .tr-pos-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        
        .tr-pos-card { display: block; text-decoration: none; border-radius: 20px; transition: all 0.3s ease; position: relative; overflow: hidden; background: #ffffff; border: 2px solid transparent; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); }
        .tr-pos-card-inner { padding: 1.75rem; display: flex; flex-direction: column; height: 100%; z-index: 2; position: relative; }
        
        /* Retail Card Styling */
        .tr-card-retail { border-color: var(--tr-border); }
        .tr-card-retail:hover { border-color: var(--tr-emerald); transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.1), 0 10px 10px -5px rgba(16, 185, 129, 0.04); }
        .tr-card-retail .tr-pos-icon { background: var(--tr-emerald-light); color: var(--tr-emerald); }
        .tr-card-retail .tr-badge-mode { background: var(--tr-emerald); color: #fff; }
        .tr-card-retail .tr-pos-action { color: var(--tr-emerald); background: var(--tr-emerald-light); }
        .tr-card-retail:hover .tr-pos-action { background: var(--tr-emerald); color: #fff; }

        /* Wholesale Card Styling */
        .tr-card-wholesale { border-color: var(--tr-border); }
        .tr-card-wholesale:hover { border-color: var(--tr-blue); transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04); }
        .tr-card-wholesale .tr-pos-icon { background: var(--tr-blue-light); color: var(--tr-blue); }
        .tr-card-wholesale .tr-badge-mode { background: var(--tr-blue); color: #fff; }
        .tr-card-wholesale .tr-pos-action { color: var(--tr-blue); background: var(--tr-blue-light); }
        .tr-card-wholesale:hover .tr-pos-action { background: var(--tr-blue); color: #fff; }

        /* Card Elements */
        .tr-pos-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; }
        .tr-pos-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        .tr-badge-mode { padding: 4px 12px; border-radius: 999px; font-size: 0.7rem; font-weight: 900; letter-spacing: 0.1em; }
        
        .tr-pos-content { flex-grow: 1; }
        .tr-pos-name { font-size: 1.35rem; font-weight: 900; color: var(--tr-text-main); margin: 0 0 0.5rem 0; }
        .tr-pos-desc { font-size: 0.9rem; color: var(--tr-text-muted); line-height: 1.6; margin: 0 0 1.25rem 0; }
        
        .tr-pos-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .tr-pos-pills span { font-size: 0.75rem; font-weight: 700; color: var(--tr-text-main); background: #f1f5f9; padding: 4px 10px; border-radius: 8px; border: 1px solid var(--tr-border); }

        .tr-pos-action { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-radius: 12px; font-weight: 800; font-size: 0.95rem; transition: all 0.3s ease; }

        /* ── BOTTOM BANNER ── */
        .tr-info-banner { background: #ffffff; border: 1px solid var(--tr-border); border-radius: 16px; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .tr-info-content { display: flex; gap: 1rem; align-items: flex-start; flex: 1; min-width: 250px; }
        .tr-info-icon { font-size: 1.5rem; background: #fffbeb; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; flex-shrink: 0; }
        .tr-info-title { margin: 0 0 4px 0; font-size: 1rem; font-weight: 800; color: var(--tr-text-main); }
        .tr-info-desc { margin: 0; font-size: 0.85rem; color: var(--tr-text-muted); line-height: 1.5; }

        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.75rem 1.5rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: none; text-decoration: none; white-space: nowrap; }
        .tr-btn-dark { background: var(--tr-text-main); color: #fff; }
        .tr-btn-dark:hover { background: #000; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        /* ANIMATIONS */
        @keyframes tr-pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .tr-hero-section { flex-direction: column; align-items: flex-start; }
            .tr-session-strip { width: 100%; }
            .tr-info-banner { flex-direction: column; align-items: stretch; }
            .tr-btn { justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>