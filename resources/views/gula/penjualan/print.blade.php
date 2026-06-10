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
        .rpt-logo { font-size:1.25rem; font-weight:800; color:#92400e; letter-spacing:-0.02em; }
        .rpt-sub { font-size:0.625rem; color:#94a3b8; margin-top:0.125rem; text-transform:uppercase; letter-spacing:0.1em; }
        .rpt-faktur { font-size:0.7rem; font-weight:700; color:#475569; margin-top:0.5rem; font-family:'JetBrains Mono',monospace; }
        .rpt-date { font-size:0.6875rem; color:#94a3b8; }
        .rpt-section { margin-bottom:0.75rem; }
        .rpt-section-title { font-size:0.5625rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.375rem; }
        .rpt-row { display:flex; justify-content:space-between; align-items:flex-start; padding:0.2rem 0; }
        .rpt-key { font-size:0.75rem; color:#64748b; }
        .rpt-val { font-size:0.75rem; font-weight:600; color:#1e293b; text-align:right; max-width:55%; }
        .rpt-divider { border:none; border-top:1px dashed #e2e8f0; margin:0.625rem 0; }
        .rpt-item { padding:0.35rem 0; border-bottom:1px dotted #f1f5f9; }
        .rpt-item:last-child { border-bottom:none; }
        .rpt-item-name { font-size:0.75rem; font-weight:600; color:#1e293b; }
        .rpt-item-unit { font-size:0.625rem; color:#94a3b8; }
        .rpt-item-line { display:flex; justify-content:space-between; padding:0.1rem 0; }
        .rpt-item-detail { font-size:0.6875rem; color:#64748b; }
        .rpt-item-sub { font-size:0.75rem; font-weight:700; color:#d97706; }
        .rpt-total-box { background:#fffbeb; border:1.5px solid #fde68a; border-radius:6px; padding:0.75rem; margin:0.75rem 0; }
        .rpt-total-row { display:flex; justify-content:space-between; align-items:center; }
        .rpt-total-lbl { font-size:0.8125rem; font-weight:700; color:#92400e; }
        .rpt-total-val { font-size:1.125rem; font-weight:800; color:#92400e; }
        .rpt-badge { display:inline-block; padding:0.25rem 0.75rem; border-radius:20px; font-size:0.625rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; margin-top:0.375rem; }
        .rpt-badge.tunai { background:#dcfce7; color:#166534; border:1.5px solid #86efac; }
        .rpt-badge.hutang { background:#fef2f2; color:#991b1b; border:1.5px solid #fecaca; }
        .rpt-badge.transfer { background:#eff6ff; color:#1e40af; border:1.5px solid #bfdbfe; }
        .rpt-transfer-box { background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:6px; padding:0.625rem 0.75rem; margin:0.625rem 0; }
        .rpt-transfer-title { font-size:0.5625rem; font-weight:700; color:#1e40af; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.375rem; }
        .rpt-transfer-row { display:flex; justify-content:space-between; padding:0.15rem 0; }
        .rpt-transfer-key { font-size:0.6875rem; color:#1e40af; }
        .rpt-transfer-val { font-size:0.6875rem; font-weight:700; color:#1e3a5f; }
        .rpt-hutang-box { background:#fff7ed; border:1.5px solid #fed7aa; border-radius:6px; padding:0.625rem 0.75rem; margin:0.625rem 0; }
        .rpt-hutang-title { font-size:0.5625rem; font-weight:700; color:#9a3412; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.375rem; }
        .rpt-hutang-row { display:flex; justify-content:space-between; padding:0.2rem 0; }
        .rpt-hutang-key { font-size:0.6875rem; color:#c2410c; }
        .rpt-hutang-val { font-size:0.6875rem; font-weight:700; color:#9a3412; }
        .rpt-hutang-total { font-size:0.8125rem; font-weight:800; color:#dc2626; }
        .rpt-footer { text-align:center; border-top:2px dashed #e2e8f0; padding-top:0.875rem; margin-top:1rem; }
        .rpt-thanks { font-size:0.6875rem; font-weight:600; color:#92400e; }
        .rpt-note { font-size:0.5625rem; color:#94a3b8; margin-top:0.25rem; }
        .rpt-sales { font-size:0.625rem; color:#64748b; margin-top:0.5rem; }
        .print-bar { text-align:center; margin-bottom:1.5rem; display:flex; gap:0.5rem; justify-content:center; }
        .print-btn {
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 2rem;
            border-radius:10px; font-size:0.875rem; font-weight:700; border:none;
            cursor:pointer; font-family:inherit;
            background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
            box-shadow:0 6px 20px rgba(245,158,11,0.25); transition:all 0.2s;
        }
        .print-btn:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(245,158,11,0.35); }
        .back-btn {
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.5rem;
            border-radius:10px; font-size:0.875rem; font-weight:700; border:1.5px solid #e2e8f0;
            cursor:pointer; font-family:inherit; background:#fff; color:#64748b;
            transition:all 0.2s; text-decoration:none;
        }
        .back-btn:hover { background:#f8fafc; }
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
        <a href="{{ route('gula.penjualan.show', $penjualan->id) }}" class="back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali
        </a>
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
                <div class="rpt-sub">Divisi Gula</div>
                <div class="rpt-faktur">{{ $penjualan->no_faktur }}</div>
                <div class="rpt-date">{{ $penjualan->tanggal_jual->format('d M Y') }} &bull; {{ $penjualan->created_at->format('H:i') }} WIB</div>
            </div>

            {{-- Pelanggan --}}
            <div class="rpt-section">
                <div class="rpt-section-title">Pelanggan</div>
                @if($penjualan->pelanggan)
                    <div class="rpt-row">
                        <span class="rpt-key">Toko</span>
                        <span class="rpt-val">{{ $penjualan->pelanggan->nama_toko }}</span>
                    </div>
                    <div class="rpt-row">
                        <span class="rpt-key">Pemilik</span>
                        <span class="rpt-val">{{ $penjualan->pelanggan->nama_pemilik }}</span>
                    </div>
                    @if($penjualan->pelanggan->no_hp)
                    <div class="rpt-row">
                        <span class="rpt-key">No HP</span>
                        <span class="rpt-val">{{ $penjualan->pelanggan->no_hp }}</span>
                    </div>
                    @endif
                @else
                    <div class="rpt-row">
                        <span class="rpt-key">Pelanggan</span>
                        <span class="rpt-val">-</span>
                    </div>
                @endif
            </div>

            <hr class="rpt-divider">

            {{-- Items (single product for gula) --}}
            <div class="rpt-section">
                <div class="rpt-section-title">Detail Barang</div>
                <div class="rpt-item">
                    <div class="rpt-item-name">{{ $penjualan->produk->nama ?? '-' }}</div>
                    <div class="rpt-item-line">
                        <span class="rpt-item-detail">{{ $penjualan->jumlah }} {{ $penjualan->produk->satuan ?? '' }} x Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</span>
                        <span class="rpt-item-sub">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                    </div>
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
                    $payLabels = [
                        'tunai' => 'TUNAI / CASH',
                        'transfer' => 'TRANSFER',
                        'hutang' => 'KREDIT / HUTANG',
                    ];
                    $payLabel = $payLabels[$penjualan->tipe_bayar] ?? strtoupper($penjualan->tipe_bayar);
                @endphp
                <div class="rpt-badge {{ $penjualan->tipe_bayar }}">{{ $payLabel }}</div>
            </div>

            {{-- Transfer Detail --}}
            @if($penjualan->tipe_bayar === 'transfer' && ($penjualan->transfer_ref || $penjualan->foto_bukti_transfer))
            <div class="rpt-transfer-box">
                <div class="rpt-transfer-title">Detail Transfer</div>
                @if($penjualan->transfer_ref)
                <div class="rpt-transfer-row">
                    <span class="rpt-transfer-key">ID Transaksi</span>
                    <span class="rpt-transfer-val">{{ $penjualan->transfer_ref }}</span>
                </div>
                @endif
                @if($penjualan->foto_bukti_transfer)
                <div class="rpt-transfer-row">
                    <span class="rpt-transfer-key">Bukti Transfer</span>
                    <span class="rpt-transfer-val">Terlampir</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Hutang Detail --}}
            @if($penjualan->tipe_bayar === 'hutang')
            <div class="rpt-section">
                @if($penjualan->bayar > 0)
                <div class="rpt-row">
                    <span class="rpt-key">Dibayar (DP)</span>
                    <span class="rpt-val">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="rpt-row">
                    <span class="rpt-key" style="color:#dc2626;font-weight:700;">Sisa Hutang</span>
                    <span class="rpt-val" style="color:#dc2626;">Rp {{ number_format($penjualan->hutang, 0, ',', '.') }}</span>
                </div>
                @if($penjualan->hutangRecord && $penjualan->hutangRecord->jatuh_tempo)
                <div class="rpt-row">
                    <span class="rpt-key">Jatuh Tempo</span>
                    <span class="rpt-val">{{ $penjualan->hutangRecord->jatuh_tempo->format('d M Y') }}</span>
                </div>
                @endif
            </div>

            {{-- Info Hutang Pelanggan --}}
            @if($penjualan->pelanggan)
            @php
                $totalHutangPelanggan = $penjualan->pelanggan->total_hutang ?? 0;
            @endphp
            <div class="rpt-hutang-box">
                <div class="rpt-hutang-title">Info Hutang Pelanggan</div>
                <div class="rpt-hutang-row">
                    <span class="rpt-hutang-key">Total Hutang Berjalan</span>
                    <span class="rpt-hutang-total">Rp {{ number_format($totalHutangPelanggan, 0, ',', '.') }}</span>
                </div>
                @if($penjualan->pelanggan->limit_hutang > 0)
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
            @endif

            {{-- Keterangan --}}
            @if($penjualan->keterangan)
            <hr class="rpt-divider">
            <div class="rpt-section">
                <div class="rpt-section-title">Catatan</div>
                <div style="font-size:0.6875rem; color:#475569;">{{ $penjualan->keterangan }}</div>
            </div>
            @endif

            {{-- Sales info --}}
            <div class="rpt-sales">
                Sales: {{ $penjualan->sales->nama ?? '-' }}
                @if($penjualan->sales && $penjualan->sales->no_kendaraan)
                    ({{ $penjualan->sales->no_kendaraan }})
                @endif
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
