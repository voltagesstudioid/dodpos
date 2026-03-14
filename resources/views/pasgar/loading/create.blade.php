<x-app-layout>
<x-slot name="header">Buat Loading Baru</x-slot>

<div class="page-header">
    <div>
        <div class="page-header-title">Buat Loading Barang</div>
        <div class="page-header-subtitle">Pindahkan stok dari gudang utama ke kendaraan Sales / Pasgar</div>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('pasgar.loadings.index') }}" class="btn-secondary">← Kembali</a>
        <button type="submit" form="loadingForm" class="btn-primary">💾 Simpan</button>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <div>❌ Periksa input Anda:</div>
        <div style="margin-top:0.35rem;">
            <ul style="margin:0;padding-left:1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div>
@endif

<form action="{{ route('pasgar.loadings.store') }}" method="POST" id="loadingForm">
    @csrf

    <div class="panel" style="max-width: 980px;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Informasi Mutasi</div>
                <div class="panel-subtitle">Tanggal, gudang asal, kendaraan tujuan, dan catatan</div>
            </div>
            <span class="badge badge-gray">Status: Draft</span>
        </div>
        <div class="panel-body">
            <div class="form-row-3">
                <div>
                    <label class="form-label">Tanggal Loading <span class="required">*</span></label>
                    <input type="date" name="date" class="form-input" value="{{ old('date', date('Y-m-d')) }}" required>
                    <div class="form-hint">Gunakan tanggal transaksi sesuai jadwal loading.</div>
                </div>
                <div>
                    <label class="form-label">Dari Gudang (Asal) <span class="required">*</span></label>
                    <select name="from_warehouse_id" class="form-input" required>
                        <option value="">Pilih Gudang Asal</option>
                        @foreach($mainWarehouses as $wh)
                            <option value="{{ $wh->id }}" {{ (string) old('from_warehouse_id') === (string) $wh->id ? 'selected' : '' }}>
                                {{ $wh->code }} — {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Ke Kendaraan (Tujuan) <span class="required">*</span></label>
                    <select name="vehicle_id" class="form-input" required>
                        <option value="">Pilih Kendaraan/Tim Pasgar</option>
                        @foreach($vehicles as $vh)
                            @if($vh->warehouse)
                                <option value="{{ $vh->id }}" {{ (string) old('vehicle_id') === (string) $vh->id ? 'selected' : '' }}>
                                    {{ $vh->license_plate }} ({{ $vh->warehouse->name }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top: 1rem;">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="notes" class="form-input" rows="2" placeholder="Contoh: Persiapan loading untuk rute pagi">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    <div class="panel" style="max-width: 980px; margin-top: 1rem;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Daftar Barang (Item)</div>
                <div class="panel-subtitle">Tambahkan minimal 1 barang untuk dibuatkan permintaan loading</div>
            </div>
            <div class="page-header-actions" style="margin:0;">
                <button type="button" class="btn-secondary" onclick="addItemRow()">➕ Tambah Barang</button>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-wrapper">
                <table class="data-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 46%;">Produk</th>
                            <th style="width: 18%;">Qty</th>
                            <th>Catatan</th>
                            <th style="text-align:right;width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;margin-top:1rem;">
                <a href="{{ route('pasgar.loadings.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">📦 Buat Permintaan Loading</button>
            </div>
        </div>
    </div>
</form>

<template id="productTemplate">
    <option value="">Pilih Produk</option>
    @foreach($products as $p)
        <option value="{{ $p->id }}">{{ $p->code }} — {{ $p->name }} ({{ $p->unit->name ?? '-' }})</option>
    @endforeach
</template>

<template id="rowTemplate">
    <tr>
        <td>
            <select class="form-input js-product" required></select>
        </td>
        <td>
            <input type="number" class="form-input js-qty" value="1" min="1" required>
        </td>
        <td>
            <input type="text" class="form-input js-notes" placeholder="Contoh: Dus utuh">
        </td>
        <td style="text-align:right;">
            <button type="button" class="btn-danger js-remove">Hapus</button>
        </td>
    </tr>
</template>

<script>
    let itemIndex = 0;

    function addItemRow() {
        const tbody = document.getElementById('itemsBody');
        const rowTpl = document.getElementById('rowTemplate').content.cloneNode(true);
        const tr = rowTpl.querySelector('tr');

        const select = tr.querySelector('.js-product');
        select.name = `items[${itemIndex}][product_id]`;
        select.innerHTML = document.getElementById('productTemplate').innerHTML;

        const qty = tr.querySelector('.js-qty');
        qty.name = `items[${itemIndex}][quantity]`;

        const notes = tr.querySelector('.js-notes');
        notes.name = `items[${itemIndex}][notes]`;

        tr.querySelector('.js-remove').addEventListener('click', function () {
            tr.remove();
        });

        tbody.appendChild(tr);
        itemIndex++;
    }

    document.addEventListener('DOMContentLoaded', () => {
        addItemRow();
    });
</script>
</x-app-layout>
