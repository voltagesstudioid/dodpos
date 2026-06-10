<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $penjualan->no_faktur }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#f1f5f9; padding:2rem 1rem; }
        .receipt-wrap { max-width:320px; margin:0 auto; }
        .receipt {
            background:#fff; border-radius:4px; padding:1.5rem; position:relative;
            box-shadow:0 4px 24px rgba(0,0,0,0.08);
        }
        .receipt::after {
            content:''; position:absolute; bottom:-12px; left:0; right:0; height:12px;
            background:linear-gradient(135deg,#fff 33.33%,transparent 33.33%) 0 0,
                       linear-gradient(-135deg,#fff 33.33%,transparent 33.33%) 0 0;
            background-size:12px 12px; background-repeat:repeat-x;
        }
        .rpt-hdr { text-align:center; border-bottom:2px dashed #e2e8f0; padding-bottom:1rem; margin-bottom:1rem; }
        .rpt-logo { font-size:1.25rem; font-weight:800; color:#065f46; letter-spacing:-0.02em; }
        .rpt-sub { font-size:0.625rem; color:#94a3b8; margin-top:0.125rem; text-transform:uppercase; letter-spacing:0.1em; }
        .rpt-faktur { font-size:0.75rem; font-weight:700; color:#475569; margin-top:0.5rem; }
        .rpt-date { font-size:0.6875rem; color:#94a3b8; }
        .rpt-section { margin-bottom:0.875rem; }
        .rpt-section-title { font-size:0.5625rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.375rem; }
        .rpt-row { display:flex; justify-content:space-between; align-items:flex-start; padding:0.25rem 0; }
        .rpt-key { font-size:0.75rem; color:#64748b; }
        .rpt-val { font-size:0.75rem; font-weight:600; color:#1e293b; text-align:right; max-width:55%; }
        .rpt-divider { border:none; border-top:1px dashed #e2e8f0; margin:0.75rem 0; }
        .rpt-total-box { background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:6px; padding:0.75rem; margin:0.75rem 0; }
        .rpt-total-row { display:flex; justify-content:space-between; align-items:center; }
        .rpt-total-lbl { font-size:0.8125rem; font-weight:700; color:#065f46; }
        .rpt-total-val { font-size:1.125rem; font-weight:800; color:#065f46; }
        .rpt-badge { display:inline-block; padding:0.25rem 0.75rem; border-radius:20px; font-size:0.625rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; margin-top:0.375rem; }
        .rpt-badge.tunai { background:#dcfce7; color:#166534; border:1.5px solid #86efac; }
        .rpt-badge.hutang { background:#fef2f2; color:#991b1b; border:1.5px solid #fecaca; }
        .rpt-badge.transfer { background:#eff6ff; color:#1e40af; border:1.5px solid #bfdbfe; }
        .rpt-hutang-box { background:#fff7ed; border:1.5px solid #fed7aa; border-radius:6px; padding:0.625rem 0.75rem; margin:0.625rem 0; }
        .rpt-hutang-title { font-size:0.5625rem; font-weight:700; color:#9a3412; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.375rem; }
        .rpt-hutang-row { display:flex; justify-content:space-between; padding:0.2rem 0; }
        .rpt-hutang-key { font-size:0.6875rem; color:#c2410c; }
        .rpt-hutang-val { font-size:0.6875rem; font-weight:700; color:#9a3412; }
        .rpt-hutang-total { font-size:0.8125rem; font-weight:800; color:#dc2626; }
        .rpt-transfer-id { font-size:0.625rem; color:#1e40af; font-family:monospace; font-weight:700; margin-top:0.25rem; }
        .rpt-payment-row { display:flex; justify-content:space-between; padding:0.2rem 0; }
        .rpt-payment-key { font-size:0.6875rem; color:#64748b; }
        .rpt-payment-val { font-size:0.6875rem; font-weight:600; color:#1e293b; }
        .rpt-footer { text-align:center; border-top:2px dashed #e2e8f0; padding-top:0.875rem; margin-top:1rem; }
        .rpt-thanks { font-size:0.6875rem; font-weight:600; color:#065f46; }
        .rpt-note { font-size:0.5625rem; color:#94a3b8; margin-top:0.25rem; }
        .rpt-sales { font-size:0.625rem; color:#64748b; margin-top:0.5rem; }
        .print-bar { text-align:center; margin-bottom:1.5rem; }
        .print-btn {
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 2rem;
            border-radius:10px; font-size:0.875rem; font-weight:700; border:none;
            cursor:pointer; font-family:inherit;
            background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff;
            box-shadow:0 6px 20px rgba(79,70,229,0.25); transition:all 0.2s;
        }
        .print-btn:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(79,70,229,0.35); }
        @media print {
            body { background:#fff; padding:0; }
            .print-bar { display:none; }
            .receipt { box-shadow:none; }
            .receipt-wrap { max-width:80mm; }
        }
    </style>
</head>
<body>
    <div class="print-bar">
        <button class="print-btn" onclick="window.print()">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Struk
        </button>
    </div>

    <div class="receipt-wrap">
        <div class="receipt">
            {{-- Header --}}
            <div class="rpt-hdr">
                <div class="rpt-logo">DOD POS</div>
                <div class="rpt-sub">Minyak</div>
                <div class="rpt-faktur">{{ $penjualan->no_faktur }}</div>
                <div class="rpt-date">{{ $penjualan->tanggal_jual->format('d M Y • H:i') }} WIB</div>
            </div>

            {{-- Pelanggan --}}
            <div class="rpt-section">
                <div class="rpt-section-title">Pelanggan</div>
                <div class="rpt-row">
                    <span class="rpt-key">Toko</span>
                    <span class="rpt-val">{{ $penjualan->pelanggan->nama_toko ?? '-' }}</span>
                </div>
                <div class="rpt-row">
                    <span class="rpt-key">Pemilik</span>
                    <span class="rpt-val">{{ $penjualan->pelanggan->nama_pemilik ?? '-' }}</span>
                </div>
            </div>

            <hr class="rpt-divider">

            {{-- Detail Produk --}}
            <div class="rpt-section">
                <div class="rpt-section-title">Detail Transaksi</div>
                <div class="rpt-row">
                    <span class="rpt-key">{{ $penjualan->produk->nama ?? '-' }}</span>
                    <span class="rpt-val">{{ $penjualan->jumlah }} {{ $penjualan->produk->satuan ?? '' }}</span>
                </div>
                <div class="rpt-row">
                    <span class="rpt-key">Harga Satuan</span>
                    <span class="rpt-val">Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Total --}}
            <div class="rpt-total-box">
                <div class="rpt-total-row">
                    <span class="rpt-total-lbl">TOTAL</span>
                    <span class="rpt-total-val">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Pembayaran --}}
            <div class="rpt-section" style="text-align:center;">
                @php
                    $badgeLabel = $penjualan->tipe_bayar === 'tunai' ? '💵 TUNAI / CASH' : ($penjualan->tipe_bayar === 'hutang' ? '📄 KREDIT / HUTANG' : '🏦 TRANSFER');
                @endphp
                <div class="rpt-badge {{ $penjualan->tipe_bayar }}">{{ $badgeLabel }}</div>
            </div>

            @if($penjualan->tipe_bayar === 'hutang')
            <div class="rpt-section">
                <div class="rpt-payment-row">
                    <span class="rpt-payment-key">Dibayar (DP)</span>
                    <span class="rpt-payment-val">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
                </div>
                <div class="rpt-payment-row">
                    <span class="rpt-payment-key" style="color:#dc2626; font-weight:700;">Sisa Hutang Transaksi Ini</span>
                    <span class="rpt-payment-val" style="color:#dc2626;">Rp {{ number_format($penjualan->hutang, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Info Hutang Pelanggan --}}
            @php
                $totalHutangPelanggan = $penjualan->pelanggan->total_hutang ?? 0;
            @endphp
            <div class="rpt-hutang-box">
                <div class="rpt-hutang-title">📋 Info Hutang Pelanggan</div>
                <div class="rpt-hutang-row">
                    <span class="rpt-hutang-key">Total Hutang Berjalan</span>
                    <span class="rpt-hutang-total">Rp {{ number_format($totalHutangPelanggan, 0, ',', '.') }}</span>
                </div>
                @if($penjualan->pelanggan && $penjualan->pelanggan->limit_hutang > 0)
                <div class="rpt-hutang-row">
                    <span class="rpt-hutang-key">Limit Kredit</span>
                    <span class="rpt-hutang-val">Rp {{ number_format($penjualan->pelanggan->limit_hutang, 0, ',', '.') }}</span>
                </div>
                <div class="rpt-hutang-row">
                    <span class="rpt-hutang-key">Sisa Limit</span>
                    <span class="rpt-hutang-val" style="color:{{ ($penjualan->pelanggan->limit_hutang - $totalHutangPelanggan) <= 0 ? '#dc2626' : '#166534' }};">Rp {{ number_format(max(0, $penjualan->pelanggan->limit_hutang - $totalHutangPelanggan), 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            @endif

            @if($penjualan->tipe_bayar === 'transfer' && $penjualan->no_bukti_transfer)
            <div class="rpt-section">
                <div style="font-size:0.5625rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.25rem;">Bukti Transfer</div>
                <div class="rpt-transfer-id">{{ $penjualan->no_bukti_transfer }}</div>
            </div>
            @endif

            {{-- Keterangan --}}
            @if($penjualan->keterangan)
            <hr class="rpt-divider">
            <div class="rpt-section">
                <div class="rpt-section-title">Keterangan</div>
                <div style="font-size:0.6875rem; color:#475569;">{{ $penjualan->keterangan }}</div>
            </div>
            @endif

            {{-- Sales info --}}
            <div class="rpt-sales">
                Sales: {{ $penjualan->sales->nama ?? '-' }}
            </div>

            {{-- Footer --}}
            <div class="rpt-footer">
                <div class="rpt-thanks">Terima Kasih</div>
                <div class="rpt-note">Struk ini adalah bukti transaksi yang sah</div>
            </div>
        </div>
    </div>
</body>
</html>
