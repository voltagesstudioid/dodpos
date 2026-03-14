<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pembelian - {{ $order->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
            background: #e2e8f0;
        }
        .container {
            max-width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            background: #fff;
            margin: 20px auto;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-left h1 {
            margin: 0;
            font-size: 28px;
            color: #1e293b;
        }
        .header-left p {
            margin: 5px 0 0;
            color: #64748b;
        }
        .header-right {
            text-align: right;
        }
        .header-right h2 {
            margin: 0;
            font-size: 24px;
            color: #3b82f6;
            text-transform: uppercase;
        }
        .header-right p {
            margin: 5px 0 0;
            font-weight: bold;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        .info-box {
            width: 48%;
            padding: 10px;
            background: #f8fafc;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #475569;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 0;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table-items th {
            background: #1e293b;
            color: #fff;
            text-align: left;
            padding: 10px;
            font-size: 13px;
        }
        .table-items td {
            border-bottom: 1px solid #e2e8f0;
            padding: 10px;
        }
        .table-items .right {
            text-align: right;
        }
        .table-items .center {
            text-align: center;
        }
        .summary-box {
            width: 40%;
            float: right;
        }
        .summary-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-box th, .summary-box td {
            padding: 8px;
            text-align: right;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-box .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 2px solid #1e293b;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-box {
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }

        @media print {
            body { background: #fff; }
            .container { 
                margin: 0; 
                padding: 15px; 
                box-shadow: none; 
                width: 100%;
                min-height: auto;
            }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="container">
        <!-- ACTION BUTTONS -->
        <div class="no-print" style="position: absolute; top: -50px; right: 0;">
            <button onclick="window.print();" style="padding: 10px 20px; font-size: 14px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">🖨️ Cetak</button>
            <button onclick="window.close();" style="padding: 10px 20px; font-size: 14px; background: #e2e8f0; color: #333; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Tutup</button>
        </div>

        <div class="header">
            <div class="header-left">
                <h1>DodPOS</h1>
                <p>Toko Serba Ada<br>
                Jl. Contoh Alamat No. 123<br>
                Telp: 08123456789</p>
            </div>
            <div class="header-right">
                <h2>Purchase Order</h2>
                <p># {{ $order->po_number }}</p>
                <div style="margin-top: 10px; font-size: 12px;">
                    <span style="display:inline-block; padding: 3px 8px; border-radius: 12px; font-weight: bold; 
                        {{ $order->status === 'completed' ? 'background: #dcfce7; color: #166534;' : ($order->status === 'pending' ? 'background: #fef9c3; color: #854d0e;' : 'background: #f1f5f9; color: #475569;') }}">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-box">
                <h3>Informasi Pesanan</h3>
                <table>
                    <tr><td width="100">Tanggal</td><td>: {{ \Carbon\Carbon::parse($order->order_date)->format('d F Y') }}</td></tr>
                    <tr><td>Dibuat Oleh</td><td>: {{ $order->user->name ?? 'Admin' }}</td></tr>
                    <tr><td>Status Bayar</td><td>: {{ ucfirst($order->payment_status) }}</td></tr>
                    <tr><td>Jatuh Tempo</td><td>: {{ $order->due_date ? \Carbon\Carbon::parse($order->due_date)->format('d F Y') : '-' }}</td></tr>
                </table>
            </div>
            <div class="info-box">
                <h3>Kepada Pemasok (Supplier)</h3>
                <table>
                    <tr><td width="100">Nama</td><td>: <strong>{{ $order->supplier->name ?? '-' }}</strong></td></tr>
                    <tr><td>Kontak</td><td>: {{ $order->supplier->phone ?? '-' }}</td></tr>
                    <tr><td>Alamat</td><td>: {{ $order->supplier->address ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <table class="table-items">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Deskripsi Barang</th>
                    <th width="10%" class="center">Qty</th>
                    <th width="20%" class="right">Harga Satuan</th>
                    <th width="20%" class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product->name ?? 'Produk Dihapus' }}</strong>
                        <br><span style="font-size:12px; color:#64748b;">SKU: {{ $item->product->sku ?? '-' }}</span>
                    </td>
                    <td class="center">{{ $item->qty_ordered }} {{ $item->unit->abbreviation ?? $item->product->unit->abbreviation ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="clearfix">
            <div style="float:left; width:50%; font-size: 13px; color: #64748b;">
                <strong>Catatan:</strong><br>
                {{ $order->notes ?: 'Tidak ada catatan tambahan.' }}
            </div>
            <div class="summary-box">
                <table>
                    <tr class="grand-total">
                        <th>Total Tagihan</th>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        // Menghitung jumlah yang sudah dibayar, jika ada dari SupplierDebt. Sederhanakan:
                        $debt = \App\Models\SupplierDebt::where('purchase_order_id', $order->id)->first();
                        $paid = $debt ? $debt->paid_amount : ($order->payment_status == 'paid' ? $order->total_amount : 0);
                        $sisa = $order->total_amount - $paid;
                    @endphp
                    <tr>
                        <th style="font-weight:normal; color:#64748b;">Sudah Dibayar</th>
                        <td style="color:#16a34a;">Rp {{ number_format($paid, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th style="font-weight:normal; color:#64748b;">Sisa Tagihan / Hutang</th>
                        <td style="color:#ef4444;">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            <div class="signature-box">
                <p>Penerima / Pembeli</p>
                <div class="signature-line">( DodPOS Admin )</div>
            </div>
            <div class="signature-box">
                <p>Hormat Kami (Supplier)</p>
                <div class="signature-line">( {{ $order->supplier->name ?? '....................' }} )</div>
            </div>
        </div>
    </div>
</body>
</html>
