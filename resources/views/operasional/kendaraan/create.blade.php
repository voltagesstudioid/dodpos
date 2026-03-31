<x-app-layout>
    <x-slot name="header">Tambah Kendaraan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-form-container">
            
            {{-- ─── BREADCRUMB / BACK ─── --}}
            <nav class="tr-nav-breadcrumb">
                <a href="{{ route('operasional.kendaraan.index') }}" class="tr-link-back">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Daftar Kendaraan
                </a>
                <span class="tr-separator">/</span>
                <span class="tr-current">Tambah Unit Baru</span>
            </nav>

            {{-- ─── FORM CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <div class="tr-header-icon bg-indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    </div>
                    <div class="tr-header-text">
                        <h2 class="tr-card-title">Informasi Unit Kendaraan</h2>
                        <p class="tr-card-subtitle">Daftarkan armada baru untuk mendukung logistik operasional toko.</p>
                    </div>
                </div>

                <div class="tr-card-body">
                    <form action="{{ route('operasional.kendaraan.store') }}" method="POST">
                        @csrf
                        
                        <div class="tr-form-stack">
                            {{-- Plat Nomor --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Plat Nomor <span class="tr-req">*</span></label>
                                <input type="text" name="license_plate" 
                                    class="tr-input tr-plate-input @error('license_plate') is-invalid @enderror" 
                                    value="{{ old('license_plate') }}" 
                                    required autofocus 
                                    placeholder="CONTOH: B 1234 ABC" 
                                    style="text-transform: uppercase;">
                                @error('license_plate') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>

                            {{-- Jenis/Tipe --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Jenis / Tipe Kendaraan</label>
                                <input type="text" name="type" 
                                    class="tr-input @error('type') is-invalid @enderror" 
                                    value="{{ old('type') }}" 
                                    placeholder="Contoh: Mobil Box, Blind Van, Motor Kurir">
                                @error('type') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Keterangan Tambahan <span class="tr-optional">(Opsional)</span></label>
                                <textarea name="description" 
                                    class="tr-textarea @error('description') is-invalid @enderror" 
                                    rows="3" 
                                    placeholder="Jelaskan kondisi atau peruntukan kendaraan ini...">{{ old('description') }}</textarea>
                                @error('description') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="tr-form-actions">
                            <a href="{{ route('operasional.kendaraan.index') }}" class="tr-btn tr-btn-light">Batalkan</a>
                            <button type="submit" class="tr-btn tr-btn-indigo">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Simpan Kendaraan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-indigo: #4f46e5;
            --tr-indigo-hover: #4338ca;
            --tr-indigo-light: #e0e7ff;
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-danger: #ef4444;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; }
        .tr-form-container { max-width: 650px; margin: 0 auto; padding: 2.5rem 1.5rem; }

        /* ── BREADCRUMB ── */
        .tr-nav-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 2rem; }
        .tr-link-back { display: flex; align-items: center; gap: 4px; text-decoration: none; color: var(--tr-text-muted); font-size: 0.875rem; font-weight: 700; transition: color 0.2s; }
        .tr-link-back:hover { color: var(--tr-indigo); }
        .tr-separator { color: #cbd5e1; font-size: 0.875rem; }
        .tr-current { font-size: 0.875rem; font-weight: 600; color: var(--tr-text-main); }

        /* ── CARD ── */
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04); overflow: hidden; }
        .tr-card-header { padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 1rem; background: linear-gradient(to right, #ffffff, #fafafa); }
        .tr-header-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-card-title { margin: 0; font-size: 1.125rem; font-weight: 800; color: var(--tr-text-main); letter-spacing: -0.01em; }
        .tr-card-subtitle { margin: 2px 0 0; font-size: 0.8125rem; color: var(--tr-text-muted); font-weight: 500; }
        
        .tr-card-body { padding: 2rem; }

        /* ── FORM ELEMENTS ── */
        .tr-form-stack { display: flex; flex-direction: column; gap: 1.5rem; }
        .tr-label { display: block; font-size: 0.8125rem; font-weight: 700; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.625rem; }
        .tr-req { color: var(--tr-danger); }
        .tr-optional { color: var(--tr-text-muted); font-weight: 500; text-transform: none; letter-spacing: 0; }

        .tr-input, .tr-textarea {
            width: 100%; padding: 0.75rem 1rem; 
            border: 1.5px solid var(--tr-border); border-radius: 10px;
            background-color: #fcfcfd; font-family: inherit; font-size: 0.9375rem; 
            color: var(--tr-text-main); transition: all 0.2s; outline: none;
        }
        .tr-input:focus, .tr-textarea:focus { border-color: var(--tr-indigo); background-color: #ffffff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        
        /* Spesifik Plat Nomor */
        .tr-plate-input { font-family: ui-monospace, monospace; font-weight: 700; letter-spacing: 0.1em; color: var(--tr-indigo); }

        .tr-textarea { resize: vertical; min-height: 100px; line-height: 1.5; }

        /* ── BUTTONS ── */
        .tr-form-actions { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 1rem; align-items: center; }
        .tr-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0.75rem 1.5rem; border-radius: 10px; font-size: 0.875rem; font-weight: 700; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; }
        .tr-btn-indigo { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-indigo:hover { background: var(--tr-indigo-hover); transform: translateY(-1px); }
        .tr-btn-light { color: var(--tr-text-muted); background: transparent; }
        .tr-btn-light:hover { background: #f1f5f9; color: var(--tr-text-main); }

        /* ── ERRORS ── */
        .is-invalid { border-color: #fecaca; background-color: #fef2f2; }
        .tr-error-msg { color: var(--tr-danger); font-size: 0.75rem; font-weight: 600; margin-top: 0.5rem; }

        @media (max-width: 640px) {
            .tr-form-container { padding: 1.5rem 1rem; }
            .tr-card-body { padding: 1.5rem; }
            .tr-form-actions { flex-direction: column-reverse; }
            .tr-btn { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
</x-app-layout>