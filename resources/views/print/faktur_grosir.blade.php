<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Grosir #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Segoe UI',Arial,Helvetica,sans-serif;font-size:10.5px;color:#1a1a1a;background:#fff;line-height:1.4}

        /* ── PAGE ── */
        .page{width:210mm;margin:0 auto;padding:8mm 10mm}

        /* ── HEADER ── */
        .header{display:flex;justify-content:space-between;align-items:flex-start;padding-bottom:6mm;border-bottom:2.5px solid #1a1a1a}
        .brand{flex:1}
        .brand-name{font-size:20px;font-weight:900;letter-spacing:0.5px;color:#1a1a1a}
        .brand-contact{font-size:9px;color:#555;margin-top:2px;line-height:1.5}
        .invoice-meta{text-align:right}
        .invoice-title{font-size:14px;font-weight:900;letter-spacing:3px;color:#1a1a1a;text-transform:uppercase;margin-bottom:4px}
        .meta-grid{font-size:9.5px;text-align:right}
        .meta-grid div{margin-bottom:1px}
        .meta-label{color:#666;font-weight:500}
        .meta-val{font-weight:700;margin-left:4px}

        /* ── PAYMENT BADGE ── */
        .pay-badge{display:inline-block;padding:2px 10px;font-size:8.5px;font-weight:800;text-transform:uppercase;letter-spacing:1px;border-radius:3px;margin-top:4px}
        .pay-badge.cash{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
        .pay-badge.transfer{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
        .pay-badge.qris{background:#ede9fe;color:#5b21b6;border:1px solid #c4b5fd}
        .pay-badge.kredit{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}

        /* ── INFO SECTION ── */
        .info-section{display:flex;gap:6mm;margin:5mm 0}
        .info-card{flex:1;border:1px solid #d1d5db;border-radius:4px;padding:3.5mm 4mm}
        .info-card-title{font-size:8px;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:#6b7280;margin-bottom:2.5mm;padding-bottom:1.5mm;border-bottom:1px solid #e5e7eb}
        .info-row{display:flex;font-size:9.5px;margin-bottom:1.5px}
        .info-row .il{width:70px;color:#6b7280;font-weight:500;flex-shrink:0}
        .info-row .iv{font-weight:600;color:#1a1a1a;flex:1}
        .status-pill{display:inline-block;padding:1px 8px;font-size:8px;font-weight:700;border-radius:10px;background:#f3f4f6;color:#374151;border:1px solid #d1d5db}

        /* ── ITEMS TABLE ── */
        .items-wrap{margin:4mm 0}
        .items{width:100%;border-collapse:collapse}
        .items thead th{
            background:#f9fafb;font-size:8px;font-weight:800;text-transform:uppercase;letter-spacing:0.5px;
            padding:3mm 3mm;text-align:left;color:#374151;
            border-top:2px solid #1a1a1a;border-bottom:2px solid #1a1a1a
        }
        .items thead th.center{text-align:center}
        .items thead th.right{text-align:right}
        .items tbody td{padding:2.5mm 3mm;font-size:10px;border-bottom:1px solid #e5e7eb;vertical-align:top}
        .items tbody td.center{text-align:center}
        .items tbody td.right{text-align:right;font-variant-numeric:tabular-nums}
        .items tbody td.bold{font-weight:700}
        .item-name{font-weight:700;color:#1a1a1a}
        .item-sku{font-size:8px;color:#9ca3af;display:block;margin-top:1px}
        .items tfoot td{padding:2mm 3mm;font-size:9px;font-weight:700;border-top:2px solid #1a1a1a;background:#f9fafb}

        /* ── BOTTOM SECTION ── */
        .bottom-section{display:flex;gap:6mm;margin-top:5mm}
        .bottom-left{flex:1}
        .bottom-right{width:45%}

        /* ── SUMMARY ── */
        .summary{border:1.5px solid #1a1a1a;border-radius:4px;padding:4mm}
        .sum-line{display:flex;justify-content:space-between;padding:1.5mm 0;font-size:10px}
        .sum-line .sl{color:#4b5563;font-weight:500}
        .sum-line .sv{font-weight:700;font-variant-numeric:tabular-nums}
        .sum-divider{border-top:1.5px solid #1a1a1a;margin:2mm 0;padding-top:2mm}
        .sum-total{font-size:14px}
        .sum-total .sl{font-weight:800;color:#1a1a1a}
        .sum-total .sv{font-weight:900;color:#1a1a1a}
        .sum-paid{background:#ecfdf5;margin:0 -4mm;padding:2mm 4mm;border-radius:0 0 3px 3px}
        .sum-paid .sl{color:#065f46;font-weight:700}
        .sum-paid .sv{color:#065f46;font-weight:800;font-size:11px}
        .sum-change{margin:0 -4mm;padding:2mm 4mm;background:#fef3c7;border-radius:0 0 3px 3px}
        .sum-change .sl{color:#92400e;font-weight:700}
        .sum-change .sv{color:#92400e;font-weight:800;font-size:11px}

        /* ── DEBT SECTION ── */
        .sum-debt{margin:0 -4mm;padding:3mm 4mm;background:#fef2f2;border-top:1.5px dashed #f87171;border-radius:0 0 3px 3px}
        .debt-title{font-size:8px;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:#991b1b;margin-bottom:2mm}
        .debt-line{display:flex;justify-content:space-between;padding:1mm 0;font-size:9.5px}
        .debt-line .dl{color:#7f1d1d;font-weight:500}
        .debt-line .dv{color:#7f1d1d;font-weight:700;font-variant-numeric:tabular-nums}
        .debt-total{display:flex;justify-content:space-between;padding:2mm 0 0;margin-top:1.5mm;border-top:2px solid #dc2626;font-size:12px}
        .debt-total .dl{color:#991b1b;font-weight:800}
        .debt-total .dv{color:#991b1b;font-weight:900}

        /* ── SIGNATURES ── */
        .sig-area{display:flex;gap:8mm;margin-top:6mm}
        .sig-block{flex:1;text-align:center}
        .sig-label{font-size:9px;font-weight:600;color:#6b7280;margin-bottom:20mm}
        .sig-line{border-bottom:1px solid #1a1a1a;margin-bottom:2mm}
        .sig-name{font-size:9px;font-weight:700;color:#1a1a1a}

        /* ── BANK INFO ── */
        .bank-bar{background:#f0f9ff;border:1px solid #bae6fd;border-radius:4px;padding:2.5mm 4mm;margin-top:4mm;font-size:9px}
        .bank-bar b{color:#0c4a6e}

        /* ── NOTES ── */
        .notes-bar{background:#fffbeb;border:1px solid #fde68a;border-radius:4px;padding:2.5mm 4mm;margin-top:3mm;font-size:9px;color:#92400e}

        /* ── FOOTER ── */
        .footer{margin-top:6mm;padding-top:3mm;border-top:1px solid #e5e7eb;font-size:8px;color:#9ca3af;text-align:center;line-height:1.6}

        /* ── PRINT ── */
        @media screen{
            body{background:#e2e8f0}
            .page{box-shadow:0 2px 20px rgba(0,0,0,.08);margin:15px auto;background:#fff}
        }
        @media print{
            html,body{margin:0;padding:0;background:#fff}
            .no-print{display:none!important}
            .page{margin:0;padding:4mm 7mm 5mm 7mm;box-shadow:none;width:100%}
            .items thead{background:#f9fafb !important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
            .items tfoot{background:#f9fafb !important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
            .pay-badge,.sum-paid,.sum-change,.sum-debt,.bank-bar,.notes-bar{
                -webkit-print-color-adjust:exact;print-color-adjust:exact
            }
            .page,.items tbody tr{page-break-inside:avoid}
            thead{display:table-header-group}
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

    // Include additional transactions in totals
    foreach ($transaction->additionalTransactions as $addTrans) {
        $total   += $addTrans->total_amount;
        $bayar   += $addTrans->paid_amount;
        $kembali += $addTrans->change_amount;
    }

    $metodeBayar = match($transaction->payment_method) {
        'cash'     => 'Tunai',
        'transfer' => 'Transfer',
        'qris'     => 'QRIS',
        'kredit'   => 'Limit',
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
    $packedBy    = $transaction->packedBy?->name ?? '';
    $checkedBy   = $transaction->checkedBy?->name ?? '';
    $deliveredBy = $transaction->deliveredBy?->name ?? '';
    // Count items including additional transactions
    $allDetails  = $transaction->details->merge(
        $transaction->additionalTransactions->flatMap->details
    );
    $itemCount   = $allDetails->count();
    $totalQty    = $allDetails->sum(function($d) { return $d->unit_qty ?? $d->quantity; });
@endphp

<div class="page">

    {{-- ═══════════════ HEADER ═══════════════ --}}
    <div class="header">
        <div class="brand">
            <div class="brand-name">{{ $storeName }}</div>
            <div class="brand-contact">
                @if($storeAddr){{ $storeAddr }}@endif
                @if($storeAddr && ($storePhone || $storeEmail))<br>@endif
                @if($storePhone)Telp: {{ $storePhone }}@endif
                @if($storePhone && $storeEmail) &nbsp;|&nbsp; @endif
                @if($storeEmail){{ $storeEmail }}@endif
            </div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-title">Faktur Penjualan</div>
            <div class="meta-grid">
                <div><span class="meta-label">No. Faktur</span><span class="meta-val">{{ $noFaktur }}</span></div>
                <div><span class="meta-label">Tanggal</span><span class="meta-val">{{ $tglFaktur }} {{ $jamFaktur }}</span></div>
                <div><span class="meta-label">Kasir</span><span class="meta-val">{{ $kasir }}</span></div>
            </div>
            <div class="pay-badge {{ $paymentClass }}">{{ $metodeBayar }}</div>
        </div>
    </div>

    {{-- ═══════════════ CUSTOMER + DELIVERY INFO ═══════════════ --}}
    <div class="info-section">
        <div class="info-card">
            <div class="info-card-title">Pelanggan</div>
            <div class="info-row"><span class="il">Nama</span><span class="iv"><b>{{ $pelanggan }}</b></span></div>
            @if($phone !== '-')<div class="info-row"><span class="il">Telepon</span><span class="iv">{{ $phone }}</span></div>@endif
            @if($email)<div class="info-row"><span class="il">Email</span><span class="iv">{{ $email }}</span></div>@endif
            @if($alamat !== '-')<div class="info-row"><span class="il">Alamat</span><span class="iv">{{ $alamat }}</span></div>@endif
        </div>
        <div class="info-card">
            <div class="info-card-title">Pengiriman</div>
            @if($warehouse)<div class="info-row"><span class="il">Gudang</span><span class="iv">{{ $warehouse }}</span></div>@endif
            <div class="info-row"><span class="il">Status</span><span class="iv"><span class="status-pill">{{ $deliveryStatusLabel }}</span></span></div>
            @if($transaction->vehicle_id || $transaction->driver_name)
            <div class="info-row"><span class="il">Kendaraan</span><span class="iv">
                @if($transaction->vehicle){{ $transaction->vehicle->license_plate }}@if($transaction->vehicle->type) ({{ $transaction->vehicle->type }}) @endif @endif
                @if($transaction->driver_name) &mdash; {{ $transaction->driver_name }}@endif
            </span></div>
            @endif
            @if($packedBy)<div class="info-row"><span class="il">Dikemas</span><span class="iv">{{ $packedBy }}</span></div>@endif
            @if($checkedBy)<div class="info-row"><span class="il">Diperiksa</span><span class="iv">{{ $checkedBy }}</span></div>@endif
            @if($deliveredBy)<div class="info-row"><span class="il">Dikirim</span><span class="iv">{{ $deliveredBy }}</span></div>@endif
            <div class="info-row"><span class="il">Cetak ke-</span><span class="iv">{{ $transaction->print_count }}</span></div>
        </div>
    </div>

    {{-- ═══════════════ ITEMS TABLE ═══════════════ --}}
    <div class="items-wrap">
        <table class="items">
            <thead>
                <tr>
                    <th class="center" style="width:5%">No</th>
                    <th style="width:35%">Deskripsi Barang</th>
                    <th class="center" style="width:12%">Qty</th>
                    <th class="right" style="width:20%">Harga Satuan</th>
                    <th class="right" style="width:20%">Subtotal</th>
                    @if($warehouse)<th class="center" style="width:8%">Gudang</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($allDetails as $i => $detail)
                @php
                    $displayQty   = ($detail->unit_qty !== null && $detail->unit_qty > 0) ? $detail->unit_qty : $detail->quantity;
                    $displayUnit  = $detail->unit_name ?? 'pcs';
                    $displayPrice = ($displayQty > 0) ? ($detail->subtotal / $displayQty) : $detail->price;
                    $detailWh     = $detail->warehouse?->name ?? '';
                    $isAdditional = $detail->transaction_id !== $transaction->id;
                @endphp
                <tr @if($isAdditional) style="background:#fefce8;" @endif>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>
                        <span class="item-name">{{ $detail->product?->name ?? 'Barang tidak tersedia' }}</span>
                        @if($detail->product?->sku)<span class="item-sku">{{ $detail->product->sku }}</span>@endif
                        @if($isAdditional)<span style="font-size:9px;background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:3px;margin-left:4px;">+ Tambahan</span>@endif
                    </td>
                    <td class="center">{{ $displayQty }} {{ $displayUnit }}</td>
                    <td class="right">{{ number_format($displayPrice, 0, ',', '.') }}</td>
                    <td class="right bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    @if($warehouse)<td class="center">{{ Str::limit($detailWh, 10) }}</td>@endif
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="right">Total ({{ $itemCount }} jenis barang)</td>
                    <td class="center">{{ $totalQty }}</td>
                    <td></td>
                    <td class="right" style="font-size:11px">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    @if($warehouse)<td></td>@endif
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- ═══════════════ PAYMENT SUMMARY ═══════════════ --}}
    <div class="bottom-section">
        <div class="bottom-left">
            {{-- Signatures --}}
            <div class="sig-area">
                <div class="sig-block">
                    <div class="sig-label">Penerima,</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">( {{ $pelanggan }} )</div>
                </div>
                <div class="sig-block">
                    <div class="sig-label">Hormat Kami,</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $kasir }}</div>
                </div>
            </div>
        </div>
        <div class="bottom-right">
            <div class="summary">
                <div class="sum-line"><span class="sl">Total Tagihan</span><span class="sv">Rp {{ number_format($total, 0, ',', '.') }}</span></div>

                @if($isKredit && $hutang > 0)
                    {{-- Limit dengan hutang --}}
                    <div class="sum-debt">
                        <div class="debt-title">Rincian Hutang</div>
                        <div class="debt-line"><span class="dl">Hutang Sebelumnya</span><span class="dv">Rp {{ number_format($hutangSebelumnya, 0, ',', '.') }}</span></div>
                        <div class="debt-line"><span class="dl">Hutang Transaksi Ini</span><span class="dv">Rp {{ number_format($hutangTransaksiIni, 0, ',', '.') }}</span></div>
                        <div class="debt-total"><span class="dl">Total Hutang</span><span class="dv">Rp {{ number_format($hutangBerjalan, 0, ',', '.') }}</span></div>
                    </div>
                    <div class="sum-paid" style="margin-top:0">
                        <div class="sum-line" style="padding:0"><span class="sl">Uang Muka (DP)</span><span class="sv">Rp {{ number_format($bayar, 0, ',', '.') }}</span></div>
                    </div>
                @elseif($isKredit && $hutang == 0)
                    {{-- Limit lunas --}}
                    <div class="sum-divider"></div>
                    <div class="sum-paid">
                        <div class="sum-line" style="padding:0"><span class="sl">Dibayar (Lunas)</span><span class="sv">Rp {{ number_format($bayar, 0, ',', '.') }}</span></div>
                    </div>
                @else
                    {{-- Non-kredit --}}
                    <div class="sum-divider"></div>
                    <div class="sum-paid">
                        <div class="sum-line" style="padding:0"><span class="sl">Dibayar ({{ $metodeBayar }})</span><span class="sv">Rp {{ number_format($bayar, 0, ',', '.') }}</span></div>
                    </div>
                    @if($kembali > 0)
                    <div class="sum-change">
                        <div class="sum-line" style="padding:0"><span class="sl">Kembalian</span><span class="sv">Rp {{ number_format($kembali, 0, ',', '.') }}</span></div>
                    </div>
                    @endif
                @endif

                @if($transaction->payment_reference)
                <div class="sum-line" style="margin-top:2mm;font-size:8.5px;border-top:1px solid #e5e7eb;padding-top:2mm"><span class="sl">Ref. Pembayaran</span><span class="sv">{{ $transaction->payment_reference }}</span></div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════ BANK INFO (transfer) ═══════════════ --}}
    @if($isTransfer && ($bankName || $bankAccNo))
    <div class="bank-bar">
        <b>Rekening Transfer:</b>
        @if($bankName) {{ $bankName }}@endif
        @if($bankAccNo) &mdash; {{ $bankAccNo }}@endif
        @if($bankHolder) &mdash; a.n. {{ $bankHolder }}@endif
    </div>
    @endif

    {{-- ═══════════════ NOTES ═══════════════ --}}
    @if($transaction->additional_notes)
    <div class="notes-bar"><b>Catatan:</b> {{ $transaction->additional_notes }}</div>
    @endif

    {{-- ═══════════════ FOOTER ═══════════════ --}}
    <div class="footer">
        Barang yang sudah dibeli dan diterima dengan baik tidak dapat ditukar/dikembalikan.<br>
        Faktur ini dicetak pada {{ $transaction->created_at->format('d F Y, H:i') }} WIB dan sah tanpa tanda tangan dan stempel.
    </div>

</div>

{{-- ── SCREEN CONTROLS ── --}}
<div class="no-print" style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;gap:10px;padding:15px;background:#f1f5f9;border-top:1px solid #d1d5db">
    <button onclick="window.print()" style="padding:10px 32px;font-size:14px;font-weight:800;cursor:pointer;background:#1a1a1a;color:#fff;border:none;border-radius:6px;letter-spacing:0.5px">&#128424;&nbsp; Cetak Faktur</button>
    <button onclick="window.close()" style="padding:10px 32px;font-size:14px;font-weight:700;cursor:pointer;background:#fff;color:#1a1a1a;border:2px solid #1a1a1a;border-radius:6px">&#10005;&nbsp; Tutup</button>
</div>

</body>
</html>
