<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Order {{ $salesOrder->so_number }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Segoe UI',Arial,Helvetica,sans-serif;font-size:10.5px;color:#1a1a1a;background:#fff;line-height:1.4}

        .page{width:210mm;margin:0 auto;padding:8mm 10mm}

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

        .status-pill{display:inline-block;padding:2px 8px;font-size:8px;font-weight:700;border-radius:10px;margin-top:3px}
        .status-pill.draft{background:#fef3c7;color:#92400e;border:1px solid #fcd34d}
        .status-pill.confirmed{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
        .status-pill.processing{background:#e0e7ff;color:#3730a3;border:1px solid #a5b4fc}
        .status-pill.completed{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
        .status-pill.cancelled{background:#ffe4e6;color:#9f1239;border:1px solid #fda4af}

        .info-section{display:flex;gap:6mm;margin:5mm 0}
        .info-card{flex:1;border:1px solid #d1d5db;border-radius:4px;padding:3.5mm 4mm}
        .info-card-title{font-size:8px;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:#6b7280;margin-bottom:2.5mm;padding-bottom:1.5mm;border-bottom:1px solid #e5e7eb}
        .info-row{display:flex;font-size:9.5px;margin-bottom:1.5px}
        .info-row .il{width:70px;color:#6b7280;font-weight:500;flex-shrink:0}
        .info-row .iv{font-weight:600;color:#1a1a1a;flex:1}

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

        .bottom-section{display:flex;gap:6mm;margin-top:5mm}
        .bottom-left{flex:1}
        .bottom-right{width:45%}

        .summary{border:1.5px solid #1a1a1a;border-radius:4px;padding:4mm}
        .sum-line{display:flex;justify-content:space-between;padding:1.5mm 0;font-size:10px}
        .sum-line .sl{color:#4b5563;font-weight:500}
        .sum-line .sv{font-weight:700;font-variant-numeric:tabular-nums}
        .sum-divider{border-top:1.5px solid #1a1a1a;margin:2mm 0;padding-top:2mm}
        .sum-total{font-size:14px}
        .sum-total .sl{font-weight:800;color:#1a1a1a}
        .sum-total .sv{font-weight:900;color:#1a1a1a}

        .sig-area{display:flex;gap:8mm;margin-top:6mm}
        .sig-block{flex:1;text-align:center}
        .sig-label{font-size:9px;font-weight:600;color:#6b7280;margin-bottom:20mm}
        .sig-line{border-bottom:1px solid #1a1a1a;margin-bottom:2mm}
        .sig-name{font-size:9px;font-weight:700;color:#1a1a1a}

        .notes-bar{background:#fffbeb;border:1px solid #fde68a;border-radius:4px;padding:2.5mm 4mm;margin-top:3mm;font-size:9px;color:#92400e}

        .footer{margin-top:6mm;padding-top:3mm;border-top:1px solid #e5e7eb;font-size:8px;color:#9ca3af;text-align:center;line-height:1.6}

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
            .status-pill,.notes-bar{
                -webkit-print-color-adjust:exact;print-color-adjust:exact
            }
            .page,.items tbody tr{page-break-inside:avoid}
            thead{display:table-header-group}
            @page{size:A4 portrait;margin:0}
        }
    </style>
</head>
<body @if(! request()->boolean('preview')) onload="window.print();" @endif>

@php
    $storeName   = $storeSetting->store_name    ?? 'DODPOS';
    $storeAddr   = $storeSetting->store_address ?? '';
    $storePhone  = $storeSetting->store_phone   ?? '';
    $storeEmail  = $storeSetting->store_email   ?? '';

    $soNumber    = $salesOrder->so_number;
    $soDate      = \Carbon\Carbon::parse($salesOrder->order_date)->format('d/m/Y');
    $soDelivery  = $salesOrder->delivery_date ? \Carbon\Carbon::parse($salesOrder->delivery_date)->format('d/m/Y') : '-';
    $soCreated   = $salesOrder->created_at->format('d/m/Y H:i');
    $soStatus    = $salesOrder->status;
    $soStatusLabel = ucfirst($soStatus);
    $dibuatOleh  = $salesOrder->user?->name ?? '-';

    $pelanggan   = $salesOrder->customer?->name ?? '-';
    $alamat      = $salesOrder->customer?->address ?? '-';
    $phone       = $salesOrder->customer?->phone ?? '-';

    $totalQty    = $salesOrder->items->sum('quantity');
    $itemCount   = $salesOrder->items->count();
    $grandTotal  = $salesOrder->total_amount;
    $orderType   = $salesOrder->order_type === 'grosir' ? 'Grosir' : 'Eceran';
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
            <div class="invoice-title">Sales Order</div>
            <div class="meta-grid">
                <div><span class="meta-label">No. SO</span><span class="meta-val">{{ $soNumber }}</span></div>
                <div><span class="meta-label">Tanggal</span><span class="meta-val">{{ $soDate }}</span></div>
                <div><span class="meta-label">Dibuat</span><span class="meta-val">{{ $dibuatOleh }}</span></div>
                <div><span class="meta-label">Tipe</span><span class="meta-val">{{ $orderType }}</span></div>
            </div>
            <span class="status-pill {{ $soStatus }}">{{ $soStatusLabel }}</span>
        </div>
    </div>

    {{-- ═══════════════ CUSTOMER INFO ═══════════════ --}}
    <div class="info-section">
        <div class="info-card">
            <div class="info-card-title">Pelanggan</div>
            <div class="info-row"><span class="il">Nama</span><span class="iv"><b>{{ $pelanggan }}</b></span></div>
            @if($phone !== '-')<div class="info-row"><span class="il">Telepon</span><span class="iv">{{ $phone }}</span></div>@endif
            @if($alamat !== '-')<div class="info-row"><span class="il">Alamat</span><span class="iv">{{ $alamat }}</span></div>@endif
        </div>
            <div class="info-card">
                <div class="info-card-title">Pengiriman</div>
                <div class="info-row"><span class="il">Tgl Kirim</span><span class="iv">{{ $soDelivery }}</span></div>
                <div class="info-row"><span class="il">Status</span><span class="iv"><span class="status-pill {{ $soStatus }}" style="font-size:9px">{{ $soStatusLabel }}</span></span></div>
                <div class="info-row"><span class="il">Dibuat</span><span class="iv">{{ $soCreated }}</span></div>
            </div>
    </div>

    {{-- ═══════════════ ITEMS TABLE ═══════════════ --}}
    <div class="items-wrap">
        <table class="items">
            <thead>
                <tr>
                    <th class="center" style="width:5%">No</th>
                    <th style="width:33%">Deskripsi Barang</th>
                    <th class="center" style="width:10%">Gudang</th>
                    <th class="center" style="width:10%">Qty</th>
                    <th class="right" style="width:20%">Harga Satuan</th>
                    <th class="right" style="width:22%">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesOrder->items as $i => $item)
                @php
                    $unitName = $item->unit_name ?? ($item->product?->unit?->name ?? 'pcs');
                    $displayQty = ($item->unit_factor && $item->unit_factor > 1)
                        ? $item->quantity / $item->unit_factor
                        : $item->quantity;
                    $displayQtyStr = is_float($displayQty) && $displayQty != (int)$displayQty
                        ? number_format($displayQty, 1, ',', '.')
                        : number_format($displayQty, 0, ',', '.');
                @endphp
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>
                        <span class="item-name">{{ $item->product?->name ?? 'Produk tidak tersedia' }}</span>
                        @if($item->product?->sku)<span class="item-sku">{{ $item->product->sku }}</span>@endif
                    </td>
                    <td class="center bold">{{ $item->warehouse?->name ?? '-' }}</td>
                    <td class="center">{{ $displayQtyStr }} {{ $unitName }}</td>
                    <td class="right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="right bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="right">Total ({{ $itemCount }} jenis barang)</td>
                    <td class="center">{{ number_format($totalQty, 0, ',', '.') }}</td>
                    <td></td>
                    <td class="right" style="font-size:11px">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- ═══════════════ SUMMARY & SIGNATURES ═══════════════ --}}
    <div class="bottom-section">
        <div class="bottom-left">
            <div class="sig-area">
                <div class="sig-block">
                    <div class="sig-label">Pemesan,</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">( {{ $pelanggan }} )</div>
                </div>
                <div class="sig-block">
                    <div class="sig-label">Hormat Kami,</div>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $dibuatOleh }}</div>
                </div>
            </div>
        </div>
        <div class="bottom-right">
            <div class="summary">
                <div class="sum-line total">
                    <span class="sl" style="font-weight:800;font-size:11px">Grand Total</span>
                    <span class="sv" style="font-weight:900;font-size:13px">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════ NOTES ═══════════════ --}}
    @if($salesOrder->notes)
    <div class="notes-bar"><b>Catatan:</b> {{ $salesOrder->notes }}</div>
    @endif

    {{-- ═══════════════ FOOTER ═══════════════ --}}
    <div class="footer">
        Dokumen ini adalah Sales Order (Pesanan Penjualan) dan sah tanpa tanda tangan dan stempel.<br>
        Dicetak pada {{ now()->format('d F Y, H:i') }} WIB.
    </div>

</div>

<div class="no-print" style="position:fixed;bottom:0;left:0;right:0;display:flex;justify-content:center;gap:10px;padding:15px;background:#f1f5f9;border-top:1px solid #d1d5db">
    <button onclick="window.print()" style="padding:10px 32px;font-size:14px;font-weight:800;cursor:pointer;background:#1a1a1a;color:#fff;border:none;border-radius:6px;letter-spacing:0.5px">&#128424;&nbsp; Cetak Faktur</button>
    <button onclick="window.close()" style="padding:10px 32px;font-size:14px;font-weight:700;cursor:pointer;background:#fff;color:#1a1a1a;border:2px solid #1a1a1a;border-radius:6px">&#10005;&nbsp; Tutup</button>
</div>

</body>
</html>
