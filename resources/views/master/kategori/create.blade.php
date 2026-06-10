<x-app-layout>
    <x-slot name="header">Tambah Kategori</x-slot>
    <style>
        .kg-form-page{max-width:640px;margin:0 auto;padding:1.5rem;}
        .kg-breadcrumb{display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#94a3b8;margin-bottom:1.25rem;}
        .kg-breadcrumb a{color:#6366f1;text-decoration:none;font-weight:500;}
        .kg-breadcrumb a:hover{text-decoration:underline;}
        .kg-form-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;}
        .kg-form-header{display:flex;align-items:center;gap:0.875rem;padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;}
        .kg-form-icon{width:42px;height:42px;border-radius:11px;background:#eef2ff;color:#6366f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .kg-form-title{font-size:1rem;font-weight:600;color:#1e293b;margin:0;}
        .kg-form-sub{font-size:0.75rem;color:#94a3b8;margin:0.125rem 0 0;}
        .kg-form-body{padding:1.5rem;}
        .kg-field{margin-bottom:1.125rem;}
        .kg-field:last-child{margin-bottom:0;}
        .kg-label{display:block;font-size:0.8125rem;font-weight:600;color:#334155;margin-bottom:0.375rem;}
        .kg-label .req{color:#e11d48;}
        .kg-input{width:100%;height:40px;border:1px solid #e2e8f0;border-radius:8px;padding:0 0.75rem;font-size:0.8125rem;outline:none;transition:border-color .2s;box-sizing:border-box;}
        .kg-input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);}
        .kg-input.error{border-color:#e11d48;}
        .kg-textarea{width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:0.625rem 0.75rem;font-size:0.8125rem;outline:none;resize:vertical;transition:border-color .2s;box-sizing:border-box;font-family:inherit;}
        .kg-textarea:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);}
        .kg-error{display:block;font-size:0.75rem;color:#e11d48;margin-top:0.25rem;}
        .kg-hint{display:flex;align-items:center;gap:0.375rem;font-size:0.75rem;color:#94a3b8;margin-top:0.375rem;}
        .kg-footer{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.5rem;border-top:1px solid #f1f5f9;background:#f8fafc;}
        .kg-footer-hint{font-size:0.75rem;color:#94a3b8;display:flex;align-items:center;gap:0.375rem;}
        .kg-footer-actions{display:flex;gap:0.5rem;}
    </style>

    <div class="kg-form-page">
        <div class="kg-breadcrumb">
            <a href="{{ route('master.kategori') }}">Kategori</a>
            <span>›</span>
            <span>Tambah Kategori</span>
        </div>

        <form method="POST" action="{{ route('master.kategori.store') }}">
            @csrf
            <div class="kg-form-card">
                <div class="kg-form-header">
                    <div class="kg-form-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <div class="kg-form-title">Informasi Kategori</div>
                        <div class="kg-form-sub">Isi detail kategori baru untuk pengelompokan produk</div>
                    </div>
                </div>

                <div class="kg-form-body">
                    <div class="kg-field">
                        <label class="kg-label">Nama Kategori <span class="req">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="kg-input @error('name') error @enderror"
                            placeholder="Contoh: Makanan, Minuman, Elektronik...">
                        @error('name') <span class="kg-error">⚠ {{ $message }}</span> @enderror
                        <div class="kg-hint">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Gunakan nama yang singkat dan jelas
                        </div>
                    </div>
                    <div class="kg-field">
                        <label class="kg-label">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="kg-textarea @error('description') error @enderror"
                            placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
                        @error('description') <span class="kg-error">⚠ {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="kg-footer">
                    <div class="kg-footer-hint">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Kategori digunakan untuk mengelompokkan produk
                    </div>
                    <div class="kg-footer-actions">
                        <a href="{{ route('master.kategori') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">💾 Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
