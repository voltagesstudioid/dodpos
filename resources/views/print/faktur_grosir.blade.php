<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Grosir #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 9px; color: #000; background: #fff; line-height: 1.2; }

        .page { width: 241mm; margin: 0 auto; padding: 3mm 5mm; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1.5px solid #000; padding-bottom: 2mm; margin-bottom: 2mm; }
        .brand { flex: 1; padding-right: 8mm; }
        .brand-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 0.5mm; letter-spacing: 0.5px; }
        .brand-contact { font-size: 9px; color: #000; }
        
        .invoice-meta { text-align: right; }
        .invoice-label { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .invoice-no { font-size: 18px; font-weight: bold; line-height: 1.1; letter-spacing: 0.5px; margin-bottom: 2px; }
        
        .pay-badge { 
            display: inline-block; padding: 2px 8px; font-size: 11px; font-weight: bold; 
            text-transform: uppercase; letter-spacing: 1px; border: 2px solid #000; 
            border-radius: 0; color: #000; 
        }

        .info-section { display: flex; gap: 8mm; margin-bottom: 2mm; }
        .info-col { flex: 1; }
        .info-table { width: 100%; font-size: 9px; }
        .info-table td { padding: 1px 0; vertical-align: top; }
        .info-table .il { width: 75px; font-weight: normal; color: #000; }
        .info-table .iv { font-weight: bold; color: #000; }

        .items-wrap { margin-bottom: 2mm; }
        .items { width: 100%; border-collapse: collapse; border: 1.5px solid #000; }
        .items th {
            background: #fff; color: #000; font-size: 8px; font-weight: bold; text-transform: uppercase; 
            padding: 2px 3px; text-align: left; border-bottom: 1.5px solid #000; border-right: 1px solid #000;
        }
        .items th.center { text-align: center; }
        .items th.right { text-align: right; }
        
        .items td { padding: 2px 3px; font-size: 9px; border-bottom: 1px solid #000; border-right: 1px solid #000; vertical-align: top; color: #000; }
        .items td.center { text-align: center; }
        .items td.right { text-align: right; font-variant-numeric: tabular-nums; }
        .items td.bold { font-weight: bold; }
        
        .item-name { font-weight: bold; display: block; font-size: 9px; color: #000; }
        .item-sku { font-size: 7px; font-weight: normal; color: #000; }
        
        .items tfoot td { padding: 3px 3px; font-size: 9px; font-weight: bold; border-top: 1.5px solid #000; border-right: none; }
        .items tfoot td.large { font-size: 10px; font-weight: bold; border-left: 1.5px solid #000; }

        .bottom-section { display: flex; gap: 8mm; margin-top: 1mm; }
        .bottom-left { flex: 1; }
        .bottom-right { width: 65mm; border: 1.5px solid #000; padding: 3px; }

        .summary-grid { width: 100%; font-size: 9px; }
        .summary-grid td { padding: 1.5px 2px; }
        .summary-grid td.sl { font-weight: normal; }
        .summary-grid td.sv { text-align: right; font-weight: bold; }
        
        .summary-total td { font-size: 11px; border-top: 1.5px solid #000; padding-top: 2px; font-weight: bold; }
        .summary-debt td { border-top: 1px dashed #000; padding-top: 2px; margin-top: 2px; }

        .sig-area { display: flex; justify-content: space-around; margin-top: 5mm; }
        .sig-block { text-align: center; width: 40%; }
        .sig-label { font-size: 8px; font-weight: normal; margin-bottom: 12mm; }
        .sig-line { border-bottom: 1px solid #000; margin-bottom: 1px; }
        .sig-name { font-size: 9px; font-weight: bold; }

        .extra-box { border: 1px solid #000; padding: 2px 4px; margin-bottom: 2mm; font-size: 8px; }
        .footer { margin-top: 3mm; text-align: left; font-size: 7px; font-style: italic; border-top: 1px dashed #000; padding-top: 2mm; }

        @media screen {
            body { background: #e2e8f0; }
            .page { box-shadow: 0 4px 20px rgba(0,0,0,0.1); margin: 20px auto; background: #fff; }
        }
        @media print {
            html, body { margin: 0; padding: 0; background: #fff; }
            .no-print { display: none !important; }
            .page { margin: 0; padding: 0; box-shadow: none; width: 241mm; }
            @page { size: 241mm 279mm; margin: 3mm 5mm; }
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

    $noFaktur    = $transaction->invoice_number;
    $tglFaktur   = $transaction->created_at->format('d/m/Y');
    $jamFaktur   = $transaction->created_at->format('H:i');
    $kasir       = $transaction->user?->name ?? '-';

    $pelanggan   = $transaction->customer?->name ?? 'Umum';
    $alamat      = $transaction->customer?->address ?? '-';
    $phone       = $transaction->customer?->phone ?? '-';
    $email       = $transaction->customer?->email ?? '';
    $hutangBerjalan = (float) ($transaction->customer?->current_debt ?? 0);

    $total       = $transaction->total_amount;
    $bayar       = $transaction->paid_amount;
    $kembali     = $transaction->change_amount;

    foreach ($transaction->additionalTransactions as $addTrans) {
        $total   += $addTrans->total_amount;
        $bayar   += $addTrans->paid_amount;
        $kembali += $addTrans->change_amount;
    }

    $metodeBayar = match($transaction->payment_method) {
        'cash'     => 'Tunai',
        'transfer' => 'Transfer',
        'qris'     => 'QRIS',
        'kredit'   => 'Kredit / Limit',
        default    => ucfirst($transaction->payment_method),
    };

    $isKredit    = $transaction->payment_method === 'kredit';
    $isTransfer  = $transaction->payment_method === 'transfer';
    $hutang      = $isKredit ? max(0, $total - $bayar) : 0;
    $hutangTransaksiIni = $hutang;
    $hutangSebelumnya = $isKredit ? max(0, $hutangBerjalan - $hutangTransaksiIni) : 0;

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
    $allDetails  = $transaction->details->merge(
        $transaction->additionalTransactions->flatMap->details
    );
    $itemCount   = $allDetails->count();
    $totalQty    = $allDetails->sum(function($d) { return $d->unit_qty ?? $d->quantity; });
@endphp

<div class="page">
    
    <div class="header">
        <div class="brand">
            <div class="brand-name">{{ $storeName }}</div>
            <div class="brand-contact">
                @if($storeAddr){{ $storeAddr }}<br>@endif
                @if($storePhone)Telp: {{ $storePhone }}@endif
            </div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-label">FAKTUR PENJUALAN</div>
            <div class="invoice-no">{{ $noFaktur }}</div>
            <div class="pay-badge">{{ $metodeBayar }}</div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-col">
            <table class="info-table">
                <tr><td class="il">Pelanggan</td><td class="iv">: {{ $pelanggan }}</td></tr>
                @if($phone !== '-')<tr><td class="il">Telepon</td><td class="iv">: {{ $phone }}</td></tr>@endif
                @if($alamat !== '-')<tr><td class="il">Alamat</td><td class="iv">: {{ $alamat }}</td></tr>@endif
            </table>
        </div>
        <div class="info-col">
            <table class="info-table">
                <tr><td class="il">Tanggal</td><td class="iv">: {{ $tglFaktur }} {{ $jamFaktur }} WIB</td></tr>
                <tr><td class="il">Kasir</td><td class="iv">: {{ $kasir }}</td></tr>
                @if($transaction->vehicle_id || $transaction->driver_name)
                <tr><td class="il">Pengiriman</td><td class="iv">: 
                    @if($transaction->vehicle){{ $transaction->vehicle->license_plate }}@endif
                    @if($transaction->driver_name) - {{ $transaction->driver_name }}@endif
                </td></tr>
                @endif
            </table>
        </div>
    </div>

    <div class="items-wrap">
        <table class="items">
            <thead>
                <tr>
                    <th class="center" style="width:4%">No</th>
                    <th style="width:38%">Deskripsi Barang</th>
                    <th style="width:15%">Asal</th>
                    <th class="center" style="width:10%">Qty</th>
                    <th class="right" style="width:13%">Harga</th>
                    <th class="right" style="width:20%; border-right:none;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allDetails as $i => $detail)
                @php
                    $displayQty   = ($detail->unit_qty !== null && $detail->unit_qty > 0) ? $detail->unit_qty : $detail->quantity;
                    $displayUnit  = $detail->unit_name ?? 'pcs';
                    $displayPrice = ($displayQty > 0) ? ($detail->subtotal / $displayQty) : $detail->price;
                    $isAdditional = $detail->transaction_id !== $transaction->id;
                @endphp
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>
                        <span class="item-name">{{ $detail->product?->name ?? 'Barang tidak tersedia' }}@if($isAdditional) <span style="font-size:7px;">(Tmbh)</span>@endif</span>
                        @if($detail->product?->sku)<span class="item-sku">{{ $detail->product->sku }}</span>@endif
                    </td>
                    <td style="font-size:8px;">{{ $detail->warehouse?->name ?? '-' }}</td>
                    <td class="center">{{ $displayQty }} {{ $displayUnit }}</td>
                    <td class="right">{{ number_format($displayPrice, 0, ',', '.') }}</td>
                    <td class="right bold" style="border-right:none;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="right">Total:</td>
                    <td class="center">{{ $totalQty }}</td>
                    <td class="right">Tagihan:</td>
                    <td class="right large">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="bottom-section">
        <div class="bottom-left">
            @if($isTransfer && ($bankName || $bankAccNo))
            <div class="extra-box">
                Transfer: <b>{{ $bankName }} - {{ $bankAccNo }}</b> @if($bankHolder) a.n. {{ $bankHolder }}@endif
            </div>
            @endif

            @if($transaction->additional_notes)
            <div class="extra-box">
                <b>Catatan:</b> {{ $transaction->additional_notes }}
            </div>
            @endif
            
            <div class="sig-area">
                <div class="sig-block">
                    <div class="sig-label">Penerima</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">( {{ $pelanggan }} )</div>
                </div>
                <div class="sig-block">
                    <div class="sig-label">Hormat Kami</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">( {{ $storeName }} )</div>
                </div>
            </div>
        </div>
        
        <div class="bottom-right">
            <table class="summary-grid">
                <tr class="summary-total">
                    <td class="sl">TOTAL</td>
                    <td class="sv">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
                
                @if($isKredit)
                    <tr>
                        <td class="sl">DP Dibayar</td>
                        <td class="sv">Rp {{ number_format($bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="summary-debt">
                        <td class="sl">Hutang Baru</td>
                        <td class="sv">Rp {{ number_format($hutangTransaksiIni, 0, ',', '.') }}</td>
                    </tr>
                    @if($hutangSebelumnya > 0)
                    <tr>
                        <td class="sl">Hutang Lama</td>
                        <td class="sv">Rp {{ number_format($hutangSebelumnya, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="summary-debt">
                        <td class="sl">Sisa Hutang</td>
                        <td class="sv">Rp {{ number_format($hutangBerjalan, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                @else
                    <tr>
                        <td class="sl">Dibayar</td>
                        <td class="sv">Rp {{ number_format($bayar, 0, ',', '.') }}</td>
                    </tr>
                    @if($kembali > 0)
                    <tr>
                        <td class="sl">Kembali</td>
                        <td class="sv">Rp {{ number_format($kembali, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                @endif
                
                @if($transaction->payment_reference)
                <tr>
                    <td class="sl" style="font-size:7px; padding-top:1mm;">Ref:</td>
                    <td class="sv" style="font-size:7px; padding-top:1mm;">{{ $transaction->payment_reference }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="footer">
        Barang yang sudah dibeli tidak dapat ditukar/dikembalikan. Dicetak {{ $transaction->created_at->format('d/m/Y H:i') }}.
    </div>

</div>

<div class="no-print" style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;gap:10px;padding:15px;background:#f1f5f9;border-top:1px solid #d1d5db;box-shadow:0 -4px 10px rgba(0,0,0,0.05)">
    <button onclick="window.print()" style="padding:10px 32px;font-size:14px;font-weight:bold;cursor:pointer;background:#000;color:#fff;border:none;border-radius:6px;">&#128424;&nbsp; CETAK FAKTUR</button>
    <button onclick="window.close()" style="padding:10px 32px;font-size:14px;font-weight:bold;cursor:pointer;background:#fff;color:#000;border:2px solid #000;border-radius:6px;">&#10005;&nbsp; TUTUP</button>
</div>

</body>
</html>
