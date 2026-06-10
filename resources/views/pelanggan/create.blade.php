<x-app-layout>
    <x-slot name="header">Tambah Pelanggan</x-slot>

    <div class="cr-page">

        {{-- ─── BREADCRUMB ─── --}}
        <nav class="cr-breadcrumb">
            <a href="{{ route('pelanggan.index') }}" class="cr-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Daftar Pelanggan
            </a>
            <span class="cr-sep">/</span>
            <span class="cr-current">Tambah Baru</span>
        </nav>

        {{-- ─── HEADER ─── --}}
        <div class="cr-header">
            <div class="cr-header-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            </div>
            <div>
                <h1 class="cr-title">Registrasi Pelanggan</h1>
                <p class="cr-subtitle">Data ini digunakan untuk riwayat transaksi POS dan laporan piutang.</p>
            </div>
        </div>

        {{-- ─── ERROR ALERT ─── --}}
        @if($errors->any())
            <div class="cr-alert cr-alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <div class="cr-alert-body">
                    <strong>Terdapat kesalahan input:</strong>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            </div>
        @endif

        {{-- ─── FORM ─── --}}
        <form id="customerForm" action="{{ route('pelanggan.store') }}" method="POST" class="cr-form">
            @csrf

            {{-- Card 1: Identitas --}}
            <div class="cr-card">
                <div class="cr-card-head">
                    <div class="cr-card-head-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div>
                        <h2 class="cr-card-title">Identitas Pelanggan</h2>
                        <p class="cr-card-desc">Informasi kontak utama dan alamat pengiriman.</p>
                    </div>
                </div>
                <div class="cr-card-body">

                    {{-- Nama --}}
                    <div class="cr-group">
                        <label class="cr-label">Nama Pelanggan <span class="cr-req">*</span></label>
                        <input type="text" name="name"
                            class="cr-input @error('name') cr-err @enderror"
                            value="{{ old('name') }}" placeholder="Masukkan nama lengkap..." required autofocus>
                        @error('name')<div class="cr-err-msg">{{ $message }}</div>@enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="cr-group">
                        <label class="cr-label">Kategori Pelanggan <span class="cr-req">*</span></label>
                        <div class="cr-cat-grid">
                            <label class="cr-cat-card">
                                <input type="radio" name="category" value="eceran" {{ old('category', 'eceran') === 'eceran' ? 'checked' : '' }} required>
                                <div class="cr-cat-inner">
                                    <div class="cr-cat-icon cr-cat-teal">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                    </div>
                                    <div class="cr-cat-name">Eceran</div>
                                    <div class="cr-cat-desc">Pelanggan umum/retail</div>
                                </div>
                            </label>
                            <label class="cr-cat-card">
                                <input type="radio" name="category" value="grosir" {{ old('category') === 'grosir' ? 'checked' : '' }} required>
                                <div class="cr-cat-inner">
                                    <div class="cr-cat-icon cr-cat-purple">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                    </div>
                                    <div class="cr-cat-name">Grosir</div>
                                    <div class="cr-cat-desc">Toko/distributor besar</div>
                                </div>
                            </label>
                            <label class="cr-cat-card">
                                <input type="radio" name="category" value="pos" {{ old('category') === 'pos' ? 'checked' : '' }} required>
                                <div class="cr-cat-inner">
                                    <div class="cr-cat-icon cr-cat-blue">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                    </div>
                                    <div class="cr-cat-name">Toko/POS</div>
                                    <div class="cr-cat-desc">Pelanggan dengan sistem POS</div>
                                </div>
                            </label>
                        </div>
                        @error('category')<div class="cr-err-msg">{{ $message }}</div>@enderror
                    </div>

                    {{-- Kontak Grid --}}
                    <div class="cr-grid2">
                        <div class="cr-group">
                            <label class="cr-label">No. Telepon / WhatsApp <span class="cr-opt">(Opsional)</span></label>
                            <input type="tel" name="phone"
                                class="cr-input cr-mono @error('phone') cr-err @enderror"
                                value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                            @error('phone')<div class="cr-err-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="cr-group">
                            <label class="cr-label">Email <span class="cr-opt">(Opsional)</span></label>
                            <input type="email" name="email"
                                class="cr-input @error('email') cr-err @enderror"
                                value="{{ old('email') }}" placeholder="email@contoh.com">
                            @error('email')<div class="cr-err-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="cr-group">
                        <label class="cr-label">Alamat Lengkap <span class="cr-opt">(Opsional)</span></label>
                        <textarea name="address" class="cr-textarea @error('address') cr-err @enderror"
                            rows="3" placeholder="Nama jalan, gedung, RT/RW, kelurahan...">{{ old('address') }}</textarea>
                        @error('address')<div class="cr-err-msg">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            {{-- Card 2: Pengaturan Kredit --}}
            <div class="cr-card">
                <div class="cr-card-head">
                    <div class="cr-card-head-icon cr-head-amber">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    </div>
                    <div>
                        <h2 class="cr-card-title">Pengaturan Kredit (Piutang)</h2>
                        <p class="cr-card-desc">Batas maksimal hutang yang diizinkan untuk pelanggan ini.</p>
                    </div>
                </div>
                <div class="cr-card-body">
                    @if(auth()->user()->role === 'supervisor')
                        <div class="cr-group" style="margin-bottom:0;">
                            <label class="cr-label">Limit Kredit</label>
                            <div class="cr-prefix-group">
                                <span class="cr-prefix">Rp</span>
                                <input type="number" name="credit_limit"
                                    class="cr-input cr-mono @error('credit_limit') cr-err @enderror"
                                    value="{{ old('credit_limit', 0) }}" min="0">
                            </div>
                            <div class="cr-hint">Isi angka 0 jika pelanggan tidak diberikan fasilitas hutang (kredit).</div>
                            @error('credit_limit')<div class="cr-err-msg">{{ $message }}</div>@enderror
                        </div>
                    @else
                        <div class="cr-notice">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            <div>
                                <strong>Akses Terbatas</strong>
                                <p>Pengaturan Limit Kredit hanya dapat diakses dan diubah oleh akun tingkat <b>Supervisor</b>.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card 3: Catatan --}}
            <div class="cr-card">
                <div class="cr-card-head">
                    <div class="cr-card-head-icon cr-head-gray">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    </div>
                    <div>
                        <h2 class="cr-card-title">Catatan Tambahan</h2>
                        <p class="cr-card-desc">Memo internal atau keterangan khusus.</p>
                    </div>
                </div>
                <div class="cr-card-body">
                    <div class="cr-group" style="margin-bottom:0;">
                        <textarea name="notes" class="cr-textarea" rows="2"
                            placeholder="Keterangan khusus mengenai pelanggan ini...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

        </form>

        {{-- ─── FLOATING ACTION BAR ─── --}}
        <div class="cr-fab">
            <div class="cr-fab-info">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Pastikan nama dan nomor kontak sudah benar sebelum disimpan.
            </div>
            <div class="cr-fab-actions">
                <a href="{{ route('pelanggan.index') }}" class="cr-btn cr-btn-ghost">Batalkan</a>
                <button type="submit" form="customerForm" class="cr-btn cr-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Simpan Pelanggan
                </button>
            </div>
        </div>

    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .cr-page { max-width: 740px; margin: 0 auto; padding: 2rem 1.5rem 6rem; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #0f172a; }

        /* Breadcrumb */
        .cr-breadcrumb { display: flex; align-items: center; gap: 6px; margin-bottom: 1.25rem; font-size: 0.85rem; }
        .cr-back { display: flex; align-items: center; gap: 4px; text-decoration: none; color: #64748b; font-weight: 700; transition: color 0.15s; }
        .cr-back:hover { color: #3b82f6; }
        .cr-sep { color: #cbd5e1; }
        .cr-current { font-weight: 600; color: #0f172a; }

        /* Header */
        .cr-header { display: flex; align-items: center; gap: 14px; margin-bottom: 1.75rem; }
        .cr-header-icon { width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cr-title { font-size: 1.5rem; font-weight: 900; margin: 0; letter-spacing: -0.02em; }
        .cr-subtitle { font-size: 0.85rem; color: #64748b; margin: 4px 0 0; font-weight: 500; }

        /* Alert */
        .cr-alert { display: flex; align-items: flex-start; gap: 10px; padding: 0.85rem 1.1rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.85rem; }
        .cr-alert svg { flex-shrink: 0; margin-top: 2px; }
        .cr-alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .cr-alert-body strong { font-weight: 800; display: block; margin-bottom: 4px; }
        .cr-alert-body ul { margin: 0; padding-left: 1.15rem; }
        .cr-alert-body li { margin-bottom: 2px; }

        /* Form */
        .cr-form { display: flex; flex-direction: column; gap: 1.25rem; }

        /* Card */
        .cr-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
        .cr-card-head { padding: 1.15rem 1.35rem; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 12px; }
        .cr-card-head-icon { width: 34px; height: 34px; border-radius: 8px; background: #dbeafe; color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cr-head-amber { background: #fef3c7; color: #d97706; }
        .cr-head-gray { background: #f1f5f9; color: #64748b; }
        .cr-card-title { font-size: 0.95rem; font-weight: 800; margin: 0 0 2px; }
        .cr-card-desc { font-size: 0.78rem; color: #64748b; margin: 0; }
        .cr-card-body { padding: 1.35rem; }

        /* Form Group */
        .cr-group { margin-bottom: 1.15rem; }
        .cr-label { display: block; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #0f172a; margin-bottom: 0.45rem; }
        .cr-req { color: #ef4444; }
        .cr-opt { font-weight: 500; text-transform: none; letter-spacing: 0; color: #94a3b8; }

        .cr-input, .cr-textarea { width: 100%; padding: 0.7rem 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: #fafbfc; font-family: inherit; font-weight: 500; color: #0f172a; outline: none; transition: border-color 0.15s, box-shadow 0.15s, background 0.15s; }
        .cr-input:focus, .cr-textarea:focus { border-color: #3b82f6; background: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        .cr-textarea { resize: vertical; min-height: 72px; }
        .cr-err { border-color: #fca5a5 !important; background: #fef2f2 !important; }
        .cr-err-msg { color: #dc2626; font-size: 0.72rem; font-weight: 700; margin-top: 4px; }
        .cr-hint { font-size: 0.72rem; color: #94a3b8; margin-top: 5px; font-weight: 500; }
        .cr-mono { font-family: ui-monospace, 'Cascadia Code', Consolas, monospace; font-weight: 700; letter-spacing: 0.04em; }

        .cr-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* Prefix Input */
        .cr-prefix-group { display: flex; align-items: stretch; max-width: 280px; }
        .cr-prefix { display: flex; align-items: center; padding: 0 0.85rem; background: #f1f5f9; border: 1.5px solid #e2e8f0; border-right: none; border-radius: 8px 0 0 8px; font-size: 0.85rem; font-weight: 800; color: #64748b; }
        .cr-prefix-group .cr-input { border-radius: 0 8px 8px 0; }

        /* Category Cards */
        .cr-cat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.85rem; }
        .cr-cat-card { cursor: pointer; position: relative; }
        .cr-cat-card input { position: absolute; opacity: 0; pointer-events: none; }
        .cr-cat-inner { padding: 1.1rem 0.75rem; border: 2px solid #e2e8f0; border-radius: 10px; text-align: center; transition: all 0.15s; background: #fff; }
        .cr-cat-card:hover .cr-cat-inner { border-color: #cbd5e1; background: #fafafa; }
        .cr-cat-card input:checked + .cr-cat-inner { border-color: #3b82f6; background: #eff6ff; }
        .cr-cat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.6rem; }
        .cr-cat-teal { background: #ccfbf1; color: #0f766e; }
        .cr-cat-purple { background: #f3e8ff; color: #7c3aed; }
        .cr-cat-blue { background: #dbeafe; color: #3b82f6; }
        .cr-cat-name { font-weight: 800; font-size: 0.88rem; color: #0f172a; margin-bottom: 2px; }
        .cr-cat-desc { font-size: 0.68rem; color: #94a3b8; font-weight: 500; }

        /* Notice */
        .cr-notice { display: flex; align-items: flex-start; gap: 10px; padding: 1rem 1.15rem; border-radius: 10px; background: #fffbeb; border: 1px dashed #fcd34d; color: #92400e; font-size: 0.85rem; }
        .cr-notice svg { flex-shrink: 0; color: #d97706; margin-top: 2px; }
        .cr-notice strong { display: block; font-weight: 800; margin-bottom: 3px; color: #78350f; font-size: 0.85rem; }
        .cr-notice p { margin: 0; font-size: 0.8rem; line-height: 1.45; }

        /* Floating Action Bar */
        .cr-fab { position: fixed; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); border-top: 1px solid #e2e8f0; padding: 0.85rem 2rem; display: flex; justify-content: space-between; align-items: center; z-index: 50; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); }
        .cr-fab-info { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; font-weight: 600; color: #94a3b8; }
        .cr-fab-actions { display: flex; gap: 0.75rem; }

        .cr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.65rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.15s; border: 1px solid transparent; text-decoration: none; font-family: inherit; }
        .cr-btn-primary { background: #3b82f6; color: #fff; box-shadow: 0 2px 6px rgba(59,130,246,0.2); }
        .cr-btn-primary:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(59,130,246,0.25); }
        .cr-btn-ghost { background: #fff; color: #64748b; border-color: #e2e8f0; }
        .cr-btn-ghost:hover { background: #f8fafc; color: #0f172a; }

        /* Responsive */
        @@media (max-width: 768px) {
            .cr-grid2 { grid-template-columns: 1fr; }
            .cr-cat-grid { grid-template-columns: 1fr; }
            .cr-fab { flex-direction: column; gap: 0.75rem; padding: 0.85rem 1rem; }
            .cr-fab-info { display: none; }
            .cr-fab-actions { width: 100%; display: grid; grid-template-columns: 1fr 1.5fr; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var fab = document.querySelector('.cr-fab');
            var page = document.querySelector('.cr-page');
            if (fab && page) {
                page.style.paddingBottom = (fab.offsetHeight + 40) + 'px';
            }
        });
    </script>
    @endpush
</x-app-layout>
