<x-app-layout>
    <x-slot name="header">Edit Produk</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER ─── --}}
            <div class="tr-header animate-fade-in">
                <div class="tr-header-left">
                    <a href="{{ route('products.index') }}" class="tr-btn-ghost">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Daftar
                    </a>
                    <div class="tr-title-wrap">
                        <div class="tr-eyebrow">Katalog & Inventori</div>
                        <h1 class="tr-title">Edit: {{ $product->name }}</h1>
                    </div>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('products.index') }}" class="tr-btn tr-btn-outline">Batal</a>
                    <button type="submit" form="product-form" class="tr-btn tr-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            <div class="tr-alert-stack animate-fade-in-up">
                @if(session('error'))
                    <div class="tr-alert tr-alert-danger">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="tr-alert tr-alert-danger tr-alert-block">
                        <div class="alert-header">
                            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <strong>Periksa kembali isian form Anda:</strong>
                        </div>
                        <ul>
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('products.update', $product) }}" id="product-form" class="tr-form-layout animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')
                <input type="hidden" id="priceHidden" name="price" value="{{ old('price', floor($product->price)) }}">
                <input type="hidden" id="purchasePriceHidden" name="purchase_price" value="{{ old('purchase_price', floor($product->purchase_price)) }}">

                {{-- ─── CARD 1: INFORMASI UTAMA ─── --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <div class="tr-card-header-left">
                            <div class="tr-header-icon bg-indigo">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                            </div>
                            <div>
                                <h2 class="tr-section-title">Identitas Produk</h2>
                                <p class="tr-section-desc">Data dasar produk yang wajib diisi untuk sistem kasir.</p>
                            </div>
                        </div>
                    </div>
                    <div class="tr-card-body">

                        {{-- Nama Produk --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Nama Produk <span class="tr-req">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="tr-input @error('name') is-invalid @enderror" placeholder="Contoh: Gula Kristal Putih 50kg" required>
                            @error('name') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kategori & SKU --}}
                        <div class="tr-grid-2">
                            <div class="tr-form-group">
                                <label class="tr-label">Kategori <span class="tr-req">*</span></label>
                                <div class="tr-select-wrapper">
                                    <select name="category_id" class="tr-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="tr-form-group">
                                <label class="tr-label">SKU Produk <span class="tr-req">*</span></label>
                                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="tr-input tr-font-mono @error('sku') is-invalid @enderror" placeholder="Contoh: GL-50KG-P" required>
                                @error('sku') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Stok --}}
                        <div class="tr-grid-2">
                            <div class="tr-form-group">
                                <label class="tr-label">Stok Fisik Saat Ini</label>
                                @if(($maskStock ?? false) === true)
                                    <input type="text" value="Terkunci (Butuh Opname)" class="tr-input tr-disabled-input" disabled title="Lakukan Opname untuk melihat stok">
                                @else
                                    <div class="tr-input-suffix-group">
                                        <input type="number" value="{{ $product->stock }}" class="tr-input tr-disabled-input tr-font-mono text-main font-bold" disabled>
                                        <span class="suffix">Pcs</span>
                                    </div>
                                @endif
                                <div class="tr-input-hint">ℹ️ Stok hanya dapat diubah melalui menu <strong>Stok Opname</strong>.</div>
                            </div>
                            <div class="tr-form-group">
                                <label class="tr-label">Batas Minimum Stok <span class="tr-req">*</span></label>
                                <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0" class="tr-input tr-font-mono @error('min_stock') is-invalid @enderror" placeholder="0" required>
                                <div class="tr-input-hint">Sistem akan memberi alert jika stok menyentuh angka ini.</div>
                                @error('min_stock') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Accordion Opsional --}}
                        <details class="tr-accordion-block">
                            <summary class="tr-accordion-trigger">
                                <div class="trigger-content">
                                    <svg class="trigger-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    <span>Informasi Tambahan</span>
                                    <span class="tr-badge badge-gray">Opsional</span>
                                </div>
                            </summary>
                            <div class="tr-accordion-body">
                                <div class="tr-grid-2">
                                    <div class="tr-form-group">
                                        <label class="tr-label">Barcode / EAN</label>
                                        <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="tr-input tr-font-mono @error('barcode') is-invalid @enderror" placeholder="Scan barcode disini...">
                                        @error('barcode') <div class="tr-error-msg">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="tr-form-group">
                                        <label class="tr-label">Satuan Dasar (Terkecil)</label>
                                        <div class="tr-select-wrapper">
                                            <select name="unit_id" class="tr-select">
                                                <option value="">-- Pilih Satuan --</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->abbreviation }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="tr-form-group" style="margin-bottom: 0;">
                                    <label class="tr-label">Deskripsi Lengkap</label>
                                    <textarea name="description" rows="3" class="tr-textarea" placeholder="Tulis keterangan, spesifikasi, atau catatan produk...">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                        </details>

                    </div>
                </div>

                {{-- ─── CARD 2: SATUAN & HARGA ─── --}}
                <div class="tr-card animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="tr-card-header">
                        <div class="tr-card-header-left">
                            <div class="tr-header-icon bg-emerald">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            </div>
                            <div>
                                <h2 class="tr-section-title">Satuan & Konversi Harga</h2>
                                <p class="tr-section-desc">Atur multi-satuan dan level harga (Grosir, Ecer, dll).</p>
                            </div>
                        </div>
                        <button type="button" class="tr-btn tr-btn-emerald" onclick="addUnitRow()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Tambah Satuan
                        </button>
                    </div>
                    <div class="tr-card-body" style="padding-top: 1rem;">

                        {{-- Markup Tool --}}
                        <div class="tr-markup-toolbar">
                            <div class="markup-info">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                <div>
                                    <strong>Auto-Markup Harga (%)</strong>
                                    <p>Isi persentase margin keuntungan, sistem akan menghitung harga jual otomatis.</p>
                                </div>
                            </div>
                            <div class="markup-inputs">
                                <input id="pctEcer" type="number" step="0.01" min="0" class="tr-input m-input" placeholder="Ecer %">
                                <input id="pctGrosir" type="number" step="0.01" min="0" class="tr-input m-input" placeholder="Grosir %">
                                <input id="pctJ1" type="number" step="0.01" min="0" class="tr-input m-input" placeholder="J1 %">
                                <input id="pctJ2" type="number" step="0.01" min="0" class="tr-input m-input" placeholder="J2 %">
                                <input id="pctJ3" type="number" step="0.01" min="0" class="tr-input m-input" placeholder="J3 %">
                                <input id="pctMin" type="number" step="0.01" min="0" class="tr-input m-input" placeholder="Min %">
                                <button id="btnApplyMarkup" type="button" class="tr-btn tr-btn-dark m-btn">Terapkan</button>
                            </div>
                        </div>

                        <div class="tr-notice-box info" style="margin-bottom: 1.5rem;">
                            <div class="notice-icon">💡</div>
                            <div class="notice-text">
                                <strong>Panduan Konversi</strong>
                                <p><strong>Basis</strong> adalah satuan terkecil. <strong>Konversi</strong> adalah isi satuan tersebut. Contoh: Jika satuan adalah KARTON dan isinya 40 PCS, maka ketik Konversi = 40.</p>
                            </div>
                        </div>

                        {{-- Unit Table Mobile/Desktop Adaptive --}}
                        <div class="tr-unit-table-container">
                            <div class="unit-header-desktop">
                                <div class="uh-col">Satuan</div>
                                <div class="uh-col c">Konversi</div>
                                <div class="uh-col r">H. Modal</div>
                                <div class="uh-col r">Eceran</div>
                                <div class="uh-col r">Grosir</div>
                                <div class="uh-col r">Jual 1</div>
                                <div class="uh-col r">Jual 2</div>
                                <div class="uh-col r">Jual 3</div>
                                <div class="uh-col r">Minimal</div>
                                <div class="uh-col c">Basis</div>
                                <div class="uh-col c">Aksi</div>
                            </div>
                            
                            <div id="unit-rows-container" class="unit-rows-wrapper">
                                {{-- Rows injected via JS --}}
                            </div>
                        </div>

                        <div id="no-units-msg" class="tr-empty-state-box" style="display:none;">
                            <div class="empty-icon">📦</div>
                            <h4>Produk Belum Memiliki Satuan</h4>
                            <p>Klik tombol <strong>Tambah Satuan</strong> di kanan atas untuk mulai menentukan harga jual produk ini.</p>
                        </div>

                    </div>
                </div>

                {{-- ─── STICKY FOOTER ─── --}}
                <div class="tr-sticky-footer">
                    <div class="footer-content">
                        <div class="footer-hint">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Perubahan pada produk ini belum tersimpan.
                        </div>
                        <div class="footer-actions">
                            <a href="{{ route('products.index') }}" class="tr-btn tr-btn-ghost">Batalkan</a>
                            <button type="submit" class="tr-btn tr-btn-primary">
                                Simpan Perubahan Produk
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <template id="unitsJson">@json($unitsData)</template>
    <template id="existingConversionsJson">@json($existingConversionsData)</template>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-indigo: #4f46e5; --tr-indigo-hover: #4338ca; --tr-indigo-light: #e0e7ff;
            --tr-emerald: #10b981; --tr-emerald-hover: #059669; --tr-emerald-light: #dcfce7;
            --tr-danger: #ef4444; --tr-danger-light: #fef2f2;
            --tr-bg: #f8fafc; --tr-surface: #ffffff; --tr-border: #e2e8f0;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 2rem; }
        .tr-page { max-width: 1280px; margin: 0 auto; padding: 2rem 1.5rem; position: relative; }

        /* ── ANIMATIONS ── */
        .animate-fade-in { animation: fadeIn 0.4s ease forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.4s ease forwards; opacity: 0; transform: translateY(15px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem; }
        .tr-header-left { display: flex; align-items: flex-start; gap: 1.25rem; }
        .tr-btn-ghost { display: flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; color: var(--tr-text-muted); text-decoration: none; transition: 0.2s; background: #f1f5f9; height: fit-content; margin-top: 4px;}
        .tr-btn-ghost:hover { background: #e2e8f0; color: var(--tr-text-main); }
        
        .tr-eyebrow { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-indigo); margin-bottom: 0.25rem; }
        .tr-title { font-size: 1.625rem; font-weight: 900; margin: 0; color: var(--tr-text-main); letter-spacing: -0.02em; }
        .tr-header-actions { display: flex; gap: 0.75rem; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.75rem 1.25rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; white-space: nowrap; }
        .tr-btn-primary { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-primary:hover { background: var(--tr-indigo-hover); transform: translateY(-1px); }
        .tr-btn-emerald { background: var(--tr-emerald); color: white; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); }
        .tr-btn-emerald:hover { background: var(--tr-emerald-hover); transform: translateY(-1px); }
        .tr-btn-dark { background: var(--tr-text-main); color: white; }
        .tr-btn-dark:hover { background: #000; }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f1f5f9; border-color: var(--tr-text-muted); }
        .tr-btn-sm { padding: 0.5rem 1rem; font-size: 0.8rem; }

        /* ── ALERTS ── */
        .tr-alert-stack { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
        .tr-alert { padding: 1rem 1.25rem; border-radius: 12px; display: flex; align-items: center; gap: 12px; font-size: 0.9rem; font-weight: 600; line-height: 1.4; }
        .tr-alert-block { align-items: flex-start; flex-direction: column; gap: 8px; }
        .alert-header { display: flex; align-items: center; gap: 8px; }
        .tr-alert ul { margin: 0; padding-left: 28px; font-weight: 500; font-size: 0.85rem;}
        .tr-alert-danger { background: var(--tr-danger-light); color: #991b1b; border: 1px solid #fecaca; }
        .alert-icon { width: 20px; height: 20px; flex-shrink: 0; }

        /* ── CARDS ── */
        .tr-form-layout { display: flex; flex-direction: column; gap: 1.5rem; }
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.75rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background: #fafafa; }
        .tr-card-header-left { display: flex; align-items: center; gap: 1rem; }
        
        .tr-header-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .bg-emerald { background: var(--tr-emerald-light); color: var(--tr-emerald); }
        
        .tr-section-title { font-size: 1.125rem; font-weight: 800; margin: 0 0 2px 0; color: var(--tr-text-main); letter-spacing: -0.01em;}
        .tr-section-desc { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; font-weight: 500;}
        .tr-card-body { padding: 1.75rem; }

        /* ── FORM ELEMENTS ── */
        .tr-form-group { margin-bottom: 1.25rem; }
        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .tr-label { display: block; font-size: 0.8rem; font-weight: 800; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; }
        .tr-req { color: var(--tr-danger); }
        .tr-optional { font-weight: 500; text-transform: none; letter-spacing: 0; color: var(--tr-text-muted); }

        .tr-input, .tr-textarea, .tr-select { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--tr-border); border-radius: 10px; font-size: 0.95rem; background: #fcfcfd; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 600; outline: none; }
        .tr-input:focus, .tr-textarea:focus, .tr-select:focus { border-color: var(--tr-indigo); background: #fff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        .tr-textarea { resize: vertical; min-height: 80px; }
        .tr-disabled-input { background: #f1f5f9 !important; border-color: #e2e8f0 !important; color: #94a3b8; cursor: not-allowed; }
        
        .tr-input-hint { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 6px; font-weight: 600; }
        .tr-error-msg { color: var(--tr-danger); font-size: 0.75rem; font-weight: 700; margin-top: 4px; }
        .is-invalid { border-color: #fecaca; background: var(--tr-danger-light); }

        /* Input with Suffix (Pcs) */
        .tr-input-suffix-group { display: flex; align-items: stretch; }
        .tr-input-suffix-group .suffix { display: flex; align-items: center; padding: 0 1rem; background: #f1f5f9; border: 1.5px solid var(--tr-border); border-left: none; border-radius: 0 10px 10px 0; font-size: 0.85rem; font-weight: 800; color: var(--tr-text-muted); }
        .tr-input-suffix-group .tr-input { border-radius: 10px 0 0 10px; }

        /* Select Wrapper */
        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after { content: ''; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-size: contain; background-repeat: no-repeat; pointer-events: none; }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }

        /* Accordion Block */
        .tr-accordion-block { margin-top: 1rem; border: 1px solid var(--tr-border); border-radius: 12px; background: #fafafa; overflow: hidden; }
        .tr-accordion-trigger { display: block; padding: 1rem 1.25rem; cursor: pointer; user-select: none; background: #fff; }
        .tr-accordion-trigger::-webkit-details-marker { display: none; }
        .trigger-content { display: flex; align-items: center; gap: 8px; font-weight: 800; font-size: 0.9rem; color: var(--tr-text-main); }
        .trigger-arrow { width: 18px; height: 18px; transition: transform 0.2s; color: var(--tr-text-muted); }
        details[open] .trigger-arrow { transform: rotate(90deg); }
        .tr-accordion-body { padding: 1.5rem; border-top: 1px solid var(--tr-border); background: #fafafa; }

        .tr-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; height: fit-content; }
        .badge-gray { background: #e2e8f0; color: #475569; }

        /* ── NOTICE BOX ── */
        .tr-notice-box { display: flex; align-items: flex-start; gap: 12px; padding: 1rem 1.25rem; border-radius: 10px; border: 1px dashed; margin-bottom: 1rem; }
        .tr-notice-box.info { background: #eff6ff; border-color: #93c5fd; color: #1e3a8a; }
        .notice-icon { flex-shrink: 0; font-size: 1.1rem; }
        .notice-text strong { display: block; font-size: 0.85rem; font-weight: 800; margin-bottom: 2px; }
        .notice-text p { margin: 0; font-size: 0.8rem; line-height: 1.5; font-weight: 500;}

        /* ── MARKUP TOOLBAR ── */
        .tr-markup-toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; background: var(--tr-surface); border: 1px solid var(--tr-border); padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .markup-info { display: flex; align-items: center; gap: 12px; }
        .markup-info svg { color: var(--tr-warning); fill: var(--tr-warning-light); }
        .markup-info strong { display: block; font-size: 0.85rem; font-weight: 800; color: var(--tr-text-main); }
        .markup-info p { margin: 0; font-size: 0.75rem; color: var(--tr-text-muted); font-weight: 500; }
        .markup-inputs { display: flex; gap: 6px; flex-wrap: wrap; }
        .m-input { width: 85px; padding: 0.5rem 0.75rem; font-size: 0.8rem !important; text-align: center; }
        .m-btn { padding: 0.5rem 1rem; font-size: 0.8rem; border-radius: 8px; height: 38px; }

        /* ── DYNAMIC UNIT TABLE ── */
        .tr-unit-table-container { width: 100%; border: 1px solid var(--tr-border); border-radius: 12px; overflow-x: auto; background: #fff; }
        
        .unit-header-desktop { display: grid; grid-template-columns: 2fr 1.2fr repeat(7, 1.5fr) 0.8fr 0.8fr; gap: 0.5rem; padding: 0.85rem 1rem; background: #f8fafc; border-bottom: 1px solid var(--tr-border); min-width: 1100px; }
        .uh-col { font-size: 0.7rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .uh-col.c { text-align: center; } .uh-col.r { text-align: right; }

        .unit-rows-wrapper { display: flex; flex-direction: column; min-width: 1100px; }
        
        /* The row injected by JS */
        .unit-row { display: grid; grid-template-columns: 2fr 1.2fr repeat(7, 1.5fr) 0.8fr 0.8fr; gap: 0.5rem; padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; align-items: center; transition: 0.2s; }
        .unit-row:last-child { border-bottom: none; }
        .unit-row:hover { background: #fafafa; }
        .unit-row.is-base { background: #f0fdf4 !important; }
        
        .unit-cell { min-width: 0; }
        .unit-label-mobile { display: none; font-size: 0.65rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; margin-bottom: 4px; }
        .cell-center { display: flex; justify-content: center; align-items: center; }
        .cell-action { display: flex; justify-content: center; align-items: center; }

        /* Customizing inputs inside table */
        .unit-row .tr-select, .unit-row .tr-input { padding: 0.5rem; font-size: 0.85rem; border-radius: 8px; border-color: #cbd5e1;}
        .unit-row .tr-select { padding-right: 1.8rem; }
        .unit-row .tr-select-wrapper::after { right: 8px; }

        .btn-delete-row { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: var(--tr-danger-light); color: #991b1b; border: none; cursor: pointer; transition: 0.2s; }
        .btn-delete-row:hover { background: var(--tr-danger); color: white; }

        .tr-empty-state-box { text-align: center; padding: 3rem 2rem; background: #fafafa; border-bottom: 1px solid var(--tr-border); }
        .empty-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .tr-empty-state-box h4 { margin: 0 0 4px; font-size: 1.1rem; font-weight: 800; color: var(--tr-text-main); }
        .tr-empty-state-box p { margin: 0; font-size: 0.9rem; color: var(--tr-text-muted); }

        /* ── STICKY FOOTER ── */
        .tr-sticky-footer { position: sticky; bottom: 1.5rem; margin-top: 2rem; z-index: 50; }
        .footer-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding: 1rem 1.5rem; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05); }
        .footer-hint { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 700; color: var(--tr-warning); }
        .footer-actions { display: flex; gap: 1rem; }

        /* ── UTILS ── */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-weight: 700; letter-spacing: 0.05em; }
        .text-main { color: var(--tr-text-main); }
        .font-bold { font-weight: 800; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1100px) {
            .tr-unit-table-container { border: none; background: transparent; overflow: visible;}
            .unit-header-desktop { display: none; }
            .unit-rows-wrapper { min-width: 0; gap: 1rem; }
            .unit-row { grid-template-columns: 1fr 1fr; background: #fff; border: 1px solid var(--tr-border); border-radius: 12px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); align-items: start;}
            .unit-label-mobile { display: block; }
            .unit-cell.full-width { grid-column: 1 / -1; }
            .cell-center, .cell-action { justify-content: flex-start; }
            .btn-delete-row { width: 100%; justify-content: center; gap: 8px; margin-top: 0.5rem;}
            .btn-delete-row::after { content: 'Hapus Satuan Ini'; font-size: 0.85rem; font-weight: 700; }
        }
        @media (max-width: 768px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-header-actions { justify-content: space-between; }
            .tr-grid-2 { grid-template-columns: 1fr; }
            .tr-markup-toolbar { justify-content: center; }
            .markup-inputs { justify-content: center; }
            .footer-content { flex-direction: column; align-items: stretch; text-align: center; }
            .footer-hint { justify-content: center; }
            .footer-actions { display: grid; grid-template-columns: 1fr 2fr; }
        }
        @media (max-width: 480px) {
            .unit-row { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
    const allUnits = JSON.parse(document.getElementById('unitsJson')?.innerHTML || '[]');
    const existingConversions = JSON.parse(document.getElementById('existingConversionsJson')?.innerHTML || '[]');
    let unitRowIdx = 0;

    function num(v) {
        const n = Number(v);
        return Number.isFinite(n) ? n : 0;
    }
    function parseMoney(v) {
        const s = String(v ?? '');
        const digits = s.replace(/[^\d]/g, '');
        return digits ? Number(digits) : 0;
    }
    function formatMoney(v) {
        const n = Math.max(0, Math.floor(num(v)));
        return n.toLocaleString('id-ID');
    }
    function getBaseRowEl() {
        const rows = document.querySelectorAll('.unit-row');
        for (const row of rows) {
            const cb = row.querySelector('input[name$="[is_base_unit]"]');
            if (cb && cb.checked) return row;
        }
        return rows.length ? rows[0] : null;
    }
    function syncMasterFromBase() {
        const base = getBaseRowEl();
        if (!base) return;
        const ecer = base.querySelector('input[name$="[sell_price_ecer]"]');
        const modal = base.querySelector('input[name$="[purchase_price]"]');
        const priceHidden = document.getElementById('priceHidden');
        const purchaseHidden = document.getElementById('purchasePriceHidden');
        if (priceHidden && ecer) priceHidden.value = String(num(ecer.value));
        if (purchaseHidden && modal) purchaseHidden.value = String(num(modal.value));
    }
    function updateAllRowStyles() {
        document.querySelectorAll('.unit-row').forEach(row => {
            const cb = row.querySelector('input[name$="[is_base_unit]"]');
            if(cb?.checked) {
                row.classList.add('is-base');
                // For mobile responsive view borders
                row.style.borderColor = '#86efac'; 
            } else {
                row.classList.remove('is-base');
                row.style.borderColor = 'var(--tr-border)';
            }
        });
    }
    function onBaseChange(idx) {
        const row = document.getElementById(`unit-row-${idx}`);
        const checked = row?.querySelector('input[name$="[is_base_unit]"]')?.checked;
        if (checked) {
            document.querySelectorAll('input[name$="[is_base_unit]"]').forEach(cb => {
                if (cb !== row.querySelector('input[name$="[is_base_unit]"]')) cb.checked = false;
            });
        }
        updateAllRowStyles();
        syncMasterFromBase();
    }
    function removeUnitRow(idx) {
        document.getElementById(`unit-row-${idx}`)?.remove();
        if (!document.querySelectorAll('.unit-row').length) {
            document.getElementById('no-units-msg').style.display = 'block';
        }
        syncMasterFromBase();
    }
    function baseDefaults() {
        const base = getBaseRowEl();
        if (!base) {
            return {
                purchase_price: num(document.getElementById('purchasePriceHidden')?.value || 0),
                sell_price_ecer: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_grosir: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_jual1: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_jual2: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_jual3: num(document.getElementById('priceHidden')?.value || 0),
                sell_price_minimal: num(document.getElementById('priceHidden')?.value || 0),
            };
        }
        const getVal = (name) => num(base.querySelector(`input[name$="[${name}]"]`)?.value || 0);
        return {
            purchase_price: getVal('purchase_price'),
            sell_price_ecer: getVal('sell_price_ecer'),
            sell_price_grosir: getVal('sell_price_grosir'),
            sell_price_jual1: getVal('sell_price_jual1'),
            sell_price_jual2: getVal('sell_price_jual2'),
            sell_price_jual3: getVal('sell_price_jual3'),
            sell_price_minimal: getVal('sell_price_minimal'),
        };
    }
    function getHidden(row, key) {
        const el = row.querySelector(`input[type="hidden"][data-hidden="${key}"]`);
        return el ? num(el.value) : 0;
    }
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
        vis.addEventListener('input', () => { el.value = String(parseMoney(vis.value)); });
        vis.addEventListener('blur', () => { vis.value = formatMoney(el.value); });
    }
    function applyMarkup() {
        const pct = {
            sell_price_ecer: document.getElementById('pctEcer')?.value,
            sell_price_grosir: document.getElementById('pctGrosir')?.value,
            sell_price_jual1: document.getElementById('pctJ1')?.value,
            sell_price_jual2: document.getElementById('pctJ2')?.value,
            sell_price_jual3: document.getElementById('pctJ3')?.value,
            sell_price_minimal: document.getElementById('pctMin')?.value,
        };
        const keys = Object.keys(pct);
        document.querySelectorAll('.unit-row').forEach(row => {
            const modal = getHidden(row, 'purchase_price');
            keys.forEach(k => {
                const p = num(pct[k]);
                if (!pct[k] && pct[k] !== 0) return;
                if (!Number.isFinite(p) || p < 0) return;
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
        const factor = Math.max(1, Math.floor(num(d.conversion_factor || 1)));
        const base = baseDefaults();

        const unitOptions = allUnits.map(u => `<option value="${u.id}" ${u.id == (d.unit_id || '') ? 'selected' : ''}>${u.name}</option>`).join('');
        const val = (k, fallback) => String(Math.max(0, num(d[k] ?? fallback ?? 0)));
        
        const row = document.createElement('div');
        row.id = `unit-row-${idx}`;
        row.className = `unit-row ${isBase ? 'is-base' : ''}`;
        
        // Mobile layout needs explicit labels per cell, Desktop hides them via CSS
        row.innerHTML = `
            <div class="unit-cell">
                <div class="unit-label-mobile">Satuan</div>
                <div class="tr-select-wrapper">
                    <select name="units[${idx}][unit_id]" class="tr-select" required>
                        <option value="">-- Pilih --</option>${unitOptions}
                    </select>
                </div>
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Konversi</div>
                <input type="number" name="units[${idx}][conversion_factor]" value="${factor}" min="1" class="tr-input" style="text-align:center; font-weight:800;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Harga Modal</div>
                <input type="hidden" name="units[${idx}][purchase_price]" data-hidden="purchase_price" value="${val('purchase_price', isBase ? base.purchase_price : base.purchase_price * factor)}">
                <input type="text" inputmode="numeric" data-visible="purchase_price" class="tr-input tr-font-mono" style="text-align:right;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Harga Eceran</div>
                <input type="hidden" name="units[${idx}][sell_price_ecer]" data-hidden="sell_price_ecer" value="${val('sell_price_ecer', isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_ecer" class="tr-input tr-font-mono" style="text-align:right;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Harga Grosir</div>
                <input type="hidden" name="units[${idx}][sell_price_grosir]" data-hidden="sell_price_grosir" value="${val('sell_price_grosir', isBase ? base.sell_price_grosir : base.sell_price_grosir * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_grosir" class="tr-input tr-font-mono" style="text-align:right;" required>
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Harga Jual 1</div>
                <input type="hidden" name="units[${idx}][sell_price_jual1]" data-hidden="sell_price_jual1" value="${val('sell_price_jual1', isBase ? base.sell_price_jual1 : base.sell_price_jual1 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual1" class="tr-input tr-font-mono" style="text-align:right;">
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Harga Jual 2</div>
                <input type="hidden" name="units[${idx}][sell_price_jual2]" data-hidden="sell_price_jual2" value="${val('sell_price_jual2', isBase ? base.sell_price_jual2 : base.sell_price_jual2 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual2" class="tr-input tr-font-mono" style="text-align:right;">
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Harga Jual 3</div>
                <input type="hidden" name="units[${idx}][sell_price_jual3]" data-hidden="sell_price_jual3" value="${val('sell_price_jual3', isBase ? base.sell_price_jual3 : base.sell_price_jual3 * factor)}">
                <input type="text" inputmode="numeric" data-visible="sell_price_jual3" class="tr-input tr-font-mono" style="text-align:right;">
            </div>
            <div class="unit-cell">
                <div class="unit-label-mobile">Jual Minimal</div>
                <input type="hidden" name="units[${idx}][sell_price_minimal]" data-hidden="sell_price_minimal" value="${val('sell_price_minimal', (d.sell_price_minimal ?? (isBase ? base.sell_price_ecer : base.sell_price_ecer * factor)))}">
                <input type="text" inputmode="numeric" data-visible="sell_price_minimal" class="tr-input tr-font-mono" style="text-align:right;">
            </div>
            <div class="unit-cell full-width">
                <div class="unit-label-mobile">Satuan Basis (Terkecil)</div>
                <div class="cell-center">
                    <input type="checkbox" name="units[${idx}][is_base_unit]" value="1" ${isBase ? 'checked' : ''} onchange="onBaseChange(${idx})" style="width:20px;height:20px;accent-color:var(--tr-emerald);cursor:pointer;">
                </div>
            </div>
            <div class="unit-cell full-width">
                <div class="cell-action">
                    <button type="button" class="btn-delete-row" onclick="removeUnitRow(${idx})" title="Hapus Satuan">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(row);
        ['purchase_price','sell_price_ecer','sell_price_grosir','sell_price_jual1','sell_price_jual2','sell_price_jual3','sell_price_minimal'].forEach(k => wireMoneyInput(row, k));
        updateAllRowStyles();
        syncMasterFromBase();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (existingConversions.length > 0) {
            existingConversions.forEach(data => addUnitRow(data));
        } else {
            document.getElementById('no-units-msg').style.display = 'block';
        }
        document.getElementById('unit-rows-container')?.addEventListener('input', (e) => {
            const base = getBaseRowEl();
            if (!base) return;
            if (base.contains(e.target)) syncMasterFromBase();
        });
        document.getElementById('btnApplyMarkup')?.addEventListener('click', applyMarkup);
        document.getElementById('product-form')?.addEventListener('submit', () => { syncMasterFromBase(); });
        syncMasterFromBase();
    });
    </script>
    @endpush
</x-app-layout>