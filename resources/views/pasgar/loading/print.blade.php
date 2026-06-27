<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Loading - {{ $loading->nomor_loading }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11pt; color: #000; line-height: 1.4; margin: 0; padding: 0; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-title { font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header-subtitle { font-size: 10pt; color: #333; margin-top: 5px; }
        
        .info-table { width: 100%; margin-bottom: 20px; font-size: 10pt; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-table td:nth-child(odd) { width: 120px; font-weight: bold; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 10pt; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; text-align: center; }
        .items-table .text-center { text-align: center; }
        .items-table .text-right { text-align: right; }
        
        .signatures { width: 100%; margin-top: 40px; text-align: center; font-size: 10pt; }
        .sig-box { display: inline-block; width: 30%; margin: 0 5%; }
        .sig-line { margin-top: 60px; border-bottom: 1px solid #000; }
        
        .notes { font-size: 9pt; border: 1px dotted #000; padding: 10px; margin-bottom: 20px; }
        .notes strong { display: block; margin-bottom: 5px; }
        
        .print-btn { position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background: #6366f1; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12pt; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <button class="print-btn" onclick="window.print()">Cetak Halaman</button>

    <div class="header">
        <div>
            <div class="header-title">Surat Jalan Loading</div>
            <div class="header-subtitle">Tim Pasukan Garuda</div>
        </div>
        <div style="text-align: right;">
            <strong style="font-size: 14pt;">{{ $loading->nomor_loading }}</strong><br>
            Tanggal: {{ $loading->tanggal->format('d/m/Y') }}
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td>Sales / PJ</td>
            <td>: {{ $loading->sales->nama ?? '-' }}</td>
            <td>Status</td>
            <td>: {{ ucfirst($loading->status) }}</td>
        </tr>
        <tr>
            <td>Sumber Utama</td>
            <td>: {{ $loading->sumber_label }}</td>
            <td>Lokasi</td>
            <td>: {{ $loading->warehouse->name ?? '-' }}</td>
        </tr>
    </table>

    @if($loading->catatan)
    <div class="notes">
        <strong>Catatan Permintaan:</strong>
        {{ $loading->catatan }}
    </div>
    @endif

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Barang (Kode)</th>
                <th width="15%">Sumber</th>
                <th width="15%">Qty Diminta</th>
                <th width="15%">Qty Disetujui</th>
                <th width="15%">Qty Dikirim</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loading->items as $index => $item)
            @php $unitName = $item->unitConversion?->unit?->name ?? 'pcs'; @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? '-' }}</strong><br>
                    <small>{{ $item->product->sku ?? '-' }}</small>
                </td>
                <td class="text-center">{{ ucfirst($item->sumber ?? 'Gudang') }}</td>
                <td class="text-center">{{ $item->qty_diminta }} {{ $unitName }}</td>
                <td class="text-center">{{ $item->qty_disetujui }} {{ $unitName }}</td>
                <td class="text-center">{{ $item->qty_dikirim }} {{ $unitName }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <div class="sig-box">
            <span>Disiapkan Oleh (Admin/Gudang),</span>
            <div class="sig-line"></div>
            <span>{{ $loading->preparer->name ?? '(...................................)' }}</span>
        </div>
        <div class="sig-box">
            <span>Diterima Oleh (Sales),</span>
            <div class="sig-line"></div>
            <span>{{ $loading->sales->nama ?? '(...................................)' }}</span>
        </div>
    </div>

    @if($loading->cross_check_notes)
    <div style="margin-top: 40px; font-size: 9pt; color: #555;">
        <strong>Catatan Cross-Check / Muat:</strong><br>
        {!! nl2br(e($loading->cross_check_notes)) !!}
    </div>
    @endif

</body>
</html>
