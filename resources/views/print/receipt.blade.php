<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->id }}</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 2mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
            width: 58mm; /* Sesuaikan dengan ukuran kertas printer thermal yang umum (58mm) */
        }
        .container {
            padding: 0;
            max-width: 58mm;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .info {
            margin-bottom: 10px;
            font-size: 10px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            vertical-align: top;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items th, .items td {
            text-align: left;
            padding: 2px 0;
        }
        .items .right {
            text-align: right;
        }
        .item-name {
            display: block;
            margin-bottom: 2px;
        }
        .summary {
            width: 100%;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-bottom: 15px;
        }
        .summary th, .summary td {
            text-align: left;
            padding: 2px 0;
        }
        .summary .right {
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-bottom: 10px;
        }
        .payment-method {
            text-transform: uppercase;
        }

        /* Hilangkan elemen yang tidak perlu dicetak saat dialog print terbuka */
        @media print {
            body { 
                margin: 0; 
                padding: 0;
            }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print();">
    @php
        $storeName = $storeSetting->store_name ?? 'DODPOS';
        $storeAddr = $storeSetting->address ?? '';
        $storePhone = $storeSetting->phone ?? '';
        $storeNote = $storeSetting->receipt_footer_note ?? null;

        $noTrx = 'TRX-'.str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
        $metodeBayar = match($transaction->payment_method) {
            'cash' => 'TUNAI',
            'tunai' => 'TUNAI',
            'transfer' => 'TRANSFER',
            'qris' => 'QRIS',
            'kredit' => 'KREDIT',
            default => strtoupper((string) $transaction->payment_method),
        };
    @endphp
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>{{ $storeName }}</h1>
            @if($storeAddr)<p>{{ $storeAddr }}</p>@endif
            @if($storePhone)<p>Telp: {{ $storePhone }}</p>@endif
        </div>

        <!-- INFO TRANSAKSI -->
        <div class="info">
            <table>
                <tr>
                    <td>Tgl</td>
                    <td>:</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>:</td>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>No. Trx</td>
                    <td>:</td>
                    <td>{{ $noTrx }}</td>
                </tr>
                @if($transaction->customer)
                <tr>
                    <td>Pelanggan</td>
                    <td>:</td>
                    <td>{{ $transaction->customer->name }}</td>
                </tr>
                @endif
                @if($transaction->payment_reference)
                <tr>
                    <td>Ref</td>
                    <td>:</td>
                    <td>{{ $transaction->payment_reference }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- ITEMS -->
        <table class="items">
            <tbody>
                @foreach($transaction->details as $detail)
                <tr>
                    <td colspan="2">
                        <span class="item-name">{{ $detail->product->name ?? 'Produk Dihapus' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY -->
        <table class="summary">
            <tr>
                <th>Total</th>
                <th class="right">{{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <td>Bayar (<span class="payment-method">{{ $metodeBayar }}</span>)</td>
                <td class="right">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            @if($transaction->payment_method === 'kredit')
            <tr>
                <td>Sisa Hutang</td>
                <td class="right">{{ number_format($transaction->total_amount - $transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            @else
            <tr>
                <td>Kembali</td>
                <td class="right">{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>

        <!-- FOOTER -->
        <div class="footer">
            <p>Terima Kasih</p>
            @if($storeNote)
                <p>{{ $storeNote }}</p>
            @else
                <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
            @endif
        </div>
        
        <div class="no-print" style="text-align:center; padding-top: 20px;">
            <button onclick="window.print();" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">Cetak Ulang</button>
            <button onclick="window.close();" style="padding: 10px 20px; font-size: 14px; cursor: pointer; margin-left: 10px;">Tutup</button>
        </div>
    </div>
</body>
</html>
