<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $penjualan->nomor_transaksi }}</title>
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
        .rpt-item-sub { font-size:0.75rem; font-weight:700; color:#059669; }
        .rpt-total-box { background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:6px; padding:0.75rem; margin:0.75rem 0; }
        .rpt-total-row { display:flex; justify-content:space-between; align-items:center; }
        .rpt-total-lbl { font-size:0.8125rem; font-weight:700; color:#065f46; }
        .rpt-total-val { font-size:1.125rem; font-weight:800; color:#065f46; }
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
        .rpt-thanks { font-size:0.6875rem; font-weight:600; color:#065f46; }
        .rpt-note { font-size:0.5625rem; color:#94a3b8; margin-top:0.25rem; }
        .rpt-sales { font-size:0.625rem; color:#64748b; margin-top:0.5rem; }
        .rpt-cat { font-size:0.5625rem; color:#94a3b8; }
        .print-bar { text-align:center; margin-bottom:1.5rem; display:flex; gap:0.5rem; justify-content:center; }
        .print-btn {
            display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 2rem;
            border-radius:10px; font-size:0.875rem; font-weight:700; border:none;
            cursor:pointer; font-family:inherit;
            background:linear-gradient(135deg,#10b981,#059669); color:#fff;
            box-shadow:0 6px 20px rgba(16,185,129,0.25); transition:all 0.2s;
        }
        .print-btn:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(16,185,129,0.35); }
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
    <div class="print-bar" style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
        <a href="{{ route('pasgar.penjualan.show', $penjualan->id) }}" class="back-btn">← Kembali</a>
        <button class="print-btn" onclick="window.print()">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Struk
        </button>
        <button class="print-btn" onclick="printBluetooth()" style="background:linear-gradient(135deg,#059669,#047857);">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 8l12 8-6 6V2l6 6-12 8"/></svg>
            Web Bluetooth
        </button>
        <button class="print-btn" onclick="printRawBT()" style="background:linear-gradient(135deg,#db2777,#be185d);">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak via RawBT
        </button>
    </div>


    @foreach(['ASLI', 'COPY'] as $jenisStruk)
    <div class="receipt-wrap" style="{{ $loop->first ? 'margin-bottom: 2rem;' : '' }}">
        <div class="receipt">
            {{-- Header --}}
            <div class="rpt-hdr">
                <div style="font-size:0.625rem; font-weight:800; border:1px solid #065f46; border-radius:4px; display:inline-block; padding:2px 8px; margin-bottom:8px; color:#065f46; letter-spacing:1px;">{{ $jenisStruk }}</div>
                <div class="rpt-logo">DOD POS</div>
                <div class="rpt-sub">Pasukan Garuda</div>
                <div class="rpt-faktur">{{ $penjualan->nomor_transaksi }}</div>
                <div class="rpt-date">{{ $penjualan->tanggal->format('d M Y • H:i') }} WIB</div>
            </div>

            {{-- Pelanggan --}}
            <div class="rpt-section">
                <div class="rpt-section-title">Pelanggan</div>
                @if($penjualan->pelanggan_id && $penjualan->pelanggan)
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
                        <span class="rpt-key">Nama</span>
                        <span class="rpt-val">{{ $penjualan->nama_pelanggan ?: 'Umum' }}</span>
                    </div>
                    @if($penjualan->telepon_pelanggan)
                    <div class="rpt-row">
                        <span class="rpt-key">No HP</span>
                        <span class="rpt-val">{{ $penjualan->telepon_pelanggan }}</span>
                    </div>
                    @endif
                @endif
            </div>

            <hr class="rpt-divider">

            {{-- Items --}}
            <div class="rpt-section">
                <div class="rpt-section-title">Detail Barang ({{ $penjualan->items->count() }} item)</div>
                @foreach($penjualan->items as $item)
                <div class="rpt-item">
                    <div class="rpt-item-name">{{ $item->product->name ?? '-' }}</div>
                    @if($item->unitConversion)
                        <span class="rpt-item-unit">{{ $item->unitConversion->unit->name ?? '' }}</span>
                    @endif
                    <div class="rpt-item-line">
                        <span class="rpt-item-detail">{{ $item->qty }} × Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        <span class="rpt-item-sub">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
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
                        'tunai' => '💵 TUNAI / CASH',
                        'transfer' => '🏦 TRANSFER',
                        'hutang' => '📝 KREDIT / HUTANG',
                    ];
                    $payLabel = $payLabels[$penjualan->metode_bayar] ?? strtoupper($penjualan->metode_bayar);
                @endphp
                <div class="rpt-badge {{ $penjualan->metode_bayar }}">{{ $payLabel }}</div>
            </div>

            {{-- Transfer Detail --}}
            @if($penjualan->metode_bayar === 'transfer' && ($penjualan->id_transaksi_transfer || $penjualan->foto_bukti_transfer))
            <div class="rpt-transfer-box">
                <div class="rpt-transfer-title">🏦 Detail Transfer</div>
                @if($penjualan->id_transaksi_transfer)
                <div class="rpt-transfer-row">
                    <span class="rpt-transfer-key">ID Transaksi</span>
                    <span class="rpt-transfer-val">{{ $penjualan->id_transaksi_transfer }}</span>
                </div>
                @endif
                @if($penjualan->foto_bukti_transfer)
                <div class="rpt-transfer-row">
                    <span class="rpt-transfer-key">Bukti Transfer</span>
                    <span class="rpt-transfer-val">✓ Terlampir</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Hutang Detail --}}
            @if($penjualan->metode_bayar === 'hutang')
            <div class="rpt-section">
                @if($penjualan->uang_muka > 0)
                <div class="rpt-row">
                    <span class="rpt-key">Dibayar (DP)</span>
                    <span class="rpt-val">Rp {{ number_format($penjualan->uang_muka, 0, ',', '.') }}</span>
                </div>
                <div class="rpt-row">
                    <span class="rpt-key" style="color:#dc2626;font-weight:700;">Sisa Hutang</span>
                    <span class="rpt-val" style="color:#dc2626;">Rp {{ number_format($penjualan->total - $penjualan->uang_muka, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($penjualan->hutang && $penjualan->hutang->jatuh_tempo)
                <div class="rpt-row">
                    <span class="rpt-key">Jatuh Tempo</span>
                    <span class="rpt-val">{{ $penjualan->hutang->jatuh_tempo->format('d M Y') }}</span>
                </div>
                @endif
            </div>

            {{-- Info Hutang Pelanggan --}}
            @if($penjualan->pelanggan)
            @php
                $totalHutangPelanggan = $penjualan->pelanggan->total_hutang ?? 0;
            @endphp
            <div class="rpt-hutang-box">
                <div class="rpt-hutang-title">📋 Info Hutang Pelanggan</div>
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
            @if($penjualan->catatan)
            <hr class="rpt-divider">
            <div class="rpt-section">
                <div class="rpt-section-title">Catatan</div>
                <div style="font-size:0.6875rem; color:#475569;">{{ $penjualan->catatan }}</div>
            </div>
            @endif

            {{-- Loading ref --}}
            @if($penjualan->loading)
            <div style="font-size:0.5625rem;color:#94a3b8;margin-top:0.5rem;">
                Ref Loading: {{ $penjualan->loading->nomor_loading }}
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
    @if($loop->first)
    <div style="text-align:center; margin:1.5rem 0; color:#94a3b8; font-size:12px; display:flex; align-items:center; gap:8px;">
        <div style="flex:1; border-top:1px dashed #cbd5e1;"></div>
        <div>&#9988; Potong di sini &#9988;</div>
        <div style="flex:1; border-top:1px dashed #cbd5e1;"></div>
    </div>
    @endif
    @endforeach

    <script>
        function padLeft(text, length) {
            return (" ".repeat(length) + text).slice(-length);
        }

        function formatLine(left, right) {
            const totalLength = 32;
            if (left.length + right.length > totalLength) {
                return left + "\n" + padLeft(right, totalLength);
            }
            const spaces = totalLength - left.length - right.length;
            return left + " ".repeat(spaces) + right;
        }

        function getReceiptText(jenisStruk) {
            const ESC = '\x1B';
            let receipt = "";

            // Initialize
            receipt += ESC + '@';
            
            // Align Center
            receipt += ESC + 'a' + '\x01';
            receipt += "*** " + jenisStruk + " ***\n";
            receipt += "DOD POS\n";
            receipt += "Pasukan Garuda\n";
            receipt += "--------------------------------\n";
            
            // Align Left
            receipt += ESC + 'a' + '\x00';
            receipt += "Faktur: {{ $penjualan->nomor_transaksi }}\n";
            receipt += "Tgl   : {{ $penjualan->tanggal->format('d/m/Y H:i') }}\n";
            receipt += "--------------------------------\n";
            
            // Pelanggan
            @if($penjualan->pelanggan_id && $penjualan->pelanggan)
            receipt += "Toko   : {{ $penjualan->pelanggan->nama_toko }}\n";
            receipt += "Pemilik: {{ $penjualan->pelanggan->nama_pemilik }}\n";
            @else
            receipt += "Nama   : {{ $penjualan->nama_pelanggan ?: 'Umum' }}\n";
            @if($penjualan->telepon_pelanggan)
            receipt += "No HP  : {{ $penjualan->telepon_pelanggan }}\n";
            @endif
            @endif
            receipt += "--------------------------------\n";
            
            // Produk (Multiple Items)
            @foreach($penjualan->items as $item)
            receipt += formatLine("{{ $item->product->name ?? '-' }}", "{{ $item->qty }} {{ $item->unitConversion->unit->name ?? '' }}") + "\n";
            receipt += formatLine("Rp {{ number_format($item->harga, 0, ',', '.') }}", "Rp {{ number_format($item->subtotal, 0, ',', '.') }}") + "\n";
            @if(!$loop->last)
            receipt += "\n";
            @endif
            @endforeach
            receipt += "--------------------------------\n";
            
            // Total
            receipt += formatLine("TOTAL", "Rp {{ number_format($penjualan->total, 0, ',', '.') }}") + "\n";
            
            // Tipe Bayar
            let tipeBayar = "{{ $penjualan->metode_bayar }}";
            if (tipeBayar === 'tunai') {
                receipt += "PEMBAYARAN: TUNAI / CASH\n";
            } else if (tipeBayar === 'hutang') {
                receipt += "PEMBAYARAN: KREDIT / HUTANG\n";
                receipt += formatLine("DP", "Rp {{ number_format($penjualan->uang_muka ?? 0, 0, ',', '.') }}") + "\n";
                receipt += formatLine("SISA", "Rp {{ number_format($penjualan->total - $penjualan->uang_muka, 0, ',', '.') }}") + "\n";
                
                @if($penjualan->pelanggan)
                let limit = {{ $penjualan->pelanggan->limit_hutang ?? 0 }};
                let totalHutang = {{ $penjualan->pelanggan->total_hutang ?? 0 }};
                if (limit > 0) {
                    receipt += "--------------------------------\n";
                    receipt += formatLine("Limit Kredit", "Rp " + limit.toLocaleString('id-ID')) + "\n";
                    receipt += formatLine("Sisa Limit", "Rp " + Math.max(0, limit - totalHutang).toLocaleString('id-ID')) + "\n";
                }
                @endif
            } else {
                receipt += "PEMBAYARAN: TRANSFER\n";
                @if($penjualan->id_transaksi_transfer)
                receipt += "Ref: {{ $penjualan->id_transaksi_transfer }}\n";
                @endif
            }
            
            receipt += "--------------------------------\n";
            receipt += "Sales: {{ $penjualan->sales->nama ?? '-' }}\n";
            
            @if($penjualan->catatan)
            receipt += "\nKet: {{ $penjualan->catatan }}\n";
            @endif
            
            @if($penjualan->loading)
            receipt += "Ref Loading: {{ $penjualan->loading->nomor_loading }}\n";
            @endif

            receipt += "--------------------------------\n";
            
            // Align Center
            receipt += ESC + 'a' + '\x01';
            receipt += "Terima Kasih\n";
            receipt += "Struk ini adalah bukti\n";
            receipt += "transaksi yang sah.\n";
            
            // Feed paper
            receipt += "\n\n\n\n";

            return receipt;
        }

        function printRawBT() {
            try {
                let receiptASLI = getReceiptText("ASLI");
                let receiptCOPY = getReceiptText("COPY");
                let fullReceipt = receiptASLI + "\n\n--------------------------------\n\n" + receiptCOPY;
                let base64Data = btoa(fullReceipt);
                let intentUrl = "intent:" + base64Data + "#Intent;scheme=rawbt:base64;package=ru.a402d.rawbtprinter;end;";
                window.location.href = intentUrl;
            } catch (error) {
                console.error(error);
                alert("Gagal memproses struk untuk RawBT: " + error.message);
            }
        }

        async function printBluetooth() {
            try {
                if (!navigator.bluetooth) {
                    alert("Browser Anda tidak mendukung Web Bluetooth. Gunakan Google Chrome di Android atau PC.");
                    return;
                }

                const device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: [
                        '000018f0-0000-1000-8000-00805f9b34fb',
                        'e7810a71-73ae-499d-8c15-faa9aef0c3f2',
                        '0000fee7-0000-1000-8000-00805f9b34fb',
                        '49535343-fe7d-4ae5-8fa9-9fafd205e455'
                    ]
                });

                console.log('Connecting to GATT Server...');
                const server = await device.gatt.connect();

                console.log('Getting Services...');
                const services = await server.getPrimaryServices();

                let characteristic = null;
                for (const service of services) {
                    const characteristics = await service.getCharacteristics();
                    for (const c of characteristics) {
                        if (c.properties.writeWithoutResponse || c.properties.write) {
                            characteristic = c;
                            break;
                        }
                    }
                    if (characteristic) break;
                }

                if (!characteristic) {
                    alert("Karakteristik Bluetooth untuk print tidak ditemukan pada perangkat ini!");
                    return;
                }

                let receiptASLI = getReceiptText("ASLI");
                let receiptCOPY = getReceiptText("COPY");
                let fullReceipt = receiptASLI + "\n\n--------------------------------\n\n" + receiptCOPY;

                const encoder = new TextEncoder();
                const data = encoder.encode(fullReceipt);

                const CHUNK_SIZE = 50;
                for (let i = 0; i < data.length; i += CHUNK_SIZE) {
                    const chunk = data.slice(i, i + CHUNK_SIZE);
                    if (characteristic.properties.writeWithoutResponse) {
                        await characteristic.writeValueWithoutResponse(chunk);
                    } else {
                        await characteristic.writeValue(chunk);
                    }
                    await new Promise(r => setTimeout(r, 40));
                }
                
                alert("Struk berhasil dikirim ke printer!");
            } catch (error) {
                console.error('Print Error:', error);
                alert("Gagal print via Bluetooth.\n" + error.message);
            }
        }
    </script>
</body>
</html>
