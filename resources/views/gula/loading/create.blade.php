<x-app-layout>
    <x-slot name="header">Surat Jalan & Loading Armada</x-slot>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <span class="fs-4">🚚</span> Form Mutasi Stok (Loading Truk Gula)
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Buat Surat Jalan untuk memindahkan stok Gudang ke muatan Armada pick-up Sales secara otomatis.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('gula.loading.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tanggal Berangkat</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Armada (Truk / Pikap)</label>
                                <select name="vehicle_id" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Armada Kendaraan --</option>
                                    @foreach($vehicles as $kendaraan)
                                        <option value="{{ $kendaraan->id }}">{{ $kendaraan->license_plate }} ({{ $kendaraan->type }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Sales / Driver Gula Target</label>
                                <select name="sales_id" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Penanggung Jawab Truk --</option>
                                    @foreach($sales as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ strtoupper($user->role) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Rincian Muatan Barang (Item)</h6>
                            <div class="alert alert-info py-2 mb-3 small border-0 bg-info bg-opacity-10 d-flex align-items-start gap-2">
                                <span class="fs-5 lh-1">ℹ️</span> 
                                <div>Stok Gula Gudang Induk saat ini telah tersedia di dalam tanda kurung (). Jika kolom muatan Karung / Eceran dikosongkan (0), berarti produk tersebut tidak dimuat.</div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="bg-light text-center small text-uppercase">
                                        <tr>
                                            <th class="py-3" style="width: 45%;">Nama Master Gula</th>
                                            <th class="py-3 text-primary" style="width: 25%;">Bawa Karungan</th>
                                            <th class="py-3 text-success" style="width: 25%;">Bawa Eceran (Bks)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $idx => $product)
                                            @php 
                                                $stock = $product->warehouseStocks->first();
                                                $s_karung = $stock ? $stock->qty_karung : 0;
                                                $s_eceran = $stock ? $stock->qty_eceran : 0;
                                            @endphp
                                            <tr>
                                                <td class="align-middle">
                                                    <input type="hidden" name="products[{{ $idx }}][id]" value="{{ $product->id }}">
                                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                                    <div class="text-muted small mt-1">
                                                        Stok Gudang: 
                                                        <span class="badge bg-primary rounded-pill px-2">📦 {{ $s_karung }}</span> Krg,
                                                        <span class="badge bg-success rounded-pill px-2">🍬 {{ $s_eceran }}</span> Bks
                                                    </div>
                                                </td>
                                                <td class="align-middle px-3">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" name="products[{{ $idx }}][qty_karung]" class="form-control text-center text-primary fw-bold" min="0" max="{{ $s_karung }}" placeholder="0">
                                                        <span class="input-group-text bg-light text-muted">Krg</span>
                                                    </div>
                                                </td>
                                                <td class="align-middle px-3">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" name="products[{{ $idx }}][qty_eceran]" class="form-control text-center text-success fw-bold" min="0" max="{{ $s_eceran }}" placeholder="0">
                                                        <span class="input-group-text bg-light text-muted">Bks</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Catatan Khusus (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Titipan spanduk promo atau kardus kosong"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('gula.loading.index') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm" onclick="return confirm('Kirim mutasi stok gudang ke armada Sales ini?');">🚀 Proses Loading Armada</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
