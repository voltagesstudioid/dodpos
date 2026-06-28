<x-app-layout>
    <x-slot name="header">Edit Kendaraan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-form-container">
            
            {{-- ─── BREADCRUMB / NAVIGATION ─── --}}
            <nav class="tr-nav-breadcrumb">
                <a href="{{ route('operasional.kendaraan.index') }}" class="tr-link-back">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Daftar Kendaraan
                </a>
                <span class="tr-separator">/</span>
                <span class="tr-current">Edit Unit</span>
            </nav>

            {{-- ─── EDIT FORM CARD ─── --}}
            <div class="tr-card">
                <div class="tr-card-header">
                    <div class="tr-header-icon bg-indigo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <div class="tr-header-text">
                        <h2 class="tr-card-title">Perbarui Data Kendaraan</h2>
                        <p class="tr-card-subtitle">Pastikan informasi plat nomor dan tipe armada sudah sesuai untuk laporan operasional.</p>
                    </div>
                </div>

                <div class="tr-card-body">
                    <form action="{{ route('operasional.kendaraan.update', $kendaraan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="tr-form-stack">
                            {{-- Plat Nomor --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Plat Nomor <span class="tr-req">*</span></label>
                                <input type="text" name="license_plate" 
                                    class="tr-input tr-plate-input @error('license_plate') is-invalid @enderror" 
                                    value="{{ old('license_plate', $kendaraan->license_plate) }}" 
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
                                    value="{{ old('type', $kendaraan->type) }}" 
                                    placeholder="Contoh: Mobil Box, Blind Van, Motor Kurir">
                                @error('type') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>

                            {{-- Kapasitas --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Kapasitas</label>
                                <div class="tr-input-with-suffix">
                                    <input type="number" name="capacity" step="0.01" min="0"
                                        class="tr-input @error('capacity') is-invalid @enderror" 
                                        value="{{ old('capacity', $kendaraan->capacity) }}" 
                                        placeholder="0">
                                    <span class="tr-input-suffix">unit</span>
                                </div>
                                @error('capacity') <div class="tr-error-msg">{{ $message }}</div> @enderror
                                <p class="tr-hint">Kapasitas maksimal kendaraan dalam satuan unit produk.</p>
                            </div>

                            {{-- Status --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Status <span class="tr-req">*</span></label>
                                <select name="status" class="tr-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $kendaraan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="standby" {{ old('status', $kendaraan->status) == 'standby' ? 'selected' : '' }}>Standby</option>
                                    <option value="maintenance" {{ old('status', $kendaraan->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="nonaktif" {{ old('status', $kendaraan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status') <div class="tr-error-msg">{{ $message }}</div> @enderror
                                <p class="tr-hint">Status ketersediaan kendaraan untuk operasional.</p>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="tr-form-group">
                                <label class="tr-label">Keterangan / Deskripsi <span class="tr-optional">(Opsional)</span></label>
                                <textarea name="description" 
                                    class="tr-textarea @error('description') is-invalid @enderror" 
                                    rows="4" 
                                    placeholder="Tambahkan informasi tambahan mengenai kondisi kendaraan...">{{ old('description', $kendaraan->description) }}</textarea>
                                @error('description') <div class="tr-error-msg">{{ $message }}</div> @enderror
                            </div>

                            {{-- Info Assignment --}}
                            <div class="tr-info-box">
                                <div class="tr-info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                </div>
                                <div class="tr-info-content">
                                    <p class="tr-info-title">Penugasan Kendaraan</p>
                                    <p class="tr-info-text">Untuk mengubah penugasan kendaraan kepada sales, kelola melalui menu <strong>Sales → Assign Vehicle</strong> di modul Mineral.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="tr-form-actions">
                            <a href="{{ route('operasional.kendaraan.index') }}" class="tr-btn tr-btn-light">Batal</a>
                            <button type="submit" class="tr-btn tr-btn-indigo">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                Simpan Perubahan
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
        .tr-card-subtitle { margin: 2px 0 0; font-size: 0.8125rem; color: var(--tr-text-muted); font-weight: 500; line-height: 1.5; }
        
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
            box-sizing: border-box;
        }
        .tr-input:focus, .tr-textarea:focus { border-color: var(--tr-indigo); background-color: #ffffff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }

        /* ── INPUT WITH SUFFIX ── */
        .tr-input-with-suffix { position: relative; display: flex; align-items: center; }
        .tr-input-with-suffix .tr-input { padding-right: 4.5rem; }
        .tr-input-suffix { position: absolute; right: 1rem; color: var(--tr-text-muted); font-size: 0.875rem; font-weight: 600; pointer-events: none; }

        /* ── SELECT ── */
        .tr-select {
            width: 100%; padding: 0.75rem 1rem; 
            border: 1.5px solid var(--tr-border); border-radius: 10px;
            background-color: #fcfcfd; font-family: inherit; font-size: 0.9375rem; 
            color: var(--tr-text-main); transition: all 0.2s; outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
            cursor: pointer;
        }
        .tr-select:focus { border-color: var(--tr-indigo); background-color: #ffffff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        
        .tr-hint { margin-top: 8px; font-size: 0.75rem; color: var(--tr-text-muted); line-height: 1.5; }
        
        .tr-plate-input { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 700; letter-spacing: 0.05em; }

        .tr-textarea { resize: vertical; min-height: 120px; line-height: 1.6; }

        /* ── INFO BOX ── */
        .tr-info-box { display: flex; gap: 1rem; padding: 1rem 1.25rem; background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%); border: 1px solid #bfdbfe; border-radius: 12px; margin-top: 0.5rem; }
        .tr-info-icon { flex-shrink: 0; width: 40px; height: 40px; background: #3b82f6; color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .tr-info-content { flex: 1; }
        .tr-info-title { margin: 0 0 4px; font-size: 0.875rem; font-weight: 700; color: #1e40af; }
        .tr-info-text { margin: 0; font-size: 0.8125rem; color: #1e40af; line-height: 1.5; opacity: 0.85; }
        .tr-info-text strong { font-weight: 700; }

        /* ── BUTTONS ── */
        .tr-form-actions { margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 1rem; align-items: center; }
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
