<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { box-sizing: border-box; }

        .pc-page {
            max-width: 76rem;
            margin: 0 auto;
            padding: 1.75rem 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        /* Breadcrumb */
        .pc-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        .pc-back {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #64748b;
            font-size: 0.8125rem;
            font-weight: 600;
            transition: all 0.2s;
            background: #fff;
            padding: 0.5rem 1rem;
            border-radius: 99px;
            border: 1px solid #e2e8f0;
        }
        .pc-back:hover {
            color: #0f172a;
            border-color: #cbd5e1;
        }
        .pc-sep {
            color: #cbd5e1;
            font-size: 0.8125rem;
        }
        .pc-crumb {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #0f172a;
        }

        .pc-title {
            font-size: 1.375rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
            margin-bottom: 0.25rem;
        }
        .pc-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin-bottom: 1.75rem;
        }

        /* Grid Layout */
        .pc-grid-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        @media (min-width: 1024px) {
            .pc-grid-layout {
                grid-template-columns: 2fr 1fr;
            }
        }

        /* Card */
        .pc-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.5rem;
            transition: box-shadow 0.3s;
        }
        .pc-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }
        .pc-card-hdr {
            padding: 1.125rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .pc-card-ico {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .pc-card-ico svg {
            width: 17px;
            height: 17px;
        }
        .pc-card-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
        }
        .pc-card-desc {
            font-size: 0.6875rem;
            color: #94a3b8;
            margin-left: auto;
        }
        .pc-card-body {
            padding: 1.5rem;
        }

        .card-main .pc-card-hdr {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }
        .card-main .pc-card-ico {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
        }
        .card-price .pc-card-hdr {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }
        .card-price .pc-card-ico {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }
        .card-multi .pc-card-hdr {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }
        .card-multi .pc-card-ico {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        /* Form Fields */
        .pc-grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.125rem;
        }
        .pc-full {
            grid-column: 1 / -1;
        }
        .pc-fg {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }
        .pc-lbl {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
        }
        .pc-lbl svg {
            width: 13px;
            height: 13px;
            color: #94a3b8;
            flex-shrink: 0;
        }
        .pc-req {
            color: #ef4444;
        }
        .pc-opt {
            color: #94a3b8;
            font-weight: 500;
            text-transform: none;
            letter-spacing: 0;
            font-size: 0.6875rem;
        }
        .pc-inp,
        .pc-sel,
        .pc-txt {
            width: 100%;
            padding: 0.75rem 0.875rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #fcfcfd;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            color: #0f172a;
            transition: all 0.2s;
            outline: none;
        }
        .pc-inp:hover,
        .pc-sel:hover,
        .pc-txt:hover {
            border-color: #94a3b8;
        }
        .pc-inp:focus,
        .pc-sel:focus,
        .pc-txt:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }
        .pc-txt {
            resize: vertical;
            min-height: 90px;
            line-height: 1.5;
        }
        .pc-inp::placeholder,
        .pc-txt::placeholder {
            color: #cbd5e1;
        }
        .pc-sel {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px;
            padding-right: 2.5rem;
        }
        .pc-err {
            color: #ef4444;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .pc-err svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
        }
        .pc-inp.is-invalid,
        .pc-sel.is-invalid {
            border-color: #fecaca;
            background: #fef2f2;
        }
        .pc-inp.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
        }
        .pc-hint {
            font-size: 0.6875rem;
            color: #94a3b8;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .pc-hint svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }

        /* Money Input */
        .pc-money-wrap {
            position: relative;
        }
        .pc-money-prefix {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8125rem;
            font-weight: 600;
            color: #94a3b8;
            pointer-events: none;
        }
        .pc-money-inp {
            padding-left: 2.5rem !important;
        }
        .pc-money-suffix {
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.75rem;
            color: #94a3b8;
            pointer-events: none;
        }

        /* Actions */
        .pc-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }
        .pc-actions-left {
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .pc-actions-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .pc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
            font-family: inherit;
        }
        .pc-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        .pc-btn-ghost {
            background: transparent;
            border-color: #e2e8f0;
            color: #64748b;
        }
        .pc-btn-ghost:hover {
            background: #f8fafc;
            color: #0f172a;
        }
        .pc-btn-primary {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #fff;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
        }
        .pc-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
        }
        .pc-btn-primary.loading {
            pointer-events: none;
        }
        .pc-btn-primary .pc-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: pc-spin 0.6s linear infinite;
        }
        .pc-btn-primary.loading .pc-spinner {
            display: block;
        }
        .pc-btn-primary.loading .pc-btn-text {
            display: none;
        }
        @keyframes pc-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Alert */
        .pc-alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .pc-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        /* Multi-Units */
        .mu-box {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.25rem;
            margin-top: 0.75rem;
            background: #fafbfc;
            transition: all 0.3s;
        }
        .mu-box:hover {
            border-color: #cbd5e1;
        }
        .mu-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr 1.5fr 1.5fr auto;
            gap: 0.625rem;
            align-items: start;
        }
        .mu-lbl {
            font-size: 0.6875rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.25rem;
        }
        .mu-inp,
        .mu-sel {
            width: 100%;
            padding: 0.55rem 0.7rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            font-family: inherit;
            font-size: 0.8125rem;
            color: #0f172a;
            transition: all 0.2s;
            outline: none;
        }
        .mu-inp:focus,
        .mu-sel:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .mu-sel {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 14px;
            padding-right: 2rem;
        }
        .mu-remove {
            padding: 0.55rem 0.65rem;
            border-radius: 8px;
            border: none;
            background: #fee2e2;
            color: #ef4444;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            margin-top: 1.4rem;
        }
        .mu-remove:hover {
            background: #fecaca;
        }
        .mu-extras {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 0.625rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed #e2e8f0;
        }
        .mu-add-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 1rem;
            padding: 0.875rem;
            border: 2px dashed #cbd5e1;
            background: transparent;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            font-family: inherit;
        }
        .mu-add-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
            background: #eff6ff;
        }

        .pc-char-count {
            font-size: 0.6875rem;
            color: #94a3b8;
            text-align: right;
            margin-top: 3px;
        }
        .pc-char-count.warning {
            color: #f59e0b;
        }
        .pc-char-count.danger {
            color: #ef4444;
        }

        @media (max-width: 768px) {
            .pc-grid2 {
                grid-template-columns: 1fr;
            }
            .mu-grid {
                grid-template-columns: 1fr 1fr;
            }
            .mu-extras {
                grid-template-columns: 1fr 1fr;
            }
            .mu-remove {
                margin-top: 0;
            }
            .pc-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .pc-actions-left {
                text-align: center;
            }
            .pc-actions-right {
                flex-direction: column;
            }
            .pc-actions-right .pc-btn {
                justify-content: center;
            }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    <div class="pc-page">

        {{-- Breadcrumb --}}
        <nav class="pc-nav">
            <a href="{{ route('master.produk') }}" class="pc-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
            <span class="pc-sep">/</span>
            <span class="pc-crumb">Tambah Produk Baru</span>
        </nav>

        <h1 class="pc-title">Tambah Produk Baru</h1>
        <p class="pc-subtitle">Lengkapi data produk untuk ditambahkan ke dalam sistem inventory.</p>

        {{-- Error Alert --}}
        @if($errors->any())
            <div class="pc-alert pc-alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Mohon periksa kembali input Anda. Ada <strong>{{ $errors->count() }}</strong> field yang perlu diperbaiki.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('master.produk.store') }}" id="produk-form" x-data="produkForm()">
            @csrf

            <div class="pc-grid-layout">
                {{-- Left Column --}}
                <div class="pc-col-main">

                    {{-- CARD 1: Informasi Dasar --}}
                    <div class="pc-card card-main">
                        <div class="pc-card-hdr">
                            <div class="pc-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            </div>
                            <div class="pc-card-title">Informasi Dasar Produk</div>
                            <div class="pc-card-desc">Data identitas produk</div>
                        </div>
                        <div class="pc-card-body">
                            <div class="pc-grid2">
                                <div class="pc-fg pc-full">
                                    <label class="pc-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        Nama Produk <span class="pc-req">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                                        class="pc-inp @error('name') is-invalid @enderror"
                                        placeholder="Contoh: Coca-Cola 250ml"
                                        autocomplete="off">
                                    @error('name')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pc-fg">
                                    <label class="pc-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                        Kategori <span class="pc-req">*</span>
                                    </label>
                                    <select name="category_id" required class="pc-sel @error('category_id') is-invalid @enderror">
                                        <option value="">— Pilih kategori —</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pc-fg">
                                    <label class="pc-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                        Satuan Dasar <span class="pc-opt">(Opsional)</span>
                                    </label>
                                    <select name="unit_id" class="pc-sel @error('unit_id') is-invalid @enderror">
                                        <option value="">— Pilih satuan —</option>
                                        @foreach($units as $u)
                                            <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} @if($u->abbreviation)({{ $u->abbreviation }})@endif</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pc-fg">
                                    <label class="pc-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M15 3v18"/></svg>
                                        SKU <span class="pc-opt">(Otomatis jika kosong)</span>
                                    </label>
                                    <input type="text" name="sku" value="{{ old('sku') }}" class="pc-inp @error('sku') is-invalid @enderror" placeholder="SKU001" autocomplete="off">
                                    @error('sku')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>

                                <div class="pc-fg pc-full">
                                    <label class="pc-lbl">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                        Barcode <span class="pc-opt">(Opsional)</span>
                                    </label>
                                    <input type="text" name="barcode" value="{{ old('barcode') }}" class="pc-inp @error('barcode') is-invalid @enderror" placeholder="Scan atau ketik barcode" autocomplete="off">
                                    @error('barcode')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="pc-fg" style="margin-top:1.25rem;">
                                <label class="pc-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Deskripsi Produk <span class="pc-opt">(Opsional)</span>
                                </label>
                                <textarea name="description" rows="3" maxlength="500" class="pc-txt @error('description') is-invalid @enderror" placeholder="Tuliskan keterangan detail produk..." id="desc-input">{{ old('description') }}</textarea>
                                <div class="pc-char-count" id="desc-count">0 / 500</div>
                                @error('description')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: Konversi Satuan --}}
                    <div class="pc-card card-multi">
                        <div class="pc-card-hdr">
                            <div class="pc-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            </div>
                            <div class="pc-card-title">Konversi Satuan (Multi-Unit)</div>
                            <div class="pc-card-desc">Opsional</div>
                        </div>
                        <div class="pc-card-body">
                            <p style="font-size:0.8125rem; color:#64748b; margin:0 0 1.25rem; line-height:1.5;">
                                Tambahkan jika produk ini juga dijual dalam kemasan berbeda (Dus, Karton, Renceng, dll). Harga akan terhitung otomatis berdasarkan faktor konversi.
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
                                            <div class="mu-lbl">Hrg Jual 1 <span class="pc-opt">(ops.)</span></div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_jual1]`" class="mu-inp" x-model="row.jual1" @input="calcPrices(idx, 'jual1')" placeholder="0">
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Jual 2 <span class="pc-opt">(ops.)</span></div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_jual2]`" class="mu-inp" x-model="row.jual2" @input="calcPrices(idx, 'jual2')" placeholder="0">
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Jual 3 <span class="pc-opt">(ops.)</span></div>
                                            <input type="text" data-currency :name="`units[${idx}][sell_price_jual3]`" class="mu-inp" x-model="row.jual3" @input="calcPrices(idx, 'jual3')" placeholder="0">
                                        </div>
                                        <div>
                                            <div class="mu-lbl">Hrg Minimal <span class="pc-opt">(ops.)</span></div>
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

                {{-- Right Column --}}
                <div class="pc-col-side">
                    {{-- CARD 2: Harga & Stok --}}
                    <div class="pc-card card-price">
                        <div class="pc-card-hdr">
                            <div class="pc-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            </div>
                            <div class="pc-card-title">Harga & Stok</div>
                            <div class="pc-card-desc">Nilai dasar produk</div>
                        </div>
                        <div class="pc-card-body">
                            <div class="pc-fg" style="margin-bottom: 1.25rem;">
                                <label class="pc-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 10h20"/></svg>
                                    Harga Beli (Modal) <span class="pc-opt">(Opsional)</span>
                                </label>
                                <div class="pc-money-wrap">
                                    <span class="pc-money-prefix">Rp</span>
                                    <input type="text" inputmode="numeric" data-currency name="purchase_price" value="{{ old('purchase_price') }}"
                                        class="pc-inp pc-money-inp @error('purchase_price') is-invalid @enderror" placeholder="0">
                                </div>
                                @error('purchase_price')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div class="pc-fg" style="margin-bottom: 1.25rem;">
                                <label class="pc-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8l-4 4-4-4M12 16V8"/></svg>
                                    Harga Jual <span class="pc-req">*</span>
                                </label>
                                <div class="pc-money-wrap">
                                    <span class="pc-money-prefix">Rp</span>
                                    <input type="text" inputmode="numeric" data-currency name="price" value="{{ old('price') }}" required
                                        class="pc-inp pc-money-inp @error('price') is-invalid @enderror" placeholder="0">
                                </div>
                                @error('price')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div class="pc-separator" style="height:1px;background:#f1f5f9;margin:1rem 0;"></div>

                            <div class="pc-fg" style="margin-bottom: 1.25rem;">
                                <label class="pc-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                    Stok Awal <span class="pc-req">*</span>
                                </label>
                                <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" step="1"
                                    class="pc-inp @error('stock') is-invalid @enderror" placeholder="0">
                                <div class="pc-hint">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                    Jumlah stok fisik saat ini
                                </div>
                                @error('stock')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>

                            <div class="pc-fg">
                                <label class="pc-lbl">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Batas Stok Minimum <span class="pc-req">*</span>
                                </label>
                                <input type="number" name="min_stock" value="{{ old('min_stock', 0) }}" required min="0" step="1"
                                    class="pc-inp @error('min_stock') is-invalid @enderror" placeholder="0">
                                <div class="pc-hint">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                    Peringatan jika stok berada di bawah batas ini
                                </div>
                                @error('min_stock')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Global Actions --}}
            <div style="margin-top:0.5rem;">
                <div class="pc-actions">
                    <div class="pc-actions-left">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        <span>Field bertanda <span class="pc-req">*</span> wajib diisi</span>
                    </div>
                    <div class="pc-actions-right">
                        <a href="{{ route('master.produk') }}" class="pc-btn pc-btn-ghost">Batal</a>
                        <button type="submit" class="pc-btn pc-btn-primary" id="submit-btn">
                            <span class="pc-spinner"></span>
                            <span class="pc-btn-text">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Simpan Produk
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        setTimeout(() => {
            if (window.formatCurrency) {
                document.querySelectorAll('[data-currency]').forEach(el => {
                    if (el.value) el.value = window.formatCurrency(el.value);
                });
            }
        }, 100);

        document.addEventListener('alpine:init', () => {
            Alpine.data('produkForm', () => ({
                units: [],
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
                    const val = parseFloat(String(raw).replace(/[^0-9.-]/g, '')) || 0;
                    const factor = parseFloat(row.factor) || 1;
                    const fmt = (v) => v.toLocaleString('id-ID');

                    // Calculate base unit price from current row
                    const basePrice = factor > 0 ? val / factor : val;

                    // Propagate to ALL units
                    this.units.forEach((u, i) => {
                        const targetFactor = parseFloat(u.factor) || 1;
                        u[field] = fmt(basePrice * targetFactor);
                    });
                },
                calcFactor(idx) {
                    const row = this.units[idx];
                    const factor = parseFloat(row.factor) || 1;
                    const parse = (v) => parseFloat(String(v).replace(/[^0-9.-]/g, '')) || 0;
                    const fmt = (v) => v.toLocaleString('id-ID');

                    if (factor !== 1) {
                        const baseIdx = this.units.findIndex(u => parseFloat(u.factor) === 1);
                        if (baseIdx >= 0) {
                            const base = this.units[baseIdx];
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

        // ── DOM Ready ──
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('produk-form');
            const submitBtn = document.getElementById('submit-btn');
            const descInput = document.getElementById('desc-input');
            const descCount = document.getElementById('desc-count');

            // Submit prevention
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.classList.add('loading');
            });

            // Char count for description
            if (descInput) {
                function updateDescCount() {
                    const len = descInput.value.length;
                    const max = 500;
                    descCount.textContent = len + ' / ' + max;
                    descCount.className = 'pc-char-count';
                    if (len > max * 0.9) descCount.classList.add('danger');
                    else if (len > max * 0.75) descCount.classList.add('warning');
                }
                descInput.addEventListener('input', updateDescCount);
                updateDescCount();
            }

            // Unsaved changes warning
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