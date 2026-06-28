<x-app-layout>
    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .pc-page {
            max-width: 52rem;
            margin: 0 auto;
            padding: 1.5rem 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Progress Steps ── */
        .pc-steps {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            padding: 1rem 1.25rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .pc-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            white-space: nowrap;
        }
        .pc-step.active {
            color: #0f172a;
        }
        .pc-step.active .pc-step-num {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.25);
        }
        .pc-step.done .pc-step-num {
            background: #10b981;
            color: #fff;
        }
        .pc-step-num {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6875rem;
            font-weight: 700;
            background: #f1f5f9;
            color: #94a3b8;
            flex-shrink: 0;
            transition: all 0.3s;
        }
        .pc-step-line {
            flex: 1;
            height: 2px;
            background: #e2e8f0;
            border-radius: 1px;
            min-width: 12px;
        }
        .pc-step-line.done {
            background: #10b981;
        }

        @media (max-width: 520px) {
            .pc-step-text {
                display: none;
            }
        }

        /* ── Breadcrumb ── */
        .pc-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.25rem;
        }
        .pc-back {
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            color: #64748b;
            font-size: 0.8125rem;
            font-weight: 600;
            transition: color 0.2s;
        }
        .pc-back:hover {
            color: #ea580c;
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

        /* ── Title ── */
        .pc-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }
        .pc-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* ── Kode Preview ── */
        .pc-kode-box {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.75rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            color: #64748b;
            margin-bottom: 1.25rem;
        }
        .pc-kode-box span {
            color: #ea580c;
        }

        /* ── Card ── */
        .pc-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.25rem;
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

        .pc-card.orange .pc-card-hdr {
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
        }
        .pc-card.orange .pc-card-ico {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
        }
        .pc-card.green .pc-card-hdr {
            background: linear-gradient(135deg, #ecfdf5, #f0fdf4);
        }
        .pc-card.green .pc-card-ico {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }
        .pc-card.purple .pc-card-hdr {
            background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        }
        .pc-card.purple .pc-card-ico {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: #fff;
        }

        /* ── Form Fields ── */
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
            font-family: inherit;
            font-size: 0.875rem;
            color: #0f172a;
            transition: all 0.2s;
            outline: none;
        }
        .pc-inp:focus,
        .pc-sel:focus,
        .pc-txt:focus {
            border-color: #f97316;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
        }
        .pc-txt {
            resize: vertical;
            min-height: 80px;
            line-height: 1.5;
        }
        .pc-inp::placeholder,
        .pc-txt::placeholder {
            color: #cbd5e1;
        }
        .pc-sel {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
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
            padding-left: 2.75rem !important;
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

        /* Margin Preview */
        .pc-margin-box {
            margin-top: 1rem;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border: 1.5px dashed #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            transition: all 0.3s;
        }
        .pc-margin-box.positive {
            border-color: #a7f3d0;
            background: #ecfdf5;
        }
        .pc-margin-box.negative {
            border-color: #fecaca;
            background: #fef2f2;
        }
        .pc-margin-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .pc-margin-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .pc-margin-icon.positive {
            background: #d1fae5;
        }
        .pc-margin-icon.negative {
            background: #fee2e2;
        }
        .pc-margin-icon.neutral {
            background: #f1f5f9;
        }
        .pc-margin-icon svg {
            width: 16px;
            height: 16px;
        }
        .pc-margin-info {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }
        .pc-margin-lbl {
            font-size: 0.6875rem;
            font-weight: 600;
            color: #64748b;
        }
        .pc-margin-val {
            font-size: 1.125rem;
            font-weight: 800;
        }
        .pc-margin-val.positive {
            color: #059669;
        }
        .pc-margin-val.negative {
            color: #dc2626;
        }
        .pc-margin-val.neutral {
            color: #94a3b8;
        }
        .pc-margin-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .pc-margin-pct-box {
            font-size: 0.8125rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
        }
        .pc-margin-pct-box.positive {
            background: #d1fae5;
            color: #065f46;
        }
        .pc-margin-pct-box.negative {
            background: #fee2e2;
            color: #991b1b;
        }
        .pc-margin-pct-box.neutral {
            background: #f1f5f9;
            color: #94a3b8;
        }

        /* Radio Cards */
        .pc-radios {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        .pc-radio {
            position: relative;
        }
        .pc-radio input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .pc-radio-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }
        .pc-radio-card:hover {
            border-color: #fdba74;
            background: #fffaf5;
        }
        .pc-radio input:checked~.pc-radio-card {
            border-color: #f97316;
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.12);
        }
        .pc-radio-dot {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .pc-radio-dot.ok {
            background: #ecfdf5;
        }
        .pc-radio-dot.off {
            background: #f1f5f9;
        }
        .pc-radio-text {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #0f172a;
        }
        .pc-radio-sub {
            font-size: 0.6875rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Separator */
        .pc-separator {
            height: 1px;
            background: #f1f5f9;
            margin: 1.25rem 0;
        }

        /* ── Actions ── */
        .pc-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 1.25rem 0 0.5rem;
            border-top: 1px solid #f1f5f9;
            margin-top: 0.5rem;
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
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3);
        }
        .pc-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(234, 88, 12, 0.4);
        }
        .pc-btn-primary.loading {
            pointer-events: none;
        }
        .pc-btn-primary .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: pc-spin 0.6s linear infinite;
        }
        .pc-btn-primary.loading .spinner {
            display: block;
        }
        .pc-btn-primary.loading .btn-text {
            display: none;
        }
        @keyframes pc-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Alert ── */
        .pc-alert {
            padding: 0.875rem 1.125rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .pc-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        .pc-alert-info {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        /* ── Char Count ── */
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

        @media (max-width: 640px) {
            .pc-grid2 {
                grid-template-columns: 1fr;
            }
            .pc-radios {
                grid-template-columns: 1fr;
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
            .pc-margin-box {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @endpush

    <div class="pc-page">

        {{-- Breadcrumb --}}
        <nav class="pc-nav">
            <a href="{{ route('minyak.produk.index') }}" class="pc-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Produk
            </a>
            <span class="pc-sep">/</span>
            <span class="pc-crumb">Tambah Produk Baru</span>
        </nav>

        {{-- Title --}}
        <h1 class="pc-title">Tambah Produk Baru</h1>
        <p class="pc-subtitle">Lengkapi data produk minyak untuk ditambahkan ke sistem.</p>

        {{-- Kode Produk Preview --}}
        <div class="pc-kode-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M15 3v18"/></svg>
            Kode Produk: <span id="kode-preview">...</span>
        </div>

        {{-- Steps --}}
        <div class="pc-steps">
            <div class="pc-step active">
                <div class="pc-step-num">1</div>
                <span class="pc-step-text">Informasi Dasar</span>
            </div>
            <div class="pc-step-line"></div>
            <div class="pc-step">
                <div class="pc-step-num">2</div>
                        <span class="pc-step-text">Harga</span>
            </div>
            <div class="pc-step-line"></div>
            <div class="pc-step">
                <div class="pc-step-num">3</div>
                <span class="pc-step-text">Status</span>
            </div>
        </div>

        {{-- Error Alert --}}
        @if($errors->any())
            <div class="pc-alert pc-alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Mohon periksa kembali input Anda. Ada <strong>{{ $errors->count() }}</strong> field yang perlu diperbaiki.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('minyak.produk.store') }}" id="produk-form">
            @csrf

            {{-- CARD 1: Informasi Dasar --}}
            <div class="pc-card orange" data-step="1">
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
                            <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="100"
                                class="pc-inp @error('nama') is-invalid @enderror"
                                placeholder="Contoh: Pertalite, Pertamax, Solar B30, Oli Pelumas"
                                autocomplete="off">
                            @error('nama')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                Jenis Produk <span class="pc-opt">(Opsional)</span>
                            </label>
                            <select name="jenis" class="pc-sel @error('jenis') is-invalid @enderror">
                                <option value="">— Pilih jenis —</option>
                                @foreach($jenisList as $j)
                                    <option value="{{ $j->nama }}" {{ old('jenis') == $j->nama ? 'selected' : '' }}>{{ $j->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                Satuan <span class="pc-req">*</span>
                            </label>
                            <select name="satuan" required class="pc-sel @error('satuan') is-invalid @enderror" id="satuan-select">
                                <option value="">— Pilih satuan —</option>
                                @foreach($satuanList as $s)
                                    <option value="{{ $s->nama }}" {{ old('satuan') == $s->nama ? 'selected' : '' }} data-singkatan="{{ $s->singkatan ?? '' }}">{{ $s->nama }}@if($s->singkatan) ({{ $s->singkatan }})@endif</option>
                                @endforeach
                            </select>
                            @error('satuan')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: Harga --}}
            <div class="pc-card green" data-step="2">
                <div class="pc-card-hdr">
                    <div class="pc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div class="pc-card-title">Harga</div>
                    <div class="pc-card-desc">Harga jual dan modal</div>
                </div>
                <div class="pc-card-body">
                    <div class="pc-grid2">
                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 10h20"/></svg>
                                Harga Modal (HPP) <span class="pc-opt">(Opsional)</span>
                            </label>
                            <div class="pc-money-wrap">
                                <span class="pc-money-prefix">Rp</span>
                                <input type="text" inputmode="numeric" data-currency name="harga_modal" id="harga_modal" value="{{ old('harga_modal') }}"
                                    class="pc-inp pc-money-inp @error('harga_modal') is-invalid @enderror"
                                    placeholder="0">
                            </div>
                            <div class="pc-hint">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                Harga beli dari supplier per <strong class="satuan-text">liter</strong>
                            </div>
                            @error('harga_modal')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8l-4 4-4-4M12 16V8"/></svg>
                                Harga Jual <span class="pc-req">*</span>
                            </label>
                            <div class="pc-money-wrap">
                                <span class="pc-money-prefix">Rp</span>
                                <input type="text" inputmode="numeric" data-currency name="harga_jual" id="harga_jual" value="{{ old('harga_jual') }}" required
                                    class="pc-inp pc-money-inp @error('harga_jual') is-invalid @enderror"
                                    placeholder="0">
                            </div>
                            <div class="pc-hint">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                Harga jual ke pelanggan per <strong class="satuan-text">liter</strong>
                            </div>
                            @error('harga_jual')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Margin Preview --}}
                    <div class="pc-margin-box" id="margin-box">
                        <div class="pc-margin-left">
                            <div class="pc-margin-icon neutral" id="margin-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                            </div>
                            <div class="pc-margin-info">
                                <div class="pc-margin-lbl">Margin / Keuntungan</div>
                                <div class="pc-margin-val neutral" id="margin-val">Rp 0</div>
                            </div>
                        </div>
                        <div class="pc-margin-right">
                            <div class="pc-margin-pct-box neutral" id="margin-pct">0%</div>
                        </div>
                    </div>


                </div>
            </div>

            {{-- CARD 3: Status & Keterangan --}}
            <div class="pc-card purple" data-step="3">
                <div class="pc-card-hdr">
                    <div class="pc-card-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div class="pc-card-title">Status & Keterangan</div>
                    <div class="pc-card-desc">Atur status aktif dan catatan</div>
                </div>
                <div class="pc-card-body">
                    <div style="display:flex;flex-direction:column;gap:1.125rem;">
                        <div class="pc-fg">
                            <label class="pc-lbl" style="margin-bottom:0.5rem;">Status Produk <span class="pc-req">*</span></label>
                            <div class="pc-radios">
                                <label class="pc-radio">
                                    <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }} required>
                                    <div class="pc-radio-card">
                                        <div class="pc-radio-dot ok">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                        <div>
                                            <div class="pc-radio-text">Aktif</div>
                                            <div class="pc-radio-sub">Produk bisa dijual</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="pc-radio">
                                    <input type="radio" name="status" value="nonaktif" {{ old('status') === 'nonaktif' ? 'checked' : '' }}>
                                    <div class="pc-radio-card">
                                        <div class="pc-radio-dot off">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                        </div>
                                        <div>
                                            <div class="pc-radio-text">Nonaktif</div>
                                            <div class="pc-radio-sub">Sementara tidak dijual</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('status')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="pc-fg">
                            <label class="pc-lbl">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Keterangan <span class="pc-opt">(Opsional)</span>
                            </label>
                            <textarea name="keterangan" rows="2" maxlength="500" class="pc-txt @error('keterangan') is-invalid @enderror" placeholder="Catatan tambahan tentang produk..." id="keterangan-input">{{ old('keterangan') }}</textarea>
                            <div class="pc-char-count" id="char-count">0 / 500</div>
                            @error('keterangan')<div class="pc-err"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pc-actions">
                <div class="pc-actions-left">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    <span>Field bertanda <span class="pc-req">*</span> wajib diisi</span>
                </div>
                <div class="pc-actions-right">
                    <a href="{{ route('minyak.produk.index') }}" class="pc-btn pc-btn-ghost">Batal</a>
                    <button type="submit" class="pc-btn pc-btn-primary" id="submit-btn">
                        <span class="spinner"></span>
                        <span class="btn-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Produk
                        </span>
                    </button>
                </div>
            </div>
        </form>

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hargaModal = document.getElementById('harga_modal');
        const hargaJual = document.getElementById('harga_jual');
        const marginBox = document.getElementById('margin-box');
        const marginIcon = document.getElementById('margin-icon');
        const marginVal = document.getElementById('margin-val');
        const marginPct = document.getElementById('margin-pct');
        const satuanSelect = document.getElementById('satuan-select');
        const satuanTexts = document.querySelectorAll('.satuan-text');
        const form = document.getElementById('produk-form');
        const submitBtn = document.getElementById('submit-btn');
        const keterangan = document.getElementById('keterangan-input');
        const charCount = document.getElementById('char-count');
        const kodePreview = document.getElementById('kode-preview');
        const steps = document.querySelectorAll('.pc-step');
        const stepLines = document.querySelectorAll('.pc-step-line');
        const cards = document.querySelectorAll('.pc-card');

        function formatRp(n) {
            return 'Rp ' + Number(n).toLocaleString('id-ID');
        }

        // ── Margin Calculator ──
        function updateMargin() {
            const modal = parseFloat(parseCurrency(hargaModal.value)) || 0;
            const jual = parseFloat(parseCurrency(hargaJual.value)) || 0;

            if (!modal && !jual) {
                marginBox.className = 'pc-margin-box';
                marginIcon.className = 'pc-margin-icon neutral';
                marginIcon.innerHTML =
                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>';
                marginVal.className = 'pc-margin-val neutral';
                marginVal.textContent = 'Rp 0';
                marginPct.className = 'pc-margin-pct-box neutral';
                marginPct.textContent = '0%';
                return;
            }

            const margin = jual - modal;
            const pct = modal > 0 ? ((margin / modal) * 100) : (jual > 0 ? 100 : 0);

            if (margin >= 0) {
                marginBox.className = 'pc-margin-box positive';
                marginIcon.className = 'pc-margin-icon positive';
                marginIcon.innerHTML =
                    '<svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>';
                marginVal.className = 'pc-margin-val positive';
                marginVal.textContent = '+' + formatRp(margin);
                marginPct.className = 'pc-margin-pct-box positive';
                marginPct.textContent = '+' + pct.toFixed(1) + '%';
            } else {
                marginBox.className = 'pc-margin-box negative';
                marginIcon.className = 'pc-margin-icon negative';
                marginIcon.innerHTML =
                    '<svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>';
                marginVal.className = 'pc-margin-val negative';
                marginVal.textContent = formatRp(margin);
                marginPct.className = 'pc-margin-pct-box negative';
                marginPct.textContent = pct.toFixed(1) + '%';
            }
        }

        function updateSatuan() {
            const s = satuanSelect.value || 'liter';
            satuanTexts.forEach(el => el.textContent = s.toLowerCase());
        }

        // ── Kode Preview (simulated) ──
        (function generateKodePreview() {
            var d = new Date();
            var y = d.getFullYear();
            var m = String(d.getMonth() + 1).padStart(2, '0');
            var day = String(d.getDate()).padStart(2, '0');
            var seq = String(Math.floor(Math.random() * 900) + 100);
            kodePreview.textContent = 'PRD' + y + m + day + seq;
        })();

        // ── Character Count ──
        function updateCharCount() {
            const len = keterangan.value.length;
            const max = 500;
            charCount.textContent = len + ' / ' + max;
            charCount.className = 'pc-char-count';
            if (len > max * 0.9) charCount.classList.add('danger');
            else if (len > max * 0.75) charCount.classList.add('warning');
        }

        // ── Steps Progress ──
        function updateSteps() {
            let activeStep = 1;
            cards.forEach(function(card) {
                const rect = card.getBoundingClientRect();
                if (rect.top < window.innerHeight * 0.5) {
                    activeStep = parseInt(card.dataset.step) || 1;
                }
            });
            steps.forEach(function(step, i) {
                step.className = 'pc-step';
                if (i + 1 < activeStep) step.classList.add('done');
                else if (i + 1 === activeStep) step.classList.add('active');
            });
            stepLines.forEach(function(line, i) {
                line.className = 'pc-step-line';
                if (i + 1 < activeStep) line.classList.add('done');
            });
        }

        // ── Submit Prevention ──
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
        });

        // ── Unsaved Changes Warning ──
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

        // ── Events ──
        hargaModal.addEventListener('input', updateMargin);
        hargaJual.addEventListener('input', updateMargin);
        satuanSelect.addEventListener('change', updateSatuan);
        if (keterangan) {
            keterangan.addEventListener('input', updateCharCount);
            updateCharCount();
        }
        window.addEventListener('scroll', updateSteps);

        updateMargin();
        updateSatuan();
        updateSteps();
    });
    </script>
    @endpush
</x-app-layout>