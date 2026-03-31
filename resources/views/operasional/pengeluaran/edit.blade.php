<x-app-layout>
    <x-slot name="header">Edit Pengeluaran Operasional</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── HEADER AREA ─── --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Operasional & Kas</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-indigo">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                        Edit Pengeluaran
                    </h1>
                    <p class="tr-subtitle">Perbarui atau koreksi data pengeluaran kas operasional toko.</p>
                </div>

                <div class="tr-header-actions">
                    <a href="{{ route('operasional.riwayat.index') }}" class="tr-btn tr-btn-outline">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Kembali ke Riwayat
                    </a>
                </div>
            </div>

            {{-- ─── ALERTS ─── --}}
            @if(session('success'))
                <div class="tr-alert tr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="tr-alert tr-alert-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- ─── FORM CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <h2 class="tr-section-title">Detail Data Transaksi</h2>
                    <span class="tr-badge tr-badge-gray">Mode Edit</span>
                </div>

                <form id="expenseEditForm" action="{{ route('operasional.pengeluaran.update', $pengeluaran) }}" method="POST" class="tr-form-main">
                    @csrf
                    @method('PUT')

                    <div class="tr-form-grid-3">
                        {{-- Tanggal --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Tanggal Transaksi <span class="tr-req">*</span></label>
                            <input type="date" name="date" 
                                class="tr-input @error('date') is-invalid @enderror" 
                                value="{{ old('date', $pengeluaran->date) }}" required>
                            @error('date') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Kategori <span class="tr-req">*</span></label>
                            <div class="tr-select-wrapper">
                                <select name="category_id" class="tr-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('category_id', $pengeluaran->category_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>

                        {{-- Nominal --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Nominal Terkoreksi <span class="tr-req">*</span></label>
                            <div class="tr-input-prefix-group">
                                <span class="prefix">Rp</span>
                                <input type="number" name="amount" 
                                    class="tr-input @error('amount') is-invalid @enderror" 
                                    value="{{ old('amount', $pengeluaran->amount) }}" required min="0" placeholder="0">
                            </div>
                            @error('amount') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="tr-form-divider"></div>

                    <div class="tr-form-grid-2">
                        {{-- Kendaraan --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Terkait Kendaraan <span class="tr-optional">(Opsional)</span></label>
                            <div class="tr-select-wrapper">
                                <select name="vehicle_id" class="tr-select">
                                    <option value="">Umum (Bukan Kendaraan)</option>
                                    @foreach($vehicles as $kendaraan)
                                        <option value="{{ $kendaraan->id }}" {{ old('vehicle_id', $pengeluaran->vehicle_id) == $kendaraan->id ? 'selected' : '' }}>
                                            {{ strtoupper($kendaraan->license_plate) }} @if($kendaraan->type) — {{ $kendaraan->type }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="tr-input-hint">Pilih jika biaya terkait bahan bakar/servis unit.</div>
                        </div>

                        {{-- Catatan --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Catatan / Keterangan Lengkap</label>
                            <textarea name="notes" class="tr-textarea @error('notes') is-invalid @enderror" 
                                rows="3" placeholder="Contoh: Revisi pengisian bensin truk 15 liter...">{{ old('notes', $pengeluaran->notes) }}</textarea>
                            @error('notes') <div class="tr-error-msg">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="tr-form-actions">
                        <a href="{{ route('operasional.riwayat.index') }}" class="tr-btn tr-btn-light">Batalkan Edit</a>
                        <button type="submit" class="tr-btn tr-btn-indigo">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-indigo: #4f46e5;
            --tr-indigo-hover: #4338ca;
            --tr-indigo-light: #e0e7ff;
            --tr-success: #10b981;
            --tr-danger: #ef4444;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }
        .tr-page { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem; }
        .tr-eyebrow { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--tr-indigo); margin-bottom: 0.5rem; }
        .tr-title { font-size: 1.625rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 12px; letter-spacing: -0.02em; }
        .tr-title-icon-box { padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-subtitle { font-size: 0.9rem; color: var(--tr-text-muted); margin-top: 6px; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.65rem 1.25rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; }
        .tr-btn-indigo { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-indigo:hover { background: var(--tr-indigo-hover); transform: translateY(-1px); }
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f1f5f9; border-color: var(--tr-text-muted); }
        .tr-btn-light { color: var(--tr-text-muted); }
        .tr-btn-light:hover { background: #f1f5f9; }

        /* ── CARD ── */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.75rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fafafa; }
        .tr-section-title { font-size: 0.95rem; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: 0.025em; color: var(--tr-text-main); }
        
        /* ── FORM ELEMENTS ── */
        .tr-form-main { padding: 2rem; }
        .tr-form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .tr-form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; align-items: flex-start;}
        .tr-form-group { margin-bottom: 0; display: flex; flex-direction: column; gap: 6px; }
        .tr-form-divider { height: 1px; background: #f1f5f9; margin: 1.5rem 0; }

        .tr-label { font-size: 0.8125rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; }
        .tr-req { color: var(--tr-danger); }
        .tr-optional { color: var(--tr-text-muted); font-weight: 500; text-transform: none; letter-spacing: 0; }

        .tr-input, .tr-select, .tr-textarea {
            width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--tr-border); border-radius: 10px;
            background-color: #fcfcfd; font-family: inherit; font-size: 0.9375rem; color: var(--tr-text-main);
            transition: all 0.2s; outline: none;
        }
        .tr-input:focus, .tr-select:focus, .tr-textarea:focus { border-color: var(--tr-indigo); background-color: #fff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        .tr-textarea { resize: vertical; min-height: 80px; }

        /* PREFIX */
        .tr-input-prefix-group { display: flex; align-items: stretch; }
        .tr-input-prefix-group .prefix { 
            display: flex; align-items: center; padding: 0 0.75rem; background: #f1f5f9; 
            border: 1.5px solid var(--tr-border); border-right: none; border-radius: 10px 0 0 10px;
            font-size: 0.875rem; font-weight: 800; color: var(--tr-text-muted);
        }
        .tr-input-prefix-group .tr-input { border-radius: 0 10px 10px 0; }

        .tr-input-hint { font-size: 0.75rem; color: var(--tr-text-muted); font-weight: 500; }
        .tr-error-msg { color: var(--tr-danger); font-size: 0.75rem; font-weight: 600; margin-top: 2px; }

        /* SELECT CUSTOM */
        .tr-select-wrapper { position: relative; }
        .tr-select-wrapper::after {
            content: ''; position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
            width: 10px; height: 10px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: contain; background-repeat: no-repeat; pointer-events: none;
        }
        .tr-select { appearance: none; padding-right: 2.5rem; cursor: pointer; }

        /* BADGE */
        .tr-badge { padding: 4px 10px; border-radius: 99px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .tr-badge-gray { background: #f1f5f9; color: #64748b; }

        /* ALERT */
        .tr-alert { padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem; }
        .tr-alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .tr-alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ACTIONS */
        .tr-form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 1rem; align-items: center; }

        /* RESPONSIVE */
        @media (max-width: 850px) {
            .tr-form-grid-3 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .tr-header { flex-direction: column; align-items: stretch; }
            .tr-btn { width: 100%; justify-content: center; }
            .tr-form-grid-3, .tr-form-grid-2 { grid-template-columns: 1fr; }
            .tr-form-main { padding: 1.25rem; }
            .tr-form-actions { flex-direction: column-reverse; }
        }
    </style>
    @endpush
</x-app-layout>