<x-app-layout>
    <x-slot name="header">Detail Sales Order</x-slot>

    <style>
        .so-page{padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;}
        .so-page-header{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;}
        .so-page-title{font-size:1.35rem;font-weight:800;color:#0f172a;letter-spacing:-.02em;}
        .so-page-sub{font-size:.82rem;color:#64748b;margin-top:.15rem;}
        .so-btn-back{display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:10px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;}
        .so-btn-back:hover{background:#e2e8f0;color:#334155;}
        .so-btn-primary{display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-radius:10px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;box-shadow:0 2px 8px rgba(99,102,241,.25);}
        .so-btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(99,102,241,.35);}
        .so-btn-print{display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;background:#fff;color:#475569;border:1px solid #e2e8f0;border-radius:10px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;}
        .so-btn-print:hover{background:#f8fafc;}
        .so-btn-danger{display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.1rem;background:#fff;color:#dc2626;border:1px solid #fecaca;border-radius:10px;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;cursor:pointer;}
        .so-btn-danger:hover{background:#fef2f2;border-color:#fca5a5;}
        .so-actions{display:inline-flex;gap:.65rem;align-items:center;flex-wrap:wrap;}

        /* ─── card styles ─── */
        .so-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .so-card-hdr{padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;}
        .so-card-body{padding:1.5rem;}
        .so-card-title{font-size:1rem;font-weight:800;color:#0f172a;}
        .so-card-sub{font-size:.8rem;color:#64748b;margin-top:.2rem;}

        /* ─── status pills ─── */
        .so-status{font-size:.78rem;font-weight:700;padding:.3rem .8rem;border-radius:9px;display:inline-flex;align-items:center;gap:.4rem;}
        .so-status::before{content:'';width:7px;height:7px;border-radius:50%;flex-shrink:0;}
        .so-status-draft{background:#fef3c7;color:#92400e;}.so-status-draft::before{background:#d97706;}
        .so-status-confirmed{background:#dbeafe;color:#1e40af;}.so-status-confirmed::before{background:#2563eb;}
        .so-status-processing{background:#e0e7ff;color:#3730a3;}.so-status-processing::before{background:#6366f1;}
        .so-status-completed{background:#d1fae5;color:#065f46;}.so-status-completed::before{background:#059669;}
        .so-status-cancelled{background:#ffe4e6;color:#9f1239;}.so-status-cancelled::before{background:#e11d48;}

        /* ─── info grid ─── */
        .so-info-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;}
        .so-info-section{display:flex;flex-direction:column;gap:.8rem;}
        .so-info-label{font-size:.75rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;}
        .so-info-value{font-size:.95rem;font-weight:700;color:#0f172a;}
        .so-info-meta{font-size:.8rem;color:#64748b;}
        .so-info-row{display:grid;grid-template-columns:160px 1fr;gap:.5rem;align-items:baseline;}

        /* ─── items table ─── */
        .so-tbl{width:100%;border-collapse:collapse;}
        .so-tbl thead{background:#f8fafc;}
        .so-tbl th{padding:.85rem 1.1rem;font-size:.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;text-align:left;border-bottom:1px solid #e2e8f0;}
        .so-tbl td{padding:.85rem 1.1rem;font-size:.85rem;color:#334155;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        .so-tbl tbody tr{transition:background .12s;}
        .so-tbl tbody tr:hover{background:#f8fafc;}
        .so-tbl tbody tr:last-child td{border-bottom:none;}
        .so-prod-name{font-weight:700;color:#0f172a;}
        .so-prod-meta{font-size:.75rem;color:#94a3b8;margin-top:.2rem;}
        .so-num{font-variant-numeric:tabular-nums;}
        .so-total-row{background:#f8fafc;}

        /* ─── notes ─── */
        .so-notes{background:#fefce8;border:1px solid #fef08a;border-radius:12px;padding:1rem 1.2rem;}
        .so-notes-label{font-size:.75rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.4rem;}
        .so-notes-text{font-size:.85rem;color:#713f12;white-space:pre-line;}

        /* ─── company header ─── */
        .so-company-header{margin-bottom:1.5rem;}
        .so-company-name{font-size:1.5rem;font-weight:800;color:#0f172a;}
        .so-company-info{display:flex;flex-direction:column;gap:.25rem;}
        .so-invoice-info{display:flex;flex-direction:column;align-items:flex-end;gap:.25rem;}

        /* ─── invoice header ─── */
        .so-invoice-header{margin-bottom:2rem;}
        .so-section-title{font-size:.875rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;}
        .so-customer-name{font-size:1.125rem;font-weight:700;color:#0f172a;}
        .so-customer-phone,.so-customer-address{font-size:.875rem;color:#64748b;}
        .so-invoice-details{display:flex;flex-direction:column;gap:.5rem;}
        .so-detail-row{display:flex;justify-content:space-between;align-items:center;}
        .so-detail-label{font-size:.875rem;font-weight:600;color:#64748b;}
        .so-detail-value{font-size:.875rem;font-weight:700;color:#0f172a;}

        /* ─── header split ─── */
        .so-header-split{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;}
        .so-header-left{display:flex;flex-direction:column;gap:.3rem;}
        .so-so-number{font-size:1.25rem;font-weight:800;color:#4f46e5;}

        /* ─── invoice footer ─── */
        .so-invoice-footer{margin-top:2rem;}
        .so-payment-info{margin-bottom:1.5rem;}
        .so-payment-title{font-size:.875rem;color:#475569;}
        .so-payment-details{font-size:.75rem;color:#64748b;}
        .so-payment-row{margin-bottom:.25rem;}
        .so-payment-label{font-weight:600;color:#475569;margin-right:.5rem;}
        .so-payment-value{font-weight:700;color:#0f172a;}
        .so-terms-title{font-size:.875rem;color:#475569;}
        .so-terms-text{font-size:.75rem;color:#64748b;line-height:1.4;}
        .so-signature-line{height:1px;background:#000;width:200px;}
        .so-signature-label{font-size:.75rem;color:#64748b;margin-top:.25rem;}

        /* ─── responsive ─── */
        @@media(max-width:768px){
            .so-page{padding:1rem;}
            .so-info-grid{grid-template-columns:1fr;}
            .so-info-row{grid-template-columns:120px 1fr;}
            .so-page-header{flex-direction:column;align-items:stretch;}
            .so-actions{justify-content:space-between;}
            .so-header-split{flex-direction:column;}
            .so-tbl th,.so-tbl td{padding:.65rem .7rem;font-size:.78rem;}
        }
        @@media print{
            /* Reset page styles */
            *{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
            html,body{margin:0;padding:0;background:#fff;font-size:11pt;font-family:'Arial', sans-serif;color:#000;}
            
            /* Hide all UI elements */
            .so-btn-back,.so-btn-primary,.so-btn-print,.so-btn-danger,.so-actions,.so-page-header,
            .so-info-grid,.so-info-section,.so-info-label,.so-info-value,.so-info-meta,.so-info-row,
            .so-status{display:none!important;}
            
            /* Page layout */
            .so-page{padding:0!important;margin:0!important;}
            .so-card{border:none!important;box-shadow:none!important;border-radius:0!important;background:#fff!important;}
            .so-card-hdr{border-bottom:none!important;padding:0!important;}
            .so-card-body{padding:0!important;}
            
            /* Company Header - Professional invoice header */
            .so-company-header{
                border-bottom:2px solid #000!important;
                padding-bottom:12pt!important;
                margin-bottom:20pt!important;
                page-break-inside:avoid!important;
            }
            .so-company-name{
                font-size:18pt!important;
                font-weight:bold!important;
                color:#000!important;
                margin-bottom:4pt!important;
            }
            .so-company-address,.so-company-phone,.so-company-email{
                font-size:9pt!important;
                color:#333!important;
                line-height:1.3!important;
            }
            
            /* Invoice Info - Right aligned */
            .so-invoice-info{
                text-align:right!important;
                margin-top:8pt!important;
            }
            .so-card-title{
                font-size:16pt!important;
                font-weight:bold!important;
                color:#000!important;
                margin-bottom:4pt!important;
                text-transform:uppercase!important;
            }
            .so-so-number{
                font-size:12pt!important;
                font-weight:bold!important;
                color:#000!important;
                margin-bottom:2pt!important;
            }
            .so-invoice-date{
                font-size:9pt!important;
                color:#333!important;
            }
            
            /* Invoice Header - Customer info */
            .so-invoice-header{
                margin-bottom:20pt!important;
                page-break-inside:avoid!important;
            }
            .so-section-title{
                font-size:9pt!important;
                font-weight:bold!important;
                color:#000!important;
                text-transform:uppercase!important;
                margin-bottom:6pt!important;
                border-bottom:1px solid #ccc!important;
                padding-bottom:2pt!important;
            }
            .so-customer-name{
                font-size:11pt!important;
                font-weight:bold!important;
                color:#000!important;
                margin-bottom:4pt!important;
            }
            .so-customer-phone,.so-customer-address{
                font-size:9pt!important;
                color:#333!important;
                line-height:1.3!important;
            }
            .so-detail-row{
                display:flex!important;
                justify-content:space-between!important;
                margin-bottom:4pt!important;
                font-size:9pt!important;
            }
            .so-detail-label{
                font-weight:600!important;
                color:#333!important;
            }
            .so-detail-value{
                font-weight:bold!important;
                color:#000!important;
            }
            
            /* Items Table - Professional table styling */
            .so-tbl{
                width:100%!important;
                border-collapse:collapse!important;
                margin:16pt 0!important;
                page-break-inside:avoid!important;
            }
            .so-tbl thead th{
                background:#f5f5f5!important;
                color:#000!important;
                border-bottom:2px solid #000!important;
                padding:6pt 8pt!important;
                font-size:9pt!important;
                font-weight:bold!important;
                text-align:left!important;
                text-transform:uppercase!important;
            }
            .so-tbl td{
                padding:6pt 8pt!important;
                border-bottom:1px solid #ddd!important;
                font-size:9pt!important;
                color:#000!important;
                vertical-align:top!important;
            }
            .so-tbl tbody tr:hover{background:none!important;}
            .so-prod-name{
                font-weight:bold!important;
                color:#000!important;
                font-size:10pt!important;
            }
            .so-prod-meta{
                font-size:8pt!important;
                color:#666!important;
                margin-top:2pt!important;
            }
            
            /* Totals Section - Clean calculation display */
            .so-total-grid{
                margin-top:16pt!important;
                page-break-inside:avoid!important;
            }
            .so-total-row{
                display:flex!important;
                justify-content:space-between!important;
                padding:4pt 0!important;
                font-size:9pt!important;
            }
            .so-total-label{
                color:#333!important;
            }
            .so-total-value{
                font-weight:bold!important;
                color:#000!important;
                text-align:right!important;
                min-width:100pt!important;
            }
            .so-total-row:last-child{
                border-top:2px solid #000!important;
                font-size:11pt!important;
                font-weight:bold!important;
                color:#000!important;
                padding-top:8pt!important;
                margin-top:4pt!important;
            }
            
            /* Payment Info & Terms - Side by side layout */
            .so-payment-info{
                margin:20pt 0!important;
                page-break-inside:avoid!important;
            }
            .so-payment-title,.so-terms-title{
                font-size:10pt!important;
                font-weight:bold!important;
                color:#000!important;
                margin-bottom:6pt!important;
                text-transform:uppercase!important;
            }
            .so-payment-details,.so-terms-text{
                font-size:8pt!important;
                color:#333!important;
                line-height:1.4!important;
            }
            .so-payment-row{
                margin-bottom:3pt!important;
            }
            .so-payment-label{
                color:#666!important;
                font-weight:normal!important;
            }
            .so-payment-value{
                color:#000!important;
                font-weight:bold!important;
            }
            
            /* Signature Area - Professional spacing */
            .so-signature{
                margin-top:30pt!important;
                padding-top:12pt!important;
                border-top:1px solid #ccc!important;
                page-break-inside:avoid!important;
            }
            .so-signature-line{
                height:1px!important;
                background:#000!important;
                width:180pt!important;
                margin:0 auto 20pt auto!important;
            }
            .so-signature-label{
                font-size:8pt!important;
                color:#666!important;
                text-align:center!important;
                text-transform:uppercase!important;
            }
            
            /* Page setup */
            @page{
                margin:2cm 1.5cm;
                @bottom-center{
                    content:"Halaman " counter(page) " dari " counter(pages);
                    font-size:8pt;
                    color:#666;
                }
            }
            
            /* Avoid page breaks inside important sections */
            .so-company-header,.so-invoice-header,.so-tbl,.so-total-grid,.so-payment-info,.so-signature{
                page-break-inside:avoid!important;
            }
            
            /* Force page break before if needed */
            .so-signature{
                page-break-before:avoid!important;
            }
        }
    </style>

    <div class="so-page">
        {{-- ─── HEADER ─── --}}
        <div class="so-page-header">
            <a href="{{ route('sales-order.index') }}" class="so-btn-back">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Daftar
            </a>
            <div class="so-actions">
                <button type="button" onclick="window.print()" class="so-btn-print">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak
                </button>
                @if(!in_array($salesOrder->status, ['completed', 'cancelled']))
                    <a href="{{ route('sales-order.edit', $salesOrder->id) }}" class="so-btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit SO
                    </a>
                @endif
            </div>
        </div>

        {{-- ─── MAIN CARD ─── --}}
        <div class="so-card" id="printableArea">
            <div class="so-card-hdr">
                {{-- Company Header --}}
                <div class="so-company-header mb-8 pb-4 border-b border-gray-200">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="so-company-info">
                            <div class="so-company-name text-2xl font-extrabold text-gray-900">{{ $storeSettings->store_name ?? config('app.name', 'DODPOS') }}</div>
                            @if($storeSettings->store_address)
                                <div class="so-company-address text-gray-600 mt-1">{{ $storeSettings->store_address }}</div>
                            @endif
                            @if($storeSettings->store_phone)
                                <div class="so-company-phone text-gray-600 mt-1">Tel: {{ $storeSettings->store_phone }}</div>
                            @endif
                            @if($storeSettings->store_email)
                                <div class="so-company-email text-gray-600 mt-1">Email: {{ $storeSettings->store_email }}</div>
                            @endif
                        </div>
                        <div class="so-invoice-info text-right">
                            <div class="so-card-title text-2xl font-bold text-gray-900">FAKTUR PENJUALAN</div>
                            <div class="so-so-number text-lg font-bold text-gray-700 mt-1">No: {{ $salesOrder->so_number }}</div>
                            <div class="so-invoice-date text-sm text-gray-600 mt-1">Tanggal: {{ \Carbon\Carbon::parse($salesOrder->order_date)->translatedFormat('d F Y') }}</div>
                            <span class="so-status so-status-{{ $salesOrder->status }} mt-2 inline-block">
                                Status: {{ ucfirst($salesOrder->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="so-card-body">
                {{-- ─── INVOICE HEADER ─── --}}
                <div class="so-invoice-header mb-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="so-bill-to">
                            <div class="so-section-title mb-2">KEPADA:</div>
                            <div class="so-customer-name font-bold text-lg">{{ $salesOrder->customer?->name ?? '-' }}</div>
                            @if($salesOrder->customer?->phone)
                                <div class="so-customer-phone">{{ $salesOrder->customer?->phone }}</div>
                            @endif
                            @if($salesOrder->customer?->address)
                                <div class="so-customer-address whitespace-pre-line">{{ $salesOrder->customer?->address }}</div>
                            @endif
                        </div>
                        <div class="so-invoice-details">
                            <div class="so-detail-row">
                                <div class="so-detail-label">Nomor Invoice:</div>
                                <div class="so-detail-value">{{ $salesOrder->so_number }}</div>
                            </div>
                            <div class="so-detail-row">
                                <div class="so-detail-label">Tanggal Invoice:</div>
                                <div class="so-detail-value">{{ \Carbon\Carbon::parse($salesOrder->order_date)->translatedFormat('d F Y') }}</div>
                            </div>
                            <div class="so-detail-row">
                                <div class="so-detail-label">Tanggal Kirim:</div>
                                <div class="so-detail-value">
                                    @if($salesOrder->delivery_date)
                                        {{ \Carbon\Carbon::parse($salesOrder->delivery_date)->translatedFormat('d F Y') }}
                                        @php
                                            $dlvDate = \Carbon\Carbon::parse($salesOrder->delivery_date);
                                            if ($dlvDate->isSameDay(\Carbon\Carbon::today())) {
                                                echo ' <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:4px;font-size:.75rem;font-weight:700;">Hari Ini</span>';
                                            } elseif ($dlvDate->isSameDay(\Carbon\Carbon::tomorrow())) {
                                                echo ' <span style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:4px;font-size:.75rem;font-weight:700;">Besok</span>';
                                            } elseif ($dlvDate->lt(\Carbon\Carbon::today())) {
                                                echo ' <span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:4px;font-size:.75rem;font-weight:700;">Terlambat</span>';
                                            }
                                        @endphp
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="so-detail-row">
                                <div class="so-detail-label">Tanggal Jatuh Tempo:</div>
                                <div class="so-detail-value">{{ \Carbon\Carbon::parse($salesOrder->order_date)->addDays(7)->translatedFormat('d F Y') }}</div>
                            </div>
                            <div class="so-detail-row">
                                <div class="so-detail-label">Sales / Kasir:</div>
                                <div class="so-detail-value">{{ $salesOrder->user?->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── ITEMS TABLE ─── --}}
                <div class="mb-8">
                    <table class="so-tbl w-full">
                        <thead>
                            <tr>
                                <th class="text-left w-12">No</th>
                                <th class="text-left">Nama Barang</th>
                                <th class="text-right w-32">Harga Satuan</th>
                                <th class="text-center w-20">Qty</th>
                                <th class="text-right w-40">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesOrder->items as $idx => $item)
                                <tr>
                                    <td class="so-num text-center">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="so-prod-name">{{ $item->product?->name ?? ('Produk ID ' . $item->product_id) }}</div>
                                        @if($item->product?->barcode)
                                            <div class="so-prod-meta">Kode: {{ $item->product?->barcode }}</div>
                                        @endif
                                    </td>
                                    <td class="text-right so-num">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center so-num">{{ $item->quantity }}</td>
                                    <td class="text-right so-num font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ─── TOTALS SECTION ─── --}}
                <div class="mb-8">
                    <div class="flex justify-end">
                        <div class="w-80">
                            <div class="so-total-grid">
                                <div class="so-total-row flex justify-between py-2 border-b border-gray-200">
                                    <div class="so-total-label text-gray-700">Subtotal:</div>
                                    <div class="so-total-value so-num font-bold">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</div>
                                </div>
                                <div class="so-total-row flex justify-between py-2 border-b border-gray-200">
                                    <div class="so-total-label text-gray-700">PPN (11%):</div>
                                    <div class="so-total-value so-num">Rp {{ number_format($salesOrder->total_amount * 0.11, 0, ',', '.') }}</div>
                                </div>
                                <div class="so-total-row flex justify-between py-3 border-t-2 border-gray-800">
                                    <div class="so-total-label text-lg font-bold text-gray-900">TOTAL BAYAR:</div>
                                    <div class="so-total-value text-xl font-bold text-gray-900 so-num">Rp {{ number_format($salesOrder->total_amount * 1.11, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── NOTES AND FOOTER ─── --}}
                <div class="so-invoice-footer">
                    @if($salesOrder->notes)
                        <div class="so-notes mb-4">
                            <div class="so-notes-label">Catatan:</div>
                            <div class="so-notes-text">{{ $salesOrder->notes }}</div>
                        </div>
                    @endif
                    
                    <div class="so-payment-info mb-6">
                        <div class="grid grid-cols-2 gap-8">
                            <div class="so-payment-details">
                                <div class="so-payment-title font-bold mb-2 text-gray-900">METODE PEMBAYARAN:</div>
                                <div class="so-payment-details text-sm">
                                    @if($storeSettings->bank_name && $storeSettings->bank_account_number)
                                        <div class="so-payment-row mb-2">
                                            <span class="so-payment-label font-semibold text-gray-700">Transfer Bank:</span>
                                        </div>
                                        <div class="so-payment-row mb-1">
                                            <span class="so-payment-label text-gray-600">Bank:</span>
                                            <span class="so-payment-value font-semibold text-gray-900">{{ $storeSettings->bank_name }}</span>
                                        </div>
                                        <div class="so-payment-row mb-1">
                                            <span class="so-payment-label text-gray-600">No. Rekening:</span>
                                            <span class="so-payment-value font-semibold text-gray-900">{{ $storeSettings->bank_account_number }}</span>
                                        </div>
                                        @if($storeSettings->bank_account_holder)
                                        <div class="so-payment-row mb-1">
                                            <span class="so-payment-label text-gray-600">Atas Nama:</span>
                                            <span class="so-payment-value font-semibold text-gray-900">{{ $storeSettings->bank_account_holder }}</span>
                                        </div>
                                        @endif
                                    @else
                                        <div class="so-payment-row">
                                            <span class="so-payment-value text-gray-600">Silakan hubungi kami untuk informasi pembayaran.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="so-terms">
                                <div class="so-terms-title font-bold mb-2 text-gray-900">SYARAT & KETENTUAN:</div>
                                <div class="so-terms-text text-sm text-gray-700">
                                    1. Pembayaran dilakukan dalam waktu 7 hari setelah faktur diterima.<br>
                                    2. Barang yang sudah dibeli tidak dapat dikembalikan kecuali ada kerusakan.<br>
                                    3. Klaim kerusakan harus diajukan dalam waktu 3 hari setelah barang diterima.<br>
                                    4. Harga sudah termasuk PPN 11% sesuai ketentuan yang berlaku.<br>
                                    5. Pembayaran dianggap lunas setelah dana masuk ke rekening perusahaan.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="so-signature mt-8 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-8">
                            <div class="so-customer-signature">
                                <div class="so-signature-line mb-12"></div>
                                <div class="so-signature-label">Tanda Tangan Pelanggan</div>
                            </div>
                            <div class="so-company-signature text-right">
                                <div class="so-signature-line mb-12"></div>
                                <div class="so-signature-label">Tanda Tangan Perusahaan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── DELETE BUTTON ─── --}}
        @if(!in_array($salesOrder->status, ['completed', 'cancelled']))
            <div class="flex justify-end">
                <form action="{{ route('sales-order.destroy', $salesOrder->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Sales Order ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="so-btn-danger">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Hapus SO
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
