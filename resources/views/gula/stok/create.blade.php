<x-app-layout>
    <x-slot name="header">
        Tambah Master Produk Gula
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <span class="fs-4">➕</span> Form Data Gula Baru
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('gula.stok.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Informasi Produk</h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Produk Gula/Merek</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Gula Pasir Kuning" required>
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kategori Dasar</label>
                                    <select name="type" class="form-select" required>
                                        <option value="karungan">Karungan (Grosir)</option>
                                        <option value="eceran">Eceran (Hanya Bungkus)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Isi 1 Karung (Qty Eceran)</label>
                                    <div class="input-group">
                                        <input type="number" name="qty_per_karung" class="form-control" value="50" min="1" required>
                                        <span class="input-group-text">Bks (1kg)</span>
                                    </div>
                                    <small class="text-muted">Jika dibongkar, 1 Karung menjadi berapa bungkus.</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Tiering Harga (Rp)</h6>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Harga Beli / Dasar Utama</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="base_price" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Harga Jual (Jika Beli per Karung)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price_karungan" class="form-control" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Harga Jual (Jika Beli Eceran/Bungkus)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price_eceran" class="form-control" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Inisialisasi Stok Awal Gudang Utama</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary">Saldo Awal Karung</label>
                                    <div class="input-group">
                                        <input type="number" name="initial_qty_karung" class="form-control" value="0" min="0">
                                        <span class="input-group-text bg-primary text-white border-primary">Karung</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-success">Saldo Awal Eceran</label>
                                    <div class="input-group">
                                        <input type="number" name="initial_qty_eceran" class="form-control" value="0" min="0">
                                        <span class="input-group-text bg-success text-white border-success">Bungkus</span>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3 py-2 px-3 small border-0 bg-info bg-opacity-10 d-flex align-items-center gap-2">
                                <span class="fs-5">ℹ️</span> Masukkan stok fisik yang ada di gudang saat ini. Bisa dikosongkan (0) jika mau ditambah nanti via Sistem Penerimaan/Opname.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('gula.stok.index') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
