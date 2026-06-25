<x-app-layout>
    <x-slot name="header">Tambah Produk</x-slot>
    <style>
        .pd-cr{max-width:1100px;margin:0 auto;padding:1.5rem;font-family:'Plus Jakarta Sans',system-ui,sans-serif;}
        .pd-bc{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#94a3b8;margin-bottom:1.25rem;}
        .pd-bc a{color:#4f46e5;text-decoration:none;font-weight:600;}
        .pd-bc a:hover{text-decoration:underline;}
        .pd-top{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;}
        .pd-top-left h1{font-size:1.5rem;font-weight:800;color:#0f172a;margin:0;}
        .pd-top-left p{font-size:0.8125rem;color:#64748b;margin:0.25rem 0 0;}
        .pd-top-actions{display:flex;gap:0.5rem;}

        /* Steps indicator */
        .pd-steps{display:flex;gap:0;margin-bottom:1.75rem;position:relative;}
        .pd-step{flex:1;text-align:center;position:relative;padding-bottom:1rem;}
        .pd-step::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:#e2e8f0;border-radius:2px;}
        .pd-step.active::after{background:linear-gradient(90deg,#4f46e5,#6366f1);}
        .pd-step.done::after{background:#10b981;}
        .pd-step-num{width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;background:#f1f5f9;color:#94a3b8;margin-bottom:0.375rem;border:2px solid #e2e8f0;transition:all .3s;}
        .pd-step.active .pd-step-num{background:#eef2ff;color:#4f46e5;border-color:#4f46e5;}
        .pd-step.done .pd-step-num{background:#ecfdf5;color:#10b981;border-color:#10b981;}
        .pd-step-label{font-size:0.75rem;font-weight:700;color:#94a3b8;}
        .pd-step.active .pd-step-label{color:#4f46e5;}
        .pd-step.done .pd-step-label{color:#10b981;}

        /* Cards */
        .pd-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;margin-bottom:1.25rem;}
        .pd-card-head{display:flex;align-items:center;gap:0.875rem;padding:1.125rem 1.5rem;border-bottom:1px solid #f1f5f9;background:#fafbfc;}
        .pd-card-icon{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .pd-card-icon.indigo{background:#eef2ff;color:#4f46e5;}
        .pd-card-icon.emerald{background:#ecfdf5;color:#059669;}
        .pd-card-title{font-size:0.9375rem;font-weight:700;color:#1e293b;margin:0;}
        .pd-card-desc{font-size:0.75rem;color:#94a3b8;margin:0.125rem 0 0;}
        .pd-card-actions{margin-left:auto;display:flex;gap:0.5rem;}
        .pd-card-body{padding:1.5rem;}

        /* Form elements */
        .pd-field{margin-bottom:1.125rem;}
        .pd-field:last-child{margin-bottom:0;}
        .pd-grid{display:grid;gap:1.25rem;}
        .pd-grid-2{grid-template-columns:1fr 1fr;}
        .pd-grid-3{grid-template-columns:1fr 1fr 1fr;}
        .pd-label{display:block;font-size:0.75rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.375rem;}
        .pd-req{color:#e11d48;}
        .pd-input,.pd-select,.pd-textarea{width:100%;height:42px;border:1.5px solid #e2e8f0;border-radius:10px;padding:0 0.875rem;font-size:0.875rem;outline:none;transition:all .2s;box-sizing:border-box;font-family:inherit;background:#fff;color:#1e293b;font-weight:500;}
        .pd-input:focus,.pd-select:focus,.pd-textarea:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1);}
        .pd-input.valid{border-color:#10b981;background:#f0fdf4;}
        .pd-input.invalid,.pd-select.invalid{border-color:#e11d48;background:#fef2f2;}
        .pd-textarea{height:auto;padding:0.625rem 0.875rem;resize:vertical;min-height:72px;}
        .pd-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:14px;padding-right:2.5rem;cursor:pointer;}
        .pd-err{font-size:0.6875rem;color:#e11d48;margin-top:0.25rem;font-weight:600;display:none;}
        .pd-err.show{display:block;}
        .pd-hint{font-size:0.6875rem;color:#94a3b8;margin-top:0.25rem;display:flex;align-items:center;gap:0.25rem;}
        .pd-charcount{font-size:0.6875rem;color:#cbd5e1;text-align:right;margin-top:0.125rem;}

        /* SKU auto badge */
        .pd-sku-wrap{position:relative;}
        .pd-sku-badge{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:#eef2ff;color:#4f46e5;font-size:0.625rem;font-weight:800;padding:0.2rem 0.5rem;border-radius:5px;text-transform:uppercase;letter-spacing:0.05em;pointer-events:none;}

        /* Accordion */
        .pd-accordion{margin-top:1rem;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;background:#fafbfc;}
        .pd-accordion summary{display:flex;align-items:center;gap:0.5rem;padding:0.875rem 1.25rem;cursor:pointer;font-weight:700;font-size:0.8125rem;color:#475569;list-style:none;user-select:none;}
        .pd-accordion summary::-webkit-details-marker{display:none;}
        .pd-accordion summary .arrow{width:16px;height:16px;transition:transform .2s;color:#94a3b8;}
        .pd-accordion[open] summary .arrow{transform:rotate(90deg);}
        .pd-accordion-badge{background:#f1f5f9;color:#64748b;font-size:0.625rem;font-weight:700;padding:0.125rem 0.5rem;border-radius:5px;text-transform:uppercase;}
        .pd-accordion-body{padding:1.25rem;border-top:1px solid #e2e8f0;}

        /* Unit table */
        .pd-unit-wrap{border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;background:#fff;}
        .pd-unit-head{display:grid;grid-template-columns:1.5fr 0.8fr repeat(7,1.3fr) 0.6fr 0.6fr;gap:0.375rem;padding:0.75rem 1rem;background:#f8fafc;border-bottom:1px solid #e2e8f0;min-width:1050px;}
        .pd-unit-head span{font-size:0.625rem;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;}
        .pd-unit-head .c{text-align:center;} .pd-unit-head .r{text-align:right;}
        .pd-unit-scroll{overflow-x:auto;}
        .pd-unit-inner{min-width:1050px;}
        .pd-ur{display:grid;grid-template-columns:1.5fr 0.8fr repeat(7,1.3fr) 0.6fr 0.6fr;gap:0.375rem;padding:0.75rem 1rem;border-bottom:1px solid #f1f5f9;align-items:center;transition:background .15s;}
        .pd-ur:last-child{border-bottom:none;}
        .pd-ur:hover{background:#fafbfc;}
        .pd-ur.base{background:#f0fdf4 !important;}
        .pd-ur .cell{min-width:0;}
        .pd-ur .cell input,.pd-ur .cell select{height:36px;border:1px solid #cbd5e1;border-radius:7px;padding:0 0.5rem;font-size:0.8125rem;outline:none;width:100%;box-sizing:border-box;font-family:inherit;background:#fff;}
        .pd-ur .cell input:focus,.pd-ur .cell select:focus{border-color:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.08);}
        .pd-ur .cell .money{text-align:right;font-family:ui-monospace,SFMono-Regular,monospace;font-weight:600;font-size:0.8125rem;}
        .pd-ur .cell .conv{text-align:center;font-weight:800;}
        .pd-ur .cell select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 6px center;background-size:10px;padding-right:1.5rem;cursor:pointer;}
        .pd-ur .center{display:flex;justify-content:center;align-items:center;}
        .pd-ur .center input[type=checkbox]{width:18px;height:18px;accent-color:#10b981;cursor:pointer;}
        .pd-del{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;background:#fef2f2;color:#991b1b;border:none;cursor:pointer;transition:.2s;margin:0 auto;}
        .pd-del:hover{background:#ef4444;color:#fff;}
        .pd-empty{text-align:center;padding:2.5rem 1.5rem;color:#94a3b8;font-size:0.8125rem;}
        .pd-empty-icon{font-size:2rem;margin-bottom:0.5rem;}

        /* Markup toolbar */
        .pd-markup{display:flex;align-items:center;gap:1rem;flex-wrap:wrap;padding:1rem 1.25rem;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;margin-bottom:1rem;}
        .pd-markup-title{font-size:0.8125rem;font-weight:800;color:#92400e;white-space:nowrap;}
        .pd-markup-inputs{display:flex;gap:0.375rem;flex-wrap:wrap;flex:1;}
        .pd-markup-inputs input{width:80px;height:34px;border:1px solid #fde68a;border-radius:7px;padding:0 0.5rem;font-size:0.75rem;text-align:center;outline:none;background:#fff;font-weight:600;}
        .pd-markup-inputs input:focus{border-color:#d97706;box-shadow:0 0 0 2px rgba(217,119,6,.15);}
        .pd-markup-inputs input::placeholder{color:#d4a373;font-weight:500;}
        .pd-markup-btn{height:34px;padding:0 0.875rem;background:#f59e0b;color:#fff;border:none;border-radius:7px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:.2s;white-space:nowrap;}
        .pd-markup-btn:hover{background:#d97706;}

        /* Tip box */
        .pd-tip{display:flex;align-items:flex-start;gap:0.625rem;padding:0.875rem 1.125rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;margin-bottom:1rem;font-size:0.75rem;color:#1e40af;line-height:1.5;}
        .pd-tip strong{display:block;font-weight:800;margin-bottom:0.125rem;}
        .pd-tip-icon{flex-shrink:0;width:18px;height:18px;margin-top:1px;}

        /* Sticky footer */
        .pd-footer{position:sticky;bottom:1rem;margin-top:1.5rem;z-index:50;}
        .pd-footer-inner{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:0.875rem 1.25rem;background:rgba(255,255,255,.95);backdrop-filter:blur(12px);border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.08);}
        .pd-footer-info{font-size:0.8125rem;color:#64748b;display:flex;align-items:center;gap:0.5rem;}
        .pd-footer-info .count{background:#eef2ff;color:#4f46e5;font-weight:800;padding:0.125rem 0.5rem;border-radius:5px;font-size:0.75rem;}
        .pd-footer-actions{display:flex;gap:0.5rem;}

        /* Buttons */
        .pd-btn{display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.625rem 1.125rem;border-radius:10px;font-size:0.8125rem;font-weight:700;cursor:pointer;transition:all .2s;border:1px solid transparent;text-decoration:none;white-space:nowrap;}
        .pd-btn-primary{background:#4f46e5;color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.25);}
        .pd-btn-primary:hover{background:#4338ca;transform:translateY(-1px);}
        .pd-btn-outline{border-color:#e2e8f0;background:#fff;color:#475569;}
        .pd-btn-outline:hover{background:#f8fafc;border-color:#cbd5e1;}
        .pd-btn-emerald{background:#10b981;color:#fff;box-shadow:0 2px 8px rgba(16,185,129,.25);}
        .pd-btn-emerald:hover{background:#059669;transform:translateY(-1px);}
        .pd-btn-sm{padding:0.5rem 0.875rem;font-size:0.75rem;}

        /* Alert */
        .pd-alert{padding:0.875rem 1.125rem;border-radius:10px;display:flex;align-items:center;gap:0.625rem;font-size:0.8125rem;font-weight:600;margin-bottom:1rem;}
        .pd-alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
        .pd-alert ul{margin:0.375rem 0 0;padding-left:1.25rem;font-weight:500;}

        /* Responsive */
        @media(max-width:1100px){
            .pd-unit-scroll{overflow-x:visible;}
            .pd-unit-head{display:none;}
            .pd-unit-inner{min-width:0;}
            .pd-ur{grid-template-columns:1fr 1fr;background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:1rem;gap:0.75rem;margin-bottom:0.5rem;align-items:start;}
            .pd-ur .lbl{display:block;font-size:0.625rem;font-weight:800;color:#94a3b8;text-transform:uppercase;margin-bottom:0.25rem;}
            .pd-ur .full{grid-column:1/-1;}
            .pd-ur .center{justify-content:flex-start;}
            .pd-del{width:100%;height:34px;gap:0.375rem;}
            .pd-del::after{content:'Hapus';font-size:0.75rem;font-weight:700;}
        }
        @media(max-width:640px){
            .pd-grid-2,.pd-grid-3{grid-template-columns:1fr;}
            .pd-top{flex-direction:column;}
            .pd-footer-inner{flex-direction:column;text-align:center;}
            .pd-markup{flex-direction:column;align-items:stretch;}
            .pd-ur{grid-template-columns:1fr;}
        }
    </style>

    <div class="pd-cr">
        {{-- Breadcrumb --}}
        <div class="pd-bc">
            <a href="{{ route('products.index') }}">Data Produk</a>
            <span>›</span>
            <span>Tambah Produk</span>
        </div>

        {{-- Header --}}
        <div class="pd-top">
            <div class="pd-top-left">
                <h1>Tambah Produk Baru</h1>
                <p>Lengkapi informasi produk dan atur harga jual multi-satuan.</p>
            </div>
            <div class="pd-top-actions">
                <a href="{{ route('products.index') }}" class="pd-btn pd-btn-outline">Batal</a>
                <button type="submit" form="product-form" class="pd-btn pd-btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Produk
                </button>
            </div>
        </div>

        {{-- Steps --}}
        <div class="pd-steps">
            <div class="pd-step active" id="step1-indicator">
                <div class="pd-step-num">1</div>
                <div class="pd-step-label">Identitas Produk</div>
            </div>
            <div class="pd-step" id="step2-indicator">
                <div class="pd-step-num">2</div>
                <div class="pd-step-label">Satuan & Harga</div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
        <div class="pd-alert pd-alert-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="pd-alert pd-alert-danger">
            <div>
                <strong>Periksa kembali:</strong>
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('products.store') }}" id="product-form" novalidate>
            @csrf
            <input type="hidden" id="priceHidden" name="price" value="{{ old('price', 0) }}">
            <input type="hidden" id="purchasePriceHidden" name="purchase_price" value="{{ old('purchase_price', 0) }}">

            {{-- CARD 1: IDENTITAS --}}
            <div class="pd-card">
                <div class="pd-card-head">
                    <div class="pd-card-icon indigo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <div>
                        <div class="pd-card-title">Identitas Produk</div>
                        <div class="pd-card-desc">Data dasar yang wajib diisi untuk sistem kasir dan inventori.</div>
                    </div>
                </div>
                <div class="pd-card-body">
                    <div class="pd-field">
                        <label class="pd-label">Nama Produk <span class="pd-req">*</span></label>
                        <input type="text" name="name" id="f-name" value="{{ old('name') }}" class="pd-input @error('name') invalid @enderror" placeholder="Contoh: Indomie Goreng Spesial" maxlength="255" required>
                        <div class="pd-charcount"><span id="name-count">0</span>/255</div>
                        <div class="pd-err" id="err-name">Nama produk wajib diisi.</div>
                        @error('name') <div class="pd-err show">{{ $message }}</div> @enderror
                    </div>

                    <div class="pd-grid pd-grid-2">
                        <div class="pd-field">
                            <label class="pd-label">Kategori <span class="pd-req">*</span></label>
                            <select name="category_id" id="f-category" class="pd-select @error('category_id') invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="pd-err" id="err-category">Kategori wajib dipilih.</div>
                            @error('category_id') <div class="pd-err show">{{ $message }}</div> @enderror
                        </div>
                        <div class="pd-field">
                            <label class="pd-label">Kode SKU</label>
                            <div class="pd-sku-wrap">
                                <input type="text" name="sku" value="{{ old('sku', $nextSku ?? '') }}" class="pd-input" style="background:#f8fafc;color:#64748b;font-family:ui-monospace,SFMono-Regular,monospace;font-weight:700;padding-right:60px;" readonly tabindex="-1">
                                <span class="pd-sku-badge">AUTO</span>
                            </div>
                            <div class="pd-hint">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                Dibuat otomatis oleh sistem
                            </div>
                        </div>
                    </div>

                    <div class="pd-grid pd-grid-2">
                        <div class="pd-field">
                            <label class="pd-label">Stok Awal <span class="pd-req">*</span></label>
                            <input type="number" name="stock" id="f-stock" value="{{ old('stock', 0) }}" min="0" class="pd-input @error('stock') invalid @enderror" required>
                            <div class="pd-err" id="err-stock">Stok awal wajib diisi (minimal 0).</div>
                            @error('stock') <div class="pd-err show">{{ $message }}</div> @enderror
                        </div>
                        <div class="pd-field">
                            <label class="pd-label">Batas Minimum Stok <span class="pd-req">*</span></label>
                            <input type="number" name="min_stock" id="f-minstock" value="{{ old('min_stock', 5) }}" min="0" class="pd-input @error('min_stock') invalid @enderror" required>
                            <div class="pd-hint">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                Alert muncul saat stok mencapai angka ini
                            </div>
                            <div class="pd-err" id="err-minstock">Batas minimum wajib diisi.</div>
                            @error('min_stock') <div class="pd-err show">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Optional fields --}}
                    <details class="pd-accordion">
                        <summary>
                            <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                            <span>Informasi Tambahan</span>
                            <span class="pd-accordion-badge">Opsional</span>
                        </summary>
                        <div class="pd-accordion-body">
                            <div class="pd-grid pd-grid-2">
                                <div class="pd-field">
                                    <label class="pd-label">Barcode / EAN</label>
                                    <input type="text" name="barcode" value="{{ old('barcode') }}" class="pd-input @error('barcode') invalid @enderror" placeholder="Scan barcode...">
                                    @error('barcode') <div class="pd-err show">{{ $message }}</div> @enderror
                                </div>
                                <div class="pd-field">
                                    <label class="pd-label">Satuan Dasar (Terkecil)</label>
                                    <select name="unit_id" class="pd-select">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pd-field">
                                <label class="pd-label">Deskripsi Lengkap</label>
                                <textarea name="description" rows="3" class="pd-textarea" placeholder="Keterangan, spesifikasi, atau catatan produk..." maxlength="1000">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </details>
                </div>
            </div>

            {{-- CARD 2: SATUAN & HARGA --}}
            <div class="pd-card">
                <div class="pd-card-head">
                    <div class="pd-card-icon emerald">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="pd-card-title">Satuan & Harga Jual</div>
                        <div class="pd-card-desc">Atur satuan produk dan level harga (Eceran, Grosir, dll).</div>
                    </div>
                    <div class="pd-card-actions">
                        <button type="button" class="pd-btn pd-btn-emerald pd-btn-sm" onclick="addUnitRow()">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Satuan
                        </button>
                    </div>
                </div>
                <div class="pd-card-body">
                    {{-- Tip --}}
                    <div class="pd-tip">
                        <svg class="pd-tip-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <div>
                            <strong>Panduan Konversi</strong>
                            Centang <strong>Basis</strong> untuk satuan terkecil (pcs, botol). <strong>Konversi</strong> = isi per unit besar. Contoh: 1 Karton = 40 Pcs → isi 40.
                        </div>
                    </div>

                    {{-- Markup tool --}}
                    <div class="pd-markup">
                        <div class="pd-markup-title">⚡ Markup Harga</div>
                        <div class="pd-markup-inputs">
                            <input id="pctEcer" type="number" step="0.01" min="0" placeholder="Ecer %">
                            <input id="pctGrosir" type="number" step="0.01" min="0" placeholder="Grosir %">
                            <input id="pctJ1" type="number" step="0.01" min="0" placeholder="J1 %">
                            <input id="pctJ2" type="number" step="0.01" min="0" placeholder="J2 %">
                            <input id="pctJ3" type="number" step="0.01" min="0" placeholder="J3 %">
                            <input id="pctMin" type="number" step="0.01" min="0" placeholder="Min %">
                        </div>
                        <button id="btnApplyMarkup" type="button" class="pd-markup-btn">Terapkan</button>
                    </div>

                    {{-- Unit table --}}
                    <div class="pd-unit-wrap">
                        <div class="pd-unit-head">
                            <span>Satuan</span>
                            <span class="c">Konversi</span>
                            <span class="r">H. Modal</span>
                            <span class="r">Eceran</span>
                            <span class="r">Grosir</span>
                            <span class="r">Jual 1</span>
                            <span class="r">Jual 2</span>
                            <span class="r">Jual 3</span>
                            <span class="r">Minimal</span>
                            <span class="c">Basis</span>
                            <span class="c">Aksi</span>
                        </div>
                        <div class="pd-unit-scroll">
                            <div class="pd-unit-inner" id="unit-rows-container"></div>
                        </div>
                    </div>

                    <div id="no-units-msg" class="pd-empty" style="display:none;">
                        <div class="pd-empty-icon">📦</div>
                        <div>Belum ada satuan. Klik <strong>Tambah Satuan</strong> di atas.</div>
                    </div>
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="pd-footer">
                <div class="pd-footer-inner">
                    <div class="pd-footer-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        Satuan aktif: <span class="count" id="unit-count">0</span>
                    </div>
                    <div class="pd-footer-actions">
                        <a href="{{ route('products.index') }}" class="pd-btn pd-btn-outline">Batal</a>
                        <button type="submit" class="pd-btn pd-btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <template id="unitsJson">@json($unitsData)</template>

    @push('scripts')
    <script>
    const allUnits = JSON.parse(document.getElementById('unitsJson')?.innerHTML || '[]');
    let unitRowIdx = 0;

    /* ── Helpers ── */
    function num(v) { const n = Number(v); return Number.isFinite(n) ? n : 0; }
    function parseMoney(v) { const s = String(v ?? ''); const d = s.replace(/[^\d]/g, ''); return d ? Number(d) : 0; }
    function formatMoney(v) { return Math.max(0, Math.floor(num(v))).toLocaleString('id-ID'); }

    /* ── Live Validation ── */
    function validateField(el, errId) {
        const err = document.getElementById(errId);
        let valid = true;
        if (el.hasAttribute('required') && !el.value.trim()) valid = false;
        if (el.type === 'number' && el.min !== '' && Number(el.value) < Number(el.min)) valid = false;
        el.classList.toggle('invalid', !valid);
        el.classList.toggle('valid', valid && el.value.trim() !== '');
        if (err) err.classList.toggle('show', !valid);
        return valid;
    }

    function updateSteps() {
        const nameOk = !!document.getElementById('f-name')?.value.trim();
        const catOk = !!document.getElementById('f-category')?.value;
        const s1 = document.getElementById('step1-indicator');
        const s2 = document.getElementById('step2-indicator');
        if (nameOk && catOk) { s1.className = 'pd-step done'; s2.className = 'pd-step active'; }
        else { s1.className = 'pd-step active'; s2.className = 'pd-step'; }
    }

    function updateUnitCount() {
        const c = document.querySelectorAll('.pd-ur').length;
        const el = document.getElementById('unit-count');
        if (el) el.textContent = c;
    }

    /* ── Character counter ── */
    function updateCharCount() {
        const inp = document.getElementById('f-name');
        const cnt = document.getElementById('name-count');
        if (inp && cnt) cnt.textContent = inp.value.length;
    }

    /* ── Unit Row Logic ── */
    function getBaseRowEl() {
        const rows = document.querySelectorAll('.pd-ur');
        for (const row of rows) { const cb = row.querySelector('input[name$="[is_base_unit]"]'); if (cb?.checked) return row; }
        return rows.length ? rows[0] : null;
    }
    function syncMasterFromBase() {
        const base = getBaseRowEl(); if (!base) return;
        const ecer = base.querySelector('input[name$="[sell_price_ecer]"]');
        const modal = base.querySelector('input[name$="[purchase_price]"]');
        const ph = document.getElementById('priceHidden'), pp = document.getElementById('purchasePriceHidden');
        if (ph && ecer) ph.value = String(num(ecer.value));
        if (pp && modal) pp.value = String(num(modal.value));
    }

    /* ── Auto-propagate ALL prices to other units based on conversion factor ── */
    const PRICE_KEYS = ['purchase_price','sell_price_ecer','sell_price_grosir','sell_price_jual1','sell_price_jual2','sell_price_jual3','sell_price_minimal'];
    function syncAllPrices() {
        const base = getBaseRowEl();
        if (!base) return;
        const baseFactor = num(base.querySelector('input[name$="[conversion_factor]"]')?.value || 1);
        if (baseFactor <= 0) return;
        document.querySelectorAll('.pd-ur').forEach(row => {
            if (row === base) return;
            const factor = num(row.querySelector('input[name$="[conversion_factor]"]')?.value || 1);
            const ratio = factor / baseFactor;
            PRICE_KEYS.forEach(k => {
                const baseVal = getHidden(base, k);
                const newVal = Math.round(baseVal * ratio);
                setHidden(row, k, newVal);
                /* Flash animation to show the auto-fill */
                const vis = row.querySelector(`input[data-visible="${k}"]`);
                if (vis) { vis.style.transition = 'background .3s'; vis.style.background = '#ecfdf5'; setTimeout(() => { vis.style.background = ''; }, 600); }
            });
        });
    }
    /* Sync a single non-base row (when its konversi changes) */
    function syncRowPrices(row) {
        const base = getBaseRowEl();
        if (!base || row === base) return;
        const baseFactor = num(base.querySelector('input[name$="[conversion_factor]"]')?.value || 1);
        const factor = num(row.querySelector('input[name$="[conversion_factor]"]')?.value || 1);
        if (baseFactor <= 0) return;
        const ratio = factor / baseFactor;
        PRICE_KEYS.forEach(k => {
            const baseVal = getHidden(base, k);
            setHidden(row, k, Math.round(baseVal * ratio));
            const vis = row.querySelector(`input[data-visible="${k}"]`);
            if (vis) { vis.style.transition = 'background .3s'; vis.style.background = '#ecfdf5'; setTimeout(() => { vis.style.background = ''; }, 600); }
        });
    }
    function updateAllRowStyles() {
        document.querySelectorAll('.pd-ur').forEach(row => {
            const cb = row.querySelector('input[name$="[is_base_unit]"]');
            if (cb?.checked) { row.classList.add('base'); row.style.borderColor = '#86efac'; }
            else { row.classList.remove('base'); row.style.borderColor = ''; }
        });
    }
    function onBaseChange(idx) {
        const row = document.getElementById(`unit-row-${idx}`);
        const checked = row?.querySelector('input[name$="[is_base_unit]"]')?.checked;
        if (checked) document.querySelectorAll('input[name$="[is_base_unit]"]').forEach(cb => { if (cb !== row.querySelector('input[name$="[is_base_unit]"]')) cb.checked = false; });
        updateAllRowStyles(); syncMasterFromBase();
    }
    function removeUnitRow(idx) {
        document.getElementById(`unit-row-${idx}`)?.remove();
        if (!document.querySelectorAll('.pd-ur').length) document.getElementById('no-units-msg').style.display = 'block';
        syncMasterFromBase(); updateUnitCount();
    }
    function baseDefaults() {
        const base = getBaseRowEl();
        if (!base) {
            const p = num(document.getElementById('priceHidden')?.value || 0);
            return { purchase_price: num(document.getElementById('purchasePriceHidden')?.value || 0), sell_price_ecer: p, sell_price_grosir: p, sell_price_jual1: p, sell_price_jual2: p, sell_price_jual3: p, sell_price_minimal: p };
        }
        const gv = (n) => num(base.querySelector(`input[name$="[${n}]"]`)?.value || 0);
        return { purchase_price: gv('purchase_price'), sell_price_ecer: gv('sell_price_ecer'), sell_price_grosir: gv('sell_price_grosir'), sell_price_jual1: gv('sell_price_jual1'), sell_price_jual2: gv('sell_price_jual2'), sell_price_jual3: gv('sell_price_jual3'), sell_price_minimal: gv('sell_price_minimal') };
    }
    function getHidden(row, key) { const el = row.querySelector(`input[type="hidden"][data-hidden="${key}"]`); return el ? num(el.value) : 0; }
    function setHidden(row, key, value) {
        const el = row.querySelector(`input[type="hidden"][data-hidden="${key}"]`);
        const vis = row.querySelector(`input[type="text"][data-visible="${key}"]`);
        if (el) el.value = String(Math.max(0, Math.floor(num(value))));
        if (vis) vis.value = formatMoney(el?.value ?? value);
    }
    function wireMoneyInput(row, key) {
        const el = row.querySelector(`input[type="hidden"][data-hidden="${key}"]`);
        const vis = row.querySelector(`input[type="text"][data-visible="${key}"]`);
        if (!el || !vis) return;
        vis.value = formatMoney(el.value);
        vis.addEventListener('input', function () {
            var cursor = this.selectionStart || 0;
            var oldVal = this.value;
            var digitsOnly = oldVal.replace(/[^\d]/g, '');
            var digitCount = 0;
            for (var i = 0; i < cursor && i < oldVal.length; i++) {
                if (oldVal[i] >= '0' && oldVal[i] <= '9') digitCount++;
            }
            el.value = digitsOnly || '0';
            var n = Math.max(0, Math.floor(Number(digitsOnly || 0)));
            var formatted = n ? n.toLocaleString('id-ID') : '';
            this.value = formatted;
            var newPos = 0, d = 0;
            for (var j = 0; j < formatted.length; j++) {
                if (formatted[j] >= '0' && formatted[j] <= '9') d++;
                if (d === digitCount) { newPos = j + 1; break; }
            }
            if (d < digitCount) newPos = formatted.length;
            this.setSelectionRange(newPos, newPos);
        });
        vis.addEventListener('blur', () => { vis.value = formatMoney(el.value); });
    }
    function applyMarkup() {
        const pct = { sell_price_ecer: document.getElementById('pctEcer')?.value, sell_price_grosir: document.getElementById('pctGrosir')?.value, sell_price_jual1: document.getElementById('pctJ1')?.value, sell_price_jual2: document.getElementById('pctJ2')?.value, sell_price_jual3: document.getElementById('pctJ3')?.value, sell_price_minimal: document.getElementById('pctMin')?.value };
        Object.keys(pct).forEach(k => {
            const p = num(pct[k]);
            if ((!pct[k] && pct[k] !== 0) || !Number.isFinite(p) || p < 0) return;
            document.querySelectorAll('.pd-ur').forEach(row => {
                const modal = getHidden(row, 'purchase_price');
                setHidden(row, k, Math.round(modal * (1 + p / 100)));
            });
        });
        syncMasterFromBase();
    }

    function addUnitRow(data = null) {
        const container = document.getElementById('unit-rows-container');
        document.getElementById('no-units-msg').style.display = 'none';
        const idx = unitRowIdx++;
        const d = data || {};
        const isBase = !!d.is_base_unit;
        const factor = Math.max(0.0001, num(d.conversion_factor || 1));
        const base = baseDefaults();
        const unitOpts = allUnits.map(u => `<option value="${u.id}" ${u.id == (d.unit_id || '') ? 'selected' : ''}>${u.name}</option>`).join('');
        const val = (k, fb) => String(Math.max(0, num(d[k] ?? fb ?? 0)));
        const lbl = '<div class="lbl">';

        const row = document.createElement('div');
        row.id = `unit-row-${idx}`;
        row.className = `pd-ur ${isBase ? 'base' : ''}`;
        row.innerHTML = `
            <div class="cell">${lbl}Satuan</div><select name="units[${idx}][unit_id]" required><option value="">-- Pilih --</option>${unitOpts}</select></div>
            <div class="cell">${lbl}Konversi</div><input type="number" name="units[${idx}][conversion_factor]" value="${factor}" min="0.0001" step="any" class="conv" required></div>
            <div class="cell">${lbl}H. Modal</div><input type="hidden" name="units[${idx}][purchase_price]" data-hidden="purchase_price" value="${val('purchase_price', isBase ? base.purchase_price : base.purchase_price * factor)}"><input type="text" inputmode="numeric" data-visible="purchase_price" class="money" required></div>
            <div class="cell">${lbl}Eceran</div><input type="hidden" name="units[${idx}][sell_price_ecer]" data-hidden="sell_price_ecer" value="${val('sell_price_ecer', isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)}"><input type="text" inputmode="numeric" data-visible="sell_price_ecer" class="money" required></div>
            <div class="cell">${lbl}Grosir</div><input type="hidden" name="units[${idx}][sell_price_grosir]" data-hidden="sell_price_grosir" value="${val('sell_price_grosir', isBase ? base.sell_price_grosir : base.sell_price_grosir * factor)}"><input type="text" inputmode="numeric" data-visible="sell_price_grosir" class="money" required></div>
            <div class="cell">${lbl}Jual 1</div><input type="hidden" name="units[${idx}][sell_price_jual1]" data-hidden="sell_price_jual1" value="${val('sell_price_jual1', isBase ? base.sell_price_jual1 : base.sell_price_jual1 * factor)}"><input type="text" inputmode="numeric" data-visible="sell_price_jual1" class="money"></div>
            <div class="cell">${lbl}Jual 2</div><input type="hidden" name="units[${idx}][sell_price_jual2]" data-hidden="sell_price_jual2" value="${val('sell_price_jual2', isBase ? base.sell_price_jual2 : base.sell_price_jual2 * factor)}"><input type="text" inputmode="numeric" data-visible="sell_price_jual2" class="money"></div>
            <div class="cell">${lbl}Jual 3</div><input type="hidden" name="units[${idx}][sell_price_jual3]" data-hidden="sell_price_jual3" value="${val('sell_price_jual3', isBase ? base.sell_price_jual3 : base.sell_price_jual3 * factor)}"><input type="text" inputmode="numeric" data-visible="sell_price_jual3" class="money"></div>
            <div class="cell">${lbl}Minimal</div><input type="hidden" name="units[${idx}][sell_price_minimal]" data-hidden="sell_price_minimal" value="${val('sell_price_minimal', (d.sell_price_minimal ?? (isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)))}"><input type="text" inputmode="numeric" data-visible="sell_price_minimal" class="money"></div>
            <div class="cell full">${lbl}Basis</div><div class="center"><input type="checkbox" name="units[${idx}][is_base_unit]" value="1" ${isBase ? 'checked' : ''} onchange="onBaseChange(${idx})"></div></div>
            <div class="cell full"><div class="center"><button type="button" class="pd-del" onclick="removeUnitRow(${idx})" title="Hapus"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div></div>
        `;
        container.appendChild(row);
        ['purchase_price','sell_price_ecer','sell_price_grosir','sell_price_jual1','sell_price_jual2','sell_price_jual3','sell_price_minimal'].forEach(k => wireMoneyInput(row, k));
        updateAllRowStyles(); syncMasterFromBase(); updateUnitCount();
    }

    /* ── Client-side form validation ── */
    function clientValidate(e) {
        e.preventDefault();
        let ok = true;
        ok = validateField(document.getElementById('f-name'), 'err-name') && ok;
        ok = validateField(document.getElementById('f-category'), 'err-category') && ok;
        ok = validateField(document.getElementById('f-stock'), 'err-stock') && ok;
        ok = validateField(document.getElementById('f-minstock'), 'err-minstock') && ok;
        if (ok) { syncMasterFromBase(); e.target.submit(); }
        else { document.querySelector('.pd-ur, .pd-input.invalid, .pd-select.invalid')?.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    }

    /* ── Init ── */
    document.addEventListener('DOMContentLoaded', () => {
        addUnitRow({ is_base_unit: true, conversion_factor: 1 });
        updateCharCount();
        updateSteps();

        /* Live validation on blur */
        document.getElementById('f-name')?.addEventListener('blur', function() { validateField(this, 'err-name'); });
        document.getElementById('f-category')?.addEventListener('change', function() { validateField(this, 'err-category'); updateSteps(); });
        document.getElementById('f-name')?.addEventListener('input', function() { updateCharCount(); updateSteps(); validateField(this, 'err-name'); });
        document.getElementById('f-stock')?.addEventListener('blur', function() { validateField(this, 'err-stock'); });
        document.getElementById('f-minstock')?.addEventListener('blur', function() { validateField(this, 'err-minstock'); });

        document.getElementById('unit-rows-container')?.addEventListener('input', (e) => {
            const base = getBaseRowEl();
            if (!base) return;
            const isPriceField = PRICE_KEYS.some(k => e.target.matches(`input[data-hidden="${k}"]`) || e.target.matches(`input[data-visible="${k}"]`));
            const isConvField = e.target.matches('input[name$="[conversion_factor]"]');
            if (base.contains(e.target)) {
                syncMasterFromBase();
                /* Auto-propagate all prices when any base row price or konversi changes */
                if (isPriceField || isConvField) syncAllPrices();
            } else if (isConvField) {
                /* Non-base konversi changed → recalc that row's prices only */
                const row = e.target.closest('.pd-ur');
                if (row) syncRowPrices(row);
            }
        });
        document.getElementById('btnApplyMarkup')?.addEventListener('click', applyMarkup);
        document.getElementById('product-form')?.addEventListener('submit', clientValidate);
        syncMasterFromBase();
    });
    </script>
    @endpush
</x-app-layout>
