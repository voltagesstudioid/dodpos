<x-app-layout>
    <x-slot name="header">Form Repacking Gula</x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <span class="fs-4">✂️</span> Proses Pembongkaran Karung
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Sistem akan otomatis mengkonversi Karungan menjadi Eceran di Gudang Utama berdasarkan Rasio Master Produk.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('gula.repacking.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Proses</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilih Produk (Master Gula)</label>
                                <select name="gula_product_id" id="gula_product_id" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Produk Gula --</option>
                                    @foreach($products as $product)
                                        @php $stockKarung = $product->warehouseStocks->first()->qty_karung ?? 0; @endphp
                                        <option value="{{ $product->id }}" data-konversi="{{ $product->qty_per_karung }}" data-stok="{{ ($maskStock ?? false) ? 0 : $stockKarung }}">
                                            {{ $product->name }} (Isi 1 Krg = {{ $product->qty_per_karung }} Eceran) - Stok: {{ ($maskStock ?? false) ? 'Terkunci' : $stockKarung }} Krg
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="bg-light p-4 rounded-3 mb-4 border border-1">
                            <h6 class="fw-bold text-dark mb-3">Detail Kuantitas</h6>
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label text-danger fw-semibold">Jumlah Karung Dibongkar</label>
                                    <div class="input-group">
                                        <input type="number" name="qty_karung_dibongkar" id="qty_karung" class="form-control fw-bold text-center" min="1" required>
                                        <span class="input-group-text bg-danger text-white border-danger">Karung</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">Akan dikurangi dari Gudang</small>
                                </div>
                                
                                <div class="col-md-2 d-flex justify-content-center align-items-center mt-4 pt-2">
                                    <span class="fs-3 text-muted">➡️</span>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label text-success fw-semibold">Estimasi Eceran (Bungkus)</label>
                                    <div class="input-group">
                                        <input type="number" id="estimasi_eceran" class="form-control bg-white fw-bold text-center" readonly>
                                        <span class="input-group-text bg-success text-white border-success">Bks</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">Otomatis ditambahkan ke Gudang</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Pencatatan Susut (Loss) / Barang Rusak</h6>
                            <div class="alert alert-warning py-2 mb-3 small border-0 bg-warning bg-opacity-10 d-flex align-items-center gap-2">
                                <span class="fs-5">⚠️</span> 
                                Isi bagian ini JIKA ADA eceran gula yang hilang/tumpah saat dibongkar. Jumlah Susut akan memotong hasil Estimasi Eceran di atas.
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-warning">Gula Susut / Hilang</label>
                                    <div class="input-group">
                                        <input type="number" name="loss_qty_eceran" id="loss_qty" class="form-control" value="0" min="0">
                                        <span class="input-group-text">Bks</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Alasan Susut</label>
                                    <input type="text" name="notes" class="form-control" placeholder="Contoh: Digigit tikus / tumpah saat disendok..">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('gula.repacking.index') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm" onclick="return confirm('Proses bongkar karung sekarang?');">🛠️ Proses Repacking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maskStock = {{ ($maskStock ?? false) ? 'true' : 'false' }};
            const selectProduct = document.getElementById('gula_product_id');
            const inputKarung = document.getElementById('qty_karung');
            const estimasiEceran = document.getElementById('estimasi_eceran');
            const lossQty = document.getElementById('loss_qty');

            function hitungKonversi() {
                if(!selectProduct.value || !inputKarung.value) {
                    estimasiEceran.value = 0;
                    return;
                }
                
                // Ambil rasio konversi dr option tag (data-konversi)
                const selectedOption = selectProduct.options[selectProduct.selectedIndex];
                const konversiRatio = parseInt(selectedOption.getAttribute('data-konversi')) || 0;
                const maxStok = parseFloat(selectedOption.getAttribute('data-stok')) || 0;
                
                const jlhKarung = parseFloat(inputKarung.value) || 0;

                // Validate maks stok
                if(!maskStock && jlhKarung > maxStok) {
                    alert('Peringatan: Jumlah karung melebihi stok yang ada di gudang!');
                }

                const totalEstimasi = (jlhKarung * konversiRatio);
                const totalLoss = parseFloat(lossQty.value) || 0;

                estimasiEceran.value = Math.max(0, totalEstimasi - totalLoss);
            }

            selectProduct.addEventListener('change', hitungKonversi);
            inputKarung.addEventListener('input', hitungKonversi);
            lossQty.addEventListener('input', hitungKonversi);
        });
    </script>
    @endpush
</x-app-layout>
