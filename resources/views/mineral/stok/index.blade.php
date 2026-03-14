<x-app-layout>
    <x-slot name="header">
        <div class="ph">
            <div class="ph-left">
                <div class="ph-icon emerald">📦</div>
                <div>
                    <div class="ph-breadcrumb">
                        <a href="{{ route('mineral.dashboard') }}">Mineral</a>
                        <span class="ph-breadcrumb-sep">/</span>
                        <span>Stok Gudang</span>
                    </div>
                    <h2 class="ph-title">Gudang Air Mineral</h2>
                    <p class="ph-subtitle">Pemantauan stok fisik 3 jenis SKU Mineral & Catatan Penerimaan Pabrik / Barang Hancur.</p>
                </div>
            </div>
            <div class="ph-actions">
                <button onclick="document.getElementById('modal-tambah').style.display='flex'" class="btn-primary">
                    <span style="font-size:1.1rem">+</span> Input Mutasi Baru
                </button>
            </div>
        </div>
    </x-slot>

    <!-- KARTU SISA STOK -->
    <div class="grid-3 mb-3">
        @foreach($products as $prod)
            @php 
                $st = $prod->warehouseStocks->first();
                $qty = $st ? $st->qty_dus : 0;
            @endphp
            <div class="stat-card">
                <div class="stat-card-row">
                    <div class="stat-icon emerald">📦</div>
                    <div class="stat-trend neutral">{{ $prod->name }}</div>
                </div>
                <div class="mt-2">
                    <div class="stat-label">Stok On-Hand Gudang</div>
                    <div class="stat-value emerald">
                        @if(($maskStock ?? false) === true)
                            Terkunci
                        @else
                            {{ number_format($qty, 0, ',', '.') }}
                        @endif
                        <span style="font-size:1rem;color:#64748b;">Dus</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- TABEL RIWAYAT MUTASI -->
    <div class="card p-0 mb-3">
        <div class="form-card-header">
            <div class="form-card-icon indigo">↻</div>
            <div>
                <h3 class="form-card-title">Riwayat Mutasi Barang</h3>
                <p class="form-card-subtitle">Log Kedatangan Truk Pabrik & Catatan Barang Rusak/Keluar</p>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="15%">Waktu</th>
                        <th width="20%">Produk</th>
                        <th width="15%">Jenis Mutasi</th>
                        <th width="10%">Qty (Dus)</th>
                        <th width="15%">Admin</th>
                        <th width="25%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mutations as $mut)
                        <tr>
                            <td>
                                <div class="td-main">{{ $mut->created_at->format('d/m/Y') }}</div>
                                <div class="td-sub">{{ $mut->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="td-main">{{ $mut->product->name }}</div>
                            </td>
                            <td>
                                @if($mut->type == 'in')
                                    <span class="badge badge-success">Masuk Pabrik</span>
                                @elseif($mut->type == 'in_return')
                                    <span class="badge badge-indigo">Retur Armada</span>
                                @elseif($mut->type == 'out_loading')
                                    <span class="badge badge-warning">Di-Loading</span>
                                @else
                                    <span class="badge badge-danger">Rusak/Hancur</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-bold {{ in_array($mut->type, ['in','in_return']) ? 'text-green' : 'text-red' }}">
                                    {{ in_array($mut->type, ['in','in_return']) ? '+' : '-' }}{{ $mut->qty_dus }}
                                </div>
                            </td>
                            <td><div class="td-sub">{{ $mut->user->name ?? '-' }}</div></td>
                            <td><div class="td-sub">{{ $mut->notes ?? '-' }}</div></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <span class="empty-state-icon">📄</span>
                                <div class="empty-state-title">Belum ada riwayat mutasi</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-2 border-t border-gray-100">
            {{ $mutations->links() }}
        </div>
    </div>

    <!-- Modal Input Mutasi -->
    <div id="modal-tambah" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(15,23,42,0.6); z-index:999; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
        <div class="card" style="width:100%; max-width:500px; margin:1rem; animation:fadeSlideIn 0.3s ease;">
            <div class="form-card-header" style="justify-content:space-between">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div class="form-card-icon indigo">+</div>
                    <div class="form-card-title">Input Mutasi Mineral Baru</div>
                </div>
                <button type="button" onclick="document.getElementById('modal-tambah').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#94a3b8;">&times;</button>
            </div>
            <form action="{{ route('mineral.stok.store') }}" method="POST">
                @csrf
                <div class="form-card-body">
                    <div class="form-group">
                        <label class="form-label">Jenis Produk <span class="required">*</span></label>
                        <select name="product_id" class="form-input" required>
                            <option value="">-- Pilih Air Mineral --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-row mt-2">
                        <div class="form-group">
                            <label class="form-label">Tipe Mutasi <span class="required">*</span></label>
                            <select name="type" class="form-input" required>
                                <option value="in">Terima Barang (Masuk)</option>
                                <option value="out_damage">Barang Rusak / Hilang (Keluar)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah (Dus) <span class="required">*</span></label>
                            <input type="number" name="qty_dus" class="form-input" min="1" placeholder="Cth: 100" required>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="notes" class="form-input" placeholder="Misal: Truk dari Pabrik Alham Nopol B 1234 XY" rows="2"></textarea>
                    </div>
                </div>
                <div class="floating-bar sticky" style="justify-content:flex-end; padding:1rem 1.375rem; background:#f8fafc;">
                    <button type="button" class="btn-secondary" onclick="document.getElementById('modal-tambah').style.display='none'">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Mutasi</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
