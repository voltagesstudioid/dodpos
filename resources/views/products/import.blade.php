<x-app-layout>
    <x-slot name="header">Import Produk Massal</x-slot>

    <div class="imp-wrap">
        <div class="imp-container">

            {{-- ─── HEADER ─── --}}
            <div class="imp-header">
                <div class="imp-header-left">
                    <div class="imp-icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="imp-title">Import Produk Massal</h1>
                        <p class="imp-subtitle">Unggah file CSV atau XLSX untuk menambahkan atau memperbarui ratusan produk sekaligus.</p>
                    </div>
                </div>
                <div class="imp-header-actions">
                    <a href="{{ route('products.index') }}" class="imp-btn imp-btn-ghost">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('products.template') }}" class="imp-btn imp-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        Unduh Template
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            <div class="imp-alerts">
                @if(session('success'))
                    <div class="imp-alert imp-alert-success">
                        <svg class="imp-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="imp-alert imp-alert-danger">
                        <svg class="imp-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('import_summary'))
                    @php $s = session('import_summary'); @endphp
                    <div class="imp-alert imp-alert-success">
                        <svg class="imp-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <div>
                            <strong>Import Selesai!</strong>
                            <div class="imp-summary-grid">
                                <span class="imp-summary-item">✓ Ditambah: <strong>{{ $s['created'] ?? 0 }}</strong></span>
                                <span class="imp-summary-item">↻ Diperbarui: <strong>{{ $s['updated'] ?? 0 }}</strong></span>
                                <span class="imp-summary-item">⊘ Dilewati: <strong>{{ $s['skipped'] ?? 0 }}</strong></span>
                                <span class="imp-summary-item imp-summary-error">✗ Gagal: <strong>{{ $s['errors'] ?? 0 }}</strong></span>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('import_errors') && is_array(session('import_errors')))
                    <div class="imp-alert imp-alert-danger imp-alert-block">
                        <div class="imp-alert-header">
                            <svg class="imp-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <strong>Beberapa baris gagal diproses:</strong>
                        </div>
                        <ul class="imp-error-list">
                            @foreach(session('import_errors') as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($errors->any())
                    <div class="imp-alert imp-alert-danger imp-alert-block">
                        <div class="imp-alert-header">
                            <svg class="imp-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>
                            <strong>Periksa kembali input Anda:</strong>
                        </div>
                        <ul class="imp-error-list">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- ─── MAIN GRID ─── --}}
            <div class="imp-grid">

                {{-- LEFT: Upload Form --}}
                <div class="imp-left">
                    <form method="POST" action="{{ route('products.import.process') }}" enctype="multipart/form-data" id="importForm">
                        @csrf

                        <div class="imp-card">
                            <div class="imp-card-header">
                                <h2 class="imp-card-title">Upload File CSV / XLSX</h2>
                            </div>

                            <div class="imp-card-body">
                                {{-- Dropzone --}}
                                <div class="imp-form-group">
                                    <input id="csvFile" type="file" name="file" accept=".csv,.xlsx,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="imp-hidden-input" required aria-label="Pilih file CSV atau XLSX">
                                    <label for="csvFile" class="imp-dropzone" id="dropzoneArea">
                                        <div class="imp-dz-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="9" y1="3" x2="9" y2="21"/>
                                                <path d="M14 8h3"/>
                                                <path d="M14 12h3"/>
                                                <path d="M14 16h3"/>
                                            </svg>
                                        </div>
                                        <h3 class="imp-dz-title">Klik atau seret file ke sini</h3>
                                        <p class="imp-dz-sub">Format: CSV atau XLSX • Maksimal 20MB</p>

                                        <div id="filePill" class="imp-file-pill" style="display:none;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                            </svg>
                                            <span id="fileName" class="imp-file-name"></span>
                                        </div>
                                    </label>
                                </div>

                                {{-- Mode Select --}}
                                <div class="imp-form-group">
                                    <label class="imp-label" for="modeSelect">
                                        Mode Import <span class="imp-req">*</span>
                                    </label>
                                    <div class="imp-select-wrap">
                                        <select name="mode" id="modeSelect" class="imp-select" required>
                                            <option value="upsert_by_sku" selected>Update jika SKU sama, tambah jika beda</option>
                                            <option value="create_only">Hanya tambah baru (abaikan SKU yang sudah ada)</option>
                                        </select>
                                    </div>
                                    <p class="imp-help">Pilih mode sesuai kebutuhan. Mode "Update" akan memperbarui produk yang sudah ada berdasarkan SKU.</p>
                                </div>
                            </div>

                            <div class="imp-card-footer">
                                <button type="submit" class="imp-btn imp-btn-primary imp-btn-block" id="submitBtn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.59-9.21L21.5 8"/>
                                    </svg>
                                    <span id="submitText">Mulai Proses Import</span>
                                    <svg class="imp-spinner" id="submitSpinner" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none;">
                                        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
                                        <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- RIGHT: Guidelines --}}
                <div class="imp-right">
                    <div class="imp-info-card">
                        <div class="imp-info-header">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            <h3 class="imp-info-title">Panduan Format</h3>
                        </div>

                        <div class="imp-info-body">
                            <div class="imp-guide-section">
                                <h4 class="imp-guide-heading">Kolom Wajib</h4>
                                <div class="imp-tag-group">
                                    <span class="imp-tag imp-tag-required">name</span>
                                    <span class="imp-tag imp-tag-required">category</span>
                                    <span class="imp-tag imp-tag-required">price</span>
                                </div>
                            </div>

                            <div class="imp-guide-section">
                                <h4 class="imp-guide-heading">Kolom Opsional</h4>
                                <div class="imp-tag-group">
                                    <span class="imp-tag">unit</span>
                                    <span class="imp-tag">sku</span>
                                    <span class="imp-tag">barcode</span>
                                    <span class="imp-tag">purchase_price</span>
                                    <span class="imp-tag">stock</span>
                                    <span class="imp-tag">min_stock</span>
                                    <span class="imp-tag">description</span>
                                </div>
                            </div>

                            <div class="imp-divider"></div>

                            <div class="imp-guide-section">
                                <h4 class="imp-guide-heading">Fitur Otomatis</h4>
                                <ul class="imp-feature-list">
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span><strong>SKU kosong?</strong> Sistem akan generate otomatis</span>
                                    </li>
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span><strong>Kategori baru?</strong> Akan dibuat otomatis</span>
                                    </li>
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        <span><strong>Format angka:</strong> Support format Indonesia (15.000) atau polos (15000)</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="imp-divider"></div>

                            <div class="imp-guide-section">
                                <h4 class="imp-guide-heading">Contoh Header</h4>
                                <div class="imp-code-block">name;category;unit;sku;price;stock</div>
                            </div>

                            <div class="imp-guide-section">
                                <h4 class="imp-guide-heading">Contoh Data</h4>
                                <div class="imp-example-table">
                                    <table>
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
                                                <td><strong>Indomie Goreng</strong></td>
                                                <td>Sembako</td>
                                                <td>pcs</td>
                                                <td>3500</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gula Pasir</strong></td>
                                                <td>Sembako</td>
                                                <td>kg</td>
                                                <td>16000</td>
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
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        :root {
            --imp-primary: #4f46e5;
            --imp-primary-hover: #4338ca;
            --imp-primary-light: #eef2ff;
            --imp-success: #10b981;
            --imp-success-light: #d1fae5;
            --imp-danger: #ef4444;
            --imp-danger-light: #fee2e2;
            --imp-warning: #f59e0b;
            --imp-bg: #f8fafc;
            --imp-surface: #ffffff;
            --imp-border: #e2e8f0;
            --imp-text: #0f172a;
            --imp-text-muted: #64748b;
            --imp-radius: 12px;
        }

        .imp-wrap {
            background: var(--imp-bg);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--imp-text);
            padding-bottom: 4rem;
        }

        .imp-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* ── HEADER ── */
        .imp-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .imp-header-left {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .imp-icon-box {
            width: 56px;
            height: 56px;
            border-radius: var(--imp-radius);
            background: var(--imp-primary-light);
            color: var(--imp-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .imp-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0 0 0.25rem 0;
            letter-spacing: -0.02em;
        }

        .imp-subtitle {
            font-size: 0.9rem;
            color: var(--imp-text-muted);
            margin: 0;
            line-height: 1.5;
            max-width: 500px;
        }

        .imp-header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* ── BUTTONS ── */
        .imp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
            font-family: inherit;
        }

        .imp-btn-primary {
            background: var(--imp-primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .imp-btn-primary:hover:not(:disabled) {
            background: var(--imp-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -2px rgba(79, 70, 229, 0.3);
        }

        .imp-btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .imp-btn-outline {
            border-color: var(--imp-border);
            background: white;
            color: var(--imp-text);
        }

        .imp-btn-outline:hover {
            background: #f1f5f9;
            border-color: var(--imp-text-muted);
        }

        .imp-btn-ghost {
            background: transparent;
            color: var(--imp-text-muted);
        }

        .imp-btn-ghost:hover {
            color: var(--imp-text);
            background: #f1f5f9;
        }

        .imp-btn-block {
            width: 100%;
        }

        /* ── ALERTS ── */
        .imp-alerts {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .imp-alert {
            padding: 1rem 1.25rem;
            border-radius: var(--imp-radius);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .imp-alert-block {
            flex-direction: column;
        }

        .imp-alert-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .imp-alert-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .imp-alert-success {
            background: var(--imp-success-light);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .imp-alert-danger {
            background: var(--imp-danger-light);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .imp-summary-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .imp-summary-item {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .imp-summary-error {
            color: var(--imp-danger);
        }

        .imp-error-list {
            margin: 0;
            padding-left: 1.5rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .imp-error-list li {
            margin-bottom: 0.25rem;
        }

        /* ── GRID LAYOUT ── */
        .imp-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 1.5rem;
            align-items: start;
        }

        @media (max-width: 992px) {
            .imp-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── CARD ── */
        .imp-card {
            background: var(--imp-surface);
            border: 1px solid var(--imp-border);
            border-radius: var(--imp-radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .imp-card-header {
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            background: #fafbfc;
        }

        .imp-card-title {
            font-size: 1.125rem;
            font-weight: 800;
            margin: 0;
        }

        .imp-card-body {
            padding: 1.75rem;
        }

        .imp-card-footer {
            padding: 1.25rem 1.75rem;
            border-top: 1px solid #f1f5f9;
            background: #fafbfc;
        }

        /* ── FORM ── */
        .imp-form-group {
            margin-bottom: 1.5rem;
        }

        .imp-form-group:last-child {
            margin-bottom: 0;
        }

        .imp-hidden-input {
            position: absolute;
            left: -9999px;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden;
        }

        .imp-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--imp-text-muted);
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .imp-req {
            color: var(--imp-danger);
        }

        .imp-help {
            font-size: 0.8rem;
            color: var(--imp-text-muted);
            margin-top: 0.5rem;
            line-height: 1.5;
        }

        /* ── DROPZONE ── */
        .imp-dropzone {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 0.5rem;
            padding: 3rem 2rem;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: var(--imp-radius);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .imp-dropzone:hover,
        .imp-dropzone.dragover {
            border-color: var(--imp-primary);
            background: var(--imp-primary-light);
        }

        .imp-dropzone.has-file {
            border-color: var(--imp-success);
            background: var(--imp-success-light);
        }

        .imp-dz-icon {
            color: var(--imp-primary);
            margin-bottom: 0.5rem;
        }

        .imp-dropzone.has-file .imp-dz-icon {
            color: var(--imp-success);
        }

        .imp-dz-title {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 800;
        }

        .imp-dz-sub {
            margin: 0;
            font-size: 0.85rem;
            color: var(--imp-text-muted);
            max-width: 300px;
            line-height: 1.5;
        }

        .imp-file-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 99px;
            background: white;
            border: 2px solid var(--imp-success);
            color: var(--imp-success);
            font-weight: 700;
            font-size: 0.875rem;
            margin-top: 1rem;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.15);
        }

        .imp-file-name {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ── SELECT ── */
        .imp-select-wrap {
            position: relative;
        }

        .imp-select-wrap::after {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            pointer-events: none;
        }

        .imp-select {
            appearance: none;
            padding: 0.875rem 1rem;
            border: 1.5px solid var(--imp-border);
            border-radius: 10px;
            font-size: 0.95rem;
            background: white;
            transition: all 0.2s;
            font-family: inherit;
            color: var(--imp-text);
            font-weight: 600;
            width: 100%;
            outline: none;
            cursor: pointer;
        }

        .imp-select:focus {
            border-color: var(--imp-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        /* ── SPINNER ── */
        .imp-spinner {
            animation: imp-spin 1s linear infinite;
        }

        @keyframes imp-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ── INFO CARD (RIGHT) ── */
        .imp-info-card {
            background: var(--imp-surface);
            border: 1px solid var(--imp-border);
            border-radius: var(--imp-radius);
            overflow: hidden;
            position: sticky;
            top: 2rem;
        }

        .imp-info-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            background: #fafbfc;
            color: var(--imp-primary);
        }

        .imp-info-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
        }

        .imp-info-body {
            padding: 1.5rem;
        }

        .imp-guide-section {
            margin-bottom: 1.5rem;
        }

        .imp-guide-section:last-child {
            margin-bottom: 0;
        }

        .imp-guide-heading {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--imp-text-muted);
            letter-spacing: 0.05em;
            margin: 0 0 0.75rem 0;
        }

        .imp-tag-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .imp-tag {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            background: #f1f5f9;
            border: 1px solid var(--imp-border);
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            color: var(--imp-text);
        }

        .imp-tag-required {
            background: var(--imp-primary-light);
            border-color: var(--imp-primary);
            color: var(--imp-primary);
        }

        .imp-divider {
            height: 1px;
            background: var(--imp-border);
            margin: 1.5rem 0;
        }

        .imp-feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .imp-feature-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .imp-feature-list svg {
            color: var(--imp-success);
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .imp-code-block {
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
            background: #0f172a;
            color: #38bdf8;
            padding: 0.875rem 1rem;
            border-radius: 8px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .imp-example-table {
            border: 1px solid var(--imp-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .imp-example-table table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .imp-example-table th {
            background: #f8fafc;
            padding: 0.625rem 0.875rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--imp-text-muted);
            border-bottom: 1px solid var(--imp-border);
        }

        .imp-example-table td {
            padding: 0.625rem 0.875rem;
            font-size: 0.8rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .imp-example-table tr:last-child td {
            border-bottom: none;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .imp-header {
                flex-direction: column;
                align-items: stretch;
            }

            .imp-header-actions {
                flex-direction: column;
            }

            .imp-btn {
                width: 100%;
                justify-content: center;
            }

            .imp-dropzone {
                padding: 2rem 1rem;
            }

            .imp-dz-title {
                font-size: 1rem;
            }

            .imp-info-card {
                position: static;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('importForm');
            const input = document.getElementById('csvFile');
            const dropzone = document.getElementById('dropzoneArea');
            const pill = document.getElementById('filePill');
            const fileName = document.getElementById('fileName');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            if (!input || !dropzone || !pill || !fileName) return;

            // Drag and drop handlers
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
                dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
                dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'), false);
            });

            dropzone.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files;
                updateFileUI();
            }

            input.addEventListener('change', updateFileUI);

            function updateFileUI() {
                const f = input.files && input.files[0] ? input.files[0] : null;
                if (!f) {
                    pill.style.display = 'none';
                    fileName.textContent = '';
                    dropzone.classList.remove('has-file');
                    return;
                }

                pill.style.display = 'inline-flex';
                fileName.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
                dropzone.classList.add('has-file');
            }

            // Form submission with loading state
            if (form && submitBtn && submitText && submitSpinner) {
                form.addEventListener('submit', function () {
                    submitBtn.disabled = true;
                    submitText.textContent = 'Memproses...';
                    submitSpinner.style.display = 'inline-block';
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
