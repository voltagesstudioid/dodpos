<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Grosir #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            /* Menggunakan Arial yang umum untuk OS dan cukup tebal untuk dot matrix */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            line-height: 1.3;
        }

        /* CONTAINER Utama */
        .page-wrapper {
            width: 210mm;
            margin: 0 auto;
            background: #fff;
        }

        /* Setiap blok/lembar faktur */
        .faktur-copy {
            width: 100%;
            padding: 5mm 10mm 4mm 10mm;
            page-break-inside: avoid;
            position: relative;
        }

        /* GARIS POTONG dengan Label Rangkap di tengah */
        .separator {
            width: 100%;
            border-top: 1px dashed #000;
            position: relative;
            text-align: center;
            margin: 2mm 0;
        }
        .separator-label {
            display: inline-block;
            background: #fff;
            padding: 0 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            top: -7px;
        }

        /* HEADER: Logo & Invoice Info */
        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        .company-info {
            width: 45%;
        }
        .company-info h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }
        .company-info .contact {
            font-size: 10px;
            line-height: 1.4;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            border: 2px solid #000;
            padding: 4px 10px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .invoice-meta {
            font-size: 11px;
            text-align: right;
        }
        .invoice-meta table {
            float: right;
            border-collapse: collapse;
        }
        .invoice-meta td {
            padding: 1px 4px 1px 0;
            text-align: left;
        }
        .invoice-meta td:last-child {
            font-weight: bold;
        }

        /* PELANGGAN BOX */
        .customer-box {
            border: 1px dashed #000;
            padding: 5px 8px;
            width: 50%;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .customer-box .cb-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .customer-box table td {
            vertical-align: top;
            padding-bottom: 2px;
        }

        /* TABEL BARANG */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            border-bottom: 2px solid #000;
        }
        .items-table th {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 4px;
            font-size: 10px;
            text-transform: uppercase;
            text-align: left;
        }
        .items-table th.center { text-align: center; }
        .items-table th.right { text-align: right; }
        
        .items-table td {
            padding: 4px;
            font-size: 11px;
            vertical-align: top;
            border-bottom: 1px solid #000; /* Garis pembatas antar baris tegas */
        }
        .items-table td.center { text-align: center; }
        .items-table td.right { text-align: right; }
        .items-table td.bold { font-weight: bold; }
        .item-name { font-weight: bold; }
        .item-sku { font-size: 9px; display: block; }

        /* FOOTER: Ringkasan & Tanda Tangan */
        .footer-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 5px;
        }
        
        .signatures {
            display: flex;
            gap: 20px;
            width: 50%;
        }
        .sig-box {
            text-align: center;
            width: 120px;
        }
        .sig-title {
            font-size: 10px;
            margin-bottom: 20px;
        }
        .sig-line {
            border-bottom: 1px solid #000;
            margin-bottom: 3px;
        }
        .sig-name {
            font-size: 10px;
        }

        .summary-box {
            width: 40%;
            border: 2px solid #000;
            padding: 5px 8px;
            margin-bottom: 5px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 11px;
        }
        .summary-row.bold {
            font-weight: bold;
        }
        .summary-row.grand-total {
            border-top: 2px solid #000;
            margin-top: 3px;
            padding-top: 3px;
            font-size: 14px;
            font-weight: bold;
        }

        /* CATATAN BAWAH */
        .footer-note {
            font-size: 9px;
            font-style: italic;
            text-align: left;
            margin-top: 2px;
        }

        /* PRINT SETTINGS */
        @media screen {
            .page-wrapper {
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                margin: 20px auto;
            }
            body { background: #e2e8f0; }
        }
        @media print {
            body { background: #fff; margin: 0; }
            .no-print { display: none !important; }
            .page-wrapper { margin: 0; box-shadow: none; width: 100%; }
            @page {
                size: portrait; /* Menyesuaikan kertas printer */
                margin: 0;
            }
        }
    </style>
</head>
<body @if(! request()->boolean('preview')) onload="window.print();" @endif>

@php
    $storeName   = $storeSetting->store_name    ?? 'DODPOS';
    $storeAddr   = $storeSetting->address        ?? '';
    $storePhone  = $storeSetting->phone          ?? '';
    
    $noFaktur    = 'INV-GRS-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
    $tglFaktur   = $transaction->created_at->format('d/m/Y H:i');
    $kasir       = $transaction->user?->name ?? '-';
    
    $pelanggan   = $transaction->customer?->name ?? 'Umum';
    $alamat      = $transaction->customer?->address ?? '-';
    $phone       = $transaction->customer?->phone ?? '-';
    $hutangBerjalan = (float) ($transaction->customer?->current_debt ?? 0);

    $total       = $transaction->total_amount;
    $bayar       = $transaction->paid_amount;
    $kembali     = $transaction->change_amount;
    
    $metodeBayar = match($transaction->payment_method) {
        'cash'     => 'Tunai',
        'transfer' => 'Transfer Bank',
        'qris'     => 'QRIS',
        'kredit'   => 'Kredit / Hutang',
        default    => ucfirst($transaction->payment_method),
    };
    
    $isKredit    = $transaction->payment_method === 'kredit';
    $hutang      = $isKredit ? max(0, $total - $bayar) : 0;

    $copies = [
        ['label' => 'LEMBAR 1 : TOKO'],
        ['label' => 'LEMBAR 2 : PELANGGAN'],
        ['label' => 'LEMBAR 3 : GUDANG'],
    ];
@endphp

<div class="page-wrapper">

    @foreach($copies as $idx => $copy)

        {{-- Jika ini bukan lembar pertama, cetak garis potong + label rangkap sebelumnya di tengah --}}
        @if($idx > 0)
        <div class="separator">
            <span class="separator-label">✂ Potong Di Sini ({{ $copies[$idx-1]['label'] }}) ✂</span>
        </div>
        @endif

        <div class="faktur-copy">
            
            {{-- Bagian label khusus lembar ke 3 (Bawah) jika tidak ada garis pemotong lagi --}}
            @if($idx === 2)
                <div style="text-align: right; margin-bottom: 2px;">
                    <span style="font-size:10px; font-weight:bold; border: 1px solid #000; padding: 2px 5px;">{{ $copy['label'] }}</span>
                </div>
            @endif

            {{-- HEADER KOMPETEN (Kop Toko & Info Faktur) --}}
            <div class="header-wrapper">
                <div class="company-info">
                    <h1>{{ $storeName }}</h1>
                    <div class="contact">
                        @if($storeAddr){{ $storeAddr }}<br>@endif
                        @if($storePhone)Telp: {{ $storePhone }}@endif
                    </div>
                </div>
                <div class="invoice-title">
                    <h2>FAKTUR PENJUALAN</h2>
                    <div class="invoice-meta">
                        <table>
                            <tr>
                                <td>No. Faktur</td>
                                <td>:</td>
                                <td>{{ $noFaktur }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td>{{ $tglFaktur }}</td>
                            </tr>
                            <tr>
                                <td>Kasir</td>
                                <td>:</td>
                                <td>{{ $kasir }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- INFO PELANGGAN --}}
            <div class="customer-box">
                <div class="cb-title">KEPADA YTH:</div>
                <table>
                    <tr>
                        <td width="55">Nama</td>
                        <td width="10">:</td>
                        <td><b>{{ $pelanggan }}</b></td>
                    </tr>
                    @if($phone !== '-')
                    <tr>
                        <td>Telepon</td>
                        <td>:</td>
                        <td>{{ $phone }}</td>
                    </tr>
                    @endif
                    @if($alamat !== '-')
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $alamat }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            {{-- TABEL BARANG --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="center" width="5%">No</th>
                        <th width="40%">Deskripsi Barang</th>
                        <th class="center" width="10%">Qty</th>
                        <th class="right" width="20%">Hrg Satuan</th>
                        <th class="right" width="25%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $i => $detail)
                    @php
                        $displayQty  = $detail->unit_qty  ?? $detail->quantity;
                        $displayUnit = $detail->unit_name ?? 'pcs';
                        $displayPrice = ($displayQty > 0) ? $detail->subtotal / $displayQty : $detail->price;
                    @endphp
                    <tr>
                        <td class="center">{{ $i + 1 }}</td>
                        <td>
                            <span class="item-name">{{ $detail->product?->name ?? 'Barang tidak tersedia' }}</span>
                            @if($detail->product?->sku)
                            <span class="item-sku">SKU: {{ $detail->product->sku }}</span>
                            @endif
                        </td>
                        <td class="center">{{ $displayQty }} {{ $displayUnit }}</td>
                        <td class="right">{{ number_format($displayPrice, 0, ',', '.') }}</td>
                        <td class="right bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- FOOTER: Tanda tangan & Total Pembayaran --}}
            <div class="footer-wrapper">
                <div class="signatures">
                    <div class="sig-box">
                        <div class="sig-title">Penerima,</div>
                        <div class="sig-line"></div>
                        <div class="sig-name">( Nama Jelas )</div>
                    </div>
                    <div class="sig-box">
                        <div class="sig-title">Hormat Kami,</div>
                        <div class="sig-line"></div>
                        <div class="sig-name">{{ $kasir }}</div>
                    </div>
                </div>
                
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Jumlah Barang</span>
                        <span class="bold">{{ $transaction->details->sum('quantity') }} Item</span>
                    </div>
                    <div class="summary-row grand-total">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Bayar ({{ $metodeBayar }})</span>
                        <span class="bold">Rp {{ number_format($bayar, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($isKredit && $hutang > 0)
                    <div class="summary-row bold" style="margin-top: 3px;">
                        <span>Sisa Hutang</span>
                        <span>Rp {{ number_format($hutang, 0, ',', '.') }}</span>
                    </div>
                    @else
                    <div class="summary-row bold" style="margin-top: 3px;">
                        <span>Kembali</span>
                        <span>Rp {{ number_format($kembali, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    @if($transaction->customer && $hutangBerjalan > 0)
                    <div class="summary-row bold" style="margin-top: 3px;">
                        <span>Total Hutang Berjalan</span>
                        <span>Rp {{ number_format($hutangBerjalan, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="footer-note">
                * Barang yang sudah dibeli & diterima dengan baik tidak dapat ditukar/dikembalikan. <br>
                * Jika ini lembar pertama (Lembar 1), letakkan lembar ini sebagai arsip pembayaran TOKO.
            </div>

        </div>{{-- /faktur-copy --}}

    @endforeach

</div>{{-- /page-wrapper --}}

<div class="no-print" style="text-align:center; padding:20px 0; background:#f1f5f9; position:fixed; bottom:0; width:100%; border-top:1px solid #ddd;">
    <button onclick="window.print()" style="padding:10px 30px; font-size:16px; font-weight:bold; cursor:pointer; background:#000; color:#fff; border:none; border-radius:4px; margin-right:10px;">🖨️ Cetak</button>
    <button onclick="window.close()" style="padding:10px 30px; font-size:16px; font-weight:bold; cursor:pointer; background:#fff; color:#000; border:2px solid #000; border-radius:4px;">✕ Tutup</button>
</div>

</body>
</html>
