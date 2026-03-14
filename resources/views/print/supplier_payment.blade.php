<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran Hutang - {{ $payment->payment_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 0; background: #e2e8f0; }
        .container { max-width: 210mm; min-height: 148mm; /* A5 half paper size horizontally */ background: #fff; margin: 20px auto; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: relative; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #1e293b; padding-bottom: 15px; margin-bottom: 20px; }
        .header-left h1 { margin: 0; font-size: 24px; color: #1e293b; }
        .header-left p { margin: 5px 0 0; color: #64748b; font-size: 12px; }
        .header-right { text-align: right; }
        .header-right h2 { margin: 0; font-size: 20px; color: #3b82f6; text-transform: uppercase; letter-spacing: 1px; }
        .header-right p { margin: 5px 0 0; font-weight: bold; }
        .content { margin-top: 20px; line-height: 1.6; }
        .row { display: flex; margin-bottom: 10px; }
        .col-label { width: 150px; font-weight: bold; color: #475569; }
        .col-value { flex: 1; border-bottom: 1px dotted #cbd5e1; padding-bottom: 2px; }
        .amount-box { margin-top: 30px; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; display: inline-block; min-width: 250px; text-align: center; }
        .amount-box .label { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .amount-box .value { font-size: 24px; font-weight: bold; color: #1e293b; margin-top: 5px; }
        .footer { margin-top: 40px; display: flex; justify-content: flex-end; text-align: center; }
        .signature-box { width: 200px; }
        .date-line { margin-bottom: 50px; }
        .signature-line { border-top: 1px solid #333; padding-top: 5px; font-weight: bold;}
        @media print { body { background: #fff; } .container { margin: 0; padding: 15px; box-shadow: none; width: 100%; min-height: auto; border: 1px solid #000; } .no-print { display: none; } }
    </style>
</head>
<body onload="window.print();">
    <div class="container">
        <div class="no-print" style="position: absolute; top: -50px; right: 0;">
            <button onclick="window.print();" style="padding: 10px 20px; font-size: 14px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">🖨️ Cetak</button>
            <button onclick="window.close();" style="padding: 10px 20px; font-size: 14px; background: #e2e8f0; color: #333; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Tutup</button>
        </div>

        <div class="header">
            <div class="header-left">
                <h1>DodPOS</h1>
                <p>Toko Serba Ada<br>Jl. Contoh Alamat No. 123<br>Telp: 08123456789</p>
            </div>
            <div class="header-right">
                <h2>Bukti Pembayaran Hutang</h2>
                <p># {{ $payment->payment_number }}</p>
            </div>
        </div>

        <div class="content">
            <div class="row">
                <div class="col-label">Telah Dibayarkan Kepada</div>
                <div class="col-value"><strong>{{ $payment->supplierDebt->supplier->name ?? 'Supplier' }}</strong></div>
            </div>
            <div class="row">
                <div class="col-label">Tanggal Pembayaran</div>
                <div class="col-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d F Y') }}</div>
            </div>
            <div class="row">
                <div class="col-label">Metode Pembayaran</div>
                <div class="col-value">{{ ucfirst($payment->payment_method) }}</div>
            </div>
            <div class="row">
                <div class="col-label">Referensi Hutang</div>
                <div class="col-value">{{ $payment->supplierDebt->debt_number }} (PO: {{ $payment->supplierDebt->purchaseOrder->po_number ?? '-' }})</div>
            </div>
            <div class="row">
                <div class="col-label">Keterangan Tambahan</div>
                <div class="col-value">{{ $payment->notes ?: '-' }}</div>
            </div>

            <div class="amount-box">
                <div class="label">Total Pembayaran</div>
                <div class="value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="footer">
            <div class="signature-box">
                <div class="date-line">Bandung, {{ \Carbon\Carbon::parse($payment->payment_date)->format('d F Y') }}</div>
                <div class="signature-line">{{ $payment->createdBy->name ?? 'Petugas / Admin' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
