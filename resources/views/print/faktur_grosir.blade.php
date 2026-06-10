<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Grosir #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:Arial,Helvetica,sans-serif;font-size:11px;color:#000;background:#fff;line-height:1.3}

        /* ── MASTER TABLE (anti page-break) ── */
        .faktur-table{width:100%;border-collapse:collapse;table-layout:fixed}
        .faktur-table td{vertical-align:top;padding:0}

        /* ── SCREEN wrapper only ── */
        .page-wrapper{width:210mm;margin:0 auto;background:#fff;padding:5mm 8mm}

        /* ── PAYMENT BADGE ── */
        .payment-badge{
            display:inline-block;padding:3px 10px;font-size:10px;font-weight:bold;
            text-transform:uppercase;letter-spacing:1px;border:2px solid #000
        }
        .payment-badge.cash{background:#e8f5e9;border-color:#2e7d32;color:#1b5e20}
        .payment-badge.transfer{background:#e3f2fd;border-color:#1565c0;color:#0d47a1}
        .payment-badge.qris{background:#f3e5f5;border-color:#7b1fa2;color:#4a148c}
        .payment-badge.kredit{background:#fff3e0;border-color:#e65100;color:#bf360c}

        /* ── SECTION DIVIDERS ── */
        .sec-border{border-bottom:2px solid #000}
        .sec-border-thin{border-bottom:1px solid #999}
        .spacer{height:5px}
        .spacer-sm{height:3px}

        /* ── COMPANY + INVOICE META ── */
        .company-name{font-size:17px;font-weight:bold;letter-spacing:1px}
        .company-contact{font-size:9px;line-height:1.4;color:#333}
        .inv-title{font-size:16px;font-weight:bold;letter-spacing:2px;border:2px solid #000;padding:3px 8px;display:inline-block}
        .meta-table{font-size:10px;border-collapse:collapse}
        .meta-table td{padding:1px 3px 1px 0;vertical-align:top}
        .meta-table td:last-child{font-weight:bold}

        /* ── CUSTOMER + INFO BOXES ── */
        .info-box{border:1px dashed #000;padding:4px 7px;font-size:10px;vertical-align:top}
        .info-box-solid{border:1px solid #000;padding:4px 7px;font-size:10px;vertical-align:top}
        .box-title{font-weight:bold;font-size:9px;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px}
        .detail-table{font-size:10px;border-collapse:collapse;width:100%}
        .detail-table td{padding:1px 0;vertical-align:top}
        .status-tag{display:inline-block;padding:1px 5px;font-size:8px;font-weight:bold;text-transform:uppercase;border:1px solid #000;letter-spacing:.3px}

        /* ── ITEMS TABLE ── */
        .items-table{width:100%;border-collapse:collapse}
        .items-table thead th{
            background:#eee;border-top:2px solid #000;border-bottom:2px solid #000;
            padding:3px 4px;font-size:9px;text-transform:uppercase;text-align:left;
            -webkit-print-color-adjust:exact;print-color-adjust:exact
        }
        .items-table th.center{text-align:center}
        .items-table th.right{text-align:right}
        .items-table td{padding:3px 4px;font-size:10px;vertical-align:top;border-bottom:1px solid #ddd}
        .items-table td.center{text-align:center}
        .items-table td.right{text-align:right}
        .items-table td.bold{font-weight:bold}
        .item-name{font-weight:bold}
        .item-sku{font-size:8px;display:block;color:#666}

        /* ── SIGNATURE + SUMMARY ── */
        .sig-box{text-align:center;width:110px;font-size:10px}
        .sig-title{margin-bottom:30px}
        .sig-line{border-bottom:1px solid #000;margin-bottom:2px}
        .sig-name{font-size:9px}
        .summary-box{border:2px solid #000;padding:4px 7px;font-size:10px}
        .sum-row{padding:1px 0}
        .sum-row .label{float:left}
        .sum-row .value{float:right;font-weight:bold}
        .sum-row::after{content:'';display:table;clear:both}
        .sum-total{border-top:2px solid #000;margin-top:3px;padding-top:3px;font-size:13px;font-weight:bold}
        .sum-total .label{float:left}
        .sum-total .value{float:right}
        .sum-total::after{content:'';display:table;clear:both}
        .sum-hutang{color:#c62828}

        /* ── BANK + NOTES ── */
        .bank-info{padding:3px 7px;border:1px dashed #000;font-size:9px;display:inline-block;margin-top:4px}
        .add-notes{padding:3px 7px;border:1px solid #ccc;font-size:9px;font-style:italic;margin-top:4px}
        .footer-note{font-size:8px;font-style:italic;margin-top:10px;padding-top:8px;border-top:1px solid #ccc;color:#555}

        /* ── PRINT ── */
        @media screen{
            .page-wrapper{box-shadow:0 4px 15px rgba(0,0,0,.1);margin:20px auto}
            body{background:#e2e8f0}
        }
        @media print{
            html,body{margin:0;padding:0;background:#fff}
            .no-print{display:none!important}
            .page-wrapper{margin:0;padding:3mm 6mm 4mm 6mm;box-shadow:none;width:100%}
            .faktur-table{page-break-inside:avoid}
            .faktur-table tr{page-break-inside:avoid}
            .items-table tbody tr{page-break-inside:avoid}
            .items-table thead{page-break-after:avoid;break-after:avoid}
            thead{display:table-header-group}
            tfoot{display:table-footer-group}
            {!! '@' !!}page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body @if(! request()->boolean('preview')) onload="window.print();" @endif>

@php
    $storeName   = $storeSetting->store_name    ?? 'DODPOS';
    $storeAddr   = $storeSetting->store_address ?? '';
    $storePhone  = $storeSetting->store_phone   ?? '';
    $storeEmail  = $storeSetting->store_email   ?? '';
    $bankName    = $storeSetting->bank_name     ?? '';
    $bankAccNo   = $storeSetting->bank_account_number ?? '';
    $bankHolder  = $storeSetting->bank_account_holder ?? '';

    $noFaktur    = 'INV-GRS-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
    $tglFaktur   = $transaction->created_at->format('d/m/Y H:i');
    $kasir       = $transaction->user?->name ?? '-';

    $pelanggan   = $transaction->customer?->name ?? 'Umum';
    $alamat      = $transaction->customer?->address ?? '-';
    $phone       = $transaction->customer?->phone ?? '-';
    $email       = $transaction->customer?->email ?? '';
    $hutangBerjalan = (float) ($transaction->customer?->current_debt ?? 0);

    $total       = $transaction->total_amount;
    $bayar       = $transaction->paid_amount;
    $kembali     = $transaction->change_amount;

    $metodeBayar = match($transaction->payment_method) {
        'cash'     => 'Tunai',
        'transfer' => 'Transfer',
        'qris'     => 'QRIS',
        'kredit'   => 'Kredit',
        default    => ucfirst($transaction->payment_method),
    };

    $paymentClass = match($transaction->payment_method) {
        'cash'     => 'cash',
        'transfer' => 'transfer',
        'qris'     => 'qris',
        'kredit'   => 'kredit',
        default    => 'cash',
    };

    $isKredit    = $transaction->payment_method === 'kredit';
    $isTransfer  = $transaction->payment_method === 'transfer';
    $hutang      = $isKredit ? max(0, $total - $bayar) : 0;

    $warehouse   = $transaction->sourceWarehouse?->name ?? '';
    $deliveryStatusLabel = match($transaction->delivery_status ?? 'pending') {
        'pending'    => 'Menunggu',
        'packing'    => 'Dikemas',
        'ready'      => 'Siap Kirim',
        'delivering' => 'Dalam Pengiriman',
        'delivered'  => 'Terkirim',
        'cancelled'  => 'Dibatalkan',
        default      => ucfirst($transaction->delivery_status ?? '-'),
    };
    $packedBy    = $transaction->packedBy?->name ?? '';
    $checkedBy   = $transaction->checkedBy?->name ?? '';
    $deliveredBy = $transaction->deliveredBy?->name ?? '';
    $itemCount   = $transaction->details->count();
    $totalQty    = $transaction->details->sum('quantity');
@endphp

<div class="page-wrapper">
<table class="faktur-table">

    {{-- ═══ ROW 1: HEADER ═══ --}}
    <tr>
        <td colspan="2" class="sec-border" style="padding-bottom:10px">
            <table style="width:100%;border-collapse:collapse">
                <tr>
                    {{-- Company --}}
                    <td style="width:40%;vertical-align:top">
                        <div class="company-name">{{ $storeName }}</div>
                        <div class="company-contact">
                            @if($storeAddr){{ $storeAddr }}@endif
                            @if($storeAddr && ($storePhone || $storeEmail))<br>@endif
                            @if($storePhone)Telp: {{ $storePhone }}@endif
                            @if($storePhone && $storeEmail) &bull; @endif
                            @if($storeEmail){{ $storeEmail }}@endif
                        </div>
                    </td>
                    {{-- Invoice Title + Meta --}}
                    <td style="width:35%;vertical-align:top;text-align:right">
                        <div class="inv-title">FAKTUR PENJUALAN</div>
                        <table class="meta-table" style="float:right;margin-top:3px">
                            <tr><td>No. Faktur</td><td>:</td><td>{{ $noFaktur }}</td></tr>
                            <tr><td>Tanggal</td><td>:</td><td>{{ $tglFaktur }}</td></tr>
                            <tr><td>Kasir</td><td>:</td><td>{{ $kasir }}</td></tr>
                            <tr><td>Tipe</td><td>:</td><td>{{ ucfirst($transaction->sale_type ?? 'Grosir') }}</td></tr>
                        </table>
                    </td>
                    {{-- Payment Badge --}}
                    <td style="width:25%;vertical-align:top;text-align:right">
                        <div class="payment-badge {{ $paymentClass }}">{{ $metodeBayar }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ═══ ROW 2: PELANGGAN + INFO PENGIRIMAN ═══ --}}
    <tr>
        <td colspan="2" style="padding-top:8px;padding-bottom:8px">
            <table style="width:100%;border-collapse:collapse">
                <tr>
                    {{-- Customer Box --}}
                    <td style="width:55%;padding-right:4px">
                        <div class="info-box">
                            <div class="box-title">Kepada Yth:</div>
                            <table class="detail-table">
                                <tr><td width="50">Nama</td><td width="8">:</td><td><b>{{ $pelanggan }}</b></td></tr>
                                @if($phone !== '-')<tr><td>Telepon</td><td>:</td><td>{{ $phone }}</td></tr>@endif
                                @if($email)<tr><td>Email</td><td>:</td><td>{{ $email }}</td></tr>@endif
                                @if($alamat !== '-')<tr><td>Alamat</td><td>:</td><td>{{ $alamat }}</td></tr>@endif
                                @if($transaction->vehicle_id || $transaction->driver_name)
                                <tr>
                                    <td>Kendaraan</td><td>:</td>
                                    <td>
                                        @if($transaction->vehicle){{ $transaction->vehicle->license_plate }}@if($transaction->vehicle->type) ({{ $transaction->vehicle->type }})@endif @endif
                                        @if($transaction->driver_name)- {{ $transaction->driver_name }}@endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </td>
                    {{-- Info Pengiriman --}}
                    <td style="width:45%;padding-left:4px">
                        <div class="info-box-solid">
                            <div class="box-title">Info Pengiriman</div>
                            <table class="detail-table">
                                @if($warehouse)<tr><td width="70">Gudang</td><td width="8">:</td><td>{{ $warehouse }}</td></tr>@endif
                                <tr><td>Status Kirim</td><td>:</td><td><span class="status-tag">{{ $deliveryStatusLabel }}</span></td></tr>
                                @if($packedBy)<tr><td>Dikemas</td><td>:</td><td>{{ $packedBy }}</td></tr>@endif
                                @if($checkedBy)<tr><td>Diperiksa</td><td>:</td><td>{{ $checkedBy }}</td></tr>@endif
                                @if($deliveredBy)<tr><td>Dikirim</td><td>:</td><td>{{ $deliveredBy }}</td></tr>@endif
                                <tr><td>Cetak ke-</td><td>:</td><td>{{ $transaction->print_count }}</td></tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ═══ ROW 3: TABEL BARANG ═══ --}}
    <tr>
        <td colspan="2">
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="center" width="4%">No</th>
                        <th width="38%">Deskripsi Barang</th>
                        <th class="center" width="10%">Qty</th>
                        <th class="right" width="18%">Hrg Satuan</th>
                        <th class="right" width="22%">Subtotal</th>
                        @if($warehouse)<th class="center" width="8%">Gdg</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $i => $detail)
                    @php
                        $displayQty   = ($detail->unit_qty !== null && $detail->unit_qty > 0) ? $detail->unit_qty : $detail->quantity;
                        $displayUnit  = $detail->unit_name ?? 'pcs';
                        $displayPrice = ($displayQty > 0) ? ($detail->subtotal / $displayQty) : $detail->price;
                        $detailWh     = $detail->warehouse?->name ?? '';
                    @endphp
                    <tr>
                        <td class="center">{{ $i + 1 }}</td>
                        <td>
                            <span class="item-name">{{ $detail->product?->name ?? 'Barang tidak tersedia' }}</span>
                            @if($detail->product?->sku)<span class="item-sku">{{ $detail->product->sku }}</span>@endif
                        </td>
                        <td class="center">{{ $displayQty }} {{ $displayUnit }}</td>
                        <td class="right">{{ number_format($displayPrice, 0, ',', '.') }}</td>
                        <td class="right bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        @if($warehouse)<td class="center">{{ Str::limit($detailWh, 8) }}</td>@endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>

    {{-- ═══ ROW 4: TANDA TANGAN + RINGKASAN ═══ --}}
    <tr>
        <td colspan="2" style="padding-top:12px">
            <table style="width:100%;border-collapse:collapse">
                <tr>
                    {{-- Signatures --}}
                    <td style="width:55%;vertical-align:top">
                        <table style="border-collapse:collapse">
                            <tr>
                                <td class="sig-box" style="padding-right:20px">
                                    <div class="sig-title">Penerima,</div>
                                    <div class="sig-line"></div>
                                    <div class="sig-name">( {{ $pelanggan }} )</div>
                                </td>
                                <td class="sig-box">
                                    <div class="sig-title">Hormat Kami,</div>
                                    <div class="sig-line"></div>
                                    <div class="sig-name">{{ $kasir }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    {{-- Summary --}}
                    <td style="width:45%;vertical-align:top">
                        <div class="summary-box">
                            <div class="sum-row"><span class="label">Jumlah Barang</span><span class="value">{{ $itemCount }} Jenis / {{ $totalQty }} pcs</span></div>
                            <div class="sum-total"><span class="label">TOTAL</span><span class="value">Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                            <div class="sum-row" style="margin-top:2px"><span class="label">Bayar</span><span class="value">Rp {{ number_format($bayar, 0, ',', '.') }}</span></div>
                            @if($isKredit && $hutang > 0)
                            <div class="sum-row sum-hutang" style="margin-top:2px"><span class="label"><b>Sisa Hutang</b></span><span class="value"><b>Rp {{ number_format($hutang, 0, ',', '.') }}</b></span></div>
                            @else
                            <div class="sum-row" style="margin-top:2px"><span class="label"><b>Kembali</b></span><span class="value"><b>Rp {{ number_format($kembali, 0, ',', '.') }}</b></span></div>
                            @endif
                            @if($transaction->customer && $hutangBerjalan > 0)
                            <div class="sum-row sum-hutang" style="margin-top:2px"><span class="label"><b>Total Hutang</b></span><span class="value"><b>Rp {{ number_format($hutangBerjalan, 0, ',', '.') }}</b></span></div>
                            @endif
                            @if($transaction->payment_reference)
                            <div class="sum-row" style="margin-top:2px;font-size:9px"><span class="label">Ref.</span><span class="value">{{ $transaction->payment_reference }}</span></div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ═══ ROW 5: BANK INFO (transfer only) ═══ --}}
    @if($isTransfer && ($bankName || $bankAccNo))
    <tr>
        <td colspan="2">
            <div class="bank-info">
                <b>Transfer ke:</b> {{ $bankName }}
                @if($bankAccNo)
                    &mdash; {{ $bankAccNo }}
                @endif
                @if($bankHolder)
                    &mdash; a.n. {{ $bankHolder }}
                @endif
            </div>
        </td>
    </tr>
    @endif

    {{-- ═══ ROW 6: ADDITIONAL NOTES ═══ --}}
    @if($transaction->additional_notes)
    <tr>
        <td colspan="2">
            <div class="add-notes"><b>Catatan:</b> {{ $transaction->additional_notes }}</div>
        </td>
    </tr>
    @endif

    {{-- ═══ ROW 7: FOOTER NOTE ═══ --}}
    <tr>
        <td colspan="2">
            <div class="footer-note">
                * Barang yang sudah dibeli &amp; diterima dengan baik tidak dapat ditukar/dikembalikan.<br>
                * Faktur ini dicetak pada {{ $transaction->created_at->format('d F Y, H:i') }} WIB dan sah tanpa tanda tangan &amp; stempel.
            </div>
        </td>
    </tr>

</table>
</div>

<div class="no-print" style="text-align:center;padding:20px 0;background:#f1f5f9;position:fixed;bottom:0;width:100%;border-top:1px solid #ddd">
    <button onclick="window.print()" style="padding:10px 30px;font-size:16px;font-weight:bold;cursor:pointer;background:#000;color:#fff;border:none;border-radius:4px;margin-right:10px">&#128424; Cetak</button>
    <button onclick="window.close()" style="padding:10px 30px;font-size:16px;font-weight:bold;cursor:pointer;background:#fff;color:#000;border:2px solid #000;border-radius:4px">&#10005; Tutup</button>
</div>

</body>
</html>
