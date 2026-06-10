@extends('layouts.app', ['title' => 'Buat Opname - Pasgar'])

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .oc-wrap{font-family:'Plus Jakarta Sans',sans-serif;max-width:900px;margin:0 auto;padding:1.25rem}
    .oc-header{display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem}
    .oc-header-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(245,158,11,.25)}
    .oc-header-icon svg{width:26px;height:26px;stroke:#fff}
    .oc-header h1{font-size:1.35rem;font-weight:800;color:#78350f;margin:0}
    .oc-header p{font-size:.8rem;color:#d97706;margin:0;font-weight:600}
    .oc-card{background:#fff;border:1px solid #fef3c7;border-radius:14px;padding:1.5rem;margin-bottom:1.25rem}
    .oc-card-title{font-size:.85rem;font-weight:800;color:#92400e;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
    .oc-card-title svg{width:18px;height:18px;stroke:#d97706}
    .oc-form-group{margin-bottom:1rem}
    .oc-label{display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:.35rem}
    .oc-input,.oc-select,.oc-textarea{width:100%;padding:.6rem .85rem;border:1px solid #e5e7eb;border-radius:10px;font-size:.82rem;font-family:inherit;background:#fff;box-sizing:border-box}
    .oc-input:focus,.oc-select:focus,.oc-textarea:focus{outline:none;border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
    .oc-textarea{resize:vertical;min-height:70px}

    /* Item table */
    .oc-items-table{width:100%;border-collapse:collapse;margin-top:.5rem}
    .oc-items-table th{padding:.6rem .5rem;font-size:.68rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.3px;text-align:left;border-bottom:2px solid #fef3c7;background:#fffbeb}
    .oc-items-table td{padding:.6rem .5rem;font-size:.8rem;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle}
    .oc-qty-input{width:80px;padding:.4rem .5rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.82rem;text-align:center;font-weight:700;font-family:inherit}
    .oc-qty-input:focus{outline:none;border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
    .oc-selisih{display:inline-block;padding:.15rem .5rem;border-radius:6px;font-size:.72rem;font-weight:700;min-width:40px;text-align:center}
    .oc-selisih.pas{background:#d1fae5;color:#065f46}
    .oc-selisih.lebih{background:#fef3c7;color:#92400e}
    .oc-selisih.kurang{background:#fee2e2;color:#991b1b}
    .oc-product-name{font-weight:700;color:#1f2937}
    .oc-product-sku{font-size:.68rem;color:#9ca3af}

    /* Summary */
    .oc-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem;padding:1rem;background:#fffbeb;border-radius:10px;margin-bottom:1.25rem}
    .oc-summary-item{text-align:center}
    .oc-summary-val{font-size:1.2rem;font-weight:800;color:#78350f}
    .oc-summary-lbl{font-size:.68rem;color:#92400e;font-weight:600;margin-top:2px}

    /* Actions */
    .oc-actions{display:flex;gap:.75rem;justify-content:flex-end}
    .oc-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.25rem;border-radius:10px;font-size:.82rem;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all .2s}
    .oc-btn-ghost{background:#f3f4f6;color:#374151}
    .oc-btn-ghost:hover{background:#e5e7eb}
    .oc-btn-primary{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 3px 10px rgba(245,158,11,.2)}
    .oc-btn-primary:hover{box-shadow:0 5px 16px rgba(245,158,11,.3);transform:translateY(-1px)}
    .oc-btn-primary:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none}
    .oc-empty{text-align:center;padding:2.5rem 1rem;color:#9ca3af;font-size:.85rem}
    .oc-loading-info{background:#fffbeb;border:1px solid #fef3c7;border-radius:10px;padding:1rem;margin-bottom:1rem}
    .oc-loading-info h3{margin:0 0 .25rem;font-size:.9rem;font-weight:800;color:#78350f}
    .oc-loading-info p{margin:0;font-size:.78rem;color:#92400e}
</style>
@endpush

@section('content')
<div class="oc-wrap">
    @if(session('success'))
    <div style="background:#d1fae5;border:1px solid #a7f3d0;color:#065f46;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;font-weight:600;">{{ session('error') }}</div>
    @endif
    <div class="oc-header">
        <div class="oc-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div>
            <h1>Buat Opname</h1>
            <p>Rekonsiliasi barang sisa dari loading</p>
        </div>
    </div>

    @if($eligibleLoadings->isEmpty())
        <div class="oc-card">
            <div class="oc-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:.75rem"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p>Tidak ada loading yang memenuhi syarat untuk opname.</p>
                <p style="font-size:.75rem;margin-top:.5rem">Loading harus berstatus <strong>Dimuat ke Kendaraan</strong> dan memiliki barang sisa yang belum diopname.</p>
                <a href="{{ route('pasgar.opname.index') }}" class="oc-btn oc-btn-ghost" style="margin-top:1rem">Kembali</a>
            </div>
        </div>
    @else
    <form action="{{ route('pasgar.opname.store') }}" method="POST" id="opnameForm">
        @csrf

        {{-- Loading Selector --}}
        <div class="oc-card">
            <div class="oc-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Pilih Loading
            </div>
            <div class="oc-form-group">
                <select name="loading_id" id="loadingSelect" class="oc-select" required>
                    <option value="">-- Pilih Loading --</option>
                    @foreach($eligibleLoadings as $ld)
                        <option value="{{ $ld->id }}" {{ $selectedLoading && $selectedLoading->id == $ld->id ? 'selected' : '' }}>
                            {{ $ld->nomor_loading }} — {{ $ld->tanggal->format('d M Y') }} ({{ $ld->items->where('qty_sisa', '>', 0)->count() }} item sisa)
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Selected Loading Info --}}
            <div id="loadingInfo" style="display:none">
                <div class="oc-loading-info">
                    <h3 id="infoLoadingNumber"></h3>
                    <p id="infoLoadingDate"></p>
                </div>
            </div>
        </div>

        {{-- Date --}}
        <div class="oc-card">
            <div class="oc-form-group" style="margin-bottom:0">
                <label class="oc-label">Tanggal Opname</label>
                <input type="date" name="tanggal" class="oc-input" value="{{ date('Y-m-d') }}" required style="max-width:250px">
            </div>
        </div>

        {{-- Items Table --}}
        <div class="oc-card" id="itemsCard" style="display:none">
            <div class="oc-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                Item Barang Sisa
            </div>

            <div class="oc-summary" id="summaryBox">
                <div class="oc-summary-item"><div class="oc-summary-val" id="sumItems">0</div><div class="oc-summary-lbl">Total Item</div></div>
                <div class="oc-summary-item"><div class="oc-summary-val" id="sumSisa">0</div><div class="oc-summary-lbl">Sisa Sistem</div></div>
                <div class="oc-summary-item"><div class="oc-summary-val" id="sumFisik">0</div><div class="oc-summary-lbl">Total Fisik</div></div>
                <div class="oc-summary-item"><div class="oc-summary-val" id="sumSelisih">0</div><div class="oc-summary-lbl">Total Selisih</div></div>
            </div>

            <div style="overflow-x:auto">
                <table class="oc-items-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align:center">Satuan</th>
                            <th style="text-align:center">Dikirim</th>
                            <th style="text-align:center">Terjual</th>
                            <th style="text-align:center">Sisa Sistem</th>
                            <th style="text-align:center">Qty Fisik</th>
                            <th style="text-align:center">Selisih</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="oc-card" id="catatanCard" style="display:none">
            <div class="oc-form-group" style="margin-bottom:0">
                <label class="oc-label">Catatan</label>
                <textarea name="catatan" class="oc-textarea" placeholder="Catatan tambahan (opsional)..."></textarea>
            </div>
        </div>

        {{-- Actions --}}
        <div class="oc-actions" id="actionsBar" style="display:none">
            <a href="{{ route('pasgar.opname.index') }}" class="oc-btn oc-btn-ghost">Batal</a>
            <button type="submit" class="oc-btn oc-btn-primary" id="submitBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Opname & Kembalikan Stok
            </button>
        </div>
    </form>
    @endif
</div>

@if(!$eligibleLoadings->isEmpty())
@php
$loadingsData = $eligibleLoadings->map(function($ld) {
    return [
        'id' => $ld->id,
        'nomor_loading' => $ld->nomor_loading,
        'tanggal' => $ld->tanggal->format('d M Y'),
        'items' => $ld->items->where('qty_sisa', '>', 0)->values()->map(function($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? '-',
                'sku' => $item->product->sku ?? '',
                'qty_dikirim' => (int)($item->qty_dikirim ?? 0),
                'qty_terjual' => (int)$item->qty_terjual,
                'qty_sisa' => (int)$item->qty_sisa,
                'warehouse_id' => $item->warehouse_id,
                'unit_name' => $item->unitConversion?->unit?->name ?? 'pcs',
            ];
        }),
    ];
});
@endphp
@push('scripts')
<script>
const LOADINGS = @json($loadingsData);

const select = document.getElementById('loadingSelect');
const itemsCard = document.getElementById('itemsCard');
const catatanCard = document.getElementById('catatanCard');
const actionsBar = document.getElementById('actionsBar');
const loadingInfo = document.getElementById('loadingInfo');
const itemsBody = document.getElementById('itemsBody');

function init() {
    select.addEventListener('change', onLoadingChange);
    // Auto-select if only one
    if (select.options.length === 2) {
        select.selectedIndex = 1;
        onLoadingChange();
    } else if (select.value) {
        onLoadingChange();
    }
}

function onLoadingChange() {
    const ldId = parseInt(select.value);
    const ld = LOADINGS.find(l => l.id === ldId);
    if (!ld) {
        itemsCard.style.display = 'none';
        catatanCard.style.display = 'none';
        actionsBar.style.display = 'none';
        loadingInfo.style.display = 'none';
        return;
    }

    // Show loading info
    document.getElementById('infoLoadingNumber').textContent = ld.nomor_loading;
    document.getElementById('infoLoadingDate').textContent = 'Tanggal: ' + ld.tanggal;
    loadingInfo.style.display = 'block';

    // Render items
    itemsBody.innerHTML = '';
    ld.items.forEach((item, idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="oc-product-name">${item.product_name}</div>
                <div class="oc-product-sku">${item.sku}</div>
            </td>
            <td style="text-align:center;font-weight:600;color:#6b7280;font-size:.78rem">${item.unit_name}</td>
            <td style="text-align:center;font-weight:600">${item.qty_dikirim}</td>
            <td style="text-align:center;font-weight:600">${item.qty_terjual}</td>
            <td style="text-align:center;font-weight:700;color:#92400e">${item.qty_sisa}</td>
            <td style="text-align:center">
                <input type="number" name="items[${idx}][qty_fisik]" class="oc-qty-input"
                    value="${item.qty_sisa}" min="0" max="99999"
                    data-sisa="${item.qty_sisa}" data-idx="${idx}"
                    onchange="updateSelisih(this)" oninput="updateSelisih(this)">
                <input type="hidden" name="items[${idx}][loading_item_id]" value="${item.id}">
            </td>
            <td style="text-align:center"><span class="oc-selisih pas" id="sel-${idx}">0</span></td>
        `;
        itemsBody.appendChild(tr);
    });

    itemsCard.style.display = 'block';
    catatanCard.style.display = 'block';
    actionsBar.style.display = 'flex';
    updateSummary();
}

function updateSelisih(input) {
    const idx = input.dataset.idx;
    const sisa = parseInt(input.dataset.sisa);
    const fisik = parseInt(input.value) || 0;
    const selisih = fisik - sisa;
    const badge = document.getElementById('sel-' + idx);
    badge.textContent = selisih > 0 ? '+' + selisih : selisih;
    badge.className = 'oc-selisih ' + (selisih === 0 ? 'pas' : selisih > 0 ? 'lebih' : 'kurang');
    updateSummary();
}

function updateSummary() {
    const inputs = itemsBody.querySelectorAll('.oc-qty-input');
    let totalItems = inputs.length, totalSisa = 0, totalFisik = 0;
    inputs.forEach(inp => {
        const sisa = parseInt(inp.dataset.sisa);
        const fisik = parseInt(inp.value) || 0;
        totalSisa += sisa;
        totalFisik += fisik;
    });
    document.getElementById('sumItems').textContent = totalItems;
    document.getElementById('sumSisa').textContent = totalSisa;
    document.getElementById('sumFisik').textContent = totalFisik;
    const sel = totalFisik - totalSisa;
    const selEl = document.getElementById('sumSelisih');
    selEl.textContent = sel > 0 ? '+' + sel : sel;
    selEl.style.color = sel === 0 ? '#065f46' : sel > 0 ? '#92400e' : '#991b1b';
}

document.addEventListener('DOMContentLoaded', init);
</script>
@endpush
@endif
@endsection
