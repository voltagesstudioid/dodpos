<x-app-layout>
    <x-slot name="header">
        <div class="ph">
            <div class="ph-left">
                <a href="{{ route('mineral.loading.index') }}" class="ph-icon slate" style="color:white;text-decoration:none;">←</a>
                <div>
                    <h2 class="ph-title">Detail Surat Jalan #SJ-M{{ str_pad($loading->id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <p class="ph-subtitle">Dicetak pada {{ \Carbon\Carbon::parse($loading->created_at)->translatedFormat('l, d F Y H:i:s') }}</p>
                </div>
            </div>
            <div class="ph-actions">
                <button onclick="window.print()" class="btn-secondary">🖨️ Cetak Bukti</button>
            </div>
        </div>
    </x-slot>

    <div class="grid-2 mb-3">
        <!-- Informasi Pihak Terlibat -->
        <div class="card p-3">
            <h4 class="font-bold mb-2">Informasi Loading</h4>
            <div class="info-row">
                <span class="info-key">No. Ref</span>
                <span class="info-val">SJ-M{{ str_pad($loading->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Pihak Diserahkan Oleh (Admin)</span>
                <span class="info-val text-blue">{{ $loading->admin->name ?? 'Admin Dihapus' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Pihak Penerima (Sales Jalan)</span>
                <span class="info-val text-green font-bold" style="font-size:1rem">{{ $loading->sales->name ?? 'Sales Dihapus' }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Status SJ</span>
                <span class="badge badge-success">VALID (Loading Sukses)</span>
            </div>
        </div>
    </div>

    <!-- Rincian Muatan Barang -->
    <div class="card p-0 mb-3" style="max-width:800px;">
        <div class="form-card-header">
            <div class="form-card-icon blue">📦</div>
            <div>
                <h3 class="form-card-title">Rincian Barang Alokasi SJ-M{{ str_pad($loading->id, 5, '0', STR_PAD_LEFT) }}</h3>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th width="50%">Item / SKUs Air Mineral</th>
                        <th width="40%" style="text-align:right">Total Dibawa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loading->items as $i => $item)
                        <tr>
                            <td><div class="td-sub">{{ $i + 1 }}</div></td>
                            <td><div class="td-main">{{ $item->product->name }}</div></td>
                            <td style="text-align:right"><div class="font-bold">{{ $item->qty_dus }} <span style="font-size:0.8rem;color:#94a3b8;font-weight:normal">Dus</span></div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print styling -->
    <style>
        @media print {
            body * { visibility: hidden; }
            .main-wrapper { margin:0; padding:0; width:100%; }
            .page-content * { visibility: visible; }
            .ph-actions { display: none !important; }
            .card { box-shadow: none !important; border: 1px solid #000; }
            .btn-secondary { display: none !important; }
        }
    </style>
</x-app-layout>
