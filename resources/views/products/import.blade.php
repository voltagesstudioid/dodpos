<x-app-layout>
    <x-slot name="header">Import Produk (CSV)</x-slot>
    <style>
        .import-page{max-width:min(1200px,100%);margin:0 auto;padding:0 1rem 4rem;}
        .import-grid{display:grid;grid-template-columns:1.15fr 0.85fr;gap:1rem;align-items:start;}
        .dropzone{display:flex;flex-direction:column;gap:0.35rem;border:1px dashed #cbd5e1;border-radius:14px;padding:1rem;background:linear-gradient(180deg,#ffffff 0%,#f8fafc 100%);cursor:pointer;transition:all .15s ease;}
        .dropzone:hover{border-color:#6366f1;box-shadow:0 8px 24px rgba(99,102,241,0.12);}
        .dropzone-title{font-weight:900;color:#0f172a;font-size:0.9rem;}
        .dropzone-sub{color:#64748b;font-size:0.78rem;}
        .file-pill{display:inline-flex;align-items:center;gap:0.4rem;padding:0.35rem 0.6rem;border-radius:999px;background:#eef2ff;color:#3730a3;font-weight:800;font-size:0.75rem;max-width:100%;}
        .file-pill span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:520px;}
        .info-card{padding:1rem;border:1px solid #e2e8f0;border-radius:14px;background:#ffffff;}
        .info-title{font-weight:900;color:#0f172a;margin-bottom:0.5rem;}
        .kv{display:grid;grid-template-columns:140px 1fr;gap:0.4rem 0.75rem;font-size:0.82rem;}
        .kv b{color:#334155;}
        .csv-head{font-family:monospace;font-size:0.8rem;background:#0f172a;color:#e2e8f0;padding:0.75rem;border-radius:12px;overflow:auto;}
        .mini-table{width:100%;border-collapse:separate;border-spacing:0;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;}
        .mini-table th,.mini-table td{padding:0.5rem 0.6rem;border-bottom:1px solid #f1f5f9;font-size:0.78rem;text-align:left;}
        .mini-table th{background:#f8fafc;color:#334155;font-weight:900;}
        .mini-table tr:last-child td{border-bottom:none;}
        .actions-row{display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;}
        @media (max-width: 980px){.import-grid{grid-template-columns:1fr;}}
        @media (max-width: 520px){.import-page{padding:0 0.75rem 4rem;}.kv{grid-template-columns:1fr;}.file-pill span{max-width:260px;}}
    </style>
    <div class="page-container import-page">
        <div class="ph animate-in">
            <div class="ph-left">
                <div class="ph-icon indigo">📥</div>
                <div>
                    <h1 class="ph-title">Import Produk</h1>
                    <p class="ph-subtitle">Unggah CSV dari Excel/Google Sheets. Sistem bisa membaca pemisah koma atau titik koma.</p>
                </div>
            </div>
            <div class="ph-actions">
                <a href="{{ route('products.template') }}" class="btn-secondary">⬇️ Unduh Template CSV</a>
                <a href="{{ route('products.index') }}" class="btn-secondary">← Kembali</a>
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div> @endif
        @if(session('import_summary'))
            @php $s = session('import_summary'); @endphp
            <div class="alert alert-success" role="alert">
                ✅ Import selesai. Tambah: {{ $s['created'] ?? 0 }}, Update: {{ $s['updated'] ?? 0 }}, Lewat: {{ $s['skipped'] ?? 0 }}, Gagal: {{ $s['errors'] ?? 0 }}.
            </div>
        @endif
        @if(session('import_errors') && is_array(session('import_errors')))
            <div class="alert alert-danger" role="alert">
                <div style="font-weight:800;">Beberapa baris gagal diproses:</div>
                <ul style="margin:0.5rem 0 0 1rem;">
                    @foreach(session('import_errors') as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <div>❌ Periksa input:</div>
            <ul style="margin:0.5rem 0 0 1rem;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div class="import-grid animate-in animate-in-delay-1">
            <div class="panel">
                <form method="POST" action="{{ route('products.import.process') }}" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:1rem;">
                    @csrf

                    <div>
                        <label class="form-label">File CSV</label>
                        <input id="csvFile" type="file" name="file" accept=".csv,text/csv" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;">
                        <label for="csvFile" class="dropzone">
                            <div class="dropzone-title">Klik untuk pilih file, atau drag & drop</div>
                            <div class="dropzone-sub">Maks 20MB. Pemisah bisa koma (,) atau titik koma (;).</div>
                            <div id="filePill" class="file-pill" style="display:none;">
                                <div>📄</div>
                                <span id="fileName"></span>
                            </div>
                        </label>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Mode</label>
                            <select name="mode" class="form-input">
                                <option value="upsert_by_sku" selected>Perbarui jika SKU sudah ada, selain itu tambah baru</option>
                                <option value="create_only">Hanya tambah baru, abaikan SKU yang sama</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tips</label>
                            <div class="card" style="padding:0.75rem; background:#f8fafc;">
                                <div style="font-size:0.78rem;color:#64748b;">Simpan sebagai CSV dari Excel. Jika angka harga muncul 10.000, sistem akan merapikan otomatis.</div>
                            </div>
                        </div>
                    </div>

                    <div class="actions-row">
                        <button type="submit" class="btn-primary">🚀 Mulai Import</button>
                        <a href="{{ route('products.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </form>
            </div>

            <div class="info-card">
                <div class="info-title">Format CSV</div>
                <div class="kv">
                    <b>Wajib</b><div>name, category, price</div>
                    <b>Opsional</b><div>unit, sku, barcode, purchase_price, stock, min_stock, description</div>
                    <b>SKU kosong</b><div>Dibuat otomatis oleh sistem</div>
                    <b>Kategori baru</b><div>Akan dibuat otomatis bila belum ada</div>
                </div>

                <div style="height:0.75rem;"></div>

                <div style="font-weight:900;color:#0f172a;margin-bottom:0.35rem;">Header contoh</div>
                <div class="csv-head">name;category;unit;sku;barcode;price;purchase_price;stock;min_stock;description</div>

                <div style="height:0.75rem;"></div>

                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>name</th>
                            <th>category</th>
                            <th>unit</th>
                            <th>price</th>
                            <th>stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Indomie Goreng</td>
                            <td>Sembako</td>
                            <td>pcs</td>
                            <td>3500</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Gula Pasir</td>
                            <td>Sembako</td>
                            <td>kg</td>
                            <td>16000</td>
                            <td>10</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        (function () {
            const input = document.getElementById('csvFile');
            const pill = document.getElementById('filePill');
            const name = document.getElementById('fileName');
            if (!input || !pill || !name) return;
            input.addEventListener('change', function () {
                const f = input.files && input.files[0] ? input.files[0] : null;
                if (!f) {
                    pill.style.display = 'none';
                    name.textContent = '';
                    return;
                }
                pill.style.display = 'inline-flex';
                name.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
            });
        })();
    </script>
</x-app-layout>
