<x-app-layout>
    <x-slot name="header">Tambah Pelanggan</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-form-container">
            
            {{-- ─── BREADCRUMB & HEADER ─── --}}
            <nav class="tr-breadcrumb">
                <a href="{{ route('pelanggan.index') }}" class="tr-back-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Daftar Pelanggan
                </a>
                <span class="tr-separator">/</span>
                <span class="tr-current">Tambah Baru</span>
            </nav>

            <div class="tr-page-header">
                <div class="tr-title-box">
                    <div class="tr-icon-box bg-blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    </div>
                    <div>
                        <h1 class="tr-title">Registrasi Pelanggan</h1>
                        <p class="tr-subtitle">Data ini digunakan untuk riwayat transaksi POS dan laporan piutang.</p>
                    </div>
                </div>
            </div>

            {{-- ─── ERROR ALERT ─── --}}
            @if($errors->any())
                <div class="tr-alert tr-alert-danger animate-fade-in-up">
                    <div class="alert-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    </div>
                    <div class="alert-content">
                        <strong>Terdapat kesalahan input:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ─── MAIN FORM ─── --}}
            <form id="customerForm" action="{{ route('pelanggan.store') }}" method="POST" class="tr-form-layout">
                @csrf

                {{-- Card 1: Identitas --}}
                <div class="tr-card animate-fade-in-up">
                    <div class="tr-card-header">
                        <div>
                            <h2 class="tr-section-title">Identitas Pelanggan</h2>
                            <p class="tr-section-desc">Informasi kontak utama dan alamat pengiriman.</p>
                        </div>
                        <span class="tr-badge badge-blue">Kategori Default: Toko/POS</span>
                    </div>
                    <div class="tr-card-body">
                        
                        {{-- Nama --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Nama Pelanggan <span class="tr-req">*</span></label>
                            <input type="text" name="name" 
                                class="tr-input @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" placeholder="Masukkan nama lengkap..." required autofocus>
                            @error('name')<div class="tr-error-msg">{{ $message }}</div>@enderror
                        </div>

                        {{-- Kontak Grid --}}
                        <div class="tr-grid-2">
                            <div class="tr-form-group">
                                <label class="tr-label">No. Telepon / WhatsApp <span class="tr-optional">(Opsional)</span></label>
                                <input type="tel" name="phone" 
                                    class="tr-input @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                                @error('phone')<div class="tr-error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="tr-form-group">
                                <label class="tr-label">Email <span class="tr-optional">(Opsional)</span></label>
                                <input type="email" name="email" 
                                    class="tr-input @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" placeholder="email@contoh.com">
                                @error('email')<div class="tr-error-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="tr-form-group">
                            <label class="tr-label">Alamat Lengkap <span class="tr-optional">(Opsional)</span></label>
                            <textarea name="address" class="tr-textarea @error('address') is-invalid @enderror" 
                                rows="3" placeholder="Nama jalan, gedung, RT/RW, kelurahan...">{{ old('address') }}</textarea>
                            @error('address')<div class="tr-error-msg">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- Card 2: Pengaturan Kredit --}}
                <div class="tr-card animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="tr-card-header">
                        <div>
                            <h2 class="tr-section-title">Pengaturan Kredit (Piutang)</h2>
                            <p class="tr-section-desc">Batas maksimal hutang yang diizinkan untuk pelanggan ini.</p>
                        </div>
                    </div>
                    <div class="tr-card-body">
                        @if(auth()->user()->role === 'supervisor')
                            <div class="tr-form-group">
                                <label class="tr-label">Limit Kredit</label>
                                <div class="tr-input-prefix-group">
                                    <span class="prefix">Rp</span>
                                    <input type="number" name="credit_limit" 
                                        class="tr-input tr-font-mono @error('credit_limit') is-invalid @enderror" 
                                        value="{{ old('credit_limit', 0) }}" min="0">
                                </div>
                                <div class="tr-input-hint">Isi angka 0 jika pelanggan tidak diberikan fasilitas hutang (kredit).</div>
                                @error('credit_limit')<div class="tr-error-msg">{{ $message }}</div>@enderror
                            </div>
                        @else
                            <div class="tr-notice-box warning">
                                <div class="notice-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                </div>
                                <div class="notice-text">
                                    <strong>Akses Terbatas</strong>
                                    <p>Pengaturan Limit Kredit hanya dapat diakses dan diubah oleh akun tingkat <b>Supervisor</b>.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card 3: Catatan --}}
                <div class="tr-card animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="tr-card-body">
                        <div class="tr-form-group" style="margin-bottom: 0;">
                            <label class="tr-label">Catatan Tambahan / Memo Internal <span class="tr-optional">(Opsional)</span></label>
                            <textarea name="notes" class="tr-textarea" rows="2" 
                                placeholder="Keterangan khusus mengenai pelanggan ini...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ─── FLOATING ACTION BAR ─── --}}
                <div class="tr-floating-bar animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="bar-info">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        Pastikan nama dan nomor kontak sudah benar sebelum disimpan.
                    </div>
                    <div class="bar-actions">
                        <a href="{{ route('pelanggan.index') }}" class="tr-btn tr-btn-ghost">Batalkan</a>
                        <button type="submit" class="tr-btn tr-btn-blue">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                            Simpan Pelanggan
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-blue: #3b82f6; --tr-blue-light: #dbeafe; --tr-blue-hover: #2563eb;
            --tr-indigo: #4f46e5; --tr-indigo-light: #e0e7ff;
            --tr-danger: #ef4444; --tr-danger-light: #fef2f2;
            --tr-warning: #f59e0b; --tr-warning-light: #fffbeb;
            --tr-bg: #f8fafc; --tr-surface: #ffffff; --tr-border: #e2e8f0;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 8rem; }
        .tr-form-container { max-width: 760px; margin: 0 auto; padding: 2.5rem 1.5rem; }

        /* ── ANIMATIONS ── */
        .animate-fade-in-up { animation: fadeInUp 0.4s ease forwards; opacity: 0; transform: translateY(15px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* ── BREADCRUMB & HEADER ── */
        .tr-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 1.5rem; }
        .tr-back-link { display: flex; align-items: center; gap: 4px; text-decoration: none; color: var(--tr-text-muted); font-size: 0.85rem; font-weight: 700; transition: 0.2s; }
        .tr-back-link:hover { color: var(--tr-blue); }
        .tr-separator { color: #cbd5e1; font-size: 0.85rem; }
        .tr-current { font-size: 0.85rem; font-weight: 600; color: var(--tr-text-main); }

        .tr-page-header { margin-bottom: 2rem; }
        .tr-title-box { display: flex; align-items: center; gap: 1rem; }
        .tr-icon-box { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bg-blue { background: var(--tr-blue-light); color: var(--tr-blue); }
        .tr-title { font-size: 1.75rem; font-weight: 900; margin: 0 0 4px; letter-spacing: -0.02em; color: var(--tr-text-main); }
        .tr-subtitle { font-size: 0.95rem; color: var(--tr-text-muted); margin: 0; font-weight: 500; line-height: 1.5; }

        /* ── ALERTS ── */
        .tr-alert { padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 12px; font-size: 0.9rem; }
        .tr-alert-danger { background: var(--tr-danger-light); border: 1px solid #fecaca; color: #991b1b; }
        .alert-icon { flex-shrink: 0; margin-top: 2px; }
        .alert-content ul { margin: 6px 0 0; padding-left: 1.25rem; }
        .alert-content li { margin-bottom: 4px; }

        /* ── CARDS ── */
        .tr-form-layout { display: flex; flex-direction: column; gap: 1.5rem; }
        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
        .tr-card-header { padding: 1.5rem 1.75rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; background: #fafafa; }
        .tr-section-title { font-size: 1.1rem; font-weight: 800; margin: 0 0 4px 0; color: var(--tr-text-main); }
        .tr-section-desc { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0; }
        .tr-card-body { padding: 1.75rem; }

        .tr-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; height: fit-content; }
        .badge-blue { background: var(--tr-blue-light); color: var(--tr-blue); }

        /* ── FORM ELEMENTS ── */
        .tr-form-group { margin-bottom: 1.25rem; }
        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .tr-label { display: block; font-size: 0.8rem; font-weight: 800; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; }
        .tr-req { color: var(--tr-danger); }
        .tr-optional { font-weight: 500; text-transform: none; letter-spacing: 0; color: var(--tr-text-muted); }

        .tr-input, .tr-textarea { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--tr-border); border-radius: 10px; font-size: 0.95rem; background: #fcfcfd; transition: 0.2s; font-family: inherit; color: var(--tr-text-main); font-weight: 500; outline: none; }
        .tr-input:focus, .tr-textarea:focus { border-color: var(--tr-blue); background: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .tr-textarea { resize: vertical; min-height: 80px; }
        
        .tr-input-hint { font-size: 0.75rem; color: var(--tr-text-muted); margin-top: 6px; font-weight: 500; }
        .tr-error-msg { color: var(--tr-danger); font-size: 0.75rem; font-weight: 700; margin-top: 4px; }
        .is-invalid { border-color: #fecaca; background: var(--tr-danger-light); }

        /* Prefix Input (Untuk Harga) */
        .tr-input-prefix-group { display: flex; align-items: stretch; max-width: 300px; }
        .tr-input-prefix-group .prefix { display: flex; align-items: center; padding: 0 1rem; background: #f1f5f9; border: 1.5px solid var(--tr-border); border-right: none; border-radius: 10px 0 0 10px; font-size: 0.9rem; font-weight: 800; color: var(--tr-text-muted); }
        .tr-input-prefix-group .tr-input { border-radius: 0 10px 10px 0; }

        /* ── NOTICE BOX ── */
        .tr-notice-box { display: flex; align-items: flex-start; gap: 1rem; padding: 1.25rem; border-radius: 12px; border: 1px dashed; }
        .tr-notice-box.warning { background: var(--tr-warning-light); border-color: #fcd34d; color: #92400e; }
        .notice-icon { flex-shrink: 0; color: var(--tr-warning); margin-top: 2px; }
        .notice-text strong { display: block; font-size: 0.9rem; font-weight: 800; margin-bottom: 4px; color: #78350f; }
        .notice-text p { margin: 0; font-size: 0.85rem; line-height: 1.5; }

        /* ── FLOATING BAR ── */
        .tr-floating-bar { position: fixed; bottom: 0; left: 0; right: 0; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-top: 1px solid var(--tr-border); padding: 1.25rem 2rem; display: flex; justify-content: space-between; align-items: center; z-index: 50; box-shadow: 0 -4px 6px -1px rgba(0,0,0,0.05); }
        .bar-info { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: var(--tr-text-muted); }
        .bar-actions { display: flex; gap: 1rem; }

        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.75rem 1.5rem; border-radius: 10px; font-size: 0.9rem; font-weight: 800; cursor: pointer; transition: 0.2s; border: none; text-decoration: none; }
        .tr-btn-blue { background: var(--tr-blue); color: white; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.25); }
        .tr-btn-blue:hover { background: var(--tr-blue-hover); transform: translateY(-2px); box-shadow: 0 6px 10px -1px rgba(59, 130, 246, 0.3); }
        .tr-btn-ghost { background: transparent; color: var(--tr-text-muted); border: 1px solid var(--tr-border); }
        .tr-btn-ghost:hover { background: #f1f5f9; color: var(--tr-text-main); }

        /* ── UTILS ── */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-weight: 700; letter-spacing: 0.05em; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .tr-grid-2 { grid-template-columns: 1fr; }
            .tr-floating-bar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .bar-info { display: none; /* Hide info on mobile to save space */ }
            .bar-actions { width: 100%; display: grid; grid-template-columns: 1fr 2fr; }
            .tr-btn { width: 100%; padding: 0.85rem; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Adjust main container padding to prevent content hiding behind floating bar
        document.addEventListener("DOMContentLoaded", function() {
            const floatingBar = document.querySelector('.tr-floating-bar');
            const pageWrapper = document.querySelector('.tr-page-wrapper');
            if(floatingBar && pageWrapper) {
                const barHeight = floatingBar.offsetHeight;
                pageWrapper.style.paddingBottom = (barHeight + 30) + 'px';
            }
        });
    </script>
    @endpush
</x-app-layout>