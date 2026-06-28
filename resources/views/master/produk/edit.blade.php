<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { box-sizing: border-box; }

        .pe-page { max-width: 72rem; margin: 0 auto; padding: 1.75rem 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; }

        .pe-nav { display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem; }
        .pe-back { display: flex; align-items: center; gap: 6px; text-decoration: none; color: #64748b; font-size: 0.8125rem; font-weight: 600; transition: all 0.2s; background: #fff; padding: 0.5rem 1rem; border-radius: 99px; border: 1px solid #e2e8f0; }
        .pe-back:hover { color: #0f172a; border-color: #cbd5e1; }
        .pe-sep { color: #cbd5e1; font-size: 0.8125rem; }
        .pe-crumb { font-size: 0.8125rem; font-weight: 700; color: #0f172a; }

        .pe-title { font-size: 1.375rem; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; margin-bottom: 0.25rem; }
        .pe-subtitle { font-size: 0.8125rem; color: #64748b; margin-bottom: 1.75rem; }

        .pe-grid-layout { display: grid; grid-template-columns: 1fr; gap: 1.5rem; align-items: start; }
        @media (min-width: 1024px) { .pe-grid-layout { grid-template-columns: 2fr 1fr; } }

        .pe-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.04); margin-bottom: 1.5rem; transition: box-shadow 0.3s; }
        .pe-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
        .pe-card-hdr { padding: 1.125rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid #f1f5f9; }
        .pe-card-ico { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pe-card-ico svg { width: 17px; height: 17px; }
        .pe-card-title { font-size: 0.875rem; font-weight: 700; color: #0f172a; }
        .pe-card-desc { font-size: 0.6875rem; color: #94a3b8; margin-left: auto; }
        .pe-card-body { padding: 1.5rem; }

        .card-main .pe-card-hdr { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
        .card-main .pe-card-ico { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }
        .card-price .pe-card-hdr { background: linear-gradient(135deg, #ecfdf5, #d1fae5); }
        .card-price .pe-card-ico { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
        .card-multi .pe-card-hdr { background: linear-gradient(135deg, #fffbeb, #fef3c7); }
        .card-multi .pe-card-ico { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }

        .pe-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.125rem; }
        .pe-full { grid-column: 1 / -1; }
        .pe-fg { display: flex; flex-direction: column; gap: 0.375rem; }
        .pe-lbl { display: flex; align-items: center; gap: 5px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; }
        .pe-lbl svg { width: 13px; height: 13px; color: #94a3b8; flex-shrink: 0; }
        .pe-req { color: #ef4444; }
        .pe-opt { color: #94a3b8; font-weight: 500; text-transform: none; letter-spacing: 0; font-size: 0.6875rem; }
        .pe-inp, .pe-sel, .pe-txt { width: 100%; padding: 0.75rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 10px; background: #fcfcfd; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #0f172a; transition: all 0.2s; outline: none; }
        .pe-inp:hover, .pe-sel:hover, .pe-txt:hover { border-color: #94a3b8; }
        .pe-inp:focus, .pe-sel:focus, .pe-txt:focus { border-color: #3b82f6; background: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
        .pe-txt { resize: vertical; min-height: 90px; line-height: 1.5; }
        .pe-inp::placeholder, .pe-txt::placeholder { color: #cbd5e1; }
        .pe-sel { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px; padding-right: 2.5rem; }
        .pe-err { color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-top: 2px; display: flex; align-items: center; gap: 4px; }
        .pe-err svg { width: 13px; height: 13px; flex-shrink: 0; }
        .pe-inp.is-invalid, .pe-sel.is-invalid { border-color: #fecaca; background: #fef2f2; }
        .pe-hint { font-size: 0.6875rem; color: #94a3b8; margin-top: 2px; display: flex; align-items: center; gap: 4px; }
        .pe-hint svg { width: 12px; height: 12px; flex-shrink: 0; }
        .pe-inp:disabled, .pe-inp[readonly] { background: #f1f5f9; color: #64748b; cursor: not-allowed; border-color: #e2e8f0; }

        .pe-money-wrap { position: relative; }
        .pe-money-prefix { position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); font-size: 0.8125rem; font-weight: 600; color: #94a3b8; pointer-events: none; }
        .pe-money-inp { padding-left: 2.5rem !important; }

        .pe-actions { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
        .pe-actions-left { font-size: 0.75rem; color: #94a3b8; }
        .pe-actions-right { display: flex; align-items: center; gap: 0.75rem; }
        .pe-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.75rem 1.5rem; border-radius: 12px; font-size: 0.8125rem; font-weight: 700; cursor: pointer; transition: all 0.2s; border: 1px solid transparent; text-decoration: none; font-family: inherit; }
        .pe-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none !important; }
        .pe-btn-ghost { background: transparent; border-color: #e2e8f0; color: #64748b; }
        .pe-btn-ghost:hover { background: #f8fafc; color: #0f172a; }
        .pe-btn-primary { background: linear-gradient(135deg, #4f46e5, #3b82f6); color: #fff; box-shadow: 0 4px 14px rgba(59,130,246,0.3); }
        .pe-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(59,130,246,0.4); }
        .pe-btn-primary.pe-loading { pointer-events: none; }
        .pe-btn-primary .pe-spinner { display: none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: pe-spin 0.6s linear infinite; }
        .pe-btn-primary.pe-loading .pe-spinner { display: block; }
        .pe-btn-primary.pe-loading .pe-btn-text { display: none; }
        @keyframes pe-spin { to { transform: rotate(360deg); } }

        .pe-alert { padding: 1rem 1.25rem; border-radius: 12px; font-size: 0.8125rem; font-weight: 500; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
        .pe-alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .pe-alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }

        .pe-char-count { font-size: 0.6875rem; color: #94a3b8; text-align: right; margin-top: 3px; }
        .pe-char-count.warning { color: #f59e0b; }
        .pe-char-count.danger { color: #ef4444; }

        .mu-box { border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; margin-top: 0.75rem; background: #fafbfc; transition: all 0.3s; }
        .mu-box:hover { border-color: #cbd5e1; }
        .mu-grid { display: grid; grid-template-columns: 2fr 1fr 1.5fr 1.5fr 1.5fr auto; gap: 0.625rem; align-items: start; }
        .mu-lbl { font-size: 0.6875rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem; }
        .mu-inp, .mu-sel { width: 100%; padding: 0.55rem 0.7rem; border: 1.5px solid #e2e8f0; border-radius: 8px; background: #fff; font-family: inherit; font-size: 0.8125rem; color: #0f172a; transition: all 0.2s; outline: none; }
        .mu-inp:focus, .mu-sel:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .mu-sel { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 14px; padding-right: 2rem; }
        .mu-remove { padding: 0.55rem 0.65rem; border-radius: 8px; border: none; background: #fee2e2; color: #ef4444; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; height: 38px; margin-top: 1.4rem; }
        .mu-remove:hover { background: #fecaca; }
        .mu-extras { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 0.625rem; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed #e2e8f0; }
        .mu-add-btn { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 1rem; padding: 0.875rem; border: 2px dashed #cbd5e1; background: transparent; border-radius: 12px; font-size: 0.8125rem; font-weight: 700; color: #64748b; cursor: pointer; width: 100%; transition: all 0.3s; font-family: inherit; }
        .mu-add-btn:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }

        @media (max-width: 768px) {
            .pe-grid2 { grid-template-columns: 1fr; }
            .mu-grid { grid-template-columns: 1fr 1fr; }
            .mu-extras { grid-template-columns: 1fr 1fr; }
            .mu-remove { margin-top: 0; }
            .pe-actions { flex-direction: column; align-items: stretch; }
            .pe-actions-left { text-align: center; }
            .pe-actions-right { flex-direction: column; }
            .pe-actions-right .pe-btn { justify-content: center; }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    <div class="pe-page">

        <nav class="pe-nav">
            <a href="{{ route('master.produk') }}" class="pe-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Master Produk
            </a>
            <span class="pe-sep">/</span>
            <span class="pe-crumb">Edit Produk</span>
        </nav>

        <h1 class="pe-title">Edit Produk</h1>
        <p class="pe-subtitle">Perbarui data produk <strong>{{ $product->name }}</strong>.</p>

        @if($errors->any())
            <div class="pe-alert pe-alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Mohon periksa kembali input Anda. Ada <strong>{{ $errors->count() }}</strong> field yang perlu diperbaiki.</span>
            </div>
        @endif

        @if(session('error'))
            <div class="pe-alert pe-alert-warning">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('master.produk.update', $product->id) }}" id="produk-form" x-data="produkForm()">
            @csrf @method('PUT')

            <div class="pe-grid-layout">
                <div class="pe-col-main">
                    {{-- CARD 1: Informasi Dasar --}}
                    <div class="pe-card card-main">
                        <div class="pe-card-hdr">
                            <div class="pe-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            </div>
                            <div class="pe-card-title">Informasi Dasar Produk</div>
                            <div class="pe-card-desc">Data identitas produk</div>
                        </div>
                        <div class="pe-card-body">
                            <div class="pe-grid2">
                                <div class="pe-fg pe-full">
                                    <label class="pe-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        Nama Produk <span class="pe-req">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required maxlength="255"
                                        class="pe-inp @error('name') is-invalid @enderror"
                                        placeholder="Contoh: Coca-Cola 250ml" autocomplete="off">
                                    @error('name')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pe-fg">
                                    <label class="pe-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                        Kategori <span class="pe-req">*</span>
                                    </label>
                                    <select name="category_id" required class="pe-sel @error('category_id') is-invalid @enderror">
                                        <option value="">— Pilih kategori —</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pe-fg">
                                    <label class="pe-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                        Satuan Dasar <span class="pe-opt">(Opsional)</span>
                                    </label>
                                    <select name="unit_id" class="pe-sel @error('unit_id') is-invalid @enderror">
                                        <option value="">— Pilih satuan —</option>
                                        @foreach($units as $u)
                                            <option value="{{ $u->id }}" {{ old('unit_id', $product->unit_id) == $u->id ? 'selected' : '' }}>{{ $u->name }} @if($u->abbreviation)({{ $u->abbreviation }})@endif</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pe-fg">
                                    <label class="pe-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M15 3v18"/></svg>
                                        SKU <span class="pe-opt">(Otomatis jika kosong)</span>
                                    </label>
                                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="pe-inp @error('sku') is-invalid @enderror" placeholder="SKU001" autocomplete="off">
                                    @error('sku')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pe-fg pe-full">
                                    <label class="pe-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                        Barcode <span class="pe-opt">(Opsional)</span>
                                    </label>
                                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="pe-inp @error('barcode') is-invalid @enderror" placeholder="Scan atau ketik barcode" autocomplete="off">
                                    @error('barcode')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="pe-fg" style="margin-top:1.25rem;">
                                <label class="pe-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Deskripsi Produk <span class="pe-opt">(Opsional)</span>
                                </label>
                                <textarea name="description" rows="3" maxlength="500" class="pe-txt @error('description') is-invalid @enderror" placeholder="Tuliskan keterangan detail produk..." id="desc-input">{{ old('description', $product->description) }}</textarea>
                                <div class="pe-char-count" id="desc-count">{{ strlen(old('description', $product->description ?? '')) }} / 500</div>
                                @error('description')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: Konversi Satuan --}}
                    <div class="pe-card card-multi">
                        <div class="pe-card-hdr">
                            <div class="pe-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            </div>
                            <div class="pe-card-title">Konversi Satuan (Multi-Unit)</div>
                            <div class="pe-card-desc">Opsional</div>
                        </div>
                        <div class="pe-card-body">
                            <p style="font-size:0.8125rem; color:#64748b; margin:0 0 1.25rem; line-height:1.5;">
                                Tambahkan jika produk ini juga dijual dalam kemasan berbeda (Dus, Karton, Renceng, dll).
                            </p>

                            <template x-for="(row, idx) in units" :key="idx">
                                <div class="mu-box">
                                    <div class="mu-grid">
                                        <div>
                                            <div class="mu-lbl">Satuan</div>
                                            <select :name="`units[${idx}][unit_id]`" class="mu-sel" x-model="row.unit_id" required>
                                                <option value="">Pilih...</option>
                                                @foreach($units as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }} @if($u->abbreviation)({{ $u->abbreviation }})@endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Isi (Faktor)</div>
                                            <input type="number" :name="`units[${idx}][conversion_factor]`" class="mu-inp" x-model="row.factor" @input="calcFactor(idx)" min="0.0001" step="0.0001" placeholder="Mis: 12" required>
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Beli</div>
                                            <input type="text" data-currency :name="`units[${idx}][purchase_price]`" class="mu-inp" x-model="row.beli" @input="calcPrices(idx, 'beli')" placeholder="0" required>
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Ecer</div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_ecer]`" class="mu-inp" x-model="row.ecer" @input="calcPrices(idx, 'ecer')" placeholder="0" required>
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Grosir</div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_grosir]`" class="mu-inp" x-model="row.grosir" @input="calcPrices(idx, 'grosir')" placeholder="0" required>
                                        </div>
                                        <button type="button" class="mu-remove" @click="removeUnit(idx)" title="Hapus">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                        </button>
                                    </div>

                                    <div class="mu-extras">
                                        <div>
                                            <div class="mu-lbl">Hrg Jual 1 <span class="pe-opt">(ops.)</span></div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_jual1]`" class="mu-inp" x-model="row.jual1" @input="calcPrices(idx, 'jual1')" placeholder="0">
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Jual 2 <span class="pe-opt">(ops.)</span></div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_jual2]`" class="mu-inp" x-model="row.jual2" @input="calcPrices(idx, 'jual2')" placeholder="0">
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Jual 3 <span class="pe-opt">(ops.)</span></div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_jual3]`" class="mu-inp" x-model="row.jual3" @input="calcPrices(idx, 'jual3')" placeholder="0">
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Minimal <span class="pe-opt">(ops.)</span></div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_minimal]`" class="mu-inp" x-model="row.minimal" @input="calcPrices(idx, 'minimal')" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" class="mu-add-btn" @click="addUnit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Tambah Satuan Konversi
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pe-col-side">
                    {{-- CARD 2: Harga & Stok --}}
                    <div class="pe-card card-price">
                        <div class="pe-card-hdr">
                            <div class="pe-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            </div>
                            <div class="pe-card-title">Harga & Stok</div>
                            <div class="pe-card-desc">Nilai dasar produk</div>
                        </div>
                        <div class="pe-card-body">
                            <div class="pe-fg" style="margin-bottom: 1.25rem;">
                                <label class="pe-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 10h20"/></svg>
                                    Harga Beli (Modal) <span class="pe-opt">(Opsional)</span>
                                </label>
                                <div class="pe-money-wrap">
                                    <span class="pe-money-prefix">Rp</span>
                                    <input type="text" inputmode="numeric" data-currency name="purchase_price" value="{{ old('purchase_price', (float) $product->purchase_price) }}"
                                        class="pe-inp pe-money-inp @error('purchase_price') is-invalid @enderror" placeholder="0">
                                </div>
                                @error('purchase_price')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div class="pe-fg" style="margin-bottom: 1.25rem;">
                                <label class="pe-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8l-4 4-4-4M12 16V8"/></svg>
                                    Harga Jual <span class="pe-req">*</span>
                                </label>
                                <div class="pe-money-wrap">
                                    <span class="pe-money-prefix">Rp</span>
                                    <input type="text" inputmode="numeric" data-currency name="price" value="{{ old('price', (float) $product->price) }}" required
                                        class="pe-inp pe-money-inp @error('price') is-invalid @enderror" placeholder="0">
                                </div>
                                @error('price')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div class="pe-separator" style="height:1px;background:#f1f5f9;margin:1rem 0;"></div>

                            <div class="pe-fg" style="margin-bottom: 1.25rem;">
                                <label class="pe-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                    Stok Tersedia
                                </label>
                                <input type="number" value="{{ (float) $product->stock }}" readonly
                                    class="pe-inp" placeholder="0">
                                <div class="pe-hint">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                    Stok hanya bisa diubah melalui fitur Penyesuaian Stok
                                </div>
                            </div>

                            <div class="pe-fg">
                                <label class="pe-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Batas Stok Minimum <span class="pe-req">*</span>
                                </label>
                                <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required min="0" step="1"
                                    class="pe-inp @error('min_stock') is-invalid @enderror" placeholder="0">
                                <div class="pe-hint">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                    Peringatan jika stok di bawah batas ini
                                </div>
                                @error('min_stock')<div class="pe-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top:0.5rem;">
                <div class="pe-actions">
                    <div class="pe-actions-left">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        <span>Field bertanda <span class="pe-req">*</span> wajib diisi</span>
                    </div>
                    <div class="pe-actions-right">
                        <a href="{{ route('master.produk') }}" class="pe-btn pe-btn-ghost">Batal</a>
                        <button type="submit" class="pe-btn pe-btn-primary" id="submit-btn">
                            <span class="pe-spinner"></span>
                            <span class="pe-btn-text">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                                Simpan Perubahan
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    @php
        $initialUnits = $product->unitConversions->map(function($uc) {
            return [
                'unit_id' => $uc->unit_id,
                'factor' => (float)$uc->conversion_factor,
                'beli' => (float)$uc->purchase_price,
                'ecer' => (float)$uc->sell_price_ecer,
                'grosir' => (float)$uc->sell_price_grosir,
                'jual1' => (float)($uc->sell_price_jual1 ?? 0),
                'jual2' => (float)($uc->sell_price_jual2 ?? 0),
                'jual3' => (float)($uc->sell_price_jual3 ?? 0),
                'minimal' => (float)($uc->sell_price_minimal ?? 0),
            ];
        });
    @endphp
    <script>
        const initialUnits = @json($initialUnits);

        setTimeout(() => {
            if (window.formatCurrency) {
                document.querySelectorAll('[data-currency]').forEach(el => {
                    if (el.value) el.value = window.formatCurrency(el.value);
                });
            }
        }, 100);

        document.addEventListener('alpine:init', () => {
            Alpine.data('produkForm', () => ({
                units: initialUnits,
                addUnit() {
                    this.units.push({
                        unit_id: '',
                        factor: 1,
                        beli: '',
                        ecer: '',
                        grosir: '',
                        jual1: '',
                        jual2: '',
                        jual3: '',
                        minimal: ''
                    });
                },
                removeUnit(idx) {
                    this.units.splice(idx, 1);
                },
                calcPrices(idx, field) {
                    const row = this.units[idx];
                    const raw = row[field];
                    const val = window.parseCurrency ? parseFloat(window.parseCurrency(raw)) || 0 : 0;
                    const factor = parseFloat(row.factor) || 1;

                    if (factor === 1) {
                        this.units.forEach((u, i) => {
                            if (i !== idx) {
                                const targetFactor = parseFloat(u.factor) || 1;
                                const result = val * targetFactor;
                                u[field] = window.formatCurrency ? window.formatCurrency(result) : String(result);
                            }
                        });
                    }
                },
                calcFactor(idx) {
                    const row = this.units[idx];
                    const factor = parseFloat(row.factor) || 1;

                    if (factor !== 1) {
                        const baseIdx = this.units.findIndex(u => parseFloat(u.factor) === 1);
                        if (baseIdx >= 0) {
                            const base = this.units[baseIdx];
                            const parse = (v) => window.parseCurrency ? parseFloat(window.parseCurrency(v)) || 0 : 0;
                            const fmt = (v) => window.formatCurrency ? window.formatCurrency(v) : String(v);
                            row.beli = fmt(parse(base.beli) * factor);
                            row.ecer = fmt(parse(base.ecer) * factor);
                            row.grosir = fmt(parse(base.grosir) * factor);
                            row.jual1 = fmt(parse(base.jual1) * factor);
                            row.jual2 = fmt(parse(base.jual2) * factor);
                            row.jual3 = fmt(parse(base.jual3) * factor);
                            row.minimal = fmt(parse(base.minimal) * factor);
                        }
                    } else {
                        ['beli', 'ecer', 'grosir', 'jual1', 'jual2', 'jual3', 'minimal'].forEach(f => this.calcPrices(idx, f));
                    }
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('produk-form');
            const submitBtn = document.getElementById('submit-btn');
            const descInput = document.getElementById('desc-input');
            const descCount = document.getElementById('desc-count');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.classList.add('pe-loading');
            });

            if (descInput) {
                function updateDescCount() {
                    const len = descInput.value.length;
                    const max = 500;
                    descCount.textContent = len + ' / ' + max;
                    descCount.className = 'pe-char-count';
                    if (len > max * 0.9) descCount.classList.add('danger');
                    else if (len > max * 0.75) descCount.classList.add('warning');
                }
                descInput.addEventListener('input', updateDescCount);
                updateDescCount();
            }

            let formDirty = false;
            form.addEventListener('input', function() {
                if (!formDirty) {
                    formDirty = true;
                    window.addEventListener('beforeunload', function(e) {
                        e.preventDefault();
                        e.returnValue = '';
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>