<x-app-layout>
    <x-slot name="header">Import Produk Massal</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER ─── --}}
            <div class="tr-header animate-fade-in">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Inventori</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </div>
                        Import Data via CSV / XLSX
                    </h1>
                    <p class="tr-subtitle">Tambahkan ratusan produk sekaligus dengan mengunggah file CSV atau XLSX dari Excel atau Google Sheets.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('products.index') }}" class="tr-btn tr-btn-ghost">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Kembali
                    </a>
                    <a href="{{ route('products.template') }}" class="tr-btn tr-btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Unduh Template CSV
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            <div class="tr-alert-stack animate-fade-in-up">
                @if(session('success')) 
                    <div class="tr-alert tr-alert-success">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        {{ session('success') }}
                    </div> 
                @endif
                
                @if(session('error')) 
                    <div class="tr-alert tr-alert-danger">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        {{ session('error') }}
                    </div> 
                @endif

                @if(session('import_summary'))
                    @php $s = session('import_summary'); @endphp
                    <div class="tr-alert tr-alert-success">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span><strong>Import Selesai!</strong> Berhasil tambah: <strong>{{ $s['created'] ?? 0 }}</strong>, Diperbarui: <strong>{{ $s['updated'] ?? 0 }}</strong>, Dilewati: <strong>{{ $s['skipped'] ?? 0 }}</strong>, Gagal: <strong>{{ $s['errors'] ?? 0 }}</strong>.</span>
                    </div>
                @endif

                @if(session('import_errors') && is_array(session('import_errors')))
                    <div class="tr-alert tr-alert-danger tr-alert-block">
                        <div class="alert-header">
                            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <strong>Beberapa baris gagal diproses:</strong>
                        </div>
                        <ul>
                            @foreach(session('import_errors') as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                @if($errors->any())
                    <div class="tr-alert tr-alert-danger tr-alert-block">
                        <div class="alert-header">
                            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                            <strong>Periksa kembali input Anda:</strong>
                        </div>
                        <ul>
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- ─── MAIN LAYOUT ─── --}}
            <div class="tr-import-grid animate-fade-in-up" style="animation-delay: 0.1s;">
                
                {{-- KIRI: Area Upload File --}}
                <div class="tr-left-panel">
                    <form method="POST" action="{{ route('products.import.process') }}" enctype="multipart/form-data" class="tr-upload-form">
                        @csrf
                        
                        <div class="tr-card">
                            <div class="tr-card-header">
                                <h2 class="tr-section-title">Upload Dokumen CSV / XLSX</h2>
                            </div>
                            <div class="tr-card-body">
                                
                                {{-- Dropzone --}}
                                <div class="tr-form-group">
                                    <input id="csvFile" type="file" name="file" accept=".csv,.xlsx,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="hidden-input">
                                    <label for="csvFile" class="tr-dropzone" id="dropzoneArea">
                                        <div class="dz-icon">
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><path d="M14 8h3"></path><path d="M14 12h3"></path><path d="M14 16h3"></path></svg>
                                        </div>
                                        <h3 class="dz-title">Klik untuk memilih file CSV / XLSX</h3>
                                        <p class="dz-sub">Maksimal ukuran file 20MB. Untuk CSV, pemisah kolom bisa koma (,) atau titik koma (;).</p>
                                        
                                        <div id="filePill" class="dz-file-pill" style="display:none;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            <span id="fileName" class="dz-file-name"></span>
                                        </div>
                                    </label>
                                </div>

                                {{-- Mode Select --}}
                                <div class="tr-form-group" style="margin-top: 1.5rem;">
                                    <label class="tr-label">Mode Import <span class="tr-req">*</span></label>
                                    <div class="tr-select-wrapper">
                                        <select name="mode" class="tr-select">
                                            <option value="upsert_by_sku" selected>Update data jika SKU sama, jika beda Tambah Baru</option>
                                            <option value="create_only">Hanya Tambah Baru (Abaikan SKU yang sudah ada)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Footer Aksi --}}
                            <div class="tr-card-footer">
                                <button type="submit" class="tr-btn tr-btn-primary tr-btn-block">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.59-9.21L21.5 8"></path></svg>
                                    Mulai Proses Import
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- KANAN: Panduan Format (Guideline) --}}
                <div class="tr-right-panel">
                    <div class="tr-info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            </div>
                            <h3 class="info-title">Panduan Format CSV / XLSX</h3>
                        </div>
                        
                        <div class="info-content">
                            <div class="tr-kv-list">
                                <div class="kv-item">
                                    <span class="kv-key">Kolom Wajib</span>
                                    <span class="kv-val text-main font-mono">name, category, price</span>
                                </div>
                                <div class="kv-item">
                                    <span class="kv-key">Kolom Opsional</span>
                                    <span class="kv-val text-muted font-mono">unit, sku, barcode, purchase_price, stock, min_stock, description</span>
                                </div>
                                <div class="kv-item">
                                    <span class="kv-key">SKU Kosong?</span>
                                    <span class="kv-val text-muted">Sistem akan membuat SKU otomatis.</span>
                                </div>
                                <div class="kv-item">
                                    <span class="kv-key">Kategori Baru?</span>
                                    <span class="kv-val text-muted">Kategori akan dibuat otomatis bila belum ada di sistem.</span>
                                </div>
                                <div class="kv-item">
                                    <span class="kv-key">Format Angka</span>
                                    <span class="kv-val text-muted">Harga bisa pakai format Indonesia (cth: 15.000) atau angka polos (cth: 15000).</span>
                                </div>
                            </div>

                            <div class="tr-divider"></div>

                            <h4 class="mini-subtitle">Contoh Header Tabel (Baris ke-1)</h4>
                            <div class="code-block">name;category;unit;sku;price;stock</div>

                            <h4 class="mini-subtitle" style="margin-top: 1rem;">Contoh Isi Data</h4>
                            <div class="mini-table-wrapper">
                                <table class="tr-mini-table">
                                    <thead>
                                        <tr>
                                            <th>name</th>
                                            <th>category</th>
                                            <th>unit</th>
                                            <th>price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-bold">Indomie Goreng</td>
                                            <td>Sembako</td>
                                            <td>pcs</td>
                                            <td class="font-mono">3500</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold">Gula Pasir</td>
                                            <td>Sembako</td>
                                            <td>kg</td>
                                            <td class="font-mono">16000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-indigo: #4f46e5; --tr-indigo-hover: #4338ca; --tr-indigo-light: #e0e7ff;
            --tr-emerald: #10b981; --tr-emerald-light: #dcfce7;
            --tr-danger: #ef4444; --tr-danger-light: #fef2f2;
            --tr-bg: #f8fafc; --tr-surface: #ffffff; --tr-border: #e2e8f0;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 4rem; }
        .tr-page { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── ANIMATIONS ── */
        .animate-fade-in { animation: fadeIn 0.4s ease forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.4s ease forwards; opacity: 0; transform: translateY(15px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-indigo); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.625rem; font-weight: 900; margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin-top: 6px; line-height: 1.5; max-width: 500px;}
        .tr-header-actions { display: flex; gap: 0.75rem; }

        /* ── ALERTS ── */
        .tr-alert-stack { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
        .tr-alert { padding: 1rem 1.25rem; border-radius: 12px; display: flex; align-items: center; gap: 12px; font-size: 0.9rem; font-weight: 600; line-height: 1.4; }
        .tr-alert-block { align-items: flex-start; flex-direction: column; gap: 8px; }
        .alert-header { display: flex; align-items: center; gap: 8px; }
        .tr-alert ul { margin: 0; padding-left: 28px; font-weight: 500; font-size: 0.85rem;}
        .tr-alert-success { background: var(--tr-emerald-light); color: #065f46; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background: var(--tr-danger-light); color: #991b1b; border: 1px solid #fecaca; }
        .alert-icon { width: 20px; height: 20px; flex-shrink: 0; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.75rem 1.5rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; white-space: nowrap; }
        .tr-btn-primary { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover { background: var(--tr-indigo-hover); transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f1f5f9; border-color: var(--tr-text-muted); }
        .tr-btn-ghost { background: transparent; color: var(--tr-text-muted); }
        .tr-btn-ghost:hover { color: var(--tr-text-main); background: #f1f5f9;}
        .tr-btn-block { width: 100%; }

        /* ── LAYOUT GRID ── */
        .tr-import-grid { display: grid; grid-template-columns: 1fr 400px; gap: 1.5rem; align-items: start; }

        /* ── CARD & FORM (LEFT) ── */
        .tr-upload-form { margin: 0; }
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
        .tr-card-header { padding: 1.5rem 1.75rem; border-bottom: 1px solid #f1f5f9; background: #fafafa; }
        .tr-section-title { font-size: 1.1rem; font-weight: 800; margin: 0; color: var(--tr-text-main); }
        .tr-card-body { padding: 1.75rem; }
        .tr-card-footer { padding: 1.25rem 1.75rem; border-top: 1px solid #f1f5f9; background: #fafafa; }

        .hidden-input { position: absolute; left: -9999px; width: 1px; height: 1px; opacity: 0; overflow: hidden; }
        
        /* Dropzone */
        .tr-dropzone { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 8px; padding: 3rem 2rem; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; cursor: pointer; transition: 0.2s ease; position: relative; }
        .tr-dropzone:hover, .tr-dropzone.dragover { border-color: var(--tr-indigo); background: var(--tr-indigo-light); }
        .dz-icon { color: var(--tr-indigo); margin-bottom: 0.5rem; }
        .dz-title { margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--tr-text-main); }
        .dz-sub { margin: 0; font-size: 0.85rem; color: var(--tr-text-muted); max-width: 300px; line-height: 1.5;}
        
        /* File Pill Badge */
        .dz-file-pill { display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; border-radius: 99px; background: var(--tr-surface); border: 1px solid var(--tr-indigo); color: var(--tr-indigo); font-weight: 700; font-size: 0.85rem; margin-top: 1rem; box-shadow: 0 4px 6px -1px rgba(79,70,229,0.1); }
        .dz-file-name { max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Form Inputs */
        .tr-form-group { display: flex; flex-direction: column; gap: 6px; }
        .tr-label { font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--tr-text-muted); letter-spacing: 0.05em; }
        .tr-req { color: var(--tr-danger); }
        
        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding: 0.85rem 1rem; border: 1.5px solid var(--tr-border); border-radius: 10px; font-size: 0.95rem; background: #fff; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 600; width: 100%; outline: none; cursor: pointer; }
        .tr-select:focus { border-color: var(--tr-indigo); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

        /* ── INFO CARD (RIGHT) ── */
        .tr-info-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); overflow: hidden; }
        .info-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; background: #fafafa; }
        .info-icon { color: var(--tr-indigo); display: flex; }
        .info-title { margin: 0; font-size: 1rem; font-weight: 800; color: var(--tr-text-main); }
        .info-content { padding: 1.5rem; }

        .tr-kv-list { display: flex; flex-direction: column; gap: 1rem; }
        .kv-item { display: flex; flex-direction: column; gap: 2px; }
        .kv-key { font-size: 0.75rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; }
        .kv-val { font-size: 0.85rem; line-height: 1.4; }

        .tr-divider { height: 1px; background: var(--tr-border); margin: 1.5rem 0; }

        .mini-subtitle { font-size: 0.85rem; font-weight: 800; margin: 0 0 8px 0; color: var(--tr-text-main); }
        .code-block { font-family: ui-monospace, monospace; font-size: 0.8rem; background: #0f172a; color: #38bdf8; padding: 0.85rem 1rem; border-radius: 8px; overflow-x: auto; white-space: nowrap; }

        .mini-table-wrapper { border: 1px solid var(--tr-border); border-radius: 8px; overflow: hidden; }
        .tr-mini-table { width: 100%; border-collapse: collapse; text-align: left; }
        .tr-mini-table th { background: #f8fafc; padding: 0.5rem 0.75rem; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-mini-table td { padding: 0.6rem 0.75rem; font-size: 0.8rem; border-bottom: 1px solid #f1f5f9; color: var(--tr-text-main); }
        .tr-mini-table tr:last-child td { border-bottom: none; }

        /* ── UTILS ── */
        .text-main { color: var(--tr-text-main); }
        .text-muted { color: var(--tr-text-muted); }
        .font-mono { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; }
        .font-bold { font-weight: 700; }

        /* ── RESPONSIVE ── */
        @media (max-width: 992px) {
            .tr-import-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-header-actions { flex-direction: column; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-dropzone { padding: 2rem 1rem; }
            .dz-title { font-size: 1rem; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('csvFile');
            const dropzone = document.getElementById('dropzoneArea');
            const pill = document.getElementById('filePill');
            const name = document.getElementById('fileName');

            if (!input || !dropzone || !pill || !name) return;

            // Highlight dropzone on drag over
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
                dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'), false);
            });

            // Remove highlight on drag leave
            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
                dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'), false);
            });

            // Handle dropped files
            dropzone.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files; // Assign files to input
                updateFileUI();
            }

            // Handle standard click to select file
            input.addEventListener('change', updateFileUI);

            function updateFileUI() {
                const f = input.files && input.files[0] ? input.files[0] : null;
                if (!f) {
                    pill.style.display = 'none';
                    name.textContent = '';
                    dropzone.style.borderColor = '#cbd5e1';
                    dropzone.style.backgroundColor = '#f8fafc';
                    return;
                }
                
                // Show pill and update text
                pill.style.display = 'inline-flex';
                name.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
                
                // Visual feedback that file is selected
                dropzone.style.borderColor = 'var(--tr-indigo)';
                dropzone.style.backgroundColor = 'var(--tr-indigo-light)';
            }
        });
    </script>
    @endpush
</x-app-layout>
