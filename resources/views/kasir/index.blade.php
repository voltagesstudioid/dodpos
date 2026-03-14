<x-app-layout>
    <x-slot name="header">Kasir / POS</x-slot>

    <style>
        .pos-hero { display:flex; justify-content:space-between; align-items:flex-end; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; }
        .pos-title { font-size:1.5rem; font-weight:900; color:#0f172a; line-height:1.1; }
        .pos-sub { font-size:0.875rem; color:#64748b; margin-top:0.25rem; }
        .pos-cards { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:1rem; }
        .pos-card { display:block; text-decoration:none; border:1px solid #e2e8f0; border-radius:16px; background:#fff; padding:1.25rem; transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease; }
        .pos-card:hover { transform:translateY(-2px); box-shadow:0 18px 45px rgba(15,23,42,0.10); border-color:#cbd5e1; }
        .pos-card-top { display:flex; justify-content:space-between; align-items:flex-start; gap:0.75rem; }
        .pos-icon { width:46px; height:46px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; border:1px solid #e2e8f0; }
        .pos-badge { display:inline-flex; align-items:center; padding:0.25rem 0.75rem; border-radius:999px; font-size:0.7rem; font-weight:900; letter-spacing:0.08em; color:#fff; }
        .pos-name { margin-top:0.85rem; font-size:1.05rem; font-weight:900; color:#0f172a; }
        .pos-desc { margin-top:0.35rem; font-size:0.85rem; color:#64748b; line-height:1.5; }
        .pos-meta { margin-top:0.85rem; display:flex; gap:0.5rem; flex-wrap:wrap; }
        .pos-pill { font-size:0.72rem; font-weight:800; color:#334155; background:#f8fafc; border:1px solid #e2e8f0; padding:0.25rem 0.6rem; border-radius:999px; }
        @media (max-width: 820px) { .pos-cards { grid-template-columns:1fr; } }
    </style>

    <div class="page-container" style="max-width:1100px;">
        <div class="pos-hero">
            <div>
                <div class="pos-title">Kasir / POS</div>
                <div class="pos-sub">Pilih mode transaksi yang ingin digunakan.</div>
            </div>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                <span class="badge badge-indigo">Sesi Aktif</span>
                @if(isset($activeSession))
                    <span class="badge badge-gray">Modal: Rp {{ number_format($activeSession->opening_amount ?? 0, 0, ',', '.') }}</span>
                    <span class="badge badge-gray">Kas: Rp {{ number_format($expectedCash ?? 0, 0, ',', '.') }}</span>
                    <span class="badge badge-gray">Mulai: {{ optional($activeSession->created_at)->format('H:i') }}</span>
                @endif
            </div>
        </div>

        <div class="pos-cards" style="margin-bottom:1rem;">
            <a href="{{ route('kasir.eceran') }}" class="pos-card">
                <div class="pos-card-top">
                    <div class="pos-icon" style="background:#ecfdf5;border-color:#bbf7d0;">🛒</div>
                    <span class="pos-badge" style="background:#10b981;">ECERAN</span>
                </div>
                <div class="pos-name">Kasir Eceran</div>
                <div class="pos-desc">Harga per satuan terkecil untuk pelanggan umum.</div>
                <div class="pos-meta">
                    <span class="pos-pill">Cepat</span>
                    <span class="pos-pill">Satuan</span>
                </div>
            </a>

            <a href="{{ route('kasir.grosir') }}" class="pos-card">
                <div class="pos-card-top">
                    <div class="pos-icon" style="background:#eff6ff;border-color:#bfdbfe;">📦</div>
                    <span class="pos-badge" style="background:#3b82f6;">GROSIR</span>
                </div>
                <div class="pos-name">Kasir Grosir</div>
                <div class="pos-desc">Harga grosir untuk reseller dan pembelian jumlah besar.</div>
                <div class="pos-meta">
                    <span class="pos-pill">Multi satuan</span>
                    <span class="pos-pill">Harga grosir</span>
                </div>
            </a>
        </div>

        <div class="card" style="padding:1rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                <div style="font-size:0.85rem;font-weight:900;color:#0f172a;">Tips</div>
                <a href="{{ route('transaksi.index') }}" class="btn-secondary btn-sm">🧾 Lihat Transaksi</a>
            </div>
            <div style="margin-top:0.5rem;font-size:0.85rem;color:#64748b;line-height:1.6;">
                Gunakan pencarian atau scan barcode untuk mempercepat transaksi.
            </div>
        </div>
    </div>
</x-app-layout>
