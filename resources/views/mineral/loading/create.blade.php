<x-app-layout>
    <x-slot name="header">
        <div class="ph">
            <div class="ph-left">
                <div class="ph-icon teal">📝</div>
                <div>
                    <div class="ph-breadcrumb">
                        <a href="{{ route('mineral.dashboard') }}">Mineral</a>
                        <span class="ph-breadcrumb-sep">/</span>
                        <a href="{{ route('mineral.loading.index') }}">Loading Armada</a>
                        <span class="ph-breadcrumb-sep">/</span>
                        <span>Buat Surat Jalan</span>
                    </div>
                    <h2 class="ph-title">Input Surat Jalan (Loading)</h2>
                    <p class="ph-subtitle">Pindahkan stok gudang Air Mineral fisik ke muatan Truk / Pickup mobil armada Sales.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="card p-0" style="max-width:800px;">
        <div class="form-card-header">
            <div class="form-card-icon teal">🚚</div>
            <div>
                <h3 class="form-card-title">Form Alokasi Muatan Sales Mobil</h3>
                <p class="form-card-subtitle">Perhatikan sisa stok gudang sebelum memuat ke armada.</p>
            </div>
        </div>
        
        <form action="{{ route('mineral.loading.store') }}" method="POST">
            @csrf
            <div class="form-card-body">
                <div class="form-group mb-3">
                    <label class="form-label">Pilih Pegawai (Sales Armada Mineral) <span class="required">*</span></label>
                    <select name="sales_id" class="form-input" required>
                        <option value="">-- Pilih Sales (Role: sales_mineral) --</option>
                        @foreach($salesList as $sl)
                            <option value="{{ $sl->id }}">{{ $sl->name }} ({{ $sl->email }})</option>
                        @endforeach
                    </select>
                </div>

                <h4 class="font-bold text-gray mb-2">Daftar Muatan Barang (Input Qty Dus)</h4>
                <div class="table-wrapper border rounded-lg overflow-hidden">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="40%">Air Mineral SKU</th>
                                <th width="20%">Stok Tersedia</th>
                                <th width="40%">Jumlah Dimuat (Dus)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $i => $p)
                                @php
                                    $st = $p->warehouseStocks->first();
                                    $availableQty = $st ? $st->qty_dus : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="td-main">{{ $p->name }}</div>
                                        <input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $p->id }}">
                                    </td>
                                    <td>
                                        @if(($maskStock ?? false) === true)
                                            <span class="badge badge-warning">Terkunci</span>
                                        @else
                                            <span class="badge {{ $availableQty > 0 ? 'badge-success' : 'badge-danger' }}">
                                                {{ $availableQty }} Dus
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="items[{{ $i }}][qty_dus]" 
                                               class="form-input" 
                                               min="0" 
                                               max="{{ ($maskStock ?? false) ? 0 : $availableQty }}" 
                                               placeholder="{{ ($maskStock ?? false) ? 'Wajib opname untuk input' : 'Isi Qty...' }}" 
                                               {{ (($maskStock ?? false) === true || $availableQty == 0) ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="floating-bar mt-3" style="border-radius:0 0 14px 14px; background:#f8fafc;">
                <a href="{{ route('mineral.loading.index') }}" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary"><span style="font-size:1.1rem">✔️</span> Buat Surat Jalan Loading</button>
            </div>
        </form>
    </div>
</x-app-layout>
