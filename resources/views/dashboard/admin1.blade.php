<x-app-layout>
    <x-slot name="header">Dashboard Admin 1</x-slot>

    <style>
        .dash-greeting { margin-bottom: 1.5rem; }
        .dash-greeting h1 { font-size: 1.5rem; font-weight: 900; color: #0f172a; margin: 0 0 0.25rem; }
        .dash-greeting p { font-size: 0.875rem; color: #64748b; margin: 0; }
        .grid-quick { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:0.75rem; }
        .quick-item { display:flex; gap:0.75rem; align-items:center; padding:1rem; background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; text-decoration:none; color:#0f172a; }
        .quick-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; background:#f1f5f9; flex-shrink:0; }
        .quick-title { font-weight: 900; font-size: 0.9rem; }
        .quick-sub { font-size: 0.75rem; color:#64748b; margin-top:2px; }
        @media (max-width: 900px) { .grid-quick { grid-template-columns:repeat(2, minmax(0,1fr)); } }
        @media (max-width: 520px) { .grid-quick { grid-template-columns:1fr; } }
    </style>

    <div class="page-container" style="max-width:1100px;">
        <div class="dash-greeting">
            <h1>Dashboard Kasir Utama</h1>
            <p>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} — Ringkasan Penjualan & Uang Masuk</p>
        </div>

        <div class="stat-grid" style="margin-bottom:1rem;">
            <div class="stat-card">
                <div class="stat-icon indigo">🖥️</div>
                <div>
                    <div class="stat-label">Omzet POS</div>
                    <div class="stat-value indigo">Rp {{ number_format($omzetPOS, 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">{{ $jumlahTransaksi }} transaksi</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon emerald">🚚</div>
                <div>
                    <div class="stat-label">Setoran Armada</div>
                    <div class="stat-value emerald">Rp {{ number_format($totalSetoranArmada, 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">Status terverifikasi</div>
                </div>
            </div>

            <div class="stat-card" style="background:linear-gradient(135deg,#4f46e5,#7c3aed); border:none;">
                <div class="stat-icon" style="background:rgba(255,255,255,0.18); color:#fff;">💰</div>
                <div>
                    <div class="stat-label" style="color:rgba(255,255,255,0.85);">Total Uang Masuk</div>
                    <div class="stat-value" style="color:#fff;">Rp {{ number_format($totalUangMasuk, 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:rgba(255,255,255,0.85);">Omzet + setoran</div>
                </div>
            </div>
        </div>

        <div class="card" style="padding:1.25rem;">
            <div style="font-size:0.85rem;font-weight:900;color:#0f172a;margin-bottom:0.75rem;">Akses Cepat</div>
            <div class="grid-quick">
                @can('view_pos_kasir')
                    <a href="{{ route('kasir.index') }}" class="quick-item">
                        <div class="quick-icon" style="background:#ede9fe;color:#4f46e5;">🖥️</div>
                        <div>
                            <div class="quick-title">Buka Kasir</div>
                            <div class="quick-sub">Mulai transaksi POS</div>
                        </div>
                    </a>
                @endcan

                <a href="{{ route('transaksi.index') }}" class="quick-item">
                    <div class="quick-icon" style="background:#e0f2fe;color:#0284c7;">🧾</div>
                    <div>
                        <div class="quick-title">Transaksi</div>
                        <div class="quick-sub">Riwayat & void</div>
                    </div>
                </a>

                <a href="{{ route('pasgar.setoran.index') }}" class="quick-item">
                    <div class="quick-icon" style="background:#dcfce7;color:#16a34a;">🚚</div>
                    <div>
                        <div class="quick-title">Setoran Armada</div>
                        <div class="quick-sub">Validasi uang masuk</div>
                    </div>
                </a>

                <a href="{{ route('laporan.penjualan') }}" class="quick-item">
                    <div class="quick-icon" style="background:#fef3c7;color:#b45309;">📈</div>
                    <div>
                        <div class="quick-title">Laporan Penjualan</div>
                        <div class="quick-sub">Rekap harian</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
