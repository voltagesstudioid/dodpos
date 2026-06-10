<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Opname - {{ $session->reference_number ?: 'Sesi #' . $session->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #0f172a; background: #fff; font-size: 11pt; }
        
        .print-wrap { max-width: 900px; margin: 0 auto; padding: 2rem; }
        
        /* Header */
        .print-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #0f172a; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .print-title { font-size: 1.3rem; font-weight: 800; letter-spacing: -0.02em; }
        .print-subtitle { font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; }
        .print-meta { text-align: right; font-size: 0.8rem; }
        .print-meta strong { display: block; font-size: 0.85rem; }
        
        /* Info box */
        .print-info { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .print-info-box { border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.75rem 1rem; }
        .print-info-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
        .print-info-value { font-size: 0.9rem; font-weight: 700; color: #0f172a; margin-top: 2px; }
        
        /* Table */
        .print-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        .print-table th { background: #f1f5f9; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; padding: 0.6rem 0.75rem; border: 1px solid #e2e8f0; text-align: left; }
        .print-table td { padding: 0.5rem 0.75rem; font-size: 0.8rem; border: 1px solid #e2e8f0; vertical-align: middle; }
        .print-table tr:nth-child(even) td { background: #fafbfc; }
        .print-table .r { text-align: right; }
        .print-table .c { text-align: center; }
        .print-table .bold { font-weight: 700; }
        .print-table .mono { font-family: monospace; font-size: 0.75rem; }
        
        .diff-plus { color: #16a34a; font-weight: 800; }
        .diff-minus { color: #dc2626; font-weight: 800; }
        .diff-zero { color: #94a3b8; }
        
        /* Highlight */
        .print-table tr.highlight td { background: #fffbeb !important; }
        
        /* Summary */
        .print-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 2rem; }
        .print-summary-box { border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.75rem; text-align: center; }
        .print-summary-val { font-size: 1.2rem; font-weight: 800; }
        .print-summary-lbl { font-size: 0.65rem; color: #64748b; font-weight: 600; margin-top: 2px; }
        
        /* Signature */
        .print-sign { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 3rem; }
        .print-sign-box { text-align: center; }
        .print-sign-line { border-bottom: 1px solid #0f172a; margin-top: 4rem; padding-bottom: 0.25rem; }
        .print-sign-name { font-weight: 700; font-size: 0.85rem; }
        .print-sign-role { font-size: 0.7rem; color: #64748b; }
        
        /* Footer */
        .print-footer { text-align: center; font-size: 0.65rem; color: #94a3b8; margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 0.75rem; }

        /* Print button */
        .no-print { display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 1.5rem; }
        .btn-print { padding: 0.5rem 1.5rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; border: none; font-family: inherit; }
        .btn-print-primary { background: #3b82f6; color: #fff; }
        .btn-print-primary:hover { background: #2563eb; }
        .btn-print-ghost { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        @media print {
            .no-print { display: none !important; }
            body { font-size: 10pt; }
            .print-wrap { padding: 0; max-width: 100%; }
            .print-table th { background: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-table tr:nth-child(even) td { background: #fafbfc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-table tr.highlight td { background: #fffbeb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body>
    <div class="print-wrap">
        <div class="no-print">
            <button class="btn-print btn-print-primary" onclick="window.print()">🖨️ Cetak Sekarang</button>
            <button class="btn-print btn-print-ghost" onclick="window.close()">Tutup</button>
        </div>

        <div class="print-header">
            <div>
                <div class="print-title">LEMBAR OPNAME STOK</div>
                <div class="print-subtitle">{{ $session->reference_number ?: 'Draft Sesi #' . $session->id }}</div>
            </div>
            <div class="print-meta">
                <strong>{{ $session->warehouse?->name ?? '-' }}</strong>
                Dicetak: {{ now()->format('d M Y, H:i') }} WIB
            </div>
        </div>

        <div class="print-info">
            <div class="print-info-box">
                <div class="print-info-label">Status</div>
                <div class="print-info-value">{{ strtoupper($session->status) }}</div>
            </div>
            <div class="print-info-box">
                <div class="print-info-label">Dibuat Oleh</div>
                <div class="print-info-value">{{ $session->creator?->name ?? '-' }}</div>
            </div>
            <div class="print-info-box">
                <div class="print-info-label">Jumlah Item</div>
                <div class="print-info-value">{{ $session->items->count() }} produk</div>
            </div>
        </div>

        @php
            $totalItems = $session->items->count();
            $diffPlus = $session->items->where('difference_qty', '>', 0)->count();
            $diffMinus = $session->items->where('difference_qty', '<', 0)->count();
            $diffZero = $session->items->where('difference_qty', 0)->count();
        @endphp

        <div class="print-summary">
            <div class="print-summary-box">
                <div class="print-summary-val">{{ $totalItems }}</div>
                <div class="print-summary-lbl">Total Item</div>
            </div>
            <div class="print-summary-box">
                <div class="print-summary-val diff-plus">{{ $diffPlus }}</div>
                <div class="print-summary-lbl">Stok Bertambah</div>
            </div>
            <div class="print-summary-box">
                <div class="print-summary-val diff-minus">{{ $diffMinus }}</div>
                <div class="print-summary-lbl">Stok Berkurang</div>
            </div>
            <div class="print-summary-box">
                <div class="print-summary-val diff-zero">{{ $diffZero }}</div>
                <div class="print-summary-lbl">Sesuai Sistem</div>
            </div>
        </div>

        <table class="print-table">
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th>Produk</th>
                    <th>SKU</th>
                    <th class="r">Qty Sistem</th>
                    <th class="r">Qty Fisik</th>
                    <th class="c">Satuan</th>
                    <th class="c">Selisih</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($session->items as $i => $it)
                @php $diff = (int) $it->difference_qty; @endphp
                <tr class="{{ $diff !== 0 ? 'highlight' : '' }}">
                    <td class="c">{{ $i + 1 }}</td>
                    <td class="bold">{{ $it->product?->name ?? 'Produk Dihapus' }}</td>
                    <td class="mono">{{ $it->product?->sku ?? '-' }}</td>
                    <td class="r">{{ (int) $it->system_qty }}</td>
                    <td class="r bold">{{ (int) $it->physical_qty }}</td>
                    <td class="c">
                        @if($it->counted_unit)
                        <strong>{{ number_format((float)$it->counted_qty, 0) }} {{ $it->counted_unit }}</strong>
                        @else
                        <span class="diff-zero">base</span>
                        @endif
                    </td>
                    <td class="c {{ $diff > 0 ? 'diff-plus' : ($diff < 0 ? 'diff-minus' : 'diff-zero') }}">
                        {{ $diff > 0 ? '+' . $diff : $diff }}
                    </td>
                    <td>{{ $it->notes ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($session->notes)
        <div style="margin-bottom:1.5rem;padding:0.75rem 1rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;font-size:0.8rem;">
            <strong>Catatan:</strong> {{ $session->notes }}
        </div>
        @endif

        <div class="print-sign">
            <div class="print-sign-box">
                <div class="print-sign-line"></div>
                <div class="print-sign-name">{{ $session->creator?->name ?? '_______________' }}</div>
                <div class="print-sign-role">Admin Gudang (Pembuat)</div>
            </div>
            <div class="print-sign-box">
                <div class="print-sign-line"></div>
                <div class="print-sign-name">{{ $session->approver?->name ?? '_______________' }}</div>
                <div class="print-sign-role">Supervisor (Approver)</div>
            </div>
        </div>

        <div class="print-footer">
            Dokumen ini dicetak pada {{ now()->format('d M Y H:i') }} WIB | {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
